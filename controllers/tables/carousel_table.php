<?php

// Define table and primary key
$table = 'carousel';
$primaryKey = 'carousel_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'carousel_id',
    'dt' => 0,
    'field' => 'carousel_id',
    'formatter' => function ($lab1, $row) {
      return $row['carousel_id'];
    }
  ),

  array(
    'db' => 'scene',
    'dt' => 1,
    'field' => 'scene',
    'formatter' => function ($lab2, $row) {
      return $row['scene'];
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
    'db' => 'carousel_id',
    'dt' => 3,
    'field' => 'carousel_id',
    'formatter' => function ($lab6, $row) {

      return '
      <div class="dropdown">
          <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['carousel_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              &#x22EE;
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['carousel_id'] . '">
              <a class="dropdown-item fetchDataCarousel" href="#">Edit</a>
              <a class="dropdown-item fetchDataCarouselDelete" href="#">Delete</a>

          </div>
      </div>';
    }
  ),



);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class_with_where.php');

$where = "carousel_id";

// THIS IS A SAMPLE ONLY
// $joinQuery = "FROM $table
//               LEFT JOIN emp_users ON $table.emp_id = emp_users.emp_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
