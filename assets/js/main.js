document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | Auto-hide Flash Messages
    |--------------------------------------------------------------------------
    */

    const flashMessages = document.querySelectorAll(
        ".flash-message, .flash"
    );

    if (flashMessages.length > 0) {
        setTimeout(() => {
            flashMessages.forEach(msg => {
                msg.style.transition = "0.4s";
                msg.style.opacity = "0";
                setTimeout(() => {
                    msg.remove();
                }, 400);
            });
        }, 3000);
    }
});