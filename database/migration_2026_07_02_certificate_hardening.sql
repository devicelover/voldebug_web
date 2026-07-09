-- =====================================================================
-- Certificate hardening — bulk error tracking, revoke audit.
-- Adds columns used by the July 2026 review fixes.
-- Safe to apply on top of migration_2026_04_25_certificates.sql.
-- =====================================================================

ALTER TABLE `certificate_batches`
    ADD COLUMN `failed_details` MEDIUMTEXT NULL AFTER `failed_count`;

ALTER TABLE `certificates_issued`
    ADD COLUMN `revoked_at` TIMESTAMP NULL AFTER `revoked_reason`,
    ADD COLUMN `revoked_by` VARCHAR(120) NOT NULL DEFAULT '' AFTER `revoked_at`;
