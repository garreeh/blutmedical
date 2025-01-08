<?php

// Define table and primary key
$table = 'variations';
$primaryKey = 'variation_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'variation_id',
    'dt' => 0,
    'field' => 'variation_id',
    'formatter' => function ($lab1, $row) {
      return $row['variation_id'];
    }
  ),

  array(
    'db' => 'price',
    'dt' => 1,
    'field' => 'price',
    'formatter' => function ($lab2, $row) {
      return $row['price'];
    }
  ),

  array(
    'db' => 'value',
    'dt' => 2,
    'field' => 'value',
    'formatter' => function ($lab2, $row) {
      return $row['value'];
    }
  ),

  array(
    'db' => 'variation_id',
    'dt' => 3,
    'field' => 'variation_id',
    'formatter' => function ($lab6, $row) {

      return '
      <div class="dropdown">
          <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['variation_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              &#x22EE;
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['variation_id'] . '">
              <a class="dropdown-item fetchDataVariation" href="#">Edit</a>
          </div>
      </div>';
    }
  ),

);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class_with_where.php');

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

$where = "product_id = '$product_id'";

// THIS IS A SAMPLE ONLY
// $joinQuery = "FROM $table
//               LEFT JOIN product ON $table.product_id = product.product_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
