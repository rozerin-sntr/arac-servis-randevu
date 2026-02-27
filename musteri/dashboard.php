<?php
session_start();

if(!isset($_SESSION["user_id"]) || $_SESSION["rol"] != "musteri"){
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Müşteri Paneli</title>
</head>
<body>

<h2>Müşteri Dashboard</h2>
<p>Hoşgeldiniz 👋</p>

<a href="../logout.php">Çıkış Yap</a>

</body>
</html>