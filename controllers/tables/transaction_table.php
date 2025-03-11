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
			return empty($row['paypal_order_id']) ? '-' : $row['paypal_order_id'];
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
		'db' => 'cart_id',
		'dt' => 5,
		'field' => 'cart_id',
		'formatter' => function ($lab4, $row) {
			return '<a class="fetchCustomerDetails" href="#"> Click to View</a> ';
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
		'db' => 'cart.updated_at',
		'dt' => 7,
		'field' => 'updated_at',
		'formatter' => function ($lab5, $row) {
			return $row['updated_at'];
		}
	),

	array(
		'db' => 'delivery_guest_fullname',
		'dt' => 8,
		'field' => 'delivery_guest_fullname',
		'formatter' => function ($lab5, $row) {
			return $row['delivery_guest_fullname'];
		}
	),
);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class.php');

// Build the where condition
$where = "cart_status = 'Delivered'";

// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

$joinQuery = "FROM $table LEFT JOIN users ON $table.user_id = users.user_id";

// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
