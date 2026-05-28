<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';

define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
define('SITE_URL',  'http://localhost/backstreetboys');

$pdo     = getDB();
$members = $pdo->query("SELECT * FROM members ORDER BY display_order ASC, id ASC")->fetchAll();

$msg = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);
$pageTitle = 'Members';
require_once __DIR__ . '/../layout_header.php';
?>

<?php if ($msg): ?>
<div class="alert-success-custom auto-dismiss mb-4"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="content-card">
  <div class="content-card-header">
    <span class="content-card-title"><i class="fas fa-users me-2" style="color:#74b9ff;"></i>All Members</span>
    <a href="create.php" class="btn-add text-decoration-none"><i class="fas fa-plus"></i> Add Member</a>
  </div>
  <div class="content-card-body">
    <table class="bsb-table">
      <thead><tr>
        <th>#</th><th>Photo</th><th>Name</th><th>Nickname</th><th>Role</th><th>Birth Date</th><th>Birthplace</th><th>Status</th><th>Order</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php foreach ($members as $i => $m): ?>
      <tr>
        <td style="color:#666;"><?= $m['id'] ?></td>
        <td>
          <?php if (!empty($m['photo'])): ?>
            <img src="<?= SITE_URL ?>/uploads/members/<?= htmlspecialchars($m['photo']) ?>" class="table-thumb" alt="">
          <?php else: ?>
            <div class="table-thumb-ph"><i class="fas fa-user"></i></div>
          <?php endif; ?>
        </td>
        <td><strong style="color:#fff;"><?= htmlspecialchars($m['name']) ?></strong></td>
        <td style="color:#888;"><?= htmlspecialchars($m['nickname'] ?? '—') ?></td>
        <td><span class="badge-gold"><?= htmlspecialchars($m['role'] ?? '—') ?></span></td>
        <td style="color:#aaa;font-size:.82rem;"><?= $m['birthdate'] ? date('M d, Y', strtotime($m['birthdate'])) : '—' ?></td>
        <td style="color:#aaa;font-size:.82rem;"><?= htmlspecialchars($m['birthplace'] ?? '—') ?></td>
        <td><?= $m['is_active'] ? '<span class="badge-active">Active</span>' : '<span class="badge-inactive">Hidden</span>' ?></td>
        <td style="color:#888;"><?= $m['display_order'] ?></td>
        <td>
          <a href="edit.php?id=<?= $m['id'] ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
          <a href="toggle.php?id=<?= $m['id'] ?>" class="btn-action btn-toggle" title="Toggle Visibility"><i class="fas fa-eye<?= $m['is_active'] ? '' : '-slash' ?>"></i></a>
          <a href="delete.php?id=<?= $m['id'] ?>" class="btn-action btn-delete confirm-delete" title="Delete"><i class="fas fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$members): ?>
      <tr><td colspan="10" class="text-center py-4" style="color:#666;">No members found. <a href="create.php" style="color:#c9a84c;">Add one →</a></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../layout_footer.php'; ?>
