<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    redirect('modules/transactions/transactions.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $amount = sanitize($_POST['amount']);
    $type = sanitize($_POST['type']);
    $categoryId = (int) $_POST['category_id'];
    $paymentMethod = sanitize($_POST['payment_method']);
    $transactionDate = sanitize($_POST['transaction_date']);
    $description = sanitize($_POST['description']);
    $isRecurring = isset($_POST['is_recurring']) ? 1 : 0;

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE transactions
        SET category_id = ?, amount = ?, type = ?, payment_method = ?,
            transaction_date = ?, description = ?, is_recurring = ?
        WHERE id = ? AND user_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "idssssiii",
        $categoryId,
        $amount,
        $type,
        $paymentMethod,
        $transactionDate,
        $description,
        $isRecurring,
        $id,
        $userId
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    setFlashMessage('success', 'Transaction updated.');
    redirect('modules/transactions/transactions.php');
}

$query = "
SELECT *
FROM transactions
WHERE id = $id
AND user_id = $userId
LIMIT 1
";

$transaction = mysqli_fetch_assoc(mysqli_query($conn, $query));

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

    <h1>Edit Transaction</h1>

    <form method="POST" class="main-form">

        <input
            type="number"
            step="0.01"
            name="amount"
            value="<?= $transaction['amount']; ?>"
            required
        >

        <select name="type" required>
            <option value="income" <?= $transaction['type'] == 'income' ? 'selected' : ''; ?>>
                Income
            </option>
            <option value="expense" <?= $transaction['type'] == 'expense' ? 'selected' : ''; ?>>
                Expense
            </option>
        </select>

        <select name="category_id" required>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option
                    value="<?= $cat['id']; ?>"
                    <?= $transaction['category_id'] == $cat['id'] ? 'selected' : ''; ?>
                >
                    <?= $cat['category_name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input
            type="text"
            name="payment_method"
            value="<?= $transaction['payment_method']; ?>"
        >

        <input
            type="date"
            name="transaction_date"
            value="<?= $transaction['transaction_date']; ?>"
            required
        >

        <textarea name="description"><?= $transaction['description']; ?></textarea>

        <label>
            <input
                type="checkbox"
                name="is_recurring"
                <?= $transaction['is_recurring'] ? 'checked' : ''; ?>
            >
            Recurring Payment
        </label>

        <button type="submit">Update Transaction</button>

    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>