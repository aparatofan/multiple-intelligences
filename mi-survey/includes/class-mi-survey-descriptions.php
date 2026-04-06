<?php
class MI_Survey_Descriptions {

    /**
     * Get the description for a given intelligence type and language.
     *
     * @param string $type Type code A-H.
     * @param string $lang 'pl' or 'en'.
     * @return string HTML description.
     */
    public static function get_description( $type, $lang = 'pl' ) {
        $descriptions = self::get_all_descriptions();
        if ( isset( $descriptions[ $lang ][ $type ] ) ) {
            return $descriptions[ $lang ][ $type ];
        }
        return '';
    }

    /**
     * Get all descriptions.
     *
     * @return array
     */
    private static function get_all_descriptions() {
        return array(
            'en' => array(
                'A' => '<p>Linguistic intelligence involves a deep sensitivity to the meaning, sound, and rhythm of words. People with strong linguistic intelligence think in words and have a remarkable ability to use language to express complex meanings. They enjoy reading, writing, telling stories, and playing word games.</p>
<p>This intelligence is associated with professions such as writing, journalism, poetry, law, public speaking, and translation. To develop it further, try keeping a journal, learning a new language, joining a debate club, reading widely across genres, or practising storytelling. Even daily habits like writing emails with care or composing short poems can sharpen your linguistic abilities over time.</p>',

                'B' => '<p>Logical-mathematical intelligence is the capacity to analyse problems logically, carry out mathematical operations, and investigate issues scientifically. People strong in this area think conceptually and abstractly, and are able to see patterns and relationships that others may miss. They enjoy working with numbers, solving puzzles, and conducting experiments.</p>
<p>This intelligence is common in scientists, mathematicians, engineers, programmers, and financial analysts. To strengthen it, try solving logic puzzles or brain teasers, learning to code, playing strategy games like chess, exploring statistical thinking, or setting up small experiments in your everyday life. Asking "why?" and "what if?" regularly helps cultivate this way of thinking.</p>',

                'C' => '<p>Visual-spatial intelligence is the ability to think in three dimensions. People with this strength have a vivid imagination, strong spatial reasoning, and an eye for detail in visual environments. They tend to think in images and pictures, and are good at visualising objects from different angles, reading maps, and interpreting charts or diagrams.</p>
<p>Architects, sculptors, graphic designers, photographers, navigators, and pilots often score highly here. To develop this intelligence, try drawing, painting, building models, doing jigsaw puzzles, creating mind maps, learning to use design software, or practising visualisation exercises. Photography walks — where you look for interesting compositions in everyday surroundings — are also an excellent way to sharpen spatial awareness.</p>',

                'D' => '<p>Musical intelligence is the capacity to discern pitch, rhythm, timbre, and tone. People with strong musical intelligence are sensitive to sounds in their environment, often hear music in their head, and have an intuitive understanding of musical structure. They may find it easy to learn songs, recognise melodies, or keep a beat.</p>
<p>Composers, musicians, singers, DJs, music teachers, and sound engineers typically have well-developed musical intelligence. To nurture it, try learning a musical instrument, singing in a choir, listening actively to different genres of music, composing your own tunes (even simple ones), clapping rhythms, or attending live performances. Even humming or tapping along to music in daily life exercises this intelligence.</p>',

                'E' => '<p>Bodily-kinesthetic intelligence is the ability to use your body effectively to solve problems, create products, or express ideas. People strong in this area have excellent hand-eye coordination, fine motor skills, and a strong sense of timing. They learn best by doing — through movement, touch, and hands-on experience rather than by reading or listening.</p>
<p>Athletes, dancers, surgeons, craftspeople, actors, and mechanics often excel in this intelligence. To develop it, engage in regular physical activity or sports, take up a craft like woodworking or knitting, try dance or martial arts classes, practise yoga or tai chi, or simply use gestures and role-play when learning new concepts. Building things with your hands — from cooking to model-making — is one of the best ways to strengthen this intelligence.</p>',

                'F' => '<p>Interpersonal intelligence is the ability to understand and interact effectively with others. People with strong interpersonal intelligence are skilled at reading other people\'s moods, motivations, and intentions. They communicate well, collaborate naturally, and often take on mediating or leadership roles in groups.</p>
<p>Teachers, counsellors, salespeople, politicians, social workers, and team leaders typically have high interpersonal intelligence. To develop it, practise active listening, volunteer for group projects, seek out diverse social situations, learn about non-verbal communication, try mentoring someone, or join clubs and community organisations. Reflecting on how your words and actions affect others is also a powerful development tool.</p>',

                'G' => '<p>Intrapersonal intelligence is the capacity for self-awareness and introspection. People with strong intrapersonal intelligence have a deep understanding of their own emotions, goals, strengths, and weaknesses. They are reflective, self-motivated, and often prefer working independently. They set meaningful goals and are good at self-regulation.</p>
<p>Philosophers, psychologists, theologians, writers, and entrepreneurs frequently show high intrapersonal intelligence. To develop it, try journaling regularly, practising meditation or mindfulness, setting personal goals and reviewing them, taking personality or strengths assessments, spending quiet time in reflection, or working with a therapist or coach. Self-knowledge grows whenever you pause to ask, "What am I feeling right now, and why?"</p>',

                'H' => '<p>Naturalistic intelligence is the ability to recognise, categorise, and draw upon features of the natural environment. People with strong naturalistic intelligence are attuned to the natural world — they notice patterns in nature, enjoy being outdoors, and are often skilled at classifying plants, animals, rocks, or weather patterns. They tend to feel more at ease outside than inside.</p>
<p>Biologists, conservationists, farmers, gardeners, veterinarians, and nature photographers typically have high naturalistic intelligence. To develop it, spend more time outdoors, start a garden or care for plants, learn to identify local birds or trees, volunteer for environmental causes, keep a nature journal, or explore documentaries about ecosystems and wildlife. Even small acts like observing seasonal changes on your daily walk can strengthen this intelligence.</p>',
            ),

            'pl' => array(
                'A' => '<p>Inteligencja językowa to wyjątkowa wrażliwość na znaczenie, dźwięk i rytm słów. Osoby o silnej inteligencji językowej myślą słowami i mają niezwykłą zdolność posługiwania się językiem w celu wyrażania złożonych treści. Lubią czytać, pisać, opowiadać historie i bawić się słowami. Chętnie sięgają po krzyżówki, gry słowne i zagadki językowe.</p>
<p>Ten rodzaj inteligencji jest powiązany z takimi zawodami jak pisarstwo, dziennikarstwo, poetyka, prawo, wystąpienia publiczne i tłumaczenia. Aby ją rozwijać, warto prowadzić dziennik, uczyć się nowego języka, dołączyć do klubu dyskusyjnego, czytać książki z różnych gatunków literackich oraz ćwiczyć sztukę opowiadania. Nawet codzienne nawyki, takie jak staranne pisanie e-maili czy komponowanie krótkich wierszy, mogą z czasem wyostrzyć zdolności językowe.</p>',

                'B' => '<p>Inteligencja logiczno-matematyczna to zdolność logicznej analizy problemów, przeprowadzania operacji matematycznych oraz badania zjawisk w sposób naukowy. Osoby silne w tym obszarze myślą pojęciowo i abstrakcyjnie, potrafią dostrzegać wzorce i zależności, których inni mogą nie zauważać. Lubią pracować z liczbami, rozwiązywać łamigłówki i przeprowadzać eksperymenty.</p>
<p>Ten rodzaj inteligencji jest typowy dla naukowców, matematyków, inżynierów, programistów i analityków finansowych. Aby ją wzmocnić, warto rozwiązywać łamigłówki logiczne, uczyć się programowania, grać w gry strategiczne takie jak szachy, eksplorować myślenie statystyczne lub przeprowadzać małe eksperymenty w codziennym życiu. Regularne zadawanie sobie pytań „dlaczego?" i „a co jeśli?" pomaga rozwijać ten sposób myślenia.</p>',

                'C' => '<p>Inteligencja wizualno-przestrzenna to zdolność myślenia w trzech wymiarach. Osoby obdarzone tą inteligencją mają bogatą wyobraźnię, silne rozumowanie przestrzenne i wyczucie szczegółów w otoczeniu wizualnym. Myślą obrazami, potrafią wyobrażać sobie przedmioty z różnych perspektyw, czytać mapy i interpretować wykresy oraz diagramy.</p>
<p>Architekci, rzeźbiarze, graficy, fotografowie, nawigatorzy i piloci często osiągają wysokie wyniki w tym obszarze. Aby rozwijać tę inteligencję, warto rysować, malować, budować modele, układać puzzle, tworzyć mapy myśli, uczyć się oprogramowania graficznego lub ćwiczyć wizualizację. Spacery fotograficzne — podczas których szukamy ciekawych kompozycji w codziennym otoczeniu — to również doskonały sposób na wyostrzenie świadomości przestrzennej.</p>',

                'D' => '<p>Inteligencja muzyczna to zdolność rozróżniania wysokości dźwięku, rytmu, barwy i tonu. Osoby o silnej inteligencji muzycznej są wrażliwe na dźwięki w swoim otoczeniu, często słyszą muzykę w głowie i mają intuicyjne rozumienie struktury muzycznej. Z łatwością uczą się piosenek, rozpoznają melodie i utrzymują rytm.</p>
<p>Kompozytorzy, muzycy, śpiewacy, DJ-e, nauczyciele muzyki i inżynierowie dźwięku mają zazwyczaj dobrze rozwiniętą inteligencję muzyczną. Aby ją pielęgnować, warto uczyć się gry na instrumencie, śpiewać w chórze, aktywnie słuchać różnych gatunków muzyki, komponować własne melodie (nawet proste), klaskać rytmy lub uczęszczać na koncerty na żywo. Nawet nucenie czy wystukiwanie rytmu w codziennym życiu ćwiczy tę inteligencję.</p>',

                'E' => '<p>Inteligencja kinestetyczna (ruchowa) to zdolność efektywnego wykorzystywania ciała do rozwiązywania problemów, tworzenia produktów lub wyrażania idei. Osoby silne w tym obszarze mają doskonałą koordynację wzrokowo-ruchową, precyzyjne umiejętności motoryczne i silne poczucie rytmu. Uczą się najlepiej przez działanie — poprzez ruch, dotyk i praktyczne doświadczenie, a nie przez czytanie czy słuchanie.</p>
<p>Sportowcy, tancerze, chirurdzy, rzemieślnicy, aktorzy i mechanicy często wyróżniają się w tej inteligencji. Aby ją rozwijać, warto regularnie uprawiać sport, podjąć się rękodzieła takiego jak stolarka czy robótki ręczne, spróbować tańca lub sztuk walki, ćwiczyć jogę lub tai chi, a także używać gestów i odgrywania ról podczas nauki. Budowanie rzeczy własnymi rękami — od gotowania po budowanie modeli — to jeden z najlepszych sposobów wzmacniania tej inteligencji.</p>',

                'F' => '<p>Inteligencja interpersonalna (społeczna) to zdolność rozumienia innych ludzi i efektywnego współdziałania z nimi. Osoby o silnej inteligencji interpersonalnej potrafią odczytywać nastroje, motywacje i intencje innych osób. Dobrze się komunikują, naturalnie współpracują i często przyjmują role mediatorów lub liderów w grupach.</p>
<p>Nauczyciele, doradcy, handlowcy, politycy, pracownicy socjalni i liderzy zespołów mają zazwyczaj wysoki poziom inteligencji interpersonalnej. Aby ją rozwijać, warto ćwiczyć aktywne słuchanie, angażować się w projekty grupowe, szukać różnorodnych sytuacji społecznych, uczyć się komunikacji niewerbalnej, zostać mentorem lub dołączyć do klubów i organizacji społecznych. Refleksja nad tym, jak nasze słowa i czyny wpływają na innych, jest również potężnym narzędziem rozwoju.</p>',

                'G' => '<p>Inteligencja intrapersonalna (refleksyjna) to zdolność do samoświadomości i introspekcji. Osoby o silnej inteligencji intrapersonalnej mają głębokie rozumienie własnych emocji, celów, mocnych stron i słabości. Są refleksyjne, samodzielnie zmotywowane i często wolą pracować niezależnie. Wyznaczają sobie znaczące cele i dobrze radzą sobie z samoregulacją.</p>
<p>Filozofowie, psycholodzy, teolodzy, pisarze i przedsiębiorcy często wykazują wysoki poziom inteligencji intrapersonalnej. Aby ją rozwijać, warto regularnie prowadzić dziennik, praktykować medytację lub uważność, wyznaczać cele osobiste i je przeglądać, wykonywać testy osobowości lub mocnych stron, spędzać czas w ciszy na refleksji, lub pracować z terapeutą czy coachem. Samopoznanie rośnie za każdym razem, gdy zatrzymamy się, by zapytać: „Co teraz czuję i dlaczego?"</p>',

                'H' => '<p>Inteligencja przyrodnicza to zdolność rozpoznawania, kategoryzowania i wykorzystywania cech środowiska naturalnego. Osoby o silnej inteligencji przyrodniczej są wyczulone na świat natury — dostrzegają wzorce w przyrodzie, lubią przebywać na świeżym powietrzu i często potrafią klasyfikować rośliny, zwierzęta, skały czy zjawiska pogodowe. Zwykle czują się lepiej na dworze niż w zamkniętych pomieszczeniach.</p>
<p>Biolodzy, ekolodzy, rolnicy, ogrodnicy, weterynarze i fotografowie przyrody mają zazwyczaj wysoki poziom inteligencji przyrodniczej. Aby ją rozwijać, warto spędzać więcej czasu na świeżym powietrzu, założyć ogród lub opiekować się roślinami, uczyć się rozpoznawania lokalnych ptaków i drzew, angażować się w działania na rzecz ochrony środowiska, prowadzić dziennik obserwacji przyrody lub oglądać filmy dokumentalne o ekosystemach i dzikiej przyrodzie. Nawet drobne czynności, takie jak obserwacja zmian pór roku podczas codziennego spaceru, mogą wzmocnić tę inteligencję.</p>',
            ),
        );
    }
}
