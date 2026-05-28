<?php
require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../../config/database.php';
define('SITE_ROOT', dirname(dirname(dirname(__DIR__))));
define('SITE_URL',  'http://localhost/backstreetboys');

$pdo    = getDB();
$id     = (int)($_GET['id'] ?? 0);
$isEdit = $id > 0;
$hit    = [];

if ($isEdit) {
    $s = $pdo->prepare("SELECT * FROM top_hits WHERE id=?");
    $s->execute([$id]);
    $hit = $s->fetch();
    if (!$hit) { header('Location: index.php'); exit; }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title'        => trim($_POST['title'] ?? ''),
        'album'        => trim($_POST['album'] ?? ''),
        'year_released'=> $_POST['year_released'] ? (int)$_POST['year_released'] : null,
        'duration'     => trim($_POST['duration'] ?? ''),
        'youtube_url'  => trim($_POST['youtube_url'] ?? ''),
        'spotify_url'  => trim($_POST['spotify_url'] ?? ''),
        'description'  => trim($_POST['description'] ?? ''),
        'peak_chart'   => $_POST['peak_chart'] !== '' ? (int)$_POST['peak_chart'] : null,
        'is_featured'  => isset($_POST['is_featured']) ? 1 : 0,
        'is_active'    => isset($_POST['is_active']) ? 1 : 0,
        'display_order'=> (int)($_POST['display_order'] ?? 0),
    ];

    if (!$data['title']) $errors[] = 'Title is required.';

    $coverFilename = $isEdit ? ($hit['cover_image'] ?? '') : '';
    if (!empty($_FILES['cover_image']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Invalid image format.';
        } else {
            $newName = 'hit_' . time() . '_' . rand(100,999) . '.' . $ext;
            $dest = SITE_ROOT . '/uploads/albums/' . $newName;
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $dest)) {
                if ($coverFilename && file_exists(SITE_ROOT . '/uploads/albums/' . $coverFilename))
                    unlink(SITE_ROOT . '/uploads/albums/' . $coverFilename);
                $coverFilename = $newName;
            }
        }
    }

    if (!$errors) {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE top_hits SET
                title=?,album=?,year_released=?,duration=?,cover_image=?,youtube_url=?,spotify_url=?,
                description=?,peak_chart=?,is_featured=?,is_active=?,display_order=? WHERE id=?");
            $stmt->execute([$data['title'],$data['album'],$data['year_released'],$data['duration'],
                $coverFilename,$data['youtube_url'],$data['spotify_url'],$data['description'],
                $data['peak_chart'],$data['is_featured'],$data['is_active'],$data['display_order'],$id]);
            $_SESSION['flash'] = 'Hit updated successfully!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO top_hits
                (title,album,year_released,duration,cover_image,youtube_url,spotify_url,description,peak_chart,is_featured,is_active,display_order)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$data['title'],$data['album'],$data['year_released'],$data['duration'],
                $coverFilename,$data['youtube_url'],$data['spotify_url'],$data['description'],
                $data['peak_chart'],$data['is_featured'],$data['is_active'],$data['display_order']]);
            $_SESSION['flash'] = 'Hit "' . $data['title'] . '" added!';
        }
        header('Location: index.php'); exit;
    }
    $hit = array_merge($hit, $data);
}

$pageTitle = $isEdit ? 'Edit Hit' : 'Add Hit';
require_once __DIR__ . '/../layout_header.php';
?>

<div class="mb-4">
  <a href="index.php" style="color:#c9a84c;font-size:.85rem;text-decoration:none;"><i class="fas fa-arrow-left me-1"></i>Back to Top Hits</a>
</div>
<?php if ($errors): ?>
<div class="alert-error-custom auto-dismiss mb-4"><?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<div class="content-card">
  <div class="content-card-header">
    <span class="content-card-title">
      <i class="fas fa-<?= $isEdit ? 'edit' : 'plus' ?> me-2" style="color:#c9a84c;"></i>
      <?= $isEdit ? 'Edit: ' . htmlspecialchars($hit['title']) : 'Add New Hit' ?>
    </span>
  </div>
  <div class="p-4">
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Song Title *</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($hit['title'] ?? '') ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Duration (e.g. 3:33)</label>
          <input type="text" name="duration" class="form-control" placeholder="3:33" value="<?= htmlspecialchars($hit['duration'] ?? '') ?>">
        </div>
        <div class="col-md-8">
          <label class="form-label">Album</label>
          <input type="text" name="album" class="form-control" value="<?= htmlspecialchars($hit['album'] ?? '') ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Year Released</label>
          <input type="number" name="year_released" class="form-control" placeholder="1999" min="1990" max="2030" value="<?= htmlspecialchars($hit['year_released'] ?? '') ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Peak Chart Position</label>
          <input type="number" name="peak_chart" class="form-control" placeholder="1" min="1" max="200" value="<?= htmlspecialchars($hit['peak_chart'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($hit['description'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">YouTube URL</label>
          <input type="url" name="youtube_url" class="form-control" placeholder="https://youtube.com/watch?v=..." value="<?= htmlspecialchars($hit['youtube_url'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Spotify URL</label>
          <input type="url" name="spotify_url" class="form-control" placeholder="https://open.spotify.com/track/..." value="<?= htmlspecialchars($hit['spotify_url'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Album Cover Image</label>
          <?php if (!empty($hit['cover_image'])): ?>
          <div class="mb-2">
            <img src="<?= SITE_URL ?>/uploads/albums/<?= htmlspecialchars($hit['cover_image']) ?>"
                 style="height:64px;width:64px;object-fit:cover;border-radius:8px;border:1px solid rgba(201,168,76,.3);" alt="">
            <small class="text-muted ms-2">Current cover</small>
          </div>
          <?php endif; ?>
          <input type="file" name="cover_image" class="form-control" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="col-md-4">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)($hit['display_order'] ?? 0) ?>" min="0">
        </div>
        <div class="col-md-4 d-flex align-items-end gap-4">
          <div class="form-check form-switch">
            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" <?= !empty($hit['is_featured']) ? 'checked' : '' ?>>
            <label for="is_featured" class="form-check-label" style="color:#aaa;">Featured Hit</label>
          </div>
          <div class="form-check form-switch">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?= (!isset($hit['is_active']) || $hit['is_active']) ? 'checked' : '' ?>>
            <label for="is_active" class="form-check-label" style="color:#aaa;">Visible on Website</label>
          </div>
        </div>
        <div class="col-12 d-flex gap-2 mt-2">
          <button type="submit" class="btn-save"><i class="fas fa-save me-1"></i> <?= $isEdit ? 'Update Hit' : 'Save Hit' ?></button>
          <a href="index.php" class="btn-cancel text-decoration-none">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layout_footer.php'; ?>
