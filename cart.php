<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tourism_db");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND is_ordered = 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #a1c4fd, #c2e9fb);
            margin: 0;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: center;
        }
        th {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .order-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            margin-left: 10px;
        }
        .actions {
            display: flex;
            justify-content: center;
        }
        .back-btn {
    display: inline-block;
    margin-bottom: 20px;
    margin-left: 10%;
    padding: 10px 20px;
    background: linear-gradient(to right, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: 0.3s;
}
.back-btn:hover {
    background: linear-gradient(to right, #5a67d8, #6b46c1);
}
    </style>
</head>
<body>
<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
<h2>My Cart</h2>
<table>
    <tr><th>Activity</th><th>Price</th><th>Action</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['activity_name']) ?></td>
        <td>Rs. <?= number_format($row['price'], 2) ?></td>
        <td class="actions">
            <form method="get" action="order.php" style="display:inline;">
                <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                <button type="submit" class="order-btn">Order</button>
            </form>
            <form method="post" action="delete_cart.php" style="display:inline;">
                <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                <button type="submit" class="delete-btn">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
