<?php

return [
    'up' => [
        'sqlite' => "
            CREATE TABLE IF NOT EXISTS post_tags (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                post_id INTEGER NOT NULL,
                tag_id INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE,
                UNIQUE(post_id, tag_id)
            );
            CREATE INDEX IF NOT EXISTS idx_post_tags_post_id ON post_tags(post_id);
            CREATE INDEX IF NOT EXISTS idx_post_tags_tag_id ON post_tags(tag_id);
        ",
        'mysql' => "
            CREATE TABLE IF NOT EXISTS post_tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                post_id INT NOT NULL,
                tag_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_post_id (post_id),
                INDEX idx_tag_id (tag_id),
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
                UNIQUE KEY unique_post_tag (post_id, tag_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        'pgsql' => "
            CREATE TABLE IF NOT EXISTS post_tags (
                id SERIAL PRIMARY KEY,
                post_id INTEGER NOT NULL,
                tag_id INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
                UNIQUE(post_id, tag_id)
            );
            CREATE INDEX IF NOT EXISTS idx_post_tags_post_id ON post_tags(post_id);
            CREATE INDEX IF NOT EXISTS idx_post_tags_tag_id ON post_tags(tag_id);
        "
    ],
    'down' => [
        'sqlite' => "
            DROP TABLE IF EXISTS post_tags
        ",
        'mysql' => "
            DROP TABLE IF EXISTS post_tags
        ",
        'pgsql' => "
            DROP TABLE IF EXISTS post_tags
        "
    ]
];