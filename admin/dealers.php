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

// Filtre formundan gelen verileri alıyorum (Boşlarsa boş kalırlar)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cityFilter = isset($_GET['city']) ? trim($_GET['city']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';

// Filtre menüsünde göstermek için mevcut şehirleri veritabanından çekiyorum
$citySql = "SELECT DISTINCT city FROM dealers WHERE city IS NOT NULL ORDER BY city ASC";
$cityStmt = $db->prepare($citySql);
$cityStmt->execute();
$cities = $cityStmt->fetchAll(PDO::FETCH_COLUMN);

// Ana SQL sorgumu başlatıyorum
$sql = "SELECT * FROM dealers WHERE 1=1";
$params = [];

// Eğer arama kutusuna bir şey yazıldıysa
if($search != "") {
    $sql = $sql . " AND (name LIKE :search OR authorized_person LIKE :search)";
    $params[':search'] = "%" . $search . "%";
}

// Eğer bölge (şehir) seçildiyse
if($cityFilter != "") {
    $sql = $sql . " AND city = :city";
    $params[':city'] = $cityFilter;
}

// Eğer aktif/pasif durumu seçildiyse
if($statusFilter != "") {
    $sql = $sql . " AND status = :status";
    $params[':status'] = $statusFilter;
}

// Verileri okuyorum (En yeniden eskiye sıralı)
$sql = $sql . " ORDER BY created_at DESC";
$statement = $db->prepare($sql);
$statement->execute($params);

$dealerList = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayi Yönetimi | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">BAYİ AĞI</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Bayi Yönetimi</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Aktif ve pasif tüm bayilerinizi, bölgelerini ve iletişim bilgilerini yönetin.</p>
            </div>
            
            <div>
                <a href="add-dealer.php" class="btn btn-add-new fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px;">
                    <i class="fas fa-plus-circle me-2"></i> Yeni Bayi Ekle
                </a>
            </div>
        </div>

        <div class="filter-wrapper mb-4 shadow-sm">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control custom-search-input w-100" placeholder="Bayi adı veya yetkili ara..." value="<?= htmlspecialchars($search) ?>">
                </div>
                
                <div class="col-md-3">
                    <select name="city" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Bölgeler (İller)</option>
                        <?php foreach($cities as $cityItem): ?>
                            <option value="<?= htmlspecialchars($cityItem) ?>" <?= ($cityFilter == $cityItem) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cityItem) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Durumlar</option>
                        <option value="aktif" <?= ($statusFilter == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                        <option value="pasif" <?= ($statusFilter == 'pasif') ? 'selected' : '' ?>>Pasif</option>
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
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 30%;">Bayi / Yetkili Bilgisi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 25%;">İletişim</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 15%;">Hizmet Bölgesi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-center" style="letter-spacing: 1px; width: 10%;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px; width: 15%;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0 bg-white">
                        
                        <?php if(empty($dealerList)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary">
                                    <i class="fas fa-store-slash fa-3x mb-3 opacity-25"></i>
                                    <h5 class="fw-bold">Bayi Bulunamadı</h5>
                                    <p class="mb-0">Arama kriterlerinize uygun kayıt yok.</p>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach($dealerList as $dealer): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#<?= $dealer['id'] ?></td>
                            
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if($dealer['status'] == 'aktif'): ?>
                                        <div class="dealer-icon-wrapper"><i class="fas fa-store"></i></div>
                                    <?php else: ?>
                                        <div class="dealer-icon-wrapper" style="background: rgba(100, 116, 139, 0.1); color: #64748b;"><i class="fas fa-store-slash"></i></div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;"><?= htmlspecialchars($dealer['name']) ?></div>
                                        <div class="small fw-medium text-secondary mt-1"><i class="fas fa-user-tie text-brand-blue me-1"></i> <?= htmlspecialchars($dealer['authorized_person'] ?? 'Belirtilmedi') ?></div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;"><?= htmlspecialchars($dealer['phone']) ?></div>
                                <div class="small text-secondary mt-1"><?= htmlspecialchars($dealer['email'] ?? '-') ?></div>
                            </td>
                            
                            <td>
                                <div class="fw-bold" style="<?= $dealer['status'] == 'aktif' ? 'color: #1C4F8C;' : 'color: #64748b;' ?>"><?= htmlspecialchars($dealer['city']) ?></div>
                                <div class="small text-secondary mt-1"><?= htmlspecialchars($dealer['district']) ?></div>
                            </td>
                            
                            <td class="text-center">
                                <?php if($dealer['status'] == 'aktif'): ?>
                                    <span class="status-badge status-aktif">AKTİF</span>
                                <?php else: ?>
                                    <span class="status-badge status-pasif">PASİF</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-end pe-4">
                                <a href="edit-dealer.php?id=<?= $dealer['id'] ?>" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                
                                <button type="button" class="action-btn btn-delete ms-1" title="Sil" onclick="confirmDelete('delete-dealer.php?id=<?= $dealer['id'] ?>', 'Bu bayiyi sistemden tamamen kaldırmak üzeresiniz.')">
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
                    Toplam <span class="fw-bold text-brand-dark"><?= count($dealerList) ?></span> bayi listeleniyor.
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