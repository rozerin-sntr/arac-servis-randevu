<?php
include("../config/database.php");

if(isset($_GET["id"])){

    $id = $_GET["id"];

    $sql = "DELETE FROM kullanicilar WHERE id='$id'";
    $conn->query($sql);

}

header("Location: kullanicilar.php");
exit;
?>