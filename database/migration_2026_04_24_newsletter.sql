-- Newsletter subscribers (footer form captures these).
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id`         INT(11)      NOT NULL AUTO_INCREMENT,
    `email`      VARCHAR(191) NOT NULL,
    `name`       VARCHAR(120) NOT NULL DEFAULT '',
    `source`     VARCHAR(60)  NOT NULL DEFAULT 'footer',    -- footer | landing | exit_intent | manual
    `status`     VARCHAR(20)  NOT NULL DEFAULT 'subscribed',-- subscribed | unsubscribed
    `ip`         VARBINARY(16) NOT NULL,
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_email` (`email`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
