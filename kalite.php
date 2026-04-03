<?php 
// Veritabanı bağlantısı
require_once 'config/db.php';
$database = new Database();
$db = $database->connect();

// AKTİF BELGELERİ ÇEK
$certificates = [];
try {
    // Belgeleri en son eklenen en başta görünecek şekilde (id DESC) çekiyoruz
    $certStmt = $db->prepare("SELECT * FROM certificates WHERE status = 'aktif' ORDER BY id DESC");
    $certStmt->execute();
    $certificates = $certStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Hata durumunda boş kalsın
}

include 'includes/header.php'; 
?>

    <div class="modern-page-header">
        <div class="container">
            <span class="subtitle reveal-bottom">Güvencemiz</span>
            <h1 class="reveal-bottom delay-100">Kalite Belgelerimiz</h1>
            <div class="header-line reveal-bottom delay-200"></div>
        </div>
    </div>

    <section class="quality-section">
        <div class="container">
            
            <div class="quality-intro reveal-bottom">
                <p>Karacapınar Doğal Kaynak Suyu olarak, kaynaktan bardağınıza kadar geçen tüm süreçte uluslararası hijyen ve kalite standartlarını tavizsiz uyguluyoruz. Üstün teknolojiye sahip tesislerimizde insan sağlığına verdiğimiz değeri, ulusal ve uluslararası bağımsız kuruluşlardan aldığımız sertifikalarla belgelendiriyoruz.</p>
            </div>

            <div class="certificates-grid">
                
                <?php 
                if(count($certificates) > 0) {
                    foreach($certificates as $index => $cert) { 
                        
                        // Animasyon gecikmesi (0, 100, 200, 300 şeklinde 3'lü sıra için)
                        $delay = (($index % 3) + 1) * 100;
                        
                        // Belge başlığına göre otomatik ikon belirleme
                        $title_lower = strtolower($cert['title']);
                        $iconClass = "fa-award"; // Varsayılan ikon
                        
                        if(strpos($title_lower, 'iso') !== false) {
                            $iconClass = "fa-globe-europe";
                        } elseif (strpos($title_lower, 'tse') !== false) {
                            $iconClass = "fa-certificate";
                        } elseif (strpos($title_lower, 'helal') !== false) {
                            $iconClass = "fa-check-circle";
                        } elseif (strpos($title_lower, 'sağlık') !== false || strpos($title_lower, 'gıda') !== false) {
                            $iconClass = "fa-shield-alt";
                        } elseif (strpos($title_lower, 'çevre') !== false) {
                            $iconClass = "fa-leaf";
                        }
                ?>
                <div class="premium-cert-card reveal-bottom delay-<?php echo $delay; ?>">
                    <div class="cert-badge-3d">
                        <i class="fas <?php echo $iconClass; ?>"></i>
                    </div>
                    
                    <h3><?php echo htmlspecialchars($cert['title']); ?></h3>
                    
                    <p>
                        <?php 
                            if(!empty($cert['expiry_date'])) {
                                echo "Geçerlilik Tarihi: " . date('d.m.Y', strtotime($cert['expiry_date']));
                            } else {
                                echo "Süresiz Geçerlilik";
                            }
                        ?>
                    </p>
                    
                    <a href="<?php echo htmlspecialchars($cert['url']); ?>" target="_blank" class="cert-action-btn">
                        <i class="fas fa-search"></i> İncele
                    </a>
                </div>
                <?php 
                    } 
                } else {
                ?>
                    <div style="grid-column: 1 / -1; background: var(--white); border-radius: 20px; padding: 60px 20px; text-align: center; border: 1px dashed rgba(28, 79, 140, 0.2);">
                        <i class="fas fa-file-contract fa-3x" style="color: var(--brand-blue); opacity: 0.5; margin-bottom: 20px;"></i>
                        <h4 style="color: var(--brand-dark); font-weight: 700; margin-bottom: 10px;">Belgeler Güncelleniyor</h4>
                        <p style="color: #64748b; font-size: 15px;">Şu an için sistemde kayıtlı aktif bir sertifika bulunmamaktadır.</p>
                    </div>
                <?php } ?>

            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>