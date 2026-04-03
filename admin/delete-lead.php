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
    
    $leadId = intval($_GET['id']);

    try {
        // 2. Veritabanından talebi (lead) tamamen sil
        $deleteSql = "DELETE FROM leads WHERE id = :id";
        $deleteStmt = $db->prepare($deleteSql);
        $deleteStmt->execute([':id' => $leadId]);

        // 3. Başarılı mesajını session'a at
        $_SESSION['success_msg'] = "Talep sistemden kalıcı olarak silindi.";

    } catch(PDOException $e) {
        // Hata durumunda mesajı at
        $_SESSION['error_msg'] = "Sistem Hatası: Talep silinemedi.";
    }

} else {
    // ID gelmemişse veya hatalıysa
    $_SESSION['error_msg'] = "Geçersiz işlem.";
}

// 4. Ne olursa olsun işlemin sonunda müşteri talepleri listesine geri dön
header("Location: leads.php");
exit;

?>