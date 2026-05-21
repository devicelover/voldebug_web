-- Letterhead v2: match the attached Welcome Letter PDF.
-- New company identity fields (legal name, CIN, website, admin email, brand color)
-- and new per-intern fields (enrollment number, internship type, college).

ALTER TABLE `settings`
    ADD COLUMN `company_legal_name` VARCHAR(191) NOT NULL DEFAULT 'Voldebug Innovations Pvt. Ltd.' AFTER `name`,
    ADD COLUMN `cin`                VARCHAR(50)  NOT NULL DEFAULT '' AFTER `company_legal_name`,
    ADD COLUMN `website`            VARCHAR(191) NOT NULL DEFAULT '' AFTER `cin`,
    ADD COLUMN `admin_email`        VARCHAR(191) NOT NULL DEFAULT '' AFTER `website`,
    ADD COLUMN `brand_color`        VARCHAR(7)   NOT NULL DEFAULT '#1a8f4a' AFTER `admin_email`;

ALTER TABLE `interns`
    ADD COLUMN `enrollment_number` VARCHAR(60)  NOT NULL DEFAULT '' AFTER `phone`,
    ADD COLUMN `internship_type`   VARCHAR(30)  NOT NULL DEFAULT '' AFTER `employee_type`,   -- Hybrid | Remote | On-site | full-time
    ADD COLUMN `college`           VARCHAR(191) NOT NULL DEFAULT '' AFTER `internship_type`;
