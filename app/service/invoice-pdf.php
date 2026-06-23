<?php
$rootPath = dirname(__DIR__, 2);
include $rootPath . '/koneksi.php';
require_once $rootPath . '/app/config/session.php';

require_once $rootPath . '/vendor/autoload.php';


$transactionId = $_GET['transaction_id'] ?? '';

if (empty($transactionId)) {
    die("Transaction ID not provided!");
}

if (!isset($koneksi) || !$koneksi) {
    die("Database connection not available!");
}
$stmt = mysqli_prepare($koneksi, "
    SELECT t.*, p.judul, p.harga 
    FROM tb_pay t 
    JOIN tb_produk p ON t.product_id = p.id 
    WHERE t.transaction_id = ?
");
mysqli_stmt_bind_param($stmt, 's', $transactionId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$transaction = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$transaction) {
    die("Transaction not found!");
}

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('cloverania');
$pdf->SetTitle('Invoice - ' . $transaction['transaction_id']);
$pdf->SetSubject('Invoice');
$pdf->SetKeywords('Invoice, Payment');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(20, 20, 20);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 20);

$pdf->Cell(0, 15, 'INVOICE', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(0, 10, 'Transaction ID: ' . $transaction['transaction_id'], 0, 1, 'C');
$pdf->Cell(0, 10, 'Date: ' . date('d/m/Y H:i', strtotime($transaction['created_at'])), 0, 1, 'C');

$pdf->Ln(10);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Customer Information', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 8, 'Name: ' . ($_SESSION['user_name'] ?? 'User'), 0, 1, 'L');
$pdf->Cell(0, 8, 'Google ID: ' . $transaction['google_id'], 0, 1, 'L');

$pdf->Ln(10);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Product Details', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 8, 'Product: ' . $transaction['judul'], 0, 1, 'L');
$pdf->Cell(0, 8, 'Price: Rp ' . number_format($transaction['harga'], 0, ',', '.'), 0, 1, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Total: Rp ' . number_format($transaction['gross_amount'], 0, ',', '.'), 0, 1, 'L');

$pdf->Ln(20);

$pdf->SetFont('helvetica', 'B', 12);
$statusText = ucfirst($transaction['transaction_status']);
$pdf->Cell(0, 10, 'Payment Status: ' . $statusText, 0, 1, 'C');

$pdf->Output('invoice_' . $transaction['transaction_id'] . '.pdf', 'I');
?>