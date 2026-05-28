<?php
// ============================================================
// Database Configuration
// ============================================================

define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_NAME',     'backstreetboys_db');
define('DB_CHARSET',  'utf8mb4');

// Site root (no trailing slash)
define('SITE_ROOT', dirname(__DIR__));
define('SITE_URL',  'http://localhost/backstreetboys');

// Upload paths
define('UPLOAD_DIR',    SITE_ROOT . '/uploads/');
define('UPLOAD_URL',    SITE_URL  . '/uploads/');

/**
 * Returns a PDO connection (singleton)
 */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

/**
 * Fetch a single site setting by key
 */
function getSetting(string $key, string $default = ''): string {
    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT setting_val FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $row  = $stmt->fetch();
    return $row ? (string)$row['setting_val'] : $default;
}
