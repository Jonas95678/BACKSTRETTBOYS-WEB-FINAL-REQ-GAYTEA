<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';
define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
define('SITE_URL', 'http://localhost/backstreetboys');

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);
if ($id) {
    $pdo->prepare("UPDATE members SET is_active = 1 - is_active WHERE id=?")->execute([$id]);
    $_SESSION['flash'] = 'Member visibility toggled.';
}
header('Location: index.php'); exit;
