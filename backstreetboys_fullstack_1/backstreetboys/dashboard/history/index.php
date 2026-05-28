<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';
define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
define('SITE_URL',  'http://localhost/backstreetboys');

$pdo    = getDB();
$events = $pdo->query("SELECT * FROM history ORDER BY event_year ASC, display_order ASC")->fetchAll();
$msg    = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);
$pageTitle = 'History';
require_once __DIR__ . '/../layout_header.php';
?>

<?php if ($msg): ?>
<div class="alert-success-custom auto-dismiss mb-4"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="content-card">
  <div class="content-card-header">
    <span class="content-card-title"><i class="fas fa-history me-2" style="color:#a29bfe;"></i>All History Events</span>
    <a href="create.php" class="btn-add text-decoration-none"><i class="fas fa-plus"></i> Add Event</a>
  </div>
  <div class="content-card-body">
    <table class="bsb-table">
      <thead><tr>
        <th>#</th><th>Year</th><th>Title</th><th>Category</th><th>Status</th><th>Order</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php foreach ($events as $e): ?>
      <tr>
        <td style="color:#666;"><?= $e['id'] ?></td>
        <td><span class="badge-gold"><?= htmlspecialchars($e['event_year']) ?></span></td>
        <td>
          <strong style="color:#fff;"><?= htmlspecialchars($e['title']) ?></strong>
          <div style="font-size:.75rem;color:#666;margin-top:2px;"><?= htmlspecialchars(mb_substr($e['description'] ?? '', 0, 60)) ?>...</div>
        </td>
        <td><span style="font-size:.75rem;color:#a29bfe;border:1px solid rgba(162,155,254,.3);padding:2px 10px;border-radius:20px;"><?= htmlspecialchars($e['category']) ?></span></td>
        <td><?= $e['is_active'] ? '<span class="badge-active">Active</span>' : '<span class="badge-inactive">Hidden</span>' ?></td>
        <td style="color:#888;"><?= $e['display_order'] ?></td>
        <td>
          <a href="edit.php?id=<?= $e['id'] ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
          <a href="toggle.php?id=<?= $e['id'] ?>" class="btn-action btn-toggle" title="Toggle"><i class="fas fa-eye<?= $e['is_active'] ? '' : '-slash' ?>"></i></a>
          <a href="delete.php?id=<?= $e['id'] ?>" class="btn-action btn-delete confirm-delete" title="Delete"><i class="fas fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$events): ?>
      <tr><td colspan="7" class="text-center py-4" style="color:#666;">No events yet. <a href="create.php" style="color:#c9a84c;">Add one →</a></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout_footer.php'; ?>
