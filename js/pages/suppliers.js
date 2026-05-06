const Suppliers = (() => {

  async function render() {
    try {
      const res = await API.Suppliers.getAll();
      _draw(res.data);
    } catch (err) { Utils.toast('Failed to load suppliers: ' + err.message, 'error'); }
  }

  function _draw(all) {
    if (!all.length) {
      Utils.setHTML('supplier-tbody', `
       <tr>
        <td colspan="6">
          <div class="empty-state">
           No suppliers added yet.
          </div>
        </td>
      </tr>`);
      return;
    }
    Utils.setHTML('supplier-tbody',
      all.map((s, i) => `
        <tr>
          <td class="txt-dim">${i + 1}</td>
          <td class="txt-bright">${s.Supplier_Name}</td>
          <td>${s.Contact_Number}</td>
          <td class="txt-dim">${s.Email_Address || 'N/A'}</td>
          <td class="txt-dim">${s.Address || 'N/A'}</td>
          <td>
            <div class="tbl-actions">
              <button class="btn btn-flat btn-xs txt-blue" onclick="Suppliers.openEdit(${s.Supplier_ID})">Edit</button>
              <button class="btn btn-flat btn-xs txt-red"  onclick="Suppliers.confirmDelete(${s.Supplier_ID}, '${s.Supplier_Name.replace(/'/g,"\\'")}')">Delete</button>
            </div>
          </td>
        </tr>`).join('')
    );
  }

  function openAdd() {
    Utils.$('supplier-modal-title').textContent = 'Add Supplier';
    Utils.$('edit-supplier-id').value = '';
    ['fs-name','fs-contact','fs-email','fs-address'].forEach(id => { const el = Utils.$(id); if (el) el.value = ''; });
    Modals.open('supplier');
  }

  async function openEdit(id) {
    try {
      const res = await API.Suppliers.getById(id);
      const s   = res.data;
      Utils.$('supplier-modal-title').textContent = 'Edit Supplier';
      Utils.$('edit-supplier-id').value = s.Supplier_ID;
      Utils.$('fs-name').value    = s.Supplier_Name;
      Utils.$('fs-contact').value = s.Contact_Number;
      Utils.$('fs-email').value   = s.Email_Address || '';
      Utils.$('fs-address').value = s.Address || '';
      Modals.open('supplier');
    } catch (err) { Utils.toast('Could not load supplier: ' + err.message, 'error'); }
  }

  async function save() {
    if (!Utils.validate(['fs-name','fs-contact'])) {
      Utils.toast('Supplier Name and Contact are required.', 'error'); return;
    }
    const data = {
      Supplier_Name:  Utils.$('fs-name').value.trim(),
      Contact_Number: Utils.$('fs-contact').value.trim(),
      Email_Address:  Utils.$('fs-email').value.trim(),
      Address:        Utils.$('fs-address').value.trim()
    };
    const editId = parseInt(Utils.$('edit-supplier-id').value);
    try {
      if (editId) { await API.Suppliers.update(editId, data); Utils.toast('Supplier updated successfully.', 'success'); }
      else        { await API.Suppliers.create(data);         Utils.toast('Supplier added successfully.', 'success'); }
      Modals.close('supplier');
      render();
    } catch (err) { Utils.toast('Error: ' + err.message, 'error'); }
  }

  function confirmDelete(id, name) {
    Modals.confirm(`Are you sure you want to delete supplier "${name}"?`, async () => {
      try {
        await API.Suppliers.delete(id);
        Utils.toast('Supplier deleted.', 'warning');
        render();
      } catch (err) { Utils.toast('Error: ' + err.message, 'error'); }
    });
  }

  Nav.register('suppliers', render);
  return { render, openAdd, openEdit, save, confirmDelete };
})();
