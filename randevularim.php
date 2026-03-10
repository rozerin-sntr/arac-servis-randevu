<?php
session_start();
include("../includes/baglanti.php");

/** @var mysqli $conn */

// Güvenlik Kontrolü
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

/** * ÖNEMLİ: Eğer veritabanında sütun adın 'tarih' değilse, 
 * aşağıdaki sorguda 'tarih' yazan yeri kendi sütun adınla değiştir.
 */
$sql = "SELECT * FROM randevular WHERE musteri_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $randevular = $stmt->get_result();
} else {
    // Sütun hatası durumunda kullanıcıya bilgi veriyoruz
    die("<div style='padding:20px; background:#fff5f5; color:#c53030; border:1px solid #feb2b2; border-radius:10px; margin:20px;'>
            <strong>Veritabanı Yapı Hatası:</strong> Randevular tablosunda sıralama yapılacak sütun bulunamadı. 
            Lütfen veritabanındaki sütun isimlerini kontrol edin.
         </div>");
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper" style="background-color: #f4f6f9; min-height: 100vh;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-6"><h2 class="font-weight-bold text-dark">Randevularım</h2></div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h3 class="card-title font-weight-bold text-primary">
                        <i class="fas fa-calendar-check mr-2"></i>Randevu Listesi
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3 text-muted small font-weight-bold"># ID</th>
                                    <th class="border-0 py-3 text-muted small font-weight-bold">ARAÇ BİLGİSİ</th>
                                    <th class="border-0 py-3 text-muted small font-weight-bold text-center">DURUM</th>
                                    <th class="border-0 px-4 py-3 text-muted small font-weight-bold text-right">İŞLEM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($randevular && $randevular->num_rows > 0): ?>
                                    <?php while($row = $randevular->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-4 py-4 align-middle text-muted">#<?php echo $row["id"]; ?></td>
                                        <td class="py-4 align-middle font-weight-bold">
                                            <?php echo isset($row["arac_bilgisi"]) ? $row["arac_bilgisi"] : "Belirtilmemiş"; ?>
                                        </td>
                                        <td class="py-4 align-middle text-center">
                                            <?php 
                                                $durum = isset($row["durum"]) ? $row["durum"] : "beklemede";
                                                $badge = "badge-secondary";
                                                if($durum == "beklemede") $badge = "badge-warning text-dark";
                                                elseif($durum == "onaylandi") $badge = "badge-primary";
                                                elseif($durum == "tamamlandi") $badge = "badge-success";
                                            ?>
                                            <span class="badge <?php echo $badge; ?> rounded-pill px-3 py-2">
                                                <?php echo strtoupper($durum); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 align-middle text-right">
                                            <a href="randevu_detay.php?id=<?php echo $row['id']; ?>" class="btn btn-light btn-sm rounded-pill px-3 shadow-none border">Detay</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Henüz kayıtlı bir randevunuz bulunmuyor.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("../includes/footer.php"); ?>