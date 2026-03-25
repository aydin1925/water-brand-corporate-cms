<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayi Yönetimi | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">BAYİ AĞI</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Bayi Yönetimi</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Aktif ve pasif tüm bayilerinizi, bölgelerini ve iletişim bilgilerini yönetin.</p>
            </div>
            
            <div>
                <a href="add-dealer.php" class="btn btn-add-new fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px;">
                    <i class="fas fa-plus-circle me-2"></i> Yeni Bayi Ekle
                </a>
            </div>
        </div>

        <div class="filter-wrapper mb-4 shadow-sm">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control custom-search-input w-100" placeholder="Bayi adı veya yetkili ara...">
                </div>
                
                <div class="col-md-3">
                    <select name="city" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Bölgeler (İller)</option>
                        <option value="ankara">Ankara</option>
                        <option value="istanbul">İstanbul</option>
                        <option value="bursa">Bursa</option>
                        <option value="izmir">İzmir</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Durumlar</option>
                        <option value="aktif">Aktif</option>
                        <option value="pasif">Pasif</option>
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
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 30%;">Bayi / Yetkili Bilgisi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 25%;">İletişim</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 15%;">Hizmet Bölgesi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-center" style="letter-spacing: 1px; width: 10%;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px; width: 15%;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0 bg-white">
                        
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#201</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="dealer-icon-wrapper">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">Çankaya Merkez Bayi</div>
                                        <div class="small fw-medium text-secondary mt-1"><i class="fas fa-user-tie text-brand-blue me-1"></i> Ahmet Yılmaz</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;">0312 456 78 90</div>
                                <div class="small text-secondary mt-1">cankaya@karacapinar.com.tr</div>
                            </td>
                            <td>
                                <div class="fw-bold" style="color: #1C4F8C;">Ankara</div>
                                <div class="small text-secondary mt-1">Çankaya, Gölbaşı</div>
                            </td>
                            <td class="text-center"><span class="status-badge status-aktif">AKTİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-dealer.php?id=201" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#202</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="dealer-icon-wrapper">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">Marmara Dağıtım A.Ş.</div>
                                        <div class="small fw-medium text-secondary mt-1"><i class="fas fa-user-tie text-brand-blue me-1"></i> Mehmet Demir</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;">0224 123 45 67</div>
                                <div class="small text-secondary mt-1">bursa@karacapinar.com.tr</div>
                            </td>
                            <td>
                                <div class="fw-bold" style="color: #1C4F8C;">Bursa</div>
                                <div class="small text-secondary mt-1">Nilüfer, Osmangazi</div>
                            </td>
                            <td class="text-center"><span class="status-badge status-aktif">AKTİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-dealer.php?id=202" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#203</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="dealer-icon-wrapper" style="background: rgba(100, 116, 139, 0.1); color: #64748b;">
                                        <i class="fas fa-store-slash"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">Ege Su Dağıtım</div>
                                        <div class="small fw-medium text-secondary mt-1"><i class="fas fa-user-tie text-brand-blue me-1"></i> Ayşe Kaya</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-brand-dark fw-medium" style="font-size: 14px;">0232 987 65 43</div>
                                <div class="small text-secondary mt-1">izmir@karacapinar.com.tr</div>
                            </td>
                            <td>
                                <div class="fw-bold" style="color: #64748b;">İzmir</div>
                                <div class="small text-secondary mt-1">Bornova, Buca</div>
                            </td>
                            <td class="text-center"><span class="status-badge status-pasif">PASİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-dealer.php?id=203" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-white">
                <div class="small fw-medium text-secondary">
                    Toplam <span class="fw-bold text-brand-dark">45</span> bayiden <span class="fw-bold text-brand-dark">1-10</span> arası gösteriliyor.
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