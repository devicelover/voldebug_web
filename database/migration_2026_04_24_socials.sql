-- Add modern social link columns (LinkedIn + Twitter/X) so the footer can surface them.
ALTER TABLE `settings`
    ADD COLUMN `linkedin` VARCHAR(191) NOT NULL DEFAULT '' AFTER `github`,
    ADD COLUMN `twitter`  VARCHAR(191) NOT NULL DEFAULT '' AFTER `linkedin`;
