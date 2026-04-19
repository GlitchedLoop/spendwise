<?php

require_once '../../includes/session_check.php';
$pageCSS = 'transactions.css';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$userId = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Base WHERE Clause
|--------------------------------------------------------------------------
| All transaction data must always be user-specific.
| Never allow cross-user access.
|
*/

$where = " WHERE t.user_id = $userId ";

/*
|--------------------------------------------------------------------------
| Filters + Search Logic
|--------------------------------------------------------------------------
*/

require_once 'transaction_filters.php';
require_once 'transaction_search.php';

/*
|--------------------------------------------------------------------------
| Fetch Transactions
|--------------------------------------------------------------------------
*/

$query = "
SELECT 
    t.*,
    c.category_name
FROM transactions t
JOIN categories c
    ON t.category_id = c.id
$where
ORDER BY t.transaction_date DESC, t.created_at DESC
";

$result = mysqli_query($conn, $query);

/*
|--------------------------------------------------------------------------
| Fetch Categories for Filter Dropdown
|--------------------------------------------------------------------------
*/

$categoryQuery = "
SELECT 
    id,
    category_name
FROM categories
WHERE user_id = $userId
ORDER BY category_name ASC
";

$categories = mysqli_query($conn, $categoryQuery);

?>

<div class="transactions-page">

    <h1>Transactions</h1>

    <?php require_once '../../includes/flash_message.php'; ?>

    <!-- Top Action Button -->
    <div class="top-actions">
        <a href="add_transaction.php" class="btn-primary">
            + Add Transaction
        </a>
    </div>

    <!-- Filter + Search Form -->
    <form method="GET" class="filter-form">

        <!-- Search -->
        <input
            type="text"
            name="search"
            placeholder="Search by description, category, payment method..."
            value="<?= htmlspecialchars($search ?? ''); ?>"
        >

        <!-- Type Filter -->
        <select name="type">
            <option value="">All Types</option>

            <option
                value="income"
                <?= (($filterType ?? '') == 'income') ? 'selected' : ''; ?>
            >
                Income
            </option>

            <option
                value="expense"
                <?= (($filterType ?? '') == 'expense') ? 'selected' : ''; ?>
            >
                Expense
            </option>
        </select>

        <!-- Category Filter -->
        <select name="category">
            <option value="">All Categories</option>

            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option
                    value="<?= $cat['id']; ?>"
                    <?= (($filterCategory ?? '') == $cat['id']) ? 'selected' : ''; ?>
                >
                    <?= htmlspecialchars($cat['category_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Date Filter -->
        <input
            type="date"
            name="date"
            value="<?= htmlspecialchars($filterDate ?? ''); ?>"
        >

        <!-- Submit -->
        <button type="submit">
            Apply Filters
        </button>

        <!-- Reset -->
        <a href="transactions.php" class="btn-secondary">
            Reset
        </a>

    </form>

    <!-- Transactions Table -->
    <table class="transaction-table">

        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Type</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Recurring</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($row['transaction_date']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['category_name']); ?>
                        </td>

                        <td>
                            <?= ucfirst(htmlspecialchars($row['type'])); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['payment_method']); ?>
                        </td>

                        <td>
                            <?= formatCurrency($row['amount']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['description']); ?>
                        </td>

                        <td>
                            <?= $row['is_recurring'] ? 'Yes' : 'No'; ?>
                        </td>

                        <td>
                            <a href="edit_transaction.php?id=<?= $row['id']; ?>">
                                Edit
                            </a>

                            |

                            <a
                                href="delete_transaction.php?id=<?= $row['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this transaction?');"
                            >
                                Delete
                            </a>
                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="8" style="text-align: center;">
                        No transactions found.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?php require_once '../../includes/footer.php'; ?>