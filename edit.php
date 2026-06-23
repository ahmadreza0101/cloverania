<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
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

$baseUrl = '';

$errors = [];
$id_get = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "SELECT * FROM tb_produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id_get);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$row) {
    $_SESSION['flash_error'] = 'Produk tidak ditemukan.';
    echo "<script>window.location.href='/produk.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $harga     = (int)($_POST['harga'] ?? 0);
    $stok      = (int)($_POST['stok'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $gambar_url = trim($_POST['gambar_url'] ?? '') !== '' ? trim($_POST['gambar_url']) : $row['gambar'];
    $file_url   = trim($_POST['asset_url'] ?? '')  !== '' ? trim($_POST['asset_url'])  : $row['file'];

    $stmt = mysqli_prepare($koneksi, "UPDATE tb_produk SET gambar=?, judul=?, kategori=?, harga=?, stok=?, file=?, deskripsi=?, is_active=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'sssiissii', $gambar_url, $judul, $kategori, $harga, $stok, $file_url, $deskripsi, $is_active, $id_get);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_success'] = 'Produk berhasil diperbarui!';
        echo "<script>window.location.href='/produk.php';</script>";
        exit();
    } else {
        $errors[] = "Gagal update: " . mysqli_error($koneksi);
        $row = array_merge($row, compact('judul', 'kategori', 'harga', 'stok', 'deskripsi', 'is_active'));
        $row['gambar'] = $gambar_url;
        $row['file']   = $file_url;
    }
    mysqli_stmt_close($stmt);
}
?>
<div class="main-content">
    <div class="container-fluid mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4">
                    <a href="/produk.php" class="btn btn-outline-secondary btn-sm mb-3">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <div>
                        <h2 class="fw-bold mb-0 fs-4">Ubah Data Produk Aset</h2>
                        <p class="text-muted mb-0 small">Perbarui informasi katalog digital Cloverania Store.</p>
                    </div>
                </div>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Ada kesalahan pengisian data:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="" method="post" enctype="multipart/form-data" novalidate id="form-edit-produk">
                            <input type="hidden" name="gambar_url" id="gambar_url" value="<?php echo htmlspecialchars($row['gambar'] ?? ''); ?>">
                            <input type="hidden" name="asset_url" id="asset_url" value="<?php echo htmlspecialchars($row['file'] ?? ''); ?>">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="judul" class="form-label fw-semibold">Nama Produk / Judul <span class="text-danger">*</span></label>
                                        <input type="text" name="judul" id="judul" value="<?php echo htmlspecialchars($row['judul']); ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kategori" class="form-label fw-semibold">Kategori Aset <span class="text-danger">*</span></label>
                                        <input type="text" name="kategori" id="kategori" value="<?php echo htmlspecialchars($row['kategori'] ?? ''); ?>" class="form-control" placeholder="Contoh: Font, Audio, Template, dll." required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                        <input type="number" name="harga" id="harga" value="<?php echo htmlspecialchars($row['harga']); ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="stok" class="form-label fw-semibold">Stok Barang Digital</label>
                                        <input type="number" name="stok" id="stok" value="<?php echo htmlspecialchars($row['stok']); ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status Visibilitas Toko</label>
                                        <div class="form-check form-switch mt-1">
                                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" <?php echo (!isset($row['is_active']) || $row['is_active'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_active">Aktifkan produk di katalog depan pembeli</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi Produk</label>
                                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"><?php echo htmlspecialchars($row['deskripsi'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gambar_file" class="form-label fw-semibold">Ganti Gambar Preview</label>
                                        <input type="file" name="gambar_file" id="gambar_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                                        <div class="mt-2" id="preview-wrapper">
                                            <?php if (!empty($row['gambar'])): ?>
                                                <img id="preview" src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="Preview" style="max-width:120px;max-height:120px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="asset_file" class="form-label fw-semibold">Ganti File</label>
                                        <input type="file" name="asset_file" id="asset_file" class="form-control">
                                        <div class="mt-1 small text-muted">
                                            File saat ini:
                                            <?php if (!empty($row['file'])): ?>
                                                <a href="<?php echo htmlspecialchars($row['file']); ?>" target="_blank" class="text-decoration-none">
                                                    <i class="bi bi-download"></i> Unduh file saat ini
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Belum ada file.</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div id="upload-status" class="small text-muted mb-2"></div>
                            <div class="d-flex gap-2">
                                <button type="submit" id="btn-submit-edit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                <a href="/produk.php" class="btn btn-outline-secondary px-4">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="upload-overlay" style="display:none;position:fixed;inset:0;background:rgba(15,15,20,0.75);z-index:9999;align-items:center;justify-content:center;flex-direction:column;color:#fff;text-align:center;padding:1rem;">
    <div class="spinner-border text-light mb-3" role="status" style="width:3rem;height:3rem;"></div>
    <div id="upload-overlay-text" class="fw-semibold fs-5">Mengunggah...</div>
    <div class="small mt-2 text-white-50">Proses ini bisa makan waktu untuk file berukuran besar.<br>Jangan tutup atau refresh halaman ini.</div>
</div>
<script>
document.getElementById('gambar_file').addEventListener('change', function (e) {
    const file = e.target.files[0];
    let preview = document.getElementById('preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = ev => {
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'preview';
                preview.style = 'max-width:120px;max-height:120px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6';
                document.getElementById('preview-wrapper').appendChild(preview);
            }
            preview.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
});

const SIGNATURE_ENDPOINT = '/app/service/cloudinary-signature.php';

async function getCloudinarySignature(type) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 15000);

    let res;
    try {
        res = await fetch(`${SIGNATURE_ENDPOINT}?type=${type}`, { signal: controller.signal });
    } catch (err) {
        throw new Error('Gagal menghubungi server untuk signature upload (timeout atau koneksi terputus). Cek koneksi internet lalu coba lagi.');
    } finally {
        clearTimeout(timeoutId);
    }

    let data;
    try {
        data = await res.json();
    } catch (err) {
        throw new Error('Server tidak mengembalikan respon yang valid dari cloudinary-signature.');
    }

    if (!res.ok || data.error) throw new Error(data.error || 'Gagal mengambil signature upload.');
    return data;
}

async function uploadToCloudinaryDirect(file, type, onProgress) {
    const sig = await getCloudinarySignature(type);

    const formData = new FormData();
    formData.append('file', file);
    formData.append('api_key', sig.apiKey);
    formData.append('timestamp', sig.timestamp);
    formData.append('signature', sig.signature);
    formData.append('folder', sig.folder);

    const uploadUrl = `https://api.cloudinary.com/v1_1/${sig.cloudName}/${sig.resourceType}/upload`;

    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', uploadUrl);
        xhr.timeout = 120000;
        xhr.upload.onprogress = (e) => {
            if (e.lengthComputable && onProgress) onProgress(Math.round((e.loaded / e.total) * 100));
        };
        xhr.onload = () => {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.error) return reject(new Error(data.error.message));
                resolve(data.secure_url);
            } catch (err) {
                reject(new Error('Respon server tidak valid.'));
            }
        };
        xhr.onerror = () => reject(new Error('Koneksi ke server gagal.'));
        xhr.ontimeout = () => reject(new Error('Upload ke server timeout (lebih dari 2 menit). Coba file lebih kecil atau cek koneksi internet.'));
        xhr.send(formData);
    });
}

const formEdit    = document.getElementById('form-edit-produk');
const btnSubmitE   = document.getElementById('btn-submit-edit');
const statusElE     = document.getElementById('upload-status');
const overlayE         = document.getElementById('upload-overlay');
const overlayTextE     = document.getElementById('upload-overlay-text');

let isSubmittingEdit = false;

function showOverlayE(text) {
    overlayTextE.textContent = text;
    overlayE.style.display = 'flex';
}
function hideOverlayE() {
    overlayE.style.display = 'none';
}
function beforeUnloadHandlerE(e) {
    e.preventDefault();
    e.returnValue = '';
}

(function waitForToastrEdit() {
    if (typeof window.toastr === 'undefined') {
        setTimeout(waitForToastrEdit, 50);
        return;
    }
    initEditPage();
})();

function initEditPage() {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "4000"
    };

    formEdit.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (isSubmittingEdit) return;
        isSubmittingEdit = true;

        btnSubmitE.disabled = true;
        const originalLabel = btnSubmitE.textContent;
        window.addEventListener('beforeunload', beforeUnloadHandlerE);
        showOverlayE('Menyiapkan...');

        try {
            const gambarFile = document.getElementById('gambar_file').files[0];
            const assetFile  = document.getElementById('asset_file').files[0];

            const MAX_SIZE = 10 * 1024 * 1024;
            if ((gambarFile && gambarFile.size > MAX_SIZE) || (assetFile && assetFile.size > MAX_SIZE)) {
                throw new Error('Maksimal ukuran seluruh file dan gambar adalah 10 MB.');
            }

            if (gambarFile) {
                btnSubmitE.textContent = 'Mengunggah gambar...';
                showOverlayE('Mengunggah gambar preview baru ke server... 0%');
                document.getElementById('gambar_url').value = await uploadToCloudinaryDirect(
                    gambarFile, 'image', (p) => {
                        statusElE.textContent = `Mengunggah gambar preview... ${p}%`;
                        showOverlayE(`Mengunggah gambar preview... ${p}%`);
                    }
                );
            }

            if (assetFile) {
                btnSubmitE.textContent = 'Mengunggah file...';
                showOverlayE('Mengunggah file ke server... 0%');
                document.getElementById('asset_url').value = await uploadToCloudinaryDirect(
                    assetFile, 'raw', (p) => {
                        statusElE.textContent = `Mengunggah file... ${p}%`;
                        showOverlayE(`Mengunggah file... ${p}%`);
                    }
                );
            }

            document.getElementById('gambar_file').removeAttribute('name');
            document.getElementById('asset_file').removeAttribute('name');

            statusElE.textContent = 'Menyimpan perubahan...';
            showOverlayE('Menyimpan perubahan ke database...');

            window.removeEventListener('beforeunload', beforeUnloadHandlerE);

            HTMLFormElement.prototype.submit.call(formEdit);
        } catch (err) {
            window.removeEventListener('beforeunload', beforeUnloadHandlerE);
            hideOverlayE();
            statusElE.textContent = '';
            toastr.error('Gagal upload: ' + err.message);
            btnSubmitE.disabled = false;
            btnSubmitE.textContent = originalLabel;
            isSubmittingEdit = false;
        }
    });
}
</script>
<?php include 'partials/dashboard/footer.php'; ?>