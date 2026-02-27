<?php
session_start();
include("../includes/baglanti.php");
/** @var mysqli $conn */
if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
    header("Location: ../login.php");
    exit;
}

$musteri_id = $_SESSION["user_id"];
// Randevu iptal işlemi
if(isset($_GET["iptal"])){

    $randevu_id = intval($_GET["iptal"]);

    $stmt = $conn->prepare("
        UPDATE randevular 
        SET durum='iptal'
        WHERE id=? AND musteri_id=? AND durum='beklemede'
    ");
    $stmt->bind_param("ii", $randevu_id, $musteri_id);
    $stmt->execute();

    header("Location: randevularim.php");
    exit;
}

// Sadece kendi randevularını çek
$stmt = $conn->prepare("
    SELECT *
    FROM randevular
    WHERE musteri_id = ?
    ORDER BY randevu_tarihi DESC
");
$stmt->bind_param("i", $musteri_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper p-3">
    <h2>Randevularım</h2>

    <table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Araç</th>
        <th>Tarih</th>
        <th>Saat</th>
        <th>Durum</th>
        <th>İşlem</th>
    </tr>

    <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["arac_bilgisi"]; ?></td>
            <td><?php echo $row["randevu_tarihi"]; ?></td>
            <td><?php echo $row["randevu_saati"]; ?></td>
            <td><?php echo $row["durum"]; ?></td>
            <td>
                <?php if($row["durum"] == "beklemede") { ?>
                    <a href="?iptal=<?php echo $row["id"]; ?>" 
                       onclick="return confirm('Randevuyu iptal etmek istiyor musunuz?')">
                       İptal Et
                    </a>
                <?php } else { ?>
                    -
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
</div>

<?php include("../includes/footer.php"); ?>