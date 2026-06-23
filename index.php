<?php
$rootPath = __DIR__;
require_once $rootPath . '/app/config/session.php';
include 'partials/index/header.php';
?>

<style>
    * {
        box-sizing: border-box;
    }
    
    html, body {
        overflow-x: hidden !important;
        width: 100% !important;
    }
    
    .container {
        overflow-x: hidden;
    }
    
    main {
        overflow-x: hidden;
    }
    
    section {
        overflow-x: hidden;
    }
    
    .row {
        overflow-x: hidden;
    }
    
    @media (max-width: 991px) {
        body {
            padding-top: 60px;
        }
        
        .hero-section {
            padding: 2rem 1rem;
        }
        
        .hero-section h1 {
            font-size: 1.75rem;
            line-height: 1.3;
        }
        
        .hero-section p {
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .feature-section {
            padding: 2rem 1rem;
        }
        
        .feature-section h2 {
            font-size: 1.5rem;
        }
        
        .cta-box {
            padding: 2rem 1.5rem;
        }
        
        .cta-box h2 {
            font-size: 1.5rem;
        }
        
        .cta-box p {
            font-size: 0.95rem;
        }
    }
    
    @media (max-width: 767px) {
        body {
            padding-top: 56px;
        }
        
        .hero-section {
            padding: 1.5rem 1rem;
        }
        
        .hero-section h1 {
            font-size: 1.5rem;
        }
        
        .hero-section p {
            font-size: 0.95rem;
        }
        
        .row {
            margin-left: 0;
            margin-right: 0;
        }
        
        .col-12 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .hero-section h1 {
            font-size: 1.35rem;
        }
        
        .hero-section p {
            font-size: 0.9rem;
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        .card-text {
            font-size: 0.85rem !important;
        }
        
        .cta-box {
            padding: 1.5rem 1rem !important;
        }
        
        .cta-box h2 {
            font-size: 1.25rem;
        }
        
        .cta-box p {
            font-size: 0.85rem;
        }
        
        .btn {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>

<main class="d-flex flex-column min-vh-100">

    <section class="hero-section py-4 py-md-5 text-center container">

        <div class="row py-3 py-lg-5">

            <div class="col-12 col-md-10 col-lg-8 mx-auto">

                <h1 class="fw-bold fs-2 fs-md-3 fs-lg-4 mb-3 mb-md-4 text-white">
                    Toko font yang keren dan mantap
                </h1>

                <p class="fs-6 fs-md-5 text-secondary mb-4">
                    Menjual font yang keren dan mantap untuk kebutuhan desainmu. Temukan berbagai pilihan font berkualitas tinggi yang siap mempercantik proyek kreatifmu.
                </p>

<div class="d-flex flex-wrap justify-content-center mt-3 mt-md-4 gap-2 gap-md-3">
    <?php if (isset($_SESSION['buyer_status']) && $_SESSION['buyer_status'] === 'login'): ?>
        <a href="/daftar.php" class="btn btn-primary px-4 px-md-5 mb-2 mb-md-3">Lihat Produk</a>
    <?php else: ?>
        <a href="/app/service/login-google.php" class="btn btn-primary px-4 px-md-5 mb-2 mb-md-3">Login / Daftar</a>
        <a href="/daftar.php" class="btn btn-outline-secondary px-4 px-md-5 mb-2 mb-md-3">Daftar Produk</a>
    <?php endif; ?>
</div>
            </div>

        </div>

    </section>

    <section id="fitur" class="feature-section py-4 py-md-5">

        <div class="container">

            <h2 class="text-center mb-4 mb-md-5 fw-bold text-white fs-4 fs-md-3">
                Mengapa Beli Font Di Cloverania?
            </h2>

            <div class="row g-3 g-md-4">

                <div class="col-12 col-sm-6 col-md-4">

                    <div class="card card-feature h-100 rounded-4">

                        <div class="card-body text-center p-3 p-md-4">

                            <div class="fs-3 fs-md-2 fs-lg-1 mb-2 mb-md-3">
                                💵
                            </div>

                            <h5 class="card-title fw-bold fs-6 fs-md-5">
                                Harga Murah
                            </h5>

                            <p class="card-text small fs-6 fs-md-5">
                                Harga yang terjangkau untuk semua jenis font, cocok untuk berbagai kebutuhan desain.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-4">

                    <div class="card card-feature h-100 rounded-4">

                        <div class="card-body text-center p-3 p-md-4">

                            <div class="fs-3 fs-md-2 fs-lg-1 mb-2 mb-md-3">
                                ⚡
                            </div>

                            <h5 class="card-title fw-bold fs-6 fs-md-5">
                                Proses Cepat
                            </h5>

                            <p class="card-text small fs-6 fs-md-5">
                                Proses pembelian yang cepat dan mudah, sehingga Anda dapat segera menggunakan font yang Anda inginkan.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-4">

                    <div class="card card-feature h-100 rounded-4">

                        <div class="card-body text-center p-3 p-md-4">

                            <div class="fs-3 fs-md-2 fs-lg-1 mb-2 mb-md-3">
                                📱
                            </div>

                            <h5 class="card-title fw-bold fs-6 fs-md-5">
                                Suport Qris
                            </h5>

                            <p class="card-text small fs-6 fs-md-5">
                                Pembayaran bisa menggunakan qris sehingga lebih mudah dan praktis untuk semua pengguna.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <section class="py-4 py-md-5 text-center container">

        <div class="cta-box p-4 p-md-5 rounded-4">

            <h2 class="fw-bold mb-2 mb-md-3 text-white fs-4 fs-md-3">
                Siap Memilih Font Anda?
            </h2>

            <p class="text-secondary mb-3 mb-md-4 small fs-6 fs-md-5">
                Bergabunglah dengan ribuan pengguna yang telah mempercayai
                cloverania untuk kebutuhan font mereka.
            </p>

            <a
                href="<?php echo (isset($_SESSION['buyer_status']) && $_SESSION['buyer_status'] === 'login') ? '/daftar.php' : '/app/service/login-google.php'; ?>"
                class="btn btn-primary px-4 px-md-5"
            >
                <?php echo (isset($_SESSION['buyer_status']) && $_SESSION['buyer_status'] === 'login') ? 'Beli Sekarang' : 'Login / Daftar'; ?>
            </a>

        </div>

    </section>

</main>

<?php include 'partials/index/footer.php'; ?>