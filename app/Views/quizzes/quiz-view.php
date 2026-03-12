<div class="container py-5">
    <h1 class="main-title text-center mb-4"></h1>
    <div class="row">
        <?php foreach ($quizzes as $q): ?>
            <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h4><?= htmlspecialchars($q['title']) ?></h4>
                    <p><?= htmlspecialchars($q['description']) ?></p>
                    <p><small>JLPT: <?= htmlspecialchars($q['jlpt_level']) ?> • Time: <?= (int)$q['time_limit'] ?>s</small></p>
                    <?php if (!empty($lastAttempts[$q['id']])): ?>
                        <p><small>Last attempt: <?= $lastAttempts[$q['id']]['score'] ?>%</small></p>
                    <?php endif; ?>
                    <a href="/quizzes/view/<?= $q['id'] ?>" class="btn btn-primary">View Quiz</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
