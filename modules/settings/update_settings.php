<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('modules/settings/settings.php');
}

$currency = sanitize($_POST['currency']);
$darkMode = isset($_POST['dark_mode']) ? 1 : 0;
$notifications = isset($_POST['notifications_enabled']) ? 1 : 0;

$stmt = mysqli_prepare(
    $conn,
    "UPDATE settings
     SET currency = ?, dark_mode = ?, notifications_enabled = ?
     WHERE user_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "siii",
    $currency,
    $darkMode,
    $notifications,
    $userId
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

setFlashMessage('success', 'Settings updated successfully.');

redirect('modules/settings/settings.php');