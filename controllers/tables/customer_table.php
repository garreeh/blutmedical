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
        'db' => 'user_contact',
        'dt' => 4,
        'field' => 'user_contact',
        'formatter' => function ($lab6, $row) {
            // Format date to 'Y-m-d' (e.g., 2024-09-03)
            return $row['user_contact'];

        }
    ),

    array(
        'db' => 'account_status',
        'dt' => 5,
        'field' => 'account_status',
        'formatter' => function ($lab5, $row) {

            $account_status = strtolower($row['account_status']);

            if ($account_status == 'active') {
                $color = '#d4edda';   // light green
                $textColor = '#155724';
            } else {
                $color = '#f8d7da';   // light red
                $textColor = '#721c24';
            }

            $width = '80px';
            $height = '30px';
            $border_radius = '10px';

            return '<span style="
            display:inline-block;
            background-color:' . $color . ';
            color:' . $textColor . ';
            width:' . $width . ';
            height:' . $height . ';
            border-radius:' . $border_radius . ';
            text-align:center;
            line-height:' . $height . ';
            font-size:12px;
            font-weight:500;
        ">' . ucfirst($account_status) . '</span>';
        }
    ),

    array(
        'db' => 'user_id',
        'dt' => 6,
        'field' => 'user_id',
        'formatter' => function ($lab6, $row) {
            return '
                <div class="dropdown">
                    <button class="btn btn-info" type="button" id="dropdownMenuButton' . $row['user_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['user_id'] . '">
                        <a class="dropdown-item fetchDataUserSetAdmin" href="#">Set Admin / Unset Admin</a>
                        <a class="dropdown-item fetchDataUserDelete" href="#">Delete</a>

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
$where = "is_admin = '0' AND (is_deleted = 0 OR is_deleted IS NULL)";

// Fetch and encode data
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
