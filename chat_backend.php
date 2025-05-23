<?php
session_start();
require 'db.php';
require 'vendor/autoload.php'; // Ensure this path is correct

use Predis\Client as RedisClient;

if (!isset($_SESSION['student_ID'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}
    
header('Content-Type: application/json');

$user_id = $_SESSION['student_ID'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$response = '';

// Initialize Redis
$redis = new RedisClient();

// Handle menu options
if ($action === '1') {
    $response = "Bot: To vote, go to the voting section and select your preferred candidates.";
    $stmt = $conn->prepare("INSERT INTO support_messages (sender_id, receiver_id, message) VALUES ('bot', ?, ?)");
    $stmt->bind_param("ss", $user_id, $response);
    $stmt->execute();
    echo json_encode(['status' => 'success']);
    exit;

} elseif ($action === '2') {
    $response = "Bot: If you already voted, you cannot vote again.";
    $stmt = $conn->prepare("INSERT INTO support_messages (sender_id, receiver_id, message) VALUES ('bot', ?, ?)");
    $stmt->bind_param("ss", $user_id, $response);
    $stmt->execute();
    echo json_encode(['status' => 'success']);
    exit;

} elseif ($action === '3') {
    $msg = 'Student has contacted support.';

    // Only add to queue if not already there
    $check = $conn->prepare("SELECT id FROM support_queue WHERE student_ID = ?");
    $check->bind_param("s", $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO support_queue (student_ID) VALUES (?)");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
    }

    // Send bot message
    $response = "Bot: You've been connected to support. Please wait for the admin.";
    $stmt2 = $conn->prepare("INSERT INTO support_messages (sender_id, receiver_id, message) VALUES ('bot', ?, ?)");
    $stmt2->bind_param("ss", $user_id, $response);
    $stmt2->execute();

    $_SESSION['in_support'] = true;

    // Notify via Redis
    $redis->publish('support_channel', json_encode([
        'student_ID' => $user_id,
        'message' => $msg,
        'timestamp' => date('Y-m-d H:i:s')
    ]));

    echo json_encode(['status' => 'connected']);
    exit;
}

// Handle student message sending (✅ fixed here)
elseif ($action === 'student_message') {
    $msg = trim($_POST['message'] ?? '');
    if ($msg !== '') {
        // Add to message_queue only
        $stmtQueue = $conn->prepare("INSERT INTO message_queue (student_ID, message) VALUES (?, ?)");
        $stmtQueue->bind_param("ss", $user_id, $msg);
        $stmtQueue->execute();

        echo json_encode(['status' => 'sent']);
    } else {
        echo json_encode(['status' => 'empty']);
    }
    exit;
}

// Fetch chat history
elseif ($action === 'fetch_messages') {
    $messages = [];

    // Get saved conversation (support_messages)
    $stmt = $conn->prepare("
        SELECT sender_id, message, timestamp 
        FROM support_messages 
        WHERE sender_id = ? OR receiver_id = ? 
        ORDER BY timestamp ASC
    ");
    $stmt->bind_param("ss", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'sender' => htmlspecialchars($row['sender_id']),
            'message' => htmlspecialchars($row['message']),
            'timestamp' => $row['timestamp']
        ];
    }

    // Also show any temporary unsaved messages in queue (only if not yet handled by admin)
    $stmtQueue = $conn->prepare("
        SELECT message, created_at 
        FROM message_queue 
        WHERE student_ID = ? 
        ORDER BY created_at ASC
    ");
    $stmtQueue->bind_param("s", $user_id);
    $stmtQueue->execute();
    $queueResult = $stmtQueue->get_result();
    while ($row = $queueResult->fetch_assoc()) {
        $messages[] = [
            'sender' => htmlspecialchars($user_id),
            'message' => htmlspecialchars($row['message']),
            'timestamp' => $row['created_at']
        ];
    }

    // Sort by time
    usort($messages, function ($a, $b) {
        return strtotime($a['timestamp']) <=> strtotime($b['timestamp']);
    });

    echo json_encode(['status' => 'ok', 'messages' => $messages]);
    exit;
}

echo json_encode(['status' => 'invalid_action']);
?>


