<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';
define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
define('SITE_URL',  'http://localhost/backstreetboys');

$pdo    = getDB();
$id     = (int)($_GET['id'] ?? 0);
$isEdit = $id > 0;
$event  = [];

if ($isEdit) {
    $s = $pdo->prepare("SELECT * FROM history WHERE id=?");
    $s->execute([$id]);
    $event = $s->fetch();
    if (!$event) { header('Location: index.php'); exit; }
}

$errors = [];
$categories = ['Formation','Album','Tour','Award','Milestone','Other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'event_year'    => (int)($_POST['event_year'] ?? 0),
        'event_month'   => $_POST['event_month'] !== '' ? (int)$_POST['event_month'] : null,
        'title'         => trim($_POST['title'] ?? ''),
        'description'   => trim($_POST['description'] ?? ''),
        'category'      => $_POST['category'] ?? 'Milestone',
        'is_active'     => isset($_POST['is_active']) ? 1 : 0,
        'display_order' => (int)($_POST['display_order'] ?? 0),
    ];

    if (!$data['title']) $errors[] = 'Title is required.';
    if (!$data['event_year']) $errors[] = 'Year is required.';

    $imgFilename = $isEdit ? ($event['image'] ?? '') : '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $newName = 'hist_' . time() . '_' . rand(100,999) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], SITE_ROOT . '/uploads/' . $newName)) {
                if ($imgFilename && file_exists(SITE_ROOT . '/uploads/' . $imgFilename))
                    unlink(SITE_ROOT . '/uploads/' . $imgFilename);
                $imgFilename = $newName;
            }
        }
    }

    if (!$errors) {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE history SET event_year=?,event_month=?,title=?,description=?,image=?,category=?,is_active=?,display_order=? WHERE id=?");
            $stmt->execute([$data['event_year'],$data['event_month'],$data['title'],$data['description'],$imgFilename,$data['category'],$data['is_active'],$data['display_order'],$id]);
            $_SESSION['flash'] = 'Event updated!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO history (event_year,event_month,title,description,image,category,is_active,display_order) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$data['event_year'],$data['event_month'],$data['title'],$data['description'],$imgFilename,$data['category'],$data['is_active'],$data['display_order']]);
            $_SESSION['flash'] = 'Event added!';
        }
        header('Location: index.php'); exit;
    }
    $event = array_merge($event, $data);
}

$pageTitle = $isEdit ? 'Edit Event' : 'Add Event';
require_once __DIR__ . '/../layout_header.php';
?>

<div class="mb-4">
  <a href="index.php" style="color:#c9a84c;font-size:.85rem;text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Back to History</a>
</div>
<?php if ($errors): ?>
<div class="alert-error-custom auto-dismiss mb-4"><?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<div class="content-card">
  <div class="content-card-header">
    <span class="content-card-title">
      <i class="fas fa-<?= $isEdit ? 'edit' : 'plus' ?> me-2" style="color:#a29bfe;"></i>
      <?= $isEdit ? 'Edit Event' : 'Add History Event' ?>
    </span>
  </div>
  <div class="p-4">
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Year *</label>
          <input type="number" name="event_year" class="form-control" placeholder="1993" min="1900" max="2100"
                 value="<?= htmlspecialchars($event['event_year'] ?? '') ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Month (optional)</label>
          <select name="event_month" class="form-select">
            <option value="">— No specific month —</option>
            <?php
            $months = ['January','February','March','April','May','June',
                       'July','August','September','October','November','December'];
            foreach ($months as $i => $m):
              $sel = isset($event['event_month']) && (int)$event['event_month'] === ($i+1) ? 'selected' : '';
            ?>
            <option value="<?= $i+1 ?>" <?= $sel ?>><?= $m ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Category</label>
          <select name="category" class="form-select">
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat ?>" <?= ($event['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Event Title *</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['title'] ?? '') ?>" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
        </div>
        <div class="col-md-12">
          <label class="form-label">Image (optional)</label>
          <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="col-md-6">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)($event['display_order'] ?? 0) ?>" min="0">
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check form-switch">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?= (!isset($event['is_active']) || $event['is_active']) ? 'checked' : '' ?>>
            <label for="is_active" class="form-check-label" style="color:#aaa;">Visible on Website</label>
          </div>
        </div>
        <div class="col-12 d-flex gap-2 mt-2">
          <button type="submit" class="btn-save"><i class="fas fa-save me-1"></i> <?= $isEdit ? 'Update Event' : 'Save Event' ?></button>
          <a href="index.php" class="btn-cancel text-decoration-none">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layout_footer.php'; ?>
