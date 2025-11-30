<?php
namespace App\Models;
use PDO;
class FlashcardQAModel {
    private $id;
    private $flashcardId;
    private $question;
    private $answer;
    private $pdo;
    public function __construct($id = null , $flashcardId = null, $question = null, $answer = null, $pdo = null) {
        $this->id = $id;
        $this->flashcardId = $flashcardId;
        $this->question = $question;
        $this->answer = $answer;
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
    }
    //getters
    public function getId() { return $this->id; }
    public function getFlashcardId() { return $this->flashcardId; }
    public function getQuestion() { return $this->question; }
    public function getAnswer() { return $this->answer; }

    //setters
    public function setId($id) { $this->id = $id; }
    public function setFlashcardId($flashcardId) { $this->flashcardId = $flashcardId; }
    public function setQuestion($question) { $this->question = $question; }
    public function setAnswer($answer) { $this->answer = $answer; }

    public function getByID($id){
        $stmt = $this->pdo->prepare("SELECT * FROM flashcards_qa WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return new FlashcardQAModel(
                $result['id'],
                $result['flashcard_id'],
                $result['question'],
                $result['answer'],
            );
        }
        return null;
    }
    public function getByFlashcardID($flashcardId){
        $stmt = $this->pdo->prepare("SELECT * FROM flashcards_qa WHERE flashcard_id = :flashcard_id");
        $stmt->execute([':flashcard_id' => $flashcardId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return new FlashcardQAModel(
                $result['id'],
                $result['flashcard_id'],
                $result['question'],
                $result['answer'],
            );
        }
        return null;
    }

    public function create($flashcardId, $question, $answer) {
        $stmt = $this->pdo->prepare("INSERT INTO flashcards_qa (flashcard_id, question, answer) VALUES (:flashcard_id, :question, :answer)");
        return $stmt->execute([
            ':flashcard_id' => $flashcardId,
            ':question' => $question,
            ':answer' => $answer,
        ]);
    }
    public function update($id, $flashcardId, $question, $answer) {
        $stmt = $this->pdo->prepare("UPDATE flashcards_qa SET flashcard_id = :flashcard_id, question = :question, answer = :answer WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':flashcard_id' => $flashcardId,
            ':question' => $question,
            ':answer' => $answer,
        ]);
    }
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM flashcards_qa WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getRandomQuestionSet($flashcardId) {
        $stmt = $this->pdo->prepare("
        SELECT id, flashcard_id, question, answer 
        FROM flashcards_qa 
        WHERE flashcard_id = :flashcard_id 
        ORDER BY RAND()
        ");
        $stmt->execute([':flashcard_id' => $flashcardId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return null;
        }

        $result = [];
        foreach ($rows as $row) {
            $result[] = new FlashcardQAModel(
                $row['id'],
                $row['flashcard_id'],
                $row['question'],
                $row['answer']
            );
        }
        return $result;
    }
}