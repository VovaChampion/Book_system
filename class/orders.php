<?php
require_once('class/db.php');

class Order 
{
    private $db;

    public function __construct() 
    {
        $this->db = new Db();
        $this->db = $this->db->connect();
    }
    
    // get all orders
    public function getOrders() 
    {
        $stmt = $this->db->prepare('SELECT * FROM orders');
        if ($stmt->execute()){
            if ($stmt->rowCount()>0){
                while ($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    $data[] =$row;
                }
                return $data;
            }
        }
    }

    // // create a new order
    public function createOrder($name,$email,$phone)
    {
        $date = date('Y-m-d H:i:s');
        $amount = 99;
        
        $stmt = $this->db->prepare('INSERT INTO orders (name, email, phone, amount, date) 
        VALUES (:name, :email, :phone, :amount, :date);');
        
        $stmt->execute([
            ':name' => $name, 
            ':email' => $email,
            ':phone' => $phone, 
            ':amount' => $amount,
            ':date' => $date
        ]);

        // $id_order = $this->db->lastInsertId(); 
        // return $id_order;
        
        header("Location:confirm.php");
    }

    public function sendStripe($name,$email,$phone)
    {
        require_once('vendor/stripe/stripe-php/init.php');
        \Stripe\Stripe::setApiKey('sk_test_Y5Fm9BboJjOtQwUFG4N7AzTk'); //YOUR_STRIPE_SECRET_KEY

        $token = (isset($_POST['stripeToken'])) ? $_POST['stripeToken'] : null;

        $name_last = "Sunset";
        $address = "Vasagatan";
        $state = "Stockholm";
        $zip = "12050";
        $country = "Sweden";
        $user_info = [
            'First Name' => $name,
            'Last Name' => $name_last,
            'Address' => $address,
            'State' => $state,
            'Zip Code' => $zip,
            'Country' => $country,
            'Phone' => $phone
        ];
        // $customer_id = 'cus_F6Ai4gLolcMAb3';
        if (isset($customer_id)) 
        {
            try {
                // Use Stripe's library to make requests...
                $customer = \Stripe\Customer::retrieve($customer_id);
            } catch (\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $body = $e->getJsonBody();
                $err  = $body['error'];
            
                print('Status is:' . $e->getHttpStatus() . "\n");
                print('Type is:' . $err['type'] . "\n");
                print('Code is:' . $err['code'] . "\n");
                // param is '' in this case
                print('Param is:' . $err['param'] . "\n");
                print('Message is:' . $err['message'] . "\n");
            } catch (\Stripe\Error\RateLimit $e) {
                // Too many requests made to the API too quickly
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
            } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
            }
        } else {
            try {
                // Use Stripe's library to make requests...
                $customer = \Stripe\Customer::create(array(
                    'email' => $email,
                    'source' => $token,
                    'metadata' => $user_info,
                ));
            } catch (\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $body = $e->getJsonBody();
                $err  = $body['error'];
            
                print('Status is:' . $e->getHttpStatus() . "\n");
                print('Type is:' . $err['type'] . "\n");
                print('Code is:' . $err['code'] . "\n");
                // param is '' in this case
                print('Param is:' . $err['param'] . "\n");
                print('Message is:' . $err['message'] . "\n");
            } catch (\Stripe\Error\RateLimit $e) {
                // Too many requests made to the API too quickly
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
            } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
            }
        }

        if (isset($customer)) 
        {
            //print_r($customer);
            $charge_customer = true;

            // Save the customer in your own database!
            $this->createOrder($name,$email,$phone);

            // Charge the Customer instead of the card
            try {
                // Use Stripe's library to make requests...
                $charge = \Stripe\Charge::create(array(
                    'amount' => 9900,
                    'description' => 'Books',
                    'currency' => 'sek',
                    'customer' => $customer->id,
                    'metadata' => $user_info
                ));
            } catch (\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $body = $e->getJsonBody();
                $err  = $body['error'];
            
                print('Status is:' . $e->getHttpStatus() . "\n");
                print('Type is:' . $err['type'] . "\n");
                print('Code is:' . $err['code'] . "\n");
                // param is '' in this case
                print('Param is:' . $err['param'] . "\n");
                print('Message is:' . $err['message'] . "\n");
                $charge_customer = false;
            } catch (\Stripe\Error\RateLimit $e) {
                // Too many requests made to the API too quickly
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
            } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
            }
            if ($charge_customer) 
            {
                //print_r($charge);
            }
        }
    }

    // get user email
    public function getUser($email) 
    {
        $stmt = $this->db->prepare('SELECT email FROM orders WHERE email = :email');
        
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }
}

?>