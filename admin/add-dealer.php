<?php
session_start();

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/db.php';
$database = new Database();
$db = $database->connect();

$message = "";
$messageType = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {

    $name = trim($_POST['name']);
    $authPerson = trim($_POST['authorized_person']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $city = trim($_POST['city']);
    $district = trim($_POST['district']);
    $address = trim($_POST['address']);
    $status = $_POST['status'];

    try {
        // SQL Hatası Düzeltildi: :adress yerine :address yazıldı
        $sql = "INSERT INTO dealers (name, authorized_person, phone, email, city, district, address, status) 
                VALUES (:name, :authPerson, :phone, :email, :city, :district, :address, :status)";
        
        $statement = $db->prepare($sql);
        $statement->execute([
            ':name' => $name,
            ':authPerson' => $authPerson,
            ':phone' => $phone,
            ':email' => $email,
            ':city' => $city,
            ':district' => $district,
            ':address' => $address, // Eşleşme sağlandı
            ':status' => $status
        ]);
    
        $message = "Bayi başarıyla sisteme eklendi.";
        $messageType = "success";
        
    } catch(PDOException $e) {
        $message = "Sistem Hatası: Bayi kaydedilemedi. Lütfen bilgileri kontrol edin.";
        $messageType = "error";
        error_log("Bayi Ekleme Hatası: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Bayi Ekle | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">BAYİ AĞI YÖNETİMİ</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Yeni Bayi Ekle</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Web sitenizin "Bayilerimiz" sayfasında listelenecek yeni bir yetkili satıcı ekleyin.</p>
            </div>
            
            <div>
                <a href="dealers.php" class="btn border fw-bold px-4 py-2 rounded-pill shadow-sm bg-white text-secondary" style="font-size: 14px; transition: 0.3s;">
                    <i class="fas fa-arrow-left me-2"></i> Listeye Dön
                </a>
            </div>
        </div>

        <form action="" method="POST">
            <div class="row g-4">
                
                <div class="col-lg-8">
                    <div class="glass-panel p-4 p-md-5 h-100">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-store text-brand-blue me-2"></i>Bayi ve İletişim Bilgileri</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="custom-form-label">Bayi / Şirket Adı <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control custom-form-control" placeholder="Örn: Çankaya Merkez Dağıtım" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="custom-form-label">Yetkili Kişi</label>
                                <input type="text" name="authorized_person" class="form-control custom-form-control" placeholder="Örn: Ahmet Yılmaz">
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">Telefon Numarası <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control custom-form-control" placeholder="Örn: 0312 456 78 90" required>
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">E-Posta Adresi</label>
                                <input type="email" name="email" class="form-control custom-form-control" placeholder="Örn: info@cankayabayi.com">
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">İl (Şehir) <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control custom-form-control" placeholder="Örn: Ankara" required>
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">İlçe <span class="text-danger">*</span></label>
                                <input type="text" name="district" class="form-control custom-form-control" placeholder="Örn: Çankaya" required>
                            </div>

                            <div class="col-12">
                                <label class="custom-form-label">Açık Adres</label>
                                <textarea name="address" class="form-control custom-form-control" rows="3" placeholder="Bayinin tam adresini giriniz..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="glass-panel p-4 mb-4 text-center">
                        <div class="bg-light border border-dashed rounded-3 d-inline-flex align-items-center justify-content-center mb-2 mx-auto" style="width: 100px; height: 100px; border-style: dashed !important; border-color: #cbd5e1 !important; border-radius: 50% !important;">
                            <i class="fas fa-map-marker-alt fa-3x text-secondary opacity-25"></i>
                        </div>
                        <h6 class="fw-bold text-brand-dark mt-2 mb-0">Lokasyon Kaydı</h6>
                        <p class="text-secondary small mt-1">Bu bayi sisteme eklendiğinde "Bölgelerimiz" haritasında görünecektir.</p>
                    </div>

                    <div class="glass-panel p-4 h-100 d-flex flex-column">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-sliders-h text-brand-blue me-2"></i>Yayın Ayarları</h5>
                        
                        <div class="mb-4">
                            <label class="custom-form-label">Bayi Durumu <span class="text-danger">*</span></label>
                            <select name="status" class="form-select custom-form-control fw-bold" required>
                                <option value="aktif">Aktif (Sitede Görünsün)</option>
                                <option value="pasif">Pasif (Gizle)</option>
                            </select>
                            <div class="form-text mt-2" style="font-size: 11px;"><i class="fas fa-info-circle"></i> Bayi anlaşması iptal olursa kaydı silmek yerine "Pasif" duruma getirebilirsiniz.</div>
                        </div>

                        <div class="mt-auto pt-4">
                            <button type="submit" class="btn w-100 rounded-pill py-3 fw-bolder text-white shadow-sm hover-scale" style="background: linear-gradient(45deg, #1C4F8C, #00a8ff); letter-spacing: 1px; border: none;">
                                <i class="fas fa-save me-2"></i> BAYİYİ KAYDET
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/admin.js"></script>

    <?php if(!empty($message)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Başarılıysa alert sonrası dealers.php'ye (Bayi Listesi) gönder
            let redirect = '<?= $messageType == "success" ? "dealers.php" : "" ?>';
            showAlert('<?= $messageType ?>', '<?= $message ?>', redirect ? redirect : null);
        });
    </script>
    <?php endif; ?>

</body>
</html>