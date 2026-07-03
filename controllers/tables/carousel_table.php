<?php
include './../../connections/connections.php';

// Define table and primary key
$table = 'carousel';
$primaryKey = 'carousel_id';
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
    'formatter' => function ($lab6, $row) use ($row_permission) {

      $actions = '';

      // EDIT carousel permission
      if ($row_permission['carousel_edit'] == 1) {
        $actions .= '
                <a class="dropdown-item fetchDataCarousel" href="#">
                    Edit
                </a>
            ';
      }

      // DELETE carousel permission
      if ($row_permission['carousel_delete'] == 1) {
        $actions .= '
                <a class="dropdown-item fetchDataCarouselDelete" href="#">
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

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['carousel_id'] . '">
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

$where = "carousel_id";

// THIS IS A SAMPLE ONLY
// $joinQuery = "FROM $table
//               LEFT JOIN emp_users ON $table.emp_id = emp_users.emp_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
