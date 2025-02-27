<?php
        if (isset($_POST['submit'])) {
            // Tangkap data form
            $customer = $_POST['customer'];
            $produk = $_POST['produk'];
            $jumlah = $_POST['jumlah'];
            
            // Tentukan harga produk
            $harga = 0;
            switch ($produk) {
                case 'TV':
                    $harga = 4200000;
                    break;
                case 'KULKAS':
                    $harga = 3100000;
                    break;
                case 'MESIN CUCI':
                    $harga = 3800000;
                    break;
                default:
                    $harga = 0;
            }
            
            // Hitung total belanja
            $total = $harga * $jumlah;
            
            // Tampilkan hasil
            echo '<div class="row mt-4">';
            echo '<div class="col-md-12">';
            echo '<div class="">';
            echo '<h4>Hasil Belanja</h4>';
            echo '<hr>';
            echo '<p>Nama Customer : ' . $customer . '</p>';
            echo '<p>Produk Pilihan : ' . $produk . '</p>';
            echo '<p>Jumlah Beli : ' . $jumlah . '</p>';
            echo '<p>Total Belanja : Rp ' . number_format($total, 0, ',', '.') . ',-</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>