<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>🌸 NihongoJP</title>
        <!-- Page Specific CSS -->
        <!-- Global CSS -->
        <link rel="stylesheet" href="/css/style.css">

        <!-- The other CSS files -->
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="/css/<?= $style; ?>.css">
        <?php endforeach; ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://accounts.google.com/gsi/client" async defer></script>
    </head>
    <body>
        <header class="d-flex flex-column">
            <?php 
                if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
                    include __DIR__ . '/header.php';
                } else {
                    include __DIR__ . '/headerPreLoggedIn.php';
                }
            ?>
        </header>
        <main>
            <?= $content?? ''; ?>
        </main>
        <footer class="mt-auto">
            <?php include __DIR__ . '/footer.php'; ?>
        </footer>
    </body>
    <!-- Page Specific JS -->
    <?php foreach ($scripts as $script): ?>
        <script src="/js/<?= $script; ?>.js"></script>
    <?php endforeach; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</html>