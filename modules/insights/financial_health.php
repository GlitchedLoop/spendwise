<?php

require_once '../../includes/session_check.php';
require_once '../../classes/FinancialScore.php';

$userId = $_SESSION['user_id'];

$engine = new FinancialScore($conn, $userId);
$result = $engine->calculate();

/*
Store score history
*/

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO financial_scores
    (user_id, score, remarks)
    VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "iis",
    $userId,
    $result['score'],
    $result['remarks']
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);