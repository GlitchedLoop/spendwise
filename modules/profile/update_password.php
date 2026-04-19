<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('modules/profile/profile.php');
}

$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

if (
    empty($currentPassword) ||
    empty($newPassword) ||
    empty($confirmPassword)
) {
    setFlashMessage('error', 'All password fields are required.');
    redirect('modules/profile/profile.php');
}

if ($newPassword !== $confirmPassword) {
    setFlashMessage('error', 'New passwords do not match.');
    redirect('modules/profile/profile.php');
}

$query = "
SELECT password
FROM users
WHERE id = $userId
LIMIT 1
";

$user = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$user || !password_verify($currentPassword, $user['password'])) {
    setFlashMessage('error', 'Current password is incorrect.');
    redirect('modules/profile/profile.php');
}

$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = mysqli_prepare(
    $conn,
    "UPDATE users
     SET password = ?
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $newHashedPassword,
    $userId
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

setFlashMessage('success', 'Password updated successfully.');

redirect('modules/profile/profile.php');