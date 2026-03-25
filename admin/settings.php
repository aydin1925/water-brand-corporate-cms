<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genel Ayarlar | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">SİSTEM YAPILANDIRMASI</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Genel Ayarlar</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Web sitenizin iletişim, sosyal medya, SEO ve temel bilgilerini güncelleyin.</p>
            </div>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row g-4">
                
                <div class="col-lg-8">
                    
                    <div class="glass-panel p-4 mb-4">
                        <h4 class="settings-section-title"><i class="fas fa-globe"></i> Temel Site Bilgileri</h4>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="custom-form-label">Site Başlığı (Title)</label>
                                <input type="text" name="site_title" class="form-control custom-form-control" value="Karacapınar Doğal Kaynak Suyu" placeholder="Örn: Karacapınar Su">
                            </div>
                            <div class="col-md-6">
                                <label class="custom-form-label">Footer Telif Metni (Copyright)</label>
                                <input type="text" name="footer_text" class="form-control custom-form-control" value="© 2024 Karacapınar Su. Tüm Hakları Saklıdır." placeholder="Örn: © 2024 Karacapınar">
                            </div>
                            <div class="col-12">
                                <label class="custom-form-label">Site Açıklaması (Meta Description - SEO İçin)</label>
                                <textarea name="site_description" class="form-control custom-form-control" rows="3" placeholder="Arama motorlarında (Google) sitenizin altında çıkacak açıklama metni...">Torosların zirvesinden gelen doğal mineral dengesine sahip Karacapınar Kaynak Suyu...</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel p-4 mb-4">
                        <h4 class="settings-section-title"><i class="fas fa-address-book"></i> İletişim & Konum Bilgileri</h4>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="custom-form-label">Telefon Numarası</label>
                                <input type="text" name="phone" class="form-control custom-form-control" value="0850 123 45 67" placeholder="Örn: 0850 123 45 67">
                            </div>
                            <div class="col-md-6">
                                <label class="custom-form-label">E-Posta Adresi</label>
                                <input type="email" name="email" class="form-control custom-form-control" value="info@karacapinar.com.tr" placeholder="Örn: info@domain.com">
                            </div>
                            <div class="col-12">
                                <label class="custom-form-label">Açık Adres (Merkez / Fabrika)</label>
                                <textarea name="address" class="form-control custom-form-control" rows="2" placeholder="Açık adresinizi giriniz...">Toroslar Mevkii, Su Kaynağı Yolu No:1, Pozantı / Ankara</textarea>
                            </div>
                            <div class="col-12">
                                <label class="custom-form-label">Google Haritalar İframe Kodu (İletişim Sayfası İçin)</label>
                                <textarea name="map_iframe" class="form-control custom-form-control" rows="3" placeholder='<iframe src="..."></iframe>'><iframe src="https://www.google.com/maps/embed?pb=..." width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">
                    
                    <div class="glass-panel p-4 mb-4">
                        <h4 class="settings-section-title"><i class="fas fa-image"></i> Kurumsal Görseller</h4>
                        
                        <div class="mb-4">
                            <label class="custom-form-label">Mevcut Ana Logo</label>
                            <div class="p-3 bg-light rounded-3 text-center border mb-2">
                                <img src="../uploads/img/karacapınar-logo.png" alt="Logo" style="max-height: 60px;">
                            </div>
                            <input type="file" name="site_logo" class="form-control custom-form-control" accept="image/*">
                            <small class="text-secondary mt-1 d-block" style="font-size: 11px;">PNG veya SVG (Şeffaf arka plan) önerilir.</small>
                        </div>

                        <div>
                            <label class="custom-form-label">Mevcut Favicon (Sekme İkonu)</label>
                            <div class="p-2 bg-light rounded-3 text-center border mb-2 d-inline-block">
                                <i class="fas fa-tint text-brand-blue fs-4"></i>
                            </div>
                            <input type="file" name="site_favicon" class="form-control custom-form-control" accept="image/*">
                            <small class="text-secondary mt-1 d-block" style="font-size: 11px;">16x16 veya 32x32 boyutlarında .ico veya .png</small>
                        </div>
                    </div>

                    <div class="glass-panel p-4">
                        <h4 class="settings-section-title"><i class="fas fa-hashtag"></i> Sosyal Medya Bağlantıları</h4>
                        
                        <div class="mb-3">
                            <label class="custom-form-label"><i class="fab fa-instagram me-1"></i> Instagram URL</label>
                            <input type="url" name="instagram_url" class="form-control custom-form-control" value="https://instagram.com/karacapinarsu" placeholder="https://instagram.com/...">
                        </div>
                        <div class="mb-3">
                            <label class="custom-form-label"><i class="fab fa-facebook me-1"></i> Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control custom-form-control" value="https://facebook.com/karacapinarsu" placeholder="https://facebook.com/...">
                        </div>
                        <div class="mb-3">
                            <label class="custom-form-label"><i class="fab fa-twitter me-1"></i> Twitter (X) URL</label>
                            <input type="url" name="twitter_url" class="form-control custom-form-control" placeholder="https://twitter.com/...">
                        </div>
                        <div class="mb-3">
                            <label class="custom-form-label"><i class="fab fa-linkedin me-1"></i> LinkedIn URL</label>
                            <input type="url" name="linkedin_url" class="form-control custom-form-control" placeholder="https://linkedin.com/...">
                        </div>
                    </div>

                </div>
            </div>

            <div class="save-action-bar position-sticky" style="bottom: 20px; z-index: 100;">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-secondary fw-medium small d-none d-sm-inline">Tüm değişiklikleri kontrol ettiğinizden emin olun.</span>
                    <button type="submit" class="btn btn-add-new fw-bold px-5 py-2 rounded-pill shadow-lg text-uppercase" style="font-size: 14px; letter-spacing: 1px;">
                        <i class="fas fa-save me-2"></i> Ayarları Kaydet
                    </button>
                </div>
            </div>
            
        </form>

    </div>

</body>
</html>