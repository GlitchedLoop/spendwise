<?php

require_once '../../includes/session_check.php';
$pageCSS = 'analytics.css';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

require_once 'trend_analysis.php';

?>

<div class="analytics-page">

    <h1>Analytics</h1>

    <div class="chart-grid">

        <?php require_once 'income_expense_chart.php'; ?>
        <?php require_once 'category_chart.php'; ?>

    </div>

    <div class="analysis-box">

        <h2>Spending Pattern Insights</h2>

        <?php if (!empty($insights)): ?>

            <?php foreach ($insights as $insight): ?>
                <div class="insight-item">
                    <?= htmlspecialchars($insight); ?>
                </div>
            <?php endforeach; ?>

        <?php else: ?>

            <div class="insight-item">
                No analytical insights available yet.
            </div>

        <?php endif; ?>

    </div>

</div>

<script src="<?= BASE_URL; ?>assets/js/charts.js"></script>

<?php require_once '../../includes/footer.php'; ?>