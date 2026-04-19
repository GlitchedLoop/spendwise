<?php

require_once '../../includes/session_check.php';
require_once '../../classes/InsightEngine.php';

$userId = $_SESSION['user_id'];

$engine = new InsightEngine($conn, $userId);
$insights = $engine->generate();

/*
Store insights
*/

foreach ($insights as $item) {

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO insights
        (user_id, title, message, insight_type)
        VALUES (?, ?, ?, 'system')"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $userId,
        $item['title'],
        $item['message']
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}