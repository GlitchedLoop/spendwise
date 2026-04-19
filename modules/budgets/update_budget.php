<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    redirect('modules/budgets/budgets.php');
}

$query = "
SELECT *
FROM budgets
WHERE id = $id
AND user_id = $userId
LIMIT 1
";

$budget = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$budget) {
    redirect('modules/budgets/budgets.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $monthlyBudget = sanitize($_POST['monthly_budget']);

    if (empty($monthlyBudget)) {
        setFlashMessage('error', 'Budget amount required.');
        redirect("modules/budgets/update_budget.php?id=$id");
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE budgets
         SET monthly_budget = ?
         WHERE id = ? AND user_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "dii",
        $monthlyBudget,
        $id,
        $userId
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    setFlashMessage('success', 'Budget updated.');
    redirect('modules/budgets/budgets.php');
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>

<div class="form-page">

    <h1>Update Budget</h1>

    <form method="POST" class="main-form">

        <input
            type="number"
            step="0.01"
            name="monthly_budget"
            value="<?= $budget['monthly_budget']; ?>"
            required
        >

        <button type="submit">
            Update Budget
        </button>

    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>