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

// 1. URL'den gelen ID'yi kontrol et ve bayiyi bul
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $dealerId = intval($_GET['id']);

    try {
        $sql = "SELECT * FROM dealers WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->execute([':id' => $dealerId]);
        $dealer = $statement->fetch(PDO::FETCH_ASSOC);

        // Eğer veritabanında böyle bir bayi yoksa listeye geri gönder
        if(!$dealer) {
            header("Location: dealers.php");
            exit;
        }
    } catch(PDOException $e) {
        die("Veritabanı Hatası: " . $e->getMessage());
    }
} else {
    // ID yoksa veya sayı değilse listeye geri gönder
    header("Location: dealers.php");
    exit;
}


// 2. Form gönderildiğinde verileri güncelle (UPDATE)
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
        $updateSql = "UPDATE dealers SET 
                        name = :name, 
                        authorized_person = :authPerson, 
                        phone = :phone, 
                        email = :email, 
                        city = :city, 
                        district = :district, 
                        address = :address, 
                        status = :status 
                      WHERE id = :id";
        
        $updateStmt = $db->prepare($updateSql);
        
        $updateStmt->execute([
            ':name' => $name,
            ':authPerson' => $authPerson,
            ':phone' => $phone,
            ':email' => $email,
            ':city' => $city,
            ':district' => $district,
            ':address' => $address,
            ':status' => $status,
            ':id' => $dealerId
        ]);
    
        // Başarılı olduğunda session'a mesaj atıp ana listeye yönlendiriyoruz (Senin tarzın)
        $_SESSION['success_msg'] = "Bayi bilgileri başarıyla güncellendi.";
        header("Location: dealers.php");
        exit;
        
    } catch(PDOException $e) {
        $_SESSION['error_msg'] = "Sistem Hatası: Bayi güncellenemedi. Lütfen bilgileri kontrol edin.";
        // Hata durumunda sayfada kalıp mesajı gösterecek
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayi Düzenle | Karacapınar Su</title>
    
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
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Bayi Düzenle</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Mevcut bayinin iletişim bilgilerini, adresini veya çalışma durumunu güncelleyin.</p>
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
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-pen-square text-brand-blue me-2"></i>Bayi ve İletişim Bilgileri</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="custom-form-label">Bayi / Şirket Adı <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control custom-form-control" value="<?php echo htmlspecialchars($dealer['name']); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="custom-form-label">Yetkili Kişi</label>
                                <input type="text" name="authorized_person" class="form-control custom-form-control" value="<?php echo htmlspecialchars($dealer['authorized_person']); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">Telefon Numarası <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control custom-form-control" value="<?php echo htmlspecialchars($dealer['phone']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">E-Posta Adresi</label>
                                <input type="email" name="email" class="form-control custom-form-control" value="<?php echo htmlspecialchars($dealer['email']); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">İl (Şehir) <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control custom-form-control" value="<?php echo htmlspecialchars($dealer['city']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">İlçe <span class="text-danger">*</span></label>
                                <input type="text" name="district" class="form-control custom-form-control" value="<?php echo htmlspecialchars($dealer['district']); ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="custom-form-label">Açık Adres</label>
                                <textarea name="address" class="form-control custom-form-control" rows="3"><?php echo htmlspecialchars($dealer['address']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="glass-panel p-4 mb-4 text-center">
                        <div class="position-relative d-inline-block mx-auto mb-2">
                            
                            <?php if($dealer['status'] == 'aktif') { ?>
                                <div class="bg-light border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-color: rgba(28, 79, 140, 0.1) !important;">
                                    <i class="fas fa-store fa-3x text-brand-main opacity-75"></i>
                                </div>
                                <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-2 shadow" style="transform: translate(10%, 10%); border: 3px solid #fff;">
                                    <i class="fas fa-check"></i>
                                </span>
                            <?php } else { ?>
                                <div class="bg-light border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-color: rgba(100, 116, 139, 0.2) !important;">
                                    <i class="fas fa-store-slash fa-3x text-secondary opacity-50"></i>
                                </div>
                                <span class="position-absolute bottom-0 end-0 bg-secondary text-white rounded-circle p-2 shadow" style="transform: translate(10%, 10%); border: 3px solid #fff;">
                                    <i class="fas fa-minus"></i>
                                </span>
                            <?php } ?>

                        </div>
                        
                        <?php if($dealer['status'] == 'aktif') { ?>
                            <h6 class="fw-bold text-brand-dark mt-2 mb-0">Aktif Kayıt</h6>
                            <p class="text-secondary small mt-1">Bu bayi haritada listelenmektedir.</p>
                        <?php } else { ?>
                            <h6 class="fw-bold text-secondary mt-2 mb-0">Pasif Kayıt</h6>
                            <p class="text-secondary small mt-1">Bu bayi haritada gizlenmektedir.</p>
                        <?php } ?>

                    </div>

                    <div class="glass-panel p-4 h-100 d-flex flex-column">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-sliders-h text-brand-blue me-2"></i>Yayın Ayarları</h5>
                        
                        <div class="mb-4">
                            <label class="custom-form-label">Bayi Durumu <span class="text-danger">*</span></label>
                            <select name="status" class="form-select custom-form-control fw-bold" required>
                                <option value="aktif" <?php if($dealer['status'] == "aktif") echo "selected"; ?>>Aktif (Sitede Görünsün)</option>
                                <option value="pasif" <?php if($dealer['status'] == "pasif") echo "selected"; ?>>Pasif (Gizle)</option>
                            </select>
                            <div class="form-text mt-2" style="font-size: 11px;"><i class="fas fa-info-circle"></i> Bayi anlaşması iptal olursa kaydı silmek yerine "Pasif" duruma getirebilirsiniz.</div>
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