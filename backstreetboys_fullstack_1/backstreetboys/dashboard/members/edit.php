<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';

define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
define('SITE_URL',  'http://localhost/backstreetboys');

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$member = $pdo->prepare("SELECT * FROM members WHERE id = ?");
$member->execute([$id]);
$member = $member->fetch();
if (!$member) { header('Location: index.php'); exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'          => trim($_POST['name'] ?? ''),
        'nickname'      => trim($_POST['nickname'] ?? ''),
        'birthdate'     => $_POST['birthdate'] ?? '',
        'birthplace'    => trim($_POST['birthplace'] ?? ''),
        'role'          => trim($_POST['role'] ?? ''),
        'bio'           => trim($_POST['bio'] ?? ''),
        'social_ig'     => trim($_POST['social_ig'] ?? ''),
        'social_tw'     => trim($_POST['social_tw'] ?? ''),
        'social_fb'     => trim($_POST['social_fb'] ?? ''),
        'is_active'     => isset($_POST['is_active']) ? 1 : 0,
        'display_order' => (int)($_POST['display_order'] ?? 0),
    ];

    if (!$data['name']) $errors[] = 'Name is required.';

    $photoFilename = $member['photo'];
    if (!empty($_FILES['photo']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext     = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Invalid image format.';
        } else {
            $newName = 'member_' . time() . '_' . rand(100,999) . '.' . $ext;
            $dest = SITE_ROOT . '/uploads/members/' . $newName;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                // Delete old photo
                if ($photoFilename && file_exists(SITE_ROOT . '/uploads/members/' . $photoFilename)) {
                    unlink(SITE_ROOT . '/uploads/members/' . $photoFilename);
                }
                $photoFilename = $newName;
            } else {
                $errors[] = 'Failed to upload photo.';
            }
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE members SET
            name=?,nickname=?,birthdate=?,birthplace=?,role=?,photo=?,bio=?,
            social_ig=?,social_tw=?,social_fb=?,is_active=?,display_order=?
            WHERE id=?");
        $stmt->execute([
            $data['name'], $data['nickname'],
            $data['birthdate'] ?: null, $data['birthplace'],
            $data['role'], $photoFilename, $data['bio'],
            $data['social_ig'], $data['social_tw'], $data['social_fb'],
            $data['is_active'], $data['display_order'], $id
        ]);
        $_SESSION['flash'] = 'Member updated successfully!';
        header('Location: index.php'); exit;
    }
    $member = array_merge($member, $data);
}

$pageTitle = 'Edit Member';
require_once __DIR__ . '/../layout_header.php';
?>

<div class="d-flex align-items-center gap-3 mb-4">
  <a href="index.php" style="color:#c9a84c;font-size:.85rem;text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Back to Members</a>
</div>

<?php if ($errors): ?>
<div class="alert-error-custom auto-dismiss mb-4">
  <i class="fas fa-exclamation-circle me-2"></i><?= implode(' ', array_map('htmlspecialchars', $errors)) ?>
</div>
<?php endif; ?>

<div class="content-card">
  <div class="content-card-header">
    <span class="content-card-title"><i class="fas fa-user-edit me-2" style="color:#74b9ff;"></i>Edit: <?= htmlspecialchars($member['name']) ?></span>
  </div>
  <div class="p-4">
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($member['name']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Nickname</label>
          <input type="text" name="nickname" class="form-control" value="<?= htmlspecialchars($member['nickname'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Role / Voice Part</label>
          <input type="text" name="role" class="form-control" value="<?= htmlspecialchars($member['role'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Birth Date</label>
          <input type="date" name="birthdate" class="form-control" value="<?= htmlspecialchars($member['birthdate'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Birthplace</label>
          <input type="text" name="birthplace" class="form-control" value="<?= htmlspecialchars($member['birthplace'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Biography</label>
          <textarea name="bio" class="form-control" rows="5"><?= htmlspecialchars($member['bio'] ?? '') ?></textarea>
        </div>
        <div class="col-md-12">
          <label class="form-label">Profile Photo</label>
          <?php if (!empty($member['photo'])): ?>
          <div class="mb-2">
            <img src="<?= SITE_URL ?>/uploads/members/<?= htmlspecialchars($member['photo']) ?>"
                 style="height:80px;width:64px;object-fit:cover;border-radius:8px;border:1px solid rgba(201,168,76,.3);" alt="">
            <small class="text-muted ms-2">Current photo</small>
          </div>
          <?php endif; ?>
          <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
          <small class="text-muted">Leave empty to keep current photo.</small>
        </div>
        <div class="col-md-4">
          <label class="form-label">Instagram URL</label>
          <input type="url" name="social_ig" class="form-control" value="<?= htmlspecialchars($member['social_ig'] ?? '') ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Twitter URL</label>
          <input type="url" name="social_tw" class="form-control" value="<?= htmlspecialchars($member['social_tw'] ?? '') ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Facebook URL</label>
          <input type="url" name="social_fb" class="form-control" value="<?= htmlspecialchars($member['social_fb'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)$member['display_order'] ?>" min="0">
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check form-switch">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?= $member['is_active'] ? 'checked' : '' ?>>
            <label for="is_active" class="form-check-label" style="color:#aaa;">Visible on Website</label>
          </div>
        </div>
        <div class="col-12 d-flex gap-2 mt-2">
          <button type="submit" class="btn-save"><i class="fas fa-save me-1"></i> Update Member</button>
          <a href="index.php" class="btn-cancel text-decoration-none">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../layout_footer.php'; ?>
