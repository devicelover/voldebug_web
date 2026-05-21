-- =====================================================================
-- Voldebug HR / Intern Management — Schema Migration
-- Date: 2026-04-21
-- Purpose: add tables for intern tracking, letter templates, issued
-- letters (with QR verification), and an email log. Extends settings
-- with letterhead/signature/stamp columns and extends client_career
-- with status/job-link/timestamp.
--
-- REVIEW THIS BEFORE RUNNING. Migrations are idempotent where safe
-- (IF NOT EXISTS) but ALTER TABLE on existing tables is not — no
-- destructive changes are included.
-- =====================================================================

-- ---------------------------------------------------------------------
-- 1. Extend settings: letterhead + signatory + stamp + signature image
-- ---------------------------------------------------------------------
ALTER TABLE `settings`
    ADD COLUMN `logo`                  VARCHAR(255) NOT NULL DEFAULT '' AFTER `instagram`,
    ADD COLUMN `signature_image`       VARCHAR(255) NOT NULL DEFAULT '' AFTER `logo`,
    ADD COLUMN `stamp_image`           VARCHAR(255) NOT NULL DEFAULT '' AFTER `signature_image`,
    ADD COLUMN `signatory_name`        VARCHAR(191) NOT NULL DEFAULT '' AFTER `stamp_image`,
    ADD COLUMN `signatory_designation` VARCHAR(191) NOT NULL DEFAULT '' AFTER `signatory_name`,
    ADD COLUMN `hr_email`              VARCHAR(191) NOT NULL DEFAULT 'hr@voldebug.in' AFTER `signatory_designation`,
    ADD COLUMN `letterhead_address`    TEXT         NOT NULL                         AFTER `hr_email`;

-- ---------------------------------------------------------------------
-- 2. Extend client_career: application status, job link, timestamps
-- ---------------------------------------------------------------------
ALTER TABLE `client_career`
    ADD COLUMN `applied_job_id` INT(11)     NULL         AFTER `Position`,
    ADD COLUMN `status`         VARCHAR(30) NOT NULL DEFAULT 'applied' AFTER `pdf`,
    ADD COLUMN `notes`          TEXT        NULL         AFTER `status`,
    ADD COLUMN `created_at`     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `notes`,
    ADD INDEX `idx_status` (`status`),
    ADD INDEX `idx_applied_job_id` (`applied_job_id`);
-- status values: applied | reviewed | shortlisted | hired | rejected | withdrawn

-- ---------------------------------------------------------------------
-- 3. interns — promoted applicants + direct-add employees/interns
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `interns` (
    `id`                INT(11)       NOT NULL AUTO_INCREMENT,
    `applicant_id`      INT(11)       NULL,                    -- FK-ish to client_career.id (nullable for direct adds)
    `name`              VARCHAR(191)  NOT NULL,
    `email`             VARCHAR(191)  NOT NULL,
    `phone`             VARCHAR(30)   NOT NULL DEFAULT '',
    `role`              VARCHAR(191)  NOT NULL,                -- free-text role title shown on letter
    `role_tag`          VARCHAR(60)   NOT NULL DEFAULT 'general', -- matches template role_tag (cybersecurity_intern, web_dev_intern, etc.)
    `employee_type`     VARCHAR(20)   NOT NULL DEFAULT 'intern',  -- intern | employee | contractor
    `start_date`        DATE          NULL,
    `end_date`          DATE          NULL,
    `github_repo`       VARCHAR(255)  NOT NULL DEFAULT '',
    `linkedin_url`      VARCHAR(255)  NOT NULL DEFAULT '',
    `mentor`            VARCHAR(191)  NOT NULL DEFAULT '',
    `tasks_summary`     TEXT          NULL,                    -- what they worked on, shown in completion letter
    `performance_notes` TEXT          NULL,                    -- internal, never printed
    `status`            VARCHAR(30)   NOT NULL DEFAULT 'active', -- active | completed | terminated | on_hold
    `created_at`        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_email_type` (`email`, `employee_type`),
    INDEX `idx_role_tag` (`role_tag`),
    INDEX `idx_status` (`status`),
    INDEX `idx_applicant` (`applicant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- 4. letter_templates — editable templates per letter type + role
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `letter_templates` (
    `id`             INT(11)      NOT NULL AUTO_INCREMENT,
    `template_name`  VARCHAR(191) NOT NULL,                     -- e.g. "Cybersecurity Intern — Joining Letter"
    `letter_type`    VARCHAR(40)  NOT NULL,                     -- offer | joining | completion | experience | rejection | custom
    `role_tag`       VARCHAR(60)  NOT NULL DEFAULT 'general',   -- cybersecurity_intern | web_dev_intern | web_designer_intern | general
    `email_subject`  VARCHAR(255) NOT NULL,
    `email_body`     MEDIUMTEXT   NOT NULL,                     -- supports {{name}} {{role}} {{start_date}} {{end_date}} {{company}} {{signatory}} {{verify_url}}
    `letter_body`    MEDIUMTEXT   NOT NULL,                     -- HTML body rendered into letterhead PDF
    `attach_pdf`     TINYINT(1)   NOT NULL DEFAULT 1,           -- attach generated PDF to the email
    `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_type_role` (`letter_type`, `role_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- 5. letters_issued — every generated letter, with QR-verifiable token
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `letters_issued` (
    `id`             INT(11)      NOT NULL AUTO_INCREMENT,
    `verify_token`   VARCHAR(64)  NOT NULL,                     -- random URL-safe token, embedded in QR
    `intern_id`      INT(11)      NOT NULL,
    `template_id`    INT(11)      NOT NULL,
    `letter_type`    VARCHAR(40)  NOT NULL,                     -- denormalised for fast verify lookup
    `recipient_name` VARCHAR(191) NOT NULL,                     -- snapshot at issue time
    `recipient_email`VARCHAR(191) NOT NULL,
    `role_snapshot`  VARCHAR(191) NOT NULL DEFAULT '',          -- role as printed on letter
    `issue_date`     DATE         NOT NULL,
    `pdf_path`       VARCHAR(500) NOT NULL,                     -- relative path under Admin/letters/
    `rendered_html`  MEDIUMTEXT   NULL,                         -- for reprint / audit
    `revoked`        TINYINT(1)   NOT NULL DEFAULT 0,
    `revoked_reason` VARCHAR(255) NULL,
    `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_verify_token` (`verify_token`),
    INDEX `idx_intern` (`intern_id`),
    INDEX `idx_template` (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- 6. email_log — audit trail for every outgoing email
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `email_log` (
    `id`            INT(11)      NOT NULL AUTO_INCREMENT,
    `to_email`      VARCHAR(191) NOT NULL,
    `to_name`       VARCHAR(191) NOT NULL DEFAULT '',
    `subject`       VARCHAR(255) NOT NULL,
    `body`          MEDIUMTEXT   NOT NULL,
    `attachments`   VARCHAR(1000) NOT NULL DEFAULT '',          -- comma-separated paths
    `context_type`  VARCHAR(40)  NOT NULL DEFAULT 'manual',     -- letter | contact_form | quote | manual
    `context_id`    INT(11)      NULL,                          -- e.g. letters_issued.id
    `status`        VARCHAR(20)  NOT NULL DEFAULT 'pending',    -- pending | sent | failed
    `error_message` TEXT         NULL,
    `sent_at`       TIMESTAMP    NULL,
    `created_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_to` (`to_email`),
    INDEX `idx_status` (`status`),
    INDEX `idx_context` (`context_type`, `context_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- 7. Seed default letter templates (inserted once)
-- ---------------------------------------------------------------------
INSERT INTO `letter_templates` (template_name, letter_type, role_tag, email_subject, email_body, letter_body) VALUES
('Generic — Offer Letter',                  'offer',      'general',              'Your offer from Voldebug',            'Dear {{name}},\n\nWe are pleased to offer you the position of {{role}} at Voldebug, commencing {{start_date}}. The attached letter contains the formal offer. Please verify this letter at {{verify_url}}.\n\nWarm regards,\n{{signatory}}',          '<h2>Offer of Engagement</h2><p>Dear {{name}},</p><p>We are pleased to offer you the position of <strong>{{role}}</strong> at Voldebug, commencing {{start_date}}.</p><p>We look forward to you joining us.</p>'),
('Generic — Joining Letter',                'joining',    'general',              'Welcome to Voldebug',                 'Dear {{name}},\n\nWelcome to Voldebug as {{role}}, effective {{start_date}}. The attached joining letter is verifiable at {{verify_url}}.\n\n— {{signatory}}',                                                                                     '<h2>Joining Letter</h2><p>Dear {{name}},</p><p>This is to confirm that you have joined Voldebug as <strong>{{role}}</strong> effective <strong>{{start_date}}</strong>.</p>'),
('Cybersecurity Intern — Joining Letter',   'joining',    'cybersecurity_intern', 'Welcome to Voldebug — Cybersecurity', 'Dear {{name}},\n\nWelcome aboard the Cybersecurity team at Voldebug. Your internship as {{role}} begins on {{start_date}}. Letter verifiable at {{verify_url}}.\n\n— {{signatory}}',                                                           '<h2>Joining Letter — Cybersecurity Internship</h2><p>Dear {{name}},</p><p>We are pleased to confirm your internship as <strong>{{role}}</strong> with Voldebug''s Cybersecurity team, commencing <strong>{{start_date}}</strong>.</p>'),
('Web Development Intern — Joining Letter', 'joining',    'web_dev_intern',       'Welcome to Voldebug — Web Development','Dear {{name}},\n\nWelcome to the Web Development team. Your internship as {{role}} begins {{start_date}}. Verify at {{verify_url}}.\n\n— {{signatory}}',                                                                                      '<h2>Joining Letter — Web Development Internship</h2><p>Dear {{name}},</p><p>We confirm your internship as <strong>{{role}}</strong> with Voldebug''s Web Development team from <strong>{{start_date}}</strong>.</p>'),
('Web Designer Intern — Joining Letter',    'joining',    'web_designer_intern',  'Welcome to Voldebug — Design',        'Dear {{name}},\n\nWelcome to the Design team. Your internship as {{role}} begins {{start_date}}. Verify at {{verify_url}}.\n\n— {{signatory}}',                                                                                               '<h2>Joining Letter — Web Designer Internship</h2><p>Dear {{name}},</p><p>We confirm your internship as <strong>{{role}}</strong> with Voldebug''s Design team, starting <strong>{{start_date}}</strong>.</p>'),
('Generic — Internship Completion',         'completion', 'general',              'Your Internship Completion Letter',   'Dear {{name}},\n\nCongratulations on completing your internship at Voldebug. The attached completion letter is verifiable at {{verify_url}}.\n\n— {{signatory}}',                                                                              '<h2>Internship Completion Certificate</h2><p>This is to certify that <strong>{{name}}</strong> has successfully completed the internship as <strong>{{role}}</strong> at Voldebug from <strong>{{start_date}}</strong> to <strong>{{end_date}}</strong>.</p><p>During the internship, they contributed to: {{tasks_summary}}</p>'),
('Generic — Experience Letter',             'experience', 'general',              'Your Experience Letter from Voldebug','Dear {{name}},\n\nAttached is your experience letter. It is verifiable at {{verify_url}}.\n\n— {{signatory}}',                                                                                                                                 '<h2>Experience Letter</h2><p>This is to certify that <strong>{{name}}</strong> was engaged with Voldebug as <strong>{{role}}</strong> from <strong>{{start_date}}</strong> to <strong>{{end_date}}</strong>.</p>'),
('Generic — Rejection',                     'rejection',  'general',              'Update on your Voldebug application', 'Dear {{name}},\n\nThank you for your interest in Voldebug. After review, we are unable to move forward with your application for {{role}} at this time. We wish you the best.\n\n— {{signatory}}',                                           '');
