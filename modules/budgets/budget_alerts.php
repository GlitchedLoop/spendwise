<?php

$alertQuery = "
SELECT 
    b.*,
    c.category_name
FROM budgets b
LEFT JOIN categories c
    ON b.category_id = c.id
WHERE b.user_id = $userId
AND b.month_year = '$currentMonth'
";

$alertResult = mysqli_query($conn, $alertQuery);

while ($alert = mysqli_fetch_assoc($alertResult)) {

    $categoryId = $alert['category_id'];

    if ($categoryId) {
        $spendQuery = "
        SELECT IFNULL(SUM(amount),0) as total
        FROM transactions
        WHERE user_id = $userId
        AND category_id = $categoryId
        AND type = 'expense'
        ";
    } else {
        $spendQuery = "
        SELECT IFNULL(SUM(amount),0) as total
        FROM transactions
        WHERE user_id = $userId
        AND type = 'expense'
        ";
    }

    $spent = mysqli_fetch_assoc(
        mysqli_query($conn, $spendQuery)
    )['total'];

    $budget = $alert['monthly_budget'];

    if ($budget <= 0) continue;

    $percentage = ($spent / $budget) * 100;

    if ($percentage >= 100) {
        echo "<div class='danger-alert'>
                Budget exceeded for " .
                ($alert['category_name'] ?: 'Overall Budget') .
             "</div>";
    } elseif ($percentage >= 80) {
        echo "<div class='warning-alert'>
                Budget nearing limit for " .
                ($alert['category_name'] ?: 'Overall Budget') .
             "</div>";
    }
}
?>