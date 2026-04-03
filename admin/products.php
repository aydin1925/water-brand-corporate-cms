<?php
session_start();

require_once '../config/db.php';

// Admin giriş kontrolü
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->connect();

// 1. FİLTRELEME DEĞİŞKENLERİNİ AL
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// 2. KATEGORİLERİ ÇEK (Filtre açılır menüsü için)
$categories = [];
try {
    $catStmt = $db->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Kategoriler çekilemedi: " . $e->getMessage());
}

// 3. SAYFALAMA (PAGINATION) AYARLARI
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Her sayfada gösterilecek ürün sayısı
$offset = ($page - 1) * $limit;

// 4. SQL SORGUSUNU DİNAMİK OLUŞTUR
$whereClauses = [];
$params = [];

if ($search !== '') {
    $whereClauses[] = "(p.title LIKE :search OR p.sku LIKE :search)";
    $params[':search'] = "%$search%";
}
if ($category_filter !== '') {
    $whereClauses[] = "p.category_id = :category";
    $params[':category'] = $category_filter;
}
if ($status_filter !== '') {
    $whereClauses[] = "p.status = :status";
    $params[':status'] = $status_filter;
}

$whereSql = "";
if (count($whereClauses) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Toplam kayıt sayısını bul (Sayfalama için)
$total_records = 0;
try {
    $countSql = "SELECT COUNT(*) FROM products p $whereSql";
    $countStmt = $db->prepare($countSql);
    $countStmt->execute($params);
    $total_records = $countStmt->fetchColumn();
} catch(PDOException $e) {
    error_log("Sayım Hatası: " . $e->getMessage());
}

$total_pages = ceil($total_records / $limit);

// 5. ÜRÜNLERİ ÇEK
$products = [];
try {
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            $whereSql 
            ORDER BY p.id DESC 
            LIMIT :limit OFFSET :offset";
            
    $stmt = $db->prepare($sql);
    
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    error_log("Ürünler Çekilemedi: " . $e->getMessage());
}

// URL'deki mevcut GET parametrelerini korumak için yardımcı fonksiyon
function getQueryString($exclude = 'page') {
    $params = $_GET;
    unset($params[$exclude]);
    return http_build_query($params);
}
$queryString = getQueryString();
$queryString = $queryString ? '&' . $queryString : '';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Yönetimi | Karacapınar Su</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css"> 
</head>
<body>

    <?php require_once '../includes/sidebar.php'; ?>
    
    <div class="admin-main-wrapper">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 30px; height: 3px; background-color: #00a8ff; border-radius: 2px;"></div>
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">VİTRİN YÖNETİMİ</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Ürün Yönetimi</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Sitede sergilenen ürünleri ekleyin, düzenleyin veya kaldırın.</p>
            </div>
            
            <div>
                <a href="add-product.php" class="btn btn-add-new fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px;">
                    <i class="fas fa-plus-circle me-2"></i> Yeni Ürün Ekle
                </a>
            </div>
        </div>

        <div class="filter-wrapper mb-4 shadow-sm">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control custom-search-input w-100" placeholder="Ürün adı veya SKU ara..." value="<?= htmlspecialchars($search) ?>">
                </div>
                
                <div class="col-md-3">
                    <select name="category" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Kategoriler</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($category_filter == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Durumlar</option>
                        <option value="aktif" <?= ($status_filter === 'aktif') ? 'selected' : '' ?>>Aktif (Sitede Görünür)</option>
                        <option value="pasif" <?= ($status_filter === 'pasif') ? 'selected' : '' ?>>Pasif (Gizli)</option>
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
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 ps-4" style="letter-spacing: 1px; width: 5%;">#</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 35%;">Ürün Bilgisi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 20%;">Kategori</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-center" style="letter-spacing: 1px; width: 15%;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px; width: 25%;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0 bg-white">
                        
                        <?php if(count($products) > 0): ?>
                            <?php foreach($products as $index => $prod): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-secondary"><?= $offset + $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="product-thumb-wrapper">
                                            <?php $imgSrc = !empty($prod['image_url']) ? "../" . htmlspecialchars($prod['image_url']) : "https://cdn-icons-png.flaticon.com/512/3050/3050186.png"; ?>
                                            <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($prod['title']) ?>">
                                        </div>
                                        <div>
                                            <div class="fw-bolder text-brand-dark" style="font-size: 15px;">
                                                <?= htmlspecialchars($prod['title']) ?>
                                            </div>
                                            <div class="small fw-medium text-secondary mt-1">
                                                <?= !empty($prod['sku']) ? "SKU: " . htmlspecialchars($prod['sku']) : "SKU Yok" ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold" style="color: #1C4F8C;">
                                        <?= htmlspecialchars($prod['category_name'] ?? 'Kategorisiz') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if($prod['status'] == 'aktif'): ?>
                                        <span class="status-badge status-aktif">AKTİF</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pasif">PASİF</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="edit-product.php?id=<?= $prod['id'] ?>" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                    <button type="button" class="action-btn btn-delete ms-1" title="Sil" onclick="confirmDelete('delete-product.php?id=<?= $prod['id'] ?>', 'Bu ürünü ve ona ait görseli tamamen silmek üzeresiniz.')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                    <h5 class="fw-bold">Ürün Bulunamadı</h5>
                                    <p class="mb-0">Arama kriterlerinize uygun veya sisteme eklenmiş bir ürün yok.</p>
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <?php if($total_pages > 1): ?>
            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-white">
                <div class="small fw-medium text-secondary">
                    Toplam <span class="fw-bold text-brand-dark"><?= $total_records ?></span> üründen 
                    <span class="fw-bold text-brand-dark"><?= $offset + 1 ?>-<?= min($offset + $limit, $total_records) ?></span> arası gösteriliyor.
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?><?= $queryString ?>"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= $queryString ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?><?= $queryString ?>"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>

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