$('.controls').on('submit', e => {
    e.preventDefault(); // stop Enter from reloading page
});
function setupFlashcardFilter(type) {
    const searchInput = $(`.${type}-controls input[name="${type}_search"]`);
    const sortSelect = $(`.${type}-controls select[name="${type}_sort"]`);
    const orderSelect = $(`.${type}-controls select[name="${type}_order"]`);
    const tableBody = $(`#${type}-table tbody`);

    function renderRows(data) {
        const rows = data.map(card => `
            <tr>
                <td>${card.topic}</td>
                ${type === 'public' ? `<td>${card.author || ''}</td>` : ''}
                <td>${formatDate(card.created_at)}</td>
                <td>${formatDate(card.updated_at)}</td>
                <td>
                    <a href="/flashcards/view/${card.id}" class="btn btn-info btn-sm">View</a>
                    <a href="/flashcards/edit/${card.id}" class="btn btn-primary btn-sm">Edit</a>
                    <button onclick="openDeleteModal(${card.id})" class="btn btn-danger btn-sm">Delete</button>
                </td>
            </tr>
        `).join('');
        tableBody.html(rows);
    }

    function fetchFlashcards() {
        const params = new URLSearchParams({
            type: type,
            [`${type}_search`]: searchInput.val(),
            [`${type}_sort`]: sortSelect.val(),
            [`${type}_order`]: orderSelect.val()
        });

        // Update URL without reloading the page
        history.replaceState(null, '', `/flashcards?${params.toString()}`);

        fetch(`/flashcards?${params.toString()}&ajax=1`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (type === 'private') renderRows(data.privateFlashcards || []);
            if (type === 'public') renderRows(data.publicFlashcards || []);
        })
        .catch(err => console.error(err));
    }

    searchInput.on('input', fetchFlashcards);
    sortSelect.on('change', fetchFlashcards);
    orderSelect.on('change', fetchFlashcards);

    fetchFlashcards(); // fetch immidiately
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
}

setupFlashcardFilter('private');
setupFlashcardFilter('public');
