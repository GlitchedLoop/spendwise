<?php

require_once '../config/config.php';

if (!isLoggedIn()) {
    exit(json_encode([]));
}

$userId = $_SESSION['user_id'];
$type = $_GET['type'] ?? '';

header('Content-Type: application/json');

/*
|--------------------------------------------------------------------------
| Income vs Expense Monthly Chart
|--------------------------------------------------------------------------
*/

if ($type === 'income_expense') {

    $labels = [];
    $incomeData = [];
    $expenseData = [];

    for ($month = 1; $month <= 12; $month++) {

        $labels[] = date("M", mktime(0, 0, 0, $month, 10));

        $incomeQuery = "
        SELECT IFNULL(SUM(amount),0) as total
        FROM transactions
        WHERE user_id = $userId
        AND type = 'income'
        AND MONTH(transaction_date) = $month
        ";

        $expenseQuery = "
        SELECT IFNULL(SUM(amount),0) as total
        FROM transactions
        WHERE user_id = $userId
        AND type = 'expense'
        AND MONTH(transaction_date) = $month
        ";

        $incomeData[] = mysqli_fetch_assoc(
            mysqli_query($conn, $incomeQuery)
        )['total'];

        $expenseData[] = mysqli_fetch_assoc(
            mysqli_query($conn, $expenseQuery)
        )['total'];
    }

    echo json_encode([
        'labels' => $labels,
        'income' => $incomeData,
        'expense' => $expenseData
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Expense Category Pie Chart
|--------------------------------------------------------------------------
*/

if ($type === 'category_pie') {

    $labels = [];
    $values = [];

    $query = "
    SELECT 
        c.category_name,
        SUM(t.amount) as total
    FROM transactions t
    JOIN categories c
        ON t.category_id = c.id
    WHERE t.user_id = $userId
    AND t.type = 'expense'
    GROUP BY t.category_id
    ORDER BY total DESC
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = $row['category_name'];
        $values[] = $row['total'];
    }

    echo json_encode([
        'labels' => $labels,
        'values' => $values
    ]);

    exit;
}

echo json_encode([]);