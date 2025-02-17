<?php

// Define table and primary key
$table = 'subcategory';
$primaryKey = 'subcategory_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'subcategory_id',
    'dt' => 0,
    'field' => 'subcategory_id',
    'formatter' => function ($lab1, $row) {
      return $row['subcategory_id'];
    }
  ),

  array(
    'db' => 'subcategory_name',
    'dt' => 1,
    'field' => 'subcategory_name',
    'formatter' => function ($lab2, $row) {
      return $row['subcategory_name'];
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
    'db' => 'subcategory_id',
    'dt' => 4,
    'field' => 'subcategory_id',
    'formatter' => function ($lab6, $row) {

      return '
      <div class="dropdown">
          <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['subcategory_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              &#x22EE;
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['subcategory_id'] . '">
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

$where = "subcategory_id";

// THIS IS A SAMPLE ONLY
// $joinQuery = "FROM $table
//               LEFT JOIN emp_users ON $table.emp_id = emp_users.emp_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
