<?php

// Define table and primary key
$table = 'purchase_order';
$primaryKey = 'purchase_order_id';
// Define columns for DataTables
$columns = array(
	array(
		'db' => 'purchase_order_id',
		'dt' => 0,
		'field' => 'purchase_order_id',
		'formatter' => function ($lab1, $row) {
			return $row['purchase_order_id'];
		}
	),

	array(
		'db' => 'purchase_number',
		'dt' => 1,
		'field' => 'purchase_number',
		'formatter' => function ($lab2, $row) {
			return $row['purchase_number'];
		}
	),

	array(
		'db' => 'supplier.supplier_name',
		'dt' => 2,
		'field' => 'supplier_name',
		'formatter' => function ($lab3, $row) {
			return $row['supplier_name'];
		}
	),

	array(
		'db' => 'product.product_name',
		'dt' => 3,
		'field' => 'product_name',
		'formatter' => function ($lab4, $row) {
			return $row['product_name'];
		}
	),

	array(
		'db' => 'quantity',
		'dt' => 4,
		'field' => 'quantity',
		'formatter' => function ($lab5, $row) {
			return $row['quantity'];
		}
	),

	array(
		'db' => 'purchase_order.created_at',
		'dt' => 5,
		'field' => 'created_at',
		'formatter' => function ($lab5, $row) {
			return $row['created_at'];
		}
	),

	array(
		'db' => 'purchase_order.updated_at',
		'dt' => 6,
		'field' => 'updated_at',
		'formatter' => function ($lab5, $row) {
			return $row['updated_at'];
		}
	),

	array(
		'db' => 'purchase_order_id',
		'dt' => 7,
		'field' => 'purchase_order_id',
		'formatter' => function ($lab6, $row) {

			return '
      <div class="dropdown">
          <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['purchase_order_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              &#x22EE;
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['purchase_order_id'] . '">
              <a class="dropdown-item fetchDataPurchase" href="#">Edit</a>
          </div>
      </div>';
		}
	),

);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class.php');


// THIS IS A SAMPLE ONLY
$where = "purchase_order_id > 0";

// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));

$joinQuery = "FROM $table LEFT JOIN supplier ON $table.supplier_id = supplier.supplier_id
													LEFT JOIN product on $table.product_id = product.product_id";

// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));
