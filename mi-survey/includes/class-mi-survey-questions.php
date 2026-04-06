<?php
class MI_Survey_Questions {

    /**
     * Get all questions for a given language.
     *
     * @param string $lang 'pl' or 'en'.
     * @return array Flat array of [ 'id' => 'A1', 'type' => 'A', 'text' => '...' ]
     */
    public static function get_questions( $lang = 'pl' ) {
        $all = self::get_all_questions();
        if ( ! isset( $all[ $lang ] ) ) {
            $lang = 'pl';
        }

        $questions = array();
        foreach ( $all[ $lang ] as $type => $items ) {
            foreach ( $items as $index => $text ) {
                $num         = $index + 1;
                $questions[] = array(
                    'id'   => $type . $num,
                    'type' => $type,
                    'text' => $text,
                );
            }
        }

        return $questions;
    }

    /**
     * Get intelligence type labels.
     *
     * @param string $lang 'pl' or 'en'.
     * @return array Keyed by type code.
     */
    public static function get_type_labels( $lang = 'pl' ) {
        $labels = array(
            'pl' => array(
                'A' => 'Językowa',
                'B' => 'Logiczno-matematyczna',
                'C' => 'Wizualno-przestrzenna',
                'D' => 'Muzyczna',
                'E' => 'Kinestetyczna (ruchowa)',
                'F' => 'Interpersonalna (społeczna)',
                'G' => 'Intrapersonalna (refleksyjna)',
                'H' => 'Przyrodnicza',
            ),
            'en' => array(
                'A' => 'Linguistic',
                'B' => 'Logical-Mathematical',
                'C' => 'Visual-Spatial',
                'D' => 'Musical',
                'E' => 'Bodily-Kinesthetic',
                'F' => 'Interpersonal',
                'G' => 'Intrapersonal',
                'H' => 'Naturalistic',
            ),
        );

        return isset( $labels[ $lang ] ) ? $labels[ $lang ] : $labels['pl'];
    }

    /**
     * Get icon slugs for each type.
     *
     * @return array
     */
    public static function get_icon_slugs() {
        return array(
            'A' => 'language',
            'B' => 'logic',
            'C' => 'spatial',
            'D' => 'music',
            'E' => 'body',
            'F' => 'interpersonal',
            'G' => 'intrapersonal',
            'H' => 'nature',
        );
    }

    /**
     * Get icon URL for a type.
     *
     * @param string $type   Type code A-H.
     * @param string $format 'svg' or 'png'.
     * @return string URL.
     */
    public static function get_icon_url( $type, $format = 'svg' ) {
        $slugs    = self::get_icon_slugs();
        $slug     = isset( $slugs[ $type ] ) ? $slugs[ $type ] : 'language';
        $base_url = 'https://polecanynauczycielangielskiego.pl/wp-content/uploads/2026/04/';

        if ( 'svg' === $format && 'body' === $slug ) {
            return $base_url . 'MI_body_clean.svg';
        }

        return $base_url . 'MI_' . $slug . '.' . $format;
    }

    /**
     * All questions data.
     *
     * @return array
     */
    private static function get_all_questions() {
        return array(
            'pl' => array(
                'A' => array(
                    'Słyszę w głowie słowa, zanim je przeczytam, wymówię, napiszę.',
                    'Zdarza się, że inni przerywają mi wypowiedź, by zapytać o znaczenie słowa, którego użyłem/łam w mowie lub piśmie.',
                    'Książki są dla mnie ważne.',
                    'Ostatnio napisałem/łam coś, z czego jestem bardzo dumny/a, co zyskało uznanie w oczach innych ludzi.',
                    'W rozmowie często odnoszę się do tego co przeczytałem/łam lub słyszałem/łam.',
                    'Lubię zabawy słowne tj. scrabble, anagramy, krzyżówki.',
                    'Lubię zabawiać siebie lub innych łamańcami językowymi, bezsensownymi rymowankami i kalamburami.',
                    'Wysłuchuję więcej informacji z radia i kaset niż z telewizji.',
                    'Język polski, przedmioty humanistyczne i historia były dla mnie łatwiejsze niż matematyka i nauki ścisłe.',
                    'Kiedy jadę samochodem, większą uwagę zwracam na napisy na bilbordach niż na krajobraz.',
                ),
                'B' => array(
                    'Wygrywam w szachy, warcaby i inne gry strategiczne.',
                    'Czasami myślę abstrakcyjnymi konceptami, bez słów i bez ich wizerunków.',
                    'Matematyka i/lub nauki ścisłe były pośród moich ulubionych przedmiotów w szkole.',
                    'Umiem dokonywać w pamięci prostych obliczeń np. pomnożyć składniki do gotowania lub pomiary techniczne.',
                    'Lubię wynajdywać błędy logiczne w tym, co ludzie mówią i robią w pracy i w domu.',
                    'Czuję się pewniej i lepiej gdy coś zostanie zmierzone, sklasyfikowane, zanalizowane w jakiś sposób.',
                    'Mam umysł, który czasami działa jak komputer.',
                    'Lubię eksperymenty w stylu „a co jeśli?" (np. a co będzie jeśli podwoję ilość wody, którą podlewam mój krzew różany?)',
                    'Wierzę, że większość rzeczy i zjawisk ma racjonalne wyjaśnienie.',
                    'Dużo rozmyślam o tym jak funkcjonują niektóre rzeczy.',
                ),
                'C' => array(
                    'Mam aparat fotograficzny/kamerę by nagrywać to, co widzę dookoła.',
                    'W szkole geometria była dla mnie łatwiejsza niż algebra.',
                    'Jestem wrażliwy/a na kolory.',
                    'Jak zamykam oczy, „widzę" wyraźny obraz.',
                    'Umiem sobie wyobrazić jak coś wygląda z lotu ptaka.',
                    'Wolę materiały do czytania bogato opatrzone ilustracjami.',
                    'Lubię układanki, puzzle, labirynty i inne wizualne łamigłówki.',
                    'Mam realistycznie wyglądające sny.',
                    'Mam ogólną łatwość orientacji nawet w nieznanym terenie.',
                    'Lubię rysować i robić „esy-floresy".',
                ),
                'D' => array(
                    'Moje życie byłoby ubogie bez muzyki.',
                    'Kiedy idę ulicą, łapię się na tym, że w głowie mi gra jakiś kawałek muzyki usłyszany w radio, telewizji, lub gdzie indziej.',
                    'Znam wiele melodii.',
                    'Wiem kiedy nuta fałszuje.',
                    'Mam dobry głos.',
                    'Zwykle umiem wyśpiewać melodię, po usłyszeniu jej tylko raz lub dwa.',
                    'Często w trakcie pracy czy nauki, wystukuję lub nucę melodie.',
                    'Z łatwością umiem wystukać rytm do melodii na prostym instrumencie muzycznym.',
                    'Umiem grać i gram na instrumencie muzycznym.',
                    'Często słucham muzyki w radio, z płyt i kaset.',
                ),
                'E' => array(
                    'Regularnie uprawiam przynajmniej jeden rodzaj sportu lub aktywności fizycznej.',
                    'Najlepsze pomysły przychodzą mi do głowy, kiedy jestem na spacerze, kiedy biegam lub w trakcie innej aktywności fizycznej.',
                    'Siedzenie w jednym miejscu przez dłuższy czas sprawia mi trudności.',
                    'Lubię pracować rękoma, koncentrować się na takich czynnościach jak szycie, tkanie, rzeźbienie, stolarka, budowanie modeli itp.',
                    'Aby się czegoś nauczyć muszę to przećwiczyć i nie wystarcza mi przeczytanie opisu lub obejrzenie tego na wideo.',
                    'Mogę się opisać jako osobę dobrze zorganizowaną.',
                    'W rozmowie gestykuluję lub/i używam innych elementów języka niewerbalnego.',
                    'Potrzebuję dotknąć rzeczy, aby się o nich nauczyć.',
                    'Lubię spędzać czas poza domem, na świeżym powietrzu.',
                    'Lubię szybką jazdę i inne podnoszące adrenalinę zajęcia.',
                ),
                'F' => array(
                    'Kiedy mam problem, raczej się zwrócę z nim do kogoś, niż spróbuję rozwiązać go sam.',
                    'Wolę spędzać wieczory na imprezie towarzyskiej niż sam/a w domu.',
                    'Lubię wyzwanie nauczenia innej osoby, lub grupy osób tego, co sam/a umiem.',
                    'Dobrze się czuję w tłumie.',
                    'Ludzie często przychodzą do mnie po radę.',
                    'Uważam się za typ przywódcy (albo inni tak uważają).',
                    'Wolę takie gry jak Monopol lub brydż niż gry dla jednego gracza i pasjanse.',
                    'Wolę sporty grupowe tj. badminton, siatkówka itd. bardziej niż sporty uprawiane w pojedynkę tj. pływanie czy jogging.',
                    'Mam przynajmniej troje bliskich przyjaciół.',
                    'Lubię brać udział w spotkaniach towarzyskich związanych z moją pracą, kościołem lub społecznością.',
                ),
                'G' => array(
                    'Mam ważne cele w swoim życiu, o których regularnie myślę.',
                    'Regularnie spędzam czas w samotności medytując, rozmyślając, lub myśląc o ważnych zagadnieniach życia.',
                    'Wolę spędzić weekend w samotności w domku w lesie, niż w kurorcie pełnym ludzi.',
                    'Uważam się za osobę o silnej woli lub bardzo niezależną.',
                    'Mam niezwykłe myśli na różne tematy i inni ludzie zdają się tego nie rozumieć.',
                    'Prowadzę własną działalność lub przynajmniej poważnie myślałem/łam o jej rozpoczęciu.',
                    'Chodziłem/łam na sesje terapeutyczne lub doradcze lub treningi rozwoju, aby się lepiej poznać.',
                    'Prowadzę dziennik lub pamiętnik i uwieczniam w nim wydarzenia z mojego wewnętrznego życia.',
                    'Mam specjalne hobby lub zainteresowanie, o którym inni nie wiedzą.',
                    'Uważam się za samotnika (lub inni tak uważają).',
                ),
                'H' => array(
                    'Lubię porządkować rzeczy i zjawiska według ich wspólnych cech.',
                    'Sprawy związane z ekologią są dla mnie ważne.',
                    'Nie mam nic przeciwko pracy w ogrodzie.',
                    'Zwierzęta odgrywają ważną rolę w moim życiu.',
                    'Dużo czasu spędzam na dworze.',
                    'Zawsze miałem lub mam psa, kota lub inne zwierzę domowe.',
                    'Jestem dobry w przewidywaniu pogody.',
                    'W moim domu jest dużo kwiatów i roślin i lubię się nimi zajmować.',
                    'Podobają mi się zdjęcia przedstawiające przyrodę i krajobrazy.',
                    'Lepiej się czuję gdy jestem na dworze niż wewnątrz budynku.',
                ),
            ),
            'en' => array(
                'A' => array(
                    'I can hear words in my head before I read, speak, or write them down.',
                    'Other people sometimes have to stop and ask me to explain the meaning of the words I use in my writing and speaking.',
                    'Books are important to me.',
                    "I've written something recently that I was particularly proud of or that earned me recognition from others.",
                    "My conversation includes frequent reference to things that I've read or heard.",
                    'I enjoy word games like Scrabble, Anagrams, or Passwords.',
                    'I enjoy entertaining myself or others with tongue twisters, nonsense rhymes, or puns.',
                    'I get more out of listening to the radio or a spoken-word recording than I do from television or films.',
                    'English, social studies, and history were easier for me in school than math and science.',
                    'When I drive down a road, I pay more attention to the words written on billboards than to the scenery.',
                ),
                'B' => array(
                    'I beat my friends in chess, checkers, Go, or other strategy games.',
                    'I sometimes think in clear, abstract, wordless, imageless concepts.',
                    'Math and/or science were among my favourite subjects in school.',
                    'I can double or triple a cooking recipe or carpentry measurement without having to put it all down on paper.',
                    'I like finding logical flaws in things that people say and do at home and work.',
                    'I feel more comfortable when something has been measured, categorized, analysed, or quantified in some way.',
                    "I've got a mind that sometimes works like a computer.",
                    'I like to set up little "what if" experiments (for example, "what if I double the amount of water I give to my rose bush each week?")',
                    'I believe that most things have a rational explanation.',
                    'I wonder a lot about how certain things work.',
                ),
                'C' => array(
                    'I have a camera or camcorder that I use to record what I see around me.',
                    'Geometry was easier for me than algebra in school.',
                    "I'm sensitive to colour.",
                    'I often see clear visual images when I close my eyes.',
                    "I can comfortably imagine how something might appear if it were looked down upon from directly above in a bird's eye view.",
                    'I prefer looking at reading material that is heavily illustrated.',
                    'I enjoy solving jigsaw puzzles, mazes, or other visual puzzles.',
                    'I have vivid dreams at night.',
                    'I can generally find my way around unfamiliar territory.',
                    'I like to draw or doodle.',
                ),
                'D' => array(
                    'My life would be poorer if there was no music in it.',
                    'I catch myself sometimes walking down the street with a television jingle or other tune running through my mind.',
                    'I know the tunes of many different songs or musical pieces.',
                    'I can tell when a musical note is off-key.',
                    'I have a good singing voice.',
                    'If I hear a musical selection once or twice, I am usually able to sing it back fairly accurately.',
                    'I often make tapping sounds or sing little melodies while working, studying, or learning something new.',
                    'I can easily keep time to a piece of music with a simple percussion instrument.',
                    'I play a musical instrument.',
                    'I frequently listen to musical selections on radio, records, cassettes, or compact discs.',
                ),
                'E' => array(
                    'I engage in at least one sport or physical activity on a regular basis.',
                    "My best ideas often come to me when I'm out for a long walk, a jog, or some other kind of physical activity.",
                    'I find it difficult to sit still for long periods of time.',
                    'I like working with my hands at some concrete activity such as sewing, weaving, carving, carpentry, model-building or a similar task.',
                    'I need to practice a new skill by doing it rather than simply reading about it or seeing a video that describes it.',
                    'I would describe myself as well-coordinated.',
                    'I frequently use hand gestures or other forms of body language when conversing with someone.',
                    'I need to touch things in order to learn more about them.',
                    'I often like to spend my free time outdoors.',
                    'I enjoy dare devil amusement rides, or similar thrilling physical experiences.',
                ),
                'F' => array(
                    "When I've got a problem, I'm more likely to seek out another person for help than attempt to work it out on my own.",
                    'I would rather spend my evenings at a lively party than at home alone.',
                    'I enjoy the challenge of teaching another person, or groups of people, what I know how to do.',
                    'I feel comfortable in the midst of a crowd.',
                    "I'm considered an individual that people come to for advice and counsel.",
                    'I consider myself a leader (or others have called me that).',
                    'I prefer social pastimes like Monopoly or bridge to individual recreations such as video games and solitaire.',
                    'I prefer group sports like badminton, volleyball, or softball to solo sports such as swimming and jogging.',
                    'I have at least three close friends.',
                    'I like to get involved in social activities connected with my work, church, or community.',
                ),
                'G' => array(
                    'I have some important goals for my life that I think about on a regular basis.',
                    'I regularly spend time alone to meditate, reflect, or think about important life questions.',
                    'I would prefer to spend a weekend alone in a cabin in the woods rather than at a fancy resort with lots of people around.',
                    'I consider myself to be strong willed or fiercely independent.',
                    "I have unique thoughts about things that others don't seem to understand.",
                    'I am self-employed or have at least thought seriously about establishing my own business.',
                    'I have attended counselling sessions or personal growth seminars to learn more about myself.',
                    'I keep a personal diary or journal to record the events of my inner life.',
                    'I have a special hobby or interest that I keep pretty much to myself.',
                    'I see myself as a loner (or others see me that way).',
                ),
                'H' => array(
                    'I like to classify things and phenomena by their common features.',
                    'Ecology and environmental issues are important to me.',
                    "I don't mind working in the garden.",
                    'Animals play an important role in my life.',
                    'I spend a lot of time outdoors.',
                    'I have always had or currently have a dog, cat, or other pet.',
                    'I am good at predicting the weather.',
                    'My home has plenty of flowers and plants and I enjoy taking care of them.',
                    'I enjoy photographs of nature and landscapes.',
                    'I feel better when I am outdoors than inside a building.',
                ),
            ),
        );
    }
}
