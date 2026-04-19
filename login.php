<?php

require_once 'config/config.php';

if (isLoggedIn()) {
    redirect('modules/dashboard/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        setFlashMessage('error', 'All fields are required.');
        redirect('login.php');
    }

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, full_name, password
         FROM users
         WHERE email = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];

            setFlashMessage(
                'success',
                'Welcome back!'
            );

            redirect('modules/dashboard/dashboard.php');
        }
    }

    setFlashMessage(
        'error',
        'Invalid email or password.'
    );

    redirect('login.php');
}

$flash = getFlashMessage();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SpendWise</title>

    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/auth.css">
</head>
<body>

<div class="auth-container">

    <div class="auth-box">

        <h1>Welcome Back</h1>
        <p>Login to your SpendWise account</p>

        <?php if ($flash): ?>
            <div class="flash-message <?= $flash['type']; ?>">
                <?= htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

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

            <button type="submit">
                Login
            </button>

        </form>

        <div class="auth-footer">
            Don't have an account?
            <a href="signup.php">Create one</a>
        </div>

    </div>

</div>

</body>
</html>