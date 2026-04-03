<?php 
// Veritabanı bağlantısı
require_once 'config/db.php';
$database = new Database();
$db = $database->connect();

$alertMessage = "";
$alertType = "";

// 1. FORM GÖNDERİLDİ Mİ? (LEADS TABLOSUNA KAYIT)
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
    
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $interest = $_POST['interest_area'];
    $content = trim($_POST['message']); // Tablodaki karşılığı 'content'
    $ip_address = $_SERVER['REMOTE_ADDR']; // Tablondaki ip_address sütunu için

    // Formdan gelen seçeneği, veritabanındaki (leads tablosundaki) category değerlerine eşliyoruz
    $category = 'iletisim'; // Varsayılan
    if($interest == 'Su Siparişi' || $interest == 'Kurumsal Tedarik') {
        $category = 'damacana'; 
    } elseif($interest == 'Bayilik Başvurusu') {
        $category = 'bayilik';
    }

    try {
        // Tablo yapına birebir uygun INSERT sorgusu
        $insert = $db->prepare("INSERT INTO leads (full_name, phone, email, category, content, status, ip_address) VALUES (:full_name, :phone, :email, :category, :content, 'yeni', :ip_address)");
        
        $insert->execute([
            ':full_name' => $full_name,
            ':phone' => $phone,
            ':email' => $email,
            ':category' => $category,
            ':content' => $content,
            ':ip_address' => $ip_address
        ]);
        
        $alertMessage = "Talebiniz başarıyla alınmıştır. İlgili birimimiz en kısa sürede sizinle iletişime geçecektir.";
        $alertType = "success";
        
    } catch(PDOException $e) {
        $alertMessage = "Sistem hatası oluştu. Lütfen bilgilerinizi kontrol edip tekrar deneyin.";
        $alertType = "error";
    }
}

// 2. GENEL AYARLARI ÇEK (Adres, Telefon, Sosyal Medya, Harita için)
$settings = [];
try {
    $stmt = $db->prepare("SELECT setting_key, setting_value FROM settings");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch(PDOException $e) {
    // Sessizce geç
}

// Ayar çekme fonksiyonu
function getSetting($key, $array, $default = '') {
    return (isset($array[$key]) && $array[$key] !== '') ? $array[$key] : $default;
}

// Ürünler sayfasından sipariş butonuna tıklanarak gelindiyse kontrol et
$isProductOrder = isset($_GET['urun']) ? true : false;

include 'includes/header.php'; 
?>

    <div class="inner-hero contact-hero" style="background-image: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1920&auto=format&fit=crop');">
        <div class="contact-hero-overlay"></div>
        <div class="container position-relative z-2">
            <div class="contact-hero-content reveal-bottom">
                <div class="brand-subtitle" style="color: #fff; opacity: 0.8; margin-bottom: 10px;"><span style="display:inline-block; width:30px; height:2px; background:#fff; vertical-align:middle; margin-right:10px;"></span> BİZE ULAŞIN</div>
                <h1 class="display-title">Doğallığa Bir Adım<br><span style="color: var(--brand-blue);">Daha Yakın.</span></h1>
                <p class="lead-desc">Siparişleriniz, bayilik başvurularınız ve her türlü görüşünüz için doğrudan uzman ekibimizle iletişime geçin.</p>
            </div>
        </div>
    </div>

    <main class="container position-relative z-5 contact-main-wrapper">
        <div class="contact-grid">
            
            <div class="contact-info-column reveal-left delay-100">
                <div class="d-flex-column gap-20">
                    
                    <div class="contact-glass-card">
                        <div class="icon-glow-box">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h6 class="cg-title">Genel Merkez & Fabrika</h6>
                            <p class="cg-desc">
                                <?php echo nl2br(htmlspecialchars(getSetting('address', $settings, 'Organize Sanayi Bölgesi, Merkez'))); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="contact-glass-card">
                        <div class="icon-glow-box">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h6 class="cg-title">Müşteri Hizmetleri</h6>
                            <p class="cg-desc">
                                <?php $tel = getSetting('phone', $settings, '444 0 000'); ?>
                                <a href="tel:<?php echo str_replace(' ', '', $tel); ?>" class="cg-link"><?php echo htmlspecialchars($tel); ?></a><br>
                                Hafta içi: 09:00 - 18:00
                            </p>
                        </div>
                    </div>
                    
                    <div class="contact-glass-card">
                        <div class="icon-glow-box">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="cg-title">Dijital İletişim</h6>
                            <p class="cg-desc">
                                <?php $email = getSetting('email', $settings, 'bilgi@karacapinar.com.tr'); ?>
                                <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="cg-link"><?php echo htmlspecialchars($email); ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-social-panel text-center">
                        <h6 class="cg-title" style="font-size: 11px; letter-spacing: 2px;">BİZİ TAKİP EDİN</h6>
                        <div class="social-links-glass">
                            <?php if(!empty($settings['facebook_url'])): ?>
                                <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            
                            <?php if(!empty($settings['twitter_url'])): ?>
                                <a href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            
                            <?php if(!empty($settings['instagram_url'])): ?>
                                <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            
                            <?php if(!empty($settings['linkedin_url'])): ?>
                                <a href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="contact-form-column reveal-bottom delay-200" id="basvuru">
                <div class="glass-form-panel">
                    
                    <div class="form-header">
                        <div>
                            <h3 class="fh-title">İletişim & Talep Formu</h3>
                            <p class="fh-desc">Talebinizi iletin, en kısa sürede dönüş yapalım.</p>
                        </div>
                        <div class="fh-icon"><i class="fas fa-paper-plane"></i></div>
                    </div>
                    
                    <form action="#basvuru" method="POST">
                        
                        <div class="form-group-label"><i class="fas fa-user-circle"></i> KİŞİSEL BİLGİLER</div>
                        <div class="form-grid">
                            <div class="fg-full"><input type="text" name="full_name" class="glass-input" placeholder="Adınız Soyadınız *" required></div>
                            <div class="fg-half"><input type="tel" name="phone" class="glass-input" placeholder="Telefon Numaranız *" required></div>
                            <div class="fg-half"><input type="email" name="email" class="glass-input" placeholder="E-Posta Adresiniz"></div>
                        </div>

                        <div class="form-group-label" style="color: var(--brand-main);"><i class="fas fa-briefcase"></i> TALEP DETAYI</div>
                        <div class="form-grid">
                            <div class="fg-full">
                                <select name="interest_area" class="glass-input custom-select" required>
                                    <option value="" <?php echo (!$isProductOrder) ? 'selected' : ''; ?> disabled>İletişim Nedeni *</option>
                                    
                                    <option value="Su Siparişi" <?php echo ($isProductOrder) ? 'selected' : ''; ?>>Ev / Ofis Su Siparişi</option>
                                    
                                    <option value="Bayilik Başvurusu">Bayilik Başvurusu</option>
                                    <option value="Kurumsal Tedarik">Kurumsal Tedarik (Toptan)</option>
                                    <option value="Şikayet ve Öneri">Şikayet ve Öneri</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                            <div class="fg-full">
                                <textarea name="message" class="glass-input" rows="3" placeholder="Lütfen açık adresinizi ve talebinizin detaylarını buraya yazın..." required></textarea>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="kvkk-check">
                                <input type="checkbox" id="kvkkCheck" required>
                                <label for="kvkkCheck"><a href="#">KVKK Aydınlatma Metni</a>'ni okudum ve onaylıyorum.</label>
                            </div>
                            <button type="submit" name="submit_contact" class="premium-submit-btn">
                                Gönder <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="map-wrapper reveal-bottom delay-300">
            <div class="map-overlay-card d-none d-md-block">
                <div class="moc-header">
                    <div class="moc-icon"><i class="fas fa-building"></i></div>
                    <h6>Karacapınar Su A.Ş.</h6>
                </div>
                <p><i class="fas fa-clock"></i> Pzt - Cmt: 08:00 - 19:00</p>
                <p><i class="fas fa-truck"></i> Hızlı Teslimat Bölgesi</p>
            </div>
            
            <div class="map-container">
                <?php 
                    $iframeCode = getSetting('map_iframe', $settings, '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12037.95455829676!2d28.988002650000005!3d41.03646695!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cab7650656bd63%3A0x8ca058b28c20b6c3!2zVGFrc2ltIE1leWRhbsSxLCDFnsOha3VsdSwgQmV5b8SfbHUvxLBzdGFuYnVs!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str" allowfullscreen="" loading="lazy"></iframe>'); 
                    echo $iframeCode;
                ?>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(!empty($alertMessage)): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: '<?php echo $alertType; ?>',
            title: '<?php echo ($alertType == "success") ? "Harika!" : "Hata!"; ?>',
            text: '<?php echo $alertMessage; ?>',
            confirmButtonColor: '<?php echo ($alertType == "success") ? "#00a8ff" : "#ef4444"; ?>',
            confirmButtonText: 'Tamam'
        });
    });
</script>
<?php endif; ?>