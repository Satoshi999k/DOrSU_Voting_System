<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = $_POST['message'];
    $user_id = $_SESSION['user']['id'];
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $msg);
    $stmt->execute();
    echo "Feedback submitted!";
}
?>

<form method="POST">
    <h2>Submit Feedback</h2>
    <textarea name="message" required></textarea><br>
    <button type="submit">Send</button>
</form>
