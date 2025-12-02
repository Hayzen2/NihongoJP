<?php
    namespace App\Controllers;
    use App\Models\ChatbotModel;
    use Parsedown;
    class ChatbotController {
        private $chatbotModel;
        private $parsedown;
        public function __construct() {
            $this->chatbotModel = new ChatbotModel();
            $this->parsedown = new Parsedown();
        }

        public function sendMessage() {
            $message = $_POST['message'] ?? '';
            $messageHtml = $this->parsedown->text($message);
            $history = $_SESSION['chat_history'] ?? [];
            $botReply = $this->chatbotModel->sendMessage($message, $history);
            $botReplyHtml = $this->parsedown->text($botReply);
            // Update history
            $_SESSION['chat_history'][] = ['sender' => 'user', 'message' => $messageHtml];
            $_SESSION['chat_history'][] = ['sender' => 'bot', 'message' => $botReplyHtml];
            // Return JSON
            header('Content-Type: application/json');
            echo json_encode(['reply' => $botReplyHtml]);
            exit;
        }
    }
?>