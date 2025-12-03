const newContainer = '#new-qa-container';
const editContainer = '#edit-qa-container';

// delete QA
$(document).on('click', '.btn-delete-qa', function (e) {
  e.preventDefault();
  const $qaItem = $(this).closest('.qa-item');
  const $container = $qaItem.closest('.qa-item').parent();
  $qaItem.remove();
  updateDeleteButtons($container);
  renumberQA($container);
});

// add QA (new modal)
$('.btn-new-question-new').on('click', function () {
  const $container = $(newContainer);
  const nextNum = $container.find('.qa-item').length + 1;
  const html = `
    <div class="qa-item mb-4 p-3 rounded shadow-sm">
      <label class="form-label">Question #${nextNum}</label>
      <textarea class="form-control mb-2" rows="3" name="questions[]" placeholder="Enter question" required></textarea>

      <label class="form-label">Answer #${nextNum}</label>
      <textarea class="form-control mb-2" rows="3" name="answers[]" placeholder="Enter answer" required></textarea>

      <button type="button" class="btn btn-delete-qa">Delete</button>
    </div>
  `;
  $container.append(html);
  updateDeleteButtons($container);
  renumberQA($container);
});

// add QA (edit modal)
$('.btn-new-question-edit').on('click', function () {
  const $container = $(editContainer);
  const nextNum = $container.find('.qa-item').length + 1;
  const html = `
    <div class="qa-item mb-4 p-3 rounded shadow-sm">
      <label class="form-label">Question #${nextNum}</label>
      <textarea class="form-control mb-2" rows="3" name="questions[]" placeholder="Enter question" required></textarea>

      <label class="form-label">Answer #${nextNum}</label>
      <textarea class="form-control mb-2" rows="3" name="answers[]" placeholder="Enter answer" required></textarea>

      <button type="button" class="btn btn-delete-qa">Delete</button>
    </div>
  `;
  $container.append(html);
  updateDeleteButtons($container);
  renumberQA($container);
});

// ensure delete buttons exist only when >1 items
function updateDeleteButtons($containerOrSelector) {
  const $container = $($containerOrSelector);
  const $items = $container.find('.qa-item');
  if ($items.length <= 1) {
    // remove delete buttons entirely when only 1 item
    $items.find('.btn-delete-qa').remove();
  } else {
    // ensure every item has one delete button
    $items.each(function () {
      if ($(this).find('.btn-delete-qa').length === 0) {
        $(this).append('<button type="button" class="btn btn-delete-qa">Delete</button>');
      }
    });
  }
}

// renumber question/answer labels
function renumberQA($containerOrSelector) {
  const $container = $($containerOrSelector);
  $container.find('.qa-item').each((index, item) => {
    const $labels = $(item).find('.form-label');
    // first label -> Question, second -> Answer
    if ($labels.eq(0).length) $labels.eq(0).text(`Question #${index + 1}`);
    if ($labels.eq(1).length) $labels.eq(1).text(`Answer #${index + 1}`);
  });

  // update counters (optional, keep in sync if you still use them)
  if ($container.is(newContainer)) {
    questionNumberNew = $container.find('.qa-item').length;
  } else if ($container.is(editContainer)) {
    questionNumberEdit = $container.find('.qa-item').length;
  }
}

function openEditModal(id){
  $('#edit-qa-container').empty();
  $('#edit-flashcard-form').attr('action', `/flashcards/update/${id}`);

  $.get(`/flashcards/get/${id}`, function(data) {
    if (!data || !data.id) {
      alert('Flashcard data not found');
      return;
    }

    $('#topic-edit').val(data.topic);
    $('#status-edit').val(data.status);

    // append items (no delete buttons decided yet)
    data.flashcardQASet.forEach((qa, index) => {
      const html = `
      <div class="qa-item mb-4 p-3 rounded shadow-sm">
        <label class="form-label">Question #${index + 1}</label>
        <textarea class="form-control mb-2" rows="3" name="questions[]" required>${qa.question}</textarea>

        <label class="form-label">Answer #${index + 1}</label>
        <textarea class="form-control mb-2" rows="3" name="answers[]" required>${qa.answer}</textarea>
      </div>
      `;
      $('#edit-qa-container').append(html);
    });

    // then ensure delete buttons exist only when >1 items
    updateDeleteButtons('#edit-qa-container');
    renumberQA('#edit-qa-container');

    // set counter variables
    questionNumberEdit = Math.max(1, data.flashcardQASet.length); // ensure at least 1
    $('#editFlashcardModal').removeClass('d-none');
  });
}

$('#openModal').on('click', () => $('#newFlashcardModal').removeClass('d-none'));
$('#closeModal').on('click', () => $('#newFlashcardModal').addClass('d-none'));
$('#edit-closeModal').on('click', () => $('#editFlashcardModal').addClass('d-none'));

$('.overlay .btn-add-flashcard').on('click', () => $('#new-flashcard-form').submit());
$('.overlay .btn-edit-flashcard').on('click', () => $('#edit-flashcard-form').submit());

// initialize
$(function () {
  updateDeleteButtons(newContainer);
  updateDeleteButtons(editContainer);
  renumberQA(newContainer);
  renumberQA(editContainer);
});
