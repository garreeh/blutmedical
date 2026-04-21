<?php

// Define table and primary key
$table = 'users';
$primaryKey = 'user_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'user_id',
    'dt' => 0,
    'field' => 'user_id',
    'formatter' => function ($lab1, $row) {
      return $row['user_id'];
    }
  ),
  array(
    'db' => 'user_fullname',
    'dt' => 1,
    'field' => 'user_fullname',
    'formatter' => function ($lab2, $row) {
      return $row['user_fullname'];
    }
  ),

  array(
    'db' => 'username',
    'dt' => 2,
    'field' => 'username',
    'formatter' => function ($lab3, $row) {
      return $row['username'];
    }
  ),

  array(
    'db' => 'user_email',
    'dt' => 3,
    'field' => 'user_email',
    'formatter' => function ($lab3, $row) {
      return $row['user_email'];
    }
  ),

  array(
    'db' => 'user_id',
    'dt' => 4,
    'field' => 'user_id',
    'formatter' => function ($lab5, $row) {
      return '
        <div class="dropdown" style="display: inline-block !important; position: relative !important;">
            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton' . $row['user_id'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                &#x22EE;
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['user_id'] . '" style="z-index: 9999 !important;">
                <li><a class="dropdown-item fetchDataPassword" href="#">Change Details</a></li>
            </ul>
        </div>';
    }
  ),



);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class_with_where.php');

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

$where = "user_id = '$user_id' AND is_admin = '0'";

// Fetch and encode data
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
