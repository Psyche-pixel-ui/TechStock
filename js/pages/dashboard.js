const Dashboard = (() => {

  async function render() {
    try {
      const [summaryRes, recentRes] = await Promise.all([
        API.Reports.getSummary(),
        API.Reports.getRecentTransactions(6)
      ]);
      _renderStats(summaryRes.data);
      _renderAlerts();
      _renderRecentTransactions(recentRes.data);
      Utils.$('dash-date').textContent = new Date().toLocaleDateString('en-PH', { dateStyle: 'full' });
    } catch (err) { Utils.toast('Failed to load dashboard: ' + err.message, 'error'); }
  }

  function _renderStats(data) {
    Utils.$('st-products').textContent  = data.total_products;
    Utils.$('st-low').textContent       = data.low_stock;
    Utils.$('st-out').textContent       = data.out_of_stock;
    Utils.$('st-suppliers').textContent = data.total_suppliers;
  }

  async function _renderAlerts() {
    try {
      const res = await API.Reports.getLowStock();
      const items = res.data;

      if (!items.length) {
        Utils.setHTML('dash-alerts', `
          <div class="empty-state">
            All stock levels are healthy. No alerts at this time.
          </div>`);
        return;
      }

      Utils.setHTML('dash-alerts',
        items.map(p => {
          const isOut = p.Stock_Quantity === 0;

          return `
            <div class="alert ${isOut ? 'alert-danger' : 'alert-warn'}">
              <div>
                <strong>${p.Product_Name}</strong>
                ${isOut 
                  ? 'Out of Stock' 
                  : `Only ${p.Stock_Quantity} unit(s) left (minimum: ${p.Min_Stock_Level})`}
              </div>
            </div>`;
        }).join('')
      );

    } catch (err) {
      Utils.setHTML('dash-alerts', `<div class="empty-state">Could not load alerts.</div>`);
    }
  }

  function _renderRecentTransactions(rows) {
    if (!rows || !rows.length) {
      Utils.setHTML('dash-txns', `<tr><td colspan="5"><div class="empty-state">No transactions recorded yet.</div></td></tr>`);
      return;
    }
    Utils.setHTML('dash-txns',
      rows.map(tx => {
        const isIn = tx.Type === 'Stock In';
        return `
          <tr>
            <td>${Utils.formatDate(tx.Transaction_Date)}</td>
            <td class="txt-bright">${tx.Product_Name}</td>
            <td><span class="badge ${isIn ? 'badge-in' : 'badge-out-tx'}">${tx.Type}</span></td>
            <td style="color:${isIn ? 'var(--green)' : 'var(--red)'};font-weight:700">
              ${isIn ? '+' : '-'}${tx.Quantity}
            </td>
            <td class="txt-dim">${tx.Remarks || ''}</td>
          </tr>`;
      }).join('')
    );
  }

  Nav.register('dashboard', render);
  return { render };
})();