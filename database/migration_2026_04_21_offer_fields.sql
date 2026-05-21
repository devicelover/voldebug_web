-- Fields commonly shown on offer letters. Both optional (empty = hidden via {{#IF}}).
ALTER TABLE `interns`
    ADD COLUMN `stipend`             VARCHAR(120) NOT NULL DEFAULT '' AFTER `mentor`,
    ADD COLUMN `reporting_location`  VARCHAR(191) NOT NULL DEFAULT '' AFTER `stipend`;
