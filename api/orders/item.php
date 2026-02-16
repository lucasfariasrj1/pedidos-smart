<?php
require_once __DIR__ . '/../client.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'missing_id']);
    exit;
}

forward_request('/auth/orders/' . $id);
