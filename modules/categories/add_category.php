<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoryName = sanitize($_POST['category_name']);
    $categoryType = sanitize($_POST['category_type']);

    if (empty($categoryName) || empty($categoryType)) {
        setFlashMessage('error', 'All fields are required.');
        redirect('modules/categories/add_category.php');
    }

    $checkStmt = mysqli_prepare(
        $conn,
        "SELECT id
         FROM categories
         WHERE user_id = ?
         AND category_name = ?"
    );

    mysqli_stmt_bind_param(
        $checkStmt,
        "is",
        $userId,
        $categoryName
    );

    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        setFlashMessage('error', 'Category already exists.');
        redirect('modules/categories/add_category.php');
    }

    mysqli_stmt_close($checkStmt);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO categories
        (user_id, category_name, category_type, is_default)
        VALUES (?, ?, ?, 0)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $userId,
        $categoryName,
        $categoryType
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    setFlashMessage('success', 'Category added successfully.');
    redirect('modules/categories/categories.php');
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>

<div class="form-page">

    <h1>Add Category</h1>

    <form method="POST" class="main-form">

        <input
            type="text"
            name="category_name"
            placeholder="Category Name"
            required
        >

        <select name="category_type" required>
            <option value="">Select Type</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>

        <button type="submit">
            Save Category
        </button>

    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>