<div id="chatbot">
    <div id="chatbot-header">
        💬 Chat
        <span id="chatbot-toggle"><i class="bi bi-chevron-down"></i></span>
    </div>
    <div id="chatbot-body">
        <div id="chatbot-messages">
            <?php
            if (isset($_SESSION['chat_history'])) {
                foreach ($_SESSION['chat_history'] as $entry) {
                    $msg = $entry['message'];
                    $class = ($entry['sender'] === 'user') ? 'user-msg' : 'bot-msg';
                    echo "<div class='chat-bubble $class'>$msg</div>";
                }
            }
            ?>
        </div>
        <div class="chatbot-input-wrapper">
            <input id="chatbot-input" type="text" placeholder="Type a message..." />
            <button id="chatbot-send" class="chatbot-send-btn" >
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>
