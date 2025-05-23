<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_answered'])) {
    $studentID = $_POST['student_ID'];

    // Delete all messages involving this student
    $deleteMessages = $conn->prepare("DELETE FROM support_messages WHERE sender_id = ? OR receiver_id = ?");
    $deleteMessages->bind_param("ss", $studentID, $studentID);
    $deleteMessages->execute();
    $deleteMessages->close();

    // Delete from support_queue
    $stmt = $conn->prepare("DELETE FROM support_queue WHERE student_ID = ?");
    $stmt->bind_param("s", $studentID);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Marked as answered, messages deleted, and removed from queue.'); window.location.href = 'support_requests.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DOrSU VOTING SYSTEM</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        header {
            background-color: #003F77;
            padding: 20px;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        header img {
            height: 50px;
        }

        .button-group {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            display: flex;
            gap: 10px;
        }

        .header-button {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: transform 0.3s ease, background-color 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header-button:hover {
            background-color: #2471a3;
            transform: translateY(-3px);
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #003F77;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #003F77;
            color: white;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .btn-chat {
            background-color: #007bff;
            color: white;
            padding: 6px 14px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-chat:hover {
            background-color: #0056b3;
        }

        .btn-answer {
            background-color: #27ae60;
            margin-right: 8px;
        }

        .btn-answer:hover {
            background-color: #1e8449;
        }
    </style>
</head>
<body>

<header>
    <img src="image/dorsu.png" alt="Davao Oriental State University">
    <div class="button-group">
        <a href="admin_dashboard.php" class="header-button">Home</a>
        <a href="student_list.php" class="header-button">Student List</a>
        <a href="manage_accounts.php" class="header-button">Manage Accounts</a>
        <a href="view_results.php" class="header-button">Results</a>
        <a href="logout.php" class="header-button" style="background-color: #e74c3c;">Logout</a>
    </div>
</header>

<div class="container">
    <h2>Support Requests</h2>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Requested At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="support-requests-body">
            <!-- Dynamic rows from fetch_support_requests.php -->
        </tbody>
    </table>
</div>

<script>
    function openChat(button) {
        const studentId = button.getAttribute('data-student-id');
        if (studentId) {
            window.location.href = 'admin_chat.php?student_ID=' + encodeURIComponent(studentId);
        } else {
            alert("Invalid student ID.");
        }
    }

    function fetchSupportRequests() {
        fetch('fetch_support_requests.php')
            .then(response => response.text())
            .then(html => {
                document.getElementById('support-requests-body').innerHTML = html;
            })
            .catch(error => console.error('Error fetching support requests:', error));
    }

    fetchSupportRequests();
    setInterval(fetchSupportRequests, 5000);
</script>

</body>
</html>


