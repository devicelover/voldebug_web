-- Bulk email queue (cron processes N per minute to avoid spam blocks).
CREATE TABLE IF NOT EXISTS `email_queue` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `campaign_id`     INT(11)         NULL,
    `to_email`        VARCHAR(191)    NOT NULL,
    `to_name`         VARCHAR(191)    NOT NULL DEFAULT '',
    `subject`         VARCHAR(255)    NOT NULL,
    `body`            MEDIUMTEXT      NOT NULL,
    `from_email`      VARCHAR(191)    NOT NULL DEFAULT '',
    `from_name`       VARCHAR(191)    NOT NULL DEFAULT '',
    `reply_to`        VARCHAR(191)    NOT NULL DEFAULT '',
    `attachments`     VARCHAR(1000)   NOT NULL DEFAULT '',
    `unsubscribe_token` VARCHAR(48)   NOT NULL DEFAULT '',
    `status`          VARCHAR(20)     NOT NULL DEFAULT 'queued',  -- queued | sending | sent | failed | skipped
    `error_message`   TEXT            NULL,
    `scheduled_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `sent_at`         TIMESTAMP       NULL,
    `created_at`      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_status_sched` (`status`, `scheduled_at`),
    INDEX `idx_to_email` (`to_email`),
    INDEX `idx_campaign` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_campaigns` (
    `id`              INT(11)        NOT NULL AUTO_INCREMENT,
    `name`            VARCHAR(191)   NOT NULL,
    `subject`         VARCHAR(255)   NOT NULL,
    `body`            MEDIUMTEXT     NOT NULL,
    `audience`        VARCHAR(60)    NOT NULL DEFAULT 'custom',  -- applicants | interns | partners | seo_leads | custom
    `audience_filter` VARCHAR(255)   NOT NULL DEFAULT '',
    `from_label`      VARCHAR(60)    NOT NULL DEFAULT 'hr',      -- hr | mihir | deep | vraj | custom
    `recipients_count`INT(11)        NOT NULL DEFAULT 0,
    `status`          VARCHAR(20)    NOT NULL DEFAULT 'draft',   -- draft | queued | sending | completed | aborted
    `created_by`      VARCHAR(120)   NOT NULL DEFAULT '',
    `created_at`      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `started_at`      TIMESTAMP      NULL,
    `completed_at`    TIMESTAMP      NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Unsubscribe records — keyed by email so future sends to that address are skipped.
CREATE TABLE IF NOT EXISTS `email_unsubscribes` (
    `email`        VARCHAR(191) NOT NULL,
    `reason`       VARCHAR(255) NOT NULL DEFAULT '',
    `unsubscribed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Multiple signatories. Settings has only one — this lets HR pick a "From" identity per send.
CREATE TABLE IF NOT EXISTS `signatories` (
    `id`             INT(11)      NOT NULL AUTO_INCREMENT,
    `label`          VARCHAR(40)  NOT NULL,                      -- short slug: hr|meet|vraj|deep|mihir
    `name`           VARCHAR(120) NOT NULL,
    `designation`    VARCHAR(120) NOT NULL DEFAULT '',
    `email`          VARCHAR(191) NOT NULL,
    `phone`          VARCHAR(40)  NOT NULL DEFAULT '',
    `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_label` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
