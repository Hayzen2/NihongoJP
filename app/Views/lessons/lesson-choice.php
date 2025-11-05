<?php
$levels = ['N5', 'N4', 'N3', 'N2', 'N1'];
$skills = [
    ['name' => 'Vocabulary', 'icon' => '📚'],
    ['name' => 'Kanji', 'icon' => '🈷️'],
    ['name' => 'Grammar', 'icon' => '✏️']
];
?>

<div class="container py-5 text-center">
    <h1 class="main-title">🌸 Start Your Japanese Learning Journey</h1>

    <div class="levels-wrapper">
        <div class="levels-row">
            <?php foreach ($levels as $level): ?>
                <div class="level-block">
                    <h4 class="level-title">JLPT <?= $level ?></h4>
                    <div class="skills-block">
                        <?php foreach ($skills as $skill): ?>
                            <a href="/lessons/<?= $level ?>/<?= strtolower($skill['name']) ?>" class="skill-card">
                                <span class="skill-icon"><?= $skill['icon'] ?></span>
                                <span class="skill-name"><?= $skill['name'] ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
