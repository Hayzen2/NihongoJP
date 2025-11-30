CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(100) UNIQUE,
    country_id mediumint unsigned,
    state_id mediumint unsigned,
    city_id mediumint unsigned,
    password_hash VARCHAR(255),
    auth_provider ENUM('local', 'google') DEFAULT 'local',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_rank VARCHAR(100),  
    avatar VARCHAR(255),
    FOREIGN KEY (country_id) REFERENCES countries(id),
    FOREIGN KEY (state_id) REFERENCES states(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (email, name, username, country_id, state_id, city_id, password_hash, auth_provider, user_rank, avatar)
VALUES
('user1@example.com', 'User One', 'user1', 1, 1, 1, 'hash1', 'local', 'Beginner', NULL),
('user2@example.com', 'User Two', 'user2', 1, 1, 1, 'hash2', 'local', 'Beginner', NULL),
('user3@example.com', 'User Three', 'user3', 1, 1, 1, 'hash3', 'local', 'Intermediate', NULL),
('user4@example.com', 'User Four', 'user4', 1, 1, 1, 'hash4', 'local', 'Intermediate', NULL),
('user5@example.com', 'User Five', 'user5', 1, 1, 1, 'hash5', 'local', 'Advanced', NULL),
('user6@example.com', 'User Six', 'user6', 1, 1, 1, 'hash6', 'local', 'Advanced', NULL),
('user7@example.com', 'User Seven', 'user7', 1, 1, 1, 'hash7', 'local', 'Beginner', NULL),
('user8@example.com', 'User Eight', 'user8', 1, 1, 1, 'hash8', 'local', 'Intermediate', NULL),
('user9@example.com', 'User Nine', 'user9', 1, 1, 1, 'hash9', 'local', 'Advanced', NULL),
('user10@example.com', 'User Ten', 'user10', 1, 1, 1, 'hash10', 'local', 'Beginner', NULL);