<?php

function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

function redirect($path)
{
    header("Location: " . BASE_URL . $path);
    exit();
}

function setFlashMessage($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }

    return null;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function formatCurrency($amount, $currency = DEFAULT_CURRENCY)
{
    return $currency . number_format($amount, 2);
}

function currentMonthYear()
{
    return date('F Y');
}

function currentDate()
{
    return date('Y-m-d');
}

function uploadFileValid($file)
{
    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, ALLOWED_FILE_TYPES)) {
        return false;
    }

    return true;
}

function generateDefaultCategories($conn, $userId)
{
    $defaultCategories = [
        ['Food', 'expense'],
        ['Transport', 'expense'],
        ['Utilities', 'expense'],
        ['Entertainment', 'expense'],
        ['Bills', 'expense'],
        ['Salary', 'income'],
        ['Shopping', 'expense'],
        ['Health', 'expense']
    ];

    foreach ($defaultCategories as $category) {
        $name = $category[0];
        $type = $category[1];

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO categories (user_id, category_name, category_type, is_default)
             VALUES (?, ?, ?, 1)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iss",
            $userId,
            $name,
            $type
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

?>