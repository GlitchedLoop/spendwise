<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('modules/profile/profile.php');
}

$fullName = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);

if (empty($fullName) || empty($email)) {
    setFlashMessage('error', 'All fields are required.');
    redirect('modules/profile/profile.php');
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE users
     SET full_name = ?, email = ?
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssi",
    $fullName,
    $email,
    $userId
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$_SESSION['full_name'] = $fullName;

setFlashMessage('success', 'Profile updated successfully.');

redirect('modules/profile/profile.php');