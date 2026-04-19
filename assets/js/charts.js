document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | Income vs Expense Line Chart
    |--------------------------------------------------------------------------
    */

    const incomeChartCanvas = document.getElementById(
        "incomeExpenseChart"
    );

    if (incomeChartCanvas) {
        fetch("../../api/chart_data.php?type=income_expense")
            .then(response => response.json())
            .then(data => {
                new Chart(incomeChartCanvas, {
                    type: "line",
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: "Income",
                                data: data.income,
                                borderWidth: 2
                            },
                            {
                                label: "Expense",
                                data: data.expense,
                                borderWidth: 2
                            }
                        ]
                    }
                });
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Category Pie Chart
    |--------------------------------------------------------------------------
    */

    const pieChartCanvas = document.getElementById(
        "categoryPieChart"
    );

    if (pieChartCanvas) {
        fetch("../../api/chart_data.php?type=category_pie")
            .then(response => response.json())
            .then(data => {
                new Chart(pieChartCanvas, {
                    type: "pie",
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                data: data.values,
                                borderWidth: 1
                            }
                        ]
                    }
                });
            });
    }
});