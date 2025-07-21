<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
    $conn = new mysqli("localhost", "root", "", "tourism_db");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

header("Location: cart.php");
exit;
