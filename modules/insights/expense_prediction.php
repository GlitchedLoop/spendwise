<?php

require_once '../../includes/session_check.php';
require_once '../../classes/PredictionEngine.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$userId = $_SESSION['user_id'];

$engine = new PredictionEngine($conn, $userId);
$predictedExpense = $engine->predictNextMonthExpense();

$nextMonth = date('F Y', strtotime('+1 month'));

/*
|--------------------------------------------------------------------------
| Store Prediction
|--------------------------------------------------------------------------
*/

$checkQuery = "
SELECT id
FROM predictions
WHERE user_id = $userId
AND predicted_month = '$nextMonth'
LIMIT 1
";

$exists = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($exists) == 0) {

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO predictions
        (user_id, predicted_month, predicted_expense)
        VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "isd",
        $userId,
        $nextMonth,
        $predictedExpense
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

?>

<div class="prediction-page">

    <h1>Expense Prediction</h1>

    <div class="prediction-card">

        <h2>Next Month Forecast</h2>

        <p>
            Predicted Expense for
            <strong><?= $nextMonth; ?></strong>
        </p>

        <div class="prediction-amount">
            <?= formatCurrency($predictedExpense); ?>
        </div>

        <p>
            Based on the moving average of your last
            3 months of expenses.
        </p>

    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>