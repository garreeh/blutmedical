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
		'db' => 'user_address',
		'dt' => 1,
		'field' => 'user_address',
		'formatter' => function ($lab1, $row) {
			return $row['user_address'];
		}
	),

	array(
		'db' => 'users.user_fullname',
		'dt' => 2,
		'field' => 'user_fullname',
		'formatter' => function ($lab2, $row) {
			return $row['user_fullname'];
		}
	),

	array(
		'db' => 'cart_status',
		'dt' => 3,
		'field' => 'cart_status',
		'formatter' => function ($lab3, $row) {
			return $row['cart_status'];
		}
	),

	array(
		'db' => 'total_price',
		'dt' => 4,
		'field' => 'total_price',
		'formatter' => function ($lab4, $row) {
			return $row['total_price'];
		}
	),

	array(
		'db' => 'payment_method',
		'dt' => 5,
		'field' => 'payment_method',
		'formatter' => function ($lab4, $row) {
			return $row['payment_method'];
		}
	),

	array(
		'db' => 'proof_of_payment',
		'dt' => 6,
		'field' => 'proof_of_payment',
		'formatter' => function ($lab4, $row) {
			// Check if the value is null or empty
			if (empty($lab4)) {
				return 'COD';
			} else {
				return '<a class="ProofData" href="#"> View Image</a>';
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
              <a class="dropdown-item fetchDataFinish" href="#">Tag as Delivered</a>

          </div>
      </div>';
		}
	),
);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class.php');
include '../../connections/connections.php';

session_start();
$user_id = $_SESSION['user_id'];
$joinQuery = "FROM $table 
									 LEFT JOIN users ON $table.user_id = users.user_id
									 LEFT JOIN usertype ON users.user_type_id = usertype.user_type_id";

// Fetch the user type for the logged-in user
$query = "SELECT usertype.user_type_id FROM users 
								 LEFT JOIN usertype ON users.user_type_id = usertype.user_type_id 
								 WHERE users.user_id = '$user_id'";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['user_type_id'] == 1) {  // Assuming 1 is the Admin user_type_id
	$where = "cart_status = 'Out For Delivery'"; // Admin can see all deliveries
} else {
	$where = "cart_status = 'Out For Delivery' AND delivery_rider_id = $user_id"; // Non-admins see only their assigned deliveries
}

// THIS IS A SAMPLE ONLY

// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));



// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
