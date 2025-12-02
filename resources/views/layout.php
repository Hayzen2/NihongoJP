<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>🌸 NihongoJP</title>
        <!-- Page Specific CSS -->
        <!-- Global CSS -->
        <link rel="stylesheet" href="/css/style.css">
        <link rel="stylesheet" href="/css/chatbot.css">
        <link rel="stylesheet" href="/css/loading.css">
        <!-- The other CSS files -->
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="/css/<?= $style; ?>.css">
        <?php endforeach; ?>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script src="https://accounts.google.com/gsi/client" async defer></script>
        <script src="https://cdn.jsdelivr.net/npm/@interactjs/interactjs/dist/interact.min.js"></script>
    </head>
    <body>
        <?php include __DIR__ . '/loadingPage.php'; ?>
        <header class="d-flex flex-column">
            <?php 
                if (isset($_SESSION['user']) && $_SESSION['user']) {
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
        <?php
        // Floating chatbot
        $currentPage = basename($_SERVER['REQUEST_URI']);
        $excludeChatbotPages = [
            'subscription', 
            'login', 
            'register',
            'register/local',
            'login/google',
            'login/forgot-password',
            'login/local',
            'contact',
            'api/countries',
            'api/provinces',
            'api/cities',
            'login/forgot-password/send-otp',
            'login/forgot-password/input-otp',
            'login/forgot-password/verify-otp',
            'login/forgot-password/reset-password-form',
            'login/forgot-password/reset-expired',
            'check-username',
            'check-email'
        ];

        if (!in_array($currentPage, $excludeChatbotPages)) {
            include __DIR__ . '/chatbot.php';
        }
        ?>
        <!-- Page Specific JS -->
        <?php foreach ($scripts as $script): ?>
            <script src="/js/<?= $script; ?>.js"></script>
        <?php endforeach; ?>
        <script src="/js/chatbot.js"></script>
        <script src="/js/loading.js"></script>
    </body>
</html>