// admin/assets/js/admin.js

/**
 * Genel Silme Onay Penceresi (SweetAlert2)
 * @param {string} deleteUrl - Silme işleminin yapılacağı URL (Örn: 'delete-product.php?id=5')
 * @param {string} customMessage - Kullanıcıya gösterilecek uyarı metni
 */
window.confirmDelete = function(deleteUrl, customMessage = "Bu kaydı tamamen silmek üzeresiniz. Bu işlem geri alınamaz!") {
    Swal.fire({
        title: 'Emin misiniz?',
        text: customMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444', // Kırmızı (Tehlike)
        cancelButtonColor: '#64748b',  // Gri (İptal)
        confirmButtonText: 'Evet, Sil!',
        cancelButtonText: 'İptal',
        background: '#ffffff',
        color: '#2c3e50',
        customClass: { popup: 'rounded-4 shadow-lg border' }
    }).then((result) => {
        if (result.isConfirmed) {
            // Kullanıcı onaylarsa ilgili PHP silme dosyasına yönlendir
            window.location.href = deleteUrl;
        }
    });
};

/**
 * Genel Bildirim Penceresi (SweetAlert2)
 * @param {string} type - 'success', 'error', 'warning', 'info'
 * @param {string} message - Gösterilecek mesaj metni
 * @param {string} redirectUrl - (Opsiyonel) Tamam'a basıldıktan sonra yönlendirilecek sayfa
 */
window.showAlert = function(type, message, redirectUrl = null) {
    const title = type === 'success' ? 'Başarılı!' : 'Hata!';
    const btnColor = type === 'success' ? '#00a8ff' : '#ef4444'; // Turkuaz veya Kırmızı

    Swal.fire({
        icon: type,
        title: title,
        text: message,
        background: '#ffffff',
        color: '#2c3e50',
        confirmButtonColor: btnColor,
        confirmButtonText: 'Tamam',
        customClass: { popup: 'rounded-4 shadow-lg border' }
    }).then(() => {
        // Eğer bir yönlendirme URL'si verilmişse oraya git
        if (redirectUrl) {
            window.location.href = redirectUrl;
        } 
        // POST işlemi sonrası sayfayı temizlemek için (Formun tekrar gönderilmesini önler)
        else if (type === 'success' && window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
};