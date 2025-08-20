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
       body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #e0f7fa, #fce4ec);
    color: #2c3e50;
}

.container {
    width: 90%;
    margin: auto;
    padding: 40px 20px;
}

h1 {
    text-align: center;
    font-size: 2.5em;
    margin-bottom: 10px;
    background: linear-gradient(to right, #ff6f61, #ffca28);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

p {
    text-align: center;
    font-size: 1.2em;
    margin-bottom: 30px;
}

.card {
    background: linear-gradient(135deg, #ffffff, #f1f8e9);
    border-radius: 16px;
    padding: 25px;
    margin: 30px 0;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: scale(1.02);
}

h2 {
    font-size: 1.8em;
    margin-bottom: 15px;
    background: linear-gradient(to right, #42a5f5, #66bb6a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

table, th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
}

th {
    background: linear-gradient(to right, #81d4fa, #aed581);
    color: #2c3e50;
    font-weight: bold;
}

td {
    background-color: #fafafa;
}

input, button {
    padding: 10px 14px;
    margin: 8px 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1em;
}

input {
    background: #fff;
}

button {
    background: linear-gradient(to right, #ff8a65, #f06292);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background: linear-gradient(to right, #f44336, #e91e63);
}

a {
    color: #00796b;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* last ko chart */


button {
  background: linear-gradient(to right, #42a5f5, #66bb6a);
  color: white;
  border: none;
  padding: 12px 20px;
  font-size: 1em;
  border-radius: 8px;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: transform 0.2s ease;
}

button:hover {
  transform: scale(1.05);
  background: linear-gradient(to right, #1e88e5, #43a047);
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

<div class="card">
  <h2>📊 Most Ordered Activities</h2>
  <p>Click the button to visualize top services based on order frequency.</p>

  <?php
  $activityData = [];
  $query = "SELECT activity_name, COUNT(*) as total FROM orders GROUP BY activity_name ORDER BY total DESC";
  $result = $conn->query($query);

  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $activityData[] = [
              'activity' => $row['activity_name'],
              'count' => $row['total']
          ];
      }
  }
  ?>

  <button onclick="generateChart()">Generate Chart</button>
  <canvas id="dssChart" width="400" height="200" style="margin-top: 30px;"></canvas>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    function generateChart() {
      const ctx = document.getElementById('dssChart').getContext('2d');

      const labels = <?php echo json_encode(array_column($activityData, 'activity')); ?>;
      const data = <?php echo json_encode(array_column($activityData, 'count')); ?>;

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Orders per Activity',
            data: data,
            backgroundColor: [
              'rgba(255, 99, 132, 0.6)',
              'rgba(54, 162, 235, 0.6)',
              'rgba(255, 206, 86, 0.6)',
              'rgba(75, 192, 192, 0.6)',
              'rgba(153, 102, 255, 0.6)',
              'rgba(255, 159, 64, 0.6)'
            ],
            borderRadius: 8
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            title: {
              display: true,
              text: 'Most Ordered Activities',
              color: '#333',
              font: { size: 18 }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { color: '#333' }
            },
            x: {
              ticks: { color: '#333' }
            }
          }
        }
      });
    }
  </script>
</div>




    <div style="text-align:center; margin-top:20px;">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
