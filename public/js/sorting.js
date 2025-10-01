document.querySelector('.controls').addEventListener('submit', e => {
    e.preventDefault(); // stop Enter from reloading page
});
const searchInput = document.querySelector('input[name="search"]');
const sortSelect = document.querySelector('select[name="sort"]');
const orderSelect = document.querySelector('select[name="order"]');
const flashcardBody = document.querySelector('.flashcard-list tbody');

function renderFlashcards(flashcards) {
   const rows = flashcards.map(flashcard => `
        <tr>
            <td data-label="Topic">${flashcard.topic}</td>
            <td data-label="Author">${flashcard.author}</td>
            <td data-label="Created At">${formatDate(flashcard.created_at)}</td>
            <td data-label="Updated At">${formatDate(flashcard.updated_at)}</td>
            <td>
                <a href="/flashcards/${flashcard.id}" class="btn btn-info">View</a>
                <a href="/flashcards/edit/${flashcard.id}" class="btn btn-primary">Edit</a>
                <a href="/flashcards/delete/${flashcard.id}" class="btn btn-danger">Delete</a>
            </td>
        </tr>
    `).join('');
    flashcardBody.innerHTML = rows;
}
function fetchFlashcards() {
    const search = searchInput.value.trim();
    const sort = sortSelect.value;
    const order = orderSelect.value;
    
    const params = new URLSearchParams({ search, sort, order });
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

searchInput.addEventListener('input', fetchFlashcards);
sortSelect.addEventListener('change', fetchFlashcards);
orderSelect.addEventListener('change', fetchFlashcards);

fetchFlashcards();