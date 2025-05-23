<?php
session_start();

// ✅ Ensure only admins can access this page
if (!isset($_SESSION['admin_user'])) {
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
            color: #333;
            background-color: #f4f6f9;
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
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            text-align: center;
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: bold;
            color: #003F77;
            margin-bottom: 30px;
        }

        .dashboard-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .dashboard-buttons a {
            background-color: #ffffff;
            border: 2px solid #003F77;
            color: #003F77;
            text-decoration: none;
            padding: 20px 30px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            min-width: 180px;
        }

        .dashboard-buttons a:hover {
            background-color: #003F77;
            color: white;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<header>
    <img src="image/dorsu.png" alt="Davao Oriental State University">
    <a href="logout.php" class="logout-button">Logout</a>
</header>

<div class="dashboard-container">
    <div class="dashboard-title">
        Welcome, <?php echo htmlspecialchars($_SESSION['admin_user']['username']); ?>
    </div>
    <div class="dashboard-buttons">
        <a href="vote_results.php">Vote Results</a>
        <a href="student_list.php">Student List</a>
        <a href="admin_accounts.php">Manage Accounts</a>
        <a href="support_requests.php">Support Requests</a>
    </div>
</div>

</body>
</html>
