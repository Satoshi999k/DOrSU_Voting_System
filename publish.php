<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// Replace 'chat_channel' with your desired channel name
$channel = 'chat_channel';
$message = 'Hello, this is a test message!';

$redis->publish($channel, $message);
echo "Message published to channel '{$channel}'.\n";
?>
