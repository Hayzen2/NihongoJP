<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">🌸 NihongoJP</a>
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/lessons">Lessons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/quizzes">Quizzes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/flashcards">Flashcard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/subscription">Subscription</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/buy-books">Buy Books</a>
                </li>
            </ul>
        </div>
        <div class="navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/rank">Rank</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link">
                        <img src="<?= $_SESSION['user']['avatar'] ?? 'images/default-ava.png' ?>" alt="avatar" class="rounded-circle" width="30" height="30">
                        <span class="ms-2"><?= $_SESSION['user']['username'] ?? $_SESSION['user']['name']  ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Logout</a>
                </li>
            </ul>
    </div>
</nav>