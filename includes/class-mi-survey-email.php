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
        $title = 'pl' === $lang
            ? esc_html( $name ) . ', to jest twój Profil Inteligencji Wielorakich'
            : esc_html( $name ) . ', this is your Profile of Multiple Intelligences';

        $date_label = 'pl' === $lang ? 'Data ankiety' : 'Survey date';

        $dynamic_note = 'pl' === $lang
            ? 'Profil MI jest dynamiczny. Zmienia się w czasie.'
            : 'The MI profile is dynamic. It changes over time.';

        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;color:#333;">';
        $html .= '<h2 style="color:#e8a317;font-size:24px;">' . $title . '</h2>';
        $html .= '<p style="color:#888;font-size:14px;">' . esc_html( $date_label ) . ': ' . esc_html( $date ) . '</p>';

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

        $html .= '<p style="font-style:italic;color:#888;font-size:14px;text-align:center;">' . esc_html( $dynamic_note ) . '</p>';

        // Descriptions — styled to match browser layout.
        foreach ( $sorted_scores as $type => $score ) {
            $label       = isset( $labels[ $type ] ) ? $labels[ $type ] : $type;
            $description = MI_Survey_Descriptions::get_description( $type, $lang );
            $icon_url    = MI_Survey_Questions::get_icon_url( $type, 'png' );

            $html .= '<div style="margin-bottom:30px;padding:20px;border-left:4px solid #e8a317;background:#fefcf6;border-radius:8px;">';
            $html .= '<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;"><tr>';
            $html .= '<td style="vertical-align:middle;padding-right:12px;"><img src="' . esc_url( $icon_url ) . '" alt="" width="150" height="150" style="display:block;"></td>';
            $html .= '<td style="vertical-align:middle;">';
            $html .= '<div style="font-size:22px;font-weight:700;color:#E8A317;margin:0 0 4px 0;">' . esc_html( $label ) . '</div>';
            $html .= '<div style="font-size:18px;font-weight:700;color:#e8a317;">' . intval( $score ) . ' / 10</div>';
            $html .= '</td></tr></table>';
            $html .= '<div style="font-size:15px;line-height:1.7;color:#444;">' . $description . '</div>';
            $html .= '</div>';
        }

        $html .= '<p style="font-size:12px;color:#999;margin-top:30px;">© ' . gmdate( 'Y' ) . ' PNA — polecanynauczycielangielskiego.pl</p>';
        $html .= '</body></html>';

        return $html;
    }
}
