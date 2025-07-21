<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$cart_id = intval($_GET['cart_id']);
$conn = new mysqli("localhost", "root", "", "tourism_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$stmt = $conn->prepare("SELECT * FROM cart WHERE id = ? AND user_id = ? AND is_ordered = 0");
$stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$activity = $result->fetch_assoc();
$stmt->close();
if (!$activity) {
    die("Invalid cart ID or already ordered.");
}
$amt = floatval($activity['price']);
$taxamt = 0;
$servicecharge = 0;
$deliverycharge = 0;
$totalamt = $amt + $taxamt + $servicecharge + $deliverycharge;
$user_id = $_SESSION['user_id'];
$transaction_id = date("Ymd-His") . "-$user_id-" . uniqid(); 
$_SESSION['transaction_id'] = $transaction_id;
$_SESSION['cart_id'] = $cart_id; 
$product_code = 'EPAYTEST';
$success_url = "http://localhost/Tour/esewa_success.php";
$failure_url = "http://localhost/Tour/esewa_failed.php";
$secret_key = '8gBm/:&EnhH.1/q';
$signed_field_names = "total_amount,transaction_uuid,product_code";
$signature_data = "total_amount=$totalamt,transaction_uuid=$transaction_id,product_code=$product_code";
$signature = base64_encode(hash_hmac('sha256', $signature_data, $secret_key, true));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Confirm Payment</title>
    <style>
        /* Reset some default styles */
        body, h2, p, form {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            max-width: 420px;
            width: 100%;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #02632aff;
        }
        p {
            font-size: 18px;
            margin: 12px 0;
        }
        strong {
            color: #222;
        }
        form {
            margin-top: 30px;
        }

        button {
            background-color: #00cc11ff;
            color: white;
            font-size: 18px;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        button:hover {
            background-color: #00a344ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirm Your Booking</h2>
        <p>Activity: <strong><?= htmlspecialchars($activity['activity_name']) ?></strong></p>
        <p>Amount: Rs. <strong><?= number_format($totalamt, 2) ?></strong></p>
        <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
            <input type="hidden" name="amount" value="<?= $amt ?>" />
            <input type="hidden" name="tax_amount" value="<?= $taxamt ?>" />
            <input type="hidden" name="total_amount" value="<?= $totalamt ?>" />
            <input type="hidden" name="transaction_uuid" value="<?= $transaction_id ?>" />
            <input type="hidden" name="product_code" value="<?= $product_code ?>" />
            <input type="hidden" name="product_service_charge" value="<?= $servicecharge ?>" />
            <input type="hidden" name="product_delivery_charge" value="<?= $deliverycharge ?>" />
            <input type="hidden" name="success_url" value="<?= $success_url ?>" />
            <input type="hidden" name="failure_url" value="<?= $failure_url ?>" />
            <input type="hidden" name="signed_field_names" value="<?= $signed_field_names ?>" />
            <input type="hidden" name="signature" value="<?= $signature ?>" />
            <button type="submit">Proceed to eSewa</button>
        </form>
    </div>
</body>
</html>
