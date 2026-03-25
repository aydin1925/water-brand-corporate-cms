$(document).ready(function(){

    /* ==========================================
       1. PRELOADER (Tarayıcı Hafızası ile Tek Seferlik)
       ========================================== */
    if (!sessionStorage.getItem('preloaderShown')) {
        // Kullanıcı siteye oturumda ilk kez giriyor: 1.5 sn göster
        setTimeout(function(){ 
            $("#preloader").fadeOut(800); 
        }, 1500);
        // Hafızaya "gösterildi" notunu düş
        sessionStorage.setItem('preloaderShown', 'true');
    } else {
        // Kullanıcı alt sayfalarda geziyor: Beklemeden anında yok et
        $("#preloader").hide();
    }

    /* ==========================================
       2. SLICK CAROUSEL (Hero Slider)
       ========================================== */
    $('.hero-slider').slick({
        slidesToShow: 1, 
        dots: true, 
        arrows: false,
        autoplay: true, 
        fade: true, 
        speed: 1200, 
        pauseOnHover: false
    });

    /* ==========================================
       3. SCROLL REVEAL (Animasyonlar)
       ========================================== */
    ScrollReveal().reveal('.reveal-bottom', { 
        distance: '40px', 
        origin: 'bottom', 
        duration: 1000, 
        interval: 150 
    });
    ScrollReveal().reveal('.reveal-left', { 
        distance: '60px', 
        origin: 'left', 
        duration: 1000 
    });

    /* ==========================================
       4. HEADER SCROLL KONTROLÜ
       ========================================== */
    $(window).scroll(function(){
        if ($(this).scrollTop() > 50) {
            $('#main-header').css('padding', '10px 0').css('background', 'rgba(255,255,255,0.98)');
            $('#main-header .logo img').css('height', '65px'); 
        } else {
            $('#main-header').css('padding', '20px 0').css('background', 'rgba(255,255,255,0.95)');
            $('#main-header .logo img').css('height', '85px'); 
        }
    });

    /* ==========================================
       5. MOBİL HAMBURGER VE AÇILIR MENÜ (DROPDOWN)
       ========================================== */
    $('#hamburger').click(function(){
        $('#nav-menu').toggleClass('active');
        $(this).toggleClass('open');
    });

    // Mobilde alt menüleri (dropdown) tıklayarak açma
    if (window.innerWidth <= 768) {
        $('.dropdown > a').click(function(e) {
            e.preventDefault(); // Sayfaya gitmeyi geçici olarak durdur, menüyü aç
            $(this).parent('.dropdown').toggleClass('mobile-open');
        });
        
        // Dropdown içindeki bir linke tıklandığında menüyü kapat ve o linke git
        $('.dropdown-menu a').click(function(e) {
            const link = $(this).attr('href');
            // Eğer sayfa içi bir link ise (href="#..." gibi), mobilde menüyü kapatarak o bölüme git
            if(link.includes('#')) {
                $('#nav-menu').removeClass('active');
                $('#hamburger').removeClass('open');
                // Linkin normal şekilde çalışması için e.preventDefault() YAPMIYORUZ
            }
        });
    }

    /* ==========================================
       6. ÜRÜNLER CAROUSEL (Yatay Kaydırma)
       ========================================== */
    const track = document.getElementById('product-track');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const scrollAmount = 330; 

    if (btnNext && btnPrev && track) {
        btnNext.addEventListener('click', () => { 
            track.scrollBy({ left: scrollAmount, behavior: 'smooth' }); 
        });
        btnPrev.addEventListener('click', () => { 
            track.scrollBy({ left: -scrollAmount, behavior: 'smooth' }); 
        });
    }

    /* ==========================================
       7. HİLAL ŞEKLİNDE GERÇEKÇİ PATLAMA ANİMASYONU (Mobilde Scroll, PC'de Click)
       ========================================== */
    const bottleWrapper = document.getElementById('mineral-bottle');
    const cap = document.getElementById('bottle-cap');
    const clickText = document.getElementById('clickText');
    const mineralCards = document.querySelectorAll('.mineral-burst-card');
    
    let isOpened = false;

    if (bottleWrapper && mineralCards.length > 0) {
        
        // Animasyonu tetikleyen ana fonksiyon
        const triggerExplosion = () => {
            if (isOpened) return; 
            isOpened = true;

            if(cap) cap.classList.add('fly');
            if(clickText) clickText.style.display = 'none';

            bottleWrapper.animate([
                { transform: 'translateY(0px)' },
                { transform: 'translateY(10px)' },
                { transform: 'translateY(0px)' }
            ], { duration: 300, easing: 'ease-out' });

            const container = document.querySelector('.pop-container');
            const containerRect = container.getBoundingClientRect();
            const bottleRect = bottleWrapper.getBoundingClientRect();
            
            const startX = (bottleRect.left - containerRect.left) + (bottleRect.width / 2);
            const startY = (bottleRect.top - containerRect.top) + 20; 

            const radiusX = $(window).width() > 768 ? 220 : 120; 
            const radiusY = $(window).width() > 768 ? 140 : 90;  
            const startAngle = Math.PI * 1.05; 
            const endAngle = Math.PI * 1.95;   

            // Fışkıran küçük damlalar
            for(let i = 0; i < 25; i++) {
                const miniDrop = document.createElement('div');
                miniDrop.className = 'mini-drop';
                
                const size = Math.random() * 8 + 4;
                miniDrop.style.width = size + 'px';
                miniDrop.style.height = size + 'px';
                
                container.appendChild(miniDrop);
                
                miniDrop.style.left = (startX - size/2) + 'px';
                miniDrop.style.top = (startY - size/2) + 'px';
                
                const sprayAngle = Math.PI * 1.2 + (Math.random() * Math.PI * 0.6); 
                const sprayRadius = Math.random() * 150 + 60; 
                
                const destX = startX + Math.cos(sprayAngle) * sprayRadius - (size/2);
                const destY = startY + Math.sin(sprayAngle) * sprayRadius - (size/2);
                
                const deltaX = startX - destX - (size/2);
                const deltaY = startY - destY;
                
                const anim = miniDrop.animate([
                    { transform: `translate(${deltaX}px, ${deltaY + 20}px) scale(0.5)`, opacity: 1 },
                    { transform: `translate(0px, -20px) scale(1)`, opacity: 1, offset: 0.7 },
                    { transform: `translate(0px, 40px) scale(0)`, opacity: 0 } 
                ], {
                    duration: 500 + Math.random() * 500, 
                    easing: 'cubic-bezier(0.2, 0.9, 0.3, 1.1)', 
                    fill: 'forwards'
                });
                
                anim.onfinish = () => { miniDrop.remove(); };
            }

            // Ana mineral kartları
            mineralCards.forEach((card, index) => {
                const dropColor = card.getAttribute('data-color');
                if(dropColor) card.style.backgroundColor = dropColor;

                const angle = startAngle + (endAngle - startAngle) * (index / (mineralCards.length - 1));
                const destX = startX + Math.cos(angle) * radiusX - (card.offsetWidth / 2);
                const destY = startY + Math.sin(angle) * radiusY - (card.offsetHeight / 2);

                card.style.left = destX + 'px';
                card.style.top = destY + 'px';
                card.style.opacity = 1;

                const deltaX = startX - destX - (card.offsetWidth / 2);
                const deltaY = startY - destY;

                const anim = card.animate([
                    { transform: `translate(${deltaX}px, ${deltaY + 50}px) scale(0)`, opacity: 0 },
                    { transform: `translate(0px, -15px) scale(1.1)`, opacity: 1, offset: 0.7 },
                    { transform: `translate(0px, 0px) scale(1)`, opacity: 1 }
                ], {
                    duration: 800 + (index * 150), 
                    easing: 'cubic-bezier(0.2, 0.9, 0.3, 1.2)', 
                    fill: 'forwards'
                });

                anim.onfinish = () => {
                    card.classList.add('floating-drop');
                    card.style.animationDelay = `${index * 0.3}s`; 
                };
            });
        };

        // Masaüstü için tıklama olayı
        bottleWrapper.addEventListener('click', triggerExplosion);

        // Mobil için scroll (kaydırma) olayı
        if (window.innerWidth <= 768) {
            if(clickText) clickText.style.display = 'none'; // Mobilde tıklama yazısını gizle
            
            const observer = new IntersectionObserver((entries) => {
                // Şişe ekrana girdiği an tetikle
                if (entries[0].isIntersecting) {
                    triggerExplosion();
                    observer.disconnect(); // Animasyon bir kez çalışınca takibi bırak
                }
            }, { threshold: 0.6 }); // Şişenin %60'ı göründüğünde patlat

            observer.observe(bottleWrapper);
        }
    }

    /* ==========================================
       8. SAYILARI SAYDIRMA ANİMASYONU
       ========================================== */
    const counterSection = document.getElementById('counter-section');
    const counters = document.querySelectorAll('.stat-number');
    let counted = false;

    if(counterSection && counters.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            if(entries[0].isIntersecting && !counted) {
                counted = true;
                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target');
                        const count = +counter.innerText;
                        const speed = 200; 
                        const inc = target / speed;

                        if(count < target) {
                            counter.innerText = Math.ceil(count + inc);
                            setTimeout(updateCount, 10);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    updateCount();
                });
            }
        }, { threshold: 0.5 });

        observer.observe(counterSection);
    }

    /* ==========================================
       9. ÜRÜN FİLTRELEME (Ürünler Sayfası)
       ========================================== */
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.detailed-product-card');

    if(filterBtns.length > 0 && productCards.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                
                // Aktif buton stilini değiştir
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filterValue = btn.getAttribute('data-filter');

                productCards.forEach(card => {
                    
                    // ScrollReveal ile çakışmaması için reveal sınıfını kaldır
                    card.classList.remove('reveal-bottom'); 

                    if(filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                        card.classList.remove('hidden-card');
                        // Geri gelme animasyonu
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 10);
                    } else {
                        // Küçülerek kaybolma efekti
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            card.classList.add('hidden-card');
                        }, 400); // CSS transition süresi kadar bekle
                    }
                });
            });
        });
    }

});