
</main><!-- end .main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-hide alerts
document.querySelectorAll('.auto-dismiss').forEach(el => {
  setTimeout(() => { el.style.transition='opacity .5s'; el.style.opacity='0';
    setTimeout(()=>el.remove(), 500); }, 3500);
});
// Confirm deletes
document.querySelectorAll('.confirm-delete').forEach(btn => {
  btn.addEventListener('click', function(e) {
    if (!confirm('Are you sure you want to delete this record? This cannot be undone.')) {
      e.preventDefault();
    }
  });
});
</script>
</body>
</html>
