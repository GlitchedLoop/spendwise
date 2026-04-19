<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';
require_once 'helpers.php';

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        redirect('login.php');
    }
}

$_SESSION['last_activity'] = time();

?>