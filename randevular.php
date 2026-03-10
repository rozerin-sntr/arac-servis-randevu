<?php
session_start();
include("../includes/baglanti.php");
if(isset($_GET['status']) && $_GET['status'] == 'silindi'){
    echo '<div class="alert alert-success" role="alert">
            Randevu başarıyla silindi!
          </div>';
}
/** @var mysqli $conn */

// Randevu silme
if(isset($_GET["sil"])){

    $id = intval($_GET["sil"]);

    $stmt = $conn->prepare("DELETE FROM randevular WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: randevular.php");
    exit;
}
if(isset($_GET['yeni_durum']) && isset($_GET['id'])){

$yeni_durum = $_GET['yeni_durum'];

$randevu_id = intval($_GET['id']);


$stmt = $conn->prepare("UPDATE randevular SET durum = ? WHERE id = ?");

$stmt->bind_param("si", $yeni_durum, $randevu_id);


if($stmt->execute()){

header("Location: randevular.php?status=guncellendi");

exit;

}

} 
// Filtre
$filtre = "";

if(isset($_GET["durum"]) && $_GET["durum"] != ""){
    $durum = $_GET["durum"];
    $filtre = "WHERE r.durum = '$durum'";
}

if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "admin"){
    header("Location: ../login.php");
    exit;
}

// Servis atama işlemi
if(isset($_POST["servis_ata"])){

    $randevu_id = $_POST["randevu_id"];
    $servis_id = $_POST["servis_id"];

    $stmt = $conn->prepare("UPDATE randevular SET servis_id=?, durum='onaylandi' WHERE id=?");
    $stmt->bind_param("ii", $servis_id, $randevu_id);
    $stmt->execute();
}

// Randevuları çek
$query = "
SELECT r.*, 
       m.ad AS musteri_adi,
       s.ad AS servis_adi
FROM randevular r
LEFT JOIN kullanicilar m ON r.musteri_id = m.id
LEFT JOIN kullanicilar s ON r.servis_id = s.id
$filtre
ORDER BY r.randevu_tarihi DESC
";

$result = $conn->query($query);

// Servis kullanıcılarını çek
$servisler = mysqli_query($conn, "SELECT id, ad FROM kullanicilar WHERE rol='servis'");
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>



    <div class="content-wrapper p-4">
<div class="d-flex justify-content-between align-items-center mb-4">
<h2 class="font-weight-bold text-dark m-0">Randevu Yönetimi</h2>
<div class="btn-group shadow-sm" style="border-radius: 50px; overflow: hidden;">
<a href="?durum=tumu" class="btn btn-secondary px-4">Tümü</a>
<a href="?durum=beklemede" class="btn btn-warning text-white px-4">Beklemede</a>
<a href="?durum=islemde" class="btn btn-primary px-4">İşlemde</a>
<a href="?durum=tamamlandi" class="btn btn-success px-4">Tamamlandı</a>
</div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3">ID</th>
                        <th class="border-0 py-3">Müşteri</th>
                        <th class="border-0 py-3">Araç</th>
                        <th class="border-0 py-3 text-center">Tarih / Saat</th>
                        <th class="border-0 py-3 text-center">Durum</th>
                        
                        <th class="border-0 px-4 py-3 text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 text-muted">#19</td>
                        <td class="font-weight-bold">Sude Nur Koç</td>
                        <td><span class="badge badge-secondary px-3 py-2" style="border-radius: 50px;">65ABC65</span></td>
                        <td class="text-center small text-muted">2026-03-04<br>21:13:00</td>
                        <td class="text-center">
    <form action="randevular.php" method="POST" class="d-flex justify-content-center align-items-center">
        <input type="hidden" name="randevu_id" value="<?php echo $row['id']; ?>">
        
        <select name="servis_id" class="form-control form-control-sm rounded-pill mr-2" style="width: 120px;" required>
            <option value="">Servis Seç</option>
            <?php
            // Sadece rolü 'servis' olan kullanıcıları çekiyoruz
            $servisler_sorgu = "SELECT id, ad FROM kullanicilar WHERE rol = 'servis'";
            $servisler_sonuc = $conn->query($servisler_sorgu);
            while($servis = $servisler_sonuc->fetch_assoc()):
            ?>
            
                <option value="<?php echo $servis['id']; ?>"><?php echo $servis['ad']; ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit" name="servis_ata" class="btn btn-sm btn-dark rounded-circle" style="width: 30px; height: 30px; padding: 0;">
            <i class="fas fa-plus"></i>
        </button>
    </form>
</td>
                           <div class="dropdown-menu shadow border-0">
    <a class="dropdown-item" href="randevular.php?id=<?php echo $row['id']; ?>&yeni_durum=islemde">İşlemde Yap</a>
<a class="dropdown-item" href="randevular.php?id=<?php echo $row['id']; ?>&yeni_durum=tamamlandi">Tamamlandı yap</a>
</div>
                        
                        
                       <td class="px-4 text-right">
<a href="randevular.php?sil=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Bu randevuyu silmek istediğinize emin misiniz?')">
<i class="fas fa-trash"></i>
</a>
</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>

<?php include("../includes/footer.php"); ?>