<?php
    namespace App\Controllers;
    use App\Models\UserModel;
    class HomepageController {
        private $userModel;
        public function __construct() {
            $this->userModel = new UserModel();
        }
        public function showHomepage() {
            render('homepage', [
                'styles' => ['homepage'],
                'scripts' => ['createSakura'],
                
            ]);

        }
    }
?>