<?php

require_once '../../includes/session_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_SESSION['user_id'];
    $amount = sanitize($_POST['amount']);
    $type = sanitize($_POST['type']);
    $categoryId = (int) $_POST['category_id'];
    $date = sanitize($_POST['transaction_date']);
    $description = sanitize($_POST['description']);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO transactions
        (user_id, category_id, amount, type, transaction_date, description)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iidsss",
        $userId,
        $categoryId,
        $amount,
        $type,
        $date,
        $description
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    setFlashMessage('success', 'Transaction added successfully.');
    redirect('modules/dashboard/dashboard.php');
}