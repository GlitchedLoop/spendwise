<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];
$currentMonth = date('F Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoryId = !empty($_POST['category_id'])
        ? (int) $_POST['category_id']
        : null;

    $monthlyBudget = sanitize($_POST['monthly_budget']);

    if (empty($monthlyBudget)) {
        setFlashMessage('error', 'Budget amount is required.');
        redirect('modules/budgets/set_budget.php');
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO budgets
        (user_id, category_id, monthly_budget, month_year)
        VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iids",
        $userId,
        $categoryId,
        $monthlyBudget,
        $currentMonth
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    setFlashMessage('success', 'Budget created successfully.');
    redirect('modules/budgets/budgets.php');
}

$categoryQuery = "
SELECT id, category_name
FROM categories
WHERE user_id = $userId
AND category_type = 'expense'
ORDER BY category_name ASC
";

$categories = mysqli_query($conn, $categoryQuery);

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>

<div class="form-page">

    <h1>Set Monthly Budget</h1>

    <form method="POST" class="main-form">

        <select name="category_id">
            <option value="">Overall Budget</option>

            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option value="<?= $cat['id']; ?>">
                    <?= htmlspecialchars($cat['category_name']); ?>
                </option>
            <?php endwhile; ?>

        </select>

        <input
            type="number"
            step="0.01"
            name="monthly_budget"
            placeholder="Monthly Budget Amount"
            required
        >

        <button type="submit">
            Save Budget
        </button>

    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>