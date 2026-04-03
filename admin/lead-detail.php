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

// 1. URL'den gelen ID'yi kontrol et ve talebi bul
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $leadId = intval($_GET['id']);

    try {
        $sql = "SELECT * FROM leads WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->execute([':id' => $leadId]);
        $lead = $statement->fetch(PDO::FETCH_ASSOC);

        // Eğer veritabanında böyle bir kayıt yoksa listeye geri gönder
        if(!$lead) {
            header("Location: leads.php");
            exit;
        }
    } catch(PDOException $e) {
        die("Veritabanı Hatası: " . $e->getMessage());
    }
} else {
    // ID yoksa veya sayı değilse listeye geri gönder
    header("Location: leads.php");
    exit;
}

// 2. Sağ taraftaki form gönderildiğinde durumu ve notları güncelle
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $status = $_POST['status'];
    $adminNotes = trim($_POST['admin_notes']);

    try {
        $updateSql = "UPDATE leads SET 
                        status = :status, 
                        admin_notes = :admin_notes 
                      WHERE id = :id";
        
        $updateStmt = $db->prepare($updateSql);
        
        $updateStmt->execute([
            ':status' => $status,
            ':admin_notes' => $adminNotes,
            ':id' => $leadId
        ]);
    
        $_SESSION['success_msg'] = "Talep durumu ve yönetici notları güncellendi.";
        
        // Sayfayı yenile (değişiklikleri anında görmek için)
        header("Location: lead-detail.php?id=" . $leadId);
        exit;
        
    } catch(PDOException $e) {
        $_SESSION['error_msg'] = "Sistem Hatası: Kayıt güncellenemedi.";
    }
}

// İngilizce ay isimlerini Türkçe yapmak için basit bir yardımcı
function turkceTarih($tarih) {
    $aylar = [
        'January' => 'Ocak', 'February' => 'Şubat', 'March' => 'Mart',
        'April' => 'Nisan', 'May' => 'Mayıs', 'June' => 'Haziran',
        'July' => 'Temmuz', 'August' => 'Ağustos', 'September' => 'Eylül',
        'October' => 'Ekim', 'November' => 'Kasım', 'December' => 'Aralık'
    ];
    $formatli = date('d F Y, H:i', strtotime($tarih));
    return strtr($formatli, $aylar);
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talep Detayı | Karacapınar Su</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css"> 
    
    <style>
        /* Sadece Bu Sayfaya Özel Ufak Detaylar */
        .timeline-marker {
            width: 12px; height: 12px; border-radius: 50%;
            background-color: var(--brand-main);
            display: inline-block; margin-right: 8px;
        }
        .timeline-marker.accent { background-color: var(--brand-blue); }
        .customer-message-box {
            background-color: #f8fafc;
            border-left: 4px solid var(--brand-blue);
            padding: 20px;
            border-radius: 0 12px 12px 0;
            font-size: 15px;
            line-height: 1.6;
            color: var(--brand-dark);
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <?php require_once '../includes/sidebar.php'; ?>

    <div class="admin-main-wrapper position-relative pb-5">
        
        <div class="mb-4 mb-md-5">
            <a href="leads.php" class="text-secondary fw-bold text-decoration-none transition-smooth mb-3 d-inline-block" style="font-size: 14px;">
                <i class="fas fa-arrow-left me-2"></i> Taleplere Dön
            </a>
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div style="width: 30px; height: 3px; background-color: #00a8ff; border-radius: 2px;"></div>
                        <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">KAYIT DETAYI #<?php echo $lead['id']; ?></span>
                    </div>
                    <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.6rem; letter-spacing: -1px;">
                        <?php echo htmlspecialchars($lead['name']); ?>
                    </h2>
                    <p class="text-secondary fw-medium mt-1 mb-0">
                        Talep Tarihi: <span class="text-brand-main fw-bold"><?php echo turkceTarih($lead['created_at']); ?></span>
                    </p>
                </div>
                
                <div>
                    <div class="glass-panel px-4 py-2 rounded-pill fw-bold text-brand-main text-center w-100 d-inline-block shadow-sm" style="font-size: 14px;">
                        <i class="fas fa-tag text-brand-blue me-2"></i> 
                        Kategori: 
                        <?php 
                            if($lead['type'] == 'damacana') echo '19L Damacana Siparişi';
                            else if($lead['type'] == 'bayilik') echo 'Bayilik Başvurusu';
                            else echo 'Genel İletişim';
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-7">
                <div class="glass-panel p-4 p-md-5 h-100">
                    <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-user-circle text-brand-blue me-2"></i>Müşteri Profili & İçerik</h5>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-1">
                                <div class="timeline-marker accent"></div>
                                <span class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Ad Soyad</span>
                            </div>
                            <div class="text-brand-dark fw-bolder fs-5 ps-3"><?php echo htmlspecialchars($lead['name']); ?></div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-1">
                                <div class="timeline-marker"></div>
                                <span class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Telefon Numarası</span>
                            </div>
                            <a href="tel:<?php echo htmlspecialchars($lead['phone']); ?>" class="text-brand-dark fw-bolder fs-5 text-decoration-none ps-3 d-inline-block text-truncate">
                                <?php echo htmlspecialchars($lead['phone']); ?>
                            </a>
                        </div>

                        <div class="col-md-6 mt-4">
                            <div class="d-flex align-items-center mb-1">
                                <div class="timeline-marker"></div>
                                <span class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">E-Posta Adresi</span>
                            </div>
                            <a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" class="text-brand-dark fw-bold fs-6 text-decoration-none ps-3 d-inline-block text-truncate">
                                <?php echo htmlspecialchars($lead['email']); ?>
                            </a>
                        </div>

                        <div class="col-md-6 mt-4">
                            <div class="d-flex align-items-center mb-1">
                                <div class="timeline-marker accent"></div>
                                <span class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Bölge Bilgisi</span>
                            </div>
                            <div class="text-secondary fw-bold fs-6 ps-3">
                                <?php 
                                    if(!empty($lead['city']) && !empty($lead['district'])) {
                                        echo htmlspecialchars($lead['city'] . ' / ' . $lead['district']);
                                    } else {
                                        echo "Belirtilmemiş";
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <span class="d-block text-secondary small fw-bold text-uppercase mb-2" style="letter-spacing: 0.5px;">
                                <i class="fas fa-comment-alt text-brand-blue me-1"></i> Müşteri Mesajı / Sipariş Notu
                            </span>
                            <div class="customer-message-box">
                                <?php 
                                    if(!empty($lead['message'])) {
                                        // nl2br fonksiyonu formdaki enter tuşlarını <br> etiketine çevirir
                                        echo nl2br(htmlspecialchars($lead['message']));
                                    } else {
                                        echo "<em>Müşteri herhangi bir ek mesaj bırakmamış.</em>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="glass-panel p-4 p-md-5 h-100" style="border-top: 4px solid var(--brand-blue);">
                    <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-sliders-h text-brand-blue me-2"></i>Süreç Yönetimi</h5>
                    
                    <form action="" method="POST" class="mt-4 d-flex flex-column h-100">
                        
                        <div class="mb-4">
                            <label class="custom-form-label">Talep Durumu <span class="text-danger">*</span></label>
                            <select name="status" class="form-select custom-form-control fw-bold" style="cursor: pointer; height: 50px;">
                                <option value="yeni" <?php if($lead['status'] == 'yeni') echo 'selected'; ?>>🔴 Yeni (Bekliyor)</option>
                                <option value="islemde" <?php if($lead['status'] == 'islemde') echo 'selected'; ?>>🟠 İşlemde (Görüşülüyor)</option>
                                <option value="tamamlandi" <?php if($lead['status'] == 'tamamlandi') echo 'selected'; ?>>🟢 Tamamlandı (Sipariş Alındı)</option>
                                <option value="iptal" <?php if($lead['status'] == 'iptal') echo 'selected'; ?>>⚪ İptal / Olumsuz</option>
                            </select>
                        </div>
                        
                        <div class="mb-5 flex-grow-1">
                            <label class="custom-form-label">Yönetici Notları (Admin Notes)</label>
                            <textarea name="admin_notes" class="form-control custom-form-control w-100" rows="8" placeholder="Müşteriyle yapılan görüşme detaylarını, verilen fiyatları veya arama sonucunu buraya not alın..."><?php echo htmlspecialchars($lead['admin_notes'] ?? ''); ?></textarea>
                            <div class="form-text text-secondary mt-2" style="font-size: 11px;">
                                <i class="fas fa-shield-alt text-success me-1"></i> Bu notlar müşteriye gösterilmez, sadece yöneticiler görebilir.
                            </div>
                        </div>

                        <div class="mt-auto">
                            <button type="submit" class="btn w-100 rounded-pill py-3 fw-bolder text-white shadow-sm hover-scale" style="background: linear-gradient(45deg, #1C4F8C, #00a8ff); letter-spacing: 1px; border: none;">
                                <i class="fas fa-check-circle fs-5 me-2"></i> GÜNCELLE VE KAYDET
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/admin.js"></script>

    <?php if(isset($_SESSION['success_msg'])) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => { 
            showAlert('success', '<?php echo htmlspecialchars($_SESSION['success_msg']); ?>'); 
        });
    </script>
    <?php unset($_SESSION['success_msg']); } ?>

    <?php if(isset($_SESSION['error_msg'])) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => { 
            showAlert('error', '<?php echo htmlspecialchars($_SESSION['error_msg']); ?>'); 
        });
    </script>
    <?php unset($_SESSION['error_msg']); } ?>

</body>
</html>