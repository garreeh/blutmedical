<?php

$table = 'cart';

$primaryKey = 'cart_id';

$columns = array(

	array(
		'db' => 'cart.cart_id',
		'dt' => 0,
		'field' => 'cart_id',
		'formatter' => function ($lab1, $row) {
			return $row['cart_id'];
		}
	),

	array(
		'db' => 'cart.paypal_order_id',
		'dt' => 1,
		'field' => 'paypal_order_id',
		'formatter' => function ($lab1, $row) {
			return !empty($row['paypal_order_id'])
				? $row['paypal_order_id']
				: $row['reference_no'];
		}
	),

	array(
		'db' => 'users.user_fullname',
		'dt' => 2,
		'field' => 'user_fullname',
		'formatter' => function ($lab2, $row) {

			return empty($row['user_fullname'])
				? $row['delivery_guest_fullname']
				: $row['user_fullname'];

		}
	),

	array(
		'db' => 'cart.cart_status',
		'dt' => 3,
		'field' => 'cart_status',
		'formatter' => function ($lab3, $row) {

			$cart_status = $row['cart_status'];
			$style = '';

			if ($cart_status === 'Processing') {

				$style = 'background-color: lightyellow; border-radius: 5px; padding: 5px;';

			} elseif ($cart_status === 'Shipped') {

				$style = 'background-color: lightyellow; border-radius: 5px; padding: 5px;';

			} elseif ($cart_status === 'Delivered') {

				$style = 'background-color: lightgreen; border-radius: 5px; padding: 5px;';

			}

			return "<span style=\"$style\">{$cart_status}</span>";
		}
	),

	/*
	|--------------------------------------------------------------------------
	| DT 4 - TOTAL PAYMENT
	|--------------------------------------------------------------------------
	|
	| REGISTERED USER:
	|     product.product_sellingprice
	|
	| GUEST:
	|     variations.price
	|
	*/

	array(
		'db' => 'order_totals.should_be_paid',
		'dt' => 4,
		'field' => 'should_be_paid',
		'formatter' => function ($value, $row) {

			$shouldBePaid = (float) ($row['should_be_paid'] ?? 0);

			if ($shouldBePaid == 0) {
				$shouldBePaid = (float) ($row['total_payment'] ?? 0);
			}

			$voucher = (float) ($row['voucher_percentage'] ?? 0);

			$discount = ($shouldBePaid * $voucher) / 100;

			$shouldBePaid = $shouldBePaid - $discount;

			$paymentMethod = strtolower(
				trim(
					$row['payment_method'] ?? ''
				)
			);

			if ($paymentMethod === 'paypal') {
				return '$ ' . number_format($shouldBePaid, 2);
			}

			$currencyRate = (float) ($row['dollar_currency'] ?? 1);

			$shouldBePaid = $shouldBePaid * $currencyRate;

			return '₱ ' . number_format($shouldBePaid, 2);
		}
	),

	array(
		'db' => 'payment_totals.total_payment',
		'dt' => 5,
		'field' => 'total_payment',
		'formatter' => function ($value, $row) {

			$price = (float) ($row['total_payment'] ?? 0);

			$voucher = (float) ($row['voucher_percentage'] ?? 0);

			$discount = ($price * $voucher) / 100;

			$final = $price - $discount;

			$paymentMethod = strtolower(
				trim(
					$row['payment_method'] ?? ''
				)
			);

			if ($paymentMethod === 'paypal') {
				return '$ ' . number_format($final, 2);
			}

			$currencyRate = (float) ($row['dollar_currency'] ?? 1);

			$final = $final * $currencyRate;

			return '₱ ' . number_format($final, 2);
		}
	),

	array(
		'db' => 'cart.payment_method',
		'dt' => 6,
		'field' => 'payment_method',
		'formatter' => function ($lab4, $row) {
			return $row['payment_method'];
		}
	),

	array(
		'db' => 'cart.created_at',
		'dt' => 7,
		'field' => 'created_at',
		'formatter' => function ($lab5, $row) {
			return $row['created_at'];
		}
	),

	array(
		'db' => 'cart.reference_no',
		'dt' => 8,
		'field' => 'reference_no',
		'formatter' => function ($lab5, $row) {

			return '
            <a 
                class="fetchOrderDetails" 
                href=""
                data-reference-no="' . htmlspecialchars($row['reference_no'], ENT_QUOTES, 'UTF-8') . '"
            >
                Test
            </a>
        ';
		}
	),

	array(
		'db' => 'cart.updated_at',
		'dt' => 9,
		'field' => 'updated_at',
		'formatter' => function ($lab5, $row) {
			return $row['updated_at'];
		}
	),

	array(
		'db' => 'cart.delivery_guest_fullname',
		'dt' => 10,
		'field' => 'delivery_guest_fullname',
		'formatter' => function ($lab5, $row) {
			return $row['delivery_guest_fullname'];
		}
	),

	array(
		'db' => 'voucher.voucher_percentage',
		'dt' => 11,
		'field' => 'voucher_percentage'
	),

	array(
		'db' => 'cart.reference_no',
		'dt' => 12,
		'field' => 'reference_no'
	),

	array(
		'db' => 'cart.payment_method',
		'dt' => 13,
		'field' => 'payment_method'
	),

	array(
		'db' => 'currency.dollar_currency',
		'dt' => 14,
		'field' => 'dollar_currency'
	)
);


/*
|--------------------------------------------------------------------------
| CONNECTIONS
|--------------------------------------------------------------------------
*/

include '../../connections/ssp_connection.php';

require('../../assets/datatables/ssp.class.php');


/*
|--------------------------------------------------------------------------
| DATE FILTER
|--------------------------------------------------------------------------
*/

$dateFrom = isset($_GET['date_from'])
	? $_GET['date_from'] . ' 00:00:00'
	: null;

$dateTo = isset($_GET['date_to'])
	? $_GET['date_to'] . ' 23:59:59'
	: null;


/*
|--------------------------------------------------------------------------
| WHERE
|--------------------------------------------------------------------------
|
| Only Delivered carts.
|
| Filter by updated_at.
|
| Only return the first Delivered cart
| for each reference_no.
|
*/

$where = "
    cart.cart_status = 'Delivered'

    AND cart.updated_at BETWEEN '$dateFrom' AND '$dateTo'

    AND cart.cart_id = (
        SELECT MIN(c2.cart_id)
        FROM cart c2
        WHERE c2.reference_no = cart.reference_no
        AND c2.cart_status = 'Delivered'
    )
";


/*
|--------------------------------------------------------------------------
| JOIN QUERY
|--------------------------------------------------------------------------
*/

$joinQuery = "

FROM cart

LEFT JOIN users
    ON cart.user_id = users.user_id

LEFT JOIN voucher
    ON voucher.voucher_id = cart.voucher_id

LEFT JOIN product
    ON product.product_id = cart.product_id

LEFT JOIN variations
    ON variations.variation_id = cart.variation_id

LEFT JOIN currency
    ON currency.dollar_id = 1


/*
|--------------------------------------------------------------------------
| DT 4 - TOTAL PAYMENT
|--------------------------------------------------------------------------
|
| REGISTERED USER:
|     product.product_sellingprice
|
| GUEST:
|     variations.price
|
| We determine guest/user based on cart.user_id.
|
*/

LEFT JOIN
(
    SELECT
        c.reference_no,

        SUM(
            CASE
                WHEN c.user_id IS NULL OR c.user_id = 0
                THEN v.price
                ELSE p.product_sellingprice
            END
        ) AS total_payment

    FROM cart c

    LEFT JOIN product p
        ON p.product_id = c.product_id

    LEFT JOIN variations v
        ON v.variation_id = c.variation_id

    WHERE c.cart_status = 'Delivered'

    GROUP BY c.reference_no

) AS payment_totals

    ON payment_totals.reference_no = cart.reference_no


/*
|--------------------------------------------------------------------------
| DT 5 - SHOULD BE PAID
|--------------------------------------------------------------------------
|
| Always uses variations.price.
|
*/

LEFT JOIN
(
    SELECT
        c.reference_no,

        SUM(
            v.price
        ) AS should_be_paid

    FROM cart c

    LEFT JOIN variations v
        ON v.variation_id = c.variation_id

    WHERE c.cart_status = 'Delivered'

    GROUP BY c.reference_no

) AS order_totals

    ON order_totals.reference_no = cart.reference_no

";


/*
|--------------------------------------------------------------------------
| DATATABLE RESPONSE
|--------------------------------------------------------------------------
*/

echo json_encode(

	SSP::simple(
		$_GET,
		$sql_details,
		$table,
		$primaryKey,
		$columns,
		$joinQuery,
		$where
	)

);

?>