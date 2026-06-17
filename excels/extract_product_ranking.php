<?php
include '../connections/connections.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require './../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$sheet->setCellValue('A1', 'Product ID');
$sheet->setCellValue('B1', 'Product Name');
$sheet->setCellValue('C1', 'Total Units Sold');
$sheet->setCellValue('D1', 'Status');
$sheet->setCellValue('E1', 'Total Revenue');

// Header style
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFill()
  ->setFillType(Fill::FILL_SOLID)
  ->getStartColor()->setRGB('A9D08E');

// GET DATA (NO GROUPING REQUIRED HERE)
$query = $conn->query("
  SELECT 
    cart.product_id,
    product.product_name,
    COUNT(cart.cart_id) AS total_units_sold,
    SUM(cart.total_price) AS total_revenue
  FROM cart
  LEFT JOIN product ON product.product_id = cart.product_id
  WHERE cart.cart_status = 'Delivered'
  GROUP BY cart.product_id, product.product_name
");

$rowNum = 2;
$grandTotalRevenue = 0;

if ($query && $query->num_rows > 0) {

  while ($row = $query->fetch_assoc()) {

    $sheet->setCellValue('A' . $rowNum, $row['product_id']);
    $sheet->setCellValue('B' . $rowNum, $row['product_name']);
    $sheet->setCellValue('C' . $rowNum, $row['total_units_sold']);
    $sheet->setCellValue('D' . $rowNum, 'Delivered');
    $sheet->setCellValue('E' . $rowNum, $row['total_revenue']);

    // ADD TO GRAND TOTAL
    $grandTotalRevenue += (float) $row['total_revenue'];

    $rowNum++;
  }
}

// GRAND TOTAL ROW
$sheet->setCellValue('D' . $rowNum, 'GRAND TOTAL REVENUE');
$sheet->setCellValue('E' . $rowNum, '₱ ' . number_format($grandTotalRevenue, 2));

$sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getFill()
  ->setFillType(Fill::FILL_SOLID)
  ->getStartColor()->setRGB('D4EDDA');

// Auto size columns
foreach (range('A', 'E') as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output file
$fileName = "Sales Report " . date('F d, Y') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit();
?>