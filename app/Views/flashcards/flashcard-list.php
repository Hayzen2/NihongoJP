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
    <!-- Private Flashcards -->
    <h2 class="mb-4 private-title">🔒 Private Flashcards</h2>
    <form method="GET" class="controls private-controls d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
        <label for="private_search" class="fw-bold">Search:</label>
        <input type="text" name="private_search" id="private_search" placeholder="Search by topic..." value="<?= htmlspecialchars($privateSearch) ?>">
        
        <label for="private_sort" class="fw-bold">Sort By:</label>
        <select name="private_sort">
            <option value="created_at" <?= $privateSort==='created_at'?'selected':'' ?>>Created Date</option>
            <option value="updated_at" <?= $privateSort==='updated_at'?'selected':'' ?>>Updated Date</option>
        </select>
        
        <label for="private_order" class="fw-bold">Order By:</label>
        <select name="private_order">
            <option value="asc" <?= $privateOrder==='asc'?'selected':'' ?>>Ascending</option>
            <option value="desc" <?= $privateOrder==='desc'?'selected':'' ?>>Descending</option>
        </select>
    </form>

    <div class="flashcard-table mb-3 d-flex justify-content-center align-items-center flex-column">
        <table class="table table-bordered mb-5" id="private-table">
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($privateFlashcards as $card): ?>
                <tr>
                    <td><?= is_array($card) ? $card['topic'] : $card->getTopic(); ?></td>
                    <td><?= is_array($card) ? date('d F Y', strtotime($card['created_at'])) : date('d F Y', strtotime($card->getCreatedAt())); ?></td>
                    <td><?= is_array($card) ? date('d F Y', strtotime($card['updated_at'])) : date('d F Y', strtotime($card->getUpdatedAt())); ?></td>
                    <td>
                        <a href="/flashcards/view/<?= is_array($card) ? $card['id'] : $card->getId(); ?>" class="btn btn-info btn-sm">View</a>
                        <a href="/flashcards/edit/<?= is_array($card) ? $card['id'] : $card->getId(); ?>" class="btn btn-primary btn-sm">Edit</a>
                        <button onclick="openDeleteModal(<?= is_array($card) ? $card['id'] : $card->getId(); ?>)" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Public Flashcards -->
    <h2 class="mb-4 public-title">🌍 Public Flashcards</h2>
    <form method="GET" class="controls public-controls d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
        <label for="public_search" class="fw-bold">Search:</label>
        <input type="text" name="public_search" placeholder="Search by topic or author..." value="<?= htmlspecialchars($publicSearch) ?>">
        <label for="public_sort" class="fw-bold">Sort By:</label>
        <select name="public_sort">
            <option value="topic" <?= $publicSort==='topic'?'selected':'' ?>>Topic</option>
            <option value="created_at" <?= $publicSort==='created_at'?'selected':'' ?>>Created Date</option>
            <option value="updated_at" <?= $publicSort==='updated_at'?'selected':'' ?>>Updated Date</option>
        </select>
        <label for="public_order" class="fw-bold">Order By:</label>
        <select name="public_order">
            <option value="asc" <?= $publicOrder==='asc'?'selected':'' ?>>Ascending</option>
            <option value="desc" <?= $publicOrder==='desc'?'selected':'' ?>>Descending</option>
        </select>
    </form>

    <div class="flashcard-table mb-3 d-flex justify-content-center align-items-center flex-column">
        <table class="table table-bordered" id="public-table">
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($publicFlashcards as $card): ?>
                <tr>
                    <td><?= is_array($card) ? $card['topic'] : $card->getTopic() ?></td>
                    <td>
                        <?php if (is_array($card)): ?>
                            <?= $card['author'] ?>
                        <?php else: ?>
                            <?= $card->getUsernameByUserId($card->getUserId()) ?? $card->getNameByUserId($card->getUserId()) ?>
                        <?php endif; ?>
                    </td>

                    <td><?= is_array($card) ? date('d F Y', strtotime($card['created_at'])) : date('d F Y', strtotime($card->getCreatedAt())) ?></td>
                    <td><?= is_array($card) ? date('d F Y', strtotime($card['updated_at'])) : date('d F Y', strtotime($card->getUpdatedAt())) ?></td>

                    <td>
                        <?php $id = is_array($card) ? $card['id'] : $card->getId(); ?>
                        <a href="/flashcards/view/<?= $id ?>" class="btn btn-info btn-sm">View</a>
                        <a href="/flashcards/edit/<?= $id ?>" class="btn btn-primary btn-sm">Edit</a>
                        <button onclick="openDeleteModal(<?= $id ?>)" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Use javascript:void(0) to execute JS without reloading or navigate to another page -->
<a href="javascript:void(0)" class="btn-create-flashcard" id="openModal">＋</a>