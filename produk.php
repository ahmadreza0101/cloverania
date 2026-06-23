<?php
$rootPath = __DIR__;

include $rootPath . '/koneksi.php';
/** @var mysqli $koneksi */

require_once $rootPath . '/app/config/session.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: /login.php?pesan=belum_login");
    exit();
}

include 'partials/dashboard/header.php';
include 'partials/dashboard/sidebar.php';

$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$query  = "SELECT * FROM tb_produk ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="main-content">
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <h2 class="fw-bold mb-0 fs-4">Kelola Produk</h2>
                                <p class="text-muted small mb-0">Cloverania Store</p>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <a href="/app/proses/crud-produk/tambah.php" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                                </a>
                                <a href="/app/service/print.php" class="btn btn-outline-secondary" target="_blank">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-2 p-md-3" style="overflow-x: hidden;">
                            <table class="table table-hover align-middle mb-0 w-100 dt-card-table" id="table_produk">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="ps-3">No</th>
                                        <th>Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga (Rp)</th>
                                        <th>Stok</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)):
                                        $badge = $row['is_active']
                                            ? '<span class="badge bg-success">Aktif</span>'
                                            : '<span class="badge bg-secondary">Non-Aktif</span>';

                                        $img_url = !empty($row['gambar']) ? htmlspecialchars($row['gambar']) : "";
                                ?>
                                    <tr id="baris-produk-<?= $row['id'] ?>">
                                        <td class="ps-3 text-muted"><?= $no++ ?></td>
                                        <td>
                                            <?php if ($img_url): ?>
                                            <img src="<?= (strpos($img_url, 'http') === 0) ? $img_url : 'https://res.cloudinary.com/void/image/upload/' . $img_url ?>"
                                                 width="50" height="50"
                                                 style="object-fit:cover;border-radius:6px;border:1px solid #dee2e6"
                                                 alt="foto"
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/50'; console.error('Gagal load:', this.src)">
                                            <?php else: ?>
                                            <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-semibold"><?= htmlspecialchars($row['judul']) ?></td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?= htmlspecialchars($row['kategori']) ?>
                                            </span>
                                        </td>
                                        <td class="text-nowrap">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                        <td><?= (int)$row['stok'] ?></td>
                                        <?php
                                            $deskripsiAsli = $row['deskripsi'] ?? '';
                                            $batasKarakter = 80;
                                            $deskripsiSingkat = mb_strlen($deskripsiAsli) > $batasKarakter
                                                ? mb_substr($deskripsiAsli, 0, $batasKarakter) . '...'
                                                : $deskripsiAsli;
                                        ?>
                                        <td class="text-muted small" style="max-width:220px;" title="<?= htmlspecialchars($deskripsiAsli) ?>">
                                            <?= htmlspecialchars($deskripsiSingkat) ?>
                                        </td>
                                        <td><?= $badge ?></td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center">
                                                <a href="/edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <button type="button" class="btn btn-danger btn-sm btn-hapus-kustom" data-id="<?= $row['id'] ?>" data-judul="<?= htmlspecialchars($row['judul']) ?>">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                    endwhile;
                                } 
                                ?>
                                </tbody>
                            </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHapusProduk" tabindex="-1" aria-labelledby="modalHapusProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title" id="modalHapusProdukLabel">Konfirmasi Hapus Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                Apakah Anda yakin ingin menghapus produk digital <strong id="nama-produk-modal" class="text-warning"></strong> dari katalog Cloverania Store?
            </div>
            <div class="modal-footer border-top border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btn-konfirmasi-eksekusi" class="btn btn-danger">Ya, Hapus Aset</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/style/table.css">

<style>
html, body {
    overflow-x: hidden;
    max-width: 100%;
}
</style>

<script>

(function waitForJQuery() {
    if (typeof window.jQuery === 'undefined') {
        setTimeout(waitForJQuery, 50);
        return;
    }
    initProdukPage(jQuery);
})();

function initProdukPage($) {
$(document).ready(function () {
   
    var table = $('#table_produk').DataTable({
        responsive: true,
        autoWidth: false,
        dom: '<"dt-toolbar d-flex justify-content-between align-items-center flex-wrap"lf>rt<"d-flex justify-content-between align-items-center flex-wrap"ip>',
        columnDefs: [
            { orderable: false, targets: [1, 8] },
            { responsivePriority: 1, targets: 2 },
           
            { responsivePriority: 1, targets: 8 },
            { responsivePriority: 3, targets: 4 }
        ],
        language: {
            search:      "Cari:",
            lengthMenu:  "Tampilkan _MENU_ data",
            info:        "Menampilkan _START_–_END_ dari _TOTAL_ produk",
            infoEmpty:   "Tidak ada data",
            zeroRecords: "Produk tidak ditemukan. Silakan tambah produk baru.",
            paginate: { previous: "‹ Prev", next: "Next ›" }
        },
        order: [],
     
        initComplete: function () {
            var $tableNode = $(this.api().table().node());
            if (!$tableNode.parent().hasClass('table-scroll-x')) {
                $tableNode.wrap('<div class="table-scroll-x"></div>');
            }
        }
    });

    var idProdukYgDihapus = null;

  
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    
    <?php if ($flashSuccess): ?>
    toastr.success(<?= json_encode($flashSuccess) ?>);
    <?php endif; ?>
    <?php if ($flashError): ?>
    toastr.error(<?= json_encode($flashError) ?>);
    <?php endif; ?>


   
    $(document).on('click', '.btn-hapus-kustom', function() {
        idProdukYgDihapus = $(this).data('id');
        var judulProduk = $(this).data('judul');
        $('#nama-produk-modal').text(judulProduk);
        $('#modalHapusProduk').modal('show');
    });

    $('#btn-konfirmasi-eksekusi').click(function() {
        if(idProdukYgDihapus) {
            $.ajax({
                url: "/app/proses/crud-produk/hapus.php", 
                method: "GET",
                data: { id: idProdukYgDihapus },
                success: function(response) {
                    $('#modalHapusProduk').modal('hide');

                    toastr.success('Produk digital berhasil dihapus dari katalog Cloverania Store!');
                    table.row('#baris-produk-' + idProdukYgDihapus).remove().draw(false);
                },
                error: function() {
                    $('#modalHapusProduk').modal('hide');
                    toastr.error('Terjadi kesalahan server saat mencoba menghapus data.');
                }
            });
        }
    });
});
} 
</script>
<?php include 'partials/dashboard/footer.php'; ?>