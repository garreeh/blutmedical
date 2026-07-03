<?php
include './../../connections/connections.php';

// Define table and primary key
$table = 'users';
$primaryKey = 'user_id';
// Define columns for DataTables


session_start();
$user_type_id = $_SESSION['user_type_id']; // Assume this is set upon login

$sql = "SELECT *
        FROM usertype 
        WHERE user_type_id = '$user_type_id'";
$result = mysqli_query($conn, $sql);
$row_permission = mysqli_fetch_assoc($result);

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
        'db' => 'user_email',
        'dt' => 2,
        'field' => 'user_email',
        'formatter' => function ($lab3, $row) {
            return $row['user_email'];
        }
    ),
    array(
        'db' => 'account_status',
        'dt' => 3,
        'field' => 'account_status',
        'formatter' => function ($lab5, $row) {
            $account_status = $row['account_status'];
            $color = '#90EE90'; // Light Red
            $width = '70px'; // Adjust the value as needed
            $height = '30px'; // Adjust the value as needed
            $border_radius = '10px'; // Adjust the value as needed
            return '<span style="display: inline-block; background-color: ' . $color . '; width: ' . $width . '; height: ' . $height . '; border-radius: ' . $border_radius . '; text-align: center; line-height: ' . $height . ';">' . $account_status . '</span>';
        }
    ),
    array(
        'db' => 'created_at',
        'dt' => 4,
        'field' => 'created_at',
        'formatter' => function ($lab5, $row) {
            // Format date to 'Y-m-d' (e.g., 2024-09-03)
            return date('Y-m-d', strtotime($row['created_at']));
        }
    ),

    array(
        'db' => 'updated_at',
        'dt' => 5,
        'field' => 'updated_at',
        'formatter' => function ($lab5, $row) {
            // Format date to 'Y-m-d' (e.g., 2024-09-03)
            return date('Y-m-d', strtotime($row['updated_at']));
        }
    ),

    array(
        'db' => 'user_id',
        'dt' => 6,
        'field' => 'user_id',
        'formatter' => function ($lab6, $row) use ($row_permission) {

            $actions = '';
            // EDIT USER permission
            if ($row_permission['user_setup_edit'] == 1) {
                $actions .= '
                <a class="dropdown-item fetchDataUser" href="#">
                    Edit
                </a>
            ';
            }

            // DELETE USER permission
            if ($row_permission['user_setup_delete'] == 1) {
                $actions .= '
                <a class="dropdown-item fetchDataUserDelete" href="#">
                    Delete
                </a>
            ';
            }

            // fallback if no permission
            if ($actions == '') {
                return '<span class="text-muted">No actions</span>';
            }

            return '
            <div class="dropdown">
                <button class="btn btn-info" type="button" data-toggle="dropdown">
                    &#x22EE;
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['user_id'] . '">
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

// Define where clause if needed
$where = "is_admin = 1 AND (is_deleted = 0 OR is_deleted IS NULL)";

// Fetch and encode data
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
