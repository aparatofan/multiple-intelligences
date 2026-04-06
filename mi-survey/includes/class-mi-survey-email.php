<?php
class MI_Survey_Email {

    /**
     * Send the report email.
     *
     * @param string $to     Recipient email.
     * @param string $name   Recipient name.
     * @param array  $scores Scores array.
     * @param string $lang   Language code.
     * @param string $date   Survey date.
     * @return bool
     */
    public static function send_report( $to, $name, $scores, $lang, $date ) {
        $labels  = MI_Survey_Questions::get_type_labels( $lang );
        $sorted  = MI_Survey_Scoring::sort_by_score( $scores );
        $subject = 'pl' === $lang
            ? 'Twój profil inteligencji wielorakich — MI Survey'
            : 'Your Multiple Intelligences Profile — MI Survey';

        $html = self::build_html( $name, $sorted, $labels, $lang, $date );

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: MI Survey <noreply@polecanynauczycielangielskiego.pl>',
        );

        return wp_mail( $to, $subject, $html, $headers );
    }

    /**
     * Build the HTML email body.
     */
    private static function build_html( $name, $sorted_scores, $labels, $lang, $date ) {
        $greeting = 'pl' === $lang
            ? 'Cześć ' . esc_html( $name ) . ','
            : 'Hello ' . esc_html( $name ) . ',';

        $intro = 'pl' === $lang
            ? 'Oto Twój profil inteligencji wielorakich z ankiety przeprowadzonej dnia ' . esc_html( $date ) . '.'
            : 'Here is your Multiple Intelligences profile from the survey taken on ' . esc_html( $date ) . '.';

        $dynamic_note = 'pl' === $lang
            ? 'Profil MI jest dynamiczny. Zmienia się w czasie.'
            : 'The MI profile is dynamic. It changes over time.';

        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;color:#333;">';
        $html .= '<h2 style="color:#e8a317;">' . ( 'pl' === $lang ? 'Profil Inteligencji Wielorakich' : 'Multiple Intelligences Profile' ) . '</h2>';
        $html .= '<p>' . $greeting . '</p>';
        $html .= '<p>' . $intro . '</p>';

        // Table-based chart fallback for email clients.
        $html .= '<table style="width:100%;border-collapse:collapse;margin:20px 0;">';
        foreach ( $sorted_scores as $type => $score ) {
            $label      = isset( $labels[ $type ] ) ? $labels[ $type ] : $type;
            $bar_width  = max( ( $score / 10 ) * 100, 2 );
            $icon_url   = MI_Survey_Questions::get_icon_url( $type, 'png' );

            $html .= '<tr style="border-bottom:1px solid #eee;">';
            $html .= '<td style="padding:8px 4px;width:30px;"><img src="' . esc_url( $icon_url ) . '" alt="" width="24" height="24" style="vertical-align:middle;"></td>';
            $html .= '<td style="padding:8px 4px;white-space:nowrap;font-size:14px;">' . esc_html( $label ) . '</td>';
            $html .= '<td style="padding:8px 4px;width:50%;">';
            $html .= '<div style="background:#f0f0f0;border-radius:4px;overflow:hidden;height:20px;">';
            $html .= '<div style="background:#e8a317;height:100%;width:' . $bar_width . '%;border-radius:4px;"></div>';
            $html .= '</div></td>';
            $html .= '<td style="padding:8px 4px;font-weight:bold;text-align:center;font-size:14px;">' . intval( $score ) . '/10</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $html .= '<p style="font-style:italic;color:#666;">' . esc_html( $dynamic_note ) . '</p>';

        // Descriptions.
        $html .= '<hr style="border:none;border-top:1px solid #ddd;margin:30px 0;">';
        foreach ( $sorted_scores as $type => $score ) {
            $label       = isset( $labels[ $type ] ) ? $labels[ $type ] : $type;
            $description = MI_Survey_Descriptions::get_description( $type, $lang );
            $icon_url    = MI_Survey_Questions::get_icon_url( $type, 'png' );

            $html .= '<div style="margin-bottom:25px;">';
            $html .= '<h3 style="color:#e8a317;margin-bottom:8px;">';
            $html .= '<img src="' . esc_url( $icon_url ) . '" alt="" width="24" height="24" style="vertical-align:middle;margin-right:8px;">';
            $html .= esc_html( $label ) . ' (' . intval( $score ) . '/10)</h3>';
            $html .= $description;
            $html .= '</div>';
        }

        $html .= '<p style="font-size:12px;color:#999;margin-top:30px;">© ' . gmdate( 'Y' ) . ' PNA — polecanynauczycielangielskiego.pl</p>';
        $html .= '</body></html>';

        return $html;
    }
}
