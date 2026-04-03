<?php 
// Veritabanı bağlantımızı sayfanın en başında çağırıyoruz
require_once 'config/db.php';
$database = new Database();
$db = $database->connect();

// 1. GENEL AYARLARI ÇEK (En alttaki telefon numarası için)
$settings = [];
try {
    $stmt = $db->prepare("SELECT setting_key, setting_value FROM settings");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch(PDOException $e) {
    // Hata durumunda sessizce geç
}

// Ayarları güvenle ekrana basmak için yardımcı fonksiyon
function getSetting($key, $array, $default = '') {
    return (isset($array[$key]) && $array[$key] !== '') ? htmlspecialchars($array[$key]) : $default;
}

// 2. AKTİF ÜRÜNLERİ ÇEK
$products = [];
try {
    $prodStmt = $db->prepare("SELECT * FROM products WHERE status = 'aktif' ORDER BY id ASC");
    $prodStmt->execute();
    $products = $prodStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Hata durumunda boş kalsın
}

include 'includes/header.php'; 
?>

    <div class="modern-page-header">
        <div class="container">
            <span class="subtitle reveal-bottom">Sağlık Kaynağınız</span>
            <h1 class="reveal-bottom delay-100">Ürün Ailemiz</h1>
            <div class="header-line reveal-bottom delay-200"></div>
        </div>
    </div>

    <div class="floating-filter-wrapper">
        <div class="floating-filter-nav">
            <button class="filter-btn active" data-filter="all"><i class="fas fa-th-large"></i> Tümü</button>
            <button class="filter-btn" data-filter="cam"><i class="fas fa-wine-bottle"></i> Cam Serisi</button>
            <button class="filter-btn" data-filter="damacana"><i class="fas fa-tint"></i> Polikarbon</button>
            <button class="filter-btn" data-filter="pet"><i class="fas fa-bottle-water"></i> Pet Şişe</button>
        </div>
    </div>

    <section class="products-page-section">
        <div class="container">
            
            <div class="detailed-products-grid">

                <?php 
                if(count($products) > 0) {
                    foreach($products as $index => $product) { 
                        
                        // Ürün ismine göre filtre kategorisini (cam, pet, damacana) belirliyoruz
                        // DÜZELTME: 'name' yerine 'title' kullanıldı.
                        $isim = strtolower($product['title']);
                        if (strpos($isim, 'cam') !== false) {
                            $kategori = 'cam';
                        } elseif (strpos($isim, 'pet') !== false) {
                            $kategori = 'pet';
                        } else {
                            $kategori = 'damacana'; // Varsayılan
                        }

                        // Animasyon gecikmesi için ufak bir hesap (0, 100, 200, 300...)
                        $delay = ($index % 4) * 100;
                ?>
                <div class="detailed-product-card reveal-bottom <?php echo $delay > 0 ? 'delay-'.$delay : ''; ?>" data-category="<?php echo $kategori; ?>">
                    <div class="dp-image">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    </div>
                    <div class="dp-info">
                        <div class="dp-badges">
                            <span class="badge badge-premium"><?php echo htmlspecialchars($product['sku']); ?></span>
                            <span class="badge badge-normal"><?php echo number_format($product['price'], 2, ',', '.'); ?> TL</span>
                        </div>
                        
                        <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        
                        <ul class="dp-features">
                            <li><i class="fas fa-check"></i> %100 Doğal Kaynak Suyu</li>
                            <li><i class="fas fa-check"></i> El Değmeden Dolum</li>
                            <li><i class="fas fa-check"></i> İdeal Mineral Dengesi</li>
                        </ul>
                        
                        <a href="iletisim.php?urun=<?php echo $product['id']; ?>" class="btn-dp-order">
                            <i class="fas fa-shopping-basket"></i> Sipariş Ver
                        </a>
                    </div>
                </div>
                <?php 
                    } 
                } else {
                ?>
                    <div class="w-100 text-center py-5" style="color: #64748b;">
                        <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                        <h4>Şu an gösterilecek aktif ürün bulunmuyor.</h4>
                    </div>
                <?php } ?>

            </div>
        </div>
    </section>

    <section class="order-cta-section" style="padding: 40px 0 100px; background: var(--light-gray);">
        <div class="container">
            <div class="reveal-bottom" style="background: var(--white); border-radius: 25px; padding: 50px 30px; text-align: center; box-shadow: 0 15px 35px rgba(0,0,0,0.03); border: 1px solid rgba(28, 79, 140, 0.05);">

                <h2 style="font-size: 32px; font-weight: 800; color: var(--brand-dark); margin-bottom: 15px;">Sağlıklı Su, <span style="color: var(--brand-main);">Kapınıza Kadar Gelsin</span></h2>
                <p style="color: #64748b; font-size: 16px; margin-bottom: 35px; max-width: 650px; margin-left: auto; margin-right: auto; line-height: 1.8;">Türkiye'nin dört bir yanındaki geniş bayi ağımızla, Karacapınar ferahlığına bir telefonla veya tek tıkla ulaşabilirsiniz.</p>

                <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    
                    <a href="tel:<?php echo str_replace(' ', '', getSetting('phone', $settings, '444 0 000')); ?>" style="display: inline-flex; align-items: center; gap: 10px; background: var(--brand-main); color: var(--white); padding: 14px 40px; border-radius: 50px; font-weight: 700; font-size: 15px; box-shadow: 0 10px 20px rgba(28, 79, 140, 0.2); transition: 0.3s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 25px rgba(28, 79, 140, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(28, 79, 140, 0.2)';">
                        <i class="fas fa-phone-alt"></i> <?php echo getSetting('phone', $settings, '444 0 000'); ?>'ı Ara
                    </a>

                    <a href="bayiler.php" style="display: inline-flex; align-items: center; gap: 10px; background: var(--white); color: var(--brand-main); border: 2px solid var(--brand-main); padding: 12px 40px; border-radius: 50px; font-weight: 700; font-size: 15px; transition: 0.3s;" onmouseover="this.style.background='var(--brand-main)'; this.style.color='var(--white)';" onmouseout="this.style.background='var(--white)'; this.style.color='var(--brand-main)';">
                        <i class="fas fa-map-marker-alt"></i> En Yakın Bayiyi Bul
                    </a>

                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>