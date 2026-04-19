<?php

require_once '../../includes/session_check.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$rows = $_SESSION['csv_preview'] ?? [];

if (empty($rows)) {
    redirect('modules/csv_import/upload_csv.php');
}
?>

<div class="csv-preview-page">

    <h1>CSV Preview</h1>

    <table>

        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($rows as $row): ?>

                <tr>
                    <td><?= htmlspecialchars($row['description']); ?></td>
                    <td><?= formatCurrency($row['amount']); ?></td>
                    <td><?= $row['date']; ?></td>
                    <td><?= ucfirst($row['type']); ?></td>
                    <td><?= $row['category']; ?></td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <form method="POST" action="confirm_import.php">
        <button type="submit">
            Confirm Import
        </button>
    </form>

</div>

<?php require_once '../../includes/footer.php'; ?>