<?php
    namespace App\Controllers;
    use App\Models\LessonModel;
    class LessonController {
        private $lessonModel;
        private $lessonsJson;
        public function __construct() {
            $this->lessonModel = new LessonModel();
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
    }
?>