-- Rate-limit hits. Cleaned up lazily (rows older than 24h pruned on each request).
CREATE TABLE IF NOT EXISTS `rate_limit_hits` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bucket`     VARCHAR(60)     NOT NULL,
    `ip`         VARBINARY(16)   NOT NULL,
    `hit_at`     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_bucket_ip_hit` (`bucket`, `ip`, `hit_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
