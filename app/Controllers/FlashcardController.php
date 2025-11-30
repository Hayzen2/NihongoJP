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
            $error = $_GET['error'] ?? '';
            $perPage = 5;
            //Private search, sort, and order parameters
            $privateSearch = $_GET['private_search'] ?? '';
            $privateSort = $_GET['private_sort'] ?? 'created_at';
            $privateOrder = $_GET['private_order'] ?? 'desc';
            $privatePage = $_GET['private_page'] ?? 1;
            $privateOffset = ($privatePage - 1) * $perPage;

            //Public search, sort, and order parameters
            $publicSearch = $_GET['public_search'] ?? '';
            $publicSort = $_GET['public_sort'] ?? 'topic';
            $publicOrder = $_GET['public_order'] ?? 'asc';
            $publicPage = $_GET['public_page'] ?? 1;
            $publicOffset = ($publicPage - 1) * $perPage;

            //Query separately
            $privateFlashcards = $this->flashcardModel->getBySortingPrivate($privateSearch, $perPage, $privateOffset, $privateSort, $privateOrder, $_SESSION['user']['id']);
            $publicFlashcards = $this->flashcardModel->getBySortingPublic($publicSearch, $perPage, $publicOffset, $publicSort, $publicOrder);

            $totalPrivateFlashcards = $this->flashcardModel->getTotalPrivateFlashcards($privateSearch , $_SESSION['user']['id']);
            $totalPublicFlashcards = $this->flashcardModel->getTotalPublicFlashcards($publicSearch);

            $totalPagesPrivate = ceil($totalPrivateFlashcards / $perPage);
            $totalPagesPublic = ceil($totalPublicFlashcards / $perPage);
            //definition: ajax is a request made to fetch data without reloading the page
            if (isset($_GET['ajax'])) {
                header('Content-Type: application/json');
                $type = $_GET['type'] ?? 'private';
                $data = [];

                if ($type === 'private') {
                    foreach ($privateFlashcards as $card) {
                        $data['privateFlashcards'][] = [
                            'id' => $card->getId(),
                            'topic' => $card->getTopic(),
                            'created_at' => $card->getCreatedAt(),
                            'updated_at' => $card->getUpdatedAt(),
                        ];
                    }
                    $data['currentPage'] = (int)$privatePage;
                    $data['totalPages'] = $totalPagesPrivate;
                }
                if($type === 'public') {
                    foreach ($publicFlashcards as $card) {
                        $data['publicFlashcards'][] = [
                            'id' => $card->getId(),
                            'topic' => $card->getTopic(),
                            'author' => $card->getUsernameByUserId($card->getUserId())
                            ?? $card->getNameByUserId($card->getUserId()),
                            'created_at' => $card->getCreatedAt(),
                            'updated_at' => $card->getUpdatedAt(),
                            
                        ];
                    }
                    $data['currentPage'] = (int)$publicPage;
                    $data['totalPages'] = $totalPagesPublic;
                }
                echo json_encode($data);
                exit; // Stop further execution
            }
            render('flashcards/flashcard-list', [
                'error' => $error,
                'privateFlashcards' => $privateFlashcards,
                'publicFlashcards'  => $publicFlashcards,
                'privateSearch' => $privateSearch,
                'publicSearch'  => $publicSearch,
                'privateSort'   => $privateSort,
                'publicSort'    => $publicSort,
                'privateOrder'  => $privateOrder,
                'publicOrder'   => $publicOrder,
                'styles'  => ['flashcards/flashcard-list', 'flashcards/flashcard-form'],
                'scripts' => ['flashcardList', 'flashcard-sorting']
            ]);
            
        }

        public function createNewFlashcard() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $topic = $_POST['topic'] ?? '';
                $status = $_POST['status'] ?? 'public';
                $questions = $_POST['questions'] ?? [];
                $answers = $_POST['answers'] ?? [];
                if (!$topic || empty($questions) || empty($answers)) {
                    $error = 'Please fill in all the required fields.';
                    header('Location: /flashcards?error=' . urlencode($error));
                    return;
                }
                $this->flashcardModel->create($topic, $_SESSION['user']['id'], $status);
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
                $error = 'Flashcard not found!';
                render('flashcards/flashcard-list', [
                    'error' => $error,
                    'styles' => ['flashcards/flashcard-list', 'flashcards/flashcard-form'],
                    'scripts' => ['flashcardList', 'flashcard-sorting']
                ]);
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