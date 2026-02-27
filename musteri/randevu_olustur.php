<?php
session_start();
include("../includes/baglanti.php");

/** @var mysqli $conn */

// Sadece müşteri girebilsin
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
    header("Location: ../login.php");
    exit;
}

if(isset($_POST["randevu_olustur"])){

    $musteri_id = $_SESSION["user_id"];
    $arac_bilgisi = $_POST["arac_bilgisi"];
    $tarih = $_POST["tarih"];
    $saat = $_POST["saat"];
    $aciklama = $_POST["aciklama"];

    $stmt = $conn->prepare("INSERT INTO randevular 
        (musteri_id, arac_bilgisi, randevu_tarihi, randevu_saati, aciklama) 
        VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("issss", $musteri_id, $arac_bilgisi, $tarih, $saat, $aciklama);

    if($stmt->execute()){
        $basarili = "Randevu başarıyla oluşturuldu!";
    } else {
        $hata = "Bir hata oluştu!";
    }
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper p-3">
    <h2>Randevu Oluştur</h2>

    <?php
    if(isset($hata)){
        echo "<p style='color:red;'>$hata</p>";
    }

    if(isset($basarili)){
        echo "<p style='color:green;'>$basarili</p>";
    }
    ?>

    <form method="POST">
        <label>Araç Bilgisi (Plaka / Model)</label><br>
        <input type="text" name="arac_bilgisi" required><br><br>

        <label>Randevu Tarihi</label><br>
        <input type="date" name="tarih" required><br><br>

        <label>Randevu Saati</label><br>
        <input type="time" name="saat" required><br><br>

        <label>Açıklama</label><br>
        <textarea name="aciklama"></textarea><br><br>

        <button type="submit" name="randevu_olustur">Randevu Oluştur</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>