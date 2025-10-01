<?php
    namespace App\Controllers;
    use App\Models\FlashcardModel;
    require_once __DIR__ . '/../../resources/helpers/render.php';
    class FlashcardController {
        private $flashcardModel;
        public function __construct() {
            $this->flashcardModel = new FlashcardModel();
        }
        public function showFlashcardList() {
            
            $search = $_GET['search'] ?? '';
            $sort = $_GET['sort'] ?? 'created_at';
            $order = $_GET['order'] ?? 'desc';
            $flashcards = $this->flashcardModel->getBySorting($search, $sort, $order);

            if (isset($_GET['ajax'])) {
                $data = [];
                foreach ($flashcards as $card) {
                    $data [] = [
                       'id' => $card->getId(),
                       'topic' => $card->getTopic(),
                       'author' => $card->getAuthor(),
                       'created_at' => $card->getCreatedAt(),
                       'updated_at' => $card->getUpdatedAt(),
                    ];
                }
                header('Content-Type: application/json');
                echo json_encode($data);
                exit; // Stop further execution
            }
            render('flashcards/flashcard-list',[
                'flashcards' => $flashcards,
                'style' => 'flashcard-list',
                'script' => 'sorting',
                'search' => $search,
                'sort' => $sort,
                'order' => $order
            ]);
            
        }

        public function showFlashcardContent($id) {
            $flashcard = $this->flashcardModel->getByID($id);
            if(!$flashcard){
                http_response_code(404);
                echo 'Flashcard not found';
                return;
            }
            render('flashcards/flashcard',[
                'flashcard' => $flashcard,
                'style' => 'flashcard'
            ]);
        }
        
    }
?>