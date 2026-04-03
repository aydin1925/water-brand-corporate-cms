<?php

session_start();

require_once '../config/db.php';
$database = new Database();
$db = $database->connect();

if($_SERVER['REQUEST_METHOD'] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if(empty($username) || empty($password)) {
        header("Location: login.php?error=empty");
        exit;
    }

    try{
        // Sorgu metnini oluşturuyorum
        $sql = "SELECT * FROM admins WHERE username = :username LIMIT 1";
        $statement = $db->prepare($sql);
        $statement->execute([':username' => $username]);

        // Gelen veri admin olsun
        $admin = $statement->fetch(PDO::FETCH_ASSOC);

        if($admin && password_verify($password, $admin['password'])) {
            // Giriş başarılıysa
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_role'] = $admin['role'];

            // Güvenlik için session ID yeniliyorum
            session_regenerate_id(true);

            // Dashboarda yönlendiriyorum
            header("Location: dashboard.php");
            exit;
        }
        else{
            // Giriş başarısızsa
            header("Location: login.php?error=invalid");
            exit;
        }
    }
    catch(PDOException $e) {
        error_log("Login hatası: " . $e->getMessage());
        header("Location: login.php?error=system");
        exit;
    }
}
else{
    header("Location: login.php");
    exit;
}

?>