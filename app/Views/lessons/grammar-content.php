<div class="container py-5">
    <?php if (empty($grammarPoint)): ?>
        <p class="text-center text-muted mt-5">Grammar point not found.</p>
    <?php else: ?>
    <div>
        <a href="/lessons/<?= $level ?>/grammar" class="btn btn-outline-secondary mb-4 shadow-sm">
            ⟵ Back to Grammar Lessons
        </a>
    </div>

    <div class="grammar-detail mx-auto p-5" style="max-width: 850px;">
        <h1 class="text-center main-title mb-4">
            <?= $grammarPoint['title'] ?>
        </h1>

        <?php if (!empty($grammarPoint['warning'])): ?>
            <div class="warning-box p-3 mb-4">
                <strong>⚠ Warning:</strong> <?= $grammarPoint['warning'] ?>
            </div>
        <?php endif; ?>

        <h2 class="mb-3" style="color:#d63384; font-weight:700;">📘 About</h2>
        <div class="grammar-about mb-4" style="font-size:1.1rem; line-height:1.8;">
            <?= nl2br($grammarPoint['about']) ?>
        </div>

        <?php if (!empty($grammarPoint['examples'])): ?>
            <h2 class="mb-3" style="color:#d63384; font-weight:700;">📘 Examples</h2>
            <ul class="list-group">
                <?php foreach ($grammarPoint['examples'] as $ex): ?>
                    <li class="list-group-item example-item mb-2 p-3">
                        <strong style="color:#d63384;"><?= $ex['sentence'] ?></strong>
                        <span class="text-muted d-block"><?= $ex['translation'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>