<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include("includes/baglanti.php");

if(isset($_POST["kayit"])){

    $ad = $_POST["ad"];
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    // Email zaten var mı kontrolü
    $kontrol = "SELECT * FROM kullanicilar WHERE email='$email'";
    $sonuc = mysqli_query($conn, $kontrol);

    if(mysqli_num_rows($sonuc) > 0){
        $hata = "Bu email zaten kayıtlı!";
    } else {

        $sql = "INSERT INTO kullanicilar (ad, email, sifre, rol)
                VALUES ('$ad', '$email', '$sifre', 'musteri')";

        if(mysqli_query($conn, $sql)){
            $basarili = "Kayıt başarılı! Giriş yapabilirsiniz.";
        } else {
            $hata = "Kayıt sırasında hata oluştu!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Ol</title>
</head>
<body>

<h2>Müşteri Kayıt</h2>

<?php
if(isset($hata)){
    echo "<p style='color:red;'>$hata</p>";
}

if(isset($basarili)){
    echo "<p style='color:green;'>$basarili</p>";
}
?>

<form method="POST">
    Ad:<br>
    <input type="text" name="ad" required><br><br>

    Email:<br>
    <input type="email" name="email" required><br><br>

    Şifre:<br>
    <input type="password" name="sifre" required><br><br>

    <button type="submit" name="kayit">Kayıt Ol</button>
</form>

<br>
<a href="login.php">Giriş Yap</a>

</body>
</html>