<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// Replace 'chat_channel' with your desired channel name
$channel = 'chat_channel';

echo "Subscribed to channel '{$channel}'. Waiting for messages...\n";

$redis->subscribe([$channel], function ($redis, $chan, $msg) {
    echo "Received message from channel '{$chan}': {$msg}\n";
});
?>
