$('.kanji-controls').on('submit', e => e.preventDefault());

function setupKanjiFilter() {
    const searchInput = $(`input[name="search"]`);
    const kanjiCards  = $("#kanjiCards");
    const pagination  = $(".pagination");

    function renderRows(data) {
        if (!data.length) {
            kanjiCards.html(`<p class="text-center text-muted mt-5">No kanji found.</p>`);
            return;
        }

        let html = data.map(k => `
            <div class="col-md-2 col-6 mb-4">
                <a href="/lessons/${level}/kanji/${encodeURIComponent(k.kanji)}" class="text-decoration-none">
                    <div class="kanji-card p-3 text-center rounded shadow-sm">
                        <h3 class="kanji-char">${k.kanji}</h3>
                        <p class="kanji-meaning">${k.meanings.join(', ')}</p>
                    </div>
                </a>
            </div>
        `).join('');

        kanjiCards.html(html);
    }

    function renderPagination(current, totalPages) {
        let html = '';
        let pagesToShow = [];

        pagesToShow.push(1);

        for (let i = Math.max(2, current - 1); i <= Math.min(totalPages - 1, current + 1); i++)
            pagesToShow.push(i);

        if (!pagesToShow.includes(totalPages) && totalPages > 1)
            pagesToShow.push(totalPages);

        pagesToShow.sort((a, b) => a - b);

        let lastPage = 0;
        pagesToShow.forEach(i => {
            if (i - lastPage > 1) html += `<span class="btn btn-outline-secondary disabled">...</span>`;
            html += `<button class="page-btn btn ${i === current ? 'btn-pink' : 'btn-outline-secondary'}" data-page="${i}">${i}</button>`;
            lastPage = i;
        });

        if (current > 1)
            html = `<button class="page-btn btn btn-outline-secondary" data-page="${current - 1}">⟵ Prev</button>` + html;

        if (current < totalPages)
            html += `<button class="page-btn btn btn-outline-secondary" data-page="${current + 1}">Next ⟶</button>`;

        pagination.html(html);

        $(".page-btn").on("click", function () {
            fetchKanji($(this).data("page"));
        });
    }

    function fetchKanji(page = 1) {
        const variants = expandSimilarChars(searchInput.val());
        const params = new URLSearchParams();

        variants.forEach(v => params.append('search[]', v));
        params.append('page', page);

        history.replaceState(null, '', `?${params.toString()}`);

        fetch(`?${params.toString()}&ajax=1`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(json => {
            renderRows(json.lessons);
            renderPagination(json.currentPage, json.totalPages);
        })
        .catch(err => console.error(err));
    }

    searchInput.on('input', () => fetchKanji());
    fetchKanji();
}

function expandSimilarChars(text) { 
    if (!text) return [''];

    const similarGroup = /[~～〜]/g;

    if (!similarGroup.test(text)) {
        return [text];
    }

    const variations = [
        text.replace(similarGroup, "~"),
        text.replace(similarGroup, "～"),
        text.replace(similarGroup, "〜")
    ];

    return [...new Set(variations)];
}

setupKanjiFilter();
