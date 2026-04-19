<?php

class PredictionEngine
{
    private $conn;
    private $userId;

    public function __construct($conn, $userId)
    {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function predictNextMonthExpense()
    {
        $expenses = [];

        /*
        |--------------------------------------------------------------------------
        | Last 3 Months Expense Collection
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 3; $i++) {

            $month = date('m', strtotime("-$i month"));

            $query = "
            SELECT IFNULL(SUM(amount),0) as total
            FROM transactions
            WHERE user_id = {$this->userId}
            AND type = 'expense'
            AND MONTH(transaction_date) = $month
            ";

            $result = mysqli_query($this->conn, $query);
            $row = mysqli_fetch_assoc($result);

            $expenses[] = (float)$row['total'];
        }

        /*
        |--------------------------------------------------------------------------
        | Moving Average Prediction
        |--------------------------------------------------------------------------
        */

        $prediction = 0;

        if (count($expenses) > 0) {
            $prediction = array_sum($expenses) / count($expenses);
        }

        return round($prediction, 2);
    }
}