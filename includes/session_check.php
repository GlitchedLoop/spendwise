<?php

require_once __DIR__ . '/../config/config.php';

if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login first.');
    redirect('login.php');
}

?>