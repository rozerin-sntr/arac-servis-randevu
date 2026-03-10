<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session ayarları
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true
]);

session_start();

include("includes/baglanti.php");
/** @var mysqli $conn */

$hata = "";

if(isset($_POST["giris"])){

    $email = trim($_POST["email"]);
    $sifre = $_POST["sifre"];

    $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){

        $row = $result->fetch_assoc();

       if($sifre == $row["sifre"]){
            // Güvenlik için session yenile
            session_regenerate_id(true);

            $_SESSION["user_id"] = $row["id"];
            $_SESSION["rol"] = $row["rol"];

            // Rol bazlı yönlendirme
            switch($row["rol"]){
                case "admin":
                    header("Location: /arac-servis-randevu/admin/dashboard.php");
                    break;

                case "servis":
                    header("Location: /arac-servis-randevu/servis/dashboard.php");
                    break;

                case "musteri":
                    header("Location: /arac-servis-randevu/musteri/dashboard.php");
                    break;

                default:
                    header("Location: login.php");
            }

            exit;

        } else {
            $hata = "Hatalı şifre!";
        }

    } else {
        $hata = "Kullanıcı bulunamadı!";
    }
}
?>

<!DOCTYPE html>

<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Giriş Yap | Araç Servis Sistemi</title>
<link rel="stylesheet" href="">
<link rel="stylesheet" href="">
<style>
body {
background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
height: 100vh;
display: flex;
align-items: center;
justify-content: center;
margin: 0;
font-family: 'Segoe UI', sans-serif;
}
.login-card {
background: #ffffff;
padding: 40px;
border-radius: 20px;
box-shadow: 0 15px 35px rgba(0,0,0,0.3);
width: 100%;
max-width: 400px;
text-align: center;
}
.main-icon { color: #2563eb; margin-bottom: 15px; }
.form-control {
border-radius: 50px;
padding: 12px 20px;
height: auto;
border: 1px solid #e2e8f0;
}
.btn-login {
background: #2563eb;
color: white;
border-radius: 50px;
padding: 12px;
font-weight: 600;
width: 100%;
border: none;
transition: all 0.3s;
margin-top: 10px;
}
.btn-login:hover {
background: #1d4ed8;
transform: translateY(-2px);
box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
}
</style>
</head>
<body>

<div class="login-card">
<i class="fas fa-tools fa-4x main-icon"></i>
<h2 style="font-weight: 700; color: #1e293b;">Servis Girişi</h2>
<p style="color: #64748b; margin-bottom: 30px;">Araç Servis Randevu Sistemi</p>

<form action="" method="POST">
<div class="form-group text-left">
<label style="margin-left: 15px; font-weight: 500;">Email Adresi</label>
<input type="email" name="email" class="form-control" placeholder="admin@servis.com" required>
</div>
<div class="form-group text-left">
<label style="margin-left: 15px; font-weight: 500;">Şifre</label>
<input type="password" name="sifre" class="form-control" placeholder="••••••••" required>
<button type="submit" name="giris" style="background: #2563eb; color: white; border-radius: 50px; padding: 12px; font-weight: 600; width: 100%; border: none; transition: all 0.3s; margin-top: 20px; cursor: pointer;">
Sisteme Giriş Yap
</button>
</div>

</form>

<div style="margin-top: 20px;">
<a href="sifre_unuttum.php" style="color: #64748b; text-decoration: none; font-size: 14px;">Şifremi Unuttum</a>
</div>
</div>

</body>
</html>