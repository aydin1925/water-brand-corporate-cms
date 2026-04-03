<?php
session_start();

// Admin giriş kontrolü
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/db.php';
require_once '../config/helpers.php';

$database = new Database();
$db = $database->connect();

$message = "";
$messageType = "";

// 1. Kategorileri Veritabanından Çek (Açılır menü için)
$categories = [];
try {
    $catStmt = $db->query("SELECT id, name FROM categories WHERE status = 'aktif' ORDER BY name ASC");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Kategoriler çekilemedi: " . $e->getMessage());
}

// 2. Form Gönderildiğinde İşlemleri Yap
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $title = trim($_POST['title']);
    $sku = trim($_POST['sku']); 
    $categoryId = (int)$_POST['category_id'];
    $phValue = !empty($_POST['ph']) ? (float)$_POST['ph'] : null;
    $description = trim($_POST['description']);
    $status = $_POST['status']; 
    
    // SEO URL (Slug) Oluştur
    $slug = generateSlug($title); 
    
    // Özellikleri (Etiketleri) JSON formatına çevir
    $propertiesInput = trim($_POST['properties']);
    $propertiesJson = null;
    if(!empty($propertiesInput)) {
        $propsArray = array_map('trim', explode(',', $propertiesInput));
        $propertiesJson = json_encode($propsArray, JSON_UNESCAPED_UNICODE);
    }

    $imagePath = null; 

    // 3. Resim Yükleme İşlemi
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadAndConvertToWebP($_FILES['image'], 'products', 'urun');

        if($uploadResult) {
            $imagePath = $uploadResult; 
        } else {
            $message = "Resim yüklenemedi veya desteklenmeyen format. (Sadece JPG, PNG, WEBP)";
            $messageType = "error";
        }
    }

    // 4. Veritabanına Kayıt
    if(empty($messageType)) {
        try {
            $sql = "INSERT INTO products (category_id, title, slug, sku, image_url, ph, description, properties, status) 
                    VALUES (:category_id, :title, :slug, :sku, :image_url, :ph, :description, :properties, :status)";
            
            $statement = $db->prepare($sql);
            
            $statement->execute([
                ':category_id' => $categoryId,
                ':title'       => $title,
                ':slug'        => $slug,
                ':sku'         => $sku, 
                ':image_url'   => $imagePath,
                ':ph'          => $phValue,
                ':description' => $description,
                ':properties'  => $propertiesJson,
                ':status'      => $status
            ]);
            
            $message = "Ürün başarıyla vitrine eklendi.";
            $messageType = "success";
            
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Bu ürün adı veya stok kodu (SKU) zaten kullanılıyor. Lütfen farklı bir ad/kod girin.";
            } else {
                $message = "Sistem Hatası: Veritabanına kaydedilemedi.";
            }
            $messageType = "error";
            error_log("Ürün Ekleme Hatası: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Ürün Ekle | Karacapınar Su</title>
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
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Yeni Ürün Ekle</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Web sitesi vitrininde sergilenecek yeni bir ürün oluşturun.</p>
            </div>
            
            <div>
                <a href="products.php" class="btn border fw-bold px-4 py-2 rounded-pill shadow-sm bg-white text-secondary" style="font-size: 14px; transition: 0.3s;">
                    <i class="fas fa-arrow-left me-2"></i> Listeye Dön
                </a>
            </div>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row g-4">
                
                <div class="col-lg-8">
                    <div class="glass-panel p-4 p-md-5 h-100">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-box-open text-brand-blue me-2"></i>Ürün Detayları</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="custom-form-label">Ürün Adı <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control custom-form-control" placeholder="Örn: 19 Litre Cam Damacana" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="custom-form-label">Stok Kodu (SKU)</label>
                                <input type="text" name="sku" class="form-control custom-form-control" placeholder="Örn: DAM-19L">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="custom-form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select custom-form-control" required>
                                    <option value="">Seçiniz...</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="custom-form-label">pH Değeri</label>
                                <input type="number" step="0.01" name="ph" class="form-control custom-form-control" placeholder="Örn: 8.20">
                            </div>

                            <div class="col-md-8">
                                <label class="custom-form-label">Ürün Özellikleri (Etiketler)</label>
                                <input type="text" name="properties" class="form-control custom-form-control" placeholder="Örn: 100% Doğal, Cam Ambalaj, Zengin Mineral">
                                <div class="form-text mt-1" style="font-size: 11px;"><i class="fas fa-info-circle me-1"></i> Özellikleri birbirinden <b>virgül (,)</b> ile ayırarak yazınız.</div>
                            </div>

                            <div class="col-12">
                                <label class="custom-form-label">Ürün Açıklaması</label>
                                <textarea name="description" class="form-control custom-form-control" rows="5" placeholder="Ürün hakkında detaylı bilgi metni..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="glass-panel p-4 mb-4 text-center">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3 text-start"><i class="fas fa-camera text-brand-blue me-2"></i>Ürün Görseli</h5>
                        
                        <div class="bg-light border border-dashed rounded-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; border-style: dashed !important; border-color: #cbd5e1 !important;">
                            <i class="fas fa-bottle-water fa-3x text-secondary opacity-25"></i>
                        </div>
                        <input type="file" name="image" class="form-control custom-form-control form-control-sm" accept="image/jpeg, image/png, image/webp">
                        <div class="form-text mt-2 text-start" style="font-size: 11px;"><i class="fas fa-info-circle"></i> Şeffaf arka planlı (.png veya .webp) görsel önerilir. Maksimum 2MB.</div>
                    </div>

                    <div class="glass-panel p-4 h-100 d-flex flex-column">
                        <h5 class="fw-bold text-brand-main mb-4 border-bottom pb-3"><i class="fas fa-sliders-h text-brand-blue me-2"></i>Yayın Ayarları</h5>
                        
                        <div class="mb-4">
                            <label class="custom-form-label">Durum <span class="text-danger">*</span></label>
                            <select name="status" class="form-select custom-form-control fw-bold" required>
                                <option value="aktif">Aktif (Sitede Görünsün)</option>
                                <option value="pasif">Pasif (Gizle)</option>
                            </select>
                        </div>

                        <div class="mt-auto pt-4">
                            <button type="submit" class="btn w-100 rounded-pill py-3 fw-bolder text-white shadow-sm hover-scale" style="background: linear-gradient(45deg, #1C4F8C, #00a8ff); letter-spacing: 1px; border: none;">
                                <i class="fas fa-save me-2"></i> ÜRÜNÜ KAYDET
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
            // Başarılı olursa products.php'ye yönlendir
            let redirect = '<?= $messageType == "success" ? "products.php" : "" ?>';
            showAlert('<?= $messageType ?>', '<?= $message ?>', redirect ? redirect : null);
        });
    </script>
    <?php endif; ?>

</body>
</html>