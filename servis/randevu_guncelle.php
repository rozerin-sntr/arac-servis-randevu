<?php
session_start();
include("../includes/baglanti.php");

// Servis yetki kontrolü
if(!isset($_SESSION["rol"]) || $_SESSION["rol"] != "servis"){
    header("Location: ../login.php"); exit;
}

// URL'den gelen ID'yi yakalıyoruz
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Randevu mevcut mu kontrol edelim ve bilgilerini çekelim
$sorgu = $conn->prepare("SELECT r.*, k.ad FROM randevular r JOIN kullanicilar k ON r.musteri_id = k.id WHERE r.id = ?");
$sorgu->bind_param("i", $id);
$sorgu->execute();
$randevu = $sorgu->get_result()->fetch_assoc();

// Eğer form gönderildiyse güncelleme işlemini yap
if(isset($_POST['guncelle_btn'])){
    $yeni_durum = $_POST['durum'];
    $guncelle = $conn->prepare("UPDATE randevular SET durum = ? WHERE id = ?");
    $guncelle->bind_param("si", $yeni_durum, $id);
    
    if($guncelle->execute()){
        // Başarılıysa dashboard'a geri dön
        header("Location: dashboard.php?status=ok");
        exit;
    }
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper" style="background-color: #f4f6f9; min-height: 100vh; padding: 20px;">
    <section class="content">
        <div class="container-fluid">
            <h2 class="font-weight-bold mb-4">Randevu Durumunu Güncelle (#<?php echo $id; ?>)</h2>
            
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 600px;">
                <p><strong>Müşteri:</strong> <?php echo $randevu['ad']; ?></p>
                <p><strong>Araç:</strong> <?php echo $randevu['arac_bilgisi']; ?></p>
                
                <form method="POST">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">İşlem Durumu</label>
                        <select name="durum" class="form-control rounded-pill border-2">
                            <option value="beklemede" <?php echo ($randevu['durum'] == 'beklemede') ? 'selected' : ''; ?>>BEKLEMEDE</option>
                            <option value="onaylandi" <?php echo ($randevu['durum'] == 'onaylandi') ? 'selected' : ''; ?>>ONAYLANDI</option>
                            <option value="tamamlandi" <?php echo ($randevu['durum'] == 'tamamlandi') ? 'selected' : ''; ?>>TAMAMLANDI</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="guncelle_btn" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        Güncellemeyi Tamamla
                    </button>
                    <a href="dashboard.php" class="btn btn-light rounded-pill px-4 ml-2">Vazgeç</a>
                </form>
            </div>
        </div>
    </section>
</div>

<?php include("../includes/footer.php"); ?>