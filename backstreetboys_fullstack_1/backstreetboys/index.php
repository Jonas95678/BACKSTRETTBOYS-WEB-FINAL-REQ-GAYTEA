<?php
require_once __DIR__ . '/config/database.php';
$pdo = getDB();

// ---- Fetch data ----
$members  = $pdo->query("SELECT * FROM members  WHERE is_active=1 ORDER BY display_order ASC")->fetchAll();
$tophits  = $pdo->query("SELECT * FROM top_hits WHERE is_active=1 ORDER BY display_order ASC")->fetchAll();
$history  = $pdo->query("SELECT * FROM history  WHERE is_active=1 ORDER BY event_year ASC, display_order ASC")->fetchAll();
$heroTitle    = getSetting('hero_title',    'Backstreet Boys');
$heroSubtitle = getSetting('hero_subtitle', 'The Legacy Lives On');
$aboutText    = getSetting('about_text',    '');
$siteTitle    = getSetting('site_title',    'Backstreet Boys');
$siteTagline  = getSetting('site_tagline',  '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($siteTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;900&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    :root {
      --gold: #c9a84c;
      --dark: #0a0a0a;
      --mid:  #1a1a2e;
      --card-bg: #16213e;
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#0a0a0a; color:#eee; font-family:'Open Sans',sans-serif; scroll-behavior:smooth; }

    /* === NAVBAR === */
    .bsb-navbar { background:transparent; transition:background .4s, box-shadow .4s; padding:18px 0; }
    .bsb-navbar.scrolled { background:rgba(10,10,10,.97); box-shadow:0 2px 20px rgba(0,0,0,.7); padding:10px 0; }
    .brand-bsb { font-family:'Montserrat',sans-serif; font-weight:900; font-size:1.8rem;
                 background:linear-gradient(135deg,#c9a84c,#ffd700); -webkit-background-clip:text;
                 -webkit-text-fill-color:transparent; letter-spacing:3px; }
    .navbar-nav .nav-link { color:#ddd !important; font-weight:600; letter-spacing:1px;
                            text-transform:uppercase; font-size:.85rem; margin:0 6px;
                            transition:color .2s; }
    .navbar-nav .nav-link:hover { color:#c9a84c !important; }

    /* === HERO === */
    .hero-section { min-height:100vh; background:linear-gradient(135deg,#0a0a0a 0%,#1a1a2e 50%,#0a0a0a 100%);
                    display:flex; align-items:center; position:relative; overflow:hidden; }
    .hero-section::before { content:''; position:absolute; inset:0;
                             background:url('assets/images/hero-bg.jpg') center/cover no-repeat; opacity:.18; }
    .hero-particles { position:absolute; inset:0; pointer-events:none; }
    .hero-content { position:relative; z-index:2; }
    .hero-badge { display:inline-block; background:rgba(201,168,76,.15); border:1px solid rgba(201,168,76,.4);
                  color:#c9a84c; padding:6px 20px; border-radius:30px; font-size:.8rem;
                  letter-spacing:3px; text-transform:uppercase; margin-bottom:24px; }
    .hero-title { font-family:'Montserrat',sans-serif; font-weight:900; font-size:clamp(3rem,9vw,7rem);
                  line-height:1.05; background:linear-gradient(135deg,#fff 0%,#c9a84c 50%,#ffd700 100%);
                  -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
    .hero-subtitle { font-size:1.3rem; color:#aaa; font-weight:300; letter-spacing:4px;
                     text-transform:uppercase; margin:16px 0 36px; }
    .btn-gold { background:linear-gradient(135deg,#c9a84c,#ffd700); color:#000;
                border:none; font-weight:700; padding:14px 36px; border-radius:50px;
                letter-spacing:1px; text-transform:uppercase; font-size:.9rem;
                transition:transform .2s, box-shadow .2s; }
    .btn-gold:hover { transform:translateY(-2px); box-shadow:0 8px 30px rgba(201,168,76,.45); color:#000; }
    .btn-outline-gold { border:2px solid #c9a84c; color:#c9a84c; background:transparent;
                        font-weight:700; padding:12px 32px; border-radius:50px; font-size:.9rem;
                        letter-spacing:1px; text-transform:uppercase; transition:all .2s; }
    .btn-outline-gold:hover { background:#c9a84c; color:#000; }
    .hero-stats { margin-top:60px; }
    .stat-item { text-align:center; }
    .stat-number { font-family:'Montserrat',sans-serif; font-weight:900; font-size:2.5rem; color:#c9a84c; }
    .stat-label  { font-size:.75rem; color:#888; letter-spacing:2px; text-transform:uppercase; }

    /* === SECTIONS === */
    .section-divider { width:60px; height:3px; background:linear-gradient(135deg,#c9a84c,#ffd700);
                       margin:16px auto 0; border-radius:2px; }
    .section-label { font-size:.75rem; letter-spacing:4px; color:#c9a84c; text-transform:uppercase;
                     font-weight:600; margin-bottom:12px; display:block; }

    /* === MEMBERS === */
    .members-section { background:linear-gradient(180deg,#0a0a0a,#12122a,#0a0a0a); padding:100px 0; }
    .member-card { background:linear-gradient(145deg,#16213e,#0f3460); border-radius:20px;
                   overflow:hidden; border:1px solid rgba(201,168,76,.15);
                   transition:transform .35s, box-shadow .35s; height:100%; cursor:pointer; }
    .member-card:hover { transform:translateY(-10px); box-shadow:0 25px 60px rgba(201,168,76,.2); }
    .member-photo { width:100%; height:280px; object-fit:cover; }
    .member-photo-placeholder { width:100%; height:280px; background:linear-gradient(135deg,#1a1a3e,#2d2d6e);
                                 display:flex; align-items:center; justify-content:center; font-size:4rem; color:#c9a84c; }
    .member-body { padding:24px; }
    .member-role { font-size:.75rem; letter-spacing:2px; color:#c9a84c; text-transform:uppercase; margin-bottom:4px; }
    .member-name { font-family:'Montserrat',sans-serif; font-weight:700; font-size:1.3rem; margin-bottom:8px; }
    .member-nickname { color:#888; font-size:.85rem; margin-bottom:14px; }
    .member-bio { font-size:.85rem; color:#aaa; line-height:1.7; display:-webkit-box;
                  -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

    /* === TOP HITS === */
    .tophits-section { background:#0a0a0a; padding:100px 0; }
    .hit-card { background:linear-gradient(145deg,#141414,#1e1e2e); border-radius:16px;
                border:1px solid rgba(255,255,255,.06); padding:28px 24px;
                transition:transform .3s, border-color .3s; display:flex; align-items:center; gap:20px; }
    .hit-card:hover { transform:translateY(-5px); border-color:rgba(201,168,76,.4); }
    .hit-rank { font-family:'Montserrat',sans-serif; font-weight:900; font-size:2.5rem;
                color:rgba(201,168,76,.25); min-width:48px; line-height:1; }
    .hit-cover { width:70px; height:70px; border-radius:10px; object-fit:cover; flex-shrink:0; }
    .hit-cover-placeholder { width:70px; height:70px; border-radius:10px; flex-shrink:0;
                              background:linear-gradient(135deg,#c9a84c,#8b6914);
                              display:flex; align-items:center; justify-content:center; font-size:1.6rem; }
    .hit-info { flex:1; }
    .hit-title { font-family:'Montserrat',sans-serif; font-weight:700; font-size:1.05rem; margin-bottom:4px; }
    .hit-album { font-size:.8rem; color:#888; }
    .hit-year  { font-size:.75rem; color:#c9a84c; font-weight:600; }
    .hit-duration { color:#888; font-size:.85rem; font-weight:600; }

    /* === HISTORY === */
    .history-section { background:linear-gradient(180deg,#0a0a0a,#0f0f24,#0a0a0a); padding:100px 0; }
    .timeline { position:relative; padding:40px 0; }
    .timeline::before { content:''; position:absolute; left:50%; top:0; bottom:0; width:2px;
                        background:linear-gradient(180deg,transparent,#c9a84c 15%,#c9a84c 85%,transparent);
                        transform:translateX(-50%); }
    .timeline-item { display:flex; margin-bottom:60px; position:relative; }
    .timeline-item:nth-child(odd)  { flex-direction:row; }
    .timeline-item:nth-child(even) { flex-direction:row-reverse; }
    .timeline-content { width:calc(50% - 50px); }
    .timeline-item:nth-child(odd)  .timeline-content { margin-right:50px; text-align:right; }
    .timeline-item:nth-child(even) .timeline-content { margin-left:50px; text-align:left; }
    .timeline-dot { position:absolute; left:50%; transform:translateX(-50%); z-index:2;
                    width:16px; height:16px; border-radius:50%; background:#c9a84c;
                    box-shadow:0 0 0 4px rgba(201,168,76,.25), 0 0 20px rgba(201,168,76,.5);
                    top:8px; }
    .timeline-year { font-family:'Montserrat',sans-serif; font-weight:900; font-size:1.5rem;
                     color:#c9a84c; margin-bottom:4px; }
    .timeline-title { font-weight:700; font-size:1rem; margin-bottom:8px; }
    .timeline-desc  { font-size:.85rem; color:#999; line-height:1.7; }
    .timeline-badge { display:inline-block; padding:3px 12px; border-radius:30px; font-size:.7rem;
                      font-weight:700; letter-spacing:1px; text-transform:uppercase;
                      background:rgba(201,168,76,.15); color:#c9a84c; border:1px solid rgba(201,168,76,.3);
                      margin-bottom:8px; }
    @media (max-width:768px) {
      .timeline::before { left:20px; }
      .timeline-item, .timeline-item:nth-child(even) { flex-direction:column; }
      .timeline-content,
      .timeline-item:nth-child(odd)  .timeline-content,
      .timeline-item:nth-child(even) .timeline-content {
        width:100%; margin:0 0 0 50px; text-align:left;
      }
      .timeline-dot { left:20px; }
    }

    /* === FOOTER === */
    .bsb-footer { background:#050505; border-top:1px solid rgba(201,168,76,.1); color:#888; }
    .bsb-footer a { color:#888 !important; transition:color .2s; }
    .bsb-footer a:hover { color:#c9a84c !important; }
  </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bsb-navbar" id="mainNav">
  <div class="container">
    <a class="navbar-brand" href="#home"><span class="brand-bsb">BSB</span></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#members">Members</a></li>
        <li class="nav-item"><a class="nav-link" href="#tophits">Top Hits</a></li>
        <li class="nav-item"><a class="nav-link" href="#history">History</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ===== HERO ===== -->
<section id="home" class="hero-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8 hero-content" data-aos="fade-up">
        <span class="hero-badge">Est. 1993 · Orlando, Florida</span>
        <h1 class="hero-title"><?= htmlspecialchars($heroTitle) ?></h1>
        <p class="hero-subtitle"><?= htmlspecialchars($heroSubtitle) ?></p>
        <div class="d-flex flex-wrap gap-3">
          <a href="#members" class="btn btn-gold">Meet the Members</a>
          <a href="#tophits" class="btn btn-outline-gold">Top Hits</a>
        </div>
        <div class="hero-stats row g-4 mt-2">
          <div class="col-auto stat-item">
            <div class="stat-number">100M+</div>
            <div class="stat-label">Records Sold</div>
          </div>
          <div class="col-auto stat-item">
            <div class="stat-number">30+</div>
            <div class="stat-label">Years Active</div>
          </div>
          <div class="col-auto stat-item">
            <div class="stat-number">5</div>
            <div class="stat-label">Members</div>
          </div>
          <div class="col-auto stat-item">
            <div class="stat-number"><?= count($tophits) ?>+</div>
            <div class="stat-label">Hit Songs</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== ABOUT ===== -->
<section class="py-5" style="background:#111;">
  <div class="container text-center" data-aos="fade-up">
    <span class="section-label">About</span>
    <h2 class="fw-bold fs-1 mb-4">The World's Best-Selling Boy Band</h2>
    <p class="text-muted mx-auto" style="max-width:750px;line-height:1.9;font-size:1.05rem;">
      <?= nl2br(htmlspecialchars($aboutText)) ?>
    </p>
  </div>
</section>

<!-- ===== MEMBERS ===== -->
<section id="members" class="members-section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="section-label">The Group</span>
      <h2 class="fw-bold fs-1 mb-2">Meet the Members</h2>
      <div class="section-divider mx-auto"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($members as $i => $m): ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
        <div class="member-card">
          <?php if (!empty($m['photo']) && file_exists(SITE_ROOT . '/uploads/members/' . $m['photo'])): ?>
            <img src="<?= SITE_URL ?>/uploads/members/<?= htmlspecialchars($m['photo']) ?>"
                 class="member-photo" alt="<?= htmlspecialchars($m['name']) ?>">
          <?php else: ?>
            <div class="member-photo-placeholder">
              <i class="fas fa-user"></i>
            </div>
          <?php endif; ?>
          <div class="member-body">
            <div class="member-role"><?= htmlspecialchars($m['role'] ?? '') ?></div>
            <div class="member-name"><?= htmlspecialchars($m['name']) ?></div>
            <?php if (!empty($m['nickname'])): ?>
            <div class="member-nickname">"<?= htmlspecialchars($m['nickname']) ?>"</div>
            <?php endif; ?>
            <p class="member-bio"><?= htmlspecialchars($m['bio'] ?? '') ?></p>
            <div class="mt-3 d-flex gap-2">
              <?php if (!empty($m['social_ig'])): ?>
              <a href="<?= htmlspecialchars($m['social_ig']) ?>" class="text-muted" target="_blank">
                <i class="fab fa-instagram"></i></a>
              <?php endif; ?>
              <?php if (!empty($m['social_tw'])): ?>
              <a href="<?= htmlspecialchars($m['social_tw']) ?>" class="text-muted" target="_blank">
                <i class="fab fa-twitter"></i></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== TOP HITS ===== -->
<section id="tophits" class="tophits-section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="section-label">Discography</span>
      <h2 class="fw-bold fs-1 mb-2">Top Hits</h2>
      <div class="section-divider mx-auto"></div>
    </div>
    <div class="row g-3">
      <?php foreach ($tophits as $rank => $hit): ?>
      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?= ($rank % 6) * 60 ?>">
        <div class="hit-card">
          <div class="hit-rank"><?= str_pad($rank + 1, 2, '0', STR_PAD_LEFT) ?></div>
          <?php if (!empty($hit['cover_image'])): ?>
            <img src="<?= SITE_URL ?>/uploads/albums/<?= htmlspecialchars($hit['cover_image']) ?>"
                 class="hit-cover" alt="">
          <?php else: ?>
            <div class="hit-cover-placeholder">🎵</div>
          <?php endif; ?>
          <div class="hit-info">
            <div class="hit-title"><?= htmlspecialchars($hit['title']) ?></div>
            <div class="hit-album"><?= htmlspecialchars($hit['album'] ?? '') ?></div>
            <div class="hit-year"><?= htmlspecialchars($hit['year_released'] ?? '') ?></div>
          </div>
          <div class="hit-duration"><?= htmlspecialchars($hit['duration'] ?? '') ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== HISTORY ===== -->
<section id="history" class="history-section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="section-label">Legacy</span>
      <h2 class="fw-bold fs-1 mb-2">Our History</h2>
      <div class="section-divider mx-auto"></div>
    </div>
    <div class="timeline">
      <?php foreach ($history as $i => $event): ?>
      <div class="timeline-item" data-aos="<?= ($i % 2 === 0) ? 'fade-right' : 'fade-left' ?>">
        <div class="timeline-content">
          <span class="timeline-badge"><?= htmlspecialchars($event['category']) ?></span>
          <div class="timeline-year"><?= htmlspecialchars($event['event_year']) ?></div>
          <div class="timeline-title"><?= htmlspecialchars($event['title']) ?></div>
          <p class="timeline-desc"><?= htmlspecialchars($event['description'] ?? '') ?></p>
        </div>
        <div class="timeline-dot"></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="bsb-footer py-5">
  <div class="container text-center">
    <span class="brand-bsb fs-2">BSB</span>
    <p class="text-muted small mt-3"><?= htmlspecialchars(getSetting('footer_text', '© 2024 BSB')) ?></p>
    <div class="mt-3">
      <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a>
      <a href="#" class="me-3"><i class="fab fa-twitter fa-lg"></i></a>
      <a href="#" class="me-3"><i class="fab fa-facebook fa-lg"></i></a>
      <a href="#"><i class="fab fa-youtube fa-lg"></i></a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 800, once: true });
  window.addEventListener('scroll', function () {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 80);
  });
</script>
</body>
</html>
