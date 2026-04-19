<?php

$search = '';

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $safeSearch = mysqli_real_escape_string($conn, $search);

    $where .= " 
        AND (
            t.description LIKE '%$safeSearch%'
            OR t.payment_method LIKE '%$safeSearch%'
            OR c.category_name LIKE '%$safeSearch%'
        )
    ";
}
?>