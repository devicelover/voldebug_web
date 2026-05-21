-- Speed up duplicate-application detection.
ALTER TABLE `client_career`
    ADD INDEX `idx_email_position` (`email`(60), `Position`(60), `applied_job_id`);
