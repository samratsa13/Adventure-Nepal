<?php
session_start();

// ✅ Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ✅ Database connection
$conn = new mysqli("localhost", "root", "", "tourism_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Reuse your main CSS -->
    <style>
        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 { color: #2c3e50; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th { background: #f5f5f5; }
        input, button {
            padding: 8px 12px;
            margin: 5px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        button {
            background: #2c3e50;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background: #34495e;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome, Admin 👨‍💼</h1>
    <p>Manage users, bookings, and view reports.</p>

    <!-- MIS: User Report -->
    <div class="card">
        <h2>📊 Users Report</h2>
        <?php
        $result = $conn->query("SELECT id, full_name, email, usertype FROM users");
        if ($result && $result->num_rows > 0) {
            echo "<table>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>User Type</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['usertype']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
    </div>

    <!-- MIS: Bookings Report -->
    <!-- MIS: Orders Report -->
<div class="card">
    <h2>🧾 Orders Report</h2>
    <?php
    $orders = $conn->query("SELECT o.id, u.full_name, o.activity_name, o.price, o.created_at, o.payment_status  
                            FROM orders o  
                            JOIN users u ON o.user_id = u.id");

    if ($orders && $orders->num_rows > 0) {
        echo "<table>
                <tr><th>ID</th><th>User</th><th>Activity</th><th>Price (Rs)</th><th>Date</th><th>Status</th></tr>";
        while ($row = $orders->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['activity_name']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['created_at']}</td>
                    <td>{$row['payment_status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No orders found.</p>";
    }
    ?>
</div>

    <!-- DSS: Decision Support Example -->
    <div class="card">
        <h2>🧠 DSS - Revenue Forecast Tool</h2>
        <form method="post">
            <label>Average price per booking (Rs):</label>
            <input type="number" name="price" required>
            <label>Expected bookings next month:</label>
            <input type="number" name="bookings" required>
            <button type="submit">Forecast</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['price']) && isset($_POST['bookings'])) {
            $price = (float)$_POST['price'];
            $bookings = (int)$_POST['bookings'];
            $revenue = $price * $bookings;
            echo "<p><b>📈 Forecasted Revenue:</b> Rs. $revenue</p>";
        }
        ?>
    </div>

    <div style="text-align:center; margin-top:20px;">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
