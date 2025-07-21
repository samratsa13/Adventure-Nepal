<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tourism Activities Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #009FFD, #2A2A72);
            color: white;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.3);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            
        }

        .navbar h2 {
            margin: 0;
            font-size: 24px;
            color: #fff;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            background: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }
.navbar p i{
    width: 20px;
    cursor:pointer;
}
        .navbar a:hover {
            background: #ff2a2a;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #fff;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .card-content {
            padding: 15px;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
        }

        .price {
            margin: 10px 0;
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            background: #00FFA3;
            color: #000;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #00cc87;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Adventure Nepal</h2>
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
           <div style="display: flex; align-items: center; gap: 15px;">
    <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
    <a href="logout.php">Logout</a>
</div>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>Explore Our Activities</h1>
        <div class="grid">

            <!-- Bungee -->
            <div class="card">
                <img src="bunjee.jpg" alt="Bungee Jumping">
                <div class="card-content">
                    <div class="card-title">Bungee Jumping</div>
                    <div class="price">Rs. 5000</div>
                    <a href="book.php?activity=bungee" class="btn">Book Now</a>
                </div>
            </div>

            <!-- Paragliding -->
            <div class="card">
                <img src="para.jpg" alt="Paragliding">
                <div class="card-content">
                    <div class="card-title">Paragliding</div>
                    <div class="price">Rs. 6000</div>
                    <a href="book.php?activity=paragliding" class="btn">Book Now</a>
                </div>
            </div>

            <!-- Kayaking -->
            <div class="card">
                <img src="kayak.jpg" alt="Kayaking">
                <div class="card-content">
                    <div class="card-title">Kayaking</div>
                    <div class="price">Rs. 3000</div>
                    <a href="book.php?activity=kayaking" class="btn">Book Now</a>
                </div>
            </div>

            <!-- Boating -->
            <div class="card">
                <img src="boat.jpg" alt="Boating">
                <div class="card-content">
                    <div class="card-title">Boating</div>
                    <div class="price">Rs. 1500</div>
                    <a href="book.php?activity=boating" class="btn">Book Now</a>
                </div>
            </div>

            <!-- Hot Air Balloon -->
            <div class="card">
                <img src="Baloon.jpg" alt="Hot Air Balloon">
                <div class="card-content">
                    <div class="card-title">Hot Air Balloon</div>
                    <div class="price">Rs. 8000</div>
                    <a href="book.php?activity=balloon" class="btn">Book Now</a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
