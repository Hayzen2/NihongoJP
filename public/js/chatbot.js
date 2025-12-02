const chatbot = $('#chatbot');
const chatbotHeader = $('#chatbot-header');
const chatbotToggle = $('#chatbot-toggle');
const chatbotInput = $('#chatbot-input'); 
const chatbotMessages = $('#chatbot-messages');
const chatbotSend = $('#chatbot-send');
const chatbotBody = $('#chatbot-body');

chatbotToggle.on('click', () => {
    chatbot.toggleClass('minimized');
    chatbotToggle.html(chatbot.hasClass('minimized') ? 
    '<i class="bi bi-chevron-up"></i>' : 
    '<i class="bi bi-chevron-down"></i>');
});

chatbotSend.on('click', () => {
    sendMessage();
});

chatbotInput.on('keydown', (e) => {
    if(e.key === 'Enter') {
        sendMessage();
    }
});

function sendMessage() {
    const message = chatbotInput.val();
    if(!message) {
        return;
    }
    chatbotMessages.append(`<div class="chat-bubble user-msg">${message}</div>`);
    chatbotInput.val('');
    // Auto scroll to the user message
    chatbotMessages.scrollTop(chatbotMessages.prop('scrollHeight'));
    // Show bot typing indicator
    const typingId = 'typing-indicator';
    chatbotMessages.append(`<div id="${typingId}" class="chat-bubble bot-replying bot-msg">Bot replying</div>`);
    // Auto scroll to the typing indicator
    chatbotMessages.scrollTop(chatbotMessages.prop('scrollHeight'));
    $.post('/send-message', { message }, function(res) {
        if (res?.reply) {
            // Remove typing indicator
            $(`#${typingId}`).remove();
            chatbotMessages.append(`<div class="chat-bubble bot-msg">${res.reply}</div>`);
        } else {
            chatbotMessages.append(`<div class="chat-bubble bot-msg">No response from the bot.</div>`);
        }
        // Auto scroll to the bot reply
        chatbotMessages.scrollTop(chatbotMessages.prop('scrollHeight'));
    }, 'json');
}

// Draggable chatbot
let isDragging = false;
let offsetX = 0;
let offsetY = 0;

chatbotHeader.on("mousedown", (e) => { //only drag the header
    // ignore drag if resizing
    if ($(e.target).closest('.ui-resizable-handle').length) {
        return; // ignore drag if resizing
    }
    isDragging = true;
    // get the current mouse position
    const rect = chatbot[0].getBoundingClientRect();
    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;
});

$(document).on("mousemove", (e) => {
    if (!isDragging) {
        return;
    }
    chatbot.css({
        left: (e.clientX - offsetX) + "px",
        top: (e.clientY - offsetY) + "px",
        right: "auto",
        bottom: "auto"
    });
});

$(document).on("mouseup", () => {
    isDragging = false;
});
// Resizable chatbot
chatbot.resizable({
    handles: "n, e, s, w, ne, nw, se, sw",
    minWidth: 250,
    minHeight: 200,
    maxWidth: $(window).width() * 0.9,
    maxHeight: $(window).height() * 0.8,
    resize: function(event, ui) {
        // Update chatbot height
        const headerHeight = chatbotHeader.outerHeight();
        const inputWrapperHeight = $('.chatbot-input-wrapper').outerHeight();
        chatbotMessages.height(ui.size.height - headerHeight - inputWrapperHeight - 20); // padding
    }
});
// Auto scroll to the latest message
$(window).on('pageshow', () => {
    chatbotMessages.scrollTop(chatbotMessages.prop('scrollHeight'));
});