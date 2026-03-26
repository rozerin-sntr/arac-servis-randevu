<?php
session_start();
include("../includes/baglanti.php");
/** @var mysqli $conn */
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
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

    $stmt = $conn->prepare("SELECT sifre FROM kullanicilar WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

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

include("../includes/header.php");
include("../includes/sidebar.php");
?>


<div class="content-wrapper" style="background-color: #f4f6f9; min-height: 100vh;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-6"><h2 class="font-weight-bold text-dark">Profil Bilgilerim</h2></div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm py-5 text-center" style="border-radius: 20px;">
                        <div class="mb-4">
                            <img class="img-circle elevation-1 shadow-sm"
                                 src="../assets/adminlte/dist/img/avatar3.png"
                                 alt="User profile picture"
                                 style="width: 150px; border: 4px solid #fff; padding: 2px;">
                        </div>
                        <h3 class="font-weight-bold mb-1" style="color: #343a40;">Sude Nur Koç</h3>
                        <p class="text-muted">Kayıtlı Müşteri</p>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                        <h5 class="text-primary font-weight-bold mb-4">
                            <i class="fas fa-user-edit mr-2"></i>Hesap Ayarları
                        </h5>
                        
                        <?php if($mesaj): ?> 
                            <div class="alert alert-<?php echo $mesaj_tipi; ?> rounded-pill mb-4 shadow-sm border-0 px-4">
                                <i class="fas fa-info-circle mr-2"></i><?php echo $mesaj; ?>
                            </div> 
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted">AD SOYAD</label>
                                <input type="text" name="ad" class="form-control" value="Sude Nur Koç" 
                                       style="border-radius: 50px; height: 50px; border: 1px solid #dee2e6; padding-left: 20px;">
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted">E-POSTA ADRESİ</label>
                                <input type="email" name="email" class="form-control" value="sudenurkoc@mail.com" 
                                       style="border-radius: 50px; height: 50px; border: 1px solid #dee2e6; padding-left: 20px;">
                            </div>
                            
                            <hr class="my-4" style="border-top: 1px dashed #ddd;">
                            
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-danger">MEVCUT ŞİFRE (GÜVENLİK ONAYI)</label>
                                <input type="password" name="eski_sifre" class="form-control" placeholder="Onay için mevcut şifrenizi girin" 
                                       style="border-radius: 50px; height: 50px; border: 1px dashed #ced4da; padding-left: 20px;">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="small font-weight-bold text-muted">YENİ ŞİFRE</label>
                                    <input type="password" name="yeni_sifre" class="form-control shadow-sm" 
                                           style="border-radius: 50px; height: 50px; border: 1px solid #dee2e6;">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="small font-weight-bold text-muted">YENİ ŞİFRE (TEKRAR)</label>
                                    <input type="password" name="yeni_sifre_tekrar" class="form-control shadow-sm" 
                                           style="border-radius: 50px; height: 50px; border: 1px solid #dee2e6;">
                                </div>
                            </div>
                            
                            <div class="text-right mt-3">
                                <button type="submit" name="guncelle" class="btn btn-primary px-5 shadow font-weight-bold" 
                                        style="border-radius: 50px; height: 55px; font-size: 16px;">
                                    <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>