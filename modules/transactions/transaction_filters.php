<?php

$filterType = $_GET['type'] ?? '';
$filterCategory = $_GET['category'] ?? '';
$filterDate = $_GET['date'] ?? '';

if (!empty($filterType)) {
    $safeType = mysqli_real_escape_string($conn, $filterType);
    $where .= " AND t.type = '$safeType'";
}

if (!empty($filterCategory)) {
    $safeCategory = (int)$filterCategory;
    $where .= " AND t.category_id = $safeCategory";
}

if (!empty($filterDate)) {
    $safeDate = mysqli_real_escape_string($conn, $filterDate);
    $where .= " AND t.transaction_date = '$safeDate'";
}
?>