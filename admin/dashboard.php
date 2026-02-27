<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../includes/baglanti.php");
/** @var mysqli $conn */


if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "admin"){
    header("Location: ../login.php");
    exit;
}
// Toplam kullanıcı
$toplam_kullanici = $conn->query("SELECT COUNT(*) AS sayi FROM kullanicilar")->fetch_assoc()["sayi"];

// Toplam müşteri
$toplam_musteri = $conn->query("SELECT COUNT(*) AS sayi FROM kullanicilar WHERE rol='musteri'")->fetch_assoc()["sayi"];

// Toplam servis
$toplam_servis = $conn->query("SELECT COUNT(*) AS sayi FROM kullanicilar WHERE rol='servis'")->fetch_assoc()["sayi"];

// Toplam randevu
$toplam_randevu = $conn->query("SELECT COUNT(*) AS sayi FROM randevular")->fetch_assoc()["sayi"];

// Bekleyen randevu
$bekleyen = $conn->query("SELECT COUNT(*) AS sayi FROM randevular WHERE durum='beklemede'")->fetch_assoc()["sayi"];

// Tamamlanan randevu
$tamamlanan = $conn->query("SELECT COUNT(*) AS sayi FROM randevular WHERE durum='tamamlandi'")->fetch_assoc()["sayi"];

// Son 5 randevu
$son_randevular = $conn->query("
    SELECT r.id, r.randevu_tarihi, r.randevu_saati, r.durum, 
           r.arac_bilgisi,
           m.ad AS musteri_ad, 
           s.ad AS servis_ad
    FROM randevular r
    LEFT JOIN kullanicilar m ON r.musteri_id = m.id
    LEFT JOIN kullanicilar s ON r.servis_id = s.id
    ORDER BY r.id DESC
    LIMIT 5
");

?>



<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper p-3">
    <h2>Admin Dashboard</h2>

    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:20px;">

        <div style="background:#007bff;color:white;padding:20px;width:200px;border-radius:10px;">
            <h4>Toplam Kullanıcı</h4>
            <h2><?php echo $toplam_kullanici; ?></h2>
        </div>

        <div style="background:#28a745;color:white;padding:20px;width:200px;border-radius:10px;">
            <h4>Müşteri</h4>
            <h2><?php echo $toplam_musteri; ?></h2>
        </div>

        <div style="background:#ffc107;color:black;padding:20px;width:200px;border-radius:10px;">
            <h4>Servis</h4>
            <h2><?php echo $toplam_servis; ?></h2>
        </div>

        <div style="background:#17a2b8;color:white;padding:20px;width:200px;border-radius:10px;">
            <h4>Toplam Randevu</h4>
            <h2><?php echo $toplam_randevu; ?></h2>
        </div>

        <div style="background:#dc3545;color:white;padding:20px;width:200px;border-radius:10px;">
            <h4>Bekleyen</h4>
            <h2><?php echo $bekleyen; ?></h2>
        </div>

        <div style="background:#6f42c1;color:white;padding:20px;width:200px;border-radius:10px;">
            <h4>Tamamlanan</h4>
            <h2><?php echo $tamamlanan; ?></h2>
        </div>

    </div>


    <h3 style="margin-top:40px;">Son 5 Randevu</h3>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Müşteri</th>
        <th>Araç</th>
        <th>Servis</th>
        <th>Tarih</th>
        <th>Saat</th>
        <th>Durum</th>
    </tr>

    <?php while($row = $son_randevular->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["musteri_ad"]; ?></td>
            <td><?php echo $row["arac_bilgisi"]; ?></td>
            <td><?php echo $row["servis_ad"]; ?></td>
            <td><?php echo $row["randevu_tarihi"]; ?></td>
            <td><?php echo $row["randevu_saati"]; ?></td>
            <td><?php echo $row["durum"]; ?></td>
        </tr>
    <?php } ?>


</div>


</table>

<?php include("../includes/footer.php"); ?>