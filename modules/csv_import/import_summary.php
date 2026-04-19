<?php

require_once '../../includes/session_check.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$summary = $_SESSION['import_summary'] ?? [];

if (empty($summary)) {
    redirect('modules/csv_import/upload_csv.php');
}
?>

<div class="summary-page">

    <h1>Import Summary</h1>

    <div class="summary-card">
        <p>Total Rows: <?= $summary['total']; ?></p>
        <p>Imported: <?= $summary['imported']; ?></p>
        <p>Skipped: <?= $summary['skipped']; ?></p>
        <p>Failed: <?= $summary['failed']; ?></p>
    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>