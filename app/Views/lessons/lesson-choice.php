<?php
$levels = ['N5', 'N4', 'N3', 'N2', 'N1'];
?>

<div class="mb-3">
    <span class="fw-bold me-2">JLPT Level:</span>
    <?php foreach ($levels as $level): ?>
        <a href="/lessons/<?= $level ?>" aria-label="Select JLPT level <?= $level ?>"
           class="btn btn-outline-primary <?= ($jlptFilter === $level) ? 'active' : '' ?>">
            <?= $level ?>
        </a>
    <?php endforeach; ?>
</div>