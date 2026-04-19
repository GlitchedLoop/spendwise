<?php

require_once '../../includes/session_check.php';

$userId = $_SESSION['user_id'];
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    redirect('modules/categories/categories.php');
}

/*
|--------------------------------------------------------------------------
| Prevent deleting default categories
|--------------------------------------------------------------------------
*/

$checkDefaultQuery = "
SELECT is_default
FROM categories
WHERE id = $id
AND user_id = $userId
LIMIT 1
";

$defaultResult = mysqli_query($conn, $checkDefaultQuery);
$defaultData = mysqli_fetch_assoc($defaultResult);

if (!$defaultData || $defaultData['is_default']) {
    setFlashMessage('error', 'Default category cannot be deleted.');
    redirect('modules/categories/categories.php');
}

/*
|--------------------------------------------------------------------------
| Prevent deleting category with linked transactions
|--------------------------------------------------------------------------
*/

$transactionCheckQuery = "
SELECT COUNT(*) as total
FROM transactions
WHERE category_id = $id
AND user_id = $userId
";

$transactionResult = mysqli_query($conn, $transactionCheckQuery);
$transactionData = mysqli_fetch_assoc($transactionResult);

if ($transactionData['total'] > 0) {
    setFlashMessage(
        'error',
        'Cannot delete category with existing transactions.'
    );
    redirect('modules/categories/categories.php');
}

/*
|--------------------------------------------------------------------------
| Safe Delete
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM categories
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

setFlashMessage('success', 'Category deleted successfully.');

redirect('modules/categories/categories.php');