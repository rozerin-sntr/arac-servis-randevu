<?php
session_start();
include("../includes/baglanti.php");

// Servis yetki kontrolü
if(!isset($_SESSION["rol"]) || $_SESSION["rol"] != "servis"){
    header("Location: ../login.php"); exit;
}

// Müşteri adıyla beraber tüm randevuları çekiyoruz
$sql = "SELECT r.*, k.ad as musteri_ad FROM randevular r 
       LEFT JOIN kullanicilar k ON r.musteri_id = k.id 
        ORDER BY r.id DESC";
$randevular = $conn->query($sql);
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper" style="background-color: #f4f6f9; min-height: 100vh;">
    <section class="content-header">
        <div class="container-fluid">
            <h2 class="font-weight-bold text-dark mb-4">Servis Yönetim Paneli</h2>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h3 class="card-title font-weight-bold text-primary">
                        <i class="fas fa-tools mr-2"></i>Bekleyen Randevu İşlemleri
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3 text-muted small font-weight-bold">ID</th>
                                    <th class="border-0 py-3 text-muted small font-weight-bold">MÜŞTERİ</th>
                                    <th class="border-0 py-3 text-muted small font-weight-bold">ARAÇ</th>
                                    <th class="border-0 py-3 text-muted small font-weight-bold">TARİH/SAAT</th>
                                    <th class="border-0 py-3 text-muted small font-weight-bold text-center">DURUM</th>
                                    <th class="border-0 px-4 py-3 text-muted small font-weight-bold text-right">İŞLEM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $randevular->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-4 py-4 align-middle">#<?php echo $row["id"]; ?></td>
                                    <td class="py-4 align-middle font-weight-bold"><?php echo $row["musteri_ad"]; ?></td>
                                    <td class="py-4 align-middle text-muted"><?php echo isset($row["arac_bilgisi"]) ? $row["arac_bilgisi"] : "Belirtilmedi"; ?></td>
                                    <td class="py-4 align-middle">
    <span class="font-weight-bold text-dark d-block">
        <?php echo isset($row['randevu_tarihi']) ? $row['randevu_tarihi'] : "Tarih Belirtilmedi"; ?>
    </span>
    <small class="text-primary font-weight-bold">
        <?php echo isset($row['randevu_saati']) ? $row['randevu_saati'] : "Saat Belirtilmedi"; ?>
    </small>
</td>
                                    <td class="py-4 align-middle text-center">
                                        <?php 
                                            $durum = $row["durum"];
                                            $badge = ($durum == 'beklemede') ? 'warning text-dark' : 'primary';
                                        ?>
                                        <span class="badge badge-<?php echo $badge; ?> rounded-pill px-3 py-2 shadow-sm">
                                            <?php echo strtoupper($durum); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 align-middle text-right">
    <a href="randevu_guncelle.php?id=<?php echo $row['id']; ?>" 
   class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
    <i class="fas fa-edit mr-1"></i> Güncelle
</a>
    </td>
                                        
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("../includes/footer.php"); ?>