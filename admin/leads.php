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

// Filtre değerlerini URL'den alıyorum (Boşlarsa boş kalırlar)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$typeFilter = isset($_GET['type']) ? trim($_GET['type']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';

// Ana SQL sorgumu başlatıyorum
$sql = "SELECT * FROM leads WHERE 1=1";
$params = [];

// Eğer arama kutusuna bir şey yazıldıysa
if($search != "") {
    $sql = $sql . " AND (name LIKE :search OR phone LIKE :search OR email LIKE :search)";
    $params[':search'] = "%" . $search . "%";
}

// Eğer talep türü (Sipariş/Bayilik/İletişim) seçildiyse
if($typeFilter != "") {
    $sql = $sql . " AND type = :type";
    $params[':type'] = $typeFilter;
}

// Eğer durum (Yeni/İşlemde/Tamamlandı) seçildiyse
if($statusFilter != "") {
    $sql = $sql . " AND status = :status";
    $params[':status'] = $statusFilter;
}

// Verileri okuyorum (En yeniden eskiye sıralı)
$sql = $sql . " ORDER BY created_at DESC";
$statement = $db->prepare($sql);
$statement->execute($params);

$leadList = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişler ve Başvurular | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">MÜŞTERİ YÖNETİMİ</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Siparişler & Başvurular</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Sistem üzerinden gelen tüm talepleri buradan yönetebilirsiniz.</p>
            </div>
            
            <div>
                <button class="btn fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px; background-color: #1C4F8C; color: #fff;">
                    <i class="fas fa-download me-2"></i> Excel Olarak İndir
                </button>
            </div>
        </div>

        <div class="filter-wrapper mb-4 shadow-sm">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control custom-search-input w-100" placeholder="Müşteri adı, telefon veya e-posta ara..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                
                <div class="col-md-3">
                    <select name="type" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Talep Türleri</option>
                        <option value="damacana" <?php if($typeFilter == "damacana") echo "selected"; ?>>Damacana Siparişi</option>
                        <option value="bayilik" <?php if($typeFilter == "bayilik") echo "selected"; ?>>Bayilik Başvurusu</option>
                        <option value="iletisim" <?php if($typeFilter == "iletisim") echo "selected"; ?>>Genel İletişim</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Durumlar</option>
                        <option value="yeni" <?php if($statusFilter == "yeni") echo "selected"; ?>>Yeni</option>
                        <option value="islemde" <?php if($statusFilter == "islemde") echo "selected"; ?>>İşlemde</option>
                        <option value="tamamlandi" <?php if($statusFilter == "tamamlandi") echo "selected"; ?>>Tamamlandı</option>
                        <option value="iptal" <?php if($statusFilter == "iptal") echo "selected"; ?>>İptal</option>
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
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 25%;">Müşteri / Firma Bilgisi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 20%;">İletişim</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 15%;">Talep Türü</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 15%;">Tarih</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-center" style="letter-spacing: 1px; width: 10%;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px; width: 10%;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0 bg-white">
                        
                        <?php if(empty($leadList)) { ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-secondary">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                    <h5 class="fw-bold">Talep Bulunamadı</h5>
                                    <p class="mb-0">Henüz gelen bir başvuru veya sipariş yok.</p>
                                </td>
                            </tr>
                        <?php } else { ?>

                            <?php foreach($leadList as $lead) { ?>
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#<?php echo $lead['id']; ?></td>
                                
                                <td>
                                    <div class="fw-bolder text-brand-dark"><?php echo htmlspecialchars($lead['name']); ?></div>
                                    <div class="small fw-medium text-secondary mt-1">
                                        <i class="fas fa-map-marker-alt text-brand-blue me-1"></i> 
                                        <?php echo htmlspecialchars($lead['city'] . ', ' . $lead['district']); ?>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="text-brand-dark fw-medium" style="font-size: 14px;">
                                        <?php echo htmlspecialchars($lead['phone']); ?>
                                    </div>
                                    <div class="small text-secondary mt-1">
                                        <?php echo htmlspecialchars($lead['email']); ?>
                                    </div>
                                </td>
                                
                                <td>
                                    <?php 
                                        if($lead['type'] == 'damacana') {
                                            echo '<span class="fw-bold" style="color: #1C4F8C;">Damacana Siparişi</span>';
                                        } else if($lead['type'] == 'bayilik') {
                                            echo '<span class="fw-bold" style="color: #f59e0b;">Bayilik Başvurusu</span>';
                                        } else {
                                            echo '<span class="fw-bold" style="color: #64748b;">Genel İletişim</span>';
                                        }
                                    ?>
                                </td>
                                
                                <td class="text-secondary fw-medium" style="font-size: 14px;">
                                    <?php 
                                        echo date('d M Y', strtotime($lead['created_at'])) . '<br>'; 
                                        echo '<span class="small opacity-75">' . date('H:i', strtotime($lead['created_at'])) . '</span>';
                                    ?>
                                </td>
                                
                                <td class="text-center">
                                    <?php 
                                        if($lead['status'] == 'yeni') {
                                            echo '<span class="status-badge status-yeni" style="background: rgba(0, 168, 255, 0.1); color: #00a8ff; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 800;">YENİ</span>';
                                        } else if($lead['status'] == 'islemde') {
                                            echo '<span class="status-badge status-islemde" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 800;">İŞLEMDE</span>';
                                        } else if($lead['status'] == 'tamamlandi') {
                                            echo '<span class="status-badge status-aktif" style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 800;">TAMAMLANDI</span>';
                                        } else {
                                            echo '<span class="status-badge status-pasif" style="background: rgba(100, 116, 139, 0.1); color: #64748b; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 800;">İPTAL</span>';
                                        }
                                    ?>
                                </td>
                                
                                <td class="text-end pe-4">
                                    <a href="lead-detail.php?id=<?php echo $lead['id']; ?>" class="action-btn btn-view" title="Görüntüle/İşlem Yap" style="background: rgba(28, 79, 140, 0.1); color: #1C4F8C;"><i class="fas fa-eye"></i></a>
                                    
                                    <button type="button" class="action-btn btn-delete ms-1" title="Sil" onclick="confirmDelete('delete-lead.php?id=<?php echo $lead['id']; ?>', 'Bu talebi sistemden kalıcı olarak silmek üzeresiniz.')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>

                        <?php } ?>

                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-white">
                <div class="small fw-medium text-secondary">
                    Toplam <span class="fw-bold text-brand-dark"><?php echo count($leadList); ?></span> kayıt listeleniyor.
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