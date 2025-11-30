// Prevent default form submission
$('.vocab-controls').on('submit', e => e.preventDefault());

function setupVocabFilter() {
    // DOM elements
    const searchInput = $(`input[name="search"]`);
    const vocabCards  = $("#vocabCards"); // container for card layout
    const pagination  = $(".pagination");

    // Render vocabulary rows as cards
    function renderRows(data) {
        // No results
        if (!data.length) {
            vocabCards.html(`<p class="text-center text-muted mt-5">No vocabulary found.</p>`);
            return;
        }

        // Map data into cards
        let html = data.map(v => `
            <div class="col-md-3 col-6 mb-4">
                <div class="vocab-card p-3 text-center rounded shadow-sm">
                    <h3 class="vocab-word">${v.word}</h3>
                    <p class="vocab-reading mb-1">${v.reading}</p>
                    <p class="vocab-meaning">${v.meaning}</p>
                </div>
            </div>
        `).join('');

        vocabCards.html(html);
    }

    // Render pagination with sliding window and ellipsis
    function renderPagination(current, totalPages) {
        let html = '';
        let pagesToShow = [];

        // Always show first page
        pagesToShow.push(1);

        // Show pages around current
        for (let i = Math.max(2, current-1); i <= Math.min(totalPages-1, current+1); i++)
            pagesToShow.push(i);

        // Always show last page
        if (!pagesToShow.includes(totalPages) && totalPages > 1)
            pagesToShow.push(totalPages);

        pagesToShow.sort((a,b)=>a-b);
        let lastPage = 0;

        // Build pagination buttons with ellipsis
        pagesToShow.forEach(i => {
            if (i - lastPage > 1) html += `<span class="btn btn-outline-secondary disabled">...</span>`;
            html += `<button class="page-btn btn ${i===current?'btn-pink':'btn-outline-secondary'}" data-page="${i}">${i}</button>`;
            lastPage = i;
        });

        // Prev / Next buttons
        if (current > 1) html = `<button class="page-btn btn btn-outline-secondary" data-page="${current-1}">⟵ Prev</button>` + html;
        if (current < totalPages) html += `<button class="page-btn btn btn-outline-secondary" data-page="${current+1}">Next ⟶</button>`;

        pagination.html(html);

        // Add click handlers to pagination buttons
        $(".page-btn").on("click", function () {
            fetchVocab($(this).data("page"));
        });
    }

    // Fetch vocabulary data via AJAX
    function fetchVocab(page = 1) {
        const variants = expandSimilarChars(searchInput.val());
        const params = new URLSearchParams();

        variants.forEach(v => params.append('search[]', v)); // append each variant as search[]
        params.append('page', page);// append page

        // Update browser URL without reloading
        history.replaceState(null, '', `?${params.toString()}`);

        // Fetch data from server
        fetch(`?${params.toString()}&ajax=1`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(json => {
            renderRows(json.lessons);                // Render vocab cards
            renderPagination(json.currentPage, json.totalPages); // Render pagination
        })
        .catch(err => console.error(err));
    }

    // Trigger AJAX fetch on search input change
    searchInput.on('input', () => fetchVocab());

    // Initial load
    fetchVocab();
}

function expandSimilarChars(text) { // expand similar chars
    if (!text) {
        return [''];
    }
    const similarGroup = /[~～〜]/g;

    // if no chars alike, return the original 1-value array
    if (!similarGroup.test(text)) {
        return [text];
    }
    const variations = [ // possible variations array
        text.replace(similarGroup, "~"),
        text.replace(similarGroup, "～"),
        text.replace(similarGroup, "〜")
    ];

    // remove duplicates then turn into array
    return [...new Set(variations)];
}

// Initialize vocabulary filter
setupVocabFilter();