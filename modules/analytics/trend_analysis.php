<?php

$userId = $_SESSION['user_id'];

$insights = [];

/*
|--------------------------------------------------------------------------
| Highest Spending Category
|--------------------------------------------------------------------------
*/

$query = "
SELECT 
    c.category_name,
    SUM(t.amount) as total_spent
FROM transactions t
JOIN categories c
    ON t.category_id = c.id
WHERE t.user_id = $userId
AND t.type = 'expense'
GROUP BY t.category_id
ORDER BY total_spent DESC
LIMIT 1
";

$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $insights[] = "Highest spending category is {$row['category_name']} with spending of " . formatCurrency($row['total_spent']);
}

/*
|--------------------------------------------------------------------------
| Savings Behavior
|--------------------------------------------------------------------------
*/

$incomeQuery = "
SELECT IFNULL(SUM(amount),0) as total_income
FROM transactions
WHERE user_id = $userId
AND type = 'income'
";

$expenseQuery = "
SELECT IFNULL(SUM(amount),0) as total_expense
FROM transactions
WHERE user_id = $userId
AND type = 'expense'
";

$totalIncome = mysqli_fetch_assoc(
    mysqli_query($conn, $incomeQuery)
)['total_income'];

$totalExpense = mysqli_fetch_assoc(
    mysqli_query($conn, $expenseQuery)
)['total_expense'];

$savings = $totalIncome - $totalExpense;

if ($savings > 0) {
    $insights[] = "You are maintaining positive savings of " . formatCurrency($savings);
} else {
    $insights[] = "Your expenses currently exceed your income.";
}

/*
|--------------------------------------------------------------------------
| Spending Spike Detection
|--------------------------------------------------------------------------
*/

$currentMonth = date('m');
$lastMonth = date('m') - 1;

$currentMonthQuery = "
SELECT IFNULL(SUM(amount),0) as total
FROM transactions
WHERE user_id = $userId
AND type = 'expense'
AND MONTH(transaction_date) = $currentMonth
";

$lastMonthQuery = "
SELECT IFNULL(SUM(amount),0) as total
FROM transactions
WHERE user_id = $userId
AND type = 'expense'
AND MONTH(transaction_date) = $lastMonth
";

$currentExpense = mysqli_fetch_assoc(
    mysqli_query($conn, $currentMonthQuery)
)['total'];

$lastExpense = mysqli_fetch_assoc(
    mysqli_query($conn, $lastMonthQuery)
)['total'];

if ($currentExpense > $lastExpense && $lastExpense > 0) {
    $increase = round((($currentExpense - $lastExpense) / $lastExpense) * 100);
    $insights[] = "Your spending increased by {$increase}% compared to last month.";
}