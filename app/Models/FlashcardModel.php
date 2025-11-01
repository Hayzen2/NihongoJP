<?php
namespace App\Models;
use PDO;
class FlashcardModel {
    private $pdo;

    private $id;
    private $topic;
    private $user_id;
    private $status;
    private $createdAt;
    private $updatedAt;
    public function __construct(
        $id = null,
        $topic = null,
        $user_id = null,
        $status = null,
        $createdAt = null,
        $updatedAt = null,
        $pdo = null
    ) {
        $this->id = $id;
        $this->topic = $topic;
        $this->user_id = $user_id;
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
    public function getUserId() {
        return $this->user_id;
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
    public function getUsernameByUserId($user_id) {
        $stmt = $this->pdo->prepare("SELECT username FROM users WHERE id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['username'];
    }
    public function getBySorting($search='', $sort='', $order='') {
        $orders = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        $sorts = in_array($sort, ['topic', 'created_at', 'updated_at']) ? $sort : 'created_at';

        //Base query
        $sql = "SELECT f.*, u.name AS author_name
            FROM flashcards f
            JOIN users u ON f.user_id = u.id ";
        $params = [];

        //search by topic
        if ($search) {
            $sql .= "WHERE f.topic LIKE :search OR u.name LIKE :search";
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
                $row['user_id'],
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
                $result['user_id'],
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

    public function create($topic, $user_id, $status) {
        $stmt = $this->pdo->prepare("INSERT INTO flashcards (topic, user_id, status, created_at, updated_at) VALUES (:topic, :user_id, :status, NOW(), NOW())");
        return $stmt->execute([
            ':topic' => $topic,
            ':user_id' => $user_id,
            ':status' => $status,
        ]);
    }

    public function update($id, $topic, $user_id, $status) {
        $stmt = $this->pdo->prepare("UPDATE flashcards SET topic = :topic, user_id = :user_id, status = :status, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':topic' => $topic,
            ':user_id' => $user_id,
            ':status' => $status,
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM flashcards WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>