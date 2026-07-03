<?php
include './../../connections/connections.php';

// Define table and primary key
$table = 'supplier';
$primaryKey = 'supplier_id';
// Define columns for DataTables

session_start();
$user_type_id = $_SESSION['user_type_id']; // Assume this is set upon login

$sql = "SELECT *
        FROM usertype 
        WHERE user_type_id = '$user_type_id'";
$result = mysqli_query($conn, $sql);
$row_permission = mysqli_fetch_assoc($result);

$columns = array(
	array(
		'db' => 'supplier_id',
		'dt' => 0,
		'field' => 'supplier_id',
		'formatter' => function ($lab1, $row) {
			return $row['supplier_id'];
		}
	),

	array(
		'db' => 'supplier_name',
		'dt' => 1,
		'field' => 'supplier_name',
		'formatter' => function ($lab2, $row) {
			return $row['supplier_name'];
		}
	),

	array(
		'db' => 'supplier_name',
		'dt' => 2,
		'field' => 'supplier_name',
		'formatter' => function ($lab3, $row) {
			// return '<a href="#" class="fetchDataSupplierDetails" >Click to View Details</a> ';
			return '<a class="fetchDataSupplierDetails" href="#"> Click to View Details</a> ';
		}
	),

	array(
		'db' => 'created_at',
		'dt' => 3,
		'field' => 'created_at',
		'formatter' => function ($lab4, $row) {
			return date('Y-m-d', strtotime($row['created_at']));
		}
	),

	array(
		'db' => 'updated_at',
		'dt' => 4,
		'field' => 'updated_at',
		'formatter' => function ($lab5, $row) {
			return date('Y-m-d', strtotime($row['updated_at']));
		}
	),

	array(
		'db' => 'supplier_id',
		'dt' => 5,
		'field' => 'supplier_id',
		'formatter' => function ($lab6, $row) use ($row_permission) {

			$actions = '';

			// EDIT permission
			if ($row_permission['supplier_edit'] == 1) {
				$actions .= '
                <a class="dropdown-item fetchDataSupplier" href="#">
                    Edit
                </a>
            ';
			}

			// DELETE permission
			if ($row_permission['supplier_delete'] == 1) {
				$actions .= '
                <a class="dropdown-item fetchDataSupplierDelete" href="#">
                    Delete
                </a>
            ';
			}

			// fallback if no permission
			if ($actions == '') {
				return '<span class="text-muted">No actions</span>';
			}

			return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" data-toggle="dropdown">
                    &#x22EE;
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['supplier_id'] . '">
                    ' . $actions . '
                </div>
            </div>
        ';
		}
	),

);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class_with_where.php');

$where = "supplier_id";

// THIS IS A SAMPLE ONLY
// $joinQuery = "FROM $table
//               LEFT JOIN emp_users ON $table.emp_id = emp_users.emp_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
