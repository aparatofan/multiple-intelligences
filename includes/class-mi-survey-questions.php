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
                    'Często „słyszę” w głowie słowa, zanim je przeczytam, powiem lub napiszę, jakbym wcześniej ćwiczył/a je w myślach.',
                    'Zdarza się, że inni pytają mnie o znaczenie słów, których używam w rozmowie lub w tekście.',
                    'Czytanie to ważny element mojej codzienności. Prawie zawsze mam pod ręką książkę, artykuł lub inny tekst.',
                    'Niedawno napisałem/am coś, z czego jestem szczególnie dumny/a albo co zostało docenione przez innych (post, blog, esej, e-mail itp.).',
                    'W rozmowach często odwołuję się do myśli, które usłyszałem/am albo przeczytałem/am — z książek, podcastów czy artykułów.',
                    'Lubię gry i zabawy słowne, takie jak Scrabble, Wordle, krzyżówki czy podobne łamigłówki.',
                    'Lubię bawić się językiem, na przykład łamańcami językowymi, absurdalnymi rymowankami albo kalamburami.',
                    'Więcej korzyści wynoszę ze słuchania podcastów i audiobooków niż z oglądania telewizji czy filmów.',
                    'Przedmioty humanistyczne, takie jak język polski czy historia, były dla mnie zawsze łatwiejsze niż matematyka i nauki ścisłe.',
                    'Podczas podróży moją uwagę bardziej przykuwają napisy na znakach i billboardach niż krajobrazy.',
                ),
                'B' => array(
                    'Często wygrywam z przyjaciółmi w różne gry strategiczne, na przykład w szachy, warcaby czy Go.',
                    'Zdarza mi się myśleć w czysto abstrakcyjny sposób, bez słów i obrazów.',
                    'Matematyka i nauki ścisłe zawsze należały do moich ulubionych przedmiotów.',
                    'Bez problemu przeliczam w głowie proporcje, na przykład gdy trzeba podwoić albo potroić składniki przepisu lub jakieś wymiary, bez potrzeby zapisywania obliczeń.',
                    'Łatwo zauważam błędy logiczne w tym, co ludzie mówią albo robią.',
                    'Czuję się pewniej, gdy coś można zmierzyć, uporządkować, przeanalizować albo wyrazić liczbami.',
                    'Mam wrażenie, że mój umysł działa jak komputer — szybko i metodycznie przetwarzam informacje.',
                    'Lubię sprawdzać różne scenariusze typu „a co, jeśli?”, na przykład: „Co się stanie, jeśli zmienię swoją poranną rutynę?” albo „A co, jeśli podwoję ilość wody do podlewania roślin?”.',
                    'Wierzę, że większość rzeczy można racjonalnie wyjaśnić. Zwykle bardziej ufam faktom i logice niż intuicji.',
                    'Często zastanawiam się, jak działają różne rzeczy — na przykład jak pracuje silnik, jak działa algorytm albo jak telefon bezprzewodowo łączy się z internetem.',
                ),
                'C' => array(
                    'Często dokumentuję rzeczywistość zdjęciami lub krótkimi nagraniami, robionymi telefonem, aparatem fotograficznym lub innym urządzeniem.',
                    'W szkole geometria przychodziła mi łatwiej niż algebra — lepiej radzę sobie z kształtami i przestrzenią niż z równaniami.',
                    'Mam wyczucie kolorów — szybko widzę, kiedy coś do siebie pasuje, a kiedy zupełnie się nie komponuje.',
                    'Kiedy zamykam oczy, zaczyna działać wyobraźnia: w mojej głowie powstają wyraźne obrazy i sceny.',
                    'Bez problemu wyobrażam sobie widok z góry, z lotu ptaka.',
                    'Lepiej rozumiem treści przedstawione wizualnie niż wyłącznie tekstowo — dużą pomocą są zdjęcia, diagramy czy infografiki.',
                    'Lubię wszelkie łamigłówki wizualne: układanki, puzzle czy labirynty.',
                    'Często mam bardzo realistyczne, intensywne i bogate w szczegóły sny, przypominające oglądanie filmu.',
                    'Mam dobrze rozwinięty zmysł orientacji, dzięki czemu łatwo odnajduję się w nowych miejscach.',
                    'Lubię coś rysować albo bazgrać, nawet bez celu — na przykład w trakcie rozmowy czy spotkania.',
                ),
                'D' => array(
                    'Moje życie bez muzyki byłoby uboższe.',
                    'Często spontanicznie odtwarzam w myślach melodie lub piosenki.',
                    'Znam i rozpoznaję melodie wielu różnych piosenek lub utworów muzycznych.',
                    'Łatwo wychwytuję, gdy ktoś fałszuje, śpiewając lub grając na instrumencie.',
                    'Mam dobry głos — słyszałem/am to od innych albo po prostu czuję się swobodnie, kiedy śpiewam przy ludziach.',
                    'Zwykle potrafię zanucić albo odtworzyć melodię już po jednym lub dwóch przesłuchaniach.',
                    'W trakcie pracy albo nauki często coś nucę albo wystukuję rytm.',
                    'Łatwo łapię rytm — na przykład klaszcząc, stukając czy poruszając się do muzyki.',
                    'Mam doświadczenie w grze na instrumencie muzycznym.',
                    'Muzyka towarzyszy mi każdego dnia w różnej formie — na przykład przez streaming, radio czy YouTube.',
                ),
                'E' => array(
                    'Regularnie uprawiam sport, na przykład chodzę na siłownię, biegam, pływam lub jeżdżę na rowerze.',
                    'Najlepsze pomysły wpadają mi do głowy, kiedy jestem w ruchu — na spacerze, podczas biegania albo innej aktywności.',
                    'Trudno mi długo usiedzieć w miejscu. Na dłuższych spotkaniach często zmieniam pozycję albo mam potrzebę się poruszać.',
                    'Bardzo lubię prace manualne, takie jak szycie, składanie modeli, gotowanie, majsterkowanie czy rękodzieło.',
                    'Najskuteczniej uczę się przez działanie i praktykę, a nie przez czytanie instrukcji lub oglądanie tutoriali.',
                    'Mam dobrą koordynację ruchową. Bez problemu radzę sobie z zadaniami wymagającymi równowagi lub precyzji.',
                    'Dużo gestykuluję, kiedy mówię.',
                    'Lubię poznawać rzeczy przez dotyk — brać je do ręki, sprawdzać, jak i z czego są zrobione.',
                    'Lubię spędzać czas aktywnie na świeżym powietrzu, na przykład spacerując lub uprawiając sport.',
                    'Lubię intensywne doznania fizyczne i ruchowe, na przykład jazdę rollercoasterem czy szybką jazdę na rowerze albo nartach.',
                ),
                'F' => array(
                    'Kiedy mam problem, zwykle wolę z kimś o tym porozmawiać niż radzić sobie samodzielnie.',
                    'Mając wolny wieczór, wolę go spędzić z przyjaciółmi niż w pojedynkę.',
                    'Uczenie innych tego, co sam/a umiem, sprawia mi wielką satysfakcję.',
                    'Dobrze się czuję wśród ludzi. Duże grupy mnie nie przytłaczają.',
                    'Ludzie często przychodzą do mnie po radę albo wsparcie — jestem postrzegany/a jako osoba, która słucha i pomaga.',
                    'W naturalny sposób przejmuję inicjatywę albo jestem postrzegany/a jako lider/ka.',
                    'Wolę spędzać wolny czas z innymi, na przykład grając w planszówki, zamiast spędzać czas solo, czytając książkę lub oglądając serial.',
                    'Bardziej ciągnie mnie do aktywności zespołowych, takich jak siatkówka, piłka nożna czy koszykówka, niż indywidualnych, jak pływanie czy bieganie.',
                    'Mam kilku bliskich przyjaciół, z którymi jestem w stałym kontakcie i którzy dobrze mnie znają.',
                    'Chętnie angażuję się w działania społeczne związane z moją pracą, szkołą lub społecznością, na przykład działam jako wolontariusz albo organizuję wydarzenia dla sąsiadów.',
                ),
                'G' => array(
                    'Mam jasno określone cele życiowe i regularnie o nich rozmyślam.',
                    'Systematycznie poświęcam czas na medytację, rozmyślanie i bycie ze sobą w samotności.',
                    'Na weekend najchętniej wybiorę się w spokojne, odludne miejsce, na przykład do domku w lesie, zamiast do zatłoczonego kurortu.',
                    'Uważam się za osobę o silnej woli i niezależną — sam/a podejmuję decyzje i nie ulegam presji otoczenia.',
                    'Myślę niestandardowo. Moje pomysły czy sposób patrzenia na sprawy nie zawsze są zrozumiałe dla innych.',
                    'Prowadzę własną działalność lub poważnie rozważam jej założenie.',
                    'Świadomie pracuję nad swoim rozwojem — korzystam z kursów, warsztatów, terapii lub innych form pracy nad sobą.',
                    'Prowadzę dziennik, w którym regularnie zapisuję swoje myśli.',
                    'Mam swoje pasje lub zainteresowania, którymi nie dzielę się z innymi.',
                    'Mogę być postrzegany/a jako samotnik. Tak naprawdę po prostu dobrze czuję się sam/a ze sobą — samotność jest dla mnie komfortowa.',
                ),
                'H' => array(
                    'Lubię porządkować rzeczy i dostrzegać między nimi wzorce i zależności — na przykład grupować je według wspólnych cech.',
                    'Przywiązuję dużą wagę do kwestii ekologii i ochrony środowiska, na przykład segreguję odpady, ograniczam plastik i oszczędzam wodę.',
                    'Praca w ogrodzie daje mi satysfakcję. Lubię kontakt z ziemią, roślinami i zajęcia na świeżym powietrzu.',
                    'Zwierzęta są dla mnie ważne — czuję z nimi silną więź.',
                    'Dużo czasu spędzam na świeżym powietrzu. Kontakt z naturą jest dla mnie ważny.',
                    'Aktualnie mam albo miałem/am w przeszłości zwierzę domowe.',
                    'Zwracam uwagę na zmiany pogody — często potrafię je przewidzieć, obserwując niebo.',
                    'W moim domu jest dużo roślin. Lubię się nimi otaczać i o nie dbać. Znajomi mówią, że mam „dobrą rękę” do kwiatów.',
                    'Lubię oglądać zdjęcia przyrody i krajobrazów. Godzinami mogę patrzeć na góry, lasy lub wodę.',
                    'Lepiej czuję się na zewnątrz niż w zamkniętych pomieszczeniach. Kontakt z naturą poprawia mi nastrój i dodaje energii.',
                ),
            ),
            'en' => array(
                'A' => array(
                    'I can hear words in my head before I read, speak, or write them down. It\'s as if I rehearse them silently first.',
                    'Other people sometimes have to stop and ask me to explain the meaning of the words I use in my writing and speaking.',
                    'Books are important to me. I usually have something to read, whether it\'s a novel, a non-fiction book, or even a long article.',
                    'I\'ve written something recently that I was particularly proud of or that earned me recognition from others. For example, a blog post, an essay, a poem, or even a well-crafted email.',
                    'In conversations, I often refer to things that I\'ve read or heard — a book, a podcast, an article, etc.',
                    'I enjoy word games like Scrabble, Wordle, crosswords, or similar puzzles.',
                    'I enjoy entertaining myself or others with tongue twisters, nonsense rhymes, or puns.',
                    'I get more out of listening to a podcast or an audiobook than I do from watching television or films.',
                    'Subjects like English, social studies, and history were easier for me in school than maths and science.',
                    'When I\'m travelling, I pay more attention to the words on signs and billboards than to the scenery.',
                ),
                'B' => array(
                    'I often beat my friends in chess, checkers, Go, or other strategy games.',
                    'I sometimes think in clear, abstract concepts — without words or images.',
                    'Maths and/or science were among my favourite subjects in school.',
                    'I can double or triple a cooking recipe or a measurement in my head, without having to write it down.',
                    'I like spotting logical flaws in things that people say and do, whether at home or at work.',
                    'I feel more comfortable when something has been measured, categorised, analysed, or quantified in some way.',
                    'My mind sometimes works like a computer — I process information quickly and systematically.',
                    'I like to set up little "what if" experiments. For example, "What if I change my morning routine?" or "What if I double the amount of water I give to my plants?"',
                    'I believe that most things have a rational explanation. I tend to trust logic and evidence over gut feelings or intuition.',
                    'I wonder a lot about how certain things work. For example, how an engine runs or how a phone connects to the internet.',
                ),
                'C' => array(
                    'I like to take photos or record videos to capture what I see around me — using my phone, a camera, or any other device.',
                    'Geometry was easier for me than algebra in school. I was better at working with shapes, angles, and spaces than with equations and formulas.',
                    'I\'m sensitive to colour. I notice when colours match or clash, and colour choices matter to me.',
                    'I often see clear visual images when I close my eyes. I can easily replay scenes or imagine things visually in my mind.',
                    'I can comfortably imagine how something might appear if it were looked at from directly above, like a bird\'s-eye view.',
                    'I prefer reading material that is heavily illustrated — pictures, diagrams, and infographics help me understand things better.',
                    'I enjoy solving jigsaw puzzles, mazes, or other visual puzzles.',
                    'I have vivid dreams at night. My dreams feel very real and detailed, almost like watching a film.',
                    'I can generally find my way around unfamiliar places. I have a good sense of direction.',
                    'I like to draw or doodle, even if it\'s just absent-minded sketching during a meeting or a phone call.',
                ),
                'D' => array(
                    'My life would be poorer if there was no music in it.',
                    'I catch myself sometimes walking down the street with a tune or a jingle running through my head.',
                    'I know the melodies of many different songs or musical pieces.',
                    'I can tell when a musical note is off-key.',
                    'I have a good singing voice. Other people have told me I sing well, or I feel confident singing in front of others.',
                    'If I hear a song once or twice, I\'m usually able to sing or hum it back fairly accurately.',
                    'I often make tapping sounds or sing little melodies while working, studying, or learning something new.',
                    'I can easily keep time to a piece of music — for example, by clapping, tapping, or drumming along.',
                    'I play a musical instrument (or have played one in the past).',
                    'I regularly listen to music — through streaming, radio, vinyl, or any other format. It\'s part of my daily life.',
                ),
                'E' => array(
                    'I engage in at least one sport or physical activity on a regular basis. For example, I go to the gym, jog, swim, or cycle.',
                    'My best ideas often come to me when I\'m out for a long walk, a jog, or doing some other kind of physical activity.',
                    'I find it difficult to sit still for long periods of time. During a long meeting or lecture, I often fidget, shift in my seat, or feel the urge to get up and move.',
                    'I like working with my hands — for example, sewing, woodworking, building models, cooking, fixing things, or doing crafts.',
                    'I need to practise a new skill by doing it, rather than simply reading about it or watching a video.',
                    'I would describe myself as well-coordinated. I\'m generally good at physical tasks that require balance or precision.',
                    'I use hand gestures or other forms of body language a lot when talking to someone.',
                    'I need to touch things in order to learn more about them. I like to pick things up, feel textures, and explore objects with my hands.',
                    'I often like to spend my free time outdoors. For example, I enjoy going for walks, playing sports, or just being physically active in the open air.',
                    'I enjoy thrilling physical experiences — like roller coasters, bungee jumping, or fast rides.',
                ),
                'F' => array(
                    'When I\'ve got a problem, I\'m more likely to talk to someone about it than try to work it out on my own.',
                    'I\'d rather spend my evenings at a lively gathering with friends than stay at home alone.',
                    'I enjoy the challenge of teaching another person, or a group of people, something I know how to do.',
                    'I feel comfortable in the middle of a crowd. Large groups of people don\'t make me anxious.',
                    'People often come to me for advice or support. I\'m seen as someone who listens and helps.',
                    'I consider myself a leader, or others have described me that way.',
                    'I\'d rather spend my free time with others than be on my own. For example, I prefer playing a board game with friends to playing solitaire by myself.',
                    'I prefer team sports like volleyball, football, or basketball to solo activities such as swimming laps or jogging on my own.',
                    'I have a few good friends. They know a lot about me, and we keep in touch regularly.',
                    'I like to get involved in social activities connected with my work, school, or community. For example, I join clubs, volunteer, or organise events.',
                ),
                'G' => array(
                    'I have some important goals for my life that I think about on a regular basis.',
                    'I regularly spend time alone to meditate, reflect, or think about important life questions.',
                    'I would prefer to spend a weekend alone in a quiet place — like a cabin in the woods — rather than at a busy resort with lots of people around.',
                    'I consider myself strong-willed or fiercely independent. I make my own decisions and don\'t easily give in to pressure.',
                    'I have thoughts and ideas that are quite unique — others sometimes don\'t understand them.',
                    'I am self-employed or have at least thought seriously about starting my own business.',
                    'I have taken part in personal development activities — like counselling, coaching, workshops, or online courses — to learn more about myself.',
                    'I keep a personal diary, journal, or blog to record my thoughts and inner experiences.',
                    'I have a special hobby or interest that I keep mostly to myself. It\'s something personal that I don\'t share widely.',
                    'I see myself as a loner, or others see me that way. I\'m comfortable spending time on my own.',
                ),
                'H' => array(
                    'I like to classify things and spot patterns — for example, grouping plants, animals, or objects by their common features.',
                    'Ecology and environmental issues are important to me. I care about things like recycling, climate change, and protecting nature.',
                    'I don\'t mind working in the garden. I enjoy being around soil, plants, and outdoor tasks.',
                    'Animals play an important role in my life. I feel a strong connection to them.',
                    'I spend a lot of time outdoors — simply being in nature is important for me.',
                    'I have always had or currently have a pet — such as a dog, cat, fish, or any other animal.',
                    'I am good at noticing changes in the weather. I can often tell what the weather will be like just by looking at the sky.',
                    'My home has plenty of flowers and plants, and I enjoy taking care of them.',
                    'I enjoy looking at photographs of nature and landscapes. Beautiful scenery — mountains, forests, oceans — catches my eye and holds my attention.',
                    'I feel better when I am outdoors than inside a building. Being in nature lifts my mood and gives me energy.',
                ),
            ),
        );
    }
}
