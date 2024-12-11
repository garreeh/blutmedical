<?php

// Define table and primary key
$table = 'category';
$primaryKey = 'category_id';
// Define columns for DataTables
$columns = array(
	array(
		'db' => 'category_id',
		'dt' => 0,
		'field' => 'category_id',
		'formatter' => function ($lab1, $row) {
			return $row['category_id'];
		}
	),

	array(
		'db' => 'category_name',
		'dt' => 1,
		'field' => 'category_name',
		'formatter' => function ($lab2, $row) {
			return $row['category_name'];
		}
	),

	array(
		'db' => 'created_at',
		'dt' => 2,
		'field' => 'created_at',
		'formatter' => function ($lab4, $row) {
			return date('Y-m-d', strtotime($row['created_at']));
		}
	),

	array(
		'db' => 'updated_at',
		'dt' => 3,
		'field' => 'updated_at',
		'formatter' => function ($lab5, $row) {
			return date('Y-m-d', strtotime($row['updated_at']));
		}
	),

	array(
		'db' => 'category_id',
		'dt' => 4,
		'field' => 'category_id',
		'formatter' => function ($lab6, $row) {

			return '
      <div class="dropdown">
          <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['category_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              &#x22EE;
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['category_id'] . '">
              <a class="dropdown-item fetchDataCategory" href="#">Edit</a>
          </div>
      </div>';
		}
	),

);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class_with_where.php');

$where = "category_id";

// THIS IS A SAMPLE ONLY
// $joinQuery = "FROM $table
//               LEFT JOIN emp_users ON $table.emp_id = emp_users.emp_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
