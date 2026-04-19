<?php

require_once 'config/config.php';

if (isLoggedIn()) {
    redirect('modules/dashboard/dashboard.php');
} else {
    redirect('login.php');
}