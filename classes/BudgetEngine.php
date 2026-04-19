<?php

class BudgetEngine
{
    private $conn;
    private $userId;

    public function __construct($conn, $userId)
    {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    /*
    |--------------------------------------------------------------------------
    | Get Current Month
    |--------------------------------------------------------------------------
    */

    private function currentMonthYear()
    {
        return date('F Y');
    }

    /*
    |--------------------------------------------------------------------------
    | Get Total Expense
    |--------------------------------------------------------------------------
    */

    public function getTotalExpense($categoryId = null)
    {
        if ($categoryId) {
            $query = "
                SELECT IFNULL(SUM(amount), 0) AS total
                FROM transactions
                WHERE user_id = {$this->userId}
                AND type = 'expense'
                AND category_id = {$categoryId}
                AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
                AND YEAR(transaction_date) = YEAR(CURRENT_DATE())
            ";
        } else {
            $query = "
                SELECT IFNULL(SUM(amount), 0) AS total
                FROM transactions
                WHERE user_id = {$this->userId}
                AND type = 'expense'
                AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
                AND YEAR(transaction_date) = YEAR(CURRENT_DATE())
            ";
        }

        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);

        return (float)$row['total'];
    }

    /*
    |--------------------------------------------------------------------------
    | Get Budget Amount
    |--------------------------------------------------------------------------
    */

    public function getBudgetAmount($categoryId = null)
    {
        $monthYear = $this->currentMonthYear();

        if ($categoryId) {
            $query = "
                SELECT IFNULL(monthly_budget, 0) AS budget
                FROM budgets
                WHERE user_id = {$this->userId}
                AND category_id = {$categoryId}
                AND month_year = '{$monthYear}'
                LIMIT 1
            ";
        } else {
            $query = "
                SELECT IFNULL(monthly_budget, 0) AS budget
                FROM budgets
                WHERE user_id = {$this->userId}
                AND category_id IS NULL
                AND month_year = '{$monthYear}'
                LIMIT 1
            ";
        }

        $result = mysqli_query($this->conn, $query);

        if (!$result || mysqli_num_rows($result) == 0) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return (float)$row['budget'];
    }

    /*
    |--------------------------------------------------------------------------
    | Get Remaining Budget
    |--------------------------------------------------------------------------
    */

    public function getRemainingBudget($categoryId = null)
    {
        $budget = $this->getBudgetAmount($categoryId);
        $expense = $this->getTotalExpense($categoryId);

        return max($budget - $expense, 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Usage Percentage
    |--------------------------------------------------------------------------
    */

    public function getUsagePercentage($categoryId = null)
    {
        $budget = $this->getBudgetAmount($categoryId);
        $expense = $this->getTotalExpense($categoryId);

        if ($budget <= 0) {
            return 0;
        }

        $percentage = ($expense / $budget) * 100;

        return min(round($percentage, 2), 100);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Budget Status
    |--------------------------------------------------------------------------
    */

    public function getBudgetStatus($categoryId = null)
    {
        $percentage = $this->getUsagePercentage($categoryId);
        $budget = $this->getBudgetAmount($categoryId);

        if ($budget <= 0) {
            return 'No Budget Set';
        }

        if ($percentage >= 100) {
            return 'Exceeded';
        }

        if ($percentage >= 80) {
            return 'Near Limit';
        }

        return 'Safe';
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Smart Alert
    |--------------------------------------------------------------------------
    */

    public function generateBudgetAlert($categoryId = null, $categoryName = 'Overall Budget')
    {
        $status = $this->getBudgetStatus($categoryId);

        if ($status === 'Exceeded') {
            return "Budget exceeded for {$categoryName}";
        }

        if ($status === 'Near Limit') {
            return "Budget nearing limit for {$categoryName}";
        }

        return null;
    }
}