<?php
session_start();
include("../includes/baglanti.php");

/** @var mysqli $conn */

// Admin kontrolü
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "admin"){
    header("Location: ../login.php");
    exit;
}

// Kullanıcı silme işlemi
if(isset($_GET["sil"])){

    $sil_id = intval($_GET["sil"]);

    // Admin kendini silemesin
    if($sil_id != $_SESSION["user_id"]){

        $stmt = $conn->prepare("DELETE FROM kullanicilar WHERE id=?");
        $stmt->bind_param("i", $sil_id);
        $stmt->execute();
    }

    header("Location: kullanicilar.php");
    exit;
}

// Rol güncelleme işlemi
if(isset($_POST["rol_degistir"])){

    $kullanici_id = intval($_POST["kullanici_id"]);
    $yeni_rol = $_POST["yeni_rol"];

    // Admin kendini rol değiştiremesin
    if($kullanici_id != $_SESSION["user_id"]){

        $stmt = $conn->prepare("UPDATE kullanicilar SET rol=? WHERE id=?");
        $stmt->bind_param("si", $yeni_rol, $kullanici_id);
        $stmt->execute();
    }

    header("Location: kullanicilar.php");
    exit;
}


// Kullanıcıları çek
if(isset($_GET["arama"]) && $_GET["arama"] != ""){

    $arama = "%" . $_GET["arama"] . "%";

    $stmt = $conn->prepare("SELECT id, ad, email, rol 
                            FROM kullanicilar 
                            WHERE ad LIKE ? OR email LIKE ?");
    $stmt->bind_param("ss", $arama, $arama);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    $sql = "SELECT id, ad, email, rol FROM kullanicilar";
    $result = mysqli_query($conn, $sql);

}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-4 align-items-center">
<div class="col-sm-6">
<h1 class="font-weight-bold text-dark"><i class="fas fa-users-cog mr-2"></i>Kullanıcı Yönetimi</h1>
</div>
<div class="col-sm-6">
<form method="GET" action="">
    <div style="display:flex; gap:5px;">
        <input type="text" name="arama" class="form-control" placeholder="İsim veya E-posta ara..."
        value="<?= isset($_GET['arama']) ? $_GET['arama'] : '' ?>">

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>
</div>
</div>
</div>
</section>

<section class="content">
<div class="container-fluid">
<div class="card border-0 shadow" style="border-radius: 20px;">
<div class="card-body p-0">
<div class="table-responsive">
<table class="table table-hover align-middle m-0" style="min-width: 800px;">
<thead class="bg-light">
<tr>
<th class="border-0 px-4 py-3 text-muted" style="width: 100px;"># ID</th>
<th class="border-0 py-3">Kullanıcı Bilgileri</th>
<th class="border-0 py-3">E-posta Adresi</th>
<th class="border-0 py-3 text-center">Yetki / Rol</th>
<th class="border-0 px-4 py-3 text-right">Yönetim</th>
</tr>
</thead>

<tbody>
<?php
// Veritabanından tüm kullanıcıları çekiyoruz


if(isset($_GET["arama"]) && $_GET["arama"] != ""){

    $arama = $_GET["arama"];

    $sql = "SELECT * FROM kullanicilar 
            WHERE ad LIKE '%$arama%' 
            OR email LIKE '%$arama%'";

}else{

    $sql = "SELECT * FROM kullanicilar";

}

$kullanicilar = $conn->query($sql);



while($row = $kullanicilar->fetch_assoc()):
// İsimden baş harfleri alalım (Örn: Rozerin Santor -> RS)
$ad_parcalari = explode(' ', $row['ad']);
$basharf1 = mb_substr($ad_parcalari[0], 0, 1);
$basharf2 = isset($ad_parcalari[1]) ? mb_substr($ad_parcalari[1], 0, 1) : '';
$avatar_metni = strtoupper($basharf1 . $basharf2);
?>
<tr style="transition: 0.3s; border-bottom: 1px solid #f4f4f4;">
<td class="px-4 text-muted font-weight-bold">#<?php echo $row['id']; ?></td>
<td class="py-3">
<div class="d-flex align-items-center">
<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3 shadow-sm"
style="width: 45px; height: 45px; font-weight: bold; font-size: 14px;">
<?php echo $avatar_metni; ?>
</div>
<div>
<div class="font-weight-bold mb-0" style="color: #343a40;"><?php echo $row['ad']; ?></div>
<?php if($row['rol'] == 'admin'): ?>
<small class="text-success"><i class="fas fa-circle mr-1" style="font-size: 8px;"></i> Aktif Yönetici</small>
<?php endif; ?>
</div>
</div>
</td>
<td style="color: #007bff;"><?php echo $row['email']; ?></td>
<td class="text-center">
<?php if($row['rol'] == 'admin'): ?>
<span class="badge badge-dark px-3 py-2" style="border-radius: 50px; font-size: 11px;">ADMIN</span>
<?php else: ?>
<select class="form-control form-control-sm border-0 bg-light shadow-none mx-auto" style="border-radius: 50px; width: 120px;">
<option <?php echo ($row['rol'] == 'servis') ? 'selected' : ''; ?>>servis</option>
<option <?php echo ($row['rol'] == 'musteri') ? 'selected' : ''; ?>>musteri</option>
</select>
<?php endif; ?>
</td>
<td class="px-4 text-right">
<?php if($row['rol'] != 'admin'): ?>
<a href="kullanici_sil.php?id=<?= $row['id'] ?>" 
onclick="return confirm('Bu kullanıcı silinsin mi?')"
class="btn btn-sm btn-outline-danger rounded-circle">
<i class="fas fa-trash"></i>
</a>
<?php else: ?>
<span class="text-muted small">Düzenlenemez</span>
<?php endif; ?>
</td>
</tr>

<?php endwhile; ?>

</tbody>

</section>
</div>
<?php include("../includes/footer.php"); ?>