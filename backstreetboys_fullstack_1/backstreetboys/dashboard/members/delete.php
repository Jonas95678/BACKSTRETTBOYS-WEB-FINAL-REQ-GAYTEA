<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';
define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);
if ($id) {
    $row = $pdo->prepare("SELECT photo FROM members WHERE id=?");
    $row->execute([$id]);
    $row = $row->fetch();
    if ($row) {
        if (!empty($row['photo'])) {
            $f = SITE_ROOT . '/uploads/members/' . $row['photo'];
            if (file_exists($f)) unlink($f);
        }
        $pdo->prepare("DELETE FROM members WHERE id=?")->execute([$id]);
        $_SESSION['flash'] = 'Member deleted successfully.';
    }
}
header('Location: index.php'); exit;
