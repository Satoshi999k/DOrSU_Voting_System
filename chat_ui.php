<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Support Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0e0e0;
        }

        .chat-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
        }

        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 10px;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            scroll-behavior: smooth;
        }

        .message-block {
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
        }

        .admin {
            align-self: flex-start;
            text-align: left;
        }

        .student {
            align-self: flex-end;
            text-align: right;
        }

        .label {
            font-size: 0.8em;
            color: #777;
            margin-bottom: 3px;
        }

        .bubble {
            padding: 10px 15px;
            border-radius: 20px;
            max-width: 75%;
            display: inline-block;
            word-wrap: break-word;
        }

        .admin .bubble {
            background-color: #e0e0e0;
            color: black;
        }

        .student .bubble {
            background-color: #0084ff;
            color: white;
        }

        .menu-buttons {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }

        .menu-buttons button {
            flex: 1;
            margin: 0 5px;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .menu-buttons button:nth-child(1) {
            background: #e0f0ff;
        }

        .menu-buttons button:nth-child(2) {
            background: #f0f0ff;
        }

        .menu-buttons button:nth-child(3) {
            background: #ffdddd;
        }

        .menu-buttons button:hover {
            background-color: #d0e8ff;
            transform: scale(1.03);
        }

        input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button.send {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #0084ff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        button.send:hover {
            background-color: #006fd6;
            transform: scale(1.03);
        }
    </style>
</head>
<body>
<div class="chat-container">
    <h2 style="text-align:center;">Support Chat</h2>
    <div class="menu-buttons">
        <button onclick="sendMenuOption('1')">How to vote</button>
        <button onclick="sendMenuOption('2')">Already voted?</button>
        <button onclick="sendMenuOption('3')">Contact support</button>
    </div>
    <div id="chat-box" class="chat-box"></div>
    <div>
        <input type="text" id="message-input" placeholder="Type your message..." />
        <button class="send" onclick="sendMessage()" id="sendBtn">Send</button>
    </div>
</div>

<script>
    const studentID = <?php echo json_encode($_SESSION['student_ID']); ?>;

    function fetchMessages() {
        fetch('chat_backend.php?action=fetch_messages')
            .then(res => res.json())
            .then(data => {
                const chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = '';

                data.messages.forEach(msg => {
                    const block = document.createElement('div');
                    const isAdmin = msg.sender !== studentID.toString();
                    const senderType = msg.sender === 'bot' ? 'admin' : (isAdmin ? 'admin' : 'student');

                    block.className = 'message-block ' + senderType;

                    const label = document.createElement('div');
                    label.className = 'label';

                    if (msg.sender === 'bot') {
                        label.textContent = 'Sent by Bot';
                    } else if (isAdmin) {
                        label.textContent = 'Sent by Admin';
                    } else {
                        label.textContent = `Sent by Student: ${studentID}`;
                    }

                    const bubble = document.createElement('div');
                    bubble.className = 'bubble';
                    bubble.textContent = msg.message;

                    block.appendChild(label);
                    block.appendChild(bubble);

                    chatBox.appendChild(block);
                });

                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    function sendMessage() {
        const input = document.getElementById('message-input');
        const message = input.value.trim();
        if (message === '') return;

        fetch('chat_backend.php?action=student_message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'message=' + encodeURIComponent(message)
        }).then(() => {
            input.value = '';
            fetchMessages();
        });
    }

    function sendMenuOption(actionCode) {
        fetch('chat_backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=' + encodeURIComponent(actionCode)
        }).then(() => fetchMessages());
    }

    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById('message-input');
        const sendBtn = document.getElementById('sendBtn');

        input.addEventListener("keypress", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                sendBtn.click();
            }
        });

        fetchMessages();
        setInterval(fetchMessages, 2000);
    });
</script>
</body>
</html>


