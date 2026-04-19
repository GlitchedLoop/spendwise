<?php

class FinancialScore
{
    private $conn;
    private $userId;

    public function __construct($conn, $userId)
    {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function calculate()
    {
        $score = 0;
        $remarks = [];

        /*
        |--------------------------------------------------------------------------
        | Savings Rate Score (40)
        |--------------------------------------------------------------------------
        */

        $income = $this->getTotal('income');
        $expense = $this->getTotal('expense');

        if ($income > 0) {
            $savingsRate = (($income - $expense) / $income) * 100;

            if ($savingsRate >= 30) {
                $score += 40;
                $remarks[] = "Excellent savings discipline";
            } elseif ($savingsRate >= 15) {
                $score += 25;
                $remarks[] = "Moderate savings performance";
            } else {
                $score += 10;
                $remarks[] = "Low savings rate detected";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Budget Adherence (30)
        |--------------------------------------------------------------------------
        */

        $budgetQuery = "
        SELECT IFNULL(SUM(monthly_budget),0) as total_budget
        FROM budgets
        WHERE user_id = {$this->userId}
        ";

        $budget = mysqli_fetch_assoc(
            mysqli_query($this->conn, $budgetQuery)
        )['total_budget'];

        if ($budget > 0) {
            if ($expense <= $budget) {
                $score += 30;
                $remarks[] = "Good budget control";
            } else {
                $score += 10;
                $remarks[] = "Budget overspending detected";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Spending Consistency (30)
        |--------------------------------------------------------------------------
        */

        $currentMonth = date('m');
        $lastMonth = $currentMonth - 1;

        $currentExpense = $this->getMonthlyExpense($currentMonth);
        $lastExpense = $this->getMonthlyExpense($lastMonth);

        if ($lastExpense > 0) {
            $variation = abs($currentExpense - $lastExpense) / $lastExpense * 100;

            if ($variation <= 15) {
                $score += 30;
                $remarks[] = "Stable spending behavior";
            } elseif ($variation <= 30) {
                $score += 20;
                $remarks[] = "Moderate spending fluctuation";
            } else {
                $score += 10;
                $remarks[] = "High spending inconsistency";
            }
        }

        return [
            'score' => min($score, 100),
            'remarks' => implode(', ', $remarks)
        ];
    }

    private function getTotal($type)
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

    private function getMonthlyExpense($month)
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