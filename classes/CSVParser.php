<?php

class CSVParser
{
    public function parse($filePath)
    {
        $rows = [];

        if (!file_exists($filePath)) {
            return [];
        }

        $handle = fopen($filePath, "r");

        if ($handle === false) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | Skip Header Row
        |--------------------------------------------------------------------------
        */

        $header = fgetcsv($handle);

        /*
        |--------------------------------------------------------------------------
        | Parse CSV Rows
        |--------------------------------------------------------------------------
        |
        | CSV Format:
        |
        | Date,Description,Amount,Type
        |
        */

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {

            if (count($data) < 4) {
                continue;
            }

            $date = trim($data[0] ?? '');
            $description = trim($data[1] ?? '');
            $amount = (float) trim($data[2] ?? 0);
            $type = strtolower(trim($data[3] ?? ''));

            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */

            if (
                empty($date) ||
                empty($description) ||
                $amount <= 0 ||
                empty($type)
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Normalize Type
            |--------------------------------------------------------------------------
            */

            if ($type !== 'income' && $type !== 'expense') {
                $type = 'expense';
            }

            /*
            |--------------------------------------------------------------------------
            | Auto Detect Category
            |--------------------------------------------------------------------------
            */

            $category = $this->detectCategory($description);

            $rows[] = [
                'date' => $date,
                'description' => $description,
                'amount' => abs($amount),
                'type' => $type,
                'category' => $category
            ];
        }

        fclose($handle);

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | Detect Category From Description
    |--------------------------------------------------------------------------
    */

    private function detectCategory($description)
    {
        $description = strtolower($description);

        $rules = [
            'Food' => ['zomato', 'swiggy', 'restaurant', 'food'],
            'Transport' => ['uber', 'ola', 'metro', 'fuel'],
            'Shopping' => ['amazon', 'flipkart', 'shopping'],
            'Bills' => ['electricity', 'water', 'bill'],
            'Salary' => ['salary', 'credited', 'payroll'],
            'Health' => ['hospital', 'medical', 'pharmacy'],
            'Entertainment' => ['netflix', 'spotify', 'movie']
        ];

        foreach ($rules as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($description, $keyword) !== false) {
                    return $category;
                }
            }
        }

        return 'Utilities';
    }
}