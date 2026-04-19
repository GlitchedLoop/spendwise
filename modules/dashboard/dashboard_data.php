<?php

$userId = $_SESSION['user_id'];

$incomeQuery = "
SELECT IFNULL(SUM(amount), 0) AS total_income
FROM transactions
WHERE user_id = $userId
AND type = 'income'
AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
";

$expenseQuery = "
SELECT IFNULL(SUM(amount), 0) AS total_expense
FROM transactions
WHERE user_id = $userId
AND type = 'expense'
AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
";

$budgetQuery = "
SELECT IFNULL(SUM(monthly_budget), 0) AS total_budget
FROM budgets
WHERE user_id = $userId
";

$recentQuery = "
SELECT t.*, c.category_name
FROM transactions t
JOIN categories c ON t.category_id = c.id
WHERE t.user_id = $userId
ORDER BY t.transaction_date DESC
LIMIT 5
";

$totalIncome = mysqli_fetch_assoc(mysqli_query($conn, $incomeQuery))['total_income'];
$totalExpense = mysqli_fetch_assoc(mysqli_query($conn, $expenseQuery))['total_expense'];
$totalBudget = mysqli_fetch_assoc(mysqli_query($conn, $budgetQuery))['total_budget'];

$recentTransactions = mysqli_query($conn, $recentQuery);

$netBalance = $totalIncome - $totalExpense;