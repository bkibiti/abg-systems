-- Sale deletions: audit log for deleted sale lines, plus the "Delete Sales" permission.
-- Idempotent: safe to re-run. Run this in phpMyAdmin (locally and on the server),
-- then `php artisan permission:cache-reset`.

CREATE TABLE IF NOT EXISTS `sale_deletions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `sale_id` INT NULL,
    `receipt_number` VARCHAR(45) NULL,
    `stock_id` INT NULL,
    `product_name` VARCHAR(100) NULL,
    `quantity` INT NULL,
    `selling_price` DECIMAL(20, 4) NULL,
    `amount` DECIMAL(20, 4) NULL,
    `sale_date` DATETIME NULL,
    `customer_id` INT NULL,
    `payment_method_id` INT NULL,
    `sale_created_by` INT NULL,
    `reason` VARCHAR(255) NOT NULL,
    `deleted_by` INT NOT NULL,
    `deleted_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sale_deletions_deleted_at_index` (`deleted_at`),
    KEY `sale_deletions_deleted_by_index` (`deleted_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permission (guard: web). 903 is the next free id after 902 (see `permissions` table).
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
SELECT 903, 'Delete Sales', 'web', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'Delete Sales' AND `guard_name` = 'web');

-- Give it to the admin role (Superuser).
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.`id`, r.`id`
FROM `permissions` p
JOIN `roles` r ON r.`name` = 'Superuser' AND r.`guard_name` = 'web'
WHERE p.`name` = 'Delete Sales' AND p.`guard_name` = 'web'
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rhp WHERE rhp.`permission_id` = p.`id` AND rhp.`role_id` = r.`id`
  );
