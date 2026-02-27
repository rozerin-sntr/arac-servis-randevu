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

        if(password_verify($sifre, $row["sifre"])){

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
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Giriş Yap</h2>

<?php
if(isset($hata)){
    echo "<p style='color:red;'>$hata</p>";
}
?>

<form method="POST">
    Email:<br>
    <input type="email" name="email" required><br><br>

    Şifre:<br>
    <input type="password" name="sifre" required><br><br>

    <br>
    <a href="sifremi_unuttum.php">Şifremi Unuttum</a>

    <button type="submit" name="giris">Giriş Yap</button>
</form>

</body>
</html>