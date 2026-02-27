<?php
session_start();
include("../includes/baglanti.php");
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

<div class="content-wrapper p-3">
    <h2>Randevular</h2>

    <div style="margin-bottom:15px;">
    <a href="randevular.php" class="btn btn-secondary">Tümü</a>
    <a href="randevular.php?durum=beklemede" class="btn btn-warning">Beklemede</a>
    <a href="randevular.php?durum=islemde" class="btn btn-info">İşlemde</a>
    <a href="randevular.php?durum=tamamlandi" class="btn btn-success">Tamamlandı</a>
    </div>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Müşteri</th>
            <th>Araç</th>
            <th>Tarih</th>
            <th>Saat</th>
            <th>Durum</th>
            <th>Servis Ata</th>
            <th>Sil</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["musteri_adi"]; ?></td>
                <td><?php echo $row["arac_bilgisi"]; ?></td>
                <td><?php echo $row["randevu_tarihi"]; ?></td>
                <td><?php echo $row["randevu_saati"]; ?></td>
                <td><?php echo $row["durum"]; ?></td>
                <td>
                    <?php if($row["servis_id"] == NULL) { ?>
                        <form method="POST">
                            <input type="hidden" name="randevu_id" value="<?php echo $row["id"]; ?>">

                            <select name="servis_id" required>
                                <option value="">Servis Seç</option>
                                <?php 
                                mysqli_data_seek($servisler, 0);
                                while($servis = mysqli_fetch_assoc($servisler)) { ?>
                                    <option value="<?php echo $servis["id"]; ?>">
                                        <?php echo $servis["ad"]; ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <button type="submit" name="servis_ata">Ata</button>
                        </form>
                    <?php } else { ?>
                        Atandı
                    <?php } ?>
                </td>
                <td>
                    <a href="randevular.php?sil=<?php echo $row["id"]; ?>"
                    onclick="return confirm('Bu randevu silinsin mi?');"
                    style="color:red;">
                    Sil
                    </a>
                </td>
            </tr>

        <?php } ?>

    </table>
</div>

<?php include("../includes/footer.php"); ?>