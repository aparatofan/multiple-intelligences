(function($) {
    'use strict';

    var state = {
        lang: 'pl',
        userName: '',
        userEmail: '',
        scores: null,
        date: '',
        chart: null
    };

    /**
     * Shuffle an array (Fisher-Yates).
     */
    function shuffle(arr) {
        var copy = arr.slice();
        for (var i = copy.length - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var tmp = copy[i];
            copy[i] = copy[j];
            copy[j] = tmp;
        }
        return copy;
    }

    /**
     * Update UI text based on selected language.
     */
    function updateLanguage(lang) {
        state.lang = lang;
        $('[data-' + lang + ']').each(function() {
            var text = $(this).attr('data-' + lang);
            if (text) {
                if (this.tagName === 'INPUT' || this.tagName === 'BUTTON') {
                    $(this).val(text).text(text);
                } else {
                    $(this).text(text);
                }
            }
        });

        // Update GDPR text.
        var gdprData = JSON.parse($('#mi-gdpr-data').text());
        $('#mi-consent-text').text(gdprData[lang] || gdprData['pl']);
    }

    /**
     * Render questions in the survey container.
     */
    function renderQuestions(lang) {
        var dataEl = document.getElementById('mi-questions-data-' + lang);
        if (!dataEl) return;

        var questions = JSON.parse(dataEl.textContent);
        var shuffled = shuffle(questions);
        var container = $('#mi-questions-container');
        container.empty();

        $.each(shuffled, function(i, q) {
            var id = 'mi-q-' + q.id;
            var item = $('<div class="mi-question-item"></div>');
            var checkbox = $('<input type="checkbox">').attr({
                type: 'checkbox',
                id: id,
                name: 'answers[]',
                value: q.id
            });
            var label = $('<label></label>').attr('for', id).text(q.text);
            item.append(checkbox, label);
            container.append(item);
        });

        updateCheckedCount();
    }

    /**
     * Update the checked counter.
     */
    function updateCheckedCount() {
        var count = $('#mi-questions-container input[type="checkbox"]:checked').length;
        $('#mi-checked-count').text(count);
    }

    /**
     * Navigate between steps.
     */
    function goToStep(stepId) {
        $('.mi-step').hide();
        $('#' + stepId).show();
        // Scroll to top of wrapper.
        $('html, body').animate({
            scrollTop: $('#mi-survey-wrapper').offset().top - 20
        }, 300);
    }

    /**
     * Render the report chart and descriptions.
     */
    function renderReport(data) {
        state.scores = data.scores;
        state.date = data.date;

        var lang = data.language;
        var titleText = lang === 'pl'
            ? data.userName + ', to jest twój Profil Inteligencji Wielorakich'
            : data.userName + ', this is your Profile of Multiple Intelligences';
        var dateLabel = lang === 'pl' ? 'Data ankiety' : 'Survey date';
        var dynamicNote = lang === 'pl'
            ? 'Profil MI jest dynamiczny. Zmienia się w czasie.'
            : 'The MI profile is dynamic. It changes over time.';

        $('#mi-report-title').text(titleText);
        $('#mi-report-date').text(dateLabel + ': ' + data.date);
        $('#mi-dynamic-note').text(dynamicNote);

        // Update button text.
        $('#mi-download-pdf').text(lang === 'pl' ? 'Pobierz PDF' : 'Download PDF');
        $('#mi-send-email').text(lang === 'pl' ? 'Wyślij na email' : 'Send to email');

        // Build chart data — sorted by score (highest first, matching data.scores order).
        var chartLabels = [];
        var chartData = [];
        var chartIcons = [];

        $.each(data.scores, function(type, score) {
            chartLabels.push(data.labels[type]);
            chartData.push(score);
            chartIcons.push(data.iconUrls[type]);
        });

        // Render Chart.js.
        var ctx = document.getElementById('mi-chart');
        if (state.chart) {
            state.chart.destroy();
        }

        // Generate slight opacity variations for visual interest.
        var colors = chartData.map(function(_, i) {
            var opacity = 1 - (i * 0.07);
            return 'rgba(232, 163, 23, ' + Math.max(opacity, 0.5) + ')';
        });

        state.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: colors,
                    borderColor: '#e8a317',
                    borderWidth: 1,
                    borderRadius: 4,
                    barThickness: 30
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        min: 0,
                        max: 10,
                        ticks: {
                            stepSize: 1,
                            font: { size: 13 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.06)'
                        }
                    },
                    y: {
                        ticks: {
                            font: { size: 13 }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.x + ' / 10';
                            }
                        }
                    }
                }
            }
        });

        // Render descriptions.
        var descriptionsContainer = $('#mi-descriptions');
        descriptionsContainer.empty();

        $.each(data.scores, function(type, score) {
            var label = data.labels[type];
            var iconUrl = data.iconUrls[type];
            var desc = data.descriptions[type];

            var item = $('<div class="mi-description-item"></div>');
            var header = $('<div class="mi-description-header"></div>');
            header.append('<img class="mi-description-icon" src="' + iconUrl + '" alt="" loading="lazy" crossorigin="anonymous">');
            header.append(
                '<div>' +
                '<h3 class="mi-description-title">' + escapeHtml(label) + '</h3>' +
                '<span class="mi-description-score">' + score + ' / 10</span>' +
                '</div>'
            );
            item.append(header);
            item.append(desc);
            descriptionsContainer.append(item);
        });

        goToStep('mi-step-report');
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    // =========================================================================
    // Event handlers
    // =========================================================================

    // Language selector change.
    $(document).on('change', '#mi-language', function() {
        updateLanguage($(this).val());
    });

    // Registration form submit.
    $(document).on('submit', '#mi-register-form', function(e) {
        e.preventDefault();
        var name = $.trim($('#mi-user-name').val());
        var email = $.trim($('#mi-user-email').val());
        var consent = $('#mi-consent').is(':checked');
        var lang = $('#mi-language').val();
        var errorEl = $('#mi-register-error');

        errorEl.text('');

        if (!name || !email) {
            errorEl.text(lang === 'pl' ? 'Proszę wypełnić wszystkie wymagane pola.' : 'Please fill in all required fields.');
            return;
        }

        if (!isValidEmail(email)) {
            errorEl.text(lang === 'pl' ? 'Proszę podać poprawny adres email.' : 'Please provide a valid email address.');
            return;
        }

        if (!consent) {
            errorEl.text(lang === 'pl' ? 'Proszę zaakceptować zgodę na przetwarzanie danych.' : 'Please accept the consent checkbox.');
            return;
        }

        state.userName = name;
        state.userEmail = email;
        state.lang = lang;

        renderQuestions(lang);
        updateLanguage(lang);
        goToStep('mi-step-survey');
    });

    // Checkbox change — update count and highlight.
    $(document).on('change', '#mi-questions-container input[type="checkbox"]', function() {
        $(this).closest('.mi-question-item').toggleClass('mi-checked', this.checked);
        updateCheckedCount();
    });

    // Survey form submit.
    $(document).on('submit', '#mi-survey-form', function(e) {
        e.preventDefault();
        var answers = [];
        $('#mi-questions-container input[type="checkbox"]:checked').each(function() {
            answers.push($(this).val());
        });

        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).prepend('<span class="mi-loading"></span>');

        $.ajax({
            url: miSurvey.ajaxUrl,
            type: 'POST',
            data: {
                action: 'mi_survey_submit',
                nonce: miSurvey.nonce,
                user_name: state.userName,
                user_email: state.userEmail,
                language: state.lang,
                answers: answers,
                consent: '1'
            },
            success: function(response) {
                submitBtn.prop('disabled', false).find('.mi-loading').remove();
                if (response.success) {
                    renderReport(response.data);
                } else {
                    $('#mi-survey-error').text(response.data ? response.data.message : miSurvey.i18n.errorSubmit);
                }
            },
            error: function() {
                submitBtn.prop('disabled', false).find('.mi-loading').remove();
                $('#mi-survey-error').text(miSurvey.i18n.errorSubmit);
            }
        });
    });

    /**
     * Load a PNG image and return a data URI via a Promise.
     * Uses a fresh <img> with crossorigin="anonymous" to avoid CORS taint.
     */
    function loadPngAsDataUri(url, size) {
        return new Promise(function(resolve) {
            var img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function() {
                try {
                    var c = document.createElement('canvas');
                    c.width = size;
                    c.height = size;
                    c.getContext('2d').drawImage(img, 0, 0, size, size);
                    resolve(c.toDataURL('image/png'));
                } catch (e) {
                    resolve(null);
                }
            };
            img.onerror = function() {
                resolve(null);
            };
            img.src = url;
        });
    }

    // Download PDF — jsPDF + html2canvas, fully rasterised, no external URLs.
    $(document).on('click', '#mi-download-pdf', function() {
        var btn = $(this);
        var origText = btn.text();
        btn.prop('disabled', true).text(state.lang === 'pl' ? 'Generowanie PDF...' : 'Generating PDF...');

        // Pre-load all icon PNGs as data URIs before building the clone.
        // SVGs don't render properly in html2canvas, so we always use the PNG versions.
        var iconSlugs = ['language', 'logic', 'spatial', 'music', 'body', 'interpersonal', 'intrapersonal', 'nature'];
        var pngBaseUrl = 'https://polecanynauczycielangielskiego.pl/wp-content/uploads/2026/04/MI_';
        var iconPromises = {};

        iconSlugs.forEach(function(slug) {
            iconPromises[slug] = loadPngAsDataUri(pngBaseUrl + slug + '.png', 300);
        });

        // Build a map of SVG URL → slug for matching.
        var svgToSlug = {};
        iconSlugs.forEach(function(slug) {
            // Match both .svg and _clean.svg variants.
            svgToSlug[pngBaseUrl + slug + '.svg'] = slug;
            if (slug === 'body') {
                svgToSlug[pngBaseUrl + 'body_clean.svg'] = slug;
            }
            svgToSlug[pngBaseUrl + slug + '.png'] = slug;
        });

        Promise.all(iconSlugs.map(function(slug) { return iconPromises[slug]; })).then(function(dataUris) {
            // Build slug → dataUri map.
            var iconDataMap = {};
            iconSlugs.forEach(function(slug, idx) {
                iconDataMap[slug] = dataUris[idx];
            });

            var source = document.getElementById('mi-report');
            var clone = source.cloneNode(true);
            clone.id = 'mi-report-pdf-clone';
            clone.style.position = 'absolute';
            clone.style.left = '-9999px';
            clone.style.top = '0';
            clone.style.width = source.offsetWidth + 'px';
            clone.style.background = '#ffffff';

            // Convert the Chart.js <canvas> to a static <img>.
            var chartCanvas = source.querySelector('canvas');
            var cloneCanvas = clone.querySelector('canvas');
            if (chartCanvas && cloneCanvas) {
                try {
                    var chartImg = document.createElement('img');
                    chartImg.src = chartCanvas.toDataURL('image/png');
                    chartImg.style.width = '100%';
                    chartImg.style.height = 'auto';
                    cloneCanvas.parentNode.replaceChild(chartImg, cloneCanvas);
                } catch (e) {}
            }

            // Replace icon images with pre-loaded PNG data URIs.
            var imgs = clone.querySelectorAll('img.mi-description-icon');
            for (var i = 0; i < imgs.length; i++) {
                var imgSrc = imgs[i].getAttribute('src') || '';
                var matched = false;
                for (var url in svgToSlug) {
                    if (imgSrc === url) {
                        var dataUri = iconDataMap[svgToSlug[url]];
                        if (dataUri) {
                            imgs[i].src = dataUri;
                            imgs[i].style.width = '150px';
                            imgs[i].style.height = '150px';
                            imgs[i].removeAttribute('loading');
                            imgs[i].removeAttribute('crossorigin');
                            matched = true;
                        }
                        break;
                    }
                }
                if (!matched) {
                    imgs[i].parentNode.removeChild(imgs[i]);
                }
            }

            // Strip any remaining anchor hrefs.
            var links = clone.querySelectorAll('a[href]');
            for (var j = 0; j < links.length; j++) {
                links[j].removeAttribute('href');
            }

            document.body.appendChild(clone);

            // Safety timeout.
            var timedOut = false;
            var timeout = setTimeout(function() {
                timedOut = true;
                if (clone.parentNode) {
                    document.body.removeChild(clone);
                }
                btn.prop('disabled', false).text(origText);
            }, 20000);

            html2canvas(clone, {
                scale: 2,
                useCORS: false,
                allowTaint: false,
                backgroundColor: '#ffffff',
                logging: false,
                imageTimeout: 0,
                foreignObjectRendering: false
            }).then(function(canvas) {
                clearTimeout(timeout);
                if (timedOut) return;
                if (clone.parentNode) {
                    document.body.removeChild(clone);
                }

                var imgData = canvas.toDataURL('image/jpeg', 0.95);
                var pdf = new jspdf.jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });

                pdf.setProperties({
                    title: 'MI Survey Report',
                    author: 'polecanynauczycielangielskiego.pl',
                    creator: 'MI Survey Plugin',
                    subject: 'Multiple Intelligences Profile'
                });

                var pageWidth = pdf.internal.pageSize.getWidth();
                var pageHeight = pdf.internal.pageSize.getHeight();
                var margin = 10;
                var contentWidth = pageWidth - (margin * 2);
                var imgAspect = canvas.height / canvas.width;
                var imgHeight = contentWidth * imgAspect;
                var availableHeight = pageHeight - (margin * 2);

                if (imgHeight <= availableHeight) {
                    pdf.addImage(imgData, 'JPEG', margin, margin, contentWidth, imgHeight);
                } else {
                    var pxPerMm = canvas.width / contentWidth;
                    var sliceHeightPx = Math.floor(availableHeight * pxPerMm);
                    var pages = Math.ceil(canvas.height / sliceHeightPx);

                    for (var p = 0; p < pages; p++) {
                        if (p > 0) {
                            pdf.addPage();
                        }
                        var srcY = p * sliceHeightPx;
                        var srcH = Math.min(sliceHeightPx, canvas.height - srcY);

                        var sliceCanvas = document.createElement('canvas');
                        sliceCanvas.width = canvas.width;
                        sliceCanvas.height = srcH;
                        sliceCanvas.getContext('2d').drawImage(canvas, 0, srcY, canvas.width, srcH, 0, 0, canvas.width, srcH);

                        var sliceData = sliceCanvas.toDataURL('image/jpeg', 0.95);
                        var sliceH = (srcH / pxPerMm);
                        pdf.addImage(sliceData, 'JPEG', margin, margin, contentWidth, sliceH);
                    }
                }

                pdf.save('MI-Survey-Report.pdf');
                btn.prop('disabled', false).text(origText);
            }).catch(function() {
                clearTimeout(timeout);
                if (!timedOut) {
                    if (clone.parentNode) {
                        document.body.removeChild(clone);
                    }
                    btn.prop('disabled', false).text(origText);
                }
            });
        });
    });

    // Send email.
    $(document).on('click', '#mi-send-email', function() {
        var btn = $(this);
        var statusEl = $('#mi-email-status');
        var origText = btn.text();
        btn.prop('disabled', true).text(state.lang === 'pl' ? 'Wysyłanie...' : 'Sending...');
        statusEl.text('').removeClass('mi-success mi-error');

        $.ajax({
            url: miSurvey.ajaxUrl,
            type: 'POST',
            data: {
                action: 'mi_survey_send_email',
                nonce: miSurvey.nonce,
                user_name: state.userName,
                user_email: state.userEmail,
                language: state.lang,
                scores: JSON.stringify(state.scores),
                date: state.date
            },
            success: function(response) {
                btn.prop('disabled', false).text(origText);
                if (response.success) {
                    statusEl.addClass('mi-success').text(
                        state.lang === 'pl' ? 'Raport wysłany na Twój email!' : 'Report sent to your email!'
                    );
                } else {
                    statusEl.addClass('mi-error').text(
                        state.lang === 'pl' ? 'Nie udało się wysłać emaila. Spróbuj ponownie.' : 'Failed to send email. Please try again.'
                    );
                }
            },
            error: function() {
                btn.prop('disabled', false).text(origText);
                statusEl.addClass('mi-error').text(
                    state.lang === 'pl' ? 'Nie udało się wysłać emaila. Spróbuj ponownie.' : 'Failed to send email. Please try again.'
                );
            }
        });
    });

    /**
     * Simple email validation.
     */
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

})(jQuery);
