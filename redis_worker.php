<?php
require 'vendor/autoload.php';
require 'db.php';

use Predis\Client;

// Create Redis client
$redis = new Client();

echo "✅ Listening for Redis Pub/Sub messages on 'support_channel'...\n";

// Subscribe and process messages
$pubsub = $redis->pubSubLoop();
$pubsub->subscribe('support_channel');

foreach ($pubsub as $message) {
    if ($message->kind === 'message') {
        $payload = json_decode($message->payload, true);

        if ($payload && isset($payload['student_ID'], $payload['message'])) {
            $studentID = $payload['student_ID'];
            $msg = $payload['message'];
            $timestamp = $payload['timestamp'] ?? date('Y-m-d H:i:s');

            // Insert into MySQL message_queue table
            $stmt = $conn->prepare("INSERT INTO message_queue (student_ID, message, created_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $studentID, $msg, $timestamp);
            $stmt->execute();

            echo "💾 Saved: [$studentID] $msg\n";
        } else {
            echo "⚠️ Invalid message format.\n";
        }
    }
}

