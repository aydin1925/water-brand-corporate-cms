<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Yönetimi | Karacapınar Su</title>
    
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
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 2px; color: #00a8ff;">VİTRİN YÖNETİMİ</span>
                </div>
                <h2 class="fw-bolder mb-0 text-brand-dark" style="font-size: 2.2rem; letter-spacing: -1px;">Ürün Yönetimi</h2>
                <p class="text-secondary fw-medium mt-1 mb-0" style="font-size: 0.95rem;">Sitede sergilenen ürünleri ekleyin, düzenleyin veya kaldırın.</p>
            </div>
            
            <div>
                <a href="add-product.php" class="btn btn-add-new fw-bold px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px;">
                    <i class="fas fa-plus-circle me-2"></i> Yeni Ürün Ekle
                </a>
            </div>
        </div>

        <div class="filter-wrapper mb-4 shadow-sm">
            <form action="" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control custom-search-input w-100" placeholder="Ürün adı ara (Örn: 19L Damacana)...">
                </div>
                
                <div class="col-md-3">
                    <select name="category" class="form-select custom-select-filter w-100">
                        <option value="">Tüm Kategoriler</option>
                        <option value="damacana">Damacana</option>
                        <option value="cam">Cam Şişe</option>
                        <option value="pet">Pet Şişe</option>
                    </select>
                </div>

                <div class="col-md-2">
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
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 35%;">Ürün Bilgisi</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3" style="letter-spacing: 1px; width: 20%;">Kategori</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-center" style="letter-spacing: 1px; width: 15%;">Durum</th>
                            <th class="border-0 text-secondary small fw-bold text-uppercase py-3 text-end pe-4" style="letter-spacing: 1px; width: 25%;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0 bg-white">
                        
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">1</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="product-thumb-wrapper">
                                        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050186.png" alt="19L Damacana">
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">19 L Damacana</div>
                                        <div class="small fw-medium text-secondary mt-1">Ev ve ofisler için ideal, ekonomik boy.</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="fw-bold" style="color: #1C4F8C;">Damacana</span></td>
                            <td class="text-center"><span class="status-badge status-aktif">AKTİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-product.php?id=1" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4 fw-bold text-secondary">2</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="product-thumb-wrapper">
                                        <img src="https://cdn-icons-png.flaticon.com/512/2447/2447124.png" alt="15L Cam Damacana">
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">15 L Cam Damacana</div>
                                        <div class="small fw-medium text-secondary mt-1">Aileniz için sağlıklı cam ambalaj.</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="fw-bold" style="color: #00a8ff;">Cam Şişe</span></td>
                            <td class="text-center"><span class="status-badge status-aktif">AKTİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-product.php?id=2" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-4 fw-bold text-secondary">3</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="product-thumb-wrapper">
                                        <img src="https://cdn-icons-png.flaticon.com/512/824/824239.png" alt="5L Pet Şişe">
                                    </div>
                                    <div>
                                        <div class="fw-bolder text-brand-dark" style="font-size: 15px;">5 L Pet Şişe</div>
                                        <div class="small fw-medium text-secondary mt-1">Sofralarınızın vazgeçilmezi.</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="fw-bold" style="color: #64748b;">Pet Şişe</span></td>
                            <td class="text-center"><span class="status-badge status-pasif">PASİF</span></td>
                            <td class="text-end pe-4">
                                <a href="edit-product.php?id=3" class="action-btn btn-view" title="Düzenle"><i class="fas fa-pen"></i></a>
                                <button class="action-btn btn-delete ms-1" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>

                        </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-4 border-top bg-white">
                <div class="small fw-medium text-secondary">
                    Toplam <span class="fw-bold text-brand-dark">8</span> üründen <span class="fw-bold text-brand-dark">1-8</span> arası gösteriliyor.
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