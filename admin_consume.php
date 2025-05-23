<?php
require 'vendor/autoload.php'; // Load Predis
include 'db.php'; // Your MySQL connection

$redis = new Predis\Client(); // Connect to Redis

// Infinite loop (for CLI testing) or wrap in request-based logic for web
while (true) {
    // Wait for a message in the queue
    $result = $redis->blpop('support_queue', 0); // ['support_queue', 'json_string']

    $payload = json_decode($result[1], true);

    if ($payload && isset($payload['student_ID']) && isset($payload['message'])) {
        $studentID = $payload['student_ID'];
        $message = $payload['message'];

        // Save to MySQL support_messages
        $stmt = $conn->prepare("INSERT INTO support_messages (sender_id, receiver_id, message) VALUES (?, 'admin', ?)");
        $stmt->bind_param("ss", $studentID, $message);
        $stmt->execute();

        echo "Saved message from $studentID: $message\n";
    }

    // Optional: sleep(1); // to avoid 100% CPU if needed
}
