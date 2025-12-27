-- Run this if you already have the website_settings table and need to add the new payment columns

-- Add online payment columns (run this if columns don't exist)
ALTER TABLE `website_settings` 
ADD COLUMN IF NOT EXISTS `online_payment_enabled` TINYINT(1) NOT NULL DEFAULT 0 AFTER `cod_max_amount`,
ADD COLUMN IF NOT EXISTS `online_payment_label` VARCHAR(100) DEFAULT 'Pay Online (UPI/Card/NetBanking)' AFTER `online_payment_enabled`;

-- If your MySQL version doesn't support IF NOT EXISTS, use this instead:
-- ALTER TABLE `website_settings` ADD COLUMN `online_payment_enabled` TINYINT(1) NOT NULL DEFAULT 0;
-- ALTER TABLE `website_settings` ADD COLUMN `online_payment_label` VARCHAR(100) DEFAULT 'Pay Online (UPI/Card/NetBanking)';

-- Remove old payment_methods column if it exists (optional cleanup)
-- ALTER TABLE `website_settings` DROP COLUMN IF EXISTS `enabled_payment_methods`;
