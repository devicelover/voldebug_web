-- =====================================================================
-- E-Certificate module — for training / seminar / program completions.
-- Supports an optional partner institute (co-branded certs).
-- =====================================================================

-- Partner institutes (universities, training partners, co-organisers).
CREATE TABLE IF NOT EXISTS `certificate_partners` (
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(191) NOT NULL,
    `subtitle`    VARCHAR(191) NOT NULL DEFAULT '',     -- e.g. "Department of CSE" or "Mumbai Chapter"
    `logo`        VARCHAR(255) NOT NULL DEFAULT '',     -- filename under Admin/images/cert_partners/
    `website`     VARCHAR(255) NOT NULL DEFAULT '',
    `signatory_name`        VARCHAR(120) NOT NULL DEFAULT '',
    `signatory_designation` VARCHAR(120) NOT NULL DEFAULT '',
    `signature_image`       VARCHAR(255) NOT NULL DEFAULT '',
    `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Certificate templates (titles, body copy, default course names).
CREATE TABLE IF NOT EXISTS `certificate_templates` (
    `id`            INT(11)      NOT NULL AUTO_INCREMENT,
    `template_name` VARCHAR(191) NOT NULL,
    `title`         VARCHAR(191) NOT NULL DEFAULT 'Certificate of Completion',  -- big heading
    `cert_kind`     VARCHAR(30)  NOT NULL DEFAULT 'completion',   -- completion | participation | achievement | merit | custom
    `body_html`     MEDIUMTEXT   NOT NULL,           -- supports {{name}} {{course}} {{date}} {{duration}} {{custom1..5}} etc.
    `email_subject` VARCHAR(255) NOT NULL DEFAULT 'Your certificate from {{company}}',
    `email_body`    MEDIUMTEXT   NOT NULL DEFAULT '',
    `orientation`   VARCHAR(12)  NOT NULL DEFAULT 'landscape',    -- landscape | portrait
    `qr_enabled`    TINYINT(1)   NOT NULL DEFAULT 1,
    `is_active`     TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_kind` (`cert_kind`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Batches — every bulk upload creates one row so admins can group/track issuances.
CREATE TABLE IF NOT EXISTS `certificate_batches` (
    `id`              INT(11)      NOT NULL AUTO_INCREMENT,
    `name`            VARCHAR(191) NOT NULL,            -- e.g. "AI Bootcamp — Batch 7"
    `template_id`     INT(11)      NULL,
    `partner_id`      INT(11)      NULL,
    `course_name`     VARCHAR(191) NOT NULL DEFAULT '',
    `recipient_count` INT(11)      NOT NULL DEFAULT 0,
    `success_count`   INT(11)      NOT NULL DEFAULT 0,
    `failed_count`    INT(11)      NOT NULL DEFAULT 0,
    `status`          VARCHAR(20)  NOT NULL DEFAULT 'queued',   -- queued | processing | completed | aborted
    `notes`           TEXT         NULL,
    `created_by`      VARCHAR(120) NOT NULL DEFAULT '',
    `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `completed_at`    TIMESTAMP    NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One row per issued certificate.
CREATE TABLE IF NOT EXISTS `certificates_issued` (
    `id`              INT(11)       NOT NULL AUTO_INCREMENT,
    `verify_token`    VARCHAR(64)   NOT NULL,                  -- URL-safe random; embedded in QR
    `template_id`     INT(11)       NOT NULL,
    `partner_id`      INT(11)       NULL,                      -- optional partner institute
    `batch_id`        INT(11)       NULL,                      -- which bulk upload
    `recipient_name`  VARCHAR(191)  NOT NULL,
    `recipient_email` VARCHAR(191)  NOT NULL DEFAULT '',
    `course_name`     VARCHAR(255)  NOT NULL DEFAULT '',
    `completion_date` DATE          NULL,
    `duration`        VARCHAR(80)   NOT NULL DEFAULT '',       -- e.g. "12 weeks" or "20 hours"
    `custom_fields`   TEXT          NULL,                      -- JSON object for {{custom1}}..{{custom5}}
    `include_signature` TINYINT(1)  NOT NULL DEFAULT 1,
    `include_stamp`     TINYINT(1)  NOT NULL DEFAULT 1,
    `pdf_path`        VARCHAR(500)  NOT NULL,
    `revoked`         TINYINT(1)    NOT NULL DEFAULT 0,
    `revoked_reason`  VARCHAR(255)  NULL,
    `emailed_at`      TIMESTAMP     NULL,
    `created_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_verify_token` (`verify_token`),
    INDEX `idx_template` (`template_id`),
    INDEX `idx_partner` (`partner_id`),
    INDEX `idx_batch` (`batch_id`),
    INDEX `idx_email` (`recipient_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
