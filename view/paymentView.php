<?php function drawHeaderPayment() { ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>PaymentCard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/newservice.css">
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="../css/filter.css">
    <link rel="stylesheet" href="../css/payment.css">
</head>
<body>
    <header>
        <h1>Payment Service</h1>
    </header>
<?php } ?>

<?php function drawPaymentService($serviceInfo,$user_id) { ?>
<form id="paymentForm" method="post" action="../controller/ordersController.php">
    <input type="hidden" name="service_id" value="<?php echo $serviceInfo['id']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <section>
        <h2>Select a payment method</h2>
        <div class="payment-methods">
            <input type="radio" name="payment_method" id="mbway" value="mb" checked onclick="toggleForm('mb')">
            <label for="mbway">
                <img src="../images/mbWay.png" alt="MBWay">
                <span>MBWay</span>
            </label>

            <input type="radio" name="payment_method" id="mastercard" value="card" onclick="toggleForm('card')">
            <label for="mastercard">
                <img src="../images/mastercard.png" alt="MasterCard">
                <span>MasterCard</span>
            </label>
        </div>
    </section>
    
    <section>
        <div id="credit-form" class="form-section">
            <input type="text" id="cardNumber" name="card_number" placeholder="Card Number">
            <input type="text" id="cardName" name="card_name" placeholder="Cardholder Name">
        </div>
        <div id="mbWay-form" class="form-section">
            <input type="tel" id="phoneInput" name="phone_number" placeholder="Phone Number">
            <input type="text" id="fullName" name="full_name" placeholder="Full Name">
        </div>
    </section>
    
    <div class="price-box">
        <p>Value to Pay</p>
        <p><?php echo htmlspecialchars($serviceInfo['price']); ?> €</p>
    </div>
    <button type="submit" id="submitBtn">Confirm Payment</button>
    <label>
        <input type="checkbox" name="terms" required> 
        I accept the Terms & Conditions
    </label>
</form>

<script src="../js/payment.js"></script>
<?php } ?>