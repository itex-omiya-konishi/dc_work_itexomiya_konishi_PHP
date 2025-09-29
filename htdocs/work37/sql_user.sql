CREATE TABLE user (
    user_id INT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO user (user_id, username, password) VALUES (1001, '山田太郎', 'pass1234');
