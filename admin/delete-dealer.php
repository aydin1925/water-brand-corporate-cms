<?php

session_start();

// Admin giriş yaptı mı kontrol?
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Veritabanını çağırıyorum
require_once '../config/db.php';
$database = new Database();
$db = $database->connect();

// 1. URL'den gelen ID'yi kontrol et (Var mı ve sayı mı?)
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    $dealerId = intval($_GET['id']);

    try {
        // 2. Veritabanından bayiyi tamamen sil (Hard Delete)
        $sql = "DELETE FROM dealers WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->execute([':id' => $dealerId]);

        // 3. Başarılı mesajını session'a at
        $_SESSION['success_msg'] = "Bayi sistemden tamamen silindi.";

    } catch(PDOException $e) {
        // Hata durumunda mesajı at
        $_SESSION['error_msg'] = "Sistem Hatası: Bayi silinemedi.";
    }

} else {
    // ID gelmemişse veya hatalıysa
    $_SESSION['error_msg'] = "Geçersiz işlem.";
}

// 4. Ne olursa olsun işlemin sonunda bayi listesine geri dön
header("Location: dealers.php");
exit;

?>