<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    redirect('modules/categories/categories.php');
}

$query = "
SELECT *
FROM categories
WHERE id = $id
AND user_id = $userId
LIMIT 1
";

$category = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$category) {
    redirect('modules/categories/categories.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoryName = sanitize($_POST['category_name']);
    $categoryType = sanitize($_POST['category_type']);

    if (empty($categoryName) || empty($categoryType)) {
        setFlashMessage('error', 'All fields required.');
        redirect("modules/categories/edit_category.php?id=$id");
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE categories
         SET category_name = ?, category_type = ?
         WHERE id = ? AND user_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssii",
        $categoryName,
        $categoryType,
        $id,
        $userId
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    setFlashMessage('success', 'Category updated.');
    redirect('modules/categories/categories.php');
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>

<div class="form-page">

    <h1>Edit Category</h1>

    <form method="POST" class="main-form">

        <input
            type="text"
            name="category_name"
            value="<?= htmlspecialchars($category['category_name']); ?>"
            required
        >

        <select name="category_type" required>

            <option
                value="income"
                <?= $category['category_type'] == 'income' ? 'selected' : ''; ?>
            >
                Income
            </option>

            <option
                value="expense"
                <?= $category['category_type'] == 'expense' ? 'selected' : ''; ?>
            >
                Expense
            </option>

        </select>

        <button type="submit">
            Update Category
        </button>

    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>