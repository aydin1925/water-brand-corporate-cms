<style>
    /* 1. ORTAK (GLOBAL) SİDEBAR DEĞİŞKENLERİ */
    :root {
        --sidebar-width: 260px;
        --sidebar-bg: #0f172a; /* Koyu Lacivert/Siyah tonu */
        --sidebar-active: #1e293b;
        --sidebar-accent: #00a8ff; /* Karacapınar Turkuazı */
        --brand-main-sidebar: #1C4F8C;
    }

    /* 2. MASAÜSTÜ (DESKTOP) GÖRÜNÜMÜ */
    .admin-sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--sidebar-bg);
        position: fixed;
        left: 0;
        top: 0;
        padding: 1.5rem;
        color: #fff;
        z-index: 1040;
        overflow-y: auto;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    /* Ana içeriği sidebar genişliği kadar sağa itme kuralı */
    .admin-main-wrapper {
        margin-left: var(--sidebar-width);
        padding: 2.5rem;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    /* 3. MENÜ LİNKLERİ */
    .nav-link-custom {
        display: flex;
        align-items: center;
        padding: 0.8rem 1rem;
        color: #94a3b8;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 0.2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .nav-link-custom:hover, .nav-link-custom.active {
        background: var(--sidebar-active);
        color: #fff;
    }

    .nav-link-custom.active {
        border-left: 4px solid var(--sidebar-accent);
    }

    .nav-link-custom.text-danger:hover {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545 !important;
    }

    /* 4. MOBİLE ÖZEL ELEMANLAR (Başlangıçta Gizli) */
    .mobile-toggle-btn {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1030;
        background: var(--brand-main-sidebar);
        color: white;
        border: none;
        border-radius: 8px;
        width: 45px;
        height: 45px;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        cursor: pointer;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(3px);
        z-index: 1035;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    /* 5. MOBİL (TABLET VE TELEFON) RESPONSIVE KURALLARI */
    @media (max-width: 991.98px) {
        .admin-sidebar {
            transform: translateX(-100%); /* Sidebar'ı sola iterek gizle */
        }

        .admin-sidebar.show {
            transform: translateX(0); /* Butona basınca tekrar ekrana getir */
        }

        .admin-main-wrapper {
            margin-left: 0; /* İçeriğin sola yaslanmasını sağla */
            padding: 5rem 1rem 2rem 1rem !important; /* Üstten menü butonu için boşluk bırak */
        }

        .mobile-toggle-btn {
            display: flex; /* Hamburger butonunu mobilde göster */
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1; /* Arka plan kararmasını aktif et */
        }
    }
</style>

<button class="mobile-toggle-btn" id="mobileToggleBtn">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="admin-sidebar" id="adminSidebar">
    
    <div class="d-flex justify-content-between align-items-center mb-4 px-3 mt-2">
        <h4 class="fw-bolder mb-0" style="color: #fff; font-size: 20px; letter-spacing: 1px;">
            KARACA<span style="color: var(--sidebar-accent);">PINAR</span>
        </h4>
        <button class="btn btn-link text-white d-lg-none p-0 border-0" id="closeSidebarBtn">
            <i class="fas fa-times fs-5"></i>
        </button>
    </div>

    <div class="nav flex-column mt-2 flex-grow-1">
        
        <?php 
        // Hangi sayfada olduğumuzu buluyoruz
        $currentPage = basename($_SERVER['PHP_SELF']); 
        ?>

        <a href="dashboard.php" class="nav-link-custom <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
            <i class="fas fa-chart-pie me-2"></i> Yönetim Özeti
        </a>
        
        <a href="leads.php" class="nav-link-custom <?= in_array($currentPage, ['leads.php', 'lead-detail.php']) ? 'active' : '' ?>">
            <i class="fas fa-inbox me-2"></i> Siparişler & Başvurular
        </a>
        
        <a href="products.php" class="nav-link-custom <?= in_array($currentPage, ['products.php', 'add-product.php', 'edit-product.php']) ? 'active' : '' ?>">
            <i class="fas fa-bottle-water me-2"></i> Ürün Yönetimi
        </a>

        <a href="certificates.php" class="nav-link-custom <?= in_array($currentPage, ['certificates.php', 'add-certificate.php', 'edit-certificate.php']) ? 'active' : '' ?>">
            <i class="fas fa-award me-2"></i> Kalite Belgeleri
        </a>

        <a href="dealers.php" class="nav-link-custom <?= in_array($currentPage, ['dealers.php', 'add-dealer.php', 'edit-dealer.php']) ? 'active' : '' ?>">
            <i class="fas fa-store me-2"></i> Bayi Yönetimi
        </a>
        
        <a href="settings.php" class="nav-link-custom <?= ($currentPage == 'settings.php') ? 'active' : '' ?>">
            <i class="fas fa-cog me-2"></i> Genel Ayarlar
        </a>

        <div class="mt-auto pt-4">
            <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px;">
            <a href="logout.php" class="nav-link-custom text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Çıkış Yap
            </a>
        </div>

    </div>
</aside>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('mobileToggleBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const overlay = document.getElementById('sidebarOverlay');

        // Menüyü Aç
        if(toggleBtn && sidebar && overlay) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden'; // Arka planın kaymasını engelle
            });
        }

        // Çarpıya veya Boşluğa Tıklayarak Menüyü Kapat
        const closeSidebar = function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = ''; 
        };

        if(closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if(overlay) overlay.addEventListener('click', closeSidebar);
    });
</script>