-- Query SQL untuk memperbaiki kolom tabel voting di cPanel / phpMyAdmin
-- Database: kemw8233_data_pubg

-- 1. Tambah kolom urutan pada voting_candidates (mengatasi error orderBy('urutan'))
ALTER TABLE `voting_candidates` ADD COLUMN IF NOT EXISTS `urutan` INT NOT NULL DEFAULT 0 AFTER `unsur`;

-- 2. Tambah kolom pelengkap pada voting_votes
ALTER TABLE `voting_votes` ADD COLUMN IF NOT EXISTS `voter_name` VARCHAR(255) NULL AFTER `id`;
ALTER TABLE `voting_votes` ADD COLUMN IF NOT EXISTS `user_agent` VARCHAR(255) NULL AFTER `ip_address`;
ALTER TABLE `voting_votes` ADD COLUMN IF NOT EXISTS `device_cookie_id` VARCHAR(255) NULL AFTER `user_agent`;
