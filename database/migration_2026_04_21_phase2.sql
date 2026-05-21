-- Phase 2: offer acceptance, intern check-ins, job click tracking.

-- Letter responses (offer acceptance / decline). One row per issued letter
-- that the candidate acts on via the acceptance link.
CREATE TABLE IF NOT EXISTS `letter_responses` (
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `letter_id`   INT(11)      NOT NULL,
    `response`    VARCHAR(20)  NOT NULL,                -- accepted | declined
    `responder_ip`VARBINARY(16) NOT NULL,
    `user_agent`  VARCHAR(255) NOT NULL DEFAULT '',
    `notes`       TEXT         NULL,
    `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_letter` (`letter_id`),              -- one response per letter
    INDEX `idx_response` (`response`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Intern weekly check-ins. Completion letter tasks_summary can be auto-aggregated from these.
CREATE TABLE IF NOT EXISTS `intern_checkins` (
    `id`            INT(11)     NOT NULL AUTO_INCREMENT,
    `intern_id`     INT(11)     NOT NULL,
    `week_starting` DATE        NOT NULL,                -- Monday of the week
    `notes`         TEXT        NOT NULL,                -- what they worked on
    `rating`        TINYINT     NULL,                    -- 1-5, optional
    `created_at`    TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_intern_week` (`intern_id`, `week_starting`),
    INDEX `idx_intern` (`intern_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Click tracking on public job pages.
CREATE TABLE IF NOT EXISTS `career_clicks` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `job_id`     INT(11)         NOT NULL,
    `kind`       VARCHAR(20)     NOT NULL DEFAULT 'view',  -- view | apply_click
    `ip`         VARBINARY(16)   NOT NULL,
    `referrer`   VARCHAR(500)    NOT NULL DEFAULT '',
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_job_kind_time` (`job_id`, `kind`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
