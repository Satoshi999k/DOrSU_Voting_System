<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$studentID = $_GET['student_ID'] ?? '';
if (!$studentID) {
    echo "Invalid student ID.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Chat</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        h2 { text-align: center; }
        #messages {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 700px;
            margin: auto;
            height: 400px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .message-block {
            display: flex;
            flex-direction: column;
            max-width: 75%;
            margin-bottom: 8px;
        }
        .admin {
            align-self: flex-end;
            text-align: right;
        }
        .student {
            align-self: flex-start;
            text-align: left;
        }
        .bubble {
            padding: 10px;
            border-radius: 20px;
            display: inline-block;
            word-wrap: break-word;
        }
        .admin .bubble {
            background-color: #0084ff;
            color: white;
        }
        .student .bubble {
            background-color: #3a3a3a;
            color: white;
        }
        .timestamp {
            font-size: 0.75em;
            color: #aaa;
            margin-top: 2px;
        }
        form {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        input[type="text"] {
            width: 400px;
            padding: 10px;
        }
        button {
            padding: 10px;
        }
    </style>
</head>
<body>

<h2>Messages from Student: <?= htmlspecialchars($studentID) ?></h2>
<div id="messages"></div>

<form id="sendForm">
    <input type="text" id="messageInput" placeholder="Type your message..." required />
    <button type="submit">Send</button>
</form>

<script>
    const studentID = "<?= $studentID ?>";

    async function fetchMessages() {
        const res = await fetch('fetch_messages.php?student_ID=' + encodeURIComponent(studentID));
        const data = await res.json();

        const container = document.getElementById('messages');
        container.innerHTML = '';

        data.forEach(msg => {
            if (msg.sender_id === 'bot') return;

            const block = document.createElement('div');
            block.classList.add('message-block');

            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            bubble.textContent = msg.message;

            const timestamp = document.createElement('div');
            timestamp.className = 'timestamp';
            timestamp.textContent = msg.timestamp;

            if (msg.sender_id === studentID) {
                block.classList.add('student');
            } else {
                block.classList.add('admin');
            }

            block.appendChild(bubble);
            block.appendChild(timestamp);
            container.appendChild(block);
        });

        container.scrollTop = container.scrollHeight;
    }

    document.getElementById('sendForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = document.getElementById('messageInput').value;
        const res = await fetch('send_admin_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ receiver_id: studentID, message: msg })
        });

        if (res.ok) {
            document.getElementById('messageInput').value = '';
            fetchMessages();
        }
    });

    fetchMessages();
    setInterval(fetchMessages, 3000);
</script>

</body>
</html>


