<div class="container">
    <?php if (!$kanjiData || isset($kanjiData['error'])): ?>
        <p class="text-center text-danger">Kanji not found.</p>
    <?php else: ?>
        <?php $k = $kanjiData['kanji']; ?>
        <h1 class="kanji-char text-center"><?= htmlspecialchars($k['character']) ?></h1>
        <div class="kanji-info">
            <p><strong>Meaning:</strong> <?= htmlspecialchars($k['meaning']['english']) ?></p>
            <p><strong>Onyomi:</strong> <?= htmlspecialchars($k['onyomi']['katakana']) ?> (<?= htmlspecialchars($k['onyomi']['romaji']) ?>)</p>
            <p><strong>Kunyomi:</strong> <?= htmlspecialchars($k['kunyomi']['hiragana']) ?> (<?= htmlspecialchars($k['kunyomi']['romaji']) ?>)</p>
            <p><strong>Strokes:</strong> <?= htmlspecialchars($k['strokes']['count']) ?></p>
        </div>

        <div class="video-container">
            <div class="video-container">
            <video controls
                poster="<?= htmlspecialchars($k['video']['poster']) ?>">
                <source src="<?= htmlspecialchars($k['video']['mp4']) ?>" type="video/mp4">
                <source src="<?= htmlspecialchars($k['video']['webm']) ?>" type="video/webm">
                Your browser does not support the video tag.
            </video>
        </div>
        </div>

        <?php if (!empty($kanjiData['examples'])): ?>
            <h3>Examples</h3>
            <ul class="examples-list">
                <?php foreach ($kanjiData['examples'] as $ex): ?>
                    <li>
                        <strong><?= htmlspecialchars($ex['japanese']) ?></strong> - <?= htmlspecialchars($ex['meaning']['english']) ?>
                        <?php if (!empty($ex['audio']['mp3'])): ?>
                            <audio controls>
                                <source src="<?= htmlspecialchars($ex['audio']['mp3']) ?>" type="audio/mpeg">
                            </audio>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</div>