<?php
    namespace App\Controllers;
    use App\Models\FlashcardModel;
    use App\Models\FlashcardQAModel;
    class FlashcardController {
        private $flashcardModel;
        private $flashcardQAModel;
        public function __construct() {
            $this->flashcardModel = new FlashcardModel();
            $this->flashcardQAModel = new FlashcardQAModel();
        }
        public function showFlashcardList() {
            
            $search = $_GET['search'] ?? '';
            $sort = $_GET['sort'] ?? 'created_at';
            $order = $_GET['order'] ?? 'desc';
            $status = $_GET['status'] ?? 'public';
            $flashcards = $this->flashcardModel->getBySorting($search, $sort, $order, $status);

            if (isset($_GET['ajax'])) {
                $data = [];
                foreach ($flashcards as $card) {
                    $data [] = [
                       'id' => $card->getId(),
                       'topic' => $card->getTopic(),
                       'author' => $card->getAuthor(),
                        'status' => $card->getStatus(),
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
                'styles' => ['flashcards/flashcard-list', 'flashcards/flashcard-form'],
                'scripts' => ['sorting', 'flashcardList'],
                'search' => $search,
                'sort' => $sort,
                'order' => $order,
                'status' => $status
            ]);
            
        }

        public function createNewFlashcard() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $topic = $_POST['topic'] ?? '';
                $author = $_POST['author'] ?? '';
                $status = $_POST['status'] ?? 'public';
                $questions = $_POST['questions'] ?? [];
                $answers = $_POST['answers'] ?? [];
                if (!$topic || !$author || empty($questions) || empty($answers)) {
                    http_response_code(400);
                    echo 'All fields are required.';
                    return;
                }
                $this->flashcardModel->create($topic, $author, $status);
                $flashcardId = $this->flashcardModel->getLastInsertedId();

                foreach ($questions as $index => $question) { // Use index to match question with answer
                    $answer = $answers[$index] ?? '';
                    if ($question && $answer) {
                        $this->flashcardQAModel->create($flashcardId, $question, $answer);
                    }
                }
                header('Location: /flashcards');
            } else {
                http_response_code(405);
                echo 'Method Not Allowed';
            }
        }
        public function deleteFlashcard($id) {
            $flashcard = $this->flashcardModel->getByID($id);
            if(!$flashcard){
                http_response_code(404);
                echo 'Flashcard not found';
                return;
            }
            $this->flashcardModel->delete($id);
            header('Location: /flashcards');
            exit;
        }


        public function showFlashcardContent($flashcardId) {
            if(isset($_GET['reshuffle'])){
                // Clear the session variable to reshuffle questions
                $sessionKey = 'flashcard_order__' . $flashcardId;
                unset($_SESSION[$sessionKey]);
            }
            $flashcard = $this->flashcardModel->getByID($flashcardId);
            if(!$flashcard){
                http_response_code(404);
                echo 'Flashcard not found';
                return;
            }
            $sessionKey = 'flashcard_order__' . $flashcardId; // flashcard_order__1
            if (!isset($_SESSION[$sessionKey])) {
                // First visit, initialize session variable
                $flashcardQASet = $this->flashcardQAModel->getRandomQuestionSet($flashcardId);
                if(!$flashcardQASet){
                    http_response_code(404);
                    echo 'Flashcard not found';
                    return;
                }
                // Store the order of question IDs in session
                $_SESSION[$sessionKey] = array_map(function($qa) {
                    return $qa->getId();
                }, $flashcardQASet);
            } else {
                // Reuse the existing order from session
                $orderedIds = $_SESSION[$sessionKey]; 
                // Fetch questions in the stored order
                $flashcardQASet = [];
                foreach ($orderedIds as $id) {
                    $qa = $this->flashcardQAModel->getByID($id);
                    if ($qa) {
                        $flashcardQASet[] = $qa;
                    }
                }
            }
            
            
            render('flashcards/flashcard',[
                'flashcard' => $flashcard,
                'flashcardQASet' => $flashcardQASet,
                'scripts' => ['flashcardControls'],
                'styles' => ['flashcards/flashcard']
            ]);
        }
        
    }
?>