CREATE TABLE IF NOT EXISTS post (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(140),
    creationDate DATETIME NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);