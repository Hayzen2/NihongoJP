<div class ="overlay d-none text-center" id="editFlashcardModal">
    <card class="flashcard-content p-4 shadow-sm rounded">
        <?php if (isset($error) && !empty($error)): ?>
            <div class="alert alert-flashcard alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <h1 class="mb-4 text-center">Edit Existing Flashcard</h1>
        <form method = "POST" action = "/flashcards/update" id="edit-flashcard-form">
            <div id="questions-section">
                <div class="mb-3">
                    <label for="topic" class="form-label">Topic</label>
                    <input type="text" class="form-control" id="topic-edit" name="topic" required>
                </div>
                <div id="edit-qa-container"></div>
            </div>
            <div class="mb-3 d-flex justify-content-center align-items-center gap-2">
                <button type="button" class="btn btn-new-question-edit">+</button>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status-edit" name="status" required>
                <option id="public" value="public">🌍 Public</option>
                <option id="private" value="private">🔒 Private</option>
                </select>
            </div>
        </form>
        <button type="submit" class="btn btn-edit-flashcard">Save Changes</button>
        <button type="button" class="btn btn-cancel-flashcard" id="edit-closeModal">Cancel</button>
    </card>
</div>
