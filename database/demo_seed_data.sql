
USE spendwise;

-- =====================================================
-- Demo User
-- =====================================================

INSERT INTO users (id, full_name, email, password, created_at)
VALUES (
    1,
    'Demo User',
    'demo@spendwise.com',
    '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghijklmn',
    NOW()
)
ON DUPLICATE KEY UPDATE
full_name = 'Demo User';

-- =====================================================
-- SETTINGS
-- =====================================================

INSERT INTO settings (
    user_id,
    currency,
    dark_mode,
    notifications_enabled
)
VALUES (
    1,
    '₹',
    1,
    1
)
ON DUPLICATE KEY UPDATE
currency = '₹';

-- =====================================================
-- CATEGORIES
-- =====================================================

INSERT INTO categories (
    user_id,
    category_name,
    category_type,
    is_default,
    created_at
)
VALUES
(1, 'Salary', 'income', 1, NOW()),
(1, 'Freelance', 'income', 1, NOW()),
(1, 'Food', 'expense', 1, NOW()),
(1, 'Transport', 'expense', 1, NOW()),
(1, 'Shopping', 'expense', 1, NOW()),
(1, 'Bills', 'expense', 1, NOW()),
(1, 'Entertainment', 'expense', 1, NOW()),
(1, 'Health', 'expense', 1, NOW())
ON DUPLICATE KEY UPDATE
category_name = VALUES(category_name);

-- =====================================================
-- OVERALL BUDGET ONLY
-- =====================================================

INSERT INTO budgets (
    user_id,
    category_id,
    monthly_budget,
    month_year,
    created_at
)
SELECT
    1,
    NULL,
    30000,
    DATE_FORMAT(CURDATE(), '%M %Y'),
    NOW()
WHERE NOT EXISTS (
    SELECT 1
    FROM budgets
    WHERE user_id = 1
    AND category_id IS NULL
    AND month_year = DATE_FORMAT(CURDATE(), '%M %Y')
);

-- =====================================================
-- TRANSACTIONS
-- Fixed:
-- Salary now belongs to current month
-- Monthly income becomes realistic
-- =====================================================

INSERT INTO transactions (
    user_id,
    category_id,
    amount,
    type,
    payment_method,
    transaction_date,
    description,
    is_recurring,
    source,
    created_at
)
VALUES

-- =====================================================
-- INCOME
-- =====================================================

(
    1,
    (SELECT id FROM categories
     WHERE user_id = 1
     AND category_name = 'Salary'
     LIMIT 1),
    50000,
    'income',
    'Bank Transfer',
    CURDATE() - INTERVAL 15 DAY,
    'Monthly Salary',
    1,
    'manual',
    NOW()
),

(
    1,
    (SELECT id FROM categories
     WHERE user_id = 1
     AND category_name = 'Freelance'
     LIMIT 1),
    12000,
    'income',
    'UPI',
    CURDATE() - INTERVAL 12 DAY,
    'Freelance Project Payment',
    0,
    'manual',
    NOW()
),

-- =====================================================
-- EXPENSES
-- =====================================================

(
    1,
    (SELECT id FROM categories
     WHERE user_id = 1
     AND category_name = 'Food'
     LIMIT 1),
    850,
    'expense',
    'UPI',
    CURDATE() - INTERVAL 10 DAY,
    'Zomato Order',
    0,
    'manual',
    NOW()
),

(
    1,
    (SELECT id FROM categories
     WHERE user_id = 1
     AND category_name = 'Transport'
     LIMIT 1),
    420,
    'expense',
    'Card',
    CURDATE() - INTERVAL 9 DAY,
    'Uber Ride',
    0,
    'manual',
    NOW()
),

(
    1,
    (SELECT id FROM categories
     WHERE user_id = 1
     AND category_name = 'Bills'
     LIMIT 1),
    2500,
    'expense',
    'Net Banking',
    CURDATE() - INTERVAL 8 DAY,
    'Electricity Bill',
    1,
    'manual',
    NOW()
),

(
    1,
    (SELECT id FROM categories
     WHERE user_id = 1
     AND category_name = 'Entertainment'
     LIMIT 1),
    649,
    'expense',
    'Card',
    CURDATE() - INTERVAL 7 DAY,
    'Netflix Subscription',
    1,
    'manual',
    NOW()
),

(
    1,
    (SELECT id FROM categories
     WHERE user_id = 1
     AND category_name = 'Shopping'
     LIMIT 1),
    3200,
    'expense',
    'UPI',
    CURDATE() - INTERVAL 5 DAY,
    'Amazon Shopping',
    0,
    'manual',
    NOW()
),

(
    1,
    (SELECT id FROM categories
     WHERE user_id = 1
     AND category_name = 'Health'
     LIMIT 1),
    1100,
    'expense',
    'Cash',
    CURDATE() - INTERVAL 3 DAY,
    'Medical Store',
    0,
    'manual',
    NOW()
);

-- =====================================================
-- INSIGHTS
-- =====================================================

INSERT INTO insights (
    user_id,
    title,
    message,
    insight_type,
    created_at
)
VALUES
(
    1,
    'Budget Alert',
    'Food expenses are nearing your monthly limit.',
    'system',
    NOW()
),
(
    1,
    'Savings Insight',
    'You maintained strong positive savings this month.',
    'system',
    NOW()
)
ON DUPLICATE KEY UPDATE
title = VALUES(title);

-- =====================================================
-- PREDICTION
-- =====================================================

INSERT INTO predictions (
    user_id,
    predicted_month,
    predicted_expense,
    created_at
)
VALUES (
    1,
    DATE_FORMAT(
        DATE_ADD(CURDATE(), INTERVAL 1 MONTH),
        '%M %Y'
    ),
    18500,
    NOW()
)
ON DUPLICATE KEY UPDATE
predicted_expense = 18500;

-- =====================================================
-- SUBSCRIPTIONS
-- =====================================================

INSERT INTO subscriptions (
    user_id,
    description,
    amount,
    frequency,
    last_detected,
    created_at
)
VALUES
(
    1,
    'Netflix Subscription',
    649,
    'monthly',
    CURDATE(),
    NOW()
),
(
    1,
    'Spotify Premium',
    119,
    'monthly',
    CURDATE(),
    NOW()
)
ON DUPLICATE KEY UPDATE
description = VALUES(description);