<?php

require_once '../config/config.php';
require_once '../classes/PredictionEngine.php';

if (!isLoggedIn()) {
    exit(json_encode([]));
}

header('Content-Type: application/json');

$userId = $_SESSION['user_id'];

$engine = new PredictionEngine($conn, $userId);
$prediction = $engine->predictNextMonthExpense();

echo json_encode([
    'predicted_expense' => $prediction,
    'next_month' => date('F Y', strtotime('+1 month'))
]);