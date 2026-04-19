<?php

define('SITE_NAME', 'SpendWise');

define('BASE_URL', 'http://localhost/spendwise/');

define('DB_HOST', 'localhost');
define('DB_NAME', 'spendwise');
define('DB_USER', 'root');
define('DB_PASS', '');

define('DEFAULT_CURRENCY', '₹');

define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/csv/');

define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

define('ALLOWED_FILE_TYPES', ['csv']);

define('SESSION_TIMEOUT', 3600); // 1 hour

date_default_timezone_set('Asia/Kolkata');
?>