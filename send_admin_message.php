<?php
session_start();
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$receiver = $data['receiver_id'] ?? '';
$message = $data['message'] ?? '';
$adminID = $_SESSION['admin_id'] ?? 'admin';

if (!$receiver || !$message) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO support_messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $adminID, $receiver, $message);
$stmt->execute();

echo json_encode(['status' => 'success']);
