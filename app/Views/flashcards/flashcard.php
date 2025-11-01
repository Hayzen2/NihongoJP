<div class="flashcard-container">
    <div class="flashcard-header text-center mb-4 mt-4">
        <h2>Flashcard Topic: <?= htmlspecialchars($flashcard->getTopic()) ?></h2>
        <h5>Author: <?= htmlspecialchars($flashcard->getUsernameByUserId($flashcard->getUserId())) ?></h5>
    </div>

    <?php if(!empty($flashcardQASet)): ?>
        <div class="flashcard-deck text-center mb-3 d-flex justify-content-center align-items-center">
            <?php foreach($flashcardQASet as $index => $qa): ?>
                <div class="flashcard-wrapper <?= $index === 0 ? 'active' : '' ?>">
                    <div class="flashcard">
                        <!--Front Side-->
                        <div class="flashcard-front">
                            <h3>Question #<?= $index + 1 ?></h3>
                            <p><?= htmlspecialchars($qa->getQuestion()) ?></p>
                            <p class="flip-hint">(Click to flip)</p>
                        </div>
                        <!--Back Side-->
                        <div class="flashcard-back">
                            <h4>Answer</h4>
                            <p><?= htmlspecialchars($qa->getAnswer()) ?></p>
                            <p class="flip-hint">(Click to flip back)</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="controls mt-3 text-center">
            <button class="btn btn-prev">⟵ Prev</button>
            <button class="btn btn-next">Next ⟶</button>
            <button class="btn btn-reshuffle">
                <i class="bi bi-shuffle"></i> Reshuffle
            </button>
        </div>
    <?php else: ?>
        <p>No questions available for this flashcard.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="/flashcards" class="btn btn-back">⟵ Back to Flashcards</a>
    </div>
</div>