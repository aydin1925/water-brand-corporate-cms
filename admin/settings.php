<?php 
session_start();

// Admin giriş kontrolü
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/db.php';
// Resim yükleme vb. fonksiyonların için helpers.php
require_once '../config/helpers.php'; 

$database = new Database();
$db = $database->connect();

$message = "";
$messageType = "";

// 1. FORM GÖNDERİLDİYSE (UPDATE İŞLEMİ)
if($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        // Formdan gelen name değerleri ile veritabanındaki setting_key değerleri birebir aynı
        $keysToUpdate = [
            'site_title', 'site_description', 'phone', 'email', 'address', 'map_iframe',
            'stat_experience', 'stat_ph_value', 'stat_dealers', 'stat_natural',
            'instagram_url', 'facebook_url', 'twitter_url', 'linkedin_url'
        ];

        // Performans için hazırlıklı sorgu (Prepared Statement) kullanıyoruz
        $updateStmt = $db->prepare("UPDATE settings SET setting_value = :val WHERE setting_key = :key");

        // Tüm metin kutularını döngüyle veritabanına basıyoruz
        foreach ($keysToUpdate as $key) {
            if (isset($_POST[$key])) {
                $updateStmt->execute([
                    ':val' => trim($_POST[$key]),
                    ':key' => $key
                ]);
            }
        }

        // 2. LOGO YÜKLEME KONTROLÜ
        if(isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $logoResult = uploadAndConvertToWebP($_FILES['logo_file'], 'settings', 'logo');
            if($logoResult) {
                $updateStmt->execute([':val' => $logoResult, ':key' => 'logo_url']);
            }
        }

        // 3. FAVİCON YÜKLEME KONTROLÜ
        if(isset($_FILES['favicon_file']) && $_FILES['favicon_file']['error'] === UPLOAD_ERR_OK) {
            $favResult = uploadAndConvertToWebP($_FILES['favicon_file'], 'settings', 'favicon');
            if($favResult) {
                $updateStmt->execute([':val' => $favResult, ':key' => 'favicon_url']);
            }
        }

        $message = "Sistem ayarları ve kurumsal bilgiler başarıyla güncellendi.";
        $messageType = "success";

    } catch(PDOException $e) {
        $message = "Güncelleme hatası: " . $e->getMessage();
        $messageType = "error";
    }
}

// 4. SAYFA YÜKLENDİĞİNDE MEVCUT AYARLARI ÇEK
$settings = [];
try {
    $stmt = $db->prepare("SELECT setting_key, setting_value FROM settings");
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Ayarları 'setting_key' => 'setting_value' formatında diziye alıyoruz
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch(PDOException $e) {
    // Hata durumunu loglayabiliriz
}

// HTML içinde değerleri güvenle yazdırmak için küçük bir yardımcı fonksiyon
function getSetting($key, $array) {
    if(isset($array[$key])) {
        return htmlspecialchars($array[$key]);
    } else {
        return '';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genel Ayarlar | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">SİSTEM YAPILANDIRMASI</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Genel Ayarlar</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Web sitenizin iletişim, sosyal medya, SEO ve istatistik bilgilerini güncelleyin.</p>
            </div>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row g-4">
                
                <div class="col-lg-8">
                    
                    <div class="glass-panel p-4 mb-4 shadow-sm">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-globe text-brand-blue me-2"></i>Temel Site Bilgileri</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="custom-form-label">Site Başlığı (Title) <span class="text-danger">*</span></label>
                                <input type="text" name="site_title" class="form-control custom-form-control" value="<?php echo getSetting('site_title', $settings); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="custom-form-label">Site Açıklaması (Meta Description - SEO İçin)</label>
                                <textarea name="site_description" class="form-control custom-form-control" rows="3"><?php echo getSetting('site_description', $settings); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel p-4 mb-4 shadow-sm">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-address-book text-brand-blue me-2"></i>İletişim & Konum Bilgileri</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="custom-form-label">Telefon Numarası</label>
                                <input type="text" name="phone" class="form-control custom-form-control" value="<?php echo getSetting('phone', $settings); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="custom-form-label">E-Posta Adresi</label>
                                <input type="email" name="email" class="form-control custom-form-control" value="<?php echo getSetting('email', $settings); ?>">
                            </div>
                            <div class="col-12">
                                <label class="custom-form-label">Açık Adres (Merkez / Fabrika)</label>
                                <textarea name="address" class="form-control custom-form-control" rows="2"><?php echo getSetting('address', $settings); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="custom-form-label">Google Haritalar İframe Kodu</label>
                                <textarea name="map_iframe" class="form-control custom-form-control" rows="3"><?php echo getSetting('map_iframe', $settings); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel p-4 mb-4 shadow-sm">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-chart-line text-brand-blue me-2"></i>Rakamlarla Biz (Ana Sayfa Verileri)</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-3">
                                <label class="custom-form-label">Yıllık Tecrübe</label>
                                <input type="number" name="stat_experience" class="form-control custom-form-control" value="<?php echo getSetting('stat_experience', $settings); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="custom-form-label">pH Değeri</label>
                                <input type="number" step="0.01" name="stat_ph_value" class="form-control custom-form-control" value="<?php echo getSetting('stat_ph_value', $settings); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="custom-form-label">Aktif Bayi</label>
                                <input type="number" name="stat_dealers" class="form-control custom-form-control" value="<?php echo getSetting('stat_dealers', $settings); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="custom-form-label">Doğallık Oranı (%)</label>
                                <input type="number" name="stat_natural" class="form-control custom-form-control" value="<?php echo getSetting('stat_natural', $settings); ?>">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">
                    
                    <div class="glass-panel p-4 mb-4 shadow-sm">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-image text-brand-blue me-2"></i>Kurumsal Görseller</h5>
                        
                        <div class="mb-4">
                            <label class="custom-form-label">Ana Logo</label>
                            <div class="p-3 bg-light rounded-3 text-center border mb-2 position-relative">
                                <?php if(!empty($settings['logo_url'])) { ?>
                                    <img src="../<?php echo htmlspecialchars($settings['logo_url']); ?>" alt="Logo" style="max-height: 60px;">
                                <?php } else { ?>
                                    <span class="text-secondary small">Logo Yüklenmemiş</span>
                                <?php } ?>
                            </div>
                            <input type="file" name="logo_file" class="form-control custom-form-control form-control-sm" accept="image/png, image/svg+xml, image/webp">
                            <small class="text-secondary mt-1 d-block" style="font-size: 11px;">PNG veya SVG (Şeffaf) önerilir.</small>
                        </div>

                        <div>
                            <label class="custom-form-label">Favicon (Sekme İkonu)</label>
                            <div class="p-2 bg-light rounded-3 text-center border mb-2 d-inline-block">
                                <?php if(!empty($settings['favicon_url'])) { ?>
                                    <img src="../<?php echo htmlspecialchars($settings['favicon_url']); ?>" alt="Favicon" style="max-height: 32px;">
                                <?php } else { ?>
                                    <i class="fas fa-tint text-brand-blue fs-4"></i>
                                <?php } ?>
                            </div>
                            <input type="file" name="favicon_file" class="form-control custom-form-control form-control-sm" accept="image/x-icon, image/png">
                            <small class="text-secondary mt-1 d-block" style="font-size: 11px;">16x16 veya 32x32 px boyutlarında (.ico / .png)</small>
                        </div>
                    </div>

                    <div class="glass-panel p-4 shadow-sm">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-hashtag text-brand-blue me-2"></i>Sosyal Medya Bağlantıları</h5>
                        
                        <div class="mb-3">
                            <label class="custom-form-label"><i class="fab fa-instagram me-1"></i> Instagram</label>
                            <input type="url" name="instagram_url" class="form-control custom-form-control" value="<?php echo getSetting('instagram_url', $settings); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="custom-form-label"><i class="fab fa-facebook me-1"></i> Facebook</label>
                            <input type="url" name="facebook_url" class="form-control custom-form-control" value="<?php echo getSetting('facebook_url', $settings); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="custom-form-label"><i class="fab fa-twitter me-1"></i> Twitter (X)</label>
                            <input type="url" name="twitter_url" class="form-control custom-form-control" value="<?php echo getSetting('twitter_url', $settings); ?>" placeholder="Link giriniz...">
                        </div>
                        <div class="mb-3">
                            <label class="custom-form-label"><i class="fab fa-linkedin me-1"></i> LinkedIn</label>
                            <input type="url" name="linkedin_url" class="form-control custom-form-control" value="<?php echo getSetting('linkedin_url', $settings); ?>" placeholder="Link giriniz...">
                        </div>
                    </div>

                </div>
            </div>

            <div class="position-sticky" style="bottom: 20px; z-index: 100; margin-top: 30px;">
                <div class="bg-white p-3 rounded-pill shadow-lg border d-flex justify-content-end align-items-center pe-4 ps-4" style="border-color: rgba(28, 79, 140, 0.1) !important;">
                    <span class="text-secondary fw-medium small d-none d-sm-inline me-4"><i class="fas fa-info-circle me-1"></i> Tüm ayarlar sitenize anında yansıyacaktır.</span>
                    <button type="submit" class="btn rounded-pill px-5 py-2 fw-bolder text-white" style="background: linear-gradient(45deg, #1C4F8C, #00a8ff); letter-spacing: 1px; border: none;">
                        <i class="fas fa-save me-2"></i> AYARLARI KAYDET
                    </button>
                </div>
            </div>
            
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/admin.js"></script>
    
    <?php if(!empty($message)) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showAlert('<?php echo $messageType; ?>', '<?php echo $message; ?>');
        });
    </script>
    <?php } ?>

</body>
</html>