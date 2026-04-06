<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

$gdpr_en    = get_option( 'mi_survey_gdpr_text_en', 'I consent to the processing of my personal data (name and email) for the purpose of this survey. Your data will be stored securely and will not be shared with third parties.' );
$gdpr_pl    = get_option( 'mi_survey_gdpr_text_pl', 'Wyrażam zgodę na przetwarzanie moich danych osobowych (imię i adres email) w celu przeprowadzenia tej ankiety. Twoje dane będą przechowywane w sposób bezpieczny i nie będą udostępniane osobom trzecim.' );
$questions_pl = MI_Survey_Questions::get_questions( 'pl' );
$questions_en = MI_Survey_Questions::get_questions( 'en' );
?>
<div id="mi-survey-wrapper" class="mi-survey-wrapper">

    <!-- STEP 1: Registration -->
    <div id="mi-step-register" class="mi-step mi-step-active">
        <h2 class="mi-survey-title" data-pl="Ankieta Inteligencji Wielorakich" data-en="Multiple Intelligences Survey">Ankieta Inteligencji Wielorakich</h2>
        <p class="mi-survey-subtitle" data-pl="Odkryj swój unikalny profil inteligencji" data-en="Discover your unique intelligence profile">Odkryj swój unikalny profil inteligencji</p>

        <form id="mi-register-form" class="mi-form" novalidate>
            <div class="mi-form-group">
                <label for="mi-user-name" data-pl="Imię" data-en="Name">Imię</label>
                <input type="text" id="mi-user-name" name="user_name" required autocomplete="given-name">
            </div>
            <div class="mi-form-group">
                <label for="mi-user-email" data-pl="Adres email" data-en="Email address">Adres email</label>
                <input type="email" id="mi-user-email" name="user_email" required autocomplete="email">
            </div>
            <div class="mi-form-group">
                <label for="mi-language" data-pl="Język ankiety" data-en="Survey language">Język ankiety</label>
                <select id="mi-language" name="language">
                    <option value="pl">Polski</option>
                    <option value="en">English</option>
                </select>
            </div>
            <div class="mi-form-group mi-consent-group">
                <label class="mi-checkbox-label">
                    <input type="checkbox" id="mi-consent" name="consent" value="1" required>
                    <span id="mi-consent-text" class="mi-consent-text"><?php echo esc_html( $gdpr_pl ); ?></span>
                </label>
            </div>
            <div class="mi-form-error" id="mi-register-error" role="alert" aria-live="polite"></div>
            <button type="submit" class="mi-btn mi-btn-primary" data-pl="Rozpocznij ankietę" data-en="Start Survey">Rozpocznij ankietę</button>
        </form>
    </div>

    <!-- STEP 2: Survey Questions -->
    <div id="mi-step-survey" class="mi-step" style="display:none;">
        <h2 class="mi-survey-title" id="mi-survey-instruction" data-pl="Zaznacz zdania, które do Ciebie pasują." data-en="Mark the sentences which agree with you.">Zaznacz zdania, które do Ciebie pasują.</h2>
        <div class="mi-progress">
            <span id="mi-checked-count">0</span> / 80
            <span data-pl="zaznaczono" data-en="checked">zaznaczono</span>
        </div>
        <form id="mi-survey-form" class="mi-form">
            <div id="mi-questions-container" class="mi-questions-container">
                <!-- Questions injected by JS -->
            </div>
            <button type="submit" class="mi-btn mi-btn-primary" data-pl="Wyślij odpowiedzi" data-en="Submit answers">Wyślij odpowiedzi</button>
            <div class="mi-form-error" id="mi-survey-error" role="alert" aria-live="polite"></div>
        </form>
    </div>

    <!-- Questions data for JS -->
    <script type="application/json" id="mi-questions-data-pl"><?php echo wp_json_encode( $questions_pl ); ?></script>
    <script type="application/json" id="mi-questions-data-en"><?php echo wp_json_encode( $questions_en ); ?></script>
    <script type="application/json" id="mi-gdpr-data"><?php echo wp_json_encode( array( 'pl' => $gdpr_pl, 'en' => $gdpr_en ) ); ?></script>
</div>
