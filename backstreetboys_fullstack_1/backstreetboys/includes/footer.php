
<!-- ===== FOOTER ===== -->
<footer class="bsb-footer py-5">
  <div class="container text-center">
    <div class="mb-3">
      <span class="brand-bsb fs-3 fw-black">BSB</span>
    </div>
    <p class="text-muted small mb-2">
      <?= htmlspecialchars(getSetting('footer_text', '© 2024 Backstreet Boys Fan Site')) ?>
    </p>
    <div class="social-links mt-3">
      <a href="#" class="me-3 text-muted"><i class="fab fa-instagram fa-lg"></i></a>
      <a href="#" class="me-3 text-muted"><i class="fab fa-twitter fa-lg"></i></a>
      <a href="#" class="me-3 text-muted"><i class="fab fa-facebook fa-lg"></i></a>
      <a href="#" class="text-muted"><i class="fab fa-youtube fa-lg"></i></a>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 800, once: true });

  // Navbar scroll effect
  window.addEventListener('scroll', function () {
    const nav = document.getElementById('mainNav');
    if (window.scrollY > 80) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  });
</script>
</body>
</html>
