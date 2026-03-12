<!-- Floating sticky timer -->
<div id="floatingTimer">
    ⏳ Time Left: <span id="floatTime"><?= $locked ? 0 : $timeLimit ?></span>s
</div>

<div class="container py-5 main-container">
    <h1 class="main-title text-center mb-3"><?= htmlspecialchars($quiz['title']) ?></h1>
    <p class="text-center text-muted"><?= htmlspecialchars($quiz['description']) ?></p>

    <form id="quizForm">
        <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">

        <div id="questions">
            <?php foreach ($questions as $i => $q): ?>
                <?php
                    $qaId = $q['id'];
                    $correct = $correctAnswers[$qaId] ?? null;
                    $userAnswer = $prevAnswers[$qaId] ?? null;

                    // If user left blank
                    $noAnswer = ($locked && $userAnswer === null);
                ?>

                <div class="question-card mb-3" data-qa-id="<?= $qaId ?>">
                    <p class="q-index">
                        <strong>Q<?= $i+1 ?>.</strong> <?= htmlspecialchars($q['question']) ?>
                    </p>

                    <div class="choices">
                        <?php foreach ($q['choices'] as $choice): ?>
                            <?php
                                $isChecked = ($userAnswer === $choice);
                                $isCorrectChoice = ($choice === $correct);
                                $isUserWrong = ($locked && $isChecked && !$isCorrectChoice);

                                $class = "";
                                $mark = "";

                                if ($locked) {
                                    if ($isCorrectChoice) {
                                        $class = "correct";
                                        $mark = "<span class='tick'>✓</span>";
                                    } elseif ($isUserWrong) {
                                        $class = "incorrect";
                                        $mark = "<span class='cross'>✗</span>";
                                    } elseif ($noAnswer && $isCorrectChoice) {
                                        $class = "unanswered";
                                        $mark = "<span class='warn'>⚠</span>";
                                    }
                                }
                            ?>

                            <label class="choice <?= $class ?>">
                                <input 
                                    type="radio"
                                    name="qa_<?= $qaId ?>"
                                    value="<?= htmlspecialchars($choice) ?>"
                                    <?= $isChecked ? 'checked' : '' ?>
                                    <?= $locked ? 'disabled' : '' ?>
                                >
                                <span><?= htmlspecialchars($choice) ?> <?= $mark ?></span>
                            </label>
                        <?php endforeach; ?>

                        <?php if ($noAnswer): ?>
                            <p class="no-answer-note">⚠ You left this question unanswered.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <a href="/quizzes" class="btn btn-secondary">⬅ Back</a>

            <div>
                <?php if (!$locked): ?>
                    <button type="button" id="submitBtn" class="btn btn-pink-take">Submit</button>
                <?php else: ?>
                    <a href="/quizzes/retake/<?= $quiz['id'] ?>" class="btn btn-warning">Retake Quiz</a>
                <?php endif; ?>
            </div>

            <div id="resultBox" class="ms-3"></div>
        </div>

    </form>
</div>

<script>
window.__QUIZ_CONFIG__ = {
    quizId: <?= (int)$quiz['id'] ?>,
    timeLimit: <?= $timeLimit ?>,
    locked: <?= $locked ? 'true' : 'false' ?>,
    prevAnswers: <?= json_encode($prevAnswers) ?>
};
</script>
<script src="/public/js/quiz.js"></script>