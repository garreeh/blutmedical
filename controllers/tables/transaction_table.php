<?php

include '../../connections/connections.php';
include '../../connections/ssp_connection.php';

header('Content-Type: application/json');

$draw = $_GET['draw'] ?? 1;
$start = $_GET['start'] ?? 0;
$length = $_GET['length'] ?? 10;

$search = mysqli_real_escape_string(
	$conn,
	$_GET['search']['value'] ?? ''
);

/*
|--------------------------------------------------------------------------
| GET USD TO PHP CURRENCY RATE
|--------------------------------------------------------------------------
|
| dollar_currency = USD -> PHP conversion rate
|
| Example:
|
| $100 × 61 = ₱6,100.00
|
| IMPORTANT:
|
| PayPal = USD
| GCash   = PHP
|
|--------------------------------------------------------------------------
*/

$currencyQuery = mysqli_query(
	$conn,
	"
    SELECT dollar_currency
    FROM currency
    WHERE dollar_id = 1
    LIMIT 1
    "
);

if (!$currencyQuery) {

	echo json_encode([
		'success' => false,
		'message' => 'Currency query failed: ' . mysqli_error($conn)
	]);

	exit;
}

if (mysqli_num_rows($currencyQuery) == 0) {

	echo json_encode([
		'success' => false,
		'message' => 'Currency conversion rate not found.'
	]);

	exit;
}

$currencyRow = mysqli_fetch_assoc($currencyQuery);

$dollarCurrency = (float) $currencyRow['dollar_currency'];

if ($dollarCurrency <= 0) {

	echo json_encode([
		'success' => false,
		'message' => 'Invalid currency conversion rate.'
	]);

	exit;
}


/*
|--------------------------------------------------------------------------
| SORTING
|--------------------------------------------------------------------------
*/

$orderColumnIndex = $_GET['order'][0]['column'] ?? 0;

$orderDir = strtolower(
	$_GET['order'][0]['dir'] ?? 'desc'
);

if (!in_array($orderDir, ['asc', 'desc'])) {
	$orderDir = 'desc';
}


/*
|--------------------------------------------------------------------------
| ORDER COLUMN MAP
|--------------------------------------------------------------------------
*/

$orderMap = [

	0 => 'cart.cart_id',

	1 => 'cart.paypal_order_id',

	2 => 'cart.reference_no',

	3 => 'cart.payment_method',

	4 => 'cart.cart_status',

	5 => 'calculated_total_price',

	6 => 'cart.updated_at',

	7 => 'cart.delivery_guest_fullname',

	8 => 'users.user_fullname'
];

$orderColumn = $orderMap[$orderColumnIndex]
	?? 'cart.updated_at';


/*
|--------------------------------------------------------------------------
| BASE QUERY
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| We DO NOT use cart.total_price.
|
| Product without variation:
|
|     product.product_sellingprice
|
| Product with variation:
|
|     variations.price
|
| Then:
|
|     price × cart_quantity
|
|--------------------------------------------------------------------------
*/

$sql = "

SELECT

    cart.cart_id,

    cart.paypal_order_id,

    cart.reference_no,

    cart.payment_method,

    cart.cart_status,

    cart.cart_quantity,

    cart.variation_id,

    cart.product_id,

    cart.updated_at,

    cart.created_at,

    cart.delivery_guest_fullname,

    cart.proof_of_payment,

    users.user_fullname,

    product.product_name,

    product.product_sellingprice,

    variations.value AS variation_value,

    variations.price AS variation_price,

    variations_colors.color,

    voucher.voucher_percentage,


    /*
    |--------------------------------------------------------------------------
    | CALCULATED UNIT PRICE
    |--------------------------------------------------------------------------
    */

    CASE

        WHEN cart.variation_id = '-'

        THEN product.product_sellingprice

        ELSE COALESCE(
            variations.price,
            product.product_sellingprice
        )

    END AS calculated_unit_price,


    /*
    |--------------------------------------------------------------------------
    | CALCULATED ITEM TOTAL
    |--------------------------------------------------------------------------
    */

    CASE

        WHEN cart.variation_id = '-'

        THEN
            product.product_sellingprice
            * cart.cart_quantity

        ELSE

            COALESCE(
                variations.price,
                product.product_sellingprice
            )
            * cart.cart_quantity

    END AS calculated_total_price


FROM cart


LEFT JOIN users

    ON cart.user_id = users.user_id


LEFT JOIN product

    ON cart.product_id = product.product_id


LEFT JOIN variations

    ON cart.variation_id = variations.variation_id


LEFT JOIN variations_colors

    ON cart.variation_color_id =
       variations_colors.variation_color_id


LEFT JOIN voucher

    ON voucher.voucher_id = cart.voucher_id


WHERE cart.cart_status = 'Delivered'

";


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

if (!empty($search)) {

	$sql .= "

        AND (

            cart.cart_id LIKE '%$search%'

            OR cart.paypal_order_id LIKE '%$search%'

            OR cart.reference_no LIKE '%$search%'

            OR cart.payment_method LIKE '%$search%'

            OR cart.cart_status LIKE '%$search%'

            OR cart.delivery_guest_fullname LIKE '%$search%'

            OR users.user_fullname LIKE '%$search%'

            OR product.product_name LIKE '%$search%'

            OR variations.value LIKE '%$search%'

            OR variations_colors.color LIKE '%$search%'

        )

    ";
}


/*
|--------------------------------------------------------------------------
| EXECUTE QUERY
|--------------------------------------------------------------------------
*/

$result = mysqli_query($conn, $sql);

if (!$result) {

	echo json_encode([
		'success' => false,
		'message' => mysqli_error($conn)
	]);

	exit;
}


/*
|--------------------------------------------------------------------------
| GROUP ORDERS
|--------------------------------------------------------------------------
*/

$grouped = [];

while ($row = mysqli_fetch_assoc($result)) {

	/*
	|--------------------------------------------------------------------------
	| DETERMINE GROUP KEY
	|--------------------------------------------------------------------------
	|
	| PayPal:
	|     paypal_order_id
	|
	| GCash / Other:
	|     reference_no
	|
	|--------------------------------------------------------------------------
	*/

	$paymentMethod = strtolower(
		trim(
			$row['payment_method']
		)
	);

	$groupKey =

		($paymentMethod === 'paypal')

		? $row['paypal_order_id']

		: $row['reference_no'];


	if (empty($groupKey)) {

		$groupKey =
			'NO_REF_' . $row['cart_id'];
	}


	/*
	|--------------------------------------------------------------------------
	| CALCULATED ITEM TOTAL
	|--------------------------------------------------------------------------
	*/

	$itemTotal =
		(float) $row['calculated_total_price'];


	/*
	|--------------------------------------------------------------------------
	| CREATE ORDER GROUP
	|--------------------------------------------------------------------------
	*/

	if (!isset($grouped[$groupKey])) {

		$grouped[$groupKey] = [

			'cart_id' =>
				$row['cart_id'],

			'paypal_order_id' =>
				$row['paypal_order_id'],

			'reference_no' =>
				$row['reference_no'],

			'payment_method' =>
				$row['payment_method'],

			'cart_status' =>
				$row['cart_status'],

			'total_price' =>
				0,

			'updated_at' =>
				$row['updated_at'],

			'created_at' =>
				$row['created_at'],

			'delivery_guest_fullname' =>
				$row['delivery_guest_fullname'],

			'user_fullname' =>
				$row['user_fullname'],

			'voucher_percentage' =>
				(float) (
					$row['voucher_percentage'] ?? 0
				)
		];
	}


	/*
	|--------------------------------------------------------------------------
	| ADD ITEM TOTAL TO ORDER
	|--------------------------------------------------------------------------
	*/

	$grouped[$groupKey]['total_price']
		+= $itemTotal;
}


/*
|--------------------------------------------------------------------------
| FINAL DATA BUILD
|--------------------------------------------------------------------------
*/

$data = [];

foreach ($grouped as $row) {


	/*
	|--------------------------------------------------------------------------
	| ORDER REFERENCE
	|--------------------------------------------------------------------------
	*/

	$paymentMethod = strtolower(
		trim(
			$row['payment_method']
		)
	);


	$order_ref =

		($paymentMethod === 'paypal')

		? ($row['paypal_order_id'] ?: '-')

		: ($row['reference_no'] ?: '-');


	/*
	|--------------------------------------------------------------------------
	| CUSTOMER NAME
	|--------------------------------------------------------------------------
	*/

	$customer_name =

		empty(
		$row['user_fullname']
	)

		? $row['delivery_guest_fullname']

		: $row['user_fullname'];


	/*
	|--------------------------------------------------------------------------
	| BASE TOTAL
	|--------------------------------------------------------------------------
	|
	| Base total is calculated from:
	|
	| Product / variation price × quantity
	|
	|--------------------------------------------------------------------------
	*/

	$baseTotal =
		(float) $row['total_price'];


	/*
	|--------------------------------------------------------------------------
	| APPLY VOUCHER
	|--------------------------------------------------------------------------
	|
	| Voucher is applied BEFORE currency conversion.
	|
	| Example:
	|
	| Base total = $208
	| Voucher    = 10%
	|
	| Discount   = $20.80
	| Final      = $187.20
	|
	|--------------------------------------------------------------------------
	*/

	$voucherPercentage =

		(float) (
			$row['voucher_percentage'] ?? 0
		);


	$finalTotal =
		$baseTotal;


	if ($voucherPercentage > 0) {

		$discountAmount =

			(
				$baseTotal
				* $voucherPercentage
			)
			/ 100;


		$finalTotal =

			$baseTotal
			- $discountAmount;
	}


	/*
	|--------------------------------------------------------------------------
	| PAYMENT METHOD CURRENCY
	|--------------------------------------------------------------------------
	|
	| PAYPAL
	| -------
	| Keep amount in USD.
	|
	| GCASH / OTHER
	| ------------
	| Convert USD → PHP.
	|
	|--------------------------------------------------------------------------
	*/

	if ($paymentMethod === 'paypal') {

		/*
		|--------------------------------------------------------------------------
		| PAYPAL = USD
		|--------------------------------------------------------------------------
		*/

		$displayTotal =
			$finalTotal;


		$formatted_price =

			'$ ' . number_format(
				$displayTotal,
				2
			);

	} else {

		/*
		|--------------------------------------------------------------------------
		| GCASH / OTHER = PHP
		|--------------------------------------------------------------------------
		*/

		$displayTotal =

			$finalTotal
			* $dollarCurrency;


		$formatted_price =

			'₱ ' . number_format(
				$displayTotal,
				2
			);
	}


	/*
	|--------------------------------------------------------------------------
	| STATUS
	|--------------------------------------------------------------------------
	*/

	$status = "

        <span style='
            background:#d4edda;
            color:#155724;
            padding:4px 8px;
            border-radius:6px;
            font-weight:600;
        '>

            Delivered

        </span>

    ";


	/*
	|--------------------------------------------------------------------------
	| CUSTOMER DETAILS BUTTON
	|--------------------------------------------------------------------------
	*/

	$action = '

        <a

            class="fetchCustomerDetails"

            data-cart_id="' .
		$row['cart_id'] .
		'"

            href="#">

            Customer Details

        </a>

    ';


	/*
	|--------------------------------------------------------------------------
	| ORDER DETAILS BUTTON
	|--------------------------------------------------------------------------
	*/

	$actionGetOrder = '

        <a

            class="fetchOrderDetails"

            data-cart_id="' .
		$row['cart_id'] .
		'"

            data-reference_no="' .
		htmlspecialchars(
			$row['reference_no'] ?? '',
			ENT_QUOTES
		) .
		'"

            data-paypal_order_id="' .
		htmlspecialchars(
			$row['paypal_order_id'] ?? '',
			ENT_QUOTES
		) .
		'"

            data-payment_method="' .
		htmlspecialchars(
			$row['payment_method'] ?? '',
			ENT_QUOTES
		) .
		'"

            href="#">

            Order Details

        </a>

    ';


	/*
	|--------------------------------------------------------------------------
	| DATA ROW
	|--------------------------------------------------------------------------
	*/

	$data[] = [

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

	];
}


/*
|--------------------------------------------------------------------------
| APPLY PHP SORTING
|--------------------------------------------------------------------------
|
| Since orders are grouped after the SQL query, we sort the final
| grouped result here.
|
|--------------------------------------------------------------------------
*/

usort(

	$data,

	function ($a, $b) use ($orderColumnIndex, $orderDir) {

		$valueA =
			$a[$orderColumnIndex] ?? '';

		$valueB =
			$b[$orderColumnIndex] ?? '';


		/*
		|--------------------------------------------------------------------------
		| REMOVE HTML
		|--------------------------------------------------------------------------
		*/

		$valueA =
			strip_tags(
				$valueA
			);

		$valueB =
			strip_tags(
				$valueB
			);


		/*
		|--------------------------------------------------------------------------
		| NUMERIC SORTING
		|--------------------------------------------------------------------------
		|
		| Column 0 = Cart ID
		| Column 7 = Total Price
		|
		|--------------------------------------------------------------------------
		*/

		if (

			$orderColumnIndex === 0 ||

			$orderColumnIndex === 7

		) {

			$valueA =

				(float) preg_replace(
					'/[^0-9.-]/',
					'',
					$valueA
				);


			$valueB =

				(float) preg_replace(
					'/[^0-9.-]/',
					'',
					$valueB
				);
		}


		/*
		|--------------------------------------------------------------------------
		| DATE SORTING
		|--------------------------------------------------------------------------
		*/ elseif (

			$orderColumnIndex === 8

		) {

			$valueA =
				strtotime(
					$valueA
				);

			$valueB =
				strtotime(
					$valueB
				);
		}


		/*
		|--------------------------------------------------------------------------
		| STRING SORTING
		|--------------------------------------------------------------------------
		*/ else {

			$valueA =

				strtolower(
					(string) $valueA
				);


			$valueB =

				strtolower(
					(string) $valueB
				);
		}


		/*
		|--------------------------------------------------------------------------
		| COMPARE
		|--------------------------------------------------------------------------
		*/

		if ($valueA == $valueB) {

			return 0;
		}


		if ($orderDir === 'asc') {

			return

				($valueA < $valueB)

				? -1
				: 1;
		}


		return

			($valueA > $valueB)

			? -1
			: 1;
	}
);


/*
|--------------------------------------------------------------------------
| OUTPUT
|--------------------------------------------------------------------------
*/

echo json_encode([

	"draw" =>
		intval($draw),

	"recordsTotal" =>
		count($data),

	"recordsFiltered" =>
		count($data),

	"data" =>
		array_slice(
			$data,
			intval($start),
			intval($length)
		)

]);

?>