document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | Highlight Exceeded Budget Rows
    |--------------------------------------------------------------------------
    */

    const rows = document.querySelectorAll(".budget-table tbody tr");

    rows.forEach(row => {
        const statusCell = row.children[3];

        if (!statusCell) return;

        const status = statusCell.textContent.trim();

        if (status === "Exceeded") {
            row.style.fontWeight = "600";
        }
    });
});