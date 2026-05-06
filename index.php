<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechStock — RavenTech Inventory System</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&family=Exo+2:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/variables.css">
  <link rel="stylesheet" href="css/base.css">
  <link rel="stylesheet" href="css/components.css">
</head>
<body>
<div class="app">

  <nav class="sidebar">
    <div class="logo">
      TECH<span>STOCK</span>
      <small> RAVENTECH &middot; INVENTORY</small>
    </div>

    <button class="nav-btn" data-page="dashboard">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </button>
    <button class="nav-btn" data-page="products">
      <svg viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      Products
    </button>
    <button class="nav-btn" data-page="stock">
      <svg viewBox="0 0 24 24"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
      Stock In / Out
    </button>
    <button class="nav-btn" data-page="suppliers">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Suppliers
    </button>
    <button class="nav-btn" data-page="reports">
      <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Reports
    </button>

    <div class="sidebar-spacer"></div>
    <div class="sidebar-ver"> Phase 1 </div>
  </nav>

  <main class="main">

<section class="page" id="page-dashboard">
      <div class="page-hd">
        <div class="page-title">
          <span>Dashboard</span>
        </div>
        <div id="dash-date" style="font-size:13px;color:var(--text-dim);font-weight:500"></div>
      </div>

      <div class="stat-grid">
        <div class="stat-card">
          <div class="stat-val txt-bright" id="st-products">—</div>
          <div class="stat-lbl">Total Products</div>
        </div>
        <div class="stat-card">
          <div class="stat-val txt-warning" id="st-low">—</div>
          <div class="stat-lbl">Low Stock Items</div>
        </div>
        <div class="stat-card">
          <div class="stat-val txt-red" id="st-out">—</div>
          <div class="stat-lbl">Out of Stock</div>
        </div>
        <div class="stat-card">
          <div class="stat-val txt-green" id="st-suppliers">—</div>
          <div class="stat-lbl">Suppliers</div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Stock Alerts</div>
        <div id="dash-alerts"></div>
      </div>

      <div class="card">
        <div class="card-title">Recent Transactions</div>
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>Date</th><th>Product</th><th>Type</th><th>Quantity</th><th>Remarks</th></tr></thead>
            <tbody id="dash-txns"></tbody>
          </table>
        </div>
      </div>
    </section>

    <section class="page" id="page-products">
      <div class="page-hd">
        <div class="page-title">
          <svg viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          <span>Products</span>
        </div>
        <div class="flex gap8">
          <input class="search-bar" placeholder="Search products..." oninput="Products.filterSearch(this.value)">
          <button class="btn btn-blue" onclick="Products.openAdd()">+ Add Product</button>
        </div>
      </div>

      <div class="filter-tabs" id="cat-tabs">
        <button class="tab-btn active" onclick="Products.filterCat('All',this)">All</button>
        <button class="tab-btn" onclick="Products.filterCat('CPU',this)">CPU</button>
        <button class="tab-btn" onclick="Products.filterCat('GPU',this)">GPU</button>
        <button class="tab-btn" onclick="Products.filterCat('RAM',this)">RAM</button>
        <button class="tab-btn" onclick="Products.filterCat('Storage',this)">Storage</button>
        <button class="tab-btn" onclick="Products.filterCat('Motherboard',this)">Motherboard</button>
        <button class="tab-btn" onclick="Products.filterCat('Peripheral',this)">Peripheral</button>
        <button class="tab-btn" onclick="Products.filterCat('Other',this)">Other</button>
      </div>

      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead>
              <tr><th>#</th><th>Product Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Min Level</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody id="product-tbody"></tbody>
          </table>
        </div>
      </div>
    </section>

    <section class="page" id="page-stock">
      <div class="page-hd">
        <div class="page-title">
          <svg viewBox="0 0 24 24"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
          <span>Stock Transactions</span>
        </div>
        <div class="flex gap8">
          <button class="btn btn-green" onclick="Stock.openStockIn()">Stock In</button>
          <button class="btn btn-red"   onclick="Stock.openStockOut()">Stock Out</button>
        </div>
      </div>

      <div class="filter-tabs" id="txn-tabs">
        <button class="tab-btn active" onclick="Stock.filterType('All',this)">All Transactions</button>
        <button class="tab-btn" onclick="Stock.filterType('Stock In',this)">Stock In</button>
        <button class="tab-btn" onclick="Stock.filterType('Stock Out',this)">Stock Out</button>
      </div>

      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead>
              <tr><th>#</th><th>Date</th><th>Product</th><th>Type</th><th>Quantity</th><th>Supplier</th><th>Remarks</th></tr>
            </thead>
            <tbody id="txn-tbody"></tbody>
          </table>
        </div>
      </div>
    </section>

    <section class="page" id="page-suppliers">
      <div class="page-hd">
        <div class="page-title">
          <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          <span>Suppliers</span>
        </div>
        <button class="btn btn-blue" onclick="Suppliers.openAdd()">+ Add Supplier</button>
      </div>

      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead>
              <tr><th>#</th><th>Supplier Name</th><th>Contact Number</th><th>Email Address</th><th>Address</th><th>Actions</th></tr>
            </thead>
            <tbody id="supplier-tbody"></tbody>
          </table>
        </div>
      </div>
    </section>

    <section class="page" id="page-reports">
      <div class="page-hd">
        <div class="page-title">
          <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          <span>Reports</span>
        </div>
        <button class="btn btn-blue" onclick="Reports.print()">Print Report</button>
      </div>

      <div class="report-grid">
        <div class="card">
          <div class="card-title">
            <svg style="width:15px;height:15px;stroke:var(--blue);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Current Stock Levels
          </div>
          <div class="bar-chart" id="rpt-stock"></div>
        </div>
        <div class="card">
          <div class="card-title">
            <svg style="width:15px;height:15px;stroke:var(--warning);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Low Stock Items
          </div>
          <div id="rpt-low"></div>
        </div>
      </div>

      <div class="report-grid">
        <div class="card">
          <div class="card-title">
            <svg style="width:15px;height:15px;stroke:var(--red);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            Most Moved Products
          </div>
          <div class="bar-chart" id="rpt-best"></div>
        </div>
        <div class="card">
          <div class="card-title">
            <svg style="width:15px;height:15px;stroke:var(--blue);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            Transaction Summary
          </div>
          <div id="rpt-summary"></div>
        </div>
      </div>
    </section>

  </main>
</div>

<div class="modal-overlay" id="modal-stockout">
  <div class="modal">
    <div class="modal-title">
      <svg viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
      Record Stock Out
    </div>
    <div class="form-grid cols1">
      <div class="field"><label>Product *</label><select id="so-product"></select></div>
      <div class="field"><label>Quantity *</label><input id="so-qty" type="number" min="1" placeholder="0"></div>
      <div class="field"><label>Date *</label><input id="so-date" type="date"></div>
      <div class="field"><label>Remarks (Optional)</label><input id="so-remarks" placeholder="Add any notes here..."></div>
    </div>
    <div class="form-footer">
      <button class="btn btn-flat btn-sm" onclick="Modals.close('stockout')">Cancel</button>
      <button class="btn btn-red btn-sm" onclick="Stock.saveStockOut()">Record Stock Out</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal-stockin">
  <div class="modal">
    <div class="modal-title">
      <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
      Record Stock In
    </div>
    <div class="form-grid">
      <div class="field"><label>Product *</label><select id="si-product"></select></div>
      <div class="field"><label>Supplier *</label><select id="si-supplier"></select></div>
      <div class="field"><label>Quantity *</label><input id="si-qty" type="number" min="1" placeholder="0"></div>
      <div class="field"><label>Date *</label><input id="si-date" type="date"></div>
      <div class="field" style="grid-column:1/-1"><label>Remarks (Optional)</label><input id="si-remarks" placeholder="Add any notes here..."></div>
    </div>
    <div class="form-footer">
      <button class="btn btn-flat btn-sm" onclick="Modals.close('stockin')">Cancel</button>
      <button class="btn btn-green btn-sm" onclick="Stock.saveStockIn()">Record Stock In</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal-stockout">
  <div class="modal">
    <div class="modal-title">
      <svg viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
      Record Stock Out
    </div>
    <div class="form-grid">
      <div class="field"><label>Product *</label><select id="so-product"></select></div>
      <div class="field"><label>Quantity *</label><input id="so-qty" type="number" min="1" placeholder="0"></div>
      <div class="field"><label>Date *</label><input id="so-date" type="date"></div>
      <div class="field" style="grid-column:1/-1"><label>Remarks (Optional)</label><input id="so-remarks" placeholder="Add any notes here..."></div>
    </div>
    <div class="form-footer">
      <button class="btn btn-flat btn-sm" onclick="Modals.close('stockout')">Cancel</button>
      <button class="btn btn-red btn-sm" onclick="Stock.saveStockOut()">Record Stock Out</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal-supplier">
  <div class="modal">
    <div class="modal-title">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <span id="supplier-modal-title">Add Supplier</span>
    </div>
    <input type="hidden" id="edit-supplier-id">
    <div class="form-grid">
      <div class="field"><label>Supplier Name *</label><input id="fs-name" placeholder="Company or person name"></div>
      <div class="field"><label>Contact Number *</label><input id="fs-contact" placeholder="e.g. 09xxxxxxxxx"></div>
      <div class="field"><label>Email Address</label><input id="fs-email" type="email" placeholder="email@example.com"></div>
      <div class="field"><label>Address</label><input id="fs-address" placeholder="Full address"></div>
    </div>
    <div class="form-footer">
      <button class="btn btn-flat btn-sm" onclick="Modals.close('supplier')">Cancel</button>
      <button class="btn btn-green btn-sm" onclick="Suppliers.save()">Save Supplier</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal-confirm">
  <div class="modal" style="width:420px">
    <div class="modal-title" style="color:var(--red)">
      <svg style="stroke:var(--red)" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
      Confirm Delete
    </div>
    <p id="confirm-msg" style="font-size:14px;color:var(--text);margin-bottom:8px;line-height:1.6"></p>
    <p style="font-size:12px;color:var(--text-dim)">This action cannot be undone.</p>
    <div class="form-footer">
      <button class="btn btn-flat btn-sm" onclick="Modals.close('confirm')">Cancel</button>
      <button class="btn btn-red btn-sm" id="confirm-ok">Delete</button>
    </div>
  </div>
</div>

<script src="js/api.js"></script>
<script src="js/utils.js"></script>
<script src="js/modals.js"></script>
<script src="js/nav.js"></script>
<script src="js/pages/dashboard.js"></script>
<script src="js/pages/products.js"></script>
<script src="js/pages/stock.js"></script>
<script src="js/pages/suppliers.js"></script>
<script src="js/pages/reports.js"></script>
<script>Nav.init();</script>
</body>
</html>
