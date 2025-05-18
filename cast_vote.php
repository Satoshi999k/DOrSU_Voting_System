<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$username = "root";
$password = "";
$database = "voting_system";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$voter = $_SESSION['user']['student_ID'];

$check_voted = $conn->prepare("SELECT 1 FROM votes WHERE voter_student_id = ? LIMIT 1");
$check_voted->bind_param("s", $voter);
$check_voted->execute();
$check_voted->store_result();

if ($check_voted->num_rows > 0) {
    echo "<script>
        alert('You have already voted. You cannot vote again.');
        window.location.href = 'student_dashboard.php';
    </script>";
    $check_voted->close();
    $conn->close();
    exit;
}
$check_voted->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $votes = $_POST['vote'] ?? [];

    $position_map = [
        'mayor_candidate_1' => ['Earl Andre Galacio', 'Mayor'],
        'mayor_candidate_2' => ['Kenneth Semorlan', 'Mayor'],
        'Kentoy Bon Alabala' => ['Kentoy Bon Alabala', 'Secretary'],
        'Andrew Sentino Amongus' => ['Andrew Sentino Amongus', 'Secretary'],
        'Benjamin King Labad' => ['Benjamin King Labad', 'Treasurer'],
        'Paul John Herbert Yap' => ['Paul John Herbert Yap', 'Treasurer']
    ];

    foreach ($votes as $vote_key) {
        if (isset($position_map[$vote_key])) {
            [$candidate, $position] = $position_map[$vote_key];

            $stmt = $conn->prepare("INSERT INTO votes (candidate_name, position, votes, voter_student_id) VALUES (?, ?, 1, ?)");
            $stmt->bind_param("sss", $candidate, $position, $voter);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->close();
    echo "<script>
        alert('Thank you for voting! Your vote has been saved.');
        window.location.href = 'student_dashboard.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote - Election 2025</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f4f7;
            color: #333;
        }

        header {
            background-color: #003F77;
            padding: 20px;
            text-align: left;
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

        .header-buttons {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            display: flex;
            gap: 10px;
        }

        .header-buttons a {
            background-color: #003F77;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: transform 0.3s ease, background-color 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

        h1 {
            text-align: center;
            margin: 30px 0 20px;
            color: #003F77;
        }

        .table-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        th {
            background-color: #003F77;
            color: white;
            padding: 12px 20px;
            text-align: left;
        }

        tr {
            border: 30px solid #fff;
            border-radius: 15px;
        }

        td {
            background-color: #f9f9f9;
            padding: 12px 20px;
            border: 2px solid #ccc;
            border-radius: 10px;
        }

        .candidate {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .candidate img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .candidate-cell {
            padding-left: 40px;
        }

        button {
            display: block;
            margin: 30px auto 60px;
            padding: 12px 25px;
            background-color: #003F77;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056a3;
        }
    </style>
</head>

<body>
<header>
    <img src="image/dorsu.png" alt="Davao Oriental State University">
    <div class="button-group">
        <a href="student_dashboard.php" class="header-button" style="background-color: #2980b9;">Home</a>
        <a href="view_results.php" class="header-button" style="background-color: #2980b9;">Results</a>
        <a href="logout.php" class="header-button">Logout</a>
    </div>
</header>

<h1>Election 2025 - Class Officers</h1>

<form method="POST" action="cast_vote.php">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Candidates</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Mayor</strong></td>
                    <td class="candidate-cell">
                        <label class="candidate">
                            <input type="checkbox" name="vote[]" value="mayor_candidate_1" data-position="mayor">
                            <img src="image/Earl.jpg" alt="Earl Andre Galacio">
                            Earl Andre Galacio
                        </label><br>
                        <label class="candidate">
                            <input type="checkbox" name="vote[]" value="mayor_candidate_2" data-position="mayor">
                            <img src="image/Kenneth.jpg" alt="Kenneth Semorlan">
                            Kenneth Semorlan
                        </label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Secretary</strong></td>
                    <td class="candidate-cell">
                        <label class="candidate">
                            <input type="checkbox" name="vote[]" value="Kentoy Bon Alabala" data-position="secretary">
                            <img src="image/Kentoy.jpg" alt="Kentoy Bon Alabala">
                            Kentoy Bon Alabala
                        </label><br>
                        <label class="candidate">
                            <input type="checkbox" name="vote[]" value="Andrew Sentino Amongus" data-position="secretary">
                            <img src="image/Andrew.jpg" alt="Andrew Sentino Amongus">
                            Andrew Sentino Amongus
                        </label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Treasurer</strong></td>
                    <td class="candidate-cell">
                        <label class="candidate">
                            <input type="checkbox" name="vote[]" value="Benjamin King Labad" data-position="treasurer">
                            <img src="image/Benjamin.jpg" alt="Benjamin King Labad">
                            Benjamin King Labad
                        </label><br>
                        <label class="candidate">
                            <input type="checkbox" name="vote[]" value="Paul John Herbert Yap" data-position="treasurer">
                            <img src="image/Paul.jpg" alt="Paul John Herbert Yap">
                            Paul John Herbert Yap
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <button type="submit">Submit Vote</button>
</form>

<script>
    document.querySelectorAll('input[type="checkbox"][data-position]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const pos = this.dataset.position;
            if (this.checked) {
                document.querySelectorAll(`input[data-position="${pos}"]`).forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }
        });
    });

    document.querySelector('form').addEventListener('submit', function (e) {
        const positions = ['mayor', 'secretary', 'treasurer'];
        let allChecked = true;

        for (const pos of positions) {
            const checked = document.querySelector(`input[data-position="${pos}"]:checked`);
            if (!checked) {
                allChecked = false;
                break;
            }
        }

        if (!allChecked) {
            e.preventDefault();
            alert('Please vote for one candidate in each position before submitting.');
        }
    });
</script>

</body>
</html>

