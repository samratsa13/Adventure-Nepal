<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tourism_db");

$user_id = $_SESSION['user_id'];
$cart_id = $_SESSION['cart_id'] ?? null;
$transaction_id = $_SESSION['transaction_id'] ?? null;

if (!$cart_id || !$transaction_id) {
    die("Invalid session data.");
}

// Fetch from cart
$stmt = $conn->prepare("SELECT activity_name, price FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cart_id, $user_id);
$stmt->execute();
$activity = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($activity) {
    // Mark cart as ordered
    $conn->query("UPDATE cart SET is_ordered = 1 WHERE id = $cart_id");

    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, activity_name, price, payment_status) VALUES (?, ?, ?, 'Paid')");
    $stmt->bind_param("isd", $user_id, $activity['activity_name'], $activity['price']);
    $stmt->execute();
    $stmt->close();
}

header("Location: my_orders.php");
exit;
