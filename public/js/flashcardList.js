$('#openModal').on('click', function() {
    $('#newFlashcardModal').removeClass('d-none');
});

$('#closeModal').on('click', function() {
    $('#newFlashcardModal').addClass('d-none');
});

let questionNumber = 1;

$('.btn-new-question').on('click', function() {
    questionNumber++;
    const input = $('<div></div>');
    input.html(`
       <div class="qa-item mb-4 p-3 rounded shadow-sm">
            <label for ="question" class="form-label">Question #${questionNumber}</label>
            <textarea id="question" class="form-control mb-2" rows="3" name="questions[]" placeholder="Enter question" required></textarea>

            <label for ="answer" class="form-label">Answer #${questionNumber}</label>
            <textarea id="answer" class="form-control mb-2" rows="3" name="answers[]" placeholder="Enter answer" required></textarea>

            <button type="button" class="btn btn-delete-qa">Delete</button>
        </div>
    `);
    $('#qa-container').append(input);
    attachDeleteHandlers();
    renumberQA();
});

function attachDeleteHandlers() {
    $('.btn-delete-qa').off('click').on('click', function() {
        $(this).parent().remove();
        renumberQA();
    });
}
function renumberQA() {
    const qaItems = $('.qa-item');
    questionNumber = qaItems.length;
    qaItems.each((index, item) => {
        const qLabel = $(item).find('label[for="question"]');
        const aLabel = $(item).find('label[for="answer"]');
        qLabel.text(`Question #${index + 1}`);
        aLabel.text(`Answer #${index + 1}`);
    });
}
$('.btn-create-flashcard').on('click', function() {
    $('#new-flashcard-form').submit();
});

function openDeleteModal(id) {
    const modal  = new bootstrap.Modal($('#deleteModal'));
    modal.show();
    const deleteButton = $('#confirmDelete');
    deleteButton.off('click').on('click', function() {
        window.location.href = `/flashcards/delete/${id}`;
    });
}