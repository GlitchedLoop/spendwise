<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];
$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM transactions
         WHERE id = ? AND user_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $id,
        $userId
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    setFlashMessage('success', 'Transaction deleted.');
}

redirect('modules/transactions/transactions.php');