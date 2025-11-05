<?php
namespace App\Models;

use PDO;

class BookModel {
    private $pdo;
    public function __construct($pdo = null) {
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
    }

    // Get all books with their JLPT levels
    public function getAllBooks() {
        $sql = "SELECT b.*, GROUP_CONCAT(j.jlpt_level ORDER BY j.jlpt_level SEPARATOR ',') AS jlpt_levels
                FROM books b
                LEFT JOIN book_jlpt_levels j ON b.id = j.book_id
                GROUP BY b.id
                ORDER BY b.published_date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get books filtered by a single JLPT level
    public function getBooksByJlptLevel(string $level) {
        $sql = "SELECT b.*, GROUP_CONCAT(j.jlpt_level ORDER BY j.jlpt_level SEPARATOR ',') AS jlpt_levels
                FROM books b
                JOIN book_jlpt_levels j ON b.id = j.book_id
                WHERE j.jlpt_level = :level
                GROUP BY b.id
                ORDER BY b.published_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':level' => $level]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
