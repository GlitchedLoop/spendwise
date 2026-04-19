<?php

require_once '../../includes/session_check.php';
$pageCSS = 'categories.css';

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$userId = $_SESSION['user_id'];

$query = "
SELECT *
FROM categories
WHERE user_id = $userId
ORDER BY is_default DESC, category_name ASC
";

$result = mysqli_query($conn, $query);
?>

<div class="categories-page">

    <h1>Categories</h1>

    <?php require_once '../../includes/flash_message.php'; ?>

    <div class="top-actions">
        <a href="add_category.php" class="btn-primary">
            + Add Category
        </a>
    </div>

    <table class="category-table">

        <thead>
            <tr>
                <th>Category Name</th>
                <th>Type</th>
                <th>Default</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <tr>
                        <td><?= htmlspecialchars($row['category_name']); ?></td>
                        <td><?= ucfirst($row['category_type']); ?></td>
                        <td><?= $row['is_default'] ? 'Yes' : 'No'; ?></td>

                        <td>

                            <a href="edit_category.php?id=<?= $row['id']; ?>">
                                Edit
                            </a>

                            <?php if (!$row['is_default']): ?>
                                |
                                <a
                                    href="delete_category.php?id=<?= $row['id']; ?>"
                                    onclick="return confirm('Delete this category?');"
                                >
                                    Delete
                                </a>
                            <?php endif; ?>

                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="4" style="text-align:center;">
                        No categories found.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?php require_once '../../includes/footer.php'; ?>