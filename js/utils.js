const Utils = (() => {

  function formatPeso(amount) {
    return '₱' + Number(amount).toLocaleString('en-PH', {
      minimumFractionDigits: 2, maximumFractionDigits: 2
    });
  }

  function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
  }

  function todayValue() {
    return new Date().toISOString().slice(0, 10);
  }

  function stockStatus(product) {
    const qty = product.Stock_Quantity ?? product.stock_quantity ?? product.stock ?? 0;
    const min = product.Min_Stock_Level ?? product.min_stock_level ?? product.min ?? 0;
    if (qty === 0)  return { label: 'Out of Stock', cls: 'badge-out' };
    if (qty <= min) return { label: 'Low Stock',    cls: 'badge-low' };
    return                 { label: 'In Stock',     cls: 'badge-ok'  };
  }

  const $ = (id) => document.getElementById(id);

  function setHTML(id, html) {
    const el = $(id);
    if (el) el.innerHTML = html;
  }

  function toast(message, type = 'success') {
    const existing = document.querySelector('.ts-toast');
    if (existing) existing.remove();

    const colors = {
      success: '#00a82a',
      error:   '#a83200',
      warning: '#e07b00',
      info:    '#0042a9'
    };

    const t = document.createElement('div');
    t.className = 'ts-toast';
    t.textContent = message;
    Object.assign(t.style, {
      position:     'fixed',
      bottom:       '28px',
      right:        '28px',
      background:   '#ffffff',
      color:        colors[type] || colors.success,
      padding:      '13px 22px',
      borderRadius: '12px',
      fontSize:     '13px',
      fontWeight:   '600',
      fontFamily:   "'Exo 2', sans-serif",
      letterSpacing:'0.4px',
      boxShadow:    '10px 10px 20px #d1d1d1, -10px -10px 20px #ffffff',
      borderLeft:   `4px solid ${colors[type] || colors.success}`,
      zIndex:       '9999',
      animation:    'fadeIn 0.2s ease',
      maxWidth:     '320px'
    });

    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
  }

  function validate(fieldIds) {
    let valid = true;
    fieldIds.forEach(id => {
      const el = $(id);
      if (!el || !el.value.trim()) {
        if (el) {
          el.style.boxShadow = 'inset 6px 6px 12px #d1d1d1, inset -6px -6px 12px #ffffff, 0 0 0 2px rgba(168,50,0,.4)';
          setTimeout(() => { el.style.boxShadow = ''; }, 2000);
        }
        valid = false;
      }
    });
    return valid;
  }

  return { formatPeso, formatDate, todayValue, stockStatus, $, setHTML, toast, validate };
})();
