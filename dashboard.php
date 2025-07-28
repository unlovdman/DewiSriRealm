<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

include 'db.php';

if ($_SESSION['role'] == 'admin') {
    header('Location: admin_dashboard.php');
        exit(); 
} else {
    header('Location: user_dashboard.php');
        exit(); 
}
?>
