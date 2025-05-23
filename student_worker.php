<?php
require 'vendor/autoload.php';
require 'db.php';

$redis = new Predis\Client();

echo "🔄 Listening for student messages in Redis...\n";

while (true) {
    $data = $redis->blpop(['student_message_queue'], 0);

    if ($data && isset($data[1])) {
        $payload = json_decode($data[1], true);

        if ($payload && isset($payload['student_ID'], $payload['message'])) {
            $studentID = $payload['student_ID'];
            $message = $payload['message'];
            $createdAt = $payload['created_at'] ?? date('Y-m-d H:i:s');

            $stmt = $conn->prepare("INSERT INTO message_queue (student_ID, message, created_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $studentID, $message, $createdAt);
            $stmt->execute();

            echo "✅ Stored message from [$studentID]: $message\n";
        } else {
            echo "❌ Invalid message format received.\n";
        }
    }
}




