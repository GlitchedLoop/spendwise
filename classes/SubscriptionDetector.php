<?php

class SubscriptionDetector
{
    private $conn;
    private $userId;

    public function __construct($conn, $userId)
    {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function detect()
    {
        $subscriptions = [];

        /*
        |--------------------------------------------------------------------------
        | Find repeated expense descriptions
        |--------------------------------------------------------------------------
        */

        $query = "
        SELECT
            description,
            amount,
            COUNT(*) as frequency,
            MAX(transaction_date) as last_date
        FROM transactions
        WHERE user_id = {$this->userId}
        AND type = 'expense'
        GROUP BY description, amount
        HAVING COUNT(*) >= 2
        ORDER BY frequency DESC
        ";

        $result = mysqli_query($this->conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {

            /*
            Simple heuristic:
            repeated same amount + same description
            = likely subscription
            */

            $subscriptions[] = [
                'description' => $row['description'],
                'amount' => $row['amount'],
                'frequency' => 'monthly',
                'last_detected' => $row['last_date']
            ];
        }

        return $subscriptions;
    }
}