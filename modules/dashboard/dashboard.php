<?php

require_once '../../includes/session_check.php';
$pageCSS = 'dashboard.css';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
require_once 'dashboard_data.php';

?>

<div class="dashboard-page">

    <?php require_once '../../includes/flash_message.php'; ?>
    <?php require_once '../../includes/alerts.php'; ?>

    <h1>Dashboard</h1>

    <div class="stats-grid">

        <div class="card">
            <h3>Total Balance</h3>
            <p><?= formatCurrency($netBalance); ?></p>
        </div>

        <div class="card">
            <h3>Monthly Income</h3>
            <p><?= formatCurrency($totalIncome); ?></p>
        </div>

        <div class="card">
            <h3>Monthly Expenses</h3>
            <p><?= formatCurrency($totalExpense); ?></p>
        </div>

        <div class="card">
            <h3>Total Budget</h3>
            <p><?= formatCurrency($totalBudget); ?></p>
        </div>

    </div>

    <div class="recent-section">

        <h2>Recent Transactions</h2>

        <table>

            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Description</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($row = mysqli_fetch_assoc($recentTransactions)): ?>

                    <tr>
                        <td><?= $row['transaction_date']; ?></td>
                        <td><?= $row['category_name']; ?></td>
                        <td><?= ucfirst($row['type']); ?></td>
                        <td><?= formatCurrency($row['amount']); ?></td>
                        <td><?= $row['description']; ?></td>
                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>