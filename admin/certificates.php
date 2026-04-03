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

// Filtre formundan gelen verileri alıyorum
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';

// Ana SQL sorgumu başlatıyorum
$sql = "SELECT * FROM certificates WHERE 1=1";
$params = [];

// Eğer arama kutusuna bir şey yazıldıysa
if($search != "") {
    $sql = $sql . " AND title LIKE :search";
    $params[':search'] = "%" . $search . "%";
}

// Eğer aktif/pasif durumu seçildiyse
if($statusFilter != "") {
    $sql = $sql . " AND status = :status";
    $params[':status'] = $statusFilter;
}

// Verileri okuyorum (En yeniden eskiye sıralı)
$sql = $sql . " ORDER BY id DESC";
$statement = $db->prepare($sql);
$statement->execute($params);

$certificateList = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalite Belgeleri | Karacapınar Su</title>
    
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
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Kalite Belgeleri</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Sitede sergilenen ISO, TSE ve Helal sertifikalarını yönetin.</p>
            </div>
            
            <div>
                <a href="add-certificate.php" class="btn btn-add-new fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px;">
                    <i class="fas fa-plus-circle me-2"></i> Yeni Belge Ekle
                </a>
            </div>
        </div>

        <div class="filter-wrapper mb-4 shadow-sm">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control custom-search-input w-100" placeholder="Belge adı ara (Örn: ISO 9001)..." value="<?= htmlspecialchars($search) ?>">
                </div>
                
                <div class="col-md-4">
                    <select name="status" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Durumlar</option>
                        <option value="aktif" <?= ($statusFilter == 'aktif') ? 'selected' : '' ?>>Aktif (Sitede Görünür)</option>
                        <option value="pasif" <?= ($statusFilter == 'pasif') ? 'selected' : '' ?>>Pasif (Gizli)</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn w-100 fw-bold rounded-pill text-white" style="background-color: #00a8ff; padding: 12px;">
                        Filtrele
                    </button>
                </div>
            </form>
        </div>

        <div class="glass-panel p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: rgba(244, 248, 251, 0.9);">
                        <tr>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 ps-4" style="letter-spacing: 1px; width: 5%;">ID</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 40%;">Belge Bilgisi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 20%;">Geçerlilik Tarihi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-center" style="letter-spacing: 1px; width: 15%;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px; width: 20%;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0 bg-white">
                        
                        <?php if(empty($certificateList)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    <i class="fas fa-file-excel fa-3x mb-3 opacity-25"></i>
                                    <h5 class="fw-bold">Belge Bulunamadı</h5>
                                    <p class="mb-0">Arama kriterlerinize uygun sertifika yok.</p>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach($certificateList as $cert): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#<?= $cert['id'] ?></td>
                            
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="cert-thumb-wrapper">
                                        <?php 
                                            // Dosya uzantısını bul ve ona göre ikon göster
                                            $ext = strtolower(pathinfo($cert['url'], PATHINFO_EXTENSION));
                                            if($ext == 'pdf') {
                                                echo '<i class="fas fa-file-pdf"></i>';
                                            } else {
                                                echo '<i class="fas fa-file-image"></i>';
                                            }
                                        ?>
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;"><?= htmlspecialchars($cert['title']) ?></div>
                                        <div class="small fw-medium text-secondary mt-1">
                                            <a href="../<?= htmlspecialchars($cert['url']) ?>" target="_blank" class="text-decoration-none" style="color: #00a8ff;">
                                                <i class="fas fa-external-link-alt me-1"></i> Dosyayı Görüntüle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <?php 
                                    // Tarih Kontrol Mantığı
                                    if(empty($cert['expiry_date'])) {
                                        // Tarih girilmemişse
                                        $dateText = "Süresiz";
                                        $statusText = "Geçerli";
                                        $statusColor = "text-success";
                                        $statusIcon = "fa-check-circle";
                                    } else {
                                        // Tarih girilmişse
                                        $dateText = date('d.m.Y', strtotime($cert['expiry_date']));
                                        $today = date('Y-m-d');
                                        
                                        if($cert['expiry_date'] < $today) {
                                            $statusText = "Süresi Dolmuş";
                                            $statusColor = "text-danger";
                                            $statusIcon = "fa-exclamation-circle";
                                        } else {
                                            $statusText = "Geçerli";
                                            $statusColor = "text-success";
                                            $statusIcon = "fa-check-circle";
                                        }
                                    }
                                ?>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;"><?= $dateText ?></div>
                                <div class="small <?= $statusColor ?> mt-1"><i class="fas <?= $statusIcon ?> me-1"></i> <?= $statusText ?></div>
                            </td>
                            
                            <td class="text-center">
                                <?php if($cert['status'] == 'aktif'): ?>
                                    <span class="status-badge status-aktif">AKTİF</span>
                                <?php else: ?>
                                    <span class="status-badge status-pasif">PASİF</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-end pe-4">
                                <a href="edit-certificate.php?id=<?= $cert['id'] ?>" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                
                                <button type="button" class="action-btn btn-delete ms-1" title="Sil" onclick="confirmDelete('delete-certificate.php?id=<?= $cert['id'] ?>', 'Bu kalite belgesini sistemden tamamen silmek üzeresiniz.')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-white">
                <div class="small fw-medium text-secondary">
                    Toplam <span class="fw-bold text-brand-dark"><?= count($certificateList) ?></span> belge listeleniyor.
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/admin.js"></script>

    <?php if(isset($_SESSION['success_msg'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => { 
            showAlert('success', '<?= htmlspecialchars($_SESSION['success_msg']) ?>'); 
        });
    </script>
    <?php unset($_SESSION['success_msg']); endif; ?>

    <?php if(isset($_SESSION['error_msg'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => { 
            showAlert('error', '<?= htmlspecialchars($_SESSION['error_msg']) ?>'); 
        });
    </script>
    <?php unset($_SESSION['error_msg']); endif; ?>

</body>
</html>