<div class="container py-5">

    <h1 class="main-title text-center mb-5">📘 JLPT <?= strtoupper($level) ?> Vocabulary</h1>

    <div class="d-flex justify-content-center mb-4">
        <a href="/lessons" class="btn btn-outline-secondary shadow-sm">
            ⟵ Back to Skills Lessons
        </a>
    </div>

    <!-- Search box -->
    <form class="controls vocab-controls d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
        <input 
            type="text" 
            name="search" 
            class="form-control search-box"
            placeholder="Search word, reading, or meaning..."
            value="<?= htmlspecialchars(!empty($searches) ? $searches[0] : '') ?>"
            style="max-width: 320px;"
        >
        <button id="openCanvas" class="btn btn-write mt-2">✏️ Write</button>
        <!-- Modal for canvas -->
        <div class="draw-board-container">
            <button type="button" id="closeCanvas" class="btn canvas-modal-close">✖ Close</button>
            <canvas id="drawBoard" width="300" height="300" class="draw-board"></canvas>

            <div class="d-flex justify-content-center gap-3 mt-3">
                <button id="clearDraw" class="btn btn-outline-secondary">Clear</button>
            </div>

            <div id="outputContainer" class="recognized-container mt-3">
                <p><strong>Recognized Characters:</strong></p>
                <div id="output" class="recognized-output"></div>
            </div>
        </div>
    </form>

    <!-- Vocabulary cards -->
    <div class="row vocab-cards" id="vocabCards">
        <?php if (empty($vocabs)): ?>
            <p class="text-center text-muted mt-5">No vocabulary found.</p>
        <?php else: ?>
            <?php foreach ($vocabs as $vocab): ?>
                <div class="col-md-3 col-6 mb-4">
                    <div class="vocab-card p-3 text-center rounded shadow-sm">
                        <h3 class="vocab-word"><?= htmlspecialchars($vocab['word']) ?></h3>
                        <p class="vocab-reading mb-1"><?= htmlspecialchars($vocab['reading']) ?></p>
                        <p class="vocab-meaning"><?= htmlspecialchars($vocab['meaning']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination d-flex justify-content-center mt-4 gap-2"></div>
    <?php endif; ?>

</div>
