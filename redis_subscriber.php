<?php
require 'vendor/autoload.php'; // Ensure this path is correct

use Predis\Client as RedisClient;

$redis = new RedisClient();

$pubsub = $redis->pubSubLoop();
$pubsub->subscribe('support_channel');

echo "Listening for messages on 'support_channel'...\n";

foreach ($pubsub as $message) {
    if ($message->kind === 'message') {
        $data = json_decode($message->payload, true);
        // Process the message as needed
        echo "New support request from Student ID: {$data['student_ID']} at {$data['timestamp']}\n";
        echo "Message: {$data['message']}\n";
    }
}
