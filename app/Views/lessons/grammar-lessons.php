<div class="container py-5">
    <h1 class="main-title text-center mb-5">📘 Grammar Lessons - <?= $level ?></h1>
    <div>
        <a href="/lessons" class="btn btn-outline-secondary mb-4 shadow-sm">
            ⟵ Back to Skills Lessons
        </a>
    </div>
    <?php if (empty($lessons)): ?>
        <p class="text-center text-muted mt-5">No grammar lessons available for this level yet.</p>
    <?php else: ?>
        <?php foreach ($lessons as $lesson): ?>
            <?php if (!empty($lesson['grammar_points'])): ?>
                <div class="lesson-group my-5">
                    <h2 class="lesson-title py-2 px-3 rounded-pill"><?= $lesson['lesson'] ?></h2>
                    <div class="row">
                        <?php foreach ($lesson['grammar_points'] as $point): ?>
                            <div class="col-md-3 col-6 mb-4">
                                <a href="/lessons/<?= $level ?>/grammar/<?= urlencode($point['title']) ?>" 
                                   class="grammar-card d-block p-4 text-center rounded shadow-sm text-decoration-none">
                                    <h3 class="grammar-symbol"><?= $point['symbol'] ?></h3>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
