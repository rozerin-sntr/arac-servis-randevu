<?php
include("includes/baglanti.php");
/** @var mysqli $conn */
$mesaj = "";
$mesaj_tipi = "";

if(isset($_POST["reset"])){

    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT id FROM kullanicilar WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){

        $row = $result->fetch_assoc();
        $user_id = $row["id"];

        // 8 karakterli rastgele şifre üret
        $yeni_sifre = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz123456789"),0,8);

        $hashli = password_hash($yeni_sifre, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE kullanicilar SET sifre=? WHERE id=?");
        $stmt->bind_param("si", $hashli, $user_id);
        $stmt->execute();

        $mesaj = "Yeni şifreniz: <strong>$yeni_sifre</strong>";
        $mesaj_tipi = "success";
    }
    else{
        $mesaj = "Bu email ile kullanıcı bulunamadı.";
        $mesaj_tipi = "danger";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Şifremi Unuttum</title>
    <link rel="stylesheet" href="adminlte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Şifremi Unuttum</p>

            <?php if($mesaj != ""){ ?>
                <div class="alert alert-<?php echo $mesaj_tipi; ?>">
                    <?php echo $mesaj; ?>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <button type="submit" name="reset" class="btn btn-primary btn-block">
                    Şifreyi Sıfırla
                </button>
            </form>

            <br>
            <a href="login.php">Giriş Sayfasına Dön</a>

        </div>
    </div>
</div>

</body>
</html>