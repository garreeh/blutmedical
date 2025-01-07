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
    'db' => 'product_name',
    'dt' => 1,
    'field' => 'product_name',
    'formatter' => function ($lab2, $row) {
      return $row['product_name'];
    }
  ),

  array(
    'db' => 'attribute',
    'dt' => 2,
    'field' => 'attribute',
    'formatter' => function ($lab2, $row) {
      return $row['attribute'];
    }
  ),

  array(
    'db' => 'value',
    'dt' => 3,
    'field' => 'value',
    'formatter' => function ($lab2, $row) {
      return $row['value'];
    }
  ),

  array(
    'db' => 'variation_id',
    'dt' => 4,
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
require('../../assets/datatables/ssp.class.php');

$where = "variation_id";

// THIS IS A SAMPLE ONLY
$joinQuery = "FROM $table
              LEFT JOIN product ON $table.product_id = product.product_id";

// Fetch and encode JOIN AND WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
