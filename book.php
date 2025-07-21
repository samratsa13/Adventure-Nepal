<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['activity'])) {
    die("Invalid activity request.");
}

$activity_name = $_GET['activity'];
$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "tourism_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get activity info from DB
$stmt = $conn->prepare("SELECT price FROM activities WHERE name = ?");
$stmt->bind_param("s", $activity_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Activity not found.");
}

$activity = $result->fetch_assoc();
$price = $activity['price'];

// Insert into cart table
$stmt = $conn->prepare("INSERT INTO cart (user_id, activity_name, price, is_ordered) VALUES (?, ?, ?, 0)");
$stmt->bind_param("isd", $user_id, $activity_name, $price);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: cart.php");
exit();
?>
