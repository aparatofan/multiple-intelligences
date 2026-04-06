<?php
class MI_Survey_Scoring {

    /**
     * Calculate scores from checked question IDs.
     *
     * @param array $answers Array of checked question IDs (e.g. ['A1','A3','B5','H10']).
     * @return array Associative array ['A' => n, 'B' => n, ..., 'H' => n].
     */
    public static function calculate( $answers ) {
        $scores = array(
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0,
            'F' => 0,
            'G' => 0,
            'H' => 0,
        );

        if ( ! is_array( $answers ) ) {
            return $scores;
        }

        foreach ( $answers as $answer_id ) {
            $answer_id = sanitize_text_field( $answer_id );
            if ( preg_match( '/^([A-H])(\d{1,2})$/', $answer_id, $matches ) ) {
                $type = $matches[1];
                $num  = (int) $matches[2];
                if ( $num >= 1 && $num <= 10 && isset( $scores[ $type ] ) ) {
                    $scores[ $type ]++;
                }
            }
        }

        // Cap at 10.
        foreach ( $scores as $type => $score ) {
            $scores[ $type ] = min( $score, 10 );
        }

        return $scores;
    }

    /**
     * Get scores sorted by value descending.
     *
     * @param array $scores Associative scores array.
     * @return array Sorted array preserving keys.
     */
    public static function sort_by_score( $scores ) {
        arsort( $scores );
        return $scores;
    }
}
