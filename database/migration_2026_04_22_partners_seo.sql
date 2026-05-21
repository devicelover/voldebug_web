-- =====================================================================
-- Key Partners (international) + SEO leads (digital marketing).
-- =====================================================================

CREATE TABLE IF NOT EXISTS `key_partners` (
    `id`              INT(11)      NOT NULL AUTO_INCREMENT,
    `company_name`    VARCHAR(191) NOT NULL,
    `contact_name`    VARCHAR(191) NOT NULL,
    `title_prefix`    VARCHAR(10)  NOT NULL DEFAULT '',           -- Mr/Ms/Mx/Dr
    `first_name`      VARCHAR(100) NOT NULL DEFAULT '',
    `email`           VARCHAR(191) NOT NULL,
    `phone`           VARCHAR(40)  NOT NULL DEFAULT '',
    `country`         VARCHAR(80)  NOT NULL DEFAULT '',
    `city`            VARCHAR(120) NOT NULL DEFAULT '',
    `website`         VARCHAR(255) NOT NULL DEFAULT '',
    `territories`     VARCHAR(255) NOT NULL DEFAULT '',           -- e.g. "Germany, Austria, Switzerland"
    `commission_rate` VARCHAR(40)  NOT NULL DEFAULT '',           -- free text: "15%", "tiered" etc.
    `status`          VARCHAR(30)  NOT NULL DEFAULT 'prospect',   -- prospect|invited|onboarded|active|paused|terminated|rejected
    `intro_sent_at`   TIMESTAMP    NULL DEFAULT NULL,
    `onboarded_at`    TIMESTAMP    NULL DEFAULT NULL,
    `ended_at`        TIMESTAMP    NULL DEFAULT NULL,
    `notes`           TEXT         NULL,
    `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `seo_leads` (
    `id`              INT(11)      NOT NULL AUTO_INCREMENT,
    `business_name`   VARCHAR(191) NOT NULL,
    `contact_name`    VARCHAR(191) NOT NULL DEFAULT '',
    `email`           VARCHAR(191) NOT NULL,
    `phone`           VARCHAR(40)  NOT NULL DEFAULT '',
    `website`         VARCHAR(255) NOT NULL DEFAULT '',
    `industry`        VARCHAR(120) NOT NULL DEFAULT '',
    `monthly_budget`  VARCHAR(80)  NOT NULL DEFAULT '',           -- "₹10k-25k", "tbd"
    `services`        VARCHAR(255) NOT NULL DEFAULT '',           -- "SEO, Google Ads, Content"
    `source`          VARCHAR(60)  NOT NULL DEFAULT '',           -- referral, website, linkedin, cold
    `status`          VARCHAR(30)  NOT NULL DEFAULT 'new',        -- new|contacted|qualified|proposal_sent|won|lost
    `notes`           TEXT         NULL,
    `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
