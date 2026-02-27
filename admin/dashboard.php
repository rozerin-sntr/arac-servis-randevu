<?php
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "admin"){
    header("Location: ../login.php");
    exit;
}
?>


<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar.php"); ?>

<div class="content-wrapper p-3">
    <h2>Admin Dashboard</h2>

    <div class="card">
        <div class="card-body">
            Admin paneli başarıyla oluşturuldu 🎉
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>