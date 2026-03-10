<?php
session_start();
include("../includes/baglanti.php");

if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
    header("Location: ../login.php"); exit;
}

$id = $_GET['id'];
$sorgu = $conn->prepare("SELECT * FROM randevular WHERE id = ? AND musteri_id = ?");
$sorgu->bind_param("ii", $id, $_SESSION["user_id"]);
$sorgu->execute();
$randevu = $sorgu->get_result()->fetch_assoc();

if(!$randevu) { die("Randevu bulunamadı!"); }

// Veritabanındaki farklı sütun isimlerini yakalayan akıllı kontrol
$tarih_verisi = $randevu['tarih'] ?? $randevu['randevu_tarihi'] ?? null;
$saat_verisi = $randevu['saat'] ?? $randevu['randevu_saati'] ?? null;
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper" style="background-color: #f4f6f9; min-height: 100vh; padding: 20px;">
    <section class="content-header">
        <div class="container-fluid">
            <h2 class="font-weight-bold text-dark mb-4">Randevu Detayı #<?php echo $id; ?></h2>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; background-color: #ffffff;">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary font-weight-bold mb-4"><i class="fas fa-car mr-2"></i>Araç Bilgileri</h5>
                        <p class="mb-3"><strong>Araç Plaka/Model:</strong> <?php echo $randevu['arac_bilgisi']; ?></p>
                        
                        <p class="mb-3"><strong>Tarih:</strong> 
                            <?php echo $tarih_verisi ? date("d.m.Y", strtotime($tarih_verisi)) : "<span class='text-danger'>Veritabanında 'tarih' sütununu kontrol edin!</span>"; ?>
                        </p>
                        
                        <p class="mb-3"><strong>Saat:</strong> 
                            <?php echo $saat_verisi ? $saat_verisi : "<span class='text-danger'>Veritabanında 'saat' sütununu kontrol edin!</span>"; ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h5 class="text-primary font-weight-bold mb-4"><i class="fas fa-info-circle mr-2"></i>Müşteri Notu</h5>
                        <div class="p-3 bg-light" style="border-radius: 15px; border-left: 5px solid #007bff; min-height: 120px;">
                            <?php echo !empty($randevu['aciklama']) ? $randevu['aciklama'] : "Açıklama belirtilmemiş."; ?>
                        </div>
                        <div class="mt-4">
                            <?php 
                                $durum = strtoupper($randevu['durum']);
                                $color = ($durum == 'BEKLEMEDE') ? 'warning text-dark' : 'primary';
                            ?>
                            <span class="badge badge-<?php echo $color; ?> px-4 py-2 rounded-pill shadow-sm" style="font-size: 14px;">
                                DURUM: <?php echo $durum; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-5">
                    <a href="randevularim.php" class="btn btn-secondary rounded-pill px-5 shadow-sm font-weight-bold">
                        <i class="fas fa-list mr-2"></i>Listeye Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("../includes/footer.php"); ?>