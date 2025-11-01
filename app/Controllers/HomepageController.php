<?php
    namespace App\Controllers;
    class HomepageController {
        public function showHomepage() {
            render('homepage', [
                'styles' => ['homepage'],
                'scripts' => ['createSakura']
            ]);

        }
    }
?>