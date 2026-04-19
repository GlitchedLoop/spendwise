document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | Delete Confirmation Enhancement
    |--------------------------------------------------------------------------
    */

    const deleteLinks = document.querySelectorAll(
        "a[href*='delete_transaction.php']"
    );

    deleteLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            const confirmDelete = confirm(
                "Are you sure you want to delete this transaction?"
            );

            if (!confirmDelete) {
                e.preventDefault();
            }
        });
    });
});