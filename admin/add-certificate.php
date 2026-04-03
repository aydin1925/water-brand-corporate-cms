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

// Form gönderildiğinde işlemleri başlat
if($_SERVER['REQUEST_METHOD'] == "POST") {

    // 1. Formdan gelen metin verilerini alıyorum
    $title = trim($_POST['title']);
    $status = $_POST['status'];
    
    // Tarih boş gönderilmişse veritabanına NULL olarak yazmak için kontrol ediyorum
    $expiryDate = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null;
    
    $filePath = null;

    // 2. Dosya yükleme (Upload) İşlemi
    if(isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === UPLOAD_ERR_OK) {
        
        $fileTmpPath = $_FILES['certificate_file']['tmp_name'];
        $fileName = $_FILES['certificate_file']['name'];
        
        // Dosyanın uzantısını buluyorum (pdf, jpg, png vb.)
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // İzin verilen uzantılar
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];

        if(in_array($fileExtension, $allowedExtensions)) {
            
            // Dosya isminin çakışmaması için benzersiz bir isim üretiyorum
            $newFileName = uniqid('belge_') . '.' . $fileExtension;
            $uploadDir = '../uploads/certificates/';

            // Eğer klasör yoksa oluştur
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $destination = $uploadDir . $newFileName;

            // Dosyayı geçici dizinden asıl klasörüne taşı
            if(move_uploaded_file($fileTmpPath, $destination)) {
                // Veritabanına kaydedilecek yol
                $filePath = 'uploads/certificates/' . $newFileName;
            } else {
                $_SESSION['error_msg'] = "Dosya sunucuya yüklenirken bir hata oluştu.";
            }

        } else {
            $_SESSION['error_msg'] = "Sadece PDF, JPG, PNG veya WEBP formatlarına izin verilmektedir.";
        }

    } else {
        $_SESSION['error_msg'] = "Lütfen bir belge dosyası seçin.";
    }

    // 3. Eğer yükleme sırasında bir hata oluşmadıysa veritabanına kaydet
    if(!isset($_SESSION['error_msg']) && $filePath) {
        try {
            $sql = "INSERT INTO certificates (title, url, expiry_date, status) VALUES (:title, :url, :expiry_date, :status)";
            
            $statement = $db->prepare($sql);
            
            $statement->execute([
                ':title' => $title,
                ':url' => $filePath,
                ':expiry_date' => $expiryDate,
                ':status' => $status
            ]);

            // Başarılı olduğunda listeye yönlendir
            $_SESSION['success_msg'] = "Belge başarıyla sisteme eklendi.";
            header("Location: certificates.php");
            exit;

        } catch(PDOException $e) {
            $_SESSION['error_msg'] = "Sistem Hatası: Belge veritabanına kaydedilemedi.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Belge Ekle | Karacapınar Su</title>
    
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
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Yeni Belge Ekle</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Web sitesinde sergilenecek ISO, TSE veya Helal sertifikalarını sisteme yükleyin.</p>
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
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-award text-brand-blue me-2"></i>Belge Detayları</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="custom-form-label">Belge Adı / Unvanı <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control custom-form-control" placeholder="Örn: ISO 9001:2015 Kalite Yönetim Sistemi" required>
                                <div class="form-text mt-1" style="font-size: 11px;"><i class="fas fa-info-circle me-1"></i> Müşterilerin sitede göreceği resmi belge adıdır.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="custom-form-label">Geçerlilik Bitiş Tarihi</label>
                                <input type="date" name="expiry_date" class="form-control custom-form-control">
                                <div class="form-text mt-1" style="font-size: 11px;"><i class="fas fa-info-circle me-1"></i> Belgenin süresi yoksa boş bırakabilirsiniz.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">Yayın Durumu <span class="text-danger">*</span></label>
                                <select name="status" class="form-select custom-form-control fw-bold" required>
                                    <option value="aktif">Aktif (Sitede Görünsün)</option>
                                    <option value="pasif">Pasif (Gizle)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="glass-panel p-4 mb-4 text-center h-100 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3 text-start"><i class="fas fa-file-upload text-brand-blue me-2"></i>Belge Dosyası</h5>
                        
                        <div class="bg-light border border-dashed rounded-3 d-inline-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 120px; height: 140px; border-style: dashed !important; border-color: #cbd5e1 !important;">
                            <i class="fas fa-file-pdf fa-3x text-secondary opacity-25"></i>
                        </div>
                        
                        <label class="custom-form-label d-block text-start">Dosya Seçin <span class="text-danger">*</span></label>
                        <input type="file" name="certificate_file" class="form-control custom-form-control form-control-sm" accept=".pdf, image/jpeg, image/png, image/webp" required>
                        <div class="form-text mt-2 text-start" style="font-size: 11px;">
                            <i class="fas fa-info-circle"></i> <b>PDF</b> veya yüksek çözünürlüklü resim (PNG, JPG) yükleyebilirsiniz. Maksimum 5MB.
                        </div>

                        <div class="mt-auto pt-4">
                            <button type="submit" class="btn w-100 rounded-pill py-3 fw-bolder text-white shadow-sm hover-scale" style="background: linear-gradient(45deg, #1C4F8C, #00a8ff); letter-spacing: 1px; border: none;">
                                <i class="fas fa-save me-2"></i> BELGEYİ KAYDET
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