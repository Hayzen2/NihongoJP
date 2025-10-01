<?php
namespace App\Models;
use PDO;
class FlashcardModel {
    private $pdo;

    private $id;
    private $topic;
    private $author;
    private $questions;
    private $answers;
    private $createdAt;
    private $updatedAt;
    public function __construct(
        $id = null,
        $topic = null,
        $author = null,
        $questions = null,
        $answers = null,
        $createdAt = null,
        $updatedAt = null
    ) {
        $this->id = $id;
        $this->topic = $topic;
        $this->author = $author;
        $this->question = $questions;
        $this->answer = $answers;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        require_once __DIR__ . '/../../config/setup_database.php';
        $this->pdo = getPDO();
    }
    public function getId() {
        return $this->id;
    }
    public function getTopic(){
        return $this->topic;
    }
    public function getAuthor() {
        return $this->author;
    }
    public function getQuestions() {
        return $this->questions;
    }
    public function getAnswers() {
        return $this->answers;
    }
    public function getCreatedAt() {
        return $this->createdAt;
    }
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
    public function getBySorting($search='', $sort='', $order='') {
        $orders = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        $sorts = in_array($sort, ['topic', 'created_at', 'updated_at']) ? $sort : 'created_at';

        //Base query
        $sql = "SELECT * FROM flashcards ";
        $params = [];

        //search by topic
        if ($search) {
            $sql .= "WHERE topic LIKE :search or author LIKE :search";
            $params[':search'] = "%$search%"; 
        }

        $sql .= " ORDER BY $sorts $orders";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $flashcards = [];
        foreach ($results as $row) {
            $flashcards[] = new FlashcardModel(
                $row['id'],
                $row['topic'],
                $row['author'],
                $row['question'],
                $row['answer'],
                $row['created_at'],
                $row['updated_at']
            );
        }

        return $flashcards;
    }
    public function getByID($id){
        $stmt = $this->pdo->prepare("SELECT * FROM flashcards WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return new FlashcardModel(
                $result['id'],
                $result['topic'],
                $result['author'],
                $result['question'],
                $result['answer'],
                $result['created_at'],
                $result['updated_at']
            );
        }
        return null;
    }
}
?>