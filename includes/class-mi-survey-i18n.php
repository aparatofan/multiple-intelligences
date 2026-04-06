<?php
class MI_Survey_I18n {

    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'mi-survey',
            false,
            dirname( MI_SURVEY_PLUGIN_BASENAME ) . '/languages/'
        );
    }
}
