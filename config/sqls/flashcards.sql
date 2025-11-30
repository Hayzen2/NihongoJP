CREATE TABLE IF NOT EXISTS flashcards (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topic VARCHAR(100) NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'public' CHECK (status IN ('public', 'private')),
    UNIQUE KEY unique_topic_per_user (topic, user_id), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO flashcards (topic, user_id, status) VALUES
('Japanese Greetings', 1, 'public'),
('Japanese Animals', 1, 'public'),
('Japanese Weather Words', 2, 'public'),
('Japanese Numbers', 2, 'public'),
('Japanese Classroom Terms', 3, 'public'),
('Japanese Food Vocabulary', 3, 'public'),
('Japanese Travel Phrases', 4, 'public'),
('Japanese Emotions', 4, 'public'),
('Basic Kanji Set 3', 5, 'public'),
('Basic Kanji Set 1', 5, 'public'),
('Japanese Clothing Vocabulary', 6, 'private'),
('Japanese Colors', 6, 'private'),
('Japanese Hobbies', 7, 'private'),
('Japanese Family Terms', 7, 'private'),
('Japanese Verbs Basic', 8, 'private'),
('Japanese Transportation Words', 8, 'private'),
('Japanese Time & Days', 9, 'private'),
('Japanese Nature Words', 9, 'private'),
('Basic Kanji Set 2', 10, 'private'),
('Basic Kanji Set 4', 10, 'private');








