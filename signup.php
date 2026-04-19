<?php

require_once 'config/config.php';

if (isLoggedIn()) {
    redirect('modules/dashboard/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (
        empty($fullName) ||
        empty($email) ||
        empty($password) ||
        empty($confirmPassword)
    ) {
        setFlashMessage('error', 'All fields are required.');
        redirect('signup.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlashMessage('error', 'Invalid email format.');
        redirect('signup.php');
    }

    if (strlen($password) < 6) {
        setFlashMessage('error', 'Password must be at least 6 characters.');
        redirect('signup.php');
    }

    if ($password !== $confirmPassword) {
        setFlashMessage('error', 'Passwords do not match.');
        redirect('signup.php');
    }

    $checkStmt = mysqli_prepare(
        $conn,
        "SELECT id FROM users WHERE email = ?"
    );

    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        setFlashMessage('error', 'Email already exists.');
        redirect('signup.php');
    }

    mysqli_stmt_close($checkStmt);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertStmt = mysqli_prepare(
        $conn,
        "INSERT INTO users (full_name, email, password)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $insertStmt,
        "sss",
        $fullName,
        $email,
        $hashedPassword
    );

    if (mysqli_stmt_execute($insertStmt)) {

        $userId = mysqli_insert_id($conn);

        $settingsStmt = mysqli_prepare(
            $conn,
            "INSERT INTO settings (user_id, currency, dark_mode)
             VALUES (?, '₹', 0)"
        );

        mysqli_stmt_bind_param($settingsStmt, "i", $userId);
        mysqli_stmt_execute($settingsStmt);
        mysqli_stmt_close($settingsStmt);

        generateDefaultCategories($conn, $userId);

        setFlashMessage(
            'success',
            'Account created successfully. Please login.'
        );

        redirect('login.php');
    } else {
        setFlashMessage('error', 'Registration failed.');
        redirect('signup.php');
    }

    mysqli_stmt_close($insertStmt);
}

$flash = getFlashMessage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup - SpendWise</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">
        <h1>Create Account</h1>
        <p>Start managing your finances smarter</p>

        <?php if ($flash): ?>
            <div class="flash <?= $flash['type']; ?>">
                <?= $flash['message']; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <input
                type="text"
                name="full_name"
                placeholder="Full Name"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirm Password"
                required
            >

            <button type="submit">
                Sign Up
            </button>

        </form>

        <p>
            Already have an account?
            <a href="login.php">Login</a>
        </p>
    </div>
</div>

</body>
</html>