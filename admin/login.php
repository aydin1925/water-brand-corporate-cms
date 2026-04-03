<?php

session_start();

if(isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi | Karacapınar Su</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/admin.css"> 
</head>

<body class="login-page-body">

    <div class="login-overlay"></div>

    <div class="login-card">
        
        <a href="../index.php">
            <img src="../uploads/img/karacapınar-logo.png" alt="Karacapınar Logo" class="login-logo">
        </a>
        
        <h1 class="login-title">Yönetim Paneli</h1>
        <p class="login-subtitle">Lütfen yetkili bilgilerinizi giriniz.</p>

        <form action="login-process.php" method="POST">
            
            <div class="input-group-custom">
                <input type="text" name="username" class="form-control-custom" placeholder="Kullanıcı Adı veya E-Posta" required>
                <i class="fas fa-user"></i>
            </div>

            <div class="input-group-custom">
                <input type="password" name="password" class="form-control-custom" placeholder="Şifreniz" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Beni Hatırla
                    </label>
                </div>
                <a href="#" class="forgot-link text-decoration-none">Şifremi Unuttum</a>
            </div>

            <button type="submit" name="login_submit" class="btn-login">
                Giriş Yap <i class="fas fa-arrow-right ms-2"></i>
            </button>

        </form>
        
        <div class="mt-4 pt-3 border-top" style="border-color: rgba(0,0,0,0.05)!important;">
            <p class="mb-0 text-secondary" style="font-size: 11px;">
                <i class="fas fa-shield-alt text-success me-1"></i> 256-bit SSL ile korunmaktadır.
            </p>
        </div>

    </div>

</body>
</html>