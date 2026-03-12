<?php
namespace App\Models;
use PDO;

class KanjisModel {
    private $pdo;

    public function __construct($pdo = null) {
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
    }

    public function getKanjiList(string $level): array {
        $stmt = $this->pdo->prepare("SELECT kanji, meanings FROM kanjis WHERE jlpt_level = ? ORDER BY kanji ASC");
        $stmt->execute([$level]);
        $kanjis = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($kanjis as &$k) {
            $k['meanings'] = json_decode($k['meanings'], true) ?: [];
        }

        return $kanjis;
    }
}
?>
