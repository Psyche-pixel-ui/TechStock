const Nav = (() => {

  // Map page keys to their render functions (registered by each page module)
  const _renderers = {};

  /** Register a render function for a page key */
  function register(key, fn) {
    _renderers[key] = fn;
  }

  /** Navigate to a page by key */
  function go(key) {
    // Hide all pages
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));

    // Deactivate all nav buttons
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));

    // Show target page
    const page = document.getElementById('page-' + key);
    if (page) page.classList.add('active');

    // Activate matching nav button
    const btn = document.querySelector(`.nav-btn[data-page="${key}"]`);
    if (btn) btn.classList.add('active');

    // Call registered renderer if available
    if (_renderers[key]) _renderers[key]();

    // Close any open modals on navigation
    Modals.closeAll();
  }

  /** Initialize nav buttons from data-page attributes */
  function init() {
    document.querySelectorAll('.nav-btn[data-page]').forEach(btn => {
      btn.addEventListener('click', () => go(btn.dataset.page));
    });

    // Default page on load
    go('dashboard');
  }

  return { go, register, init };
})();
