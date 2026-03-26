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

        if($sifre == $row["sifre"]){
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

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1 class="m-0 font-weight-bold" style="color: #343a40;">Profil Bilgilerim</h1>
</div>
</div>
</div>
</section>

<section class="content">
<div class="container-fluid">
<div class="row">

    <div class="col-md-4">
      <div class="card card-primary card-outline shadow-sm" style="border-radius: 15px; border-top: 3px solid #007bff;">
        <div class="card-body box-profile text-center">
          <div class="text-center">
            <img class="profile-user-img img-fluid img-circle mb-3"
src="../assets/adminlte/dist/img/avatar3.png"
alt="User profile picture"
style="width: 100px; border: 3px solid #adb5bd; padding: 3px;">
                 
          </div>
          <h3 class="profile-username font-weight-bold" style="color: #343a40;"><?php echo $kullanici['ad']; ?></h3>
          <p class="text-muted">Sistem Yöneticisi</p>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white" style="border-bottom: 1px solid #f4f4f4;">
          <h3 class="card-title font-weight-bold" style="color: #343a40;">
            <i class="fas fa-user-edit mr-2 text-primary"></i>Hesap Ayarları
          </h3>
        </div>
        <div class="card-body">

          <?php if($mesaj != ""): ?>
            <div class="alert alert-<?php echo $mesaj_tipi; ?> rounded-pill small py-2 shadow-sm">
              <i class="fas fa-info-circle mr-2"></i><?php echo $mesaj; ?>
            </div>
          <?php endif; ?>

          <form method="POST">
            <div class="form-group">
              <label style="font-weight: 600;">Ad Soyad</label>
              <input type="text" name="ad" class="form-control" 
                     style="border-radius: 50px; padding: 10px 20px;" 
                     value="<?php echo $kullanici['ad']; ?>" required>
            </div>

            <div class="form-group">
              <label style="font-weight: 600;">E-posta Adresi</label>
              <input type="email" name="email" class="form-control" 
                     style="border-radius: 50px; padding: 10px 20px;" 
                     value="<?php echo $kullanici['email']; ?>" required>
            </div>

            <hr style="margin: 25px 0; border-top: 1px dashed #dee2e6;">

            <div class="form-group">
              <label style="font-weight: 600;">Eski Şifre</label>
              <input type="password" name="eski_sifre" class="form-control" 
                     style="border-radius: 50px; padding: 10px 20px;" 
                     placeholder="Değiştirmek istemiyorsanız boş bırakın">
            </div>

            <div class="row">
              <div class="col-md-6 form-group">
                <label style="font-weight: 600;">Yeni Şifre</label>
                <input type="password" name="yeni_sifre" class="form-control" 
                       style="border-radius: 50px; padding: 10px 20px;">
              </div>
              <div class="col-md-6 form-group">
                <label style="font-weight: 600;">Yeni Şifre (Tekrar)</label>
                <input type="password" name="yeni_sifre_tekrar" class="form-control" 
                       style="border-radius: 50px; padding: 10px 20px;">
              </div>
            </div>

            <div class="text-right mt-3">
              <button type="submit" name="guncelle" class="btn btn-primary px-5 shadow-sm" 
                      style="border-radius: 50px; font-weight: 600;">
                <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
</section>
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