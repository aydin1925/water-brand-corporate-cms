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

try {
    // 1. İSTATİSTİKLERİ ÇEK (leads ve dealer_applications toplamı)
    // fetchColumn() ile veriyi doğrudan ve güvenli bir şekilde alıyoruz
    
    // Toplam Kayıt Sayısı
    $qSum = $db->query("SELECT (SELECT COUNT(id) FROM leads) + (SELECT COUNT(id) FROM dealer_applications)");
    $sumCount = $qSum->fetchColumn() ?: 0;

    // Bekleyen (Yeni) Talepler
    $qWait = $db->query("SELECT (SELECT COUNT(id) FROM leads WHERE status = 'yeni') + (SELECT COUNT(id) FROM dealer_applications WHERE status = 'yeni')");
    $waitingCount = $qWait->fetchColumn() ?: 0;

    // İşlemdeki Talepler
    $qActive = $db->query("SELECT (SELECT COUNT(id) FROM leads WHERE status = 'islemde') + (SELECT COUNT(id) FROM dealer_applications WHERE status = 'islemde')");
    $activeCount = $qActive->fetchColumn() ?: 0;

    // Tamamlanan İşlemler
    $qGained = $db->query("SELECT (SELECT COUNT(id) FROM leads WHERE status = 'tamamlandi') + (SELECT COUNT(id) FROM dealer_applications WHERE status = 'tamamlandi')");
    $gainedCount = $qGained->fetchColumn() ?: 0;

    // 2. SON KAYITLARI ÇEK (UNION ALL ile İki Tabloyu Birleştiriyoruz)
    $recentStmt = $db->query("
        (SELECT name AS full_name, type AS interest_area, status, created_at, 'İletişim / Sipariş' AS source FROM leads)
        UNION ALL
        (SELECT full_name, 'Bayilik Başvurusu' AS interest_area, status, created_at, 'Bayilik Formu' AS source FROM dealer_applications)
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $recentLeads = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Hata durumunda sayfayı patlatmamak için sıfırla
    $sumCount = $waitingCount = $activeCount = $gainedCount = 0;
    $recentLeads = [];
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Özeti | Karacapınar Su</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css"> 
</head>
<body>

    <?php require_once '../includes/sidebar.php'; ?>
    
    <div class="admin-main-wrapper">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 30px; height: 3px; background-color: #00a8ff; border-radius: 2px;"></div>
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">YÖNETİM ÖZETİ</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Sistem İstatistikleri</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Karacapınar Su bayi ve sipariş operasyonları güncel raporu.</p>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="../index.php" target="_blank" class="text-brand-main text-decoration-none fw-bold small text-uppercase" style="letter-spacing: 1px; transition: 0.3s;">
                    <i class="fas fa-external-link-square-alt me-2 fs-5"></i> Siteyi Görüntüle
                </a>
                <div class="mx-2" style="width: 1px; height: 30px; background-color: rgba(28, 79, 140, 0.1);"></div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-sm-block">
                        <div class="text-brand-dark fw-bolder" style="font-size: 0.9rem;"><?php echo isset($_SESSION['admin_username']) ? strtoupper(htmlspecialchars($_SESSION['admin_username'])) : 'YÖNETİCİ'; ?></div>
                        <div class="text-brand-blue fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">SİSTEM ADMİNİ</div>
                    </div>
                    <div class="bg-brand-main rounded-3 d-flex justify-content-center align-items-center shadow-sm" style="width: 42px; height: 42px;">
                        <i class="fas fa-user-shield text-white fs-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="glass-panel p-4 h-100 hover-scale">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="bg-brand-main rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px;">
                            <i class="fas fa-list-ol text-white fs-5"></i>
                        </div>
                        <span class="badge bg-light text-secondary border fw-bold px-2 py-1">Tüm Zamanlar</span>
                    </div>
                    <h3 class="fw-bolder text-brand-dark mb-1" style="font-size: 2.6rem; letter-spacing: -1.5px;"><?php echo htmlspecialchars($sumCount); ?></h3>
                    <span class="d-block text-secondary small fw-bold text-uppercase" style="letter-spacing: 1px;">Toplam Talep</span>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="glass-panel p-4 h-100 hover-scale" style="position: relative; overflow: hidden;">
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background-color: #00a8ff;"></div>
                    <div class="d-flex justify-content-between align-items-start mb-4 ps-2">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background-color: rgba(0, 168, 255, 0.1);">
                            <i class="fas fa-clock text-brand-blue fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bolder text-brand-dark mb-1 ps-2" style="font-size: 2.6rem; letter-spacing: -1.5px;"><?php echo htmlspecialchars($waitingCount); ?></h3>
                    <span class="d-block text-secondary small fw-bold text-uppercase ps-2" style="letter-spacing: 1px;">Bekleyen Talep</span>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="glass-panel p-4 h-100 hover-scale" style="position: relative; overflow: hidden;">
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background-color: #f59e0b;"></div>
                    <div class="d-flex justify-content-between align-items-start mb-4 ps-2">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background-color: rgba(245, 158, 11, 0.1);">
                            <i class="fas fa-truck-loading text-warning fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bolder text-brand-dark mb-1 ps-2" style="font-size: 2.6rem; letter-spacing: -1.5px;"><?php echo htmlspecialchars($activeCount); ?></h3>
                    <span class="d-block text-secondary small fw-bold text-uppercase ps-2" style="letter-spacing: 1px;">İşlemdeki Talep</span>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="glass-panel p-4 h-100 hover-scale" style="position: relative; overflow: hidden;">
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background-color: #10b981;"></div>
                    <div class="d-flex justify-content-between align-items-start mb-4 ps-2">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background-color: rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-check-circle text-success fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bolder text-brand-dark mb-1 ps-2" style="font-size: 2.6rem; letter-spacing: -1.5px;"><?php echo htmlspecialchars($gainedCount); ?></h3>
                    <span class="d-block text-secondary small fw-bold text-uppercase ps-2" style="letter-spacing: 1px;">Tamamlanan İşlem</span>
                </div>
            </div>
        </div>

        <div class="glass-panel p-0" style="overflow: hidden;">
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom" style="background-color: rgba(28, 79, 140, 0.02);">
                <h6 class="fw-bolder text-brand-dark mb-0 text-uppercase" style="letter-spacing: 1px;"><i class="fas fa-clipboard-list text-brand-blue me-2"></i>Son Kayıtlar</h6>
                <a href="leads.php" class="text-brand-main fw-bold text-decoration-none small" style="letter-spacing: 1px; transition: 0.3s;">TÜMÜNÜ GÖR <i class="fas fa-chevron-right ms-1" style="font-size: 10px;"></i></a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: rgba(244, 248, 251, 0.8);">
                        <tr>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 ps-4" style="letter-spacing: 1px;">Müşteri / Aday</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px;">Talep Türü</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px;">Tarih</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if($recentLeads): ?>
                            <?php foreach($recentLeads as $lead): ?>
                            <tr style="cursor: pointer; transition: 0.2s;">
                                <td class="ps-4 fw-bold text-brand-dark">
                                    <?php echo htmlspecialchars($lead['full_name']); ?>
                                    <div class="small fw-normal text-secondary mt-1"><i class="fas fa-link" style="font-size: 10px;"></i> Kaynak: <?php echo htmlspecialchars($lead['source']); ?></div>
                                </td>
                                <td class="text-brand-dark fw-medium"><?php echo htmlspecialchars($lead['interest_area']); ?></td>
                                <td>
                                    <?php 
                                    $status = strtolower($lead['status']);
                                    switch($status) {
                                        case 'yeni': $badgeClass = 'status-yeni'; $statusText = 'YENİ'; break;
                                        case 'islemde': $badgeClass = 'status-islemde'; $statusText = 'İŞLEMDE'; break;
                                        case 'tamamlandi': $badgeClass = 'status-tamamlandi'; $statusText = 'TAMAMLANDI'; break;
                                        case 'iptal': $badgeClass = 'status-iptal'; $statusText = 'İPTAL'; break;
                                        default: $badgeClass = 'status-yeni'; $statusText = strtoupper($status);
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($statusText); ?></span>
                                </td>
                                <td class="text-end pe-4 text-secondary small fw-medium">
                                    <?php echo date('d.m.Y H:i', strtotime($lead['created_at'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?> 
                        <tr>
                            <td colspan="4" class="text-center py-5 text-secondary fw-medium">
                                <i class="fas fa-inbox mb-3 fs-3 opacity-25 d-block text-brand-main"></i>
                                Henüz bir sipariş veya başvuru kaydı bulunmuyor.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>