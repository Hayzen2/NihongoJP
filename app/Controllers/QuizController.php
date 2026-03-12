<?php
namespace App\Controllers;
use App\Models\QuizModel;
use Exception;

class QuizController {
    private $quizModel;
    public function __construct() {
        $this->quizModel = new QuizModel();
    }

    public function showQuizLists() {
        $quizzes = $this->quizModel->getAllQuizzes();
        $userId = $_SESSION['user']['id'] ?? null;
        $lastAttempts = [];
        if ($userId) {
            foreach ($quizzes as $q) {
                $lastAttempts[$q['id']] = $this->quizModel->getLastAttemptByUser($q['id'], $userId);
            }
        }

        render('quizzes/quiz-list', [
            'styles' => ['quiz'],
            'scripts' => [],
            'quizzes' => $quizzes,
            'lastAttempts' => $lastAttempts
        ]);
    }

    public function showQuiz($id) {
        $id = (int)$id;
        $quiz = $this->quizModel->getQuizById($id);
        if (!$quiz) {
            http_response_code(404);
            echo "Quiz not found.";
            return;
        }

        // Get questions
        $questions = $this->quizModel->getQuestionsByQuizId($id);

        // Get correct answers map
        $qaMap = $this->quizModel->getQuestionsMapByQuizId($id); 
        $correctAnswers = [];
        // Build correct answers map
        foreach ($qaMap as $row) {
            $correctAnswers[(int)$row['id']] = (string)$row['correct_answer'];
        }

        $userId = $_SESSION['user']['id'] ?? null;
        $attempt = null;
        $prevAnswers = [];

        // Check if this is a retake
        $isRetake = isset($_GET['retake']) && $_GET['retake'] == "1";

        if ($userId && !$isRetake) {
            // Load previous attempt only if NOT retake
            $attempt = $this->quizModel->getLastAttemptByUser($id, $userId);

            if ($attempt) {
                $answersData = $this->quizModel->getAttemptAnswers($attempt['id']);
                foreach ($answersData as $a) {
                    $decoded = json_decode($a['selected_answers'], true);
                    $prevAnswers[$a['quiz_qa_id']] = $decoded ?: $a['selected_answers'];
                }
            }
        }

        // If retake: force fresh quiz
        if ($isRetake) {
            $attempt = null;
            $prevAnswers = [];
        }

        render('quizzes/quiz-take', [
            'styles' => ['quiz'],
            'scripts' => [],
            'quiz' => $quiz,
            'questions' => $questions,
            'attempt' => $attempt,
            'prevAnswers' => $prevAnswers,
            'correctAnswers' => $correctAnswers,
        ]);
    }

    public function submitAttempt() {
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) { http_response_code(401); exit; }

        $raw = file_get_contents('php://input');
        $payload = json_decode($raw, true) ?: $_POST;
        $quizId = intval($payload['quiz_id'] ?? 0);
        $answers = $payload['answers'] ?? [];

        if (!$quizId || !is_array($answers)) { http_response_code(400); exit; }

        $questionsMap = $this->quizModel->getQuestionsMapByQuizId($quizId);
        $pdo = (new \ReflectionClass($this->quizModel))->getProperty('pdo');
        $pdo->setAccessible(true);
        $db = $pdo->getValue($this->quizModel);

        $db->beginTransaction();
        try {
            $total = count($questionsMap);
            $correct = 0;
            $attemptId = $this->quizModel->createAttempt($quizId, $userId);

            foreach ($answers as $qaIdRaw => $selected) {
                $qaId = intval($qaIdRaw);
                if (!isset($questionsMap[$qaId])) continue;
                $correctAnswer = $questionsMap[$qaId]['correct_answer'];
                $isCorrect = is_array($selected)
                    ? in_array($correctAnswer, array_map('strval', $selected), true)
                    : trim((string)$selected) === trim($correctAnswer);
                if ($isCorrect) $correct++;
                $this->quizModel->saveAnswer($attemptId, $qaId, $selected, $isCorrect);
            }

            $score = $total > 0 ? round(($correct/$total)*100, 2) : 0;
            $this->quizModel->updateAttemptScore($attemptId, $score);

            header('Content-Type: application/json');
            echo json_encode([
                'attempt_id' => $attemptId,
                'score' => $score,
                'correct' => $correct,
                'total_questions' => $total
            ]);
            exit;
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
            exit;
        }
    }

    public function retakeQuiz($id)
    {
        $id = (int)$id;
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$userId) {
            header("Location: /login");
            exit;
        }

        header("Location: /quizzes/view/$id?retake=1");
        exit;
    }
}
