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
            return $row['product_id'];
        }
    ),

    array(
        'db' => 'product_sku',
        'dt' => 1,
        'field' => 'product_sku',
        'formatter' => function ($lab1, $row) {
            return $row['product_sku'];
        }
    ),

    array(
        'db' => 'product_name',
        'dt' => 2,
        'field' => 'product_name',
        'formatter' => function ($lab2, $row) {
            return $row['product_name'];
        }
    ),

    array(
        'db' => 'product_description',
        'dt' => 3,
        'field' => 'product_description',
        'formatter' => function ($lab3, $row) {
            // Return an HTML <img> tag with the image path
            // $imageUrl = '../../uploads/' . basename($row['product_image']);
            // return '<img src="' . $imageUrl . '" alt="Product Image" style="max-width: 100px; height: auto;">';
            return '<a class="fetchDataProductDescription" href="#">View Details</a> ';
        }
    ),

    array(
        'db' => 'product_image',
        'dt' => 4,
        'field' => 'product_image',
        'formatter' => function ($lab3, $row) {
            // Return an HTML <img> tag with the image path
            // $imageUrl = '../../uploads/' . basename($row['product_image']);
            // return '<img src="' . $imageUrl . '" alt="Product Image" style="max-width: 100px; height: auto;">';
            return '<a class="fetchDataProductImage" href="#">View Image</a> ';
        }
    ),

    array(
        'db' => 'product_sellingprice',
        'dt' => 5,
        'field' => 'product_sellingprice',
        'formatter' => function ($lab2, $row) {
            return '$ ' . number_format($row['product_sellingprice'], 2);
        }
    ),


    array(
        'db' => 'product_id',
        'dt' => 6,
        'field' => 'product_id',
        'formatter' => function ($lab5, $row) {
            return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['product_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    &#x22EE;
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['product_id'] . '">
                    <a class="dropdown-item fetchDataProduct" href="#">Edit</a>
                    <a class="dropdown-item fetchDataProductDelete" href="#">Delete</a>

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

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
