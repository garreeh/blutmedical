<?php
include './../../connections/connections.php';

// Define table and primary key
$table = 'product';
$primaryKey = 'product_id';

session_start();

$user_type_id = $_SESSION['user_type_id']; // Assume this is set upon login

$sql = "SELECT *
        FROM usertype 
        WHERE user_type_id = '$user_type_id'";
$result = mysqli_query($conn, $sql);
$row_permission = mysqli_fetch_assoc($result);


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
        'formatter' => function ($lab5, $row) use ($row_permission) {

            $actions = '';

            // EDIT permission
            if ($row_permission['product_edit'] == 1) {
                $actions .= '
                <a class="dropdown-item fetchDataProduct" href="#">
                    Edit
                </a>
            ';
            }

            // DELETE permission
            if ($row_permission['product_delete'] == 1) {
                $actions .= '
                <a class="dropdown-item fetchDataProductDelete" href="#">
                    Delete
                </a>
            ';
            }

            // fallback
            if ($actions == '') {
                return '<span class="text-muted">No actions</span>';
            }

            return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" data-toggle="dropdown">
                    &#x22EE;
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['product_id'] . '">
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

$where = "product_id";

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
