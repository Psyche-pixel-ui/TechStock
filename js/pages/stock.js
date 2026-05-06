const Stock = (() => {

  let _all = [], _activeFilter = 'All';

  async function render() {
    try {
      const res = await API.Transactions.getAll();
      _all = res.data;
      _draw();
    } catch (err) { Utils.toast('Failed to load transactions: ' + err.message, 'error'); }
  }

  function _draw() {
    const filtered = _activeFilter === 'All' ? _all : _all.filter(t => t.Type === _activeFilter);
    if (!filtered.length) {
      Utils.setHTML('txn-tbody', `
          <tr>
            <td colspan="7">
              <div class="empty-state">No transactions recorded yet.</div>
            </td>
          </tr>`);
      return;
    }
    Utils.setHTML('txn-tbody',
      filtered.map((tx, i) => {
        const isIn = tx.Type === 'Stock In';
        return `
          <tr>
            <td class="txt-dim">${i + 1}</td>
            <td>${Utils.formatDate(tx.Transaction_Date)}</td>
            <td class="txt-bright">${tx.Product_Name}</td>
            <td><span class="badge ${isIn ? 'badge-in' : 'badge-out-tx'}">${tx.Type}</span></td>
            <td style="color:${isIn ? 'var(--green)' : 'var(--red)'};font-weight:700">
              ${isIn ? '+' : '-'}${tx.Quantity}
            </td>
            <td class="txt-dim">${tx.Supplier_Name || 'N/A'}</td>
            <td class="txt-dim">${tx.Remarks || 'N/A'}</td>
          </tr>`;
      }).join('')
    );
  }

  async function _populateProducts(selectId) {
    const res = await API.Products.getAll();
    Utils.$(selectId).innerHTML =
      '<option value="">Select a product</option>' +
      res.data.map(p => 
      `<option value="${p.Product_ID}">
         ${p.Product_Name} - Stock: ${p.Stock_Quantity}
       </option>`
    ).join('');
  }

  async function _populateSuppliers(selectId) {
    const res = await API.Suppliers.getAll();
    Utils.$(selectId).innerHTML =
      '<option value="">Select a supplier</option>' +
      res.data.map(s => `<option value="${s.Supplier_ID}">${s.Supplier_Name}</option>`).join('');
  }

  async function openStockIn() {
    try {
      await Promise.all([_populateProducts('si-product'), _populateSuppliers('si-supplier')]);
      Utils.$('si-qty').value = ''; Utils.$('si-date').value = Utils.todayValue(); Utils.$('si-remarks').value = '';
      Modals.open('stockin');
    } catch (err) { Utils.toast('Error: ' + err.message, 'error'); }
  }

  async function saveStockIn() {
    if (!Utils.validate(['si-product','si-supplier','si-qty','si-date'])) {
      Utils.toast('Please fill in all required fields.', 'error'); return;
    }
    const qty = parseInt(Utils.$('si-qty').value);
    if (qty <= 0) { Utils.toast('Quantity must be greater than 0.', 'error'); return; }
    try {
      await API.Transactions.stockIn({
        Product_ID: parseInt(Utils.$('si-product').value),
        Supplier_ID: parseInt(Utils.$('si-supplier').value),
        Quantity: qty,
        Transaction_Date: Utils.$('si-date').value,
        Remarks: Utils.$('si-remarks').value.trim()
      });
      Utils.toast('Stock In recorded successfully.', 'success');
      Modals.close('stockin');
      render();
    } catch (err) { Utils.toast('Error: ' + err.message, 'error'); }
  }

  async function openStockOut() {
    try {
      await _populateProducts('so-product');
      Utils.$('so-qty').value = ''; Utils.$('so-date').value = Utils.todayValue(); Utils.$('so-remarks').value = '';
      Modals.open('stockout');
    } catch (err) { Utils.toast('Error: ' + err.message, 'error'); }
  }

  async function saveStockOut() {
    if (!Utils.validate(['so-product','so-qty','so-date'])) {
      Utils.toast('Please fill in all required fields.', 'error'); return;
    }
    const qty = parseInt(Utils.$('so-qty').value);
    if (qty <= 0) { Utils.toast('Quantity must be greater than 0.', 'error'); return; }
    try {
      await API.Transactions.stockOut({
        Product_ID: parseInt(Utils.$('so-product').value),
        Quantity: qty,
        Transaction_Date: Utils.$('so-date').value,
        Remarks: Utils.$('so-remarks').value.trim()
      });
      Utils.toast('Stock Out recorded successfully.', 'warning');
      Modals.close('stockout');
      render();
    } catch (err) { Utils.toast('Error: ' + err.message, 'error'); }
  }

  function filterType(type, btn) {
    _activeFilter = type;
    document.querySelectorAll('#txn-tabs .tab-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    _draw();
  }

  Nav.register('stock', render);
  return { render, openStockIn, saveStockIn, openStockOut, saveStockOut, filterType };
})();
