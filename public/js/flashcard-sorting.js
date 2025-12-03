$('.controls').on('submit', e => {
    e.preventDefault(); // stop Enter from reloading page
});
function setupFlashcardFilter(type) {
    const searchInput = $(`.${type}-controls input[name="${type}_search"]`);
    const sortSelect = $(`.${type}-controls select[name="${type}_sort"]`);
    const orderSelect = $(`.${type}-controls select[name="${type}_order"]`);
    const tableBody = $(`#${type}-table tbody`);
    const pagination = $(`#${type}-pagination`);

    function renderRows(data) {
        const rows = data.map(card => `
            <tr>
                <td>${card.topic}</td>
                ${type === 'public' ? `<td>${card.author || ''}</td>` : ''}
                ${type === 'private' ? `<td>${card.status}</td>` : ''}
                <td>${formatDate(card.created_at)}</td>
                <td>${formatDate(card.updated_at)}</td>
                <td>
                    <a href="/flashcards/view/${card.id}" class="btn btn-info btn-sm">View</a>
                    ${type === 'private' ? `<button type="button" class="btn btn-primary btn-sm" onclick="openEditModal(${card.id})">Edit</button> `: ''}
                    ${type === 'private' ? `<button onclick="openDeleteModal(${card.id})" class="btn btn-danger btn-sm">Delete</button>` : ''}
                </td>
            </tr>
        `).join('');
        tableBody.html(rows);
    }

    function fetchFlashcards(page = 1) {
        const params = new URLSearchParams({
            type,
            [`${type}_search`]: searchInput.val(),
            [`${type}_sort`]: sortSelect.val(),
            [`${type}_order`]: orderSelect.val(),
            [`${type}_page`]: page
        });

        // Update URL without reloading the page
        history.replaceState(null, '', `/flashcards?${params.toString()}`);

        fetch(`/flashcards?${params.toString()}&ajax=1`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (type === 'private') {
                renderRows(data.privateFlashcards || []);
                renderPagination(data.currentPage, data.totalPages);
            }
            if (type === 'public') {
                renderRows(data.publicFlashcards || []);
                renderPagination(data.currentPage, data.totalPages);
            }
        })
        .catch(err => console.error(err));
    }

    function renderPagination(current, totalPages) {
        if (totalPages <= 1) {
            pagination.html(""); 
            return;
        }
        let html = "";
        let pagesToShow = [];

        // Always show page 1
        pagesToShow.push(1);

        // Show pages around current
        for (let i = Math.max(2, current - 1); i <= Math.min(totalPages - 1, current + 1); i++) {
            pagesToShow.push(i);
        }

        // Always show last page
        if (totalPages > 1) pagesToShow.push(totalPages);

        pagesToShow = [...new Set(pagesToShow)].sort((a, b) => a - b);

        let last = 0;

        // Build pagination (Prev)
        if (current > 1) {
            html += `<button class="page-btn btn btn-outline-secondary" data-page="${current - 1}">⟵ Prev</button>`;
        }

        // Page numbers with ellipsis
        pagesToShow.forEach(p => {
            if (p - last > 1) {
                html += `<span class="btn btn-outline-secondary disabled">...</span>`;
            }

            html += `
                <button class="page-btn btn ${p === current ? 'btn-pink' : 'btn-outline-secondary'}" data-page="${p}">
                    ${p}
                </button>
            `;
            last = p;
        });
        // Next
        if (current < totalPages) {
            html += `<button class="page-btn btn btn-outline-secondary" data-page="${current + 1}">Next ⟶</button>`;
        }
        pagination.html(html);

        // Attach click handlers
        $(".page-btn").on("click", function () {
            fetchFlashcards($(this).data("page"));
        });
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
