-- Historical partner contributions (Oct 2025 - Jul 2026), from the operating-cost spreadsheet.
-- Each month is an Operations contribution dated the 1st of that month. The one-off "startup
-- cost" entry (Jun 2026) is recorded as Investment, since it's capital that decides ownership
-- shares, not a running cost.
-- Idempotent: safe to re-run (each row is skipped if a matching partner_id/type/date/amount
-- row already exists). Run this in phpMyAdmin after `partnership.sql` has already been applied.

-- Abraham Assenga (partner_id 1)
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2025-10-01', 465000.00, 'Monthly Contribution for October', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2025-10-01' AND `amount` = 465000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2025-11-01', 125000.00, 'Monthly Contribution for November', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2025-11-01' AND `amount` = 125000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2025-12-01', 140000.00, 'Monthly Contribution for December', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2025-12-01' AND `amount` = 140000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2026-01-01', 133000.00, 'Monthly Contribution for January', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2026-01-01' AND `amount` = 133000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2026-02-01', 144000.00, 'Monthly Contribution for February', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2026-02-01' AND `amount` = 144000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2026-03-01', 200000.00, 'Monthly Contribution for March', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2026-03-01' AND `amount` = 200000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2026-04-01', 132000.00, 'Monthly Contribution for April', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2026-04-01' AND `amount` = 132000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2026-05-01', 135000.00, 'Monthly Contribution for May', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2026-05-01' AND `amount` = 135000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Investment', '2026-06-01', 1000000.00, 'Startup cost', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Investment' AND `contribution_date` = '2026-06-01' AND `amount` = 1000000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2026-06-01', 110000.00, 'Monthly Contribution for June', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2026-06-01' AND `amount` = 110000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 1, 'Operations', '2026-07-01', 29400.00, 'Monthly Contribution for July', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 1 AND `type` = 'Operations' AND `contribution_date` = '2026-07-01' AND `amount` = 29400.00);

-- Beatus Kibiti (partner_id 2)
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2025-10-01', 466000.00, 'Monthly Contribution for October', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2025-10-01' AND `amount` = 466000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2025-11-01', 125000.00, 'Monthly Contribution for November', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2025-11-01' AND `amount` = 125000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2025-12-01', 152000.00, 'Monthly Contribution for December', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2025-12-01' AND `amount` = 152000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2026-01-01', 135000.00, 'Monthly Contribution for January', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2026-01-01' AND `amount` = 135000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2026-02-01', 144000.00, 'Monthly Contribution for February', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2026-02-01' AND `amount` = 144000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2026-03-01', 234000.00, 'Monthly Contribution for March', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2026-03-01' AND `amount` = 234000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2026-04-01', 133000.00, 'Monthly Contribution for April', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2026-04-01' AND `amount` = 133000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2026-05-01', 135000.00, 'Monthly Contribution for May', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2026-05-01' AND `amount` = 135000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Investment', '2026-06-01', 1001000.00, 'Startup cost', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Investment' AND `contribution_date` = '2026-06-01' AND `amount` = 1001000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2026-06-01', 110000.00, 'Monthly Contribution for June', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2026-06-01' AND `amount` = 110000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 2, 'Operations', '2026-07-01', 29400.00, 'Monthly Contribution for July', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 2 AND `type` = 'Operations' AND `contribution_date` = '2026-07-01' AND `amount` = 29400.00);

-- Gabriel Marandu (partner_id 3)
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2025-10-01', 470000.00, 'Monthly Contribution for October', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2025-10-01' AND `amount` = 470000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2025-11-01', 125000.00, 'Monthly Contribution for November', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2025-11-01' AND `amount` = 125000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2025-12-01', 135000.00, 'Monthly Contribution for December', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2025-12-01' AND `amount` = 135000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2026-01-01', 133000.00, 'Monthly Contribution for January', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2026-01-01' AND `amount` = 133000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2026-02-01', 144000.00, 'Monthly Contribution for February', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2026-02-01' AND `amount` = 144000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2026-03-01', 217000.00, 'Monthly Contribution for March', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2026-03-01' AND `amount` = 217000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2026-04-01', 132000.00, 'Monthly Contribution for April', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2026-04-01' AND `amount` = 132000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2026-05-01', 135000.00, 'Monthly Contribution for May', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2026-05-01' AND `amount` = 135000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Investment', '2026-06-01', 1000000.00, 'Startup cost', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Investment' AND `contribution_date` = '2026-06-01' AND `amount` = 1000000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2026-06-01', 110000.00, 'Monthly Contribution for June', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2026-06-01' AND `amount` = 110000.00);
INSERT INTO `partner_contributions` (`partner_id`, `type`, `contribution_date`, `amount`, `description`, `created_at`, `updated_at`)
SELECT 3, 'Operations', '2026-07-01', 29400.00, 'Monthly Contribution for July', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `partner_contributions` WHERE `partner_id` = 3 AND `type` = 'Operations' AND `contribution_date` = '2026-07-01' AND `amount` = 29400.00);
