<?php
require_once '../config/db.php';
require_once '../config/helpers.php';

setCorsHeaders();
$db = getDB();
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch (method()) {

  case 'GET':
    if ($id) {
      $stmt = $db->prepare('SELECT * FROM Product WHERE Product_ID = ?');
      $stmt->execute([$id]);
      $row = $stmt->fetch();
      if (!$row) error('Product not found.', 404);
      ok($row);
    } else {
      $stmt = $db->query('SELECT * FROM Product ORDER BY Product_Name ASC');
      ok($stmt->fetchAll());
    }
    break;

  case 'POST':
    $b = getBody();
    requireFields($b, ['Product_Name', 'Category', 'Price', 'Stock_Quantity', 'Min_Stock_Level']);
    if ((float)$b['Price'] < 0)         error('Price cannot be negative.');
    if ((int)$b['Stock_Quantity'] < 0)  error('Stock cannot be negative.');
    if ((int)$b['Min_Stock_Level'] < 0) error('Min stock level cannot be negative.');

    $stmt = $db->prepare(
      'INSERT INTO Product (Product_Name, Category, Price, Stock_Quantity, Min_Stock_Level)
       VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([
      trim($b['Product_Name']),
      $b['Category'],
      (float)$b['Price'],
      (int)$b['Stock_Quantity'],
      (int)$b['Min_Stock_Level']
    ]);
    created(['id' => $db->lastInsertId()], 'Product added.');
    break;

  case 'PUT':
    if (!$id) error('Product ID is required.');
    $b = getBody();
    requireFields($b, ['Product_Name', 'Category', 'Price', 'Stock_Quantity', 'Min_Stock_Level']);

    $stmt = $db->prepare(
      'UPDATE Product
       SET Product_Name = ?, Category = ?, Price = ?, Stock_Quantity = ?, Min_Stock_Level = ?
       WHERE Product_ID = ?'
    );
    $stmt->execute([
      trim($b['Product_Name']),
      $b['Category'],
      (float)$b['Price'],
      (int)$b['Stock_Quantity'],
      (int)$b['Min_Stock_Level'],
      $id
    ]);
    if ($stmt->rowCount() === 0) error('Product not found.', 404);
    ok(null, 'Product updated.');
    break;

  case 'DELETE':
    if (!$id) error('Product ID is required.');
    $stmt = $db->prepare('DELETE FROM Product WHERE Product_ID = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) error('Product not found.', 404);
    ok(null, 'Product deleted.');
    break;

  default:
    error('Method not allowed.', 405);
}
