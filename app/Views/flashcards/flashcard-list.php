<?php require_once __DIR__ . '/flashcard-new.php'; ?>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title fw-bold" id="deleteModalLabel">⚠️ Confirm Delete</h5>
        </div>

        <div class="modal-body text-center">
            <p class="fs-5 mb-3">Are you sure you want to delete this flashcard?</p>
            <p class="text-muted small">This action cannot be undone.</p>
        </div>

        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDelete">Yes, Delete</button>
        </div>
        </div>
  </div>
</div>
<!-- End Delete Confirmation Modal -->
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
                        <th scope="col">Privacy Status</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($flashcards as $card): ?>
                        <tr>
                            <td class="fw-bold" data-label="Topic"><?= $card->getTopic() ?></td>
                            <td class="fw-bold" data-label="Author"><?= $card->getUsernameByUserId($card->getUserId()) ?></td>
                            <td data-label="Status"><?= $card->getStatus() === 'public' ? '🌍 Public' : '🔒 Private' ?></td>
                            <td data-label="Created At"><?= date("d F Y", strtotime($card->getCreatedAt())) ?></td>
                            <td data-label="Updated At"><?= date("d F Y", strtotime($card->getUpdatedAt())) ?></td>
                            <td>
                                <a href="/flashcards/view/<?= $card->getId() ?>" class="btn btn-info">View</a>
                                <a href="/flashcards/edit/<?= $card->getId() ?>" class="btn btn-primary">Edit</a>
                                <button type="button" class="btn btn-danger" onclick="openDeleteModal(<?=$card->getId()?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Use javascript:void(0) to execute JS without reloading or navigate to another page -->
<a href="javascript:void(0)" class="btn-add-flashcard" id="openModal">＋</a> 
