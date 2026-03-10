<?php
session_start();
include("../includes/baglanti.php");

/** @var mysqli $conn */

// Güvenlik Kontrolü: Sadece müşteriler randevu oluşturabilir
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$mesaj = "";
$mesaj_tipi = "";

// Randevu Kayıt İşlemi
if(isset($_POST["randevu_kaydet"])){
    $arac_bilgisi = $_POST["arac_bilgisi"];
    $tarih = $_POST["tarih"];
    $saat = $_POST["saat"];
    $aciklama = $_POST["aciklama"];

    $sorgu_metni = "INSERT INTO randevular (musteri_id, arac_bilgisi, randevu_tarihi,randevu_saati, aciklama, durum) VALUES (?, ?, ?, ?, ?, 'beklemede')";
$ekle = $conn->prepare($sorgu_metni);

if ($ekle === false) {
die("Sorgu Hazırlama Hatası: " . $conn->error);
}

$ekle->bind_param("issss", $user_id, $arac_bilgisi, $tarih, $saat, $aciklama);

if($ekle->execute()){
$mesaj = "Randevu talebiniz başarıyla oluşturuldu!";
$mesaj_tipi = "success";
} else {
$mesaj = "Bir hata oluştu, lütfen tekrar deneyin.";
$mesaj_tipi = "danger";
}
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper" style="background-color: #f4f6f9; min-height: 100vh;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h2 class="font-weight-bold text-dark">Yeni Randevu Oluştur</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                        <h5 class="text-primary font-weight-bold mb-4">
                            <i class="fas fa-calendar-plus mr-2"></i>Randevu Detayları
                        </h5>

                        <?php if($mesaj): ?> 
                            <div class="alert alert-<?php echo $mesaj_tipi; ?> rounded-pill mb-4 shadow-sm border-0 px-4">
                                <i class="fas fa-info-circle mr-2"></i><?php echo $mesaj; ?>
                            </div> 
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted">ARAÇ BİLGİSİ (PLAKA / MODEL)</label>
                                <div class="input-group">
                                    <input type="text" name="arac_bilgisi" class="form-control" 
                                           placeholder="Örn: 34 ABC 123 - Honda Civic"
                                           style="border-radius: 50px; height: 50px; border: 1px solid #dee2e6; padding-left: 20px;" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="small font-weight-bold text-muted">RANDEVU TARİHİ</label>
                                    <input type="date" name="tarih" class="form-control" 
                                           style="border-radius: 50px; height: 50px; border: 1px solid #dee2e6;" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="small font-weight-bold text-muted">RANDEVU SAATİ</label>
                                    <input type="time" name="saat" class="form-control" 
                                           style="border-radius: 50px; height: 50px; border: 1px solid #dee2e6;" required>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted">AÇIKLAMA / ŞİKAYETİNİZ</label>
                                <textarea name="aciklama" class="form-control" rows="4" 
                                          placeholder="Aracınızdaki sorunu kısaca belirtiniz..."
                                          style="border-radius: 20px; border: 1px solid #dee2e6; padding: 15px;"></textarea>
                            </div>

                            <div class="text-right">
                                <button type="submit" name="randevu_kaydet" class="btn btn-primary px-5 shadow font-weight-bold" 
                                        style="border-radius: 50px; height: 55px; font-size: 16px;">
                                    <i class="fas fa-check-circle mr-2"></i>Randevuyu Oluştur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("../includes/footer.php"); ?>