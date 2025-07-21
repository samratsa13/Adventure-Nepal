<?php
session_start();
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "tourism_db");
    if ($conn->connect_error) die("Connection failed");

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $name, $email, $hashed);
            if ($insert->execute()) {
                $success = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Error while registering.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Adventure Nepal</title>
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #010101);
            font-family: sans-serif;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .form-box {
            background: rgba(0,0,0,0.3);
            padding: 30px;
            border-radius: 15px;
            width: 350px;
        }
        h2 {
            text-align: center;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: none;
            border-radius: 8px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #ffcc00;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .error, .success {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            text-align: center;
        }
        .error {
            background: #cc0000;
            color: #fff;
        }
        .success {
            background: #00aa00;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Create an Account</h2>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="name" required placeholder="Full Name">
        <input type="email" name="email" required placeholder="Email Address">
        <input type="password" name="password" required placeholder="Password">
        <input type="password" name="confirm" required placeholder="Confirm Password">
        <button type="submit">Register</button>
        <p>Already a Member <a href="login.php">Login</a></p>
    </form>
</div>

</body>
</html>
