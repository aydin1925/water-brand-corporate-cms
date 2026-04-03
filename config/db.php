<?php

class Database {

private $host = "localhost";
private $db_name = "karacapinar_db";
private $username = "root";
private $password = "";

//Bağlantı nesnesi
public $conn;

// Bağlantı fonksiyonu
public function connect() {
    $this->conn = null;

    try{
        // Veritabanı bağlantısı
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";

        // PDO nesnesi oluşturuyorum
        $this->conn = new PDO($dsn, $this->username, $this->password);

        // Veriler varsayılan olarak diziye dönüşsün
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    catch(PDOException $e) {
        echo "Bağlantı hatası: " . $e->getMessage();
    }

    return $this->conn;
}
}