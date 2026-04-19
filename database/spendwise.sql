CREATE DATABASE IF NOT EXISTS spendwise;
USE spendwise;

-- =====================================================
-- 1. USERS
-- =====================================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- 2. CATEGORIES
-- =====================================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    category_type ENUM('income', 'expense') DEFAULT 'expense',
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =====================================================
-- 3. TRANSACTIONS
-- =====================================================

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,

    amount DECIMAL(12,2) NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'UPI',
    transaction_date DATE NOT NULL,
    description VARCHAR(255) NOT NULL,

    is_recurring TINYINT(1) DEFAULT 0,
    source ENUM('manual', 'csv') DEFAULT 'manual',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY (category_id)
    REFERENCES categories(id)
    ON DELETE CASCADE
);

-- =====================================================
-- 4. BUDGETS
-- =====================================================

CREATE TABLE budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NULL,

    monthly_budget DECIMAL(12,2) NOT NULL,
    month_year VARCHAR(20) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY (category_id)
    REFERENCES categories(id)
    ON DELETE SET NULL
);

-- =====================================================
-- 5. SETTINGS
-- =====================================================

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,

    currency VARCHAR(20) DEFAULT '₹',
    dark_mode TINYINT(1) DEFAULT 0,
    notifications_enabled TINYINT(1) DEFAULT 1,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =====================================================
-- 6. CSV IMPORT LOGS
-- =====================================================

CREATE TABLE csv_import_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    file_name VARCHAR(255) NOT NULL,
    total_rows INT DEFAULT 0,
    imported_rows INT DEFAULT 0,
    skipped_rows INT DEFAULT 0,
    failed_rows INT DEFAULT 0,

    imported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =====================================================
-- 7. FINANCIAL SCORES
-- =====================================================

CREATE TABLE financial_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    score INT NOT NULL,
    remarks VARCHAR(255),

    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =====================================================
-- 8. INSIGHTS
-- =====================================================

CREATE TABLE insights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    insight_type VARCHAR(100),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =====================================================
-- 9. SUBSCRIPTIONS
-- =====================================================

CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    description VARCHAR(255) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    frequency VARCHAR(50) DEFAULT 'monthly',
    last_detected DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =====================================================
-- 10. PREDICTIONS
-- =====================================================

CREATE TABLE predictions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    predicted_month VARCHAR(50),
    predicted_expense DECIMAL(12,2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =====================================================
-- 11. NOTIFICATIONS
-- =====================================================

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =====================================================
-- DEFAULT CATEGORIES (will be inserted after signup)
-- =====================================================