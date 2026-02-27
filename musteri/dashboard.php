<?php
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
    header("Location: ../login.php");
    exit;
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper p-3">
    <h2>Müşteri Dashboard</h2>
    <p>Hoşgeldiniz 👋</p>
</div>

<?php include("../includes/footer.php"); ?>