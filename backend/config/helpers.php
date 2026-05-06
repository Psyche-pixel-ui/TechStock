<?php

function setCorsHeaders(): void {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type');
  header('Content-Type: application/json; charset=UTF-8');

  // Handle preflight OPTIONS request
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
  }
}

function respond(bool $success, $data = null, string $message = '', int $status = 200): void {
  http_response_code($status);
  $payload = ['success' => $success];
  if ($message)    $payload['message'] = $message;
  if ($data !== null) $payload['data'] = $data;
  echo json_encode($payload);
  exit;
}

function ok($data = null, string $message = ''): void {
  respond(true, $data, $message, 200);
}

function created($data = null, string $message = 'Created'): void {
  respond(true, $data, $message, 201);
}

function error(string $message, int $status = 400): void {
  respond(false, null, $message, $status);
}


function getBody(): array {
  $raw = file_get_contents('php://input');
  return json_decode($raw, true) ?? [];
}


function method(): string {
  return $_SERVER['REQUEST_METHOD'];
}


function requireFields(array $body, array $fields): void {
  foreach ($fields as $f) {
    if (!isset($body[$f]) || $body[$f] === '') {
      error("Field '$f' is required.");
    }
  }
}
