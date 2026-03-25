<?php include 'includes/header.php'; ?>

    <div class="inner-hero contact-hero" style="background-image: url('https://images.unsplash.com/photo-1505236732171-72a5b19c4981?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
        <div class="contact-hero-overlay"></div>
        <div class="container position-relative z-2">
            <div class="contact-hero-content reveal-bottom">
                <div class="brand-subtitle" style="color: #fff; opacity: 0.8; margin-bottom: 10px;"><span style="display:inline-block; width:30px; height:2px; background:#fff; vertical-align:middle; margin-right:10px;"></span> İŞ ORTAĞIMIZ OLUN</div>
                <h1 class="display-title">Büyük Ailemize<br><span style="color: var(--brand-blue);">Katılın.</span></h1>
                <p class="lead-desc">Kârlı bir yatırım, güçlü bir marka ve kesintisiz destek ile kendi işinizin patronu olun. Karacapınar güvencesiyle kazanmaya hemen başlayın.</p>
            </div>
        </div>
    </div>

    <main class="container position-relative z-5 contact-main-wrapper">
        <div class="partner-grid">
            
            <div class="partner-info-column reveal-left delay-100">
                
                <div class="info-glass-panel">
                    <h3 class="partner-section-title">Neden Karacapınar?</h3>
                    <div class="partner-benefits-grid">
                        <div class="p-benefit-card">
                            <div class="pb-icon"><i class="fas fa-chart-pie"></i></div>
                            <div>
                                <h4>Yüksek Kâr Marjı</h4>
                                <p>Sürdürülebilir kazanç modeli ile hızlı yatırım dönüşü.</p>
                            </div>
                        </div>
                        <div class="p-benefit-card">
                            <div class="pb-icon"><i class="fas fa-bullhorn"></i></div>
                            <div>
                                <h4>Pazarlama Desteği</h4>
                                <p>Ulusal ve yerel reklamlarda markanın gücünü arkanıza alın.</p>
                            </div>
                        </div>
                        <div class="p-benefit-card">
                            <div class="pb-icon"><i class="fas fa-truck-loading"></i></div>
                            <div>
                                <h4>Güçlü Lojistik</h4>
                                <p>Geniş araç filosu ile kesintisiz ve zamanında teslimat.</p>
                            </div>
                        </div>
                        <div class="p-benefit-card">
                            <div class="pb-icon"><i class="fas fa-map-marked-alt"></i></div>
                            <div>
                                <h4>Bölge Koruması</h4>
                                <p>Size özel tanımlanan bölgede tek yetkili olma garantisi.</p>
                            </div>
                        </div>
                    </div>

                    <h3 class="partner-section-title" style="margin-top: 20px;">Başvuru Süreci</h3>
                    <div class="partner-timeline">
                        <div class="timeline-step">
                            <div class="ts-number">1</div>
                            <div class="ts-content">
                                <h5>Ön Başvuru</h5>
                                <p>Yandaki formu doldurarak ilk adımı atın.</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="ts-number">2</div>
                            <div class="ts-content">
                                <h5>Değerlendirme</h5>
                                <p>Bölge ve fizibilite uzmanlarımızca incelenir.</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="ts-number">3</div>
                            <div class="ts-content">
                                <h5>Karşılıklı Görüşme</h5>
                                <p>Detayları konuşmak üzere genel merkezimizde ağırlanır.</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="ts-number">4</div>
                            <div class="ts-content">
                                <h5>Sözleşme ve Kurulum</h5>
                                <p>Sözleşme imzalanır, bayilik operasyonu başlatılır.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="partner-form-column reveal-bottom delay-200">
                <div class="glass-form-panel sticky-form">
                    
                    <div class="form-header">
                        <div>
                            <h3 class="fh-title">Bayilik Ön Başvuru Formu</h3>
                            <p class="fh-desc">Lütfen bilgileri eksiksiz doldurunuz.</p>
                        </div>
                        <div class="fh-icon"><i class="fas fa-handshake"></i></div>
                    </div>
                    
                    <form id="karacapinarPartnerForm">
                        
                        <div class="form-group-label"><i class="fas fa-user-tie"></i> KİŞİSEL BİLGİLER</div>
                        <div class="form-grid mb-4">
                            <div class="fg-full"><input type="text" name="full_name" class="glass-input" placeholder="Adınız Soyadınız *" required></div>
                            <div class="fg-half"><input type="tel" name="phone" class="glass-input" placeholder="Telefon Numaranız *" required></div>
                            <div class="fg-half"><input type="email" name="email" class="glass-input" placeholder="E-Posta Adresiniz"></div>
                        </div>

                        <div class="form-group-label" style="color: var(--brand-main);"><i class="fas fa-store"></i> YATIRIM BİLGİLER</div>
                        <div class="form-grid mb-4">
                            <div class="fg-half"><input type="text" name="city" class="glass-input" placeholder="Düşünülen İl *" required></div>
                            <div class="fg-half"><input type="text" name="district" class="glass-input" placeholder="Düşünülen İlçe *" required></div>
                            <div class="fg-full">
                                <select name="budget" class="glass-input custom-select" required>
                                    <option value="" selected disabled>Planlanan Yatırım Bütçesi *</option>
                                    <option value="1M - 3M">1.000.000 ₺ - 3.000.000 ₺</option>
                                    <option value="3M - 5M">3.000.000 ₺ - 5.000.000 ₺</option>
                                    <option value="5M+">5.000.000 ₺ ve üzeri</option>
                                </select>
                            </div>
                            <div class="fg-full">
                                <select name="experience" class="glass-input custom-select" required>
                                    <option value="" selected disabled>Damacana Su Sektöründe Tecrübeniz Var Mı? *</option>
                                    <option value="Evet">Evet, tecrübem var.</option>
                                    <option value="Hayır">Hayır, yeni yatırımcıyım.</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group-label" style="color: #64748b;"><i class="fas fa-comment-dots"></i> EK BİLGİLER</div>
                        <div class="form-grid">
                            <div class="fg-full">
                                <textarea name="message" class="glass-input" rows="3" placeholder="Kendinizden ve hedeflerinizden kısaca bahsedin..."></textarea>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="kvkk-check">
                                <input type="checkbox" id="kvkkCheckPartner" required>
                                <label for="kvkkCheckPartner"><a href="#">KVKK Aydınlatma Metni</a>'ni okudum ve onaylıyorum.</label>
                            </div>
                            <button type="submit" class="premium-submit-btn">
                                Başvuruyu Tamamla <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

<?php include 'includes/footer.php'; ?>