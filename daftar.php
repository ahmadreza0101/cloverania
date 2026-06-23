<?php
$rootPath = __DIR__;
require_once $rootPath . '/app/config/session.php';
include 'koneksi.php'; /** @var mysqli $koneksi */
include 'partials/index/header.php';

$query = "SELECT * FROM tb_produk WHERE is_active = 1 ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<main class="d-flex flex-column min-vh-100">

    <section id="produk" class="feature-section py-5">

        <div class="container">

            <h2 class="text-center mb-5 fw-bold text-white">
                Daftar Font Di Cloverania
            </h2>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <div class="row g-4">

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <div class="col-md-4">

                            <div class="card card-feature h-100 rounded-4">

                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="<?= htmlspecialchars($row['gambar']) ?>"
                                         class="card-img-top"
                                         alt="<?= htmlspecialchars($row['judul']) ?>"
                                         style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <span class="text-white fs-1">📦</span>
                                    </div>
                                <?php endif; ?>

                                <div class="card-body p-4">

                                    <span class="badge bg-primary mb-2">
                                        <?= htmlspecialchars($row['kategori']) ?>
                                    </span>

                                    <h5 class="card-title fw-bold mb-2">
                                        <?= htmlspecialchars($row['judul']) ?>
                                    </h5>

                                    <p class="card-text text-muted small mb-3">
                                        <?= substr(htmlspecialchars($row['deskripsi']), 0, 100) ?>
                                        <?= strlen($row['deskripsi']) > 100 ? '...' : '' ?>
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="text-primary fw-bold mb-0">
                                            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                                        </h4>
                                        <?php if ($row['stok'] > 0): ?>
                                            <span class="badge bg-success">Tersedia</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Habis</span>
                                        <?php endif; ?>
                                    </div>

                                </div>

                                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                    <a href="/detail.php?id=<?= $row['id'] ?>"
                                       class="btn btn-primary w-100">
                                        <i class="bi bi-eye me-2"></i>Lihat Detail
                                    </a>
                                </div>

                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <div class="text-center py-5">
                    <div class="fs-1 mb-3">📭</div>
                    <h4 class="text-white">Belum ada produk yang tersedia</h4>
                    <p class="text-white-50">Silakan cek kembali nanti</p>
                </div>

            <?php endif; ?>

        </div>

    </section>

</main>

<?php include 'partials/index/footer.php'; ?>