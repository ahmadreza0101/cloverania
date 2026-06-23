<?php
$rootPath = __DIR__;
require_once $rootPath . '/app/config/session.php';
include 'koneksi.php'; /** @var mysqli $koneksi */
include 'partials/index/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "SELECT * FROM tb_produk WHERE id = ? AND is_active = 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    echo "<div class='container mt-5 text-center'><h4>Produk tidak ditemukan</h4><a href='/daftar.php' class='btn btn-primary'>Kembali</a></div>";
    include 'partials/index/footer.php';
    exit();
}

$isLoggedIn = isset($_SESSION['user_email']) && isset($_SESSION['google_id']);

$hasPurchased = false;
$transactionId = null;
if ($isLoggedIn) {
    $googleId = $_SESSION['google_id'];
    $checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'tb_pay'");
    if (mysqli_num_rows($checkTable) > 0) {
        $stmtCheck = mysqli_prepare(
            $koneksi,
            "SELECT transaction_id FROM tb_pay WHERE google_id = ? AND product_id = ? ORDER BY created_at DESC LIMIT 1"
        );
        mysqli_stmt_bind_param($stmtCheck, 'si', $googleId, $id);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        $purchase = mysqli_fetch_assoc($resultCheck);
        if ($purchase) {
            $hasPurchased = true;
            $transactionId = $purchase['transaction_id'];
        }
        mysqli_stmt_close($stmtCheck);
    }
}

$flashSuccess = $_GET['msg'] ?? '';
$flashError = $_GET['err'] ?? '';
?>

<style>
    @media (max-width: 767px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .card-img-top {
            height: 250px !important;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }

        .card-img-top {
            height: 200px !important;
        }

        .btn-lg {
            padding: 0.6rem 1rem !important;
            font-size: 0.95rem !important;
        }
    }
</style>

<main class="d-flex flex-column min-vh-100">
    <section class="feature-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <?php if (!empty($row['gambar'])): ?>
                            <img src="<?= htmlspecialchars($row['gambar']) ?>"
                                 class="card-img-top"
                                 alt="<?= htmlspecialchars($row['judul']) ?>"
                                 style="height: 400px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                 style="height: 400px;">
                                <span class="text-white fs-1">📦</span>
                            </div>
                        <?php endif; ?>

                        <div class="card-body p-5">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-3">
                                    <li class="breadcrumb-item"><a href="/daftar.php" class="text-decoration-none">Daftar Produk</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($row['judul']) ?></li>
                                </ol>
                            </nav>

                            <span class="badge bg-primary mb-3">
                                <?= htmlspecialchars($row['kategori']) ?>
                            </span>

                            <h1 class="card-title fw-bold mb-3">
                                <?= htmlspecialchars($row['judul']) ?>
                            </h1>

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <h2 class="text-primary fw-bold mb-0">
                                    Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                                </h2>
                                <?php if ($row['stok'] > 0): ?>
                                    <span class="badge bg-success fs-6">Tersedia (<?= (int)$row['stok'] ?>)</span>
                                <?php else: ?>
                                    <span class="badge bg-danger fs-6">Habis</span>
                                <?php endif; ?>
                            </div>

                            <hr>

                            <h5 class="fw-bold mb-3">Deskripsi Produk</h5>
                            <p class="card-text text-muted">
                                <?= nl2br(htmlspecialchars($row['deskripsi'])) ?>
                            </p>

                            <hr class="my-4">

                            <?php if (!empty($row['file']) && $row['stok'] > 0): ?>
                                <?php if ($hasPurchased): ?>
                                    <?php $downloadUrl = str_replace('/upload/', '/upload/fl_attachment/', htmlspecialchars($row['file'])); ?>
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Anda sudah membeli produk ini!
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="<?= $downloadUrl ?>"
                                           target="_blank"
                                           class="btn btn-success btn-lg">
                                            <i class="bi bi-download me-2"></i>Unduh File
                                        </a>
                                        <a href="/app/service/invoice-pdf.php?transaction_id=<?= urlencode($transactionId) ?>"
                                           target="_blank"
                                           class="btn btn-info btn-lg">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>Cetak Invoice PDF
                                        </a>
                                        <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                            <i class="bi bi-x-circle me-2"></i>Batalkan Pembelian
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Batalkan pembelian produk ini? Akses unduh akan dicabut.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="/app/proses/pay/cancel-purchase.php" method="POST">
                                                            <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transactionId) ?>">
                                                            <input type="hidden" name="product_id" value="<?= $id ?>">
                                                            <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="/daftar.php" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Produk
                                        </a>
                                    </div>
                                <?php elseif ($isLoggedIn): ?>
                                    <div class="d-grid gap-2">
                                        <form action="/app/proses/pay/proses-payment.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?= $id ?>">
                                            <button type="submit" class="btn btn-success btn-lg w-100">
                                                <i class="bi bi-cart-check me-2"></i>Beli Produk Sekarang
                                            </button>
                                        </form>
                                        <a href="/daftar.php" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Produk
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="d-grid gap-2">
                                        <a href="/app/service/login-google.php" class="btn btn-primary btn-lg">
                                            <i class="bi bi-google me-2"></i>Login Google untuk Beli
                                        </a>
                                        <a href="/daftar.php" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Produk
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php elseif ($row['stok'] <= 0): ?>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Maaf, stok produk ini sedang habis.
                                </div>
                                <a href="/daftar.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Produk
                                </a>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="bi bi-x-circle me-2"></i>
                                    File produk tidak tersedia.
                                </div>
                                <a href="/daftar.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Produk
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    $(document).ready(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "3000"
        };

        <?php if ($flashSuccess === 'bought'): ?>
            toastr.success('Pembelian berhasil! Anda sekarang bisa mengunduh file.');
        <?php elseif ($flashSuccess === 'cancelled'): ?>
            toastr.info('Pembelian telah dibatalkan.');
        <?php elseif ($flashError === 'stok'): ?>
            toastr.error('Maaf, stok produk sudah habis.');
        <?php elseif ($flashError === 'notfound'): ?>
            toastr.error('Transaksi tidak ditemukan.');
        <?php endif; ?>
    });
</script>

<?php include 'partials/index/footer.php'; ?>