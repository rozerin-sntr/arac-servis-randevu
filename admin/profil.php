<?php
session_start();
include("../includes/baglanti.php");
/** @var mysqli $conn */
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "admin"){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$mesaj = "";
$mesaj_tipi = "";


if(isset($_POST["guncelle"])){

    $ad = $_POST["ad"];
    $email = $_POST["email"];
    $eski_sifre = $_POST["eski_sifre"];
    $yeni_sifre = $_POST["yeni_sifre"];
    $yeni_sifre_tekrar = $_POST["yeni_sifre_tekrar"];

    // Mevcut şifreyi veritabanından çek
    $stmt = $conn->prepare("SELECT sifre FROM kullanicilar WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Eğer yeni şifre alanları doldurulduysa
    if(!empty($yeni_sifre) || !empty($eski_sifre)){

        if(!password_verify($eski_sifre, $row["sifre"])){
            $mesaj = "Eski şifre yanlış!";
            $mesaj_tipi = "danger";
        }
        elseif($yeni_sifre != $yeni_sifre_tekrar){
            $mesaj = "Yeni şifreler eşleşmiyor!";
            $mesaj_tipi = "danger";
        }
        else{
            $hashli_sifre = password_hash($yeni_sifre, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE kullanicilar SET ad=?, email=?, sifre=? WHERE id=?");
            $stmt->bind_param("sssi", $ad, $email, $hashli_sifre, $user_id);
            $stmt->execute();

            $mesaj = "Profil ve şifre güncellendi!";
            $mesaj_tipi = "success";
        }

    } else {
        // Şifre değiştirmeden güncelleme
        $stmt = $conn->prepare("UPDATE kullanicilar SET ad=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $ad, $email, $user_id);
        $stmt->execute();

        $mesaj = "Profil güncellendi!";
        $mesaj_tipi = "success";
    }
}


$stmt = $conn->prepare("SELECT ad, email FROM kullanicilar WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$kullanici = $result->fetch_assoc();
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper p-3">
    <h2>Profilim</h2>

    <?php if($mesaj != ""){ ?>
        <div class="alert alert-<?php echo $mesaj_tipi; ?>">
            <?php echo $mesaj; ?>
        </div>
    <?php } ?>

    <form method="POST">
        <label>Ad Soyad</label><br>
        <input type="text" name="ad" value="<?php echo $kullanici["ad"]; ?>" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="<?php echo $kullanici["email"]; ?>" required><br><br>

        <label>Eski Şifre</label><br>
        <div style="position:relative;">
        <input type="password" name="eski_sifre" id="eski_sifre" class="form-control">
            <button type="button" onclick="togglePassword('eski_sifre')" 
                style="position:absolute; right:5px; top:5px;">
                👁
            </button>
        </div>

        <label>Yeni Şifre</label><br>
        <div style="position:relative;">
        <input type="password" name="yeni_sifre" id="yeni_sifre" class="form-control">
            <button type="button" onclick="togglePassword('yeni_sifre')" 
                style="position:absolute; right:5px; top:5px;">
                👁
        </button>
        </div>

        <label>Yeni Şifre (Tekrar)</label><br>
        <div style="position:relative;">
        <input type="password" name="yeni_sifre_tekrar" id="yeni_sifre_tekrar" class="form-control">
            <button type="button" onclick="togglePassword('yeni_sifre_tekrar')" 
                style="position:absolute; right:5px; top:5px;">
                👁
            </button>
        </div>

        <button type="submit" name="guncelle">Güncelle</button>
    </form>
</div>

<script>
function togglePassword(id) {
    var input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>
<?php include("../includes/footer.php"); ?>