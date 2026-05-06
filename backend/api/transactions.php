<?php
require_once '../config/db.php';
require_once '../config/helpers.php';

setCorsHeaders();
$db   = getDB();
$type = $_GET['type'] ?? null;

switch (method()) {

  // ── GET — all transactions with joins ──
  case 'GET':
    $stmt = $db->query("
      SELECT
        t.Transaction_ID,
        t.Type,
        t.Quantity,
        t.Transaction_Date,
        t.Remarks,
        t.Created_At,
        p.Product_ID,
        p.Product_Name,
        p.Category,
        s.Supplier_ID,
        s.Supplier_Name
      FROM Stock_Transaction t
      JOIN     Product  p ON t.Product_ID  = p.Product_ID
      LEFT JOIN Supplier s ON t.Supplier_ID = s.Supplier_ID
      ORDER BY t.Created_At DESC
    ");
    ok($stmt->fetchAll());
    break;


  case 'POST':
    $b = getBody();

    if ($type === 'in') {
      requireFields($b, ['Product_ID', 'Supplier_ID', 'Quantity', 'Transaction_Date']);
      $qty = (int)$b['Quantity'];
      if ($qty <= 0) error('Quantity must be greater than 0.');

      try {
        $db->beginTransaction();

        $db->prepare("
          INSERT INTO Stock_Transaction
            (Product_ID, Supplier_ID, Quantity, Transaction_Date, Type, Remarks)
          VALUES (?, ?, ?, ?, 'Stock In', ?)
        ")->execute([
          (int)$b['Product_ID'],
          (int)$b['Supplier_ID'],
          $qty,
          $b['Transaction_Date'],
          trim($b['Remarks'] ?? '')
        ]);

        $db->prepare('UPDATE Product SET Stock_Quantity = Stock_Quantity + ? WHERE Product_ID = ?')
           ->execute([$qty, (int)$b['Product_ID']]);

        $db->commit();
        created(null, 'Stock In recorded.');

      } catch (Exception $e) {
        $db->rollBack();
        error('Stock In failed: ' . $e->getMessage(), 500);
      }

    } elseif ($type === 'out') {
      requireFields($b, ['Product_ID', 'Quantity', 'Transaction_Date']);
      $qty = (int)$b['Quantity'];
      if ($qty <= 0) error('Quantity must be greater than 0.');

      try {
        $db->beginTransaction();

        //Stock_Quantity cannot go below 0
        $check = $db->prepare('SELECT Stock_Quantity FROM Product WHERE Product_ID = ? FOR UPDATE');
        $check->execute([(int)$b['Product_ID']]);
        $product = $check->fetch();

        if (!$product) { $db->rollBack(); error('Product not found.', 404); }
        if ($product['Stock_Quantity'] < $qty) {
          $db->rollBack();
          error("Not enough stock. Available: {$product['Stock_Quantity']}");
        }

        $db->prepare("
          INSERT INTO Stock_Transaction
            (Product_ID, Supplier_ID, Quantity, Transaction_Date, Type, Remarks)
          VALUES (?, NULL, ?, ?, 'Stock Out', ?)
        ")->execute([
          (int)$b['Product_ID'],
          $qty,
          $b['Transaction_Date'],
          trim($b['Remarks'] ?? '')
        ]);

        $db->prepare('UPDATE Product SET Stock_Quantity = Stock_Quantity - ? WHERE Product_ID = ?')
           ->execute([$qty, (int)$b['Product_ID']]);

        $db->commit();
        created(null, 'Stock Out recorded.');

      } catch (Exception $e) {
        $db->rollBack();
        error('Stock Out failed: ' . $e->getMessage(), 500);
      }

    } else {
      error('Unknown type. Use ?type=in or ?type=out');
    }
    break;

  default:
    error('Method not allowed.', 405);
}
