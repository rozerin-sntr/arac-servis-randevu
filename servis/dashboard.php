<?php
session_start();
include("../includes/baglanti.php");
/** @var mysqli $conn */
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "servis"){
    header("Location: ../login.php");
    exit;
}

$servis_id = $_SESSION["user_id"];

// Durum güncelleme işlemi
if(isset($_POST["durum_guncelle"])){

    $randevu_id = $_POST["randevu_id"];
    $yeni_durum = $_POST["yeni_durum"];

    $stmt = $conn->prepare("
        UPDATE randevular 
        SET durum=? 
        WHERE id=? AND servis_id=?
    ");
    $stmt->bind_param("sii", $yeni_durum, $randevu_id, $servis_id);
    $stmt->execute();
}

// Sadece kendisine atanan randevuları çek
$stmt = $conn->prepare("
    SELECT r.*, k.ad AS musteri_adi
    FROM randevular r
    JOIN kullanicilar k ON r.musteri_id = k.id
    WHERE r.servis_id = ?
    ORDER BY r.randevu_tarihi DESC
");
$stmt->bind_param("i", $servis_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper p-3">
    <h2>Bana Atanan Randevular</h2>

    <table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Müşteri</th>
        <th>Araç</th>
        <th>Tarih</th>
        <th>Saat</th>
        <th>Durum</th>
        <th>Güncelle</th>
    </tr>

    <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["musteri_adi"]; ?></td>
            <td><?php echo $row["arac_bilgisi"]; ?></td>
            <td><?php echo $row["randevu_tarihi"]; ?></td>
            <td><?php echo $row["randevu_saati"]; ?></td>
            <td><?php echo $row["durum"]; ?></td>
            <td>
                <?php if($row["durum"] != "tamamlandi") { ?>
                    <form method="POST">
                        <input type="hidden" name="randevu_id" value="<?php echo $row["id"]; ?>">
                        <select name="yeni_durum" required>
                            <option value="islemde">İşlemde</option>
                            <option value="tamamlandi">Tamamlandı</option>
                        </select>
                        <button type="submit" name="durum_guncelle">Güncelle</button>
                    </form>
                <?php } else { ?>
                    ✔ Tamamlandı
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

    </table>
</div>

<?php include("../includes/footer.php"); ?>