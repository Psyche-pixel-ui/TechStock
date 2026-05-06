const API = (() => {

  const BASE = 'http://localhost/TechStock/backend/api';

  async function request(method, url, body = null) {
    const options = { method, headers: { 'Content-Type': 'application/json' } };
    if (body) options.body = JSON.stringify(body);
    const res  = await fetch(url, options);
    const data = await res.json();
    if (!data.success) throw new Error(data.message || 'API error');
    return data;
  }

  const get = (url)        => request('GET',    url);
  const post= (url, body)  => request('POST',   url, body);
  const put = (url, body)  => request('PUT',    url, body);
  const del = (url)        => request('DELETE', url);

  const Products = {
    getAll:  ()         => get(`${BASE}/products.php`),
    getById: (id)       => get(`${BASE}/products.php?id=${id}`),
    create:  (data)     => post(`${BASE}/products.php`, data),
    update:  (id, data) => put(`${BASE}/products.php?id=${id}`, data),
    delete:  (id)       => del(`${BASE}/products.php?id=${id}`)
  };

  const Suppliers = {
    getAll:  ()         => get(`${BASE}/suppliers.php`),
    getById: (id)       => get(`${BASE}/suppliers.php?id=${id}`),
    create:  (data)     => post(`${BASE}/suppliers.php`, data),
    update:  (id, data) => put(`${BASE}/suppliers.php?id=${id}`, data),
    delete:  (id)       => del(`${BASE}/suppliers.php?id=${id}`)
  };

  const Transactions = {
    getAll:   ()     => get(`${BASE}/transactions.php`),
    stockIn:  (data) => post(`${BASE}/transactions.php?type=in`,  data),
    stockOut: (data) => post(`${BASE}/transactions.php?type=out`, data)
  };

  const Reports = {
    getSummary:            ()          => get(`${BASE}/reports.php?type=summary`),
    getLowStock:           ()          => get(`${BASE}/reports.php?type=low-stock`),
    getStockLevels:        ()          => get(`${BASE}/reports.php?type=stock-levels`),
    getBestMoved:          ()          => get(`${BASE}/reports.php?type=best-moved`),
    getRecentTransactions: (limit = 6) => get(`${BASE}/reports.php?type=recent-transactions&limit=${limit}`)
  };

  return { Products, Suppliers, Transactions, Reports };
})();
