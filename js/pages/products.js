const Products = (() => {

  let _all = [], _searchQuery = '', _activeCategory = 'All';

  async function render() {
    try {
      const res = await API.Products.getAll();
      _all = res.data;
      _draw();
    } catch (err) { Utils.toast('Failed to load products: ' + err.message, 'error'); }
  }

  function _draw() {
    const filtered = _all.filter(p => {
      const matchCat    = _activeCategory === 'All' || p.Category === _activeCategory;
      const matchSearch = p.Product_Name.toLowerCase().includes(_searchQuery.toLowerCase()) ||
                          p.Category.toLowerCase().includes(_searchQuery.toLowerCase());
      return matchCat && matchSearch;
    });

    if (!filtered.length) {
      Utils.setHTML('product-tbody', `<tr><td colspan="8"><div class="empty-state"><svg viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>No products found.</div></td></tr>`);
      return;
    }

    Utils.setHTML('product-tbody',
      filtered.map((p, i) => {
        const { label, cls } = Utils.stockStatus(p);
        return `
          <tr>
            <td class="txt-dim">${i + 1}</td>
            <td class="txt-bright">${p.Product_Name}</td>
            <td><span class="badge badge-cat">${p.Category}</span></td>
            <td>${Utils.formatPeso(p.Price)}</td>
            <td><strong class="txt-bright">${p.Stock_Quantity}</strong></td>
            <td class="txt-dim">${p.Min_Stock_Level}</td>
            <td><span class="badge ${cls}">${label}</span></td>
            <td>
              <div class="tbl-actions">
                <button class="btn btn-flat btn-xs txt-blue" onclick="Products.openEdit(${p.Product_ID})">Edit</button>
                <button class="btn btn-flat btn-xs txt-red" onclick="Products.confirmDelete(${p.Product_ID}, '${p.Product_Name.replace(/'/g,"\\'")}')">Delete</button>
              </div>
            </td>
          </tr>`;
      }).join('')
    );
  }

  function openAdd() {
    Utils.$('product-modal-title').textContent = 'Add Product';
    Utils.$('edit-product-id').value = '';
    ['f-name','f-cat','f-price','f-stock','f-min'].forEach(id => { const el = Utils.$(id); if (el) el.value = ''; });
    Modals.open('product');
  }

  async function openEdit(id) {
    try {
      const res = await API.Products.getById(id);
      const p   = res.data;
      Utils.$('product-modal-title').textContent = 'Edit Product';
      Utils.$('edit-product-id').value = p.Product_ID;
      Utils.$('f-name').value          = p.Product_Name;
      Utils.$('f-cat').value           = p.Category;
      Utils.$('f-price').value         = p.Price;
      Utils.$('f-stock').value         = p.Stock_Quantity;
      Utils.$('f-min').value           = p.Min_Stock_Level;
      Modals.open('product');
    } catch (err) { Utils.toast('Could not load product: ' + err.message, 'error'); }
  }

  async function save() {
    if (!Utils.validate(['f-name','f-cat','f-price','f-stock','f-min'])) {
      Utils.toast('Please fill in all required fields.', 'error'); return;
    }
    const data = {
      Product_Name:    Utils.$('f-name').value.trim(),
      Category:        Utils.$('f-cat').value,
      Price:           parseFloat(Utils.$('f-price').value),
      Stock_Quantity:  parseInt(Utils.$('f-stock').value),
      Min_Stock_Level: parseInt(Utils.$('f-min').value)
    };
    const editId = parseInt(Utils.$('edit-product-id').value);
    try {
      if (editId) { await API.Products.update(editId, data); Utils.toast('Product updated successfully.', 'success'); }
      else        { await API.Products.create(data);         Utils.toast('Product added successfully.', 'success'); }
      Modals.close('product');
      render();
    } catch (err) { Utils.toast('Error: ' + err.message, 'error'); }
  }

  function confirmDelete(id, name) {
    Modals.confirm(`Are you sure you want to delete "${name}"?`, async () => {
      try {
        await API.Products.delete(id);
        Utils.toast('Product deleted.', 'warning');
        render();
      } catch (err) { Utils.toast('Error: ' + err.message, 'error'); }
    });
  }

  function filterCat(cat, btn) {
    _activeCategory = cat;
    document.querySelectorAll('#cat-tabs .tab-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    _draw();
  }

  function filterSearch(query) { _searchQuery = query; _draw(); }

  Nav.register('products', render);
  return { render, openAdd, openEdit, save, confirmDelete, filterCat, filterSearch };
})();
