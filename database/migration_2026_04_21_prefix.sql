-- Optional title prefix (Mr/Ms/Mx/Dr) + first_name (used mid-letter references like "Ms. Rutu").
ALTER TABLE `interns`
    ADD COLUMN `title_prefix` VARCHAR(10)  NOT NULL DEFAULT '' AFTER `name`,
    ADD COLUMN `first_name`   VARCHAR(100) NOT NULL DEFAULT '' AFTER `title_prefix`;
