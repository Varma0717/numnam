document.addEventListener('DOMContentLoaded', () => {
  const autoDismiss = document.querySelectorAll('[data-auto-dismiss]');
  autoDismiss.forEach((node) => {
    window.setTimeout(() => {
      node.style.opacity = '0';
      node.style.transform = 'translateY(-6px)';
      node.style.transition = 'all .25s ease';
      window.setTimeout(() => node.remove(), 280);
    }, 3200);
  });
});
