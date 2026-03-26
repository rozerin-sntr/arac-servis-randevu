<?php
session_start();
include("../includes/baglanti.php");

// Sadece müşteri rolündekiler girebilir
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
header("Location: ../login.php");
exit;
}

$user_id = $_SESSION["user_id"];

// Müşterinin toplam randevularını ve bekleyen işlemlerini sayalım
$toplam_randevu = $conn->query("SELECT COUNT(*) as sayi FROM randevular WHERE musteri_id = '$user_id'")->fetch_assoc()['sayi'];
$bekleyen_randevu = $conn->query("SELECT COUNT(*) as sayi FROM randevular WHERE musteri_id = '$user_id' AND durum = 'BEKLEMEDE'")->fetch_assoc()['sayi'];

include("../includes/header.php");
include("../includes/sidebar.php");
?>

<div class="content-wrapper" style="background-color: #f4f6f9; min-height: 100vh;">
<div class="content-header">
<div class="container-fluid">
<div class="row mb-4">
<div class="col-sm-6">
<h2 class="font-weight-bold text-dark">Hoşgeldiniz 👋</h2>
<p class="text-muted">Aracınızın servis süreçlerini buradan takip edebilirsiniz.</p>
</div>
</div>
</div>
</div>

</div>

<?php include("../includes/footer.php"); ?>