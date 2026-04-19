<?php

require_once '../../includes/session_check.php';
require_once 'duplicate_check.php';

$userId = $_SESSION['user_id'];
$rows = $_SESSION['csv_preview'] ?? [];

if (empty($rows)) {
    redirect('modules/csv_import/upload_csv.php');
}

$total = count($rows);
$imported = 0;
$skipped = 0;
$failed = 0;

foreach ($rows as $row) {

    if (
        isDuplicateTransaction(
            $conn,
            $userId,
            $row['amount'],
            $row['date'],
            $row['description']
        )
    ) {
        $skipped++;
        continue;
    }

    $categoryQuery = "
    SELECT id
    FROM categories
    WHERE user_id = $userId
    AND category_name = '{$row['category']}'
    LIMIT 1
    ";

    $categoryResult = mysqli_query($conn, $categoryQuery);
    $category = mysqli_fetch_assoc($categoryResult);

    if (!$category) {
        $failed++;
        continue;
    }

    $categoryId = $category['id'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO transactions
        (user_id, category_id, amount, type,
         transaction_date, description, source)
        VALUES (?, ?, ?, ?, ?, ?, 'csv')"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iidsss",
        $userId,
        $categoryId,
        $row['amount'],
        $row['type'],
        $row['date'],
        $row['description']
    );

    if (mysqli_stmt_execute($stmt)) {
        $imported++;
    } else {
        $failed++;
    }

    mysqli_stmt_close($stmt);
}

$_SESSION['import_summary'] = [
    'total' => $total,
    'imported' => $imported,
    'skipped' => $skipped,
    'failed' => $failed
];

unset($_SESSION['csv_preview']);

redirect('modules/csv_import/import_summary.php');