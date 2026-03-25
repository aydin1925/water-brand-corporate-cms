<?php include 'includes/header.php'; ?>

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
                            <h6 class="cg-title">Genel Merkez</h6>
                            <p class="cg-desc">Organize Sanayi Bölgesi, Merkez<br>Türkiye</p>
                        </div>
                    </div>
                    
                    <div class="contact-glass-card">
                        <div class="icon-glow-box">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h6 class="cg-title">Müşteri Hizmetleri</h6>
                            <p class="cg-desc">
                                <a href="tel:4440000" class="cg-link">444 0 000</a><br>Hafta içi: 09:00 - 18:00
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
                                <a href="mailto:bilgi@karacapinar.com.tr" class="cg-link">bilgi@karacapinar.com.tr</a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-social-panel text-center">
                        <h6 class="cg-title" style="font-size: 11px; letter-spacing: 2px;">BİZİ TAKİP EDİN</h6>
                        <div class="social-links-glass">
                            <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="contact-form-column reveal-bottom delay-200">
                <div class="glass-form-panel">
                    
                    <div class="form-header">
                        <div>
                            <h3 class="fh-title">İletişim & Talep Formu</h3>
                            <p class="fh-desc">Talebinizi iletin, en kısa sürede dönüş yapalım.</p>
                        </div>
                        <div class="fh-icon"><i class="fas fa-paper-plane"></i></div>
                    </div>
                    
                    <form id="karacapinarContactForm">
                        
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
                                    <option value="" selected disabled>İletişim Nedeni *</option>
                                    <option value="Su Siparişi">Ev / Ofis Su Siparişi</option>
                                    <option value="Bayilik Başvurusu">Bayilik Başvurusu</option>
                                    <option value="Kurumsal Tedarik">Kurumsal Tedarik (Toptan)</option>
                                    <option value="Şikayet ve Öneri">Şikayet ve Öneri</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                            <div class="fg-full">
                                <textarea name="message" class="glass-input" rows="3" placeholder="Mesajınız..." required></textarea>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="kvkk-check">
                                <input type="checkbox" id="kvkkCheck" required>
                                <label for="kvkkCheck"><a href="#">KVKK Aydınlatma Metni</a>'ni okudum ve onaylıyorum.</label>
                            </div>
                            <button type="submit" class="premium-submit-btn">
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
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3184.288775088234!2d37.31885061529683!3d37.05437817989716!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1531e13a5a73e51d%3A0x8e87d853e028db2d!2sGaziantep!5e0!3m2!1str!2str!4v1620000000000!5m2!1str!2str" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>