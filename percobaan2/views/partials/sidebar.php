<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Main Menu</div>
                <a class="nav-link" href="dashboard.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                    Dashboard
                </a>

                <!-- Manajemen Anggota -->

                <a class="nav-link collapsed" href="anggota.php" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Manajemen Anggota
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="data-anggota.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Data Anggota
                        </a>
                        <a class="nav-link" href="list-pegawai.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                            Data Pegawai
                        </a>
                        <a class="nav-link" href="../views/diskon/kartu-diskon.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
                            Kartu Diskon
                        </a>
                    </nav>
                </div>



                <!-- Manajemen Produk -->

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutss" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Manajemen Produk
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse" id="collapseLayoutss" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="data-produk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Data Produk
                        </a>
                        <a class="nav-link" href="jenis-produk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                            Jenis Produk
                        </a>
                    </nav>
                </div>



                <!-- Pemesanan -->
                <a class="nav-link" href="pemesanan.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                    Pemesanan
                </a>



                <!-- Transaksi -->

                <a class="nav-link" href="transaksi.php">

                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                    Transaksi
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Fathan Anwar
        </div>
    </nav>
</div>