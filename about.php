<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - DualTekno</title>
    <link rel="icon" type="image/png" href="assets/logohexacropped.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="styles.css" rel="stylesheet">

</head>

<body>

    <?php include 'header.php'; ?>

    <header class="bg-primary py-3">
        <div class="container px-4 px-lg-5 my-5">
            <h1 class="text-center text-white">About</h1>
        </div>
    </header>
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5 ">
            <br>
            <p>
                Selamat datang di DualTekno, pusat teknologi terpercaya yang menyediakan berbagai pilihan produk elektronik, mulai dari smartphone, laptop, hingga TV berkualitas tinggi. Kami berdedikasi untuk memberikan pengalaman belanja yang nyaman, mudah, dan aman bagi setiap pelanggan yang membutuhkan solusi teknologi.

                Mengapa Memilih Kami?


            </p>
            <p>Produk Terlengkap dan Berkualitas
                Kami menyediakan beragam pilihan HP terbaru, laptop dengan performa tinggi, dan TV dari merek-merek ternama, sehingga pelanggan dapat menemukan produk yang sesuai dengan kebutuhan dan anggaran mereka. Semua produk kami dijamin keasliannya dan didukung oleh garansi resmi.

            </p>
            <p>Harga Terjangkau dan Penawaran Menarik
                Kami selalu berusaha memberikan harga yang kompetitif dan promo menarik. Nikmati diskon khusus, paket bundling, serta penawaran cicilan yang memudahkan Anda untuk mendapatkan produk impian Anda.



            </p>
            <p>Layanan Pelanggan yang Ramah dan Profesional
                Tim kami siap membantu Anda dengan informasi dan konsultasi terkait spesifikasi produk, perbandingan harga, dan pilihan terbaik sesuai kebutuhan. Kami memahami betapa pentingnya mendapatkan produk yang tepat untuk mendukung aktivitas Anda.

            </p>
            <p>
                Garansi dan Dukungan Teknis Terpercaya
                Kami menyediakan garansi resmi dan layanan purna jual untuk semua produk, memastikan Anda mendapatkan dukungan penuh jika terjadi kendala. Dengan layanan servis berkualitas, kami pastikan pengalaman pengguna tetap optimal.

            </p>
            <p>
                Visi Kami
                Menjadi toko elektronik terkemuka yang dapat diandalkan dalam memenuhi kebutuhan teknologi masyarakat dengan produk berkualitas dan pelayanan prima.

                Misi Kami

                Menyediakan produk elektronik berkualitas dengan harga yang bersaing.
                Memberikan pengalaman belanja yang aman, mudah, dan nyaman bagi pelanggan.
                Menyediakan layanan purna jual yang profesional dan ramah.
                Kunjungi kami di DualTekno, dan temukan solusi teknologi terbaik bersama kami.
            </p>
            <p>Produk Kami

                Smartphone
                Temukan berbagai pilihan HP dari merek-merek global dan lokal dengan fitur terkini, kamera terbaik, serta daya tahan baterai yang tangguh.

                Laptop
                Dari kebutuhan untuk bekerja, gaming, hingga belajar, kami menyediakan laptop dengan spesifikasi lengkap, prosesor terbaru, dan desain yang stylish.

                TV
                Kami menawarkan berbagai pilihan TV, mulai dari LED hingga Smart TV dengan resolusi 4K, memberikan pengalaman menonton yang imersif dan memanjakan mata.

            </p>

            <br>
        </div>
    </section>



    <?php include 'footer.php'; ?>

    <script src="scripts.js"></script>

</body>

</html>