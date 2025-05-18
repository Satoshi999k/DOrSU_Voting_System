<?php
$host = "localhost";
$dbname = "voting_system";
$username = "root";
$password = "";     

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uname     = $_POST['username'];
        $pass      = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email     = $_POST['email'];
        $fname     = $_POST['firstname'];
        $sname     = $_POST['surname'];
        $studentId = $_POST['student_id'];
        $city      = $_POST['city'];
        $country   = $_POST['country'];

        $stmt = $conn->prepare("INSERT INTO user 
            (username, password, email, firstname, surname, student_id, city, country) 
            VALUES (:username, :password, :email, :firstname, :surname, :student_id, :city, :country)");

        $stmt->execute([
            ':username'   => $uname,
            ':password'   => $pass,
            ':email'      => $email,
            ':firstname'  => $fname,
            ':surname'    => $sname,
            ':student_id' => $studentId,
            ':city'       => $city,
            ':country'    => $country
        ]);

        echo "<script>
            alert('Account created successfully!');
            window.location.href = 'login.php';
        </script>";
        exit;
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Create New Account - DOrSU</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('image/frontpage.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .logo-container img {
            max-width: 850px;
            margin-top: 20px;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px; 
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            background-color: #1E88E5;
            cursor: pointer;
        }
        .btn-container button.cancel {
            background-color: #aaa;
        }
        .btn-container button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="logo-container">
        <img src="image/dorsu.png" alt="DOrSU Logo" />
    </div>
    <div class="form-container">
        <h2>New Account</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required />
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required />
            </div>
            <div class="form-group">
                <label>Email address</label>
                <input type="email" name="email" required />
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="firstname" required />
            </div>
            <div class="form-group">
                <label>Surname</label>
                <input type="text" name="surname" required />
            </div>
            <div class="form-group">
                <label>Student ID</label>
                <input type="text" name="student_id" placeholder="2023 - 1002" required />
            </div>
            <div class="form-group">
                <label>City/Town</label>
                <input type="text" name="city" value="Mati City" required />
            </div>
            <div class="form-group">
                <label>Country</label>
                <select name="country" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="Philippines" selected>Philippines</option>
                    <option value="United States">United States</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="India">India</option>
                    <option value="Germany">Germany</option>
                    <option value="France">France</option>
                    <option value="Japan">Japan</option>
                    <option value="China">China</option>
                </select>
            </div>
            <div class="btn-container">
                <button type="submit">Create my new account</button>
                <button type="button" class="cancel" onclick="window.history.back();">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
