<?php

return [
    'up' => [
        'sqlite' => "
            CREATE TABLE IF NOT EXISTS post_categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                post_id INTEGER NOT NULL,
                category_id INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE,
                UNIQUE(post_id, category_id)
            );
            CREATE INDEX IF NOT EXISTS idx_post_categories_post_id ON post_categories(post_id);
            CREATE INDEX IF NOT EXISTS idx_post_categories_category_id ON post_categories(category_id);
        ",
        'mysql' => "
            CREATE TABLE IF NOT EXISTS post_categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                post_id INT NOT NULL,
                category_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_post_id (post_id),
                INDEX idx_category_id (category_id),
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                UNIQUE KEY unique_post_category (post_id, category_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        'pgsql' => "
            CREATE TABLE IF NOT EXISTS post_categories (
                id SERIAL PRIMARY KEY,
                post_id INTEGER NOT NULL,
                category_id INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                UNIQUE(post_id, category_id)
            );
            CREATE INDEX IF NOT EXISTS idx_post_categories_post_id ON post_categories(post_id);
            CREATE INDEX IF NOT EXISTS idx_post_categories_category_id ON post_categories(category_id);
        "
    ],
    'down' => [
        'sqlite' => "
            DROP TABLE IF EXISTS post_categories
        ",
        'mysql' => "
            DROP TABLE IF EXISTS post_categories
        ",
        'pgsql' => "
            DROP TABLE IF EXISTS post_categories
        "
    ]
];