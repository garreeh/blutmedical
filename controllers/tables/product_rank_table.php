<?php

include '../../connections/connections.php';

header('Content-Type: application/json');

$draw = intval($_GET['draw'] ?? 1);
$start = intval($_GET['start'] ?? 0);
$length = intval($_GET['length'] ?? 10);

$search = mysqli_real_escape_string(
  $conn,
  $_GET['search']['value'] ?? ''
);

/*
|--------------------------------------------------------------------------
| SORTING
|--------------------------------------------------------------------------
*/
$orderColumnIndex = $_GET['order'][0]['column'] ?? null;
$orderDir = ($_GET['order'][0]['dir'] ?? 'desc') === 'asc'
  ? 'ASC'
  : 'DESC';

$columns = [
  0 => 'p.product_id',
  1 => 'p.product_name',
  2 => 'total_units_sold',
  3 => 'total_units_sold', // Status column not sortable in DB
  4 => 'total_revenue'
];

if ($orderColumnIndex === null || !isset($columns[$orderColumnIndex])) {
  $orderBy = 'total_units_sold DESC';
} else {
  $orderBy = $columns[$orderColumnIndex] . ' ' . $orderDir;
}

/*
|--------------------------------------------------------------------------
| MAIN QUERY
|--------------------------------------------------------------------------
*/
$sql = "
SELECT
    p.product_id,
    p.product_name,
    COUNT(c.cart_id) AS total_units_sold,
    COALESCE(SUM(c.total_price), 0) AS total_revenue
FROM product p
INNER JOIN cart c
    ON c.product_id = p.product_id
WHERE c.cart_status = 'Delivered'
";

if (!empty($search)) {
  $sql .= "
    AND (
        p.product_id LIKE '%$search%'
        OR p.product_name LIKE '%$search%'
    )
    ";
}

$sql .= "
GROUP BY p.product_id, p.product_name
ORDER BY $orderBy
LIMIT $start, $length
";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {

  $data[] = [
    $row['product_id'],
    $row['product_name'],
    $row['total_units_sold'],
    '<span style="
        background-color:#d4edda;
        color:#155724;
        padding:4px 10px;
        border-radius:6px;
        font-size:12px;
        font-weight:600;
        display:inline-block;
    ">Delivered</span>',
    '₱ ' . number_format((float) $row['total_revenue'], 2)
  ];
}

/*
|--------------------------------------------------------------------------
| TOTAL RECORDS
|--------------------------------------------------------------------------
*/
$countQuery = "
SELECT COUNT(DISTINCT p.product_id) AS total
FROM product p
INNER JOIN cart c
    ON c.product_id = p.product_id
WHERE c.cart_status = 'Delivered'
";

$countResult = mysqli_query($conn, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'] ?? 0;

/*
|--------------------------------------------------------------------------
| FILTERED RECORDS
|--------------------------------------------------------------------------
*/
$filteredRecords = $totalRecords;

if (!empty($search)) {

  $filteredQuery = "
    SELECT COUNT(*) AS total
    FROM (
        SELECT p.product_id
        FROM product p
        INNER JOIN cart c
            ON c.product_id = p.product_id
        WHERE c.cart_status = 'Delivered'
        AND (
            p.product_id LIKE '%$search%'
            OR p.product_name LIKE '%$search%'
        )
        GROUP BY p.product_id
    ) x
    ";

  $filteredResult = mysqli_query($conn, $filteredQuery);
  $filteredRecords = mysqli_fetch_assoc($filteredResult)['total'] ?? 0;
}

/*
|--------------------------------------------------------------------------
| RESPONSE
|--------------------------------------------------------------------------
*/
echo json_encode([
  'draw' => $draw,
  'recordsTotal' => (int) $totalRecords,
  'recordsFiltered' => (int) $filteredRecords,
  'data' => $data
]);