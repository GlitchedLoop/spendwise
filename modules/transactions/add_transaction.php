<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $amount = sanitize($_POST['amount']);
    $type = sanitize($_POST['type']);
    $categoryId = (int) $_POST['category_id'];
    $paymentMethod = sanitize($_POST['payment_method']);
    $transactionDate = sanitize($_POST['transaction_date']);
    $description = sanitize($_POST['description']);
    $isRecurring = isset($_POST['is_recurring']) ? 1 : 0;

    if (
        empty($amount) ||
        empty($type) ||
        empty($categoryId) ||
        empty($transactionDate)
    ) {
        setFlashMessage('error', 'Required fields are missing.');
        redirect('modules/transactions/add_transaction.php');
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO transactions
        (user_id, category_id, amount, type, payment_method,
         transaction_date, description, is_recurring)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iidssssi",
        $userId,
        $categoryId,
        $amount,
        $type,
        $paymentMethod,
        $transactionDate,
        $description,
        $isRecurring
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    setFlashMessage('success', 'Transaction added successfully.');
    redirect('modules/transactions/transactions.php');
}

$categoryQuery = "
SELECT id, category_name
FROM categories
WHERE user_id = $userId
ORDER BY category_name ASC
";

$categories = mysqli_query($conn, $categoryQuery);

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>

<div class="form-page">

    <h1>Add Transaction</h1>

    <form method="POST" class="main-form">

        <input type="number" step="0.01" name="amount" placeholder="Amount" required>

        <select name="type" required>
            <option value="">Select Type</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>

        <select name="category_id" required>
            <option value="">Select Category</option>

            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option value="<?= $cat['id']; ?>">
                    <?= $cat['category_name']; ?>
                </option>
            <?php endwhile; ?>

        </select>

        <input type="text" name="payment_method" placeholder="Payment Method">

        <input type="date" name="transaction_date" required>

        <textarea name="description" placeholder="Description"></textarea>

        <label>
            <input type="checkbox" name="is_recurring">
            Recurring Payment
        </label>

        <button type="submit">Save Transaction</button>

    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>