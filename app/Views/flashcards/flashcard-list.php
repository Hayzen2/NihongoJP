<div class="container mt-5 text-center">
    <h1 class="mb-4">Flashcards List </h1>
    <p class="mb-4">Your collection of Japanese flashcards!</p>
    
    <div class="flashcard-list-container">
         <!-- Controls -->
        <form method="get" class="controls d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
            <label for="search" class="fw-bold">Search:</label>
            <input type="text" name="search" placeholder="Find by topic or author..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            
            <label for="sort" class="fw-bold">Sort By:</label>
            <select name="sort">
                <option value="topic" <?= ($_GET['sort'] ?? '') === 'topic' ? 'selected' : '' ?>>Topic</option>
                <option value="created" <?= ($_GET['sort'] ?? '') === 'created' ? 'selected' : '' ?>>Created Date</option>
                <option value="updated" <?= ($_GET['sort'] ?? '') === 'updated' ? 'selected' : '' ?>>Updated Date</option>
            </select>

            <label for="order" class="fw-bold">Sort Order:</label>
            <select name="order">
                <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Ascending</option>
                <option value="desc" <?= ($_GET['order'] ?? '') === 'desc' ? 'selected' : '' ?>>Descending</option>
            </select>
        </form>

        <!-- Flashcards List -->
        <div class="flashcard-list mb-3 d-flex justify-content-center align-items-center flex-column">
            <table class="table shadow-sm rounded">
                <thead>
                    <tr>
                        <th scope="col">Topic</th>
                        <th scope="col">Author </th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($flashcards as $card): ?>
                        <tr>
                            <td data-label="Topic"><?= $card->getTopic() ?></td>
                            <td data-label="Author"><?= $card->getAuthor() ?></td>
                            <td data-label="Created At"><?= date("d F Y", strtotime($card->getCreatedAt())) ?></td>
                            <td data-label="Updated At"><?= date("d F Y", strtotime($card->getUpdatedAt())) ?></td>
                            <td>
                                <a href="/flashcards/show/<?= $card->getId() ?>" class="btn btn-info">View</a>
                                <a href="/flashcards/edit/<?= $card->getId() ?>" class="btn btn-primary">Edit</a>
                                <a href="/flashcards/delete/<?= $card->getId() ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<a href="/flashcards/new" class="btn-add-flashcard">＋</a>