<?php

include '../../connections/connections.php';

header('Content-Type: application/json');

$draw = $_GET['draw'] ?? 1;
$start = $_GET['start'] ?? 0;
$length = $_GET['length'] ?? 10;
$search = mysqli_real_escape_string($conn, $_GET['search']['value'] ?? '');

/*
|--------------------------------------------------------------------------
| SORTING (ADDED ONLY - NO LOGIC CHANGED)
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
	6 => 'cart.created_at',
	7 => 'cart.updated_at',
	8 => 'users.user_fullname'
];

$orderColumn = $orderMap[$orderColumnIndex] ?? 'cart.created_at';

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
    cart.proof_of_payment,

    users.user_fullname,

    product.product_name,
    variations.value,
    variations_colors.color,
		voucher.voucher_percentage

FROM cart
LEFT JOIN users ON cart.user_id = users.user_id
LEFT JOIN product ON cart.product_id = product.product_id
LEFT JOIN variations ON cart.variation_id = variations.variation_id
LEFT JOIN variations_colors ON cart.variation_color_id = variations_colors.variation_color_id
LEFT JOIN voucher ON voucher.voucher_id = cart.voucher_id

WHERE cart.cart_status = 'Processing'
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
        cart.created_at LIKE '%$search%' OR
        users.user_fullname LIKE '%$search%' OR
        product.product_name LIKE '%$search%' OR
        variations.value LIKE '%$search%' OR
        variations_colors.color LIKE '%$search%'
    )";
}

/*
|--------------------------------------------------------------------------
| GROUPING LOGIC (UNCHANGED)
|--------------------------------------------------------------------------
*/
$result = mysqli_query($conn, $sql);

$grouped = [];

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
			'created_at' => $row['created_at'],
			'delivery_guest_fullname' => $row['delivery_guest_fullname'],
			'user_fullname' => $row['user_fullname'],
			'voucher_percentage' => $row['voucher_percentage']

		];
	}

	$grouped[$groupKey]['total_price'] += (float) $row['total_price'];
}

/*
|--------------------------------------------------------------------------
| BUILD RESPONSE
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

	$status = $row['cart_status'];

	if ($status === 'Processing') {
		$status = "<span style='background:#fff3cd;color:#856404;padding:4px 8px;border-radius:6px;font-weight:600;'>$status</span>";
	} elseif ($status === 'Shipped') {
		$status = "<span style='background:#cce5ff;color:#004085;padding:4px 8px;border-radius:6px;font-weight:600;'>$status</span>";
	} elseif ($status === 'Delivered') {
		$status = "<span style='background:#d4edda;color:#155724;padding:4px 8px;border-radius:6px;font-weight:600;'>$status</span>";
	}

	$customerBtn = '
        <a class="fetchCustomerDetails"
            data-cart_id="' . $row['cart_id'] . '"
            data-name="' . htmlspecialchars($customer_name) . '"
            data-payment="' . $row['payment_method'] . '"
            href="#">
            Click to View
        </a>';

	$orderBtn = '
        <a class="fetchOrderDetails"
            data-cart_id="' . $row['cart_id'] . '"
            data-order_ref="' . $order_ref . '"
            href="#">
            Click to View
        </a>';

	$dropdown = '
        <div class="dropdown">
            <button class="btn btn-info" type="button" data-toggle="dropdown">
                &#x22EE;
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item fetchDataFinish"
                    data-id="' . $row['cart_id'] . '"
                    href="#">
                    Ship Order
                </a>
            </div>
        </div>';

	$data[] = array_values([
		$row['cart_id'],
		$order_ref,
		$status,
		$row['payment_method'],
		$customerBtn,
		$orderBtn,
		$formatted_price,
		$row['created_at'],
		$dropdown,
		$row['delivery_guest_fullname'],
		$row['reference_no']
	]);
}

/*
|--------------------------------------------------------------------------
| APPLY SORTING (IMPORTANT PART)
|--------------------------------------------------------------------------
*/
usort($data, function ($a, $b) use ($orderColumnIndex, $orderDir) {

	if ($orderDir === 'asc') {
		return $a[$orderColumnIndex] <=> $b[$orderColumnIndex];
	}
	return $b[$orderColumnIndex] <=> $a[$orderColumnIndex];
});

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