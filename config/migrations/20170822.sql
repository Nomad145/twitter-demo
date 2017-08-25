CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    handle VARCHAR(15) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_bin' NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    INDEX handle (handle),
    CONSTRAINT user_pk PRIMARY KEY (id),
    UNIQUE (handle),
    UNIQUE (email)
);

CREATE TABLE tweet
(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    message VARCHAR(140) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_bin' NOT NULL,
    date_posted DATETIME NOT NULL,
    INDEX user_id (user_id),
    FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE RESTRICT,
    CONSTRAINT tweet_pk PRIMARY KEY (id)
);
