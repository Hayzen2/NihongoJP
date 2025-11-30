<?php
    namespace App\Controllers;
    use App\Models\VocabsModel;
    class LessonController {
        private $vocabsModel;
        private $lessonsJson;
        public function __construct() {
            $this->vocabsModel = new VocabsModel();
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
            $lessons = $this->lessonModel->getKanjiByJlptLevel($level);
            render('lessons/kanji-lessons', [
                'styles' => ['lessons/kanji'],
                'scripts' => [],
                'level' => $level,
                'lessons' => $lessons
            ]);
        }


    }
?>