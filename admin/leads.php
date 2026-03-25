<?php 
    // BURAYA KENDİ PHP BACKEND, SESSION VE VERİTABANI KODLARINI EKLEYECEKSİN 
    // require_once '../config/db.php'; vs.
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişler ve Başvurular | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">MÜŞTERİ YÖNETİMİ</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Siparişler & Başvurular</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Sistem üzerinden gelen tüm talepleri buradan yönetebilirsiniz.</p>
            </div>
            
            <div>
                <button class="btn bg-brand-main text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px;">
                    <i class="fas fa-download me-2"></i> Excel Olarak İndir
                </button>
            </div>
        </div>

        <div class="filter-wrapper mb-4 shadow-sm">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control custom-search-input w-100" placeholder="Müşteri adı, telefon veya e-posta ara...">
                </div>
                
                <div class="col-md-3">
                    <select name="type" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Talep Türleri</option>
                        <option value="damacana">Damacana Siparişi</option>
                        <option value="bayilik">Bayilik Başvurusu</option>
                        <option value="iletisim">Genel İletişim</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Durumlar</option>
                        <option value="yeni">Yeni</option>
                        <option value="islemde">İşlemde</option>
                        <option value="tamamlandi">Tamamlandı</option>
                        <option value="iptal">İptal</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn w-100 fw-bold rounded-pill text-white" style="background-color: #00a8ff; padding: 12px;">
                        Filtrele
                    </button>
                </div>
            </form>
        </div>

        <div class="glass-panel p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: rgba(244, 248, 251, 0.9);">
                        <tr>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 ps-4" style="letter-spacing: 1px; width: 5%;">ID</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 25%;">Müşteri / Firma Bilgisi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 20%;">İletişim</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 15%;">Talep Türü</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 15%;">Tarih</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-center" style="letter-spacing: 1px; width: 10%;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px; width: 10%;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0 bg-white">
                        
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#1042</td>
                            <td>
                                <div class="fw-bolder text-brand-dark">Ahmet Yılmaz</div>
                                <div class="small fw-medium text-secondary mt-1"><i class="fas fa-map-marker-alt text-brand-blue me-1"></i> Çankaya, Ankara</div>
                            </td>
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;">0555 123 45 67</div>
                                <div class="small text-secondary mt-1">ahmet@mail.com</div>
                            </td>
                            <td><span class="fw-bold" style="color: #1C4F8C;">19L Damacana Siparişi</span></td>
                            <td class="text-secondary fw-medium" style="font-size: 14px;">24 Eki 2023 <br><span class="small opacity-75">14:30</span></td>
                            <td class="text-center"><span class="status-badge status-yeni">YENİ</span></td>
                            <td class="text-end pe-4">
                                <a href="lead-detail.php?id=1042" class="action-btn btn-view" title="Görüntüle"><i class="fas fa-eye"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#1041</td>
                            <td>
                                <div class="fw-bolder text-brand-dark">Yıldız Market Ltd. Şti.</div>
                                <div class="small fw-medium text-secondary mt-1"><i class="fas fa-map-marker-alt text-brand-blue me-1"></i> Nilüfer, Bursa</div>
                            </td>
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;">0312 987 65 43</div>
                                <div class="small text-secondary mt-1">info@yildizmarket.com</div>
                            </td>
                            <td><span class="fw-bold" style="color: #f59e0b;">Bayilik Başvurusu</span></td>
                            <td class="text-secondary fw-medium" style="font-size: 14px;">23 Eki 2023 <br><span class="small opacity-75">09:15</span></td>
                            <td class="text-center"><span class="status-badge status-islemde">İŞLEMDE</span></td>
                            <td class="text-end pe-4">
                                <a href="lead-detail.php?id=1041" class="action-btn btn-view" title="Görüntüle"><i class="fas fa-eye"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-white">
                <div class="small fw-medium text-secondary">
                    Toplam <span class="fw-bold text-brand-dark">124</span> kayıttan <span class="fw-bold text-brand-dark">1-10</span> arası gösteriliyor.
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>

        </div>

    </div>

</body>
</html>