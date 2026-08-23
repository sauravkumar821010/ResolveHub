</main>
<?php if (!isset($is_admin_area) || !$is_admin_area): ?>
<footer class="footer">
  <div class="container footer-inner">
    <span>© 2026 ResolveHub · Complaint Management Portal</span>
    <span>Submit · Track · Resolve</span>
  </div>
</footer>
<?php else: ?>
<footer class="admin-footer">ResolveHub · Complaint Management Portal · <?= date('Y') ?></footer>
</div></div>
<?php endif; ?>
<script>
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.dataset.confirm)) e.preventDefault();
  });
});
const revealItems = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window) {
  const observer = new IntersectionObserver(entries => entries.forEach(entry => {
    if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
  }), {threshold:.08});
  revealItems.forEach(el => observer.observe(el));
} else revealItems.forEach(el => el.classList.add('visible'));
</script>
</body>
</html>
