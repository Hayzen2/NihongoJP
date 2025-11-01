<?php
namespace App\Models;
use PDO;
class FlashcardModel {
    private $pdo;

    private $id;
    private $topic;
    private $author;
    private $status;
    private $createdAt;
    private $updatedAt;
    public function __construct(
        $id = null,
        $topic = null,
        $author = null,
        $status = null,
        $createdAt = null,
        $updatedAt = null,
        $pdo = null
    ) {
        $this->id = $id;
        $this->topic = $topic;
        $this->author = $author;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
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
    public function getStatus() {
        return $this->status;
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
                $row['status'],
                $row['created_at'],
                $row['updated_at']
            );
        }

        return $flashcards;
    }
    public function getByID($id){
        $stmt =  $this->pdo->prepare("SELECT * FROM flashcards WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return new FlashcardModel(
                $result['id'],
                $result['topic'],
                $result['author'],
                $result['status'],
                $result['created_at'],
                $result['updated_at']
            );
        }
        return null;
    }
    public function getLastInsertedId() {
        return $this->pdo->lastInsertId();
    }

    public function create($topic, $author, $status) {
        $stmt = $this->pdo->prepare("INSERT INTO flashcards (topic, author, status, created_at, updated_at) VALUES (:topic, :author, :status, NOW(), NOW())");
        return $stmt->execute([
            ':topic' => $topic,
            ':author' => $author,
            ':status' => $status,
        ]);
    }

    public function update($id, $topic, $author, $status) {
        $stmt = $this->pdo->prepare("UPDATE flashcards SET topic = :topic, author = :author, status = :status, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':topic' => $topic,
            ':author' => $author,
            ':status' => $status,
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM flashcards WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>