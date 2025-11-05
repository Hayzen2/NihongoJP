CREATE TABLE IF NOT EXISTS books (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(250) NOT NULL,
    author VARCHAR(100) NOT NULL,
    publisher VARCHAR(100) NOT NULL,
    published_date DATE NOT NULL,
    source_link VARCHAR(250) NOT NULL,
    description TEXT NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS book_jlpt_levels (
    book_id INT UNSIGNED NOT NULL,
    jlpt_level ENUM('N5','N4','N3','N2','N1') NOT NULL,
    PRIMARY KEY (book_id, jlpt_level),
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);