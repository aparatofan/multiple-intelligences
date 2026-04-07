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

    // Download PDF — jsPDF + html2canvas, fully rasterised, no external URLs.
    $(document).on('click', '#mi-download-pdf', function() {
        var btn = $(this);
        var origText = btn.text();
        btn.prop('disabled', true).text(state.lang === 'pl' ? 'Generowanie PDF...' : 'Generating PDF...');

        var source = document.getElementById('mi-report');

        // Clone the report so we can strip URLs without affecting the live page.
        var clone = source.cloneNode(true);
        clone.id = 'mi-report-pdf-clone';
        clone.style.position = 'absolute';
        clone.style.left = '-9999px';
        clone.style.top = '0';
        clone.style.width = source.offsetWidth + 'px';
        clone.style.background = '#ffffff';

        // Convert the Chart.js <canvas> to a static <img> in the clone,
        // because html2canvas cannot reliably capture other canvas elements.
        var chartCanvas = source.querySelector('canvas');
        var cloneCanvas = clone.querySelector('canvas');
        if (chartCanvas && cloneCanvas) {
            try {
                var chartImg = document.createElement('img');
                chartImg.src = chartCanvas.toDataURL('image/png');
                chartImg.style.width = '100%';
                chartImg.style.height = 'auto';
                cloneCanvas.parentNode.replaceChild(chartImg, cloneCanvas);
            } catch (e) {
                // leave the canvas as-is if toDataURL fails
            }
        }

        // Convert external icon images to inline data URIs.
        // With CORS headers on the server and crossorigin="anonymous" on the
        // original <img>, the browser allows reading pixel data via canvas.
        // Iterate in reverse since the NodeList is live.
        var imgs = clone.querySelectorAll('img');
        var sourceImgs = source.querySelectorAll('img');
        for (var i = imgs.length - 1; i >= 0; i--) {
            var imgSrc = imgs[i].getAttribute('src') || '';
            if (imgSrc.indexOf('data:') === 0) {
                continue; // already a data URI (e.g. the chart)
            }
            try {
                var origImg = sourceImgs[i];
                if (origImg && origImg.complete && origImg.naturalWidth > 0) {
                    var c = document.createElement('canvas');
                    c.width = origImg.naturalWidth;
                    c.height = origImg.naturalHeight;
                    c.getContext('2d').drawImage(origImg, 0, 0);
                    imgs[i].src = c.toDataURL('image/png');
                } else {
                    imgs[i].parentNode.removeChild(imgs[i]);
                }
            } catch (e) {
                // CORS still blocked — remove the image so html2canvas won't hang.
                imgs[i].parentNode.removeChild(imgs[i]);
            }
        }

        // Strip any remaining anchor hrefs.
        var links = clone.querySelectorAll('a[href]');
        for (var j = 0; j < links.length; j++) {
            links[j].removeAttribute('href');
        }

        document.body.appendChild(clone);

        // Safety timeout — if html2canvas takes longer than 15 seconds, abort.
        var timedOut = false;
        var timeout = setTimeout(function() {
            timedOut = true;
            if (clone.parentNode) {
                document.body.removeChild(clone);
            }
            btn.prop('disabled', false).text(origText);
        }, 15000);

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

            // Set metadata to avoid AV false positives.
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
                // Render across multiple pages by slicing the canvas.
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
                    var sliceCtx = sliceCanvas.getContext('2d');
                    sliceCtx.drawImage(canvas, 0, srcY, canvas.width, srcH, 0, 0, canvas.width, srcH);

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
