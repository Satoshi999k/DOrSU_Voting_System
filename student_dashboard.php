<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DOrSU Students' Voting System Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            color: #B0B0B0;
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

        .logout-button {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
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

        .logout-button:hover {
            background-color: #c0392b;
            transform: translateY(-3px);
        }

        .dashboard-container {
            max-width: 800px;
            margin: 80px auto;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            padding: 40px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease, background-color 0.3s ease;
            cursor: pointer;
        }

        .dashboard-container:hover {
            background-color: #f0f8ff;
            transform: translateY(-3px);
        }

        h2 {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
            text-align: left;
        }

        .subtext {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #ffffff;
            font-size: 14px;
        }

        footer a {
            color: #ffffff;
            text-decoration: underline;
        }

        footer a:hover {
            text-decoration: none;
        }
    </style>
    <script>
        function navigateTo(url) {
            window.location.href = url;
        }
    </script>
</head>
<body>

<header>
    <img src="image/dorsu.png" alt="Davao Oriental State University">
    <a href="logout.php" class="logout-button">Logout</a>
</header>

<div class="dashboard-container" onclick="navigateTo('cast_vote.php')">
    <div style="text-align: left;">
        <h2>2025 CLASS OFFICERS ELECTION</h2>
        <p class="subtext">From: May 1 - 2, 2025</p>
        <p class="subtext">Vote wisely</p>
    </div>
</div>

<div class="dashboard-container" onclick="navigateTo('view_results.php')">
    <div style="text-align: left;">
        <h2>ELECTION RESULTS</h2>
        <p class="subtext">View the results of the election</p>
    </div>
</div>

</body>
</html>
