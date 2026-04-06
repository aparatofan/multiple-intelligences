<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<!-- STEP 3: Report -->
<div id="mi-step-report" class="mi-step" style="display:none;">
    <div id="mi-report" class="mi-report">
        <h2 class="mi-report-title" id="mi-report-title"></h2>
        <p class="mi-report-date" id="mi-report-date"></p>

        <div class="mi-chart-container">
            <canvas id="mi-chart" width="700" height="400" role="img" aria-label="Multiple Intelligences bar chart"></canvas>
        </div>

        <p class="mi-dynamic-note" id="mi-dynamic-note"></p>

        <div id="mi-descriptions" class="mi-descriptions"></div>
    </div>

    <div class="mi-report-actions">
        <button type="button" id="mi-download-pdf" class="mi-btn mi-btn-primary" data-pl="Pobierz PDF" data-en="Download PDF">
            Pobierz PDF
        </button>
        <button type="button" id="mi-send-email" class="mi-btn mi-btn-secondary" data-pl="Wyślij na email" data-en="Send to email">
            Wyślij na email
        </button>
    </div>
    <div id="mi-email-status" class="mi-email-status" role="alert" aria-live="polite"></div>
</div>
