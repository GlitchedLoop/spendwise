document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | Auto-hide Warning + Danger Alerts
    |--------------------------------------------------------------------------
    */

    const alerts = document.querySelectorAll(
        ".warning-alert, .danger-alert"
    );

    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.transition = "0.4s";
                alert.style.opacity = "0";
            });
        }, 5000);
    }
});