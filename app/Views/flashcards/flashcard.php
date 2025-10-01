<div container mt-5 text-center">
    <h1 class="mb-4">Flashcards</h1>
    <p class="mb-4">Enhance your Japanese vocabulary with our interactive flashcards!</p>
    <div class="flashcard-container">
        <div class="flashcard" id="flashcard">
            <div class="front">
                <h2 id="question">Question</h2>
                <div>
                    <img src="/assets/flashcard_image.png" alt="Flashcard Image" class="img-fluid">
                </div>
            </div>
            <div class="back">
                <h2 id="answer">Answer</h2>
                <div>
                    <img src="/assets/flashcard_image.png" alt="Flashcard Image" class="img-fluid">
                </div>
            </div>
        </div>
        <button id="show-answer" class="btn btn-primary mt-3">Show Answer</button>
        <button id="next-card" class="btn btn-secondary mt-3">Next Card</button>
</div>
</div>

<?php foreach($flashcards as $card): ?>
                <p><?= $card->getQuestion() ?> - <?= $card->getAnswer() ?> </p>
            <?php endforeach ?>