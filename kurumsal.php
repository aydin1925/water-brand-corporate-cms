<?php 
// Veritabanı bağlantımızı sayfanın en başında çağırıyoruz
require_once 'config/db.php';
$database = new Database();
$db = $database->connect();

// GENEL AYARLARI ÇEK (Rakamlarla Biz İstatistikleri için)
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

// Üst kısmı (Header) dahil et
include 'includes/header.php'; 
?>

    <div class="inner-hero" style="background-image: url('https://images.unsplash.com/photo-1434125864115-4cb2a5494f6f?q=80&w=1920&auto=format&fit=crop');">
        <div class="inner-hero-overlay"></div>
        <div class="container inner-hero-content">
            <h1 class="reveal-bottom">Hakkımızda</h1>
            <p class="reveal-bottom delay-100">Doğanın kalbinden sofralarınıza uzanan köklü serüven.</p>
        </div>
        <div class="wave-container inner-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
            </svg>
        </div>
    </div>

    <div class="floating-nav-wrapper">
        <div class="floating-nav">
            <ul id="floating-menu">
                <li><a href="#tarihce"><i class="fas fa-book-open"></i> Tarihçemiz</a></li>
                <li><a href="#istatistikler"><i class="fas fa-chart-line"></i> Rakamlarla Biz</a></li>
                <li><a href="#kaynak"><i class="fas fa-mountain"></i> Kaynağımız</a></li>
                <li><a href="#uretim"><i class="fas fa-industry"></i> Üretim</a></li>
            </ul>
        </div>
    </div>

    <section id="tarihce" class="corporate-intro">
        <div class="container">
            <div class="intro-header reveal-bottom">
                <span class="brand-subtitle">TARİHÇEMİZ</span>
                <h2 class="section-title">Yarım Asra Yaklaşan<br><b>Doğallık Tutkusu</b></h2>
                <div class="title-line"></div>
            </div>
            <div class="intro-content reveal-bottom delay-100">
                <p>
                    1980'li yıllarda Torosların zirvesinden ilham alarak çıktığımız bu yolda, Karacapınar olarak Türkiye'nin en köklü ve güvenilir doğal kaynak suyu markalarından biri olmanın gururunu yaşıyoruz. Kurulduğumuz ilk günden bu yana doğanın bize sunduğu saflığı korumayı ve gelecek nesillere taşımayı en büyük sorumluluğumuz olarak benimsedik.
                </p>
                <p>
                    Bugün geldiğimiz noktada, doğadan aldığımız gücü en ileri teknolojiyle birleştiriyor; yüksek hijyen standartlarında, el değmeden şişelediğimiz sularımızı ülkemizin dört bir yanındaki tüketicilerimizle buluşturuyoruz. Sadece su değil, sağlık ve yaşam kalitesi sunma vizyonumuzla her geçen gün büyümeye devam ediyoruz.
                </p>
            </div>
        </div>
    </section>

    <section id="istatistikler" class="stats-banner">
        <div class="container">
            <div class="stats-grid" id="counter-section">
                <div class="stat-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div class="stat-number" data-target="<?php echo getSetting('stat_experience', $settings, '40'); ?>">0</div>
                    <div class="stat-text">Yıllık Tecrübe</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-flask"></i>
                    <div class="stat-number" data-target="<?php echo getSetting('stat_ph_value', $settings, '8.2'); ?>">0</div>
                    <div class="stat-text">pH Değeri</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-store"></i>
                    <div class="stat-number" data-target="<?php echo getSetting('stat_dealers', $settings, '1250'); ?>">0</div>
                    <div class="stat-text">Aktif Bayi</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-leaf"></i>
                    <div class="stat-number" data-target="<?php echo getSetting('stat_natural', $settings, '100'); ?>">0</div>
                    <div class="stat-text">% Doğal Kaynak</div>
                </div>
            </div>
        </div>
    </section>

    <section class="corporate-details">
        <div class="container">
            
            <div id="kaynak" class="zigzag-row">
                <div class="zigzag-img reveal-left">
                    <img src="uploads/img/karacapinar-su-damla.jpg" alt="Karacapınar Doğal Kaynak">
                </div>
                <div class="zigzag-text reveal-bottom delay-100">
                    <span class="brand-subtitle">KAYNAĞIMIZ</span>
                    <h3>Torosların Zirvesinden Gelen Saf Lezzet</h3>
                    <p>Karacapınar Doğal Kaynak Suyu, yerleşim birimlerinden ve endüstriyel alanlardan kilometrelerce uzakta, doğanın kalbinde yer alan yeraltı su kaynaklarından beslenir. Yüzeye çıkana kadar farklı kayaç katmanlarından süzülerek zenginleşen suyumuz, benzersiz bir mineral dengesine ulaşır.</p>
                    <p>8.2 pH seviyesindeki alkali yapısı, vücudun doğal asit-baz dengesini korumaya yardımcı olurken; zengin magnezyum ve kalsiyum içeriğiyle günlük sağlık ritüelinizin vazgeçilmez bir parçasıdır.</p>
                </div>
            </div>

            <div id="uretim" class="zigzag-row reverse">
                <div class="zigzag-img reveal-left delay-100">
                    <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=800&auto=format&fit=crop" alt="Üretim ve Hijyen">
                </div>
                <div class="zigzag-text reveal-bottom">
                    <span class="brand-subtitle">ÜRETİM VE HİJYEN</span>
                    <h3>Sıfır İnsan Eli, Üstün Teknoloji</h3>
                    <p>Sağlığınız bizim için her şeyden önemli. Bu yüzden kaynağındaki saflığı evinize taşıyana kadar araya hiçbir şeyin girmesine izin vermiyoruz.</p>
                    <p>Avrupa standartlarındaki tam otomasyonlu üretim tesislerimizde sularımız, boru hatlarından dolum ünitelerine kadar dış ortam havasıyla temas etmeden, el değmeden şişelenmektedir. Günlük olarak laboratuvarlarımızda gerçekleştirilen mikrobiyolojik ve kimyasal analizlerle kalitemizi sürekli garanti altında tutuyoruz.</p>
                </div>
            </div>

        </div>
    </section>

<?php include 'includes/footer.php'; ?>