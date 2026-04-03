<?php
session_start();

// Admin giriş kontrolü
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/db.php';

$database = new Database();
$db = $database->connect();

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pId = intval($_GET['id']);

    try {
        // 1. Önce silinecek ürünün resim yolunu bulalım (Sunucudan da silmek için)
        $imgStmt = $db->prepare("SELECT image_url FROM products WHERE id = :id");
        $imgStmt->execute([':id' => $pId]);
        $product = $imgStmt->fetch(PDO::FETCH_ASSOC);

        if($product) {
            // 2. Eğer ürünün bir resmi varsa fiziksel olarak sunucudan sil
            if(!empty($product['image_url'])) {
                $physicalPath = "../" . $product['image_url'];
                if(file_exists($physicalPath)) {
                    @unlink($physicalPath);
                }
            }

            // 3. Veritabanından tamamen sil (Hard Delete)
            $deleteStmt = $db->prepare("DELETE FROM products WHERE id = :id");
            $deleteStmt->execute([':id' => $pId]);

            $_SESSION['success_msg'] = "Ürün ve ona ait görsel sistemden tamamen silindi.";
        } else {
            $_SESSION['error_msg'] = "Silinmek istenen ürün bulunamadı.";
        }

    } catch(PDOException $e) {
        $_SESSION['error_msg'] = "Sistem Hatası: Ürün silinemedi. (" . $e->getMessage() . ")";
    }

} else {
    $_SESSION['error_msg'] = "Geçersiz işlem.";
}

// İşlem bitince listeye geri dön
header("Location: products.php");
exit;
?>