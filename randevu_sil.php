<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("../includes/baglanti.php");

if (isset($_GET['id'])) {

    $id = intval($_GET['id']); // güvenlik için integer yaptık

    $sil = mysqli_query($conn, "DELETE FROM randevular WHERE id=$id");

    if ($sil) {
        header("Location: randevular.php?durum=silindi");
exit;
        exit;
    } else {
        echo "Silme işlemi başarısız: " . mysqli_error($conn);
    }

} else {
    echo "HATA: Silinecek bir ID bulunamadı!";
}
?>