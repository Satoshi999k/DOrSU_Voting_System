<?php
require 'db.php';

$result = $conn->query("SELECT student_ID, created_at FROM support_queue ORDER BY created_at ASC");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['student_ID']) . '</td>';
        echo '<td>' . $row['created_at'] . '</td>';
        echo '<td>
                <form method="POST" style="display:inline;" action="support_requests.php">
                    <input type="hidden" name="student_ID" value="' . htmlspecialchars($row['student_ID']) . '">
                    <button type="submit" name="mark_answered" class="btn-chat btn-answer">Mark as Answered</button>
                </form>
                <button class="btn-chat" data-student-id="' . htmlspecialchars($row['student_ID']) . '" onclick="openChat(this)">Open Chat</button>
              </td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3" style="text-align:center; color:#777;">No active support requests.</td></tr>';
}
?>
