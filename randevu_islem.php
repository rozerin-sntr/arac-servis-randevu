<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Veritabanı bağlantısı
include("../includes/baglanti.php");

if (isset($_POST['guncelle'])) {
    // 1. Formdan gelen verileri değişkenlere atıyoruz
    $id = $_POST['id'];
    $arac_bilgisi = $_POST['arac_bilgisi'];
    $durum = $_POST['durum'];

    // 2. İŞTE EKSİK OLAN ASIL KOD: Veritabanını güncelleme sorgusu
    $sorgu = "UPDATE randevularr SET arac_bilgisi = '$arac_bilgisi', durum = '$durum' WHERE id = '$id'";
    
    // 3. Sorguyu çalıştır ve sonucuna göre yönlendir
    if ($conn->query($sorgu)) {
    header("Location: dashboard.php?durum=basarili");
    exit;
} else {
    // Sorgu başarısız olursa hata parametresiyle gönder
    header("Location: dashboard.php?durum=hata");
    exit;
}
}
ob_end_flush();
?>