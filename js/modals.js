const Modals = (() => {

  function open(key) {
    const el = document.getElementById('modal-' + key);
    if (el) el.classList.add('open');
  }

  function close(key) {
    const el = document.getElementById('modal-' + key);
    if (el) el.classList.remove('open');
  }

  function closeAll() {
    document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'));
  }

  /**
   * Show a confirmation dialog.
   * @param {string} message  — The prompt text
   * @param {Function} onConfirm — Called when user clicks confirm
   */
  function confirm(message, onConfirm) {
    document.getElementById('confirm-msg').textContent = message;
    const btn = document.getElementById('confirm-ok');

    // Remove previous listener to avoid stacking
    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);

    newBtn.addEventListener('click', () => {
      onConfirm();
      close('confirm');
    });

    open('confirm');
  }

  // ── Close modal when clicking backdrop ──
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) {
      closeAll();
    }
  });

  return { open, close, closeAll, confirm };
})();
