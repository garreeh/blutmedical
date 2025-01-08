<?php

// Define table and primary key
$table = 'product';
$primaryKey = 'product_id';
// Define columns for DataTables
$columns = array(
  array(
    'db' => 'product_id',
    'dt' => 0,
    'field' => 'product_id',
    'formatter' => function ($lab1, $row) {
      // return $row['product_id'];
      return '<a href="../admin/upload_images_module.php?product_id=' . $row['product_id'] . '">' . $row['product_id'] . '</a>';

    }
  ),

  array(
    'db' => 'product_name',
    'dt' => 1,
    'field' => 'product_name',
    'formatter' => function ($lab2, $row) {
      // return $row['product_name'];
      return '<a href="../admin/upload_images_module.php?product_id=' . $row['product_id'] . '">' . $row['product_name'] . '</a>';

    }
  ),

  array(
    'db' => 'product_id',
    'dt' => 2,
    'field' => 'product_id',
    'formatter' => function ($lab6, $row) {

      return '
      <div class="dropdown">
          <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['product_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              &#x22EE;
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['product_id'] . '">
              <a class="dropdown-item fetchDataVariation" href="../admin/upload_images_module.php?product_id=' . $row['product_id'] . '">Add Image</a>
              
          </div>
      </div>';
    }
  ),

);

// Database connection details
include '../../connections/ssp_connection.php';


// Include the SSP class
require('../../assets/datatables/ssp.class_with_where.php');

$where = "product_id";

// THIS IS A SAMPLE ONLY
// $joinQuery = "FROM $table
//               LEFT JOIN product ON $table.product_id = product.product_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
