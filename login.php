<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include("includes/baglanti.php");

if(isset($_POST["giris"])){

    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    $sql = "SELECT * FROM kullanicilar WHERE email='$email' AND sifre='$sifre'";
    $sonuc = mysqli_query($conn, $sql);

    if(mysqli_num_rows($sonuc) == 1){

        $row = mysqli_fetch_assoc($sonuc);

        $_SESSION["user_id"] = $row["id"];
        $_SESSION["rol"] = $row["rol"];

        if($row["rol"] == "admin"){
            header("Location: admin/dashboard.php");
        }
        elseif($row["rol"] == "servis"){
            header("Location: servis/dashboard.php");
        }
        elseif($row["rol"] == "musteri"){
            header("Location: musteri/dashboard.php");
        }

        exit;

    } else {
        $hata = "Hatalı giriş!";
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

    <button type="submit" name="giris">Giriş Yap</button>
</form>

</body>
</html>