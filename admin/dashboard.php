<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../includes/baglanti.php");

if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "admin"){
header("Location: ../login.php"); exit;
}

$toplam_kullanici = $conn->query("SELECT COUNT(*) AS sayi FROM kullanicilar")->fetch_assoc()["sayi"];
$toplam_musteri = $conn->query("SELECT COUNT(*) AS sayi FROM kullanicilar WHERE rol='musteri'")->fetch_assoc()["sayi"];
$toplam_servis = $conn->query("SELECT COUNT(*) AS sayi FROM kullanicilar WHERE rol='servis'")->fetch_assoc()["sayi"];
$toplam_randevu = $conn->query("SELECT COUNT(*) AS sayi FROM randevular")->fetch_assoc()["sayi"];
$bekleyen = $conn->query("SELECT COUNT(*) AS sayi FROM randevular WHERE durum='beklemede'")->fetch_assoc()["sayi"];
$tamamlanan = $conn->query("SELECT COUNT(*) AS sayi FROM randevular WHERE durum='tamamlandi'")->fetch_assoc()["sayi"];

$son_randevular = $conn->query("SELECT r.id, r.randevu_tarihi, r.randevu_saati, r.durum, r.arac_bilgisi, m.ad AS musteri_ad, s.ad AS servis_ad FROM randevular r LEFT JOIN kullanicilar m ON r.musteri_id = m.id LEFT JOIN kullanicilar s ON r.servis_id = s.id ORDER BY r.id DESC LIMIT 5");
?>

<?php include("../includes/header.php"); ?>

<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper">
<div class="content-header"><div class="container-fluid"><h1>Admin Dashboard</h1></div></div>
<section class="content"><div class="container-fluid">
<div class="row">
<div class="col-lg-2 col-6"><div class="small-box bg-primary"><div class="inner"><h3><?php echo $toplam_kullanici; ?></h3><p>Kullanıcı</p></div><div class="icon"><i class="fas fa-users"></i></div></div></div>
<div class="col-lg-2 col-6"><div class="small-box bg-success"><div class="inner"><h3><?php echo $toplam_musteri; ?></h3><p>Müşteri</p></div><div class="icon"><i class="fas fa-user-tie"></i></div></div></div>
<div class="col-lg-2 col-6"><div class="small-box bg-warning"><div class="inner"><h3><?php echo $toplam_servis; ?></h3><p>Servis</p></div><div class="icon"><i class="fas fa-tools"></i></div></div></div>
<div class="col-lg-2 col-6"><div class="small-box bg-info"><div class="inner"><h3><?php echo $toplam_randevu; ?></h3><p>Randevu</p></div><div class="icon"><i class="fas fa-calendar-check"></i></div></div></div>
<div class="col-lg-2 col-6"><div class="small-box bg-danger"><div class="inner"><h3><?php echo $bekleyen; ?></h3><p>Bekleyen</p></div><div class="icon"><i class="fas fa-clock"></i></div></div></div>
<div class="col-lg-2 col-6"><div class="small-box bg-purple" style="background:#6f42c1!important;color:#fff"><div class="inner"><h3><?php echo $tamamlanan; ?></h3><p>Biten</p></div><div class="icon"><i class="fas fa-check"></i></div></div></div>
</div>
<div class="card mt-4 shadow-sm"><div class="card-header bg-white border-bottom-0"><h3><i class="fas fa-history mr-2 text-primary"></i>Son 5 Randevu</h3></div><div class="card-body p-0"><div class="table-responsive">
<table class="table table-striped table-hover m-0">
<thead class="thead-light"><tr><th>ID</th><th>Müşteri</th><th>Araç</th><th>Servis</th><th>Tarih</th><th>Durum</th><th class="text-center">İşlem</th></tr></thead>
<tbody><?php while($row = $son_randevular->fetch_assoc()){
$b = ($row["durum"]=="beklemede")?"badge-warning":(($row["durum"]=="islemde")?"badge-primary":"badge-success"); ?>
<tr><td>#<?php echo $row["id"]; ?></td><td><?php echo $row["musteri_ad"]; ?></td><td><?php echo $row["arac_bilgisi"]; ?></td><td><?php echo $row["servis_ad"]?:"-"; ?></td><td><?php echo $row["randevu_tarihi"]; ?></td>
<td><span class="badge <?php echo $b; ?> px-2 py-1"><?php echo strtoupper($row["durum"]); ?></span></td>
<td class=<a href="javascript:void(0);" onclick="silOnayla(<?php echo $row['id']; ?>)" class="btn btn-sm btn-danger rounded-pill shadow-sm">
    <i class="fas fa-trash"></i>
</a></tr>
<?php } ?></tbody>
</table>
</div></div></div>
</div></section>
</div>
<script src=""></script>

<script>
function silOnayla(id) {
Swal.fire({
title: 'Emin misiniz?',
text: "Bu randevu kaydı kalıcı olarak silinecektir!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#d33',
cancelButtonColor: '#3085d6',
confirmButtonText: 'Evet, sil!',
cancelButtonText: 'Vazgeç',
reverseButtons: true
}).then((result) => {
if (result.isConfirmed) {
// Onay verilirse silme sayfasına yönlendir
window.location.href = 'randevu_sil.php?id=' + id;
}
})
}
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// URL'deki 'durum' bilgisini yakalıyoruz
const urlParams = new URLSearchParams(window.location.search);
const durum = urlParams.get('durum');

// BAŞARI DURUMU (Küçük Yeşil Toast)
if (durum === 'basarili') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    Toast.fire({
        icon: 'success',
        title: 'Randevu başarıyla güncellendi!'
    });
} 

// HATA DURUMU (Büyük Kırmızı Alert Box)
else if (durum === 'hata') {
    Swal.fire({
        icon: 'error',
        title: 'İşlem Başarısız!',
        text: 'Veritabanına kaydedilirken bir sorun oluştu.',
        confirmButtonText: 'Tamam',
        confirmButtonColor: '#d33'
    });
}
</script>