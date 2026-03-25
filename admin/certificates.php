<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalite Belgeleri | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">KURUMSAL GÜVEN</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Kalite Belgeleri</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Sitede sergilenen ISO, TSE ve Helal sertifikalarını yönetin.</p>
            </div>
            
            <div>
                <a href="add-certificate.php" class="btn btn-add-new fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px;">
                    <i class="fas fa-plus-circle me-2"></i> Yeni Belge Ekle
                </a>
            </div>
        </div>

        <div class="filter-wrapper mb-4 shadow-sm">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control custom-search-input w-100" placeholder="Belge adı veya kurum ara (Örn: ISO 9001)...">
                </div>
                
                <div class="col-md-4">
                    <select name="status" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Durumlar</option>
                        <option value="aktif">Aktif (Sitede Görünür)</option>
                        <option value="pasif">Pasif (Gizli)</option>
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
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 ps-4" style="letter-spacing: 1px; width: 5%;">Sıra</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 40%;">Belge Bilgisi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 20%;">Geçerlilik Tarihi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-center" style="letter-spacing: 1px; width: 15%;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px; width: 20%;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0 bg-white">
                        
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">1</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="cert-thumb-wrapper">
                                        <i class="fas fa-file-pdf"></i>
                                        </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">ISO 9001:2015</div>
                                        <div class="small fw-medium text-secondary mt-1">Kalite Yönetim Sistemi</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;">31 Aralık 2026</div>
                                <div class="small text-success mt-1"><i class="fas fa-check-circle me-1"></i> Geçerli</div>
                            </td>
                            <td class="text-center"><span class="status-badge status-aktif">AKTİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-certificate.php?id=1" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4 fw-bold text-secondary">2</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="cert-thumb-wrapper">
                                        <i class="fas fa-file-image"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">ISO 22000:2018</div>
                                        <div class="small fw-medium text-secondary mt-1">Gıda Güvenliği Yönetim Sistemi</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;">15 Ekim 2025</div>
                                <div class="small text-success mt-1"><i class="fas fa-check-circle me-1"></i> Geçerli</div>
                            </td>
                            <td class="text-center"><span class="status-badge status-aktif">AKTİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-certificate.php?id=2" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4 fw-bold text-secondary">3</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="cert-thumb-wrapper">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">TSE Standart Uygunluk</div>
                                        <div class="small fw-medium text-secondary mt-1">Türk Standartları Enstitüsü</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;">01 Ocak 2024</div>
                                <div class="small text-danger mt-1"><i class="fas fa-exclamation-circle me-1"></i> Süresi Dolmuş</div>
                            </td>
                            <td class="text-center"><span class="status-badge status-pasif">PASİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-certificate.php?id=3" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-white">
                <div class="small fw-medium text-secondary">
                    Toplam <span class="fw-bold text-brand-dark">3</span> belgeden <span class="fw-bold text-brand-dark">1-3</span> arası gösteriliyor.
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>

        </div>

    </div>

</body>
</html>