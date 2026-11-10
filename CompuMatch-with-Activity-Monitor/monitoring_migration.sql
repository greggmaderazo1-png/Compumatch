CREATE TABLE IF NOT EXISTS activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(60) NOT NULL,
    actor_type VARCHAR(30) NOT NULL DEFAULT 'guest',
    actor_id VARCHAR(120) NULL,
    actor_name VARCHAR(160) NULL,
    details VARCHAR(500) NULL,
    source_ip VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activity_created (created_at),
    INDEX idx_activity_type (event_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
