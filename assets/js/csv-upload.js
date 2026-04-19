document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | CSV File Validation (Frontend)
    |--------------------------------------------------------------------------
    */

    const fileInput = document.querySelector(
        "input[type='file'][name='csv_file']"
    );

    if (!fileInput) return;

    fileInput.addEventListener("change", function () {
        const file = this.files[0];

        if (!file) return;

        const fileName = file.name.toLowerCase();

        if (!fileName.endsWith(".csv")) {
            alert("Only CSV files are allowed.");
            this.value = "";
        }
    });
});