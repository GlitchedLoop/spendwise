<?php

function isDuplicateTransaction($conn, $userId, $amount, $date, $description)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id
         FROM transactions
         WHERE user_id = ?
         AND amount = ?
         AND transaction_date = ?
         AND description = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "idss",
        $userId,
        $amount,
        $date,
        $description
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $isDuplicate = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);

    return $isDuplicate;
}