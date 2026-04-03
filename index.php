<?php 
// Veritabanı bağlantımızı sayfanın en başında çağırıyoruz
require_once 'config/db.php';
$database = new Database();
$db = $database->connect();

// 1. GENEL AYARLARI ÇEK (Tecrübe Yılı, pH değeri vs. için)
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

// Ayarları güvenle basmak için küçük yardımcı fonksiyonumuz
function getSetting($key, $array, $default = '') {
    return (isset($array[$key]) && $array[$key] !== '') ? htmlspecialchars($array[$key]) : $default;
}

// 2. AKTİF ÜRÜNLERİ ÇEK (Carousel'de listelemek için)
$products = [];
try {
    // Sadece aktif ürünleri ve sıralama numarasına (id) göre çekiyoruz
    $prodStmt = $db->prepare("SELECT * FROM products WHERE status = 'aktif' ORDER BY id ASC");
    $prodStmt->execute();
    $products = $prodStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Hata durumunda boş kalsın
}

// Üst kısmı (Header) dahil et
include 'includes/header.php'; 
?>

    <div class="hero-slider">
        <div class="item" style="background-image: url('https://images.unsplash.com/photo-1548839140-29a749e1cf4d?q=80&w=1920&auto=format&fit=crop');">
            <div class="hero-overlay"></div>
            <div class="hero-caption">
                <h1 class="reveal-left">DOĞAL YAŞAMIN<br>KAYNAĞI</h1>
                <p class="reveal-left delay-200" style="font-size: 1.3rem; margin-bottom: 30px;">Torosların zirvesinden gelen el değmemiş lezzet.</p>
                <a href="#" class="hero-btn reveal-left delay-400">Keşfetmeye Başla</a>
            </div>
        </div>
        <div class="item" style="background-image: url('https://images.unsplash.com/photo-1562016600-ece13e8ba570?q=80&w=1938&auto=format&fit=crop');">
            <div class="hero-overlay"></div>
            <div class="hero-caption">
                <h1 class="reveal-left">HER YUDUMDA<br>TAZELİK</h1>
                <p class="reveal-left delay-200" style="font-size: 1.3rem; margin-bottom: 30px;">Aileniz için ideal mineral dengesi, <?php echo getSetting('stat_ph_value', $settings, '8.2'); ?> pH.</p>
                <a href="#urunler-alani" class="hero-btn reveal-left delay-400">Ürünleri İncele</a>
            </div>
        </div>
    </div>

    <div class="wave-container">
        <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
        </svg>
    </div>

    <section class="features-section">
        <div class="container">
            <div class="section-header reveal-bottom">
                <h2 class="section-title"><?php echo getSetting('stat_experience', $settings, '40'); ?> Yıllık Tecrübe ve Teknolojiyle<br><b>Karacapınar Güvencesi</b></h2>
                <div class="title-line"></div>
            </div>
            
            <div class="features-grid">
                <div class="feature-box reveal-bottom delay-100">
                    <div class="icon-wrapper">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <h3>El Değmeden Üretim</h3>
                    <p>Tam otomasyon sistemleri ile kaynağındaki doğallığı, ilk günkü saflığıyla şişeye hapsediyoruz.</p>
                </div>
                
                <div class="feature-box reveal-bottom delay-200">
                    <div class="icon-wrapper">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>%100 Doğal Kaynak</h3>
                    <p>İdeal mineral dengesi bozulmadan, Torosların zirvesinden gelen doğanın en saf halini sunuyoruz.</p>
                </div>
                
                <div class="feature-box reveal-bottom delay-300">
                    <div class="icon-wrapper">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h3>Sürdürülebilir Çevre</h3>
                    <p>Gelecek nesillere temiz bir dünya bırakmak için %100 geri dönüştürülebilir ambalajlar kullanıyoruz.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="urunler-alani" class="products-carousel-section">
        <div class="container">
            
            <div class="section-header reveal-bottom">
                <h2 class="section-title">Ürün <b>Ailemiz</b></h2>
                <div class="title-line" style="margin-bottom: 20px;"></div>
                <p style="color:#64748b; font-size: 16px;">İhtiyacınıza en uygun doğal kaynak suyu ambalajları.</p>
            </div>
            
            <div class="carousel-wrapper reveal-bottom delay-100">
                <button class="carousel-btn left-btn" id="btn-prev"><i class="fas fa-arrow-left"></i></button>
                
                <div class="carousel-track" id="product-track">
                    
                    <?php 
                    // Veritabanındaki ürünleri dinamik olarak HTML'e basıyoruz
                    if(count($products) > 0) {
                        foreach($products as $product) { 
                    ?>
                        <div class="product-modern-card">
                            <div class="product-img-holder">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                            </div>
                            <div class="product-details">
                                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="product-link">İncele <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    <?php 
                        } 
                    } else { 
                    ?>
                        <div style="width: 100%; text-align: center; padding: 40px; color: #64748b;">
                            Henüz sisteme eklenmiş aktif bir ürün bulunmuyor.
                        </div>
                    <?php } ?>

                </div>
                
                <button class="carousel-btn right-btn" id="btn-next"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    </section>

    <section class="showcase">
        <div class="reveal-bottom">
            
            <div class="section-header">
                <h2 class="section-title">Doğallığı <b>Keşfet</b></h2>
                <div class="title-line" style="margin-bottom: 20px;"></div>
                <p style="color: #64748b;">Mineralleri keşfetmek için şişeye tıklayın.</p>
            </div>
            
            <div class="pop-container" style="position: relative; width: 100%; max-width: 900px; height: 500px; margin: 0 auto; display: flex; align-items: flex-end; justify-content: center;">
                
                <div class="bottle-pop-wrapper" id="mineral-bottle" style="position: relative; z-index: 10; cursor: pointer;">
                    <div class="bottle-cap" id="bottle-cap"></div>
                    <img src="uploads/img/sise.png" class="pop-bottle-img" alt="Karacapınar Şişe">
                    <div class="click-hint-pop" id="clickText">AÇMAK İÇİN TIKLA</div>
                </div>

                <div class="mineral-burst-card b-ph" data-color="#00a8ff">
                    <div class="burst-inner">
                        <span class="m-icon-burst">pH</span>
                        <div class="m-info-burst"><?php echo getSetting('stat_ph_value', $settings, '8.2'); ?><br><span class="desc">Alkali</span></div>
                    </div>
                </div>
                <div class="mineral-burst-card b-ca" data-color="#1C4F8C">
                    <div class="burst-inner">
                        <span class="m-icon-burst">Ca</span>
                        <div class="m-info-burst">32.5<br><span class="desc">Kalsiyum</span></div>
                    </div>
                </div>
                <div class="mineral-burst-card b-mg" data-color="#00a8ff">
                    <div class="burst-inner">
                        <span class="m-icon-burst">Mg</span>
                        <div class="m-info-burst">12.4<br><span class="desc">Magnezyum</span></div>
                    </div>
                </div>
                <div class="mineral-burst-card b-na" data-color="#1C4F8C">
                    <div class="burst-inner">
                        <span class="m-icon-burst">Na</span>
                        <div class="m-info-burst">1.2<br><span class="desc">Sodyum</span></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>