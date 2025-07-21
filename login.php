<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "tourism_db");
    if ($conn->connect_error) die("Connection failed");

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Account not found";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Adventure Nepal</title>
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
            width: 300px;
        }
        h2 {
            text-align: center;
        }
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
        .error {
            color: #ffdddd;
            background: #990000;
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" required placeholder="Email Address">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Login</button>
        <p>New here? <a href="register.php">Register Now </a></p>
    </form>
</div>

</body>
</html>
