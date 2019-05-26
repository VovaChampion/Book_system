<?php

class ORDERS 
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
        if($stmt->execute()){
            if($stmt->rowCount()>0){
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
        // $stmt = $this->db->query("SELECT LAST_INSERT_ID()");
        // $id_order = $stmt->fetchColumn();
        
        //header("Location:confirm.php");
    }
}

?>