<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt_admin = $conn->prepare("SELECT * FROM admin_user WHERE username = ?");
    $stmt_admin->bind_param("s", $username);
    $stmt_admin->execute();
    $res_admin = $stmt_admin->get_result();

    if ($res_admin->num_rows > 0) {
        $admin = $res_admin->fetch_assoc();

        if ($password === $admin['password']) {
            $_SESSION['admin_user'] = $admin;
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "<script>alert('Invalid admin password.'); window.location.href='login.php';</script>";
            exit;
        }
    }

    $stmt_user = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $res_user = $stmt_user->get_result();

    if ($res_user->num_rows > 0) {
        $user = $res_user->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['role'] = 'student';
            header("Location: student_dashboard.php");
            exit;
        } else {
            echo "<script>alert('Invalid student password.'); window.location.href='login.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Davao Oriental State University</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('image/frontpage.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .logo-container img {
            width: 100%;
            max-width: 850px;
            margin-bottom: 70px;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
        }

        .info-box {
            background-color: #1E88E5;
            color: white;
            padding: 20px;
            border-right: 10px;
            border-left: none;
            border-radius: 10px 0 0 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 320px;
            text-align: left;
            height: 284px;
            margin-right: -20px;
        }
        .info-box h3 {
            margin-bottom: 20px;
            font-size: 25px;
        }
        .info-box hr {
            margin: 10px 0;
            border: none;
            border-top: 1px solid white;
            width: 70%;
        }
        .info-box p {
            margin: 10px 0;
        }
        .info-box img {
            width: 30px;
            height: 30px;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-right: 10px;
            border-left: none;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: left;
            width: 320px;
            margin-bottom: 80px;
            height: 284px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            margin-top: 15px;
            font-size: 20px;
        }
        .login-container input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container button {
            width: 96.5%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .login-container a {
            display: block;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="image/dorsu.png" alt="Davao Oriental State University">
    </div>
    <div class="container">
        <div class="info-box">
            <h3>DOrSU Student's Feedback</h3>
            <hr>
            <p>Participate in the school voting system to make your voice heard and contribute to the selection of student leaders and representatives.</p>
        </div>
        <div class="login-container">
            <h2>Already have an account?</h2>
            <form method="POST">
                Username: <input name="username" required><br>
                Password: <input type="password" name="password" required><br>
                <button type="submit">Login</button>
            </form>
            <a href="create_account.php">Create Account</a>
        </div>
    </div>
</body>
</html>
