-- Adds a flag so the end-date reminder cron only notifies HR once per intern.
ALTER TABLE `interns`
    ADD COLUMN `completion_reminded_at` TIMESTAMP NULL DEFAULT NULL AFTER `updated_at`;
