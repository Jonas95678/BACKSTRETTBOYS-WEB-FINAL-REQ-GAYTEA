<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';
define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
define('SITE_URL',  'http://localhost/backstreetboys');

$pdo     = getDB();
$hits    = $pdo->query("SELECT * FROM top_hits ORDER BY display_order ASC, id ASC")->fetchAll();
$msg     = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);
$pageTitle = 'Top Hits';
require_once __DIR__ . '/../layout_header.php';
?>

<?php if ($msg): ?>
<div class="alert-success-custom auto-dismiss mb-4"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="content-card">
  <div class="content-card-header">
    <span class="content-card-title"><i class="fas fa-music me-2" style="color:#c9a84c;"></i>All Top Hits</span>
    <a href="create.php" class="btn-add text-decoration-none"><i class="fas fa-plus"></i> Add Hit</a>
  </div>
  <div class="content-card-body">
    <table class="bsb-table">
      <thead><tr>
        <th>#</th><th>Cover</th><th>Title</th><th>Album</th><th>Year</th><th>Duration</th><th>Featured</th><th>Status</th><th>Order</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php foreach ($hits as $h): ?>
      <tr>
        <td style="color:#666;"><?= $h['id'] ?></td>
        <td>
          <?php if (!empty($h['cover_image'])): ?>
            <img src="<?= SITE_URL ?>/uploads/albums/<?= htmlspecialchars($h['cover_image']) ?>" class="table-thumb" alt="">
          <?php else: ?>
            <div class="table-thumb-ph"><i class="fas fa-music"></i></div>
          <?php endif; ?>
        </td>
        <td><strong style="color:#fff;"><?= htmlspecialchars($h['title']) ?></strong></td>
        <td style="color:#aaa;font-size:.82rem;"><?= htmlspecialchars($h['album'] ?? '—') ?></td>
        <td><span class="badge-gold"><?= htmlspecialchars($h['year_released'] ?? '—') ?></span></td>
        <td style="color:#888;"><?= htmlspecialchars($h['duration'] ?? '—') ?></td>
        <td><?= $h['is_featured'] ? '<span class="badge-active">⭐ Featured</span>' : '<span style="color:#555;font-size:.75rem;">—</span>' ?></td>
        <td><?= $h['is_active'] ? '<span class="badge-active">Active</span>' : '<span class="badge-inactive">Hidden</span>' ?></td>
        <td style="color:#888;"><?= $h['display_order'] ?></td>
        <td>
          <a href="edit.php?id=<?= $h['id'] ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
          <a href="toggle.php?id=<?= $h['id'] ?>" class="btn-action btn-toggle" title="Toggle"><i class="fas fa-eye<?= $h['is_active'] ? '' : '-slash' ?>"></i></a>
          <a href="delete.php?id=<?= $h['id'] ?>" class="btn-action btn-delete confirm-delete" title="Delete"><i class="fas fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$hits): ?>
      <tr><td colspan="10" class="text-center py-4" style="color:#666;">No hits found. <a href="create.php" style="color:#c9a84c;">Add one →</a></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout_footer.php'; ?>
