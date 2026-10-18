-- ---------------------------------------------------------------------------
-- Simple POS update (one sale at a time)
--
-- 1. Renames sales.payment_method -> sales.payment_method_id (data is kept).
-- 2. Adds indexes on sales.customer_id and sales.payment_method_id.
-- 3. Adds foreign keys:
--      sales.customer_id       -> customers.id       ON DELETE SET NULL
--      sales.payment_method_id -> payment_method.id  ON DELETE RESTRICT
--
-- Safe to run more than once: every step checks information_schema first.
-- Run it in phpMyAdmin on the abg database, locally and on the server:
-- click the database name in the left panel FIRST, then open its SQL tab.
-- ---------------------------------------------------------------------------

-- 0. Stop at once if no database is selected (every check below needs DATABASE())
SET @sql := IF(DATABASE() IS NULL,
    'SELECT * FROM `SELECT THE abg DATABASE IN THE LEFT PANEL FIRST`',
    'SELECT DATABASE() AS running_on');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 1. Rename payment_method -> payment_method_id
SET @needs_rename := (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales' AND COLUMN_NAME = 'payment_method'
) > 0 AND (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales' AND COLUMN_NAME = 'payment_method_id'
) = 0;
SET @sql := IF(@needs_rename,
    'ALTER TABLE sales CHANGE COLUMN payment_method payment_method_id INT NULL DEFAULT NULL',
    'SELECT ''payment_method_id already exists'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Clear orphan values so the foreign keys can be created
UPDATE sales SET customer_id = NULL
WHERE customer_id IS NOT NULL AND customer_id NOT IN (SELECT id FROM customers);

UPDATE sales SET payment_method_id = NULL
WHERE payment_method_id IS NOT NULL AND payment_method_id NOT IN (SELECT id FROM payment_method);

-- 2. Indexes
SET @sql := IF((
    SELECT COUNT(*) FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales' AND INDEX_NAME = 'sales_customer_id_index'
) = 0,
    'ALTER TABLE sales ADD INDEX sales_customer_id_index (customer_id)',
    'SELECT ''sales_customer_id_index already exists'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF((
    SELECT COUNT(*) FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales' AND INDEX_NAME = 'sales_payment_method_id_index'
) = 0,
    'ALTER TABLE sales ADD INDEX sales_payment_method_id_index (payment_method_id)',
    'SELECT ''sales_payment_method_id_index already exists'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3. Foreign keys (sales.customer_id and customers.id are both signed INT)
SET @sql := IF((
    SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales'
      AND CONSTRAINT_NAME = 'sales_customer_id_foreign' AND CONSTRAINT_TYPE = 'FOREIGN KEY'
) = 0,
    'ALTER TABLE sales ADD CONSTRAINT sales_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE SET NULL',
    'SELECT ''sales_customer_id_foreign already exists'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF((
    SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales'
      AND CONSTRAINT_NAME = 'sales_payment_method_id_foreign' AND CONSTRAINT_TYPE = 'FOREIGN KEY'
) = 0,
    'ALTER TABLE sales ADD CONSTRAINT sales_payment_method_id_foreign FOREIGN KEY (payment_method_id) REFERENCES payment_method (id) ON DELETE RESTRICT',
    'SELECT ''sales_payment_method_id_foreign already exists'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Check the result
SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales'
  AND COLUMN_NAME IN ('customer_id', 'payment_method_id');
