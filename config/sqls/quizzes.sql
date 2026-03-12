-- Table for quizzes
CREATE TABLE IF NOT EXISTS quizzes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    time_limit INT DEFAULT 60,
    jlpt_level ENUM('N5','N4','N3','N2','N1') DEFAULT 'N5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Combined table for quiz questions and answers
CREATE TABLE IF NOT EXISTS quiz_qa (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT UNSIGNED NOT NULL,
    question TEXT NOT NULL,
    correct_answer TEXT NOT NULL,
    wrong_answer_1 TEXT,
    wrong_answer_2 TEXT,
    wrong_answer_3 TEXT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table to track user quiz attempts
CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    score DECIMAL(5,2) DEFAULT 0,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table to store user answers for each attempt
CREATE TABLE IF NOT EXISTS quiz_attempt_answers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT UNSIGNED NOT NULL,
    quiz_qa_id INT UNSIGNED NOT NULL,
    selected_answers TEXT,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_qa_id) REFERENCES quiz_qa(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- sample data for quizzes

INSERT INTO quizzes (title, description, time_limit, jlpt_level) VALUES
('JLPT N5 Basic Vocabulary', 'Multiple-choice quiz for common N5 vocab.', 120, 'N5'),
('JLPT N4 Grammar Quick Test', 'Short grammar check for N4 points.', 90, 'N4');

-- find the assigned quiz ids (assume 1 and 2). If auto ids differ, adapt controller usage.

-- Questions for quiz 1 (vocab)
INSERT INTO quiz_qa (quiz_id, question, correct_answer, wrong_answer_1, wrong_answer_2, wrong_answer_3)
VALUES
(1, 'What is the meaning of 「犬」 ?', 'dog', 'cat', 'car', 'person'),
(1, 'What is the reading of 「私」 in hiragana?', 'わたし', 'あなた', 'かれ', 'それ'),
(1, 'Choose the correct translation: 「学校」', 'school', 'hospital', 'office', 'park'),
(1, 'What does 「食べる」 mean?', 'to eat', 'to drink', 'to sleep', 'to walk');

-- Questions for quiz 2 (grammar)
INSERT INTO quiz_qa (quiz_id, question, correct_answer, wrong_answer_1, wrong_answer_2, wrong_answer_3)
VALUES
(2, 'Which particle marks the direct object?', 'を', 'が', 'に', 'で'),
(2, 'Choose the polite copula', 'です', 'だ', 'である', 'なり'),
(2, 'Which is the past tense of 行く (informal)?', '行った', '行く', '行きます', '行かない');