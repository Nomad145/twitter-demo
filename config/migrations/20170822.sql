CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    handle VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL,
    CONSTRAINT user_pk PRIMARY KEY (id),
    UNIQUE (handle),
    UNIQUE (email)
)
CHARACTER SET utf8mb4
COLLATE utf8mb4_bin;

CREATE TABLE tweet
(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    message VARCHAR(140) NOT NULL,
    date_posted DATETIME NOT NULL,
    INDEX user_id (user_id),
    FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE RESTRICT,
    CONSTRAINT tweet_pk PRIMARY KEY (id)
)
CHARACTER SET utf8mb4
COLLATE utf8mb4_bin;
