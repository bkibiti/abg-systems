-- Partner payouts module: payouts to partners, plus the "Reverse payouts" permission.
-- Idempotent: safe to re-run. Run this in phpMyAdmin (locally and on the server),
-- then `php artisan permission:cache-reset`.

CREATE TABLE IF NOT EXISTS `partner_payouts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payout_no` VARCHAR(20) NOT NULL,
    `partner_id` INT UNSIGNED NOT NULL,
    `payout_date` DATE NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `payment_method_id` INT NULL,
    `reference` VARCHAR(100) NULL,
    `note` VARCHAR(255) NULL,
    `batch_id` CHAR(36) NULL,
    `status` ENUM('Paid', 'Reversed') NOT NULL DEFAULT 'Paid',
    `reversed_at` DATETIME NULL,
    `reversed_by` INT UNSIGNED NULL,
    `reversal_reason` VARCHAR(255) NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `partner_payouts_payout_no_unique` (`payout_no`),
    KEY `partner_payouts_partner_id_index` (`partner_id`),
    KEY `partner_payouts_payout_date_index` (`payout_date`),
    KEY `partner_payouts_status_index` (`status`),
    KEY `partner_payouts_batch_id_index` (`batch_id`),
    CONSTRAINT `partner_payouts_amount_positive` CHECK (`amount` > 0),
    CONSTRAINT `partner_payouts_partner_id_foreign` FOREIGN KEY (`partner_id`)
        REFERENCES `partners` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `partner_payouts_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`)
        REFERENCES `payment_method` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permission (guard: web). 902 is the next free id after the partnership block (900, 901).
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
SELECT 902, 'Reverse payouts', 'web', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'Reverse payouts' AND `guard_name` = 'web');

-- Give it to the admin role (Superuser).
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.`id`, r.`id`
FROM `permissions` p
JOIN `roles` r ON r.`name` = 'Superuser' AND r.`guard_name` = 'web'
WHERE p.`name` = 'Reverse payouts' AND p.`guard_name` = 'web'
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rhp WHERE rhp.`permission_id` = p.`id` AND rhp.`role_id` = r.`id`
  );
