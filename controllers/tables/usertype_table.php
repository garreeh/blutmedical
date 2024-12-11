<?php

// Define table and primary key
$table = 'usertype';
$primaryKey = 'user_type_id';
// Define columns for DataTables
$columns = array(
    array(
        'db' => 'user_type_id',
        'dt' => 0,
        'field' => 'user_type_id',
        'formatter' => function ($lab1, $row) {
            return $row['user_type_id'];
        }
    ),
    array(
        'db' => 'user_type_name',
        'dt' => 1,
        'field' => 'user_type_name',
        'formatter' => function ($lab2, $row) {
            return $row['user_type_name'];
        }
    ),

    array(
        'db' => 'created_at',
        'dt' => 2,
        'field' => 'created_at',
        'formatter' => function ($lab3, $row) {
            // Format date to 'Y-m-d' (e.g., 2024-09-03)
            return date('Y-m-d', strtotime($row['created_at']));
        }
    ),

    array(
        'db' => 'updated_at',
        'dt' => 3,
        'field' => 'updated_at',
        'formatter' => function ($lab4, $row) {
            // Format date to 'Y-m-d' (e.g., 2024-09-03)
            return date('Y-m-d', strtotime($row['updated_at']));
        }
    ),

    array(
        'db' => 'user_type_id',
        'dt' => 4,
        'field' => 'user_type_id',
        'formatter' => function ($lab5, $row) {
            return '
                <div class="dropdown">
                    <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['user_type_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['user_type_id'] . '">
                        <a class="dropdown-item fetchDataUserType" href="#">Modify Access</a>
                    </div>
                </div>';
        }
    ),

);

// Database connection details
include '../../connections/ssp_connection.php';

// Include the SSP class
require('../../assets/datatables/ssp.class_with_where.php');

// Define where clause if needed
$where = "user_type_id";

// Fetch and encode data
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
