<?php
require_once __DIR__ . '/auth_guard.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDB();
$memberCount  = $pdo->query("SELECT COUNT(*) FROM members  WHERE is_active=1")->fetchColumn();
$hitCount     = $pdo->query("SELECT COUNT(*) FROM top_hits WHERE is_active=1")->fetchColumn();
$historyCount = $pdo->query("SELECT COUNT(*) FROM history  WHERE is_active=1")->fetchColumn();
$featuredCount= $pdo->query("SELECT COUNT(*) FROM top_hits WHERE is_featured=1")->fetchColumn();

$recentMembers = $pdo->query("SELECT * FROM members  ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentHits    = $pdo->query("SELECT * FROM top_hits ORDER BY created_at DESC LIMIT 5")->fetchAll();

define('SITE_ROOT', dirname(__DIR__));
define('SITE_URL',  'http://localhost/backstreetboys');

$pageTitle = 'Dashboard';
require_once __DIR__ . '/layout_header.php';
?>

<!-- STAT CARDS -->
<div class="row g-4 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(116,185,255,.12);">
        <i class="fas fa-users" style="color:#74b9ff;"></i>
      </div>
      <div class="stat-value"><?= $memberCount ?></div>
      <div class="stat-label">Active Members</div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(201,168,76,.12);">
        <i class="fas fa-music" style="color:#c9a84c;"></i>
      </div>
      <div class="stat-value"><?= $hitCount ?></div>
      <div class="stat-label">Top Hits</div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(162,155,254,.12);">
        <i class="fas fa-history" style="color:#a29bfe;"></i>
      </div>
      <div class="stat-value"><?= $historyCount ?></div>
      <div class="stat-label">History Events</div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(255,215,0,.1);">
        <i class="fas fa-star" style="color:#ffd700;"></i>
      </div>
      <div class="stat-value"><?= $featuredCount ?></div>
      <div class="stat-label">Featured Hits</div>
    </div>
  </div>
</div>

<!-- QUICK ACTIONS -->
<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="content-card p-3">
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <span class="text-muted me-2" style="font-size:.78rem;letter-spacing:1px;text-transform:uppercase;">Quick Actions:</span>
        <a href="members/create.php" class="btn-add text-decoration-none">
          <i class="fas fa-user-plus"></i> Add Member
        </a>
        <a href="tophits/create.php" class="btn-add text-decoration-none">
          <i class="fas fa-music"></i> Add Hit
        </a>
        <a href="history/create.php" class="btn-add text-decoration-none">
          <i class="fas fa-plus"></i> Add History
        </a>
        <a href="<?= SITE_URL ?>/index.php" target="_blank" class="btn-view-site text-decoration-none ms-auto">
          <i class="fas fa-eye"></i> View Live Site
        </a>
      </div>
    </div>
  </div>
</div>

<!-- RECENT CONTENT TABLES -->
<div class="row g-4">
  <!-- Recent Members -->
  <div class="col-xl-6">
    <div class="content-card">
      <div class="content-card-header">
        <span class="content-card-title"><i class="fas fa-users me-2" style="color:#74b9ff;"></i>Members</span>
        <a href="members/index.php" class="text-decoration-none" style="font-size:.78rem;color:#c9a84c;">View All →</a>
      </div>
      <div class="content-card-body">
        <table class="bsb-table">
          <thead><tr>
            <th>Member</th><th>Role</th><th>Status</th><th>Actions</th>
          </tr></thead>
          <tbody>
          <?php foreach ($recentMembers as $m): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="table-thumb-ph"><i class="fas fa-user"></i></div>
                <div>
                  <div style="font-weight:600;color:#fff;font-size:.85rem;"><?= htmlspecialchars($m['name']) ?></div>
                  <div style="font-size:.72rem;color:#666;"><?= htmlspecialchars($m['nickname'] ?? '') ?></div>
                </div>
              </div>
            </td>
            <td><span style="font-size:.78rem;color:#aaa;"><?= htmlspecialchars($m['role'] ?? '') ?></span></td>
            <td><?= $m['is_active'] ? '<span class="badge-active">Active</span>' : '<span class="badge-inactive">Hidden</span>' ?></td>
            <td>
              <a href="members/edit.php?id=<?= $m['id'] ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
              <a href="members/delete.php?id=<?= $m['id'] ?>" class="btn-action btn-delete confirm-delete"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Recent Hits -->
  <div class="col-xl-6">
    <div class="content-card">
      <div class="content-card-header">
        <span class="content-card-title"><i class="fas fa-music me-2" style="color:#c9a84c;"></i>Top Hits</span>
        <a href="tophits/index.php" class="text-decoration-none" style="font-size:.78rem;color:#c9a84c;">View All →</a>
      </div>
      <div class="content-card-body">
        <table class="bsb-table">
          <thead><tr>
            <th>Song</th><th>Album</th><th>Year</th><th>Actions</th>
          </tr></thead>
          <tbody>
          <?php foreach ($recentHits as $h): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="table-thumb-ph"><i class="fas fa-music"></i></div>
                <div style="font-weight:600;color:#fff;font-size:.85rem;"><?= htmlspecialchars($h['title']) ?></div>
              </div>
            </td>
            <td><span style="font-size:.78rem;color:#aaa;"><?= htmlspecialchars($h['album'] ?? '') ?></span></td>
            <td><span class="badge-gold"><?= htmlspecialchars($h['year_released'] ?? '') ?></span></td>
            <td>
              <a href="tophits/edit.php?id=<?= $h['id'] ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
              <a href="tophits/delete.php?id=<?= $h['id'] ?>" class="btn-action btn-delete confirm-delete"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>
