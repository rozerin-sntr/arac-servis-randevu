<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../includes/baglanti.php");

if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "admin"){
header("Location: ../login.php"); exit;
}

$id = $_GET['id'];
$sorgu = $conn->query("SELECT * FROM randevular WHERE id = $id");
$randevu = $sorgu->fetch_assoc();

include("../includes/header.php");
include("../includes/sidebar.php");
?>

<div class="content-wrapper p-4">
<div class="card card-primary card-outline shadow">
<div class="card-header">
<h3 class="card-title">Randevu Düzenle (#<?php echo $id; ?>)</h3>
</div>
<form action="randevu_islem.php" method="POST">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<div class="card-body">
<div class="form-group">
<label>Araç Bilgisi</label>
<input type="text" name="arac_bilgisi" class="form-control" value="<?php echo $randevu['arac_bilgisi']; ?>">
</div>
<div class="form-group">
<label>Durum</label>
<select name="durum" class="form-control">
<option value="beklemede" <?php if($randevu['durum'] == 'beklemede') echo 'selected'; ?>>Beklemede</option>
<option value="islemde" <?php if($randevu['durum'] == 'islemde') echo 'selected'; ?>>İşlemde</option>
<option value="tamamlandi" <?php if($randevu['durum'] == 'tamamlandi') echo 'selected'; ?>>Tamamlandı</option>
</select>
</div>
</div>
<div class="card-footer d-flex justify-content-between">
<a href="dashboard.php" class="btn btn-secondary shadow-sm"><i class="fas fa-times mr-1"></i> Vazgeç</a>
<button type="submit" name="guncelle" class="btn btn-success px-4 shadow"><i class="fas fa-save mr-1"></i> Değişiklikleri Kaydet</button>
</div>
</form>
</div>
</div>

<?php include("../includes/footer.php"); ?>