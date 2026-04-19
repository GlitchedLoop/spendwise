<?php

require_once '../../includes/session_check.php';
require_once '../../classes/SubscriptionDetector.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$userId = $_SESSION['user_id'];

$detector = new SubscriptionDetector($conn, $userId);
$subscriptions = $detector->detect();

/*
|--------------------------------------------------------------------------
| Clear old detected subscriptions
|--------------------------------------------------------------------------
*/

$deleteQuery = "
DELETE FROM subscriptions
WHERE user_id = $userId
";

mysqli_query($conn, $deleteQuery);

/*
|--------------------------------------------------------------------------
| Store fresh detected subscriptions
|--------------------------------------------------------------------------
*/

foreach ($subscriptions as $sub) {

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO subscriptions
        (user_id, description, amount, frequency, last_detected)
        VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "isdss",
        $userId,
        $sub['description'],
        $sub['amount'],
        $sub['frequency'],
        $sub['last_detected']
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

?>

<div class="subscription-page">

    <h1>Subscription Detection</h1>

    <div class="subscription-card">

        <h2>Detected Recurring Payments</h2>

        <?php if (!empty($subscriptions)): ?>

            <table>

                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Frequency</th>
                        <th>Last Detected</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($subscriptions as $sub): ?>

                        <tr>
                            <td><?= htmlspecialchars($sub['description']); ?></td>
                            <td><?= formatCurrency($sub['amount']); ?></td>
                            <td><?= ucfirst($sub['frequency']); ?></td>
                            <td><?= $sub['last_detected']; ?></td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p>No recurring subscriptions detected yet.</p>

        <?php endif; ?>

    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>