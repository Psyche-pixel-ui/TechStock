const Reports = (() => {

  async function render() {
    try {
      const [stockRes, lowRes, bestRes, summaryRes] = await Promise.all([
        API.Reports.getStockLevels(),
        API.Reports.getLowStock(),
        API.Reports.getBestMoved(),
        API.Reports.getSummary()
      ]);
      _renderStockLevels(stockRes.data);
      _renderLowStock(lowRes.data);
      _renderBestMoved(bestRes.data);
      _renderSummary(summaryRes.data);
    } catch (err) { Utils.toast('Failed to load reports: ' + err.message, 'error'); }
  }

  function _renderStockLevels(products) {
    if (!products.length) { Utils.setHTML('rpt-stock', '<div class="empty-state">No products yet.</div>'); return; }
    const max = Math.max(...products.map(p => p.Stock_Quantity), 1);
    Utils.setHTML('rpt-stock',
      products.map(p => `
        <div class="bar-row">
          <div class="bar-label" title="${p.Product_Name}">${p.Product_Name}</div>
          <div class="bar-track"><div class="bar-fill" style="width:${(p.Stock_Quantity/max)*100}%"></div></div>
          <div class="bar-val">${p.Stock_Quantity}</div>
        </div>`).join('')
    );
  }

  function _renderLowStock(items) {
    if (!items.length) {
      Utils.setHTML('rpt-low',
      `<div class="empty-state">All stock levels are healthy.</div>`);
      return;
    }
    Utils.setHTML('rpt-low',
      items.map(p => {
        const isOut = p.Stock_Quantity === 0;
        return `
          <div class="alert ${isOut ? 'alert-danger' : 'alert-warn'}">
            <div>
              <strong>${p.Product_Name}</strong>
              <span style="margin-left:8px;font-size:12px">
               ${isOut 
                  ? 'Out of Stock' 
                  : `${p.Stock_Quantity} left - minimum is ${p.Min_Stock_Level}`}
             </span>
            </div>
          </div>`;
      }).join('')
    );
  }

  function _renderBestMoved(items) {
    if (!items.length) { Utils.setHTML('rpt-best', '<div class="empty-state">No stock-out records yet.</div>'); return; }
    const max = items[0].Total_Out;
    Utils.setHTML('rpt-best',
      items.map(p => `
        <div class="bar-row">
          <div class="bar-label" title="${p.Product_Name}">${p.Product_Name}</div>
          <div class="bar-track"><div class="bar-fill bar-fill-red" style="width:${(p.Total_Out/max)*100}%"></div></div>
          <div class="bar-val">${p.Total_Out}</div>
        </div>`).join('')
    );
  }

  function _renderSummary(data) {
  Utils.setHTML('rpt-summary', `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div class="stat-card">
        <div class="stat-val txt-green">${data.total_in}</div>
        <div class="stat-lbl">Total Units In</div>
      </div>

      <div class="stat-card">
        <div class="stat-val txt-red">${data.total_out}</div>
        <div class="stat-lbl">Total Units Out</div>
      </div>

      <div class="stat-card">
        <div class="stat-val">${data.total_txns}</div>
        <div class="stat-lbl">Total Transactions</div>
      </div>

      <div class="stat-card">
        <div class="stat-val txt-warning" style="font-size:22px">
          ${Utils.formatPeso(data.inventory_value)}
        </div>
        <div class="stat-lbl">Inventory Value</div>
      </div>
    </div>`
  );
}

  function print() { window.print(); }

  Nav.register('reports', render);
  return { render, print };
})();