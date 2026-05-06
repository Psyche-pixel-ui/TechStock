<?php
require_once '../config/db.php';
require_once '../config/helpers.php';

setCorsHeaders();
$db = getDB();
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch (method()) {

  case 'GET':
    if ($id) {
      $stmt = $db->prepare('SELECT * FROM Supplier WHERE Supplier_ID = ?');
      $stmt->execute([$id]);
      $row = $stmt->fetch();
      if (!$row) error('Supplier not found.', 404);
      ok($row);
    } else {
      $stmt = $db->query('SELECT * FROM Supplier ORDER BY Supplier_Name ASC');
      ok($stmt->fetchAll());
    }
    break;

  case 'POST':
    $b = getBody();
    requireFields($b, ['Supplier_Name', 'Contact_Number']);

    $stmt = $db->prepare(
      'INSERT INTO Supplier (Supplier_Name, Contact_Number, Email_Address, Address)
       VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([
      trim($b['Supplier_Name']),
      trim($b['Contact_Number']),
      trim($b['Email_Address'] ?? ''),
      trim($b['Address'] ?? '')
    ]);
    created(['id' => $db->lastInsertId()], 'Supplier added.');
    break;

  case 'PUT':
    if (!$id) error('Supplier ID is required.');
    $b = getBody();
    requireFields($b, ['Supplier_Name', 'Contact_Number']);

    $stmt = $db->prepare(
      'UPDATE Supplier
       SET Supplier_Name = ?, Contact_Number = ?, Email_Address = ?, Address = ?
       WHERE Supplier_ID = ?'
    );
    $stmt->execute([
      trim($b['Supplier_Name']),
      trim($b['Contact_Number']),
      trim($b['Email_Address'] ?? ''),
      trim($b['Address'] ?? ''),
      $id
    ]);
    if ($stmt->rowCount() === 0) error('Supplier not found.', 404);
    ok(null, 'Supplier updated.');
    break;

  case 'DELETE':
    if (!$id) error('Supplier ID is required.');
    $stmt = $db->prepare('DELETE FROM Supplier WHERE Supplier_ID = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) error('Supplier not found.', 404);
    ok(null, 'Supplier deleted.');
    break;

  default:
    error('Method not allowed.', 405);
}
