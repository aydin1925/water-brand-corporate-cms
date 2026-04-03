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
    
    $certId = intval($_GET['id']);

    try {
        // 2. Önce silinecek belgenin dosya yolunu bulalım (Sunucudan silmek için)
        $findSql = "SELECT file_url FROM certificates WHERE id = :id";
        $findStmt = $db->prepare($findSql);
        $findStmt->execute([':id' => $certId]);
        $certificate = $findStmt->fetch(PDO::FETCH_ASSOC);

        if($certificate) {
            // 3. Eğer belgenin bir dosyası varsa fiziksel olarak sunucudan sil
            if(!empty($certificate['file_url'])) {
                $physicalPath = "../" . $certificate['file_url'];
                if(file_exists($physicalPath)) {
                    @unlink($physicalPath); // Dosyayı klasörden siler
                }
            }

            // 4. Veritabanından belge kaydını tamamen sil
            $deleteSql = "DELETE FROM certificates WHERE id = :id";
            $deleteStmt = $db->prepare($deleteSql);
            $deleteStmt->execute([':id' => $certId]);

            // 5. Başarılı mesajını session'a at
            $_SESSION['success_msg'] = "Kalite belgesi ve dosyası sistemden tamamen silindi.";
        } else {
            $_SESSION['error_msg'] = "Silinmek istenen belge bulunamadı.";
        }

    } catch(PDOException $e) {
        // Hata durumunda mesajı at
        $_SESSION['error_msg'] = "Sistem Hatası: Belge silinemedi.";
    }

} else {
    // ID gelmemişse veya hatalıysa
    $_SESSION['error_msg'] = "Geçersiz işlem.";
}

// 6. Ne olursa olsun işlemin sonunda belgeler listesine geri dön
header("Location: certificates.php");
exit;

?>