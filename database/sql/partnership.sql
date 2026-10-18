-- Partnership module: partners, their contributions, and the two new permissions.
-- Idempotent: safe to re-run. Run this in phpMyAdmin (locally and on the server),
-- then `php artisan permission:cache-reset`.

CREATE TABLE IF NOT EXISTS `partners` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(45) NULL,
    `email` VARCHAR(100) NULL,
    `joined_on` DATE NULL,
    `notes` TEXT NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `partners_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `partner_contributions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `partner_id` INT UNSIGNED NOT NULL,
    `type` ENUM('Investment', 'Operations') NOT NULL,
    `contribution_date` DATE NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `description` VARCHAR(255) NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `partner_contributions_partner_id_index` (`partner_id`),
    KEY `partner_contributions_type_index` (`type`),
    KEY `partner_contributions_contribution_date_index` (`contribution_date`),
    CONSTRAINT `partner_contributions_partner_id_foreign` FOREIGN KEY (`partner_id`)
        REFERENCES `partners` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rename of the contribution types ("Equipment" -> "Investment", "Operational" -> "Operations").
-- Widen the enum first so existing rows stay valid, move the data, then narrow it back down.
-- Safe to re-run: the widen/narrow always succeeds, and the UPDATEs no-op once nothing matches
-- the old values (including on a brand-new install, which already has the final enum above).
ALTER TABLE `partner_contributions`
    MODIFY COLUMN `type` ENUM('Equipment', 'Operational', 'Investment', 'Operations') NOT NULL;

UPDATE `partner_contributions` SET `type` = 'Investment' WHERE `type` = 'Equipment';
UPDATE `partner_contributions` SET `type` = 'Operations' WHERE `type` = 'Operational';

ALTER TABLE `partner_contributions`
    MODIFY COLUMN `type` ENUM('Investment', 'Operations') NOT NULL;

-- Permissions (guard: web). 900s is the next free group after the existing
-- 100-800 blocks (see `permissions` table).
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
SELECT 900, 'View Partnership', 'web', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'View Partnership' AND `guard_name` = 'web');

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
SELECT 901, 'Manage Partnership', 'web', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'Manage Partnership' AND `guard_name` = 'web');

-- Give both to the admin role (Superuser).
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.`id`, r.`id`
FROM `permissions` p
JOIN `roles` r ON r.`name` = 'Superuser' AND r.`guard_name` = 'web'
WHERE p.`name` = 'View Partnership' AND p.`guard_name` = 'web'
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rhp WHERE rhp.`permission_id` = p.`id` AND rhp.`role_id` = r.`id`
  );

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.`id`, r.`id`
FROM `permissions` p
JOIN `roles` r ON r.`name` = 'Superuser' AND r.`guard_name` = 'web'
WHERE p.`name` = 'Manage Partnership' AND p.`guard_name` = 'web'
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rhp WHERE rhp.`permission_id` = p.`id` AND rhp.`role_id` = r.`id`
  );
