document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | Dashboard Quick Checks
    |--------------------------------------------------------------------------
    */

    const statCards = document.querySelectorAll(".stats-grid .card");

    statCards.forEach(card => {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-2px)";
            this.style.transition = "0.2s";
        });

        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0)";
        });
    });
});