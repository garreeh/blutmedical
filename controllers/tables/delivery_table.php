<?php

// Define table and primary key
$table = 'cart';
$primaryKey = 'cart_id';
// Define columns for DataTables
$columns = array(
	array(
		'db' => 'cart_id',
		'dt' => 0,
		'field' => 'cart_id',
		'formatter' => function ($lab1, $row) {
			return $row['cart_id'];
		}
	),

	array(
		'db' => 'paypal_order_id',
		'dt' => 1,
		'field' => 'paypal_order_id',
		'formatter' => function ($lab1, $row) {
			return ($row['payment_method'] === 'Paypal')
				? ($row['paypal_order_id'] ?: '-')
				: ($row['reference_no'] ?: '-');
		}
	),

	array(
		'db' => 'users.user_fullname',
		'dt' => 2,
		'field' => 'user_fullname',
		'formatter' => function ($lab2, $row) {
			return empty($row['user_fullname']) ? $row['delivery_guest_fullname'] : $row['user_fullname'];
		}
	),

	array(
		'db' => 'cart_status',
		'dt' => 3,
		'field' => 'cart_status',
		'formatter' => function ($lab3, $row) {

			$cart_status = $row['cart_status'];

			// Define styles for different statuses
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

	array(
		'db' => 'payment_method',
		'dt' => 4,
		'field' => 'payment_method',
		'formatter' => function ($lab4, $row) {
			return $row['payment_method'];
		}
	),

	array(
		'db' => 'proof_of_payment',
		'dt' => 5,
		'field' => 'proof_of_payment',
		'formatter' => function ($lab4, $row) {
			return '<a class="fetchCustomerDetails" href="#"> Click to View</a> ';
		}
	),

	array(
		'db' => 'cart_id',
		'dt' => 6,
		'field' => 'cart_id',
		'formatter' => function ($lab4, $row) {
			return '<a class="fetchOrderDetails" href="#"> Click to View</a> ';
		}
	),

	array(
		'db' => 'total_price',
		'dt' => 7,
		'field' => 'total_price',
		'formatter' => function ($lab4, $row) {
			// Check if the payment method is GCash, use Peso sign, otherwise Dollar sign
			if ($row['payment_method'] == 'GCash') {
				return '₱ ' . number_format($row['total_price'], 2);
			} else {
				return '$ ' . number_format($row['total_price'], 2);
			}
		}
	),

	array(
		'db' => 'cart_id',
		'dt' => 8,
		'field' => 'cart_id',
		'formatter' => function ($lab5, $row) {
			return '
      <div class="dropdown">
          <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['cart_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              &#x22EE;
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['cart_id'] . '">
              <a class="dropdown-item fetchDataFinish" href="#">Complete Order</a>

          </div>
      </div>';
		}
	),

	array(
		'db' => 'delivery_guest_fullname',
		'dt' => 9,
		'field' => 'delivery_guest_fullname',
		'formatter' => function ($lab5, $row) {
			return $row['delivery_guest_fullname'];
		}
	),

	array(
		'db' => 'reference_no',
		'dt' => 10,
		'field' => 'reference_no',
		'formatter' => function ($lab5, $row) {
			return $row['reference_no'];
		}
	),
);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class.php');

// THIS IS A SAMPLE ONLY
$where = "cart_status = 'Shipped'";

// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

$joinQuery = "FROM $table LEFT JOIN users ON $table.user_id = users.user_id
				LEFT JOIN product ON $table.product_id = product.product_id
				LEFT JOIN variations ON $table.variation_id = variations.variation_id
				LEFT JOIN variations_colors ON $table.variation_color_id = variations_colors.variation_color_id";


// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
