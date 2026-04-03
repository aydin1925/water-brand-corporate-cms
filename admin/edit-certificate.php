<?php

session_start();

// Admin giriş yaptı mı kontrol?
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Veritabanını çağırıyorum
require_once '../config/db.php';

// Nesneleri oluşturuyorum
$database = new Database();
$db = $database->connect();

// 1. URL'den gelen ID'yi kontrol et ve belgeyi bul
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $certId = intval($_GET['id']);

    try {
        $sql = "SELECT * FROM certificates WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->execute([':id' => $certId]);
        $certificate = $statement->fetch(PDO::FETCH_ASSOC);

        // Eğer veritabanında böyle bir belge yoksa listeye geri gönder
        if(!$certificate) {
            header("Location: certificates.php");
            exit;
        }
    } catch(PDOException $e) {
        die("Veritabanı Hatası: " . $e->getMessage());
    }
} else {
    // ID yoksa veya sayı değilse listeye geri gönder
    header("Location: certificates.php");
    exit;
}

// 2. Form gönderildiğinde verileri güncelle (UPDATE)
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $title = trim($_POST['title']);
    $status = $_POST['status'];
    $expiryDate = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null;
    
    // Eski dosya yolunu varsayılan olarak tutuyoruz
    $filePath = $certificate['file_url'];

    // Yeni dosya yüklendi mi kontrol et
    if(isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === UPLOAD_ERR_OK) {
        
        $fileTmpPath = $_FILES['certificate_file']['tmp_name'];
        $fileName = $_FILES['certificate_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];

        if(in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('belge_') . '.' . $fileExtension;
            $uploadDir = '../uploads/certificates/';

            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $destination = $uploadDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $destination)) {
                // Yeni dosya başarıyla yüklendi, yolu güncelle
                $filePath = 'uploads/certificates/' . $newFileName;
                
                // Eski dosyayı sunucudan tamamen sil (Eğer varsa)
                if(!empty($certificate['file_url']) && file_exists("../" . $certificate['file_url'])) {
                    @unlink("../" . $certificate['file_url']);
                }
            } else {
                $_SESSION['error_msg'] = "Yeni dosya sunucuya yüklenirken bir hata oluştu.";
            }
        } else {
            $_SESSION['error_msg'] = "Sadece PDF, JPG, PNG veya WEBP formatlarına izin verilmektedir.";
        }
    }

    // Eğer yükleme aşamasında hata olmadıysa veritabanını güncelle
    if(!isset($_SESSION['error_msg'])) {
        try {
            $updateSql = "UPDATE certificates SET 
                            title = :title, 
                            file_url = :file_url, 
                            expiry_date = :expiry_date, 
                            status = :status 
                          WHERE id = :id";
            
            $updateStmt = $db->prepare($updateSql);
            
            $updateStmt->execute([
                ':title' => $title,
                ':file_url' => $filePath,
                ':expiry_date' => $expiryDate,
                ':status' => $status,
                ':id' => $certId
            ]);
        
            // Başarılı olduğunda session'a mesaj atıp ana listeye yönlendiriyoruz
            $_SESSION['success_msg'] = "Belge bilgileri başarıyla güncellendi.";
            header("Location: certificates.php");
            exit;
            
        } catch(PDOException $e) {
            $_SESSION['error_msg'] = "Sistem Hatası: Belge güncellenemedi.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belge Düzenle | Karacapınar Su</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/admin.css"> 
</head>
<body>

    <?php require_once '../includes/sidebar.php'; ?>

    <div class="admin-main-wrapper position-relative pb-5">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 30px; height: 3px; background-color: #00a8ff; border-radius: 2px;"></div>
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">KURUMSAL GÜVEN</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Belge Düzenle</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Mevcut sertifikanın bilgilerini, süresini veya dosyasını güncelleyin.</p>
            </div>
            
            <div>
                <a href="certificates.php" class="btn border fw-bold px-4 py-2 rounded-pill shadow-sm bg-white text-secondary" style="font-size: 14px; transition: 0.3s;">
                    <i class="fas fa-arrow-left me-2"></i> Listeye Dön
                </a>
            </div>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="row g-4">
                
                <div class="col-lg-8">
                    <div class="glass-panel p-4 p-md-5 h-100">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-pen-square text-brand-blue me-2"></i>Belge Detayları</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="custom-form-label">Belge Adı / Unvanı <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control custom-form-control" value="<?php echo htmlspecialchars($certificate['title']); ?>" required>
                                <div class="form-text mt-1" style="font-size: 11px;"><i class="fas fa-info-circle me-1"></i> Müşterilerin sitede göreceği resmi belge adıdır.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="custom-form-label">Geçerlilik Bitiş Tarihi</label>
                                <input type="date" name="expiry_date" class="form-control custom-form-control" value="<?php echo htmlspecialchars($certificate['expiry_date']); ?>">
                                <div class="form-text mt-1" style="font-size: 11px;"><i class="fas fa-info-circle me-1"></i> Belgenin süresi yoksa boş bırakabilirsiniz.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">Yayın Durumu <span class="text-danger">*</span></label>
                                <select name="status" class="form-select custom-form-control fw-bold" required>
                                    <option value="aktif" <?php if($certificate['status'] == "aktif") echo "selected"; ?>>Aktif (Sitede Görünsün)</option>
                                    <option value="pasif" <?php if($certificate['status'] == "pasif") echo "selected"; ?>>Pasif (Gizle)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="glass-panel p-4 mb-4 text-center h-100 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3 text-start"><i class="fas fa-file-pdf text-brand-blue me-2"></i>Belge Dosyası</h5>
                        
                        <div class="position-relative d-inline-block mx-auto mb-3">
                            <div class="bg-light border rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 140px; border-color: rgba(28, 79, 140, 0.1) !important;">
                                <?php 
                                    // Dosya PDF ise PDF ikonu, Resim ise Resim ikonu göster
                                    $ext = strtolower(pathinfo($certificate['file_url'], PATHINFO_EXTENSION));
                                    if($ext == 'pdf') {
                                        echo '<i class="fas fa-file-pdf fa-3x text-brand-main opacity-50"></i>';
                                    } else {
                                        echo '<i class="fas fa-file-image fa-3x text-brand-main opacity-50"></i>';
                                    }
                                ?>
                            </div>
                            
                            <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-2 shadow" style="transform: translate(25%, 25%); border: 3px solid #fff;" title="Mevcut Dosya Yüklü">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <a href="../<?php echo htmlspecialchars($certificate['file_url']); ?>" class="btn btn-sm btn-outline-secondary rounded-pill" target="_blank" style="font-size: 11px; font-weight: 700;">
                                <i class="fas fa-external-link-alt me-1"></i> Mevcut Dosyayı Görüntüle
                            </a>
                        </div>
                        
                        <div class="border-top pt-3 mt-1">
                            <label class="custom-form-label d-block text-start">Yeni Dosya Yükle (Değiştirmek İstersen)</label>
                            <input type="file" name="certificate_file" class="form-control custom-form-control form-control-sm" accept=".pdf, image/jpeg, image/png, image/webp">
                            <div class="form-text mt-2 text-start" style="font-size: 11px;">
                                <i class="fas fa-info-circle"></i> Sadece dosyayı değiştirmek istiyorsanız seçin. (PDF veya Resim, Max: 5MB).
                            </div>
                        </div>

                        <div class="mt-auto pt-4">
                            <button type="submit" class="btn w-100 rounded-pill py-3 fw-bolder text-white shadow-sm hover-scale" style="background: linear-gradient(45deg, #1C4F8C, #00a8ff); letter-spacing: 1px; border: none;">
                                <i class="fas fa-sync-alt me-2"></i> DEĞİŞİKLİKLERİ KAYDET
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/admin.js"></script>

    <?php if(isset($_SESSION['error_msg'])) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => { 
            showAlert('error', '<?php echo $_SESSION['error_msg']; ?>'); 
        });
    </script>
    <?php unset($_SESSION['error_msg']); } ?>

</body>
</html>