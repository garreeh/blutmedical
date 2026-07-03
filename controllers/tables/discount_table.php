<?php
include './../../connections/connections.php';

// Define table and primary key
$table = 'voucher';
$primaryKey = 'voucher_id';
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
    'db' => 'voucher_id',
    'dt' => 0,
    'field' => 'voucher_id',
    'formatter' => function ($lab1, $row) {
      return $row['voucher_id'];
    }
  ),

  array(
    'db' => 'voucher_code',
    'dt' => 1,
    'field' => 'voucher_code',
    'formatter' => function ($d, $row) {

      return '
        <span style="
            display:inline-block;
            background:#f8f9fa;
            color:#343a40;
            border:1px dashed #6c757d;
            border-radius:50px;
            padding:6px 14px;
            font-weight:700;
            font-size:13px;
            letter-spacing:1px;
        ">
            <i class="fas fa-tag"></i> ' . $row['voucher_code'] . '
        </span>';
    }
  ),

  array(
    'db' => 'voucher_percentage',
    'dt' => 2,
    'field' => 'voucher_percentage',
    'formatter' => function ($d, $row) {

      return '<span class="badge badge-info px-3 py-2">'
        . $row['voucher_percentage'] . '%'
        . '</span>';
    }
  ),

  array(
    'db' => 'voucher_status',
    'dt' => 3,
    'field' => 'voucher_status',
    'formatter' => function ($d, $row) {

      switch ($row['voucher_status']) {

        case 'Active':
          return '<span class="badge badge-success px-3 py-2">
                            <i class="fas fa-check-circle"></i> Active
                        </span>';

        case 'Inactive':
          return '<span class="badge badge-warning px-3 py-2">
                            <i class="fas fa-pause-circle"></i> Inactive
                        </span>';

        case 'Used':
          return '<span class="badge badge-secondary px-3 py-2">
                            <i class="fas fa-times-circle"></i> Used
                        </span>';

        default:
          return '<span class="badge badge-dark px-3 py-2">
                            <i class="fas fa-question-circle"></i> Unknown
                        </span>';
      }

    }
  ),

  array(
    'db' => 'updated_at',
    'dt' => 4,
    'field' => 'updated_at',
    'formatter' => function ($lab5, $row) {
      return date('Y-m-d', strtotime($row['updated_at']));
    }
  ),

  array(
    'db' => 'voucher_id',
    'dt' => 5,
    'field' => 'voucher_id',
    'formatter' => function ($lab6, $row) use ($row_permission) {

      $actions = '';

      // EDIT voucher permission
      if ($row_permission['discount_edit'] == 1) {
        $actions .= '
                <a class="dropdown-item fetchDataDiscount" href="#">
                    Edit
                </a>
            ';
      }

      // DELETE voucher permission
      if ($row_permission['discount_delete'] == 1) {
        $actions .= '
                <a class="dropdown-item fetchDataDiscountDelete" href="#">
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

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row['voucher_id'] . '">
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

$where = "voucher_id";

// THIS IS A SAMPLE ONLY
// $joinQuery = "FROM $table
//               LEFT JOIN emp_users ON $table.emp_id = emp_users.emp_id";

// Fetch and encode JOIN AND WHERE
// echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where));

// Fetch and encode ONLY WHERE
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where));
