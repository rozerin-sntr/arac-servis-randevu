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

<div class="content-wrapper p-3">
    <h2>Kullanıcı Listesi</h2>

    <form method="GET" style="margin-bottom:15px;">
        <input type="text" name="arama" placeholder="Ad veya email ara..."
           value="<?php echo isset($_GET['arama']) ? $_GET['arama'] : ''; ?>">
        <button type="submit">Ara</button>
    </form>

    <div class="card">
        <div class="card-body">

            <table border="1" cellpadding="10">
                <tr>
                    <th>ID</th>
                    <th>Ad</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>İşlem</th>
                </tr>

                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["ad"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td>
                            <?php if($row["id"] != $_SESSION["user_id"]) { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="kullanici_id" value="<?php echo $row["id"]; ?>">
                                    <select name="yeni_rol" onchange="this.form.submit()">
                                        <option value="admin" <?php if($row["rol"]=="admin") echo "selected"; ?>>admin</option>
                                        <option value="servis" <?php if($row["rol"]=="servis") echo "selected"; ?>>servis</option>
                                        <option value="musteri" <?php if($row["rol"]=="musteri") echo "selected"; ?>>musteri</option>
                                    </select>
                                    <input type="hidden" name="rol_degistir" value="1">
                                </form>
                            <?php } else { ?>
                                <?php echo $row["rol"]; ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row["id"] != $_SESSION["user_id"]) { ?>
                                <a href="kullanicilar.php?sil=<?php echo $row["id"]; ?>" 
                                    onclick="return confirm('Bu kullanıcı silinsin mi?')">
                                    Sil
                                </a>
                            <?php } else { ?>
                                -
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>

            </table>

        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>