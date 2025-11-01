$('.controls').on('submit', e => {
    e.preventDefault(); // stop Enter from reloading page
});
const searchInput = $('input[name="search"]');
const sortSelect = $('select[name="sort"]');
const orderSelect = $('select[name="order"]');
const flashcardBody = $('.flashcard-list tbody');

function renderFlashcards(flashcards) {
    const rows = flashcards.map(flashcard => `
        <tr>
            <td class="fw-bold" data-label="Topic">${flashcard.topic}</td>
            <td class="fw-bold" data-label="Author">${flashcard.author}</td>
            <td data-label="Status">${flashcard.status === 'public' ? '🌍 Public' : '🔒 Private'}</td>
            <td data-label="Created At">${formatDate(flashcard.created_at)}</td>
            <td data-label="Updated At">${formatDate(flashcard.updated_at)}</td>
            <td>
                <a href="/flashcards/view/${flashcard.id}" class="btn btn-info">View</a>
                <a href="/flashcards/edit/${flashcard.id}" class="btn btn-primary">Edit</a>
                <button type="button" class="btn btn-danger" onclick="openDeleteModal(${flashcard.id})">Delete</button>
            </td>
        </tr>
    `).join('');
    flashcardBody.html(rows);
}
function fetchFlashcards() {
    const search = searchInput.val().trim();
    const sort = sortSelect.val();
    const order = orderSelect.val();

    const params = new URLSearchParams({ search, sort, order });
    // Update URL without reloading the page
    history.replaceState(null, '', `/flashcards?${params.toString()}`);

    fetch(`/flashcards?${params.toString()}&ajax=1`, {
        headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => renderFlashcards(data))
        .catch(error => {
            console.error('Error fetching flashcards:', error);
        }
    );
} 

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
}

searchInput.on('input', fetchFlashcards);
sortSelect.on('change', fetchFlashcards);
orderSelect.on('change', fetchFlashcards);

fetchFlashcards();