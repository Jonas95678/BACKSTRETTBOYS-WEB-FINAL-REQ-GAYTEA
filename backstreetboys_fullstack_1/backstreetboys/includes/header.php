<?php
require_once __DIR__ . '/../config/database.php';
$siteTitle   = getSetting('site_title',   'Backstreet Boys');
$siteTagline = getSetting('site_tagline', 'The Legacy Lives On');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($siteTitle) ?></title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Site CSS -->
  <link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bsb-navbar" id="mainNav">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= SITE_URL ?>/index.php">
      <span class="brand-bsb">BSB</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>/index.php#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>/index.php#members">Members</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>/index.php#tophits">Top Hits</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>/index.php#history">History</a></li>
      </ul>
    </div>
  </div>
</nav>
