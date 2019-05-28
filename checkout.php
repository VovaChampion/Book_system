<?php

include "templates/header.php";
require_once('class/orders.php');
require_once('class/csv.php');

$orders = new Order();
$csv = new Upload();

// Create an order
if (isset($_POST['stripeToken'])) 
{
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_MAGIC_QUOTES);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    
    $result = $orders->sendStripe($name,$email,$phone);
}   

?>

<div class="container">
    <div class="table">
        <h4>Your ISBNs:</h4>
        <?php
            $file_details = $csv->showFile();
            echo $file_details;
        ?>
    </div>

    <div class="form">
        <form action="checkout.php" method="post" id="payment-form">
            <div class="form-row">
                <h4>Charge 99 SEK </h4><br>
                <div class="input-group mb-3">
                    <input type="text" name="name" placeholder="John Doe" required><br>
                    <div class="input-group-prepend">
                        <span class="input-group-text">Name</span>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" placeholder="Your Email" required>
                    <div class="input-group-append">
                        <span class="input-group-text">@example.com</span>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="tel" name="phone" pattern="[0-9]{10}" required>
                    <div class="input-group-append">
                        <span class="input-group-text">0701112233</span>
                    </div>
                </div>
                <label for="card-element">Credit or debit card</label>
                <div id="card-element">
                <!-- a Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display form errors -->
                <div id="card-errors"></div>
            </div><br>
            <button class="btn btn-success" name="create_order" value="Submit">Submit Payment</button>
        </form>
    </div>
</div>

<?php include "templates/footer.php"; ?>

<script src="https://js.stripe.com/v3/"></script>
<script src="charge.js"></script>