<?php

require_once '../../includes/session_check.php';
$pageCSS = 'budgets.css';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$userId = $_SESSION['user_id'];
$currentMonth = date('F Y');

/*
|--------------------------------------------------------------------------
| Fetch Budgets
|--------------------------------------------------------------------------
*/

$query = "
SELECT 
    b.*,
    c.category_name
FROM budgets b
LEFT JOIN categories c
    ON b.category_id = c.id
WHERE b.user_id = $userId
AND b.month_year = '$currentMonth'
ORDER BY b.created_at DESC
";

$result = mysqli_query($conn, $query);

?>

<div class="budgets-page">

    <h1>Budget Tracking</h1>

    <?php require_once '../../includes/flash_message.php'; ?>
    <?php require_once 'budget_alerts.php'; ?>

    <div class="top-actions">
        <a href="set_budget.php" class="btn-primary">
            + Set Budget
        </a>
    </div>

    <table class="budget-table">

        <thead>
            <tr>
                <th>Category</th>
                <th>Monthly Budget</th>
                <th>Current Spend</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <?php

                    $categoryId = $row['category_id'];

                    if ($categoryId) {
                        $spendQuery = "
                        SELECT IFNULL(SUM(amount),0) as total
                        FROM transactions
                        WHERE user_id = $userId
                        AND category_id = $categoryId
                        AND type = 'expense'
                        AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
                        ";

                    } else {
                        $spendQuery = "
                        SELECT IFNULL(SUM(amount),0) as total
                        FROM transactions
                        WHERE user_id = $userId
                        AND type = 'expense'
                        AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
                        ";
                    }

                    $spent = mysqli_fetch_assoc(
                        mysqli_query($conn, $spendQuery)
                    )['total'];

                    $budget = $row['monthly_budget'];

                    $percentage = $budget > 0
                        ? min(($spent / $budget) * 100, 100)
                        : 0;

                    if ($spent > $budget) {
                        $status = 'Exceeded';
                    } elseif ($percentage >= 80) {
                        $status = 'Near Limit';
                    } else {
                        $status = 'Safe';
                    }

                    ?>

                    <tr>

                        <td>
                            <?= $row['category_name'] ?: 'Overall Budget'; ?>
                        </td>

                        <td>
                            <?= formatCurrency($budget); ?>
                        </td>

                        <td>
                            <?= formatCurrency($spent); ?>
                        </td>

                        <td>
                            <?= $status; ?>
                        </td>

                        <td>
                            <?= round($percentage); ?>%
                        </td>

                        <td>
                            <a href="update_budget.php?id=<?= $row['id']; ?>">
                                Edit
                            </a>
                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" style="text-align:center;">
                        No budgets set for this month.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?php require_once '../../includes/footer.php'; ?>