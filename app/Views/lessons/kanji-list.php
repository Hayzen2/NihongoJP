<div class="container py-5">

    <h1 class="main-title text-center mb-5">🈂️ JLPT <?= strtoupper($level) ?> Kanji</h1>

    <div class="d-flex justify-content-center mb-4">
        <a href="/lessons" class="btn btn-outline-secondary shadow-sm">⟵ Back to Skills Lessons</a>
    </div>

    <!-- Search box -->
    <form class="controls kanji-controls d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
        <input 
            type="text" 
            name="search" 
            class="form-control search-box"
            placeholder="Search kanji..."
            value="<?= htmlspecialchars(!empty($searches) ? $searches[0] : '') ?>"
            style="max-width: 320px;"
        >
        <button id="openCanvas" class="btn btn-write mt-2" type="button">✏️ Write</button>
        <div class="draw-board-container" style="display:none;">
            <button type="button" id="closeCanvas" class="btn canvas-modal-close">✖ Close</button>
            <canvas id="drawBoard" width="300" height="300" class="draw-board"></canvas>

            <div class="d-flex justify-content-center gap-3 mt-3">
                <button id="clearDraw" class="btn btn-outline-secondary" type="button">Clear</button>
            </div>

            <div id="outputContainer" class="recognized-container mt-3">
                <p><strong>Recognized Characters:</strong></p>
                <div id="output" class="recognized-output"></div>
            </div>
        </div>
    </form>

    <!-- Kanji cards -->
    <div class="row kanji-cards" id="kanjiCards">
        <?php if (empty($lessons)): ?>
            <p class="text-center text-muted mt-5">No kanji found.</p>
        <?php else: ?>
            <?php foreach ($lessons as $k): ?>
                <div class="col-md-2 col-6 mb-4">
                    <a href="/lessons/<?= $level ?>/kanji/<?= rawurlencode($k['kanji']) ?>" class="text-decoration-none">
                        <div class="kanji-card p-3 text-center rounded shadow-sm">
                            <h3 class="kanji-char"><?= htmlspecialchars($k['kanji']) ?></h3>
                            <p class="kanji-meaning"><?= htmlspecialchars(implode(', ', $k['meanings'] ?? [])) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination d-flex justify-content-center mt-4 gap-2"></div>
    <?php endif; ?>

</div>
