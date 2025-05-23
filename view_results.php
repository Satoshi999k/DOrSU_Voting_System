<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT candidate_name, position, COUNT(*) AS votes FROM votes GROUP BY candidate_name, position ORDER BY position, votes DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Election Results</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
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
            background-color: #e74c3c;
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
            background-color: #c0392b;
            transform: translateY(-3px);
        }

        .results-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #003F77;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
        }

        .candidate-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
            vertical-align: middle;
        }

        .candidate-cell {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>

<header>
    <img src="image/dorsu.png" alt="Davao Oriental State University">
    <div class="button-group">
        <a href="student_dashboard.php" class="header-button" style="background-color: #2980b9;">Home</a>
        <a href="cast_vote.php" class="header-button" style="background-color: #2980b9;">Vote</a>
        <a href="chat_ui.php" class="header-button" style="background-color: #2980b9;">Support</a>
        <a href="logout.php" class="header-button">Logout</a>
    </div>
</header>

<div class="results-container">
    <h2>Election Results</h2>
    <table>
        <thead>
            <tr>
                <th>Candidate</th>
                <th>Position</th>
                <th>Total Votes</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()):
                $candidateName = $row['candidate_name'];
                $imageName = str_replace(' ', '_', $candidateName) . '.jpg';
                $imagePath = "candidate_images/$imageName";
                $imageTag = file_exists($imagePath) ?
                    "<img src='$imagePath' alt='$candidateName' class='candidate-img'>" :
                    "<img src='candidate_images/default.jpg' alt='No Image' class='candidate-img'>";
            ?>
                <tr>
                    <td class="candidate-cell">
                        <?= $imageTag ?>
                        <?= htmlspecialchars($candidateName) ?>
                    </td>
                    <td><?= htmlspecialchars($row['position']) ?></td>
                    <td><?= $row['votes'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="no-data">No votes have been cast yet.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>


