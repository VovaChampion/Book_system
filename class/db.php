<?php
class DB
{
    private $host = 'localhost';
    private $db   = 'bookstore';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    protected $conn;

    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass); //$this->options
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    public function connect(){
        return $this->conn;
    }
}

?>