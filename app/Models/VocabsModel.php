<?php

namespace App\Models;
use PDO;

class VocabsModel {
    private $pdo;
    private $id;
    private $word;
    private $reading;
    private $meaning;
    private $jlpt_level;
    public function __construct(
        $pdo = null,
        $id = null,
        $word = null,
        $reading = null,
        $meaning = null,
        $jlpt_level = null
    ) {
        $this->id = $id;
        $this->word = $word;
        $this->reading = $reading;
        $this->meaning = $meaning;
        $this->jlpt_level = $jlpt_level;
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
    }

    //getters
    public function getId() { return $this->id; }
    public function getWord() { return $this->word; }
    public function getReading() { return $this->reading; }
    public function getMeaning() { return $this->meaning; }
    public function getJlptLevel() { return $this->jlpt_level; }

    //setters
    public function setId($id) { $this->id = $id; }
    public function setWord($word) { $this->word = $word; }
    public function setReading($reading) { $this->reading = $reading; }
    public function setMeaning($meaning) { $this->meaning = $meaning; }
    public function setJlptLevel($jlpt_level) { $this->jlpt_level = $jlpt_level; }

    public function getVocabByJlptLevelFiltered($level, $searches, $perPage, $offset)
    {
        if (!is_array($searches)) {
            $searches = [$searches];
        }
        $sql = "SELECT * FROM vocabs WHERE jlpt_level = :level";
        $params = [':level' => $level];

        if (!empty($searches)) {
            $orClauses = [];
            for ($i = 0; $i < count($searches); $i++) {
                $search = $searches[$i];
                $orClauses[] = "(word LIKE :search{$i} OR reading LIKE :search{$i} OR meaning LIKE :search{$i})";
                $params[":search{$i}"] = "%{$search}%"; 
            }
            $sql .= " AND (" . implode(" OR ", $orClauses) . ")";
        }

        // Limit and offset 
        $sql .= " ORDER BY word ASC LIMIT " . intval($perPage) . " OFFSET " . intval($offset);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalVocabByJlptLevelFiltered($level, $searches)
    {
        if (!is_array($searches)) {
            $searches = [$searches];
        }
        $sql = "SELECT COUNT(*) FROM vocabs WHERE jlpt_level = :level";
        $params = [':level' => $level];

        if (!empty($searches)) {
            $orClauses = [];
            for ($i = 0; $i < count($searches); $i++) {
                $search = $searches[$i];
                $orClauses[] = "(word LIKE :search{$i} OR reading LIKE :search{$i} OR meaning LIKE :search{$i})";
                $params[":search{$i}"] = "%{$search}%"; 
            }
            $sql .= " AND (" . implode(" OR ", $orClauses) . ")";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn(); // Fetch the total count
    }
}