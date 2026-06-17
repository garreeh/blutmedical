<?php
// Include the connection file
include '../connections/connections.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Include Composer's autoloader
require './../vendor/autoload.php'; // Make sure the path is correct

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Merge cells A1:M6 for the logo and add the logo image
// $sheet->mergeCells('A1:M6');
// $drawing = new Drawing();
// $drawing->setName('Logo');
// $drawing->setDescription('Client Logo');
// $drawing->setPath('./../assets/logo/header.png');  // Provide the path to your logo image
// $drawing->setCoordinates('A1');  // Set the position where the image will appear
// $drawing->setWidth(200);  // Set the image width
// $drawing->setHeight(100);  // Set the image height
// $drawing->setWorksheet($sheet);

// Set the column headers
$sheet->setCellValue('A1', 'User Full Name');
$sheet->setCellValue('B1', 'Username');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Contact');
$sheet->setCellValue('E1', 'Address');
$sheet->setCellValue('F1', 'Account Status');
$sheet->setCellValue('G1', 'Created At');
$sheet->setCellValue('H1', 'Customer ID');
$sheet->setCellValue('I1', '');

// Apply styles to headers
$sheet->getStyle('A1:H1')->getFont()->setBold(true);
$sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A1:H1')->getFill()->getStartColor()->setRGB('A9D08E');

// Helper function
function checkEmpty($value)
{
  return empty($value) ? '-' : $value;
}

// Fetch users
$query = $conn->query("
  SELECT 
    user_id,
    user_fullname,
    username,
    user_email,
    user_contact,
    user_address,
    account_status,
    created_at
  FROM users WHERE is_admin = '0'
");

$rowNum = 2;

if ($query && $query->num_rows > 0) {

  while ($row = $query->fetch_assoc()) {

    $lineData = array(
      checkEmpty($row['user_fullname']),
      checkEmpty($row['username']),
      checkEmpty($row['user_email']),
      checkEmpty($row['user_contact']),
      checkEmpty($row['user_address']),
      checkEmpty($row['account_status']),
      checkEmpty($row['created_at']),
      checkEmpty($row['user_id'])
    );

    $sheet->fromArray($lineData, null, 'A' . $rowNum++);
  }
}

// Auto column sizing
foreach (range('A', 'H') as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Optional footer note
$sheet->setCellValue('A' . $rowNum, 'Total Customer:');
$sheet->setCellValue('B' . $rowNum, $query->num_rows);
$sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->getFont()->setBold(true);

$fileName = "Customer Export " . date('F d, Y') . ".xlsx";
// Set headers to force download the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// Automatically adjust column widths based on content
foreach (range('A', 'I') as $column) {
  $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Create writer and send the file to the browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output'); // Output directly to the browser

exit();
?>