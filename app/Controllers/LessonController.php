<?php
    namespace App\Controllers;
    use App\Models\VocabsModel;
    use App\Models\KanjisModel;
    class LessonController {
        private $vocabsModel;
        private $lessonsJson;
        private $kanjisModel;
        public function __construct() {
            $this->vocabsModel = new VocabsModel();
            $this->kanjisModel = new KanjisModel();
            $lessonsJson = file_get_contents(__DIR__ . '/../../config/jsons/bunpro_full_grammar.json');
            $this->lessonsJson = json_decode($lessonsJson, true);
        }
        public function showLessonChoices() {
            render('lessons/lesson-choice', [
                'styles' => ['lessons/lesson-choice'],
                'scripts' => []
            ]);
        }
        public function showLessonsByLevelGrammar($level) {
            $level = strtoupper($level);

            // Filter lessons by level
            $levelLessons = array_filter($this->lessonsJson, fn($lesson) => $lesson['level'] === $level);

            render('lessons/grammar-lessons', [
                'styles' => ['lessons/grammar'],
                'scripts' => [],
                'level' => $level,
                'lessons' => $levelLessons
            ]);
        }
        public function showLessonsContentByTitleGrammar($level, $title) {
            $level = strtoupper($level);
            $title = urldecode($title);

            // Search for the grammar point by title in that level
            $levelLessons = array_filter($this->lessonsJson, fn($lesson) => $lesson['level'] === $level);
            $grammarPoint = null;

            // Search for the grammar point
            foreach ($levelLessons as $lesson) {
                foreach ($lesson['grammar_points'] ?? [] as $point) {
                    if ($point['title'] === $title) {
                        $grammarPoint = $point;
                        break 2; // Exit both loops
                    }
                }
            }

            render('lessons/grammar-content', [
                'styles' => ['lessons/grammar'],
                'scripts' => [],
                'level' => $level,
                'grammarPoint' => $grammarPoint
            ]);
        }

        public function showLessonsByLevelVocabulary($level) {
            $level = strtoupper($level);
            //Get filters
            $searches = $_GET['search'] ?? []; 
            if(!is_array($searches)) {
                $searches = [$searches];
            }

            //Pagination
            $page = max(1, intval($_GET['page'] ?? 1)); // Ensure page is at least 1
            $perPage = 20; // Number of words per page
            $offset = ($page - 1) * $perPage; // Calculate the offset based on the current page 

            $lessons = $this->vocabsModel->getVocabByJlptLevelFiltered(
                $level,
                $searches,
                $perPage,
                $offset
            );

            // Get total number of vocabulary words for the level
            $totalVocab = $this->vocabsModel->getTotalVocabByJlptLevelFiltered(
                $level,
                $searches
            );
            $totalPages = ceil($totalVocab / $perPage); // Calculate the total number of pages

            //Live search with pagination
            if(isset($_GET['ajax'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'lessons' => $lessons,
                    'totalPages' => $totalPages,
                    'currentPage' => $page,
                    'totalVocab' => $totalVocab
                ]);
                exit;
            }

            render('lessons/vocabulary', [
                'styles' => ['lessons/vocabulary'],
                'scripts' => ['vocabulary','drawboard'],
                'level' => $level,
                'lessons' => $lessons,
                'searches' => $searches,
                'page' => $page,
                'totalPages' => $totalPages,
                'totalVocab' => $totalVocab
            ]);
        }

        public function showLessonsByLevelKanji($level) {
            $level = strtoupper($level);
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = 50;

            $searches = $_GET['search'] ?? [];
            if (!is_array($searches)) {
                $searches = [$searches];
            }

            // Fetch all kanji for this level from DB
            $kanjiList = $this->kanjisModel->getKanjiList($level);

            // Filter by search term
            if (!empty($searches) && array_filter($searches)) {
                $searchTerms = array_filter(array_map('trim', $searches));
                $kanjiList = array_filter($kanjiList, function($k) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        if (mb_stripos($k['kanji'], $term) !== false) {
                            return true;
                        }
                        foreach ($k['meanings'] as $m) {
                            if (mb_stripos($m, $term) !== false) return true;
                        }
                    }
                    return false;
                });
                $kanjiList = array_values($kanjiList);
            }

            // Pagination
            $total = count($kanjiList);
            $totalPages = max(1, (int) ceil($total / $perPage));
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $perPage;
            $slice = array_slice($kanjiList, $offset, $perPage);

            if (isset($_GET['ajax'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'lessons' => $slice,
                    'totalPages' => $totalPages,
                    'currentPage' => $page,
                    'totalKanji' => $total
                ]);
                exit;
            }

            render('lessons/kanji-list', [
                'styles' => ['lessons/kanji-list'],
                'scripts' => ['kanji', 'drawboard'],
                'level' => $level,
                'lessons' => $slice,
                'page' => $page,
                'totalPages' => $totalPages,
                'totalKanji' => $total
            ]);
        }

        public function showLessonsContentByTitleKanji($level, $title) {
            // Decode the kanji from URL (%E4%BA%8B -> 事)
            $kanjiChar = urldecode($title);

            $apiKey = $_ENV['KANJIALIVE_API_KEY'];
            $url = "https://kanjialive-api.p.rapidapi.com/api/public/kanji/{$kanjiChar}";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "X-RapidAPI-Key: $apiKey",
                "X-RapidAPI-Host: kanjialive-api.p.rapidapi.com"
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $kanjiData = null;
            if ($result && $httpCode === 200) {
                $kanjiData = json_decode($result, true);
                if (isset($kanjiData['error'])) {
                    $kanjiData = null;
                }
            }

            render('lessons/kanji-detail', [
                'styles' => ['lessons/kanji-detail'],
                'scripts' => [],
                'kanjiData' => $kanjiData,
                'level' => $level
            ]);
        }
    }
?>