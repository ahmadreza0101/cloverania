<?php
$rootPath = __DIR__;
require_once $rootPath . '/app/config/session.php';
include 'partials/index/header.php';
?>

<main class="d-flex flex-column min-vh-100">

    <section id="about" class="py-5">
        <div class="container" style="max-width: 900px;">

            <h1 class="fw-bold display-4 mb-3">
                Tentang <span class="text-primary">Cloverania</span>
            </h1>

            <p class="text-light lead mb-5">
                Solusi modern untuk menemukan dan menggunakan font terbaik dalam satu website sederhana.
            </p>

            <div class="mb-5">
                <h2 class="fw-bold h3 mb-3 text-white">
                    Apa itu Cloverania?
                </h2>
                <p class="text-light" style="line-height: 1.9;">
                    Cloverania lahir dari kebutuhan para desainer dan kreator untuk menemukan font berkualitas
                    tanpa harus berpindah-pindah situs. Di tengah banyaknya pilihan font yang tersebar dan sulit
                    dikurasi, Cloverania hadir sebagai satu tempat yang rapi untuk menjelajahi, memilih, dan
                    mengunduh font sesuai kebutuhan desainmu.
                </p>
            </div>

            <div class="mb-5">
                <h2 class="fw-bold h3 mb-3 text-white">
                    Kenapa Memilih Kami?
                </h2>
                <p class="text-light" style="line-height: 1.9;">
                    Kami percaya proses mencari font seharusnya cepat dan tidak merepotkan. Itulah sebabnya
                    Cloverania dirancang dengan tampilan yang ringan, navigasi yang sederhana, dan koleksi font
                    yang terus diperbarui agar setiap proyek desainmu tetap terlihat profesional.
                </p>
            </div>

            <hr class="border-secondary my-5">

            <h2 class="fw-bold h3 mb-4 text-white">
                Hubungi Kami
            </h2>

            <div class="d-flex flex-column gap-3">

                <div class="card card-feature rounded-4">
                    <a href="mailto:reza@azayaka.my.id" class="text-decoration-none">
                        <div class="card-body d-flex align-items-center gap-3 p-3 p-md-4">
                            <span class="fs-3 text-primary"><i class="bi bi-envelope-fill"></i></span>
                            <div>
                                <p class="text-secondary small mb-0 text-uppercase">Email</p>
                                <p class="text-white fw-semibold mb-0">reza@azayaka.my.id</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="card card-feature rounded-4">
                    <a href="https://maps.google.com/maps?q=jakarta,indonesia" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                        <div class="card-body d-flex align-items-center gap-3 p-3 p-md-4">
                            <span class="fs-3 text-primary"><i class="bi bi-geo-alt-fill"></i></span>
                            <div>
                                <p class="text-secondary small mb-0 text-uppercase">Alamat</p>
                                <p class="text-white fw-semibold mb-0">Jakarta, Indonesia</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="card card-feature rounded-4">
                    <a href="https://instagram.com/ahmad_reza_0101" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                        <div class="card-body d-flex align-items-center gap-3 p-3 p-md-4">
                            <span class="fs-3 text-primary"><i class="bi bi-instagram"></i></span>
                            <div>
                                <p class="text-secondary small mb-0 text-uppercase">Instagram</p>
                                <p class="text-white fw-semibold mb-0">instagram.com/ahmad_reza_0101</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="card card-feature rounded-4">
                    <a href="https://github.com/ahmadreza0101" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                        <div class="card-body d-flex align-items-center gap-3 p-3 p-md-4">
                            <span class="fs-3 text-primary"><i class="bi bi-github"></i></span>
                            <div>
                                <p class="text-secondary small mb-0 text-uppercase">GitHub</p>
                                <p class="text-white fw-semibold mb-0">github.com/ahmadreza0101</p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </section>

</main>

<?php include 'partials/index/footer.php'; ?>