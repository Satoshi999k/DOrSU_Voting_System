<?php
require 'db.php';
header('Content-Type: application/json');

$studentID = $_GET['student_ID'] ?? '';
if (!$studentID) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM message_queue WHERE student_ID = ?");
$stmt->bind_param("s", $studentID);
$stmt->execute();
$queueResult = $stmt->get_result();

while ($row = $queueResult->fetch_assoc()) {
    $insert = $conn->prepare("INSERT INTO support_messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, ?)");
    $insert->bind_param("ssss", $row['student_ID'], $row['receiver_ID'], $row['message'], $row['created_at']);
    $insert->execute();
}

$delete = $conn->prepare("DELETE FROM message_queue WHERE student_ID = ?");
$delete->bind_param("s", $studentID);
$delete->execute();

$stmt = $conn->prepare("SELECT * FROM support_messages WHERE sender_id = ? OR receiver_id = ? ORDER BY timestamp ASC");
$stmt->bind_param("ss", $studentID, $studentID);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'sender_id' => $row['sender_id'],
        'message' => $row['message'],
        'timestamp' => $row['timestamp']
    ];
}

echo json_encode($messages);





