<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tourism_db");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT activity_name, price, payment_status, created_at FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            margin: 0;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #333;
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
            background: linear-gradient(to right, #ff6a00, #ee0979);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

<h2>My Orders</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr><th>Activity</th><th>Price</th><th>Ordered At</th><th>Status</th></tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['activity_name']) ?></td>
                <td>Rs. <?= number_format($row['price'], 2) ?></td>
                <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
                <td><?= htmlspecialchars($row['payment_status']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p style="text-align:center;">You have not ordered any activities yet.</p>
<?php endif; ?>

</body>
</html>
