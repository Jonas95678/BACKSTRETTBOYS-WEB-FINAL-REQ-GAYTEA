<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: ' . (strpos($_SERVER['PHP_SELF'], '/dashboard/') !== false ? '' : 'dashboard/') . 'login.php');
    exit;
}
