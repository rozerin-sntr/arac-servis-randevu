<?php
if(!isset($_SESSION)){
    session_start();
}
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <span class="navbar-brand">Araç Servis Sistemi</span>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Panel</span>
    </a>

    <div class="sidebar">
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column">

                <!-- ADMIN MENÜ -->
                <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin"){ ?>

                    <li class="nav-item">
                        <a href="../admin/dashboard.php" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../admin/kullanicilar.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Kullanıcılar</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../admin/randevular.php" class="nav-link">
                            <i class="nav-icon fas fa-calendar"></i>
                            <p>Randevular</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../admin/profil.php" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profilim</p>
                        </a>
                    </li>

                <?php } ?>

                <!-- MÜŞTERİ MENÜ -->
                <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] == "musteri") { ?>

                    <li class="nav-item">
                        <a href="../musteri/dashboard.php" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../musteri/randevu_olustur.php" class="nav-link">
                            <i class="nav-icon fas fa-calendar-plus"></i>
                            <p>Randevu Oluştur</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../musteri/randevularim.php" class="nav-link">
                            <i class="nav-icon fas fa-calendar"></i>
                            <p>Randevularım</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../musteri/profil.php" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profilim</p>
                        </a>
                    </li>

                <?php } ?>

                <!-- SERVİS MENÜ -->
                <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] == "servis") { ?>

                    <li class="nav-item">
                        <a href="../servis/dashboard.php" class="nav-link">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../servis/profil.php" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profilim</p>
                        </a>
                    </li>

                <?php } ?>

                <!-- ORTAK ÇIKIŞ -->
                <li class="nav-item">
                    <a href="../logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Çıkış Yap</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>