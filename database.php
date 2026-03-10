<?php

$host = "localhost";
$dbname = "arac_servis";
$username = "root";
$password = ""; // XAMPP'ta varsayılan boş

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Bağlantı başarılı";
} catch(PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

?>