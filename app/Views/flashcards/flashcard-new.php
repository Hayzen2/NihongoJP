<div class ="overlay d-none text-center" id="newFlashcardModal">
    <card class="flashcard-content p-4 shadow-sm rounded">
        <h1 class="mb-4 text-center">Create New Flashcard</h1>
        <form method = "POST" action = "/flashcards/new" id="new-flashcard-form">
            <div id="questions-section">
                <div class="mb-3">
                    <label for="topic" class="form-label">Topic</label>
                    <input type="text" class="form-control" id="topic" name="topic" required>
                </div>
                <div id="qa-container">
                    <div class="qa-item mb-4 p-3 rounded shadow-sm">
                    <label for ="question-1" class="form-label">Question #1</label>
                    <textarea id="question-1" class="form-control mb-2" rows="3" name="questions[]" placeholder="Enter question" required></textarea>

                    <label for ="answer-1" class="form-label">Answer #1</label>
                    <textarea id="answer-1" class="form-control mb-2" rows="3" name="answers[]" placeholder="Enter answer" required></textarea>

                    <!-- Delete QA Button -->
                    <button type="button" class="btn btn-delete-qa">Delete</button>
                    </div>
                </div>
            </div>
            <div class="mb-3 d-flex justify-content-center align-items-center gap-2">
                <button type="button" class="btn btn-new-question">+</button>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                <option id="public" value="public">🌍 Public</option>
                <option id="private" value="private">🔒 Private</option>
                </select>
            </div>
        </form>
        <button type="submit" class="btn btn-create-flashcard">Create</button>
        <button type="button" class="btn btn-cancel-flashcard" id="closeModal">Cancel</button>
    </card>
</div>
