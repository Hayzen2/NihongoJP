<h1 class="mb-4">📚 Japanese Books</h1>

<div class="mb-3 d-flex gap-2 align-items-center">
    <label for="jlptFilter" class="fw-bold mb-0">Filter by JLPT:</label>
    <select id="jlptFilter" class="form-select w-auto" onchange="location = '?jlpt=' + this.value;">
        <option value="" <?= $jlptFilter === '' ? 'selected' : '' ?>>All</option>
        <option value="N5" <?= $jlptFilter === 'N5' ? 'selected' : '' ?>>N5</option>
        <option value="N4" <?= $jlptFilter === 'N4' ? 'selected' : '' ?>>N4</option>
        <option value="N3" <?= $jlptFilter === 'N3' ? 'selected' : '' ?>>N3</option>
        <option value="N2" <?= $jlptFilter === 'N2' ? 'selected' : '' ?>>N2</option>
        <option value="N1" <?= $jlptFilter === 'N1' ? 'selected' : '' ?>>N1</option>
    </select>
</div>

<div class="row row-cols-1 row-cols-md-2 g-4">
    <?php foreach ($books as $book): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                    <p class="card-text"><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                    <p class="card-text"><strong>Publisher:</strong> <?= htmlspecialchars($book['publisher']) ?></p>
                    <p class="card-text"><strong>JLPT Levels:</strong> <?= $book['jlpt_levels'] ?: 'All' ?></p>
                    <p class="card-text"><?= nl2br(htmlspecialchars($book['description'])) ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="<?= htmlspecialchars($book['source_link']) ?>" target="_blank" class="btn btn-primary">Buy Now</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
