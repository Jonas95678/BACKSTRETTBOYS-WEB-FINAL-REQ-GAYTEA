<?php
// Must be included AFTER auth_guard and after $pageTitle is set
$adminUser  = $_SESSION['admin_user'] ?? 'Admin';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));

if (!defined('SITE_ROOT')) define('SITE_ROOT', dirname(__DIR__));
if (!defined('SITE_URL'))  define('SITE_URL',  'http://localhost/backstreetboys');

function navActive(string $page, string $dir = ''): string {
    global $currentPage, $currentDir;
    if ($dir && $currentDir === $dir) return 'active';
    if (!$dir && $currentPage === $page) return 'active';
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> – BSB Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root { --gold:#c9a84c; --gold-light:#ffd700; --sidebar-bg:#0d0d1a; --sidebar-width:260px;
            --topbar-h:64px; --body-bg:#111122; --card-bg:#16213e; --border:rgba(255,255,255,.07); }
    *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:'Inter',sans-serif; background:var(--body-bg); color:#e0e0e0; display:flex; min-height:100vh; overflow-x:hidden; }
    .sidebar { width:var(--sidebar-width); background:var(--sidebar-bg); border-right:1px solid var(--border);
               display:flex; flex-direction:column; position:fixed; top:0; left:0; height:100vh; z-index:100; transition:transform .3s; }
    .sidebar-logo { padding:22px 24px; border-bottom:1px solid var(--border); }
    .brand-bsb { font-family:'Montserrat',sans-serif; font-weight:900; font-size:1.7rem; letter-spacing:4px;
                 background:linear-gradient(135deg,#c9a84c,#ffd700); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
    .brand-sub { font-size:.65rem; color:#555; letter-spacing:3px; text-transform:uppercase; }
    .sidebar-nav { flex:1; overflow-y:auto; padding:20px 12px; }
    .nav-section-label { font-size:.65rem; color:#555; letter-spacing:2.5px; text-transform:uppercase; padding:16px 12px 6px; font-weight:600; }
    .sidebar-link { display:flex; align-items:center; gap:12px; padding:11px 14px; border-radius:10px;
                    color:#aaa; text-decoration:none; font-size:.88rem; font-weight:500; transition:background .2s,color .2s; margin-bottom:2px; }
    .sidebar-link i { width:18px; text-align:center; font-size:.9rem; }
    .sidebar-link:hover { background:rgba(201,168,76,.1); color:#c9a84c; }
    .sidebar-link.active { background:linear-gradient(135deg,rgba(201,168,76,.2),rgba(255,215,0,.1)); color:#ffd700; border:1px solid rgba(201,168,76,.2); }
    .sidebar-footer { padding:16px; border-top:1px solid var(--border); }
    .sidebar-user { display:flex; align-items:center; gap:10px; }
    .user-avatar { width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#c9a84c,#ffd700);
                   display:flex; align-items:center; justify-content:center; color:#000; font-weight:700; font-size:.85rem; }
    .user-name { font-size:.85rem; font-weight:600; color:#ddd; }
    .user-role { font-size:.7rem; color:#666; }
    .btn-logout { margin-left:auto; background:none; border:none; color:#666; cursor:pointer; transition:color .2s; }
    .btn-logout:hover { color:#e74c3c; }
    .topbar { height:var(--topbar-h); background:var(--sidebar-bg); border-bottom:1px solid var(--border);
              display:flex; align-items:center; padding:0 28px; gap:16px;
              position:fixed; top:0; left:var(--sidebar-width); right:0; z-index:90; }
    .page-title-bar { font-family:'Montserrat',sans-serif; font-weight:700; font-size:1.1rem; color:#fff; }
    .topbar-actions { margin-left:auto; display:flex; align-items:center; gap:12px; }
    .btn-view-site { background:linear-gradient(135deg,#c9a84c,#ffd700); color:#000; border:none;
                     padding:8px 20px; border-radius:8px; font-weight:700; font-size:.82rem;
                     text-decoration:none; display:flex; align-items:center; gap:6px; transition:transform .2s,box-shadow .2s; }
    .btn-view-site:hover { transform:translateY(-1px); box-shadow:0 4px 18px rgba(201,168,76,.4); color:#000; }
    .main-content { margin-left:var(--sidebar-width); margin-top:var(--topbar-h); flex:1; padding:32px; min-height:calc(100vh - var(--topbar-h)); }
    .stat-card { background:var(--card-bg); border-radius:16px; padding:24px 22px; border:1px solid var(--border); transition:transform .25s,box-shadow .25s; }
    .stat-card:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,.4); }
    .stat-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; margin-bottom:16px; }
    .stat-value { font-family:'Montserrat',sans-serif; font-weight:900; font-size:2rem; color:#fff; }
    .stat-label { font-size:.78rem; color:#888; letter-spacing:1px; text-transform:uppercase; margin-top:2px; }
    .content-card { background:var(--card-bg); border-radius:16px; border:1px solid var(--border); overflow:hidden; }
    .content-card-header { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
    .content-card-title { font-family:'Montserrat',sans-serif; font-weight:700; font-size:.95rem; color:#fff; }
    .content-card-body { padding:0; }
    .bsb-table { width:100%; border-collapse:collapse; font-size:.875rem; }
    .bsb-table th { padding:12px 20px; border-bottom:1px solid var(--border); color:#888; font-size:.72rem; letter-spacing:1.5px; text-transform:uppercase; font-weight:600; background:rgba(0,0,0,.2); }
    .bsb-table td { padding:14px 20px; border-bottom:1px solid rgba(255,255,255,.04); color:#ccc; vertical-align:middle; }
    .bsb-table tr:last-child td { border-bottom:none; }
    .bsb-table tr:hover td { background:rgba(255,255,255,.03); }
    .table-thumb { width:44px; height:44px; border-radius:8px; object-fit:cover; }
    .table-thumb-ph { width:44px; height:44px; border-radius:8px; background:linear-gradient(135deg,#1a1a3e,#2d2d6e); display:flex; align-items:center; justify-content:center; color:#c9a84c; font-size:1.1rem; }
    .badge-active   { background:rgba(46,213,115,.15); color:#2ed573; border:1px solid rgba(46,213,115,.3); padding:3px 10px; border-radius:20px; font-size:.7rem; font-weight:700; }
    .badge-inactive { background:rgba(255,71,87,.12); color:#ff4757; border:1px solid rgba(255,71,87,.3); padding:3px 10px; border-radius:20px; font-size:.7rem; font-weight:700; }
    .badge-gold     { background:rgba(201,168,76,.15); color:#c9a84c; border:1px solid rgba(201,168,76,.3); padding:3px 10px; border-radius:20px; font-size:.7rem; font-weight:700; }
    .btn-add { background:linear-gradient(135deg,#c9a84c,#ffd700); color:#000; border:none; padding:9px 20px; border-radius:9px; font-weight:700; font-size:.82rem; transition:transform .2s; display:flex; align-items:center; gap:6px; }
    .btn-add:hover { transform:translateY(-1px); color:#000; }
    .btn-action { background:none; border:none; padding:5px 8px; border-radius:7px; cursor:pointer; font-size:.85rem; transition:background .2s,color .2s; }
    .btn-edit   { color:#74b9ff; } .btn-edit:hover   { background:rgba(116,185,255,.12); }
    .btn-delete { color:#ff6b81; } .btn-delete:hover { background:rgba(255,107,129,.12); }
    .btn-toggle { color:#a29bfe; } .btn-toggle:hover { background:rgba(162,155,254,.12); }
    .form-control,.form-select { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); color:#e0e0e0; border-radius:10px; padding:11px 14px; font-size:.88rem; }
    .form-control:focus,.form-select:focus { background:rgba(255,255,255,.07); border-color:#c9a84c; box-shadow:0 0 0 3px rgba(201,168,76,.15); color:#e0e0e0; }
    .form-control::placeholder { color:#555; }
    .form-label { font-size:.82rem; color:#aaa; font-weight:600; letter-spacing:.3px; margin-bottom:6px; }
    .form-select option { background:#16213e; color:#e0e0e0; }
    textarea.form-control { resize:vertical; min-height:100px; }
    .btn-save { background:linear-gradient(135deg,#c9a84c,#ffd700); color:#000; border:none; padding:9px 24px; border-radius:9px; font-weight:700; font-size:.88rem; }
    .btn-save:hover { color:#000; }
    .btn-cancel { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); color:#aaa; padding:9px 20px; border-radius:9px; font-size:.88rem; }
    .btn-cancel:hover { background:rgba(255,255,255,.1); color:#fff; }
    .alert-success-custom { background:rgba(46,213,115,.12); border:1px solid rgba(46,213,115,.25); color:#2ed573; padding:12px 18px; border-radius:10px; font-size:.87rem; }
    .alert-error-custom   { background:rgba(255,71,87,.1); border:1px solid rgba(255,71,87,.25); color:#ff4757; padding:12px 18px; border-radius:10px; font-size:.87rem; }
    .sidebar-toggle { display:none; background:none; border:none; color:#aaa; font-size:1.3rem; cursor:pointer; }
    .form-check-input:checked { background-color:#c9a84c; border-color:#c9a84c; }
    .form-check-input { background-color:rgba(255,255,255,.1); border-color:rgba(255,255,255,.2); }
    @media (max-width:991px) {
      .sidebar { transform:translateX(-100%); } .sidebar.open { transform:translateX(0); }
      .main-content { margin-left:0; } .topbar { left:0; } .sidebar-toggle { display:block; }
    }
  </style>
</head>
<body>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="brand-bsb">BSB</div>
    <div class="brand-sub">Admin Panel</div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section-label">Main</div>
    <a href="<?= SITE_URL ?>/dashboard/index.php" class="sidebar-link <?= navActive('index','dashboard') ?>"><i class="fas fa-chart-pie"></i> Dashboard</a>
    <a href="<?= SITE_URL ?>/index.php" target="_blank" class="sidebar-link"><i class="fas fa-external-link-alt"></i> View Website</a>
    <div class="nav-section-label">Content</div>
    <a href="<?= SITE_URL ?>/dashboard/members/index.php" class="sidebar-link <?= navActive('index','members') ?>"><i class="fas fa-users"></i> Members</a>
    <a href="<?= SITE_URL ?>/dashboard/tophits/index.php" class="sidebar-link <?= navActive('index','tophits') ?>"><i class="fas fa-music"></i> Top Hits</a>
    <a href="<?= SITE_URL ?>/dashboard/history/index.php" class="sidebar-link <?= navActive('index','history') ?>"><i class="fas fa-history"></i> History</a>
    <div class="nav-section-label">Settings</div>
    <a href="<?= SITE_URL ?>/dashboard/settings.php" class="sidebar-link <?= navActive('settings') ?>"><i class="fas fa-cog"></i> Site Settings</a>
  </nav>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="user-avatar"><?= strtoupper(substr($adminUser,0,1)) ?></div>
      <div><div class="user-name"><?= htmlspecialchars($adminUser) ?></div><div class="user-role">Administrator</div></div>
      <a href="<?= SITE_URL ?>/dashboard/logout.php" class="btn-logout ms-auto" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
    </div>
  </div>
</aside>
<div class="topbar">
  <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fas fa-bars"></i></button>
  <div class="page-title-bar"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></div>
  <div class="topbar-actions">
    <a href="<?= SITE_URL ?>/index.php" target="_blank" class="btn-view-site"><i class="fas fa-eye"></i> View Site</a>
  </div>
</div>
<main class="main-content">
