<?php
namespace App\Models;
use PDO;
use PDOException;

class QuizModel {
    private $pdo;
    public function __construct($pdo = null) {
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
        // set sensible PDO defaults if not already set
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function getAllQuizzes() {
        $stmt = $this->pdo->query("SELECT id, title, description, time_limit, jlpt_level FROM quizzes ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuizById(int $id) {
        $stmt = $this->pdo->prepare("SELECT id, title, description, time_limit, jlpt_level FROM quizzes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getQuestionsByQuizId(int $quizId) {
        $stmt = $this->pdo->prepare("
            SELECT id, question, correct_answer, wrong_answer_1, wrong_answer_2, wrong_answer_3
            FROM quiz_qa 
            WHERE quiz_id = ?
        ");
        $stmt->execute([$quizId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$r) {
            $choices = [];

            // Keep correct answer for grading
            $r['correct_answer'] = (string)$r['correct_answer'];

            // Build choices for display
            $choices[] = $r['correct_answer'];
            if (!empty($r['wrong_answer_1'])) {
                $choices[] = (string)$r['wrong_answer_1'];
            }
            if (!empty($r['wrong_answer_2'])) {
                $choices[] = (string)$r['wrong_answer_2'];
            }
            if (!empty($r['wrong_answer_3'])) {
                $choices[] = (string)$r['wrong_answer_3'];
            }

            shuffle($choices);
            $r['choices'] = $choices;
        }
        return $rows;
    }

    public function createAttempt(int $quizId, int $userId, float $score = 0.0, $completedAt = null) {
        $stmt = $this->pdo->prepare("INSERT INTO quiz_attempts (quiz_id, user_id, score, completed_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$quizId, $userId, $score, $completedAt]);
        return (int) $this->pdo->lastInsertId();
    }

    public function saveAnswer(int $attemptId, int $qaId, $selected, bool $isCorrect) {
        $stmt = $this->pdo->prepare("INSERT INTO quiz_attempt_answers (attempt_id, quiz_qa_id, selected_answers, is_correct) VALUES (?, ?, ?, ?)");
        $sel = is_array($selected) ? json_encode(array_values($selected), JSON_UNESCAPED_UNICODE) : (string)$selected;
        $stmt->execute([$attemptId, $qaId, $sel, $isCorrect ? 1 : 0]);
    }

    public function updateAttemptScore(int $attemptId, float $score) {
        $stmt = $this->pdo->prepare("UPDATE quiz_attempts SET score = ?, completed_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$score, $attemptId]);
    }

    public function getQuestionsMapByQuizId(int $quizId) {
        $stmt = $this->pdo->prepare("SELECT id, question, correct_answer FROM quiz_qa WHERE quiz_id = ?");
        $stmt->execute([$quizId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $map = [];
        foreach ($rows as $r) {
            $map[(int)$r['id']] = $r;
        }
        return $map;
    }
    public function getAttemptById(int $attemptId) {
        $stmt = $this->pdo->prepare("SELECT * FROM quiz_attempts WHERE id = ?");
        $stmt->execute([$attemptId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAttemptAnswers(int $attemptId) {
        $stmt = $this->pdo->prepare("SELECT * FROM quiz_attempt_answers WHERE attempt_id = ?");
        $stmt->execute([$attemptId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastAttemptByUser(int $quizId, int $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM quiz_attempts WHERE quiz_id = ? AND user_id = ? ORDER BY completed_at DESC LIMIT 1");
        $stmt->execute([$quizId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
