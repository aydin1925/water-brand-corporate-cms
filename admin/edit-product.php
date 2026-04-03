<?php
session_start();

require_once '../config/db.php';
require_once '../config/helpers.php'; 

$database = new Database();
$db = $database->connect();

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$messageType = ""; 

// 1. KATEGORİLERİ ÇEK (Açılır menü için)
$categories = [];
try {
    $catStmt = $db->query("SELECT id, name FROM categories WHERE status = 'aktif' ORDER BY name ASC");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Kategoriler çekilemedi: " . $e->getMessage());
}

// 2. ID KONTROLÜ VE MEVCUT VERİYİ ÇEKME
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pId = intval($_GET['id']);

    try {
        $sql = "SELECT * FROM products WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->execute([':id' => $pId]);
        $product = $statement->fetch(PDO::FETCH_ASSOC);

        if(!$product) {
            header("Location: products.php");
            exit;
        }
    } catch(PDOException $e) {
        die("Veritabanı Hatası: " . $e->getMessage());
    }
} else {
    header("Location: products.php");
    exit;
}

// 3. FORM GÖNDERİLDİĞİNDE (GÜNCELLEME İŞLEMİ)
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $title = trim($_POST['title']);
    $sku = trim($_POST['sku']); 
    $categoryId = (int)$_POST['category_id'];
    $phValue = !empty($_POST['ph']) ? (float)$_POST['ph'] : null;
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    
    // SEO URL Güncelle
    $slug = generateSlug($title);
    
    // Özellikleri JSON yap
    $propertiesInput = trim($_POST['properties']);
    $propertiesJson = null;
    if(!empty($propertiesInput)) {
        $propsArray = array_map('trim', explode(',', $propertiesInput));
        $propertiesJson = json_encode($propsArray, JSON_UNESCAPED_UNICODE);
    }

    $imagePath = $_POST['current_image']; 

    // YENİ RESİM YÜKLENDİ Mİ?
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadAndConvertToWebP($_FILES['image'], 'products', 'urun');

        if($uploadResult) {
            $imagePath = $uploadResult; 
        } else {
            $message = "Yeni resim yüklenemedi veya desteklenmeyen format.";
            $messageType = "error";
        }
    }

    // VERİTABANINI GÜNCELLE
    if(empty($messageType)) {
        try {
            $updateSql = "UPDATE products SET 
                            category_id = :category_id, 
                            title = :title, 
                            slug = :slug, 
                            sku = :sku,
                            image_url = :image_url, 
                            ph = :ph, 
                            description = :description, 
                            properties = :properties, 
                            status = :status 
                          WHERE id = :id";
            
            $updateStmt = $db->prepare($updateSql);
            
            $updateStmt->execute([
                ':category_id' => $categoryId,
                ':title'       => $title,
                ':slug'        => $slug,
                ':sku'         => $sku,
                ':image_url'   => $imagePath,
                ':ph'          => $phValue,
                ':description' => $description,
                ':properties'  => $propertiesJson,
                ':status'      => $status,
                ':id'          => $pId
            ]);
            
            $message = "Ürün bilgileri başarıyla güncellendi.";
            $messageType = "success";
            
            // Formda yeni bilgilerin görünmesi için $product değişkenini tazele
            $statement->execute([':id' => $pId]);
            $product = $statement->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Bu ürün adı veya stok kodu zaten kullanılıyor (Benzersizlik çakışması).";
            } else {
                $message = "Sistem Hatası: Güncelleme yapılamadı.";
            }
            $messageType = "error";
            error_log("Ürün Güncelleme Hatası: " . $e->getMessage());
        }
    }
}

// 4. Ekrana basmadan önce JSON özellikleri virgüllü metne geri çevir
$propertiesString = "";
if (!empty($product['properties'])) {
    $decodedProps = json_decode($product['properties'], true);
    if(is_array($decodedProps)) {
        $propertiesString = implode(', ', $decodedProps);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Düzenle | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">ÜRÜN YÖNETİMİ</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Ürün Düzenle</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Mevcut ürünün bilgilerini, kategorisini ve yayın durumunu güncelleyin.</p>
            </div>
            
            <div>
                <a href="products.php" class="btn border fw-bold px-4 py-2 rounded-pill shadow-sm bg-white text-secondary" style="font-size: 14px; transition: 0.3s;">
                    <i class="fas fa-arrow-left me-2"></i> Listeye Dön
                </a>
            </div>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($product['image_url'] ?? '') ?>">

            <div class="row g-4">
                
                <div class="col-lg-8">
                    <div class="glass-panel p-4 p-md-5 h-100">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-pen-square text-brand-blue me-2"></i>Ürün Detayları</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="custom-form-label">Ürün Adı <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control custom-form-control" value="<?= htmlspecialchars($product['title']) ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="custom-form-label">Stok Kodu (SKU)</label>
                                <input type="text" name="sku" class="form-control custom-form-control" value="<?= htmlspecialchars($product['sku'] ?? '') ?>" placeholder="Örn: DAM-19L">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="custom-form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select custom-form-control" required>
                                    <option value="">Seçiniz...</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">pH Değeri</label>
                                <input type="number" step="0.01" name="ph" class="form-control custom-form-control" value="<?= htmlspecialchars($product['ph'] ?? '') ?>">
                            </div>

                            <div class="col-12">
                                <label class="custom-form-label">Ürün Özellikleri (Etiketler)</label>
                                <input type="text" name="properties" class="form-control custom-form-control" value="<?= htmlspecialchars($propertiesString) ?>">
                                <div class="form-text mt-1" style="font-size: 11px;"><i class="fas fa-info-circle me-1"></i> Özellikleri birbirinden <b>virgül (,)</b> ile ayırarak yazınız.</div>
                            </div>

                            <div class="col-12">
                                <label class="custom-form-label">Ürün Açıklaması</label>
                                <textarea name="description" class="form-control custom-form-control" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="glass-panel p-4 shadow-sm mb-4">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-camera-retro text-brand-blue me-2"></i>Ürün Görseli</h5>
                        
                        <div class="mb-3 text-center">
                            <div class="position-relative d-inline-block mb-3">
                                <div style="background: #f8fafc; border: 1px solid rgba(28, 79, 140, 0.1); border-radius: 12px; padding: 10px;">
                                    <?php 
                                        $currentImgSrc = !empty($product['image_url']) ? "../" . htmlspecialchars($product['image_url']) : "https://cdn-icons-png.flaticon.com/512/3050/3050186.png"; 
                                    ?>
                                    <img src="<?= $currentImgSrc ?>" alt="Mevcut Görsel" class="object-fit-contain" style="width: 120px; height: 120px;">
                                </div>
                                
                                <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-2 shadow" style="transform: translate(25%, 25%); border: 3px solid #fff;" title="Mevcut Fotoğraf">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>

                            <label class="custom-form-label d-block text-start mt-2">Yeni Fotoğraf Yükle (Değiştirmek İstersen)</label>
                            <input type="file" name="image" class="form-control custom-form-control form-control-sm" accept="image/jpeg, image/png, image/webp">
                            <div class="form-text mt-2 text-start" style="font-size: 11px;"><i class="fas fa-info-circle"></i> Sadece resmi değiştirmek istiyorsanız dosya seçin. (Max: 2MB).</div>
                        </div>
                    </div>

                    <div class="glass-panel p-4 shadow-sm">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-sliders-h text-brand-blue me-2"></i>Yayın Ayarları</h5>
                        
                        <div class="mb-4">
                            <label class="custom-form-label">Durum</label>
                            <select name="status" class="form-select custom-form-control fw-bold">
                                <option value="aktif" <?= ($product['status'] == 'aktif') ? 'selected' : '' ?>>Aktif (Sitede Görünsün)</option>
                                <option value="pasif" <?= ($product['status'] == 'pasif') ? 'selected' : '' ?>>Pasif (Gizle)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn w-100 rounded-pill py-3 fw-bolder text-white shadow-sm hover-scale" style="background: linear-gradient(45deg, #1C4F8C, #00a8ff); letter-spacing: 1px; border: none;">
                            <i class="fas fa-sync-alt me-2"></i> DEĞİŞİKLİKLERİ KAYDET
                        </button>
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
            showAlert('<?= $messageType ?>', '<?= $message ?>');
        });
    </script>
    <?php endif; ?>

</body>
</html>