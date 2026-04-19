<?php require_once __DIR__ . '/../config/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME; ?></title>

    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/style.css">

    <!-- Page Specific CSS -->
    <?php if (!empty($pageCSS)): ?>
        <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/<?= $pageCSS; ?>">
    <?php endif; ?>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="main-layout">