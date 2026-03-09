CREATE DATABASE IF NOT EXISTS astoncv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE astoncv;

CREATE TABLE IF NOT EXISTS cvs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    education TEXT NOT NULL,
    key_language VARCHAR(80) NOT NULL,
    profile TEXT NOT NULL,
    links TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);
