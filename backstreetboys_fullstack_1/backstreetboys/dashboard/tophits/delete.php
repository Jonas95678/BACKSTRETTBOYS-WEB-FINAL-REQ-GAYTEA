<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';
define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);
if ($id) {
    $row = $pdo->prepare("SELECT cover_image FROM top_hits WHERE id=?");
    $row->execute([$id]);
    $row = $row->fetch();
    if ($row && $row['cover_image']) {
        $f = SITE_ROOT . '/uploads/albums/' . $row['cover_image'];
        if (file_exists($f)) unlink($f);
    }
    $pdo->prepare("DELETE FROM top_hits WHERE id=?")->execute([$id]);
    $_SESSION['flash'] = 'Hit deleted.';
}
header('Location: index.php'); exit;
