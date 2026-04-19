<?php

class InsightEngine
{
    private $conn;
    private $userId;

    public function __construct($conn, $userId)
    {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function generate()
    {
        $insights = [];

        /*
        |--------------------------------------------------------------------------
        | Highest Expense Category
        |--------------------------------------------------------------------------
        */

        $query = "
        SELECT c.category_name, SUM(t.amount) as total
        FROM transactions t
        JOIN categories c
            ON t.category_id = c.id
        WHERE t.user_id = {$this->userId}
        AND t.type = 'expense'
        GROUP BY t.category_id
        ORDER BY total DESC
        LIMIT 1
        ";

        $result = mysqli_query($this->conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $insights[] = [
                'title' => 'Top Spending Category',
                'message' => "You spend most on {$row['category_name']} (" . formatCurrency($row['total']) . ")"
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Spending Spike
        |--------------------------------------------------------------------------
        */

        $current = date('m');
        $previous = $current - 1;

        $currentExpense = $this->monthlyExpense($current);
        $previousExpense = $this->monthlyExpense($previous);

        if ($previousExpense > 0 && $currentExpense > $previousExpense) {
            $increase = round((($currentExpense - $previousExpense) / $previousExpense) * 100);

            $insights[] = [
                'title' => 'Spending Spike',
                'message' => "Your expenses increased by {$increase}% compared to last month"
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Low Savings Warning
        |--------------------------------------------------------------------------
        */

        $income = $this->total('income');
        $expense = $this->total('expense');

        if ($income > 0 && ($income - $expense) < ($income * 0.1)) {
            $insights[] = [
                'title' => 'Low Savings Warning',
                'message' => "Your savings are critically low. Consider reducing non-essential expenses."
            ];
        }

        return $insights;
    }

    private function total($type)
    {
        $query = "
        SELECT IFNULL(SUM(amount),0) as total
        FROM transactions
        WHERE user_id = {$this->userId}
        AND type = '$type'
        ";

        return mysqli_fetch_assoc(
            mysqli_query($this->conn, $query)
        )['total'];
    }

    private function monthlyExpense($month)
    {
        $query = "
        SELECT IFNULL(SUM(amount),0) as total
        FROM transactions
        WHERE user_id = {$this->userId}
        AND type = 'expense'
        AND MONTH(transaction_date) = $month
        ";

        return mysqli_fetch_assoc(
            mysqli_query($this->conn, $query)
        )['total'];
    }
}