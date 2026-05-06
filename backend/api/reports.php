<?php
require_once '../config/db.php';
require_once '../config/helpers.php';

setCorsHeaders();
if (method() !== 'GET') error('Method not allowed.', 405);

$db   = getDB();
$type = $_GET['type'] ?? '';

switch ($type) {

  case 'summary':
    $total_products  = $db->query('SELECT COUNT(*) FROM Product')->fetchColumn();
    $low_stock       = $db->query('SELECT COUNT(*) FROM Product WHERE Stock_Quantity > 0 AND Stock_Quantity <= Min_Stock_Level')->fetchColumn();
    $out_of_stock    = $db->query('SELECT COUNT(*) FROM Product WHERE Stock_Quantity = 0')->fetchColumn();
    $total_suppliers = $db->query('SELECT COUNT(*) FROM Supplier')->fetchColumn();
    $inventory_value = $db->query('SELECT COALESCE(SUM(Price * Stock_Quantity), 0) FROM Product')->fetchColumn();
    $total_txns      = $db->query('SELECT COUNT(*) FROM Stock_Transaction')->fetchColumn();
    $total_in        = $db->query("SELECT COALESCE(SUM(Quantity),0) FROM Stock_Transaction WHERE Type = 'Stock In'")->fetchColumn();
    $total_out       = $db->query("SELECT COALESCE(SUM(Quantity),0) FROM Stock_Transaction WHERE Type = 'Stock Out'")->fetchColumn();

    ok([
      'total_products'  => (int)$total_products,
      'low_stock'       => (int)$low_stock,
      'out_of_stock'    => (int)$out_of_stock,
      'total_suppliers' => (int)$total_suppliers,
      'inventory_value' => (float)$inventory_value,
      'total_txns'      => (int)$total_txns,
      'total_in'        => (int)$total_in,
      'total_out'       => (int)$total_out,
    ]);
    break;

  case 'low-stock':
    $rows = $db->query("
      SELECT Product_ID, Product_Name, Category, Stock_Quantity, Min_Stock_Level
      FROM Product
      WHERE Stock_Quantity <= Min_Stock_Level
      ORDER BY Stock_Quantity ASC
    ")->fetchAll();
    ok($rows);
    break;

  case 'stock-levels':
    $rows = $db->query("
      SELECT Product_ID, Product_Name, Category, Stock_Quantity, Min_Stock_Level, Price
      FROM Product
      ORDER BY Stock_Quantity DESC
    ")->fetchAll();
    ok($rows);
    break;

  case 'best-moved':
    $rows = $db->query("
      SELECT
        p.Product_ID,
        p.Product_Name,
        p.Category,
        SUM(t.Quantity) AS Total_Out
      FROM Stock_Transaction t
      JOIN Product p ON t.Product_ID = p.Product_ID
      WHERE t.Type = 'Stock Out'
      GROUP BY p.Product_ID, p.Product_Name, p.Category
      ORDER BY Total_Out DESC
      LIMIT 8
    ")->fetchAll();
    ok($rows);
    break;

  case 'recent-transactions':
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
    $stmt  = $db->prepare("
      SELECT
        t.Transaction_ID,
        t.Type,
        t.Quantity,
        t.Transaction_Date,
        t.Remarks,
        p.Product_Name,
        s.Supplier_Name
      FROM Stock_Transaction t
      JOIN     Product  p ON t.Product_ID  = p.Product_ID
      LEFT JOIN Supplier s ON t.Supplier_ID = s.Supplier_ID
      ORDER BY t.Created_At DESC
      LIMIT ?
    ");
    $stmt->execute([$limit]);
    ok($stmt->fetchAll());
    break;

  default:
    error("Unknown report type: '$type'.");
}
