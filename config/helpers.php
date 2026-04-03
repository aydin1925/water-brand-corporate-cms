<?php
// config/helpers.php

/**
 * Görselleri WebP formatına dönüştürerek veya PDF'leri doğrudan uploads klasörüne kaydeder.
 *
 * @param array  $fileInput   $_FILES['input_name'] dizisi
 * @param string $subDir      'team', 'products', 'certificates', 'settings' gibi alt klasör adı
 * @param string $prefixName  Dosya isminin başına gelecek ön ek
 * @return string|false       Başarılıysa 'uploads/subDir/dosya.webp' (veya .pdf) döner, başarısızsa false.
 */
function uploadAndConvertToWebP($fileInput, $subDir = '', $prefixName = 'img') {
    // 1. Temel hata kontrolü
    if (!isset($fileInput) || $fileInput['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $tmp_name = $fileInput['tmp_name'];
    $file_name = $fileInput['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // PDF desteği eklendi
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    if (!in_array($file_ext, $allowed_exts)) {
        return false; 
    }

    // 2. Klasör Yollarını Belirle (Tüm projede standart olması için 'uploads/' yapıldı)
    // Fiziksel Yol: Admin klasöründeki PHP dosyasının dosyayı yazacağı yer (../uploads/)
    $physicalPath = "../uploads/" . ($subDir ? rtrim($subDir, '/') . '/' : '');
    
    // Veritabanı Yolu: Sitenin ön yüzünde ve veritabanında saklanacak "temiz" yol (uploads/)
    $dbPath = "uploads/" . ($subDir ? rtrim($subDir, '/') . '/' : '');

    // Klasör yoksa oluştur (Örn: uploads/products/)
    if (!is_dir($physicalPath)) {
        mkdir($physicalPath, 0777, true);
    }

    // 3. Benzersiz dosya adı oluştur
    // Eğer dosya PDF ise uzantıyı '.pdf' bırak, resimse '.webp' yap
    $final_ext = ($file_ext === 'pdf') ? 'pdf' : 'webp';
    $new_filename = $prefixName . '-' . time() . '.' . $final_ext;
    
    $targetPhysicalFile = $physicalPath . $new_filename;
    $targetDbPath = $dbPath . $new_filename;

    // 4. PDF İŞLEMİ (Dönüştürmeden Doğrudan Taşı)
    if ($file_ext === 'pdf') {
        if (move_uploaded_file($tmp_name, $targetPhysicalFile)) {
            return $targetDbPath;
        }
        return false;
    }

    // 5. GÖRSEL İŞLEMİ (GD Kütüphanesi ile resmi belleğe al ve WebP yap)
    $image = false;
    if ($file_ext == 'jpg' || $file_ext == 'jpeg') {
        $image = @imagecreatefromjpeg($tmp_name);
    } elseif ($file_ext == 'png') {
        $image = @imagecreatefrompng($tmp_name);
        if ($image !== false) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }
    } elseif ($file_ext == 'webp') {
        $image = @imagecreatefromwebp($tmp_name);
    }

    // 6. Kaydet ve Temizle
    if ($image !== false) {
        // imagewebp fonksiyonu fiziksel klasöre yazar
        if (imagewebp($image, $targetPhysicalFile, 80)) {
            imagedestroy($image);
            // Başarılı! Veritabanına kayıt yapabilmen için dbPath'i döndür
            return $targetDbPath; 
        }
        imagedestroy($image);
    }

    return false;
}

/**
 * SEO uyumlu URL (Slug) oluşturur.
 * Ürün ekleme/düzenleme sayfalarındaki Türkçe karakterleri ve boşlukları dönüştürür.
 *
 * @param string $text Çevrilecek metin (Örn: "19 Litre Cam Damacana")
 * @return string Temizlenmiş URL (Örn: "19-litre-cam-damacana")
 */
function generateSlug($text) {
    // Türkçe karakter dönüşüm haritası
    $find = ['Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#'];
    $replace = ['c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp'];
    
    $text = strtolower(str_replace($find, $replace, $text));
    
    // Alfasayısal olmayan tüm karakterleri tire (-) ile değiştir
    $text = preg_replace('/[^a-z0-9\-]/', '-', $text);
    
    // Birden fazla tireyi tek tireye düşür
    $text = preg_replace('/-+/', '-', $text);
    
    // Başındaki ve sonundaki tireleri temizle
    return trim($text, '-');
}
?>