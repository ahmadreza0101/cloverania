<?php
$rootPath = dirname(__DIR__, 2);

require_once $rootPath . '/vendor/autoload.php';

$pdf = new TCPDF();
$pdf->setCreator(PDF_CREATOR);
$pdf->setTitle("Laporan Data Produk");
$pdf->setHeaderData('', 0, "Laporan Data Produk", "cloverania Store");
$pdf->setMargins(15, 27, 15);

$pdf->AddPage();

include $rootPath . '/koneksi.php';
$query = "SELECT * FROM tb_produk ORDER BY id DESC";
if (empty($koneksi)) {
    die("Database connection failed: " . mysqli_connect_error());
}
$result = mysqli_query($koneksi, $query) or die("Query failed: " . mysqli_error($koneksi));

$html = '<h2 style="text-align:center;">Laporan Data Produk</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">';
$html .= '<thead>
                <tr style="background-color:#f0f0f0;">
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga (Rp)</th>
                    <th>Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['is_active'] ? 'Aktif' : 'Non-Aktif';
    $html .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . htmlspecialchars($row['judul']) . '</td>
                    <td>' . htmlspecialchars($row['kategori']) . '</td>
                    <td>' . number_format($row['harga'], 0, ',', '.') . '</td>
                    <td>' . (int)$row['stok'] . '</td>
                    <td>' . $status . '</td>
                </tr>';
}
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("laporan_produk.pdf", "I"); 
