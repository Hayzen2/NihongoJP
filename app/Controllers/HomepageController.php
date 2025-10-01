<?php
    namespace App\Controllers;
    require_once __DIR__ . '/../../resources/helpers/render.php';
    class HomepageController {
        public function showHomepage() {
            render('homepage', [
                'style' => 'homepage',
                'script' => 'createSakura'
            ]);

        }
    }
?>