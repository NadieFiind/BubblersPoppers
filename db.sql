CREATE DATABASE bubblerspoppers;
USE bubblerspoppers;

CREATE TABLE bubbles (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    made_at DATETIME NOT NULL,
    popped_at DATETIME
);
