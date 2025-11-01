<?php
    namespace App\Controllers;
    use App\Models\LessonModel;
    class LessonController {
        private $lessonModel;
        public function __construct() {
            $this->lessonModel = new LessonModel();
        }
        public function showLessonChoices() {
            render('lessons/lesson-choice', [
                'styles' => ['lessons/lesson-choice'],
                'scripts' => ['lesson-choice']
            ]);
        }
    }
?>