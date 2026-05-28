<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';
define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
define('SITE_URL', 'http://localhost/backstreetboys');
$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);
if ($id) {
    $pdo->prepare("DELETE FROM history WHERE id=?")->execute([$id]);
    $_SESSION['flash'] = 'Event deleted.';
}
header('Location: index.php'); exit;
