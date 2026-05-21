-- Site-wide page view tracking. Excludes bots; ~minimal data.
CREATE TABLE IF NOT EXISTS `page_views` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `path`       VARCHAR(255)    NOT NULL,
    `query`      VARCHAR(255)    NOT NULL DEFAULT '',
    `referrer`   VARCHAR(500)    NOT NULL DEFAULT '',
    `ip`         VARBINARY(16)   NOT NULL,
    `ua_short`   VARCHAR(60)     NOT NULL DEFAULT '',
    `country`    VARCHAR(2)      NOT NULL DEFAULT '',   -- cloudflare CF-IPCountry if available
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_path_time` (`path`, `created_at`),
    INDEX `idx_created`   (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
