<?php

include '../../connections/connections.php';
include '../../connections/ssp_connection.php';

header('Content-Type: application/json');

$draw = $_GET['draw'] ?? 1;
$start = $_GET['start'] ?? 0;
$length = $_GET['length'] ?? 10;
$search = mysqli_real_escape_string($conn, $_GET['search']['value'] ?? '');

/*
|--------------------------------------------------------------------------
| SORTING (ADDED ONLY)
|--------------------------------------------------------------------------
*/
$orderColumnIndex = $_GET['order'][0]['column'] ?? 0;
$orderDir = $_GET['order'][0]['dir'] ?? 'desc';

$orderMap = [
	0 => 'cart.cart_id',
	1 => 'cart.paypal_order_id',
	2 => 'cart.reference_no',
	3 => 'cart.payment_method',
	4 => 'cart.cart_status',
	5 => 'cart.total_price',
	6 => 'cart.updated_at',
	7 => 'cart.delivery_guest_fullname',
	8 => 'users.user_fullname'
];

$orderColumn = $orderMap[$orderColumnIndex] ?? 'cart.updated_at';

/*
|--------------------------------------------------------------------------
| BASE QUERY
|--------------------------------------------------------------------------
*/
$sql = "
SELECT 
    cart.cart_id,
    cart.paypal_order_id,
    cart.reference_no,
    cart.payment_method,
    cart.cart_status,
    cart.total_price,
    cart.updated_at,
    cart.created_at,
    cart.delivery_guest_fullname,
    users.user_fullname,
		voucher.voucher_percentage
FROM cart
LEFT JOIN users ON cart.user_id = users.user_id
LEFT JOIN voucher ON voucher.voucher_id = cart.voucher_id

WHERE cart.cart_status = 'Delivered'
";

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
if (!empty($search)) {
	$sql .= " AND (
        cart.cart_id LIKE '%$search%' OR
        cart.paypal_order_id LIKE '%$search%' OR
        cart.reference_no LIKE '%$search%' OR
        cart.payment_method LIKE '%$search%' OR
        cart.total_price LIKE '%$search%' OR
        cart.updated_at LIKE '%$search%' OR
        cart.delivery_guest_fullname LIKE '%$search%' OR
        users.user_fullname LIKE '%$search%'
    )";
}

/*
|--------------------------------------------------------------------------
| APPLY SORTING
|--------------------------------------------------------------------------
*/
$sql .= " ORDER BY $orderColumn $orderDir";

$result = mysqli_query($conn, $sql);

$grouped = [];

/*
|--------------------------------------------------------------------------
| GROUPING (UNCHANGED)
|--------------------------------------------------------------------------
*/
while ($row = mysqli_fetch_assoc($result)) {

	$groupKey = ($row['payment_method'] === 'Paypal')
		? $row['paypal_order_id']
		: $row['reference_no'];

	if (empty($groupKey)) {
		$groupKey = 'NO_REF_' . $row['cart_id'];
	}

	if (!isset($grouped[$groupKey])) {

		$grouped[$groupKey] = [
			'cart_id' => $row['cart_id'],
			'paypal_order_id' => $row['paypal_order_id'],
			'reference_no' => $row['reference_no'],
			'payment_method' => $row['payment_method'],
			'cart_status' => $row['cart_status'],
			'total_price' => 0,
			'updated_at' => $row['updated_at'],
			'delivery_guest_fullname' => $row['delivery_guest_fullname'],
			'user_fullname' => $row['user_fullname'],
			'voucher_percentage' => $row['voucher_percentage']
		];
	}

	$grouped[$groupKey]['total_price'] += (float) $row['total_price'];
}

/*
|--------------------------------------------------------------------------
| FINAL DATA BUILD
|--------------------------------------------------------------------------
*/
$data = [];

foreach ($grouped as $row) {

	$order_ref = ($row['payment_method'] === 'Paypal')
		? ($row['paypal_order_id'] ?: '-')
		: ($row['reference_no'] ?: '-');

	$customer_name = empty($row['user_fullname'])
		? $row['delivery_guest_fullname']
		: $row['user_fullname'];

	$final_total =
		$row['total_price']
		- ($row['total_price'] * $row['voucher_percentage'] / 100);

	$formatted_price = ($row['payment_method'] == 'GCash')
		? '₱ ' . number_format($final_total, 2)
		: '$ ' . number_format($final_total, 2);

	$status = "<span style='background:#d4edda;color:#155724;padding:4px 8px;border-radius:6px;font-weight:600;'>
                Delivered
              </span>";

	$action = '
        <a class="fetchCustomerDetails"
            data-cart_id="' . $row['cart_id'] . '"
            href="#">
            Customer Details
        </a>';

	$actionGetOrder = '
        <a class="fetchOrderDetails"
            data-cart_id="' . $row['cart_id'] . '"
            data-reference_no="' . $row['reference_no'] . '"
            data-paypal_order_id="' . $row['paypal_order_id'] . '"
            data-payment_method="' . $row['payment_method'] . '"
            href="#">
            Order Details
        </a>';

	$data[] = array_values([
		$row['cart_id'],
		$order_ref,
		$customer_name,
		$status,
		$row['payment_method'],
		$action,
		$actionGetOrder,
		$formatted_price,
		$row['updated_at'],
		$row['delivery_guest_fullname'],
		$row['reference_no']
	]);
}

/*
|--------------------------------------------------------------------------
| OUTPUT
|--------------------------------------------------------------------------
*/
echo json_encode([
	"draw" => intval($draw),
	"recordsTotal" => count($data),
	"recordsFiltered" => count($data),
	"data" => array_slice($data, $start, $length)
]);