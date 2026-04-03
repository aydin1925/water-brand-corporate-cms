<?php 
// Veritabanı bağlantısı
require_once 'config/db.php';
$database = new Database();
$db = $database->connect();

// 1. GENEL AYARLARI ÇEK (Telefon numarası vb. için)
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

function getSetting($key, $array, $default = '') {
    return (isset($array[$key]) && $array[$key] !== '') ? htmlspecialchars($array[$key]) : $default;
}

// 2. AKTİF BAYİLERİ ÇEK
$dealers = [];
try {
    // Bayileri şehre ve ilçeye göre alfabetik sıralayarak çekiyoruz
    $dealerStmt = $db->prepare("SELECT * FROM dealers WHERE status = 'aktif' ORDER BY city ASC, district ASC");
    $dealerStmt->execute();
    $dealers = $dealerStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Hata durumunda boş kalsın
}

include 'includes/header.php'; 
?>

    <div class="modern-page-header">
        <div class="container">
            <span class="subtitle reveal-bottom">Geniş Dağıtım Ağımız</span>
            <h1 class="reveal-bottom delay-100">Yetkili Bayilerimiz</h1>
            <div class="header-line reveal-bottom delay-200"></div>
        </div>
    </div>

    <section class="dealers-page-section" style="padding: 40px 0 100px; background: var(--light-gray);">
        <div class="container">
            
            <div class="section-header reveal-bottom" style="margin-bottom: 40px;">
                <p style="color:#64748b; font-size: 16px;">Karacapınar kalitesine en hızlı şekilde ulaşabileceğiniz, size en yakın yetkili satış noktalarımız.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">

                <?php 
                if(count($dealers) > 0) {
                    foreach($dealers as $index => $dealer) { 
                        // Animasyon gecikmesi
                        $delay = ($index % 3) * 100;
                ?>
                <div class="reveal-bottom <?php echo $delay > 0 ? 'delay-'.$delay : ''; ?>" style="background: var(--white); border-radius: 20px; padding: 35px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(28, 79, 140, 0.05); transition: 0.4s; position: relative;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(28, 79, 140, 0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.03)';">
                    
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div style="width: 55px; height: 55px; background: rgba(0, 168, 255, 0.1); color: var(--brand-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 18px; font-weight: 800; color: var(--brand-dark); margin: 0 0 5px 0;"><?php echo htmlspecialchars($dealer['name']); ?></h3>
                            <span style="display: inline-block; background: rgba(28, 79, 140, 0.05); color: var(--brand-main); padding: 4px 10px; border-radius: 30px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
                                <?php echo htmlspecialchars($dealer['city'] . ' / ' . $dealer['district']); ?>
                            </span>
                        </div>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <p style="font-size: 14px; color: #64748b; margin-bottom: 10px; line-height: 1.6; display: flex; align-items: flex-start; gap: 10px;">
                            <i class="fas fa-map-marker-alt" style="color: var(--brand-main); margin-top: 4px;"></i> 
                            <span><?php echo htmlspecialchars($dealer['address']); ?></span>
                        </p>
                    </div>

                    <a href="tel:<?php echo str_replace(' ', '', $dealer['phone']); ?>" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; background: var(--light-gray); color: var(--brand-dark); padding: 14px 0; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.3s;" onmouseover="this.style.background='var(--brand-main)'; this.style.color='var(--white)';" onmouseout="this.style.background='var(--light-gray)'; this.style.color='var(--brand-dark)';">
                        <i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($dealer['phone']); ?>
                    </a>

                </div>
                <?php 
                    } 
                } else {
                ?>
                    <div style="grid-column: 1 / -1; background: var(--white); border-radius: 20px; padding: 60px 20px; text-align: center; border: 1px dashed rgba(28, 79, 140, 0.2);">
                        <i class="fas fa-map-marked-alt fa-3x" style="color: var(--brand-blue); opacity: 0.5; margin-bottom: 20px;"></i>
                        <h4 style="color: var(--brand-dark); font-weight: 700; margin-bottom: 10px;">Bayi Listesi Güncelleniyor</h4>
                        <p style="color: #64748b; font-size: 15px;">Şu an için sistemde kayıtlı aktif bir bayimiz bulunmamaktadır.</p>
                    </div>
                <?php } ?>

            </div>
        </div>
    </section>

    <section class="partner-cta-section" style="padding: 40px 0 100px; background: var(--light-gray);">
        <div class="container">
            <div class="reveal-bottom" style="background: var(--white); border-radius: 25px; padding: 50px 30px; text-align: center; box-shadow: 0 15px 35px rgba(0,0,0,0.03); border: 1px solid rgba(28, 79, 140, 0.05);">

                <div style="width: 70px; height: 70px; background: rgba(0, 168, 255, 0.1); color: var(--brand-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;">
                    <i class="fas fa-handshake"></i>
                </div>
                <h2 style="font-size: 32px; font-weight: 800; color: var(--brand-dark); margin-bottom: 15px;">Siz de <span style="color: var(--brand-main);">Ailemize Katılın</span></h2>
                <p style="color: #64748b; font-size: 16px; margin-bottom: 35px; max-width: 650px; margin-left: auto; margin-right: auto; line-height: 1.8;">Kendi işinizin patronu olmak ve Karacapınar güvencesiyle kazançlı bir yatırıma imza atmak için bayilik başvurunuzu hemen yapın.</p>

                <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    
                    <a href="iletisim.php#basvuru" style="display: inline-flex; align-items: center; gap: 10px; background: var(--brand-main); color: var(--white); padding: 14px 40px; border-radius: 50px; font-weight: 700; font-size: 15px; box-shadow: 0 10px 20px rgba(28, 79, 140, 0.2); transition: 0.3s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 25px rgba(28, 79, 140, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(28, 79, 140, 0.2)';">
                        <i class="fas fa-file-signature"></i> Bayilik Başvuru Formu
                    </a>

                    <a href="tel:<?php echo str_replace(' ', '', getSetting('phone', $settings, '444 0 000')); ?>" style="display: inline-flex; align-items: center; gap: 10px; background: var(--white); color: var(--brand-main); border: 2px solid var(--brand-main); padding: 12px 40px; border-radius: 50px; font-weight: 700; font-size: 15px; transition: 0.3s;" onmouseover="this.style.background='var(--brand-main)'; this.style.color='var(--white)';" onmouseout="this.style.background='var(--white)'; this.style.color='var(--brand-main)';">
                        <i class="fas fa-headset"></i> Bilgi Al
                    </a>

                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>