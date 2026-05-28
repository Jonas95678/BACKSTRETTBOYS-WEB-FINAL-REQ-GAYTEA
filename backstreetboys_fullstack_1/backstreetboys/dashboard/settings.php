<?php
require_once __DIR__ . '/auth_guard.php';
require_once __DIR__ . '/../config/database.php';
define('SITE_ROOT', dirname(__DIR__));
define('SITE_URL',  'http://localhost/backstreetboys');

$pdo = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['site_title','site_tagline','hero_title','hero_subtitle','about_text','footer_text'];
    $stmt   = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_val)
        VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_val = VALUES(setting_val)");
    foreach ($fields as $key) {
        $val = trim($_POST[$key] ?? '');
        $stmt->execute([$key, $val]);
    }
    $msg = 'Settings saved successfully!';
}

// Fetch all settings
$settings = [];
foreach ($pdo->query("SELECT setting_key, setting_val FROM site_settings") as $row) {
    $settings[$row['setting_key']] = $row['setting_val'];
}

$pageTitle = 'Site Settings';
require_once __DIR__ . '/layout_header.php';
?>

<?php if ($msg): ?>
<div class="alert-success-custom auto-dismiss mb-4"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="content-card">
  <div class="content-card-header">
    <span class="content-card-title"><i class="fas fa-cog me-2" style="color:#a29bfe;"></i>Site Settings</span>
    <a href="<?= SITE_URL ?>/index.php" target="_blank" class="btn-view-site text-decoration-none">
      <i class="fas fa-eye"></i> View Live Site
    </a>
  </div>
  <div class="p-4">
    <form method="POST">
      <div class="row g-4">
        <div class="col-md-6">
          <label class="form-label">Site Title</label>
          <input type="text" name="site_title" class="form-control" value="<?= htmlspecialchars($settings['site_title'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Site Tagline</label>
          <input type="text" name="site_tagline" class="form-control" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Hero Title (large heading on homepage)</label>
          <input type="text" name="hero_title" class="form-control" value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Hero Subtitle</label>
          <input type="text" name="hero_subtitle" class="form-control" value="<?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">About / Description Text</label>
          <textarea name="about_text" class="form-control" rows="5"><?= htmlspecialchars($settings['about_text'] ?? '') ?></textarea>
        </div>
        <div class="col-md-12">
          <label class="form-label">Footer Text</label>
          <input type="text" name="footer_text" class="form-control" value="<?= htmlspecialchars($settings['footer_text'] ?? '') ?>">
        </div>
        <div class="col-12">
          <button type="submit" class="btn-save"><i class="fas fa-save me-1"></i> Save Settings</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
