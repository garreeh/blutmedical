<?php

include '../../connections/connections.php';

if (isset($_POST['edit_user_type'])) {

  $user_type_id = $conn->real_escape_string($_POST['user_type_id']);
  $user_type_name = $conn->real_escape_string($_POST['user_type_name']);

  $ship_order = isset($_POST['ship_order']) ? $conn->real_escape_string($_POST['ship_order']) : '0';
  $view_order = isset($_POST['view_order']) ? $conn->real_escape_string($_POST['view_order']) : '0';
  $client_order_module = isset($_POST['client_order_module']) ? $conn->real_escape_string($_POST['client_order_module']) : '0';
  $complete_order = isset($_POST['complete_order']) ? $conn->real_escape_string($_POST['complete_order']) : '0';
  $view_shipped_order = isset($_POST['view_shipped_order']) ? $conn->real_escape_string($_POST['view_shipped_order']) : '0';
  $shipped_order_module = isset($_POST['shipped_order_module']) ? $conn->real_escape_string($_POST['shipped_order_module']) : '0';
  $view_transaction_module = isset($_POST['view_transaction_module']) ? $conn->real_escape_string($_POST['view_transaction_module']) : '0';
  $sales_report_module = isset($_POST['sales_report_module']) ? $conn->real_escape_string($_POST['sales_report_module']) : '0';
  $product_setup_module = isset($_POST['product_setup_module']) ? $conn->real_escape_string($_POST['product_setup_module']) : '0';
  $user_setup = isset($_POST['user_setup']) ? $conn->real_escape_string($_POST['user_setup']) : '0';

  // User Setup
  $user_setup_add = isset($_POST['user_setup_add']) ? $conn->real_escape_string($_POST['user_setup_add']) : '0';
  $user_setup_edit = isset($_POST['user_setup_edit']) ? $conn->real_escape_string($_POST['user_setup_edit']) : '0';
  $user_setup_delete = isset($_POST['user_setup_delete']) ? $conn->real_escape_string($_POST['user_setup_delete']) : '0';

  // Product
  $product_add = isset($_POST['product_add']) ? $conn->real_escape_string($_POST['product_add']) : '0';
  $product_edit = isset($_POST['product_edit']) ? $conn->real_escape_string($_POST['product_edit']) : '0';
  $product_delete = isset($_POST['product_delete']) ? $conn->real_escape_string($_POST['product_delete']) : '0';

  // Supplier
  $supplier_module = isset($_POST['supplier_module']) ? $conn->real_escape_string($_POST['supplier_module']) : '0';
  $supplier_add = isset($_POST['supplier_add']) ? $conn->real_escape_string($_POST['supplier_add']) : '0';
  $supplier_edit = isset($_POST['supplier_edit']) ? $conn->real_escape_string($_POST['supplier_edit']) : '0';
  $supplier_delete = isset($_POST['supplier_delete']) ? $conn->real_escape_string($_POST['supplier_delete']) : '0';

  // Shop Category
  $shop_category_module = isset($_POST['shop_category_module']) ? $conn->real_escape_string($_POST['shop_category_module']) : '0';
  $shop_category_add = isset($_POST['shop_category_add']) ? $conn->real_escape_string($_POST['shop_category_add']) : '0';
  $shop_category_edit = isset($_POST['shop_category_edit']) ? $conn->real_escape_string($_POST['shop_category_edit']) : '0';

  // Item Category
  $item_category_module = isset($_POST['item_category_module']) ? $conn->real_escape_string($_POST['item_category_module']) : '0';
  $item_category_add = isset($_POST['item_category_add']) ? $conn->real_escape_string($_POST['item_category_add']) : '0';
  $item_category_edit = isset($_POST['item_category_edit']) ? $conn->real_escape_string($_POST['item_category_edit']) : '0';

  // Customer Cart
  $customer_cart_module = isset($_POST['customer_cart_module']) ? $conn->real_escape_string($_POST['customer_cart_module']) : '0';
  $customer_cart_remind = isset($_POST['customer_cart_remind']) ? $conn->real_escape_string($_POST['customer_cart_remind']) : '0';

  // Reports
  $report_product_ranking = isset($_POST['report_product_ranking']) ? $conn->real_escape_string($_POST['report_product_ranking']) : '0';
  $report_customer_details = isset($_POST['report_customer_details']) ? $conn->real_escape_string($_POST['report_customer_details']) : '0';
  $report_customer_details_excel = isset($_POST['report_customer_details_excel']) ? $conn->real_escape_string($_POST['report_customer_details_excel']) : '0';
  $report_customer_details_edit = isset($_POST['report_customer_details_edit']) ? $conn->real_escape_string($_POST['report_customer_details_edit']) : '0';
  $report_customer_details_delete = isset($_POST['report_customer_details_delete']) ? $conn->real_escape_string($_POST['report_customer_details_delete']) : '0';

  // Discount
  $discount_module = isset($_POST['discount_module']) ? $conn->real_escape_string($_POST['discount_module']) : '0';
  $discount_add = isset($_POST['discount_add']) ? $conn->real_escape_string($_POST['discount_add']) : '0';
  $discount_edit = isset($_POST['discount_edit']) ? $conn->real_escape_string($_POST['discount_edit']) : '0';
  $discount_delete = isset($_POST['discount_delete']) ? $conn->real_escape_string($_POST['discount_delete']) : '0';

  // Carousel
  $carousel_module = isset($_POST['carousel_module']) ? $conn->real_escape_string($_POST['carousel_module']) : '0';
  $carousel_add = isset($_POST['carousel_add']) ? $conn->real_escape_string($_POST['carousel_add']) : '0';
  $carousel_edit = isset($_POST['carousel_edit']) ? $conn->real_escape_string($_POST['carousel_edit']) : '0';
  $carousel_delete = isset($_POST['carousel_delete']) ? $conn->real_escape_string($_POST['carousel_delete']) : '0';

  // Currency Settings
  $peso_currency_settings = isset($_POST['peso_currency_settings']) ? $conn->real_escape_string($_POST['peso_currency_settings']) : '0';
  $update_currency_settings = isset($_POST['update_currency_settings']) ? $conn->real_escape_string($_POST['update_currency_settings']) : '0';

  // Construct SQL query for UPDATE
  $sql = "UPDATE `usertype`
            SET
                user_type_name = '$user_type_name',
                ship_order = '$ship_order',
                view_order = '$view_order',
                client_order_module = '$client_order_module',
                complete_order = '$complete_order',
                view_shipped_order = '$view_shipped_order',
                shipped_order_module = '$shipped_order_module',
                view_transaction_module = '$view_transaction_module',
                sales_report_module = '$sales_report_module',
                product_setup_module = '$product_setup_module',
                user_setup = '$user_setup',

                user_setup_add = '$user_setup_add',
                user_setup_edit = '$user_setup_edit',
                user_setup_delete = '$user_setup_delete',

                product_add = '$product_add',
                product_edit = '$product_edit',
                product_delete = '$product_delete',

                supplier_module = '$supplier_module',
                supplier_add = '$supplier_add',
                supplier_edit = '$supplier_edit',
                supplier_delete = '$supplier_delete',

                shop_category_module = '$shop_category_module',
                shop_category_add = '$shop_category_add',
                shop_category_edit = '$shop_category_edit',

                item_category_module = '$item_category_module',
                item_category_add = '$item_category_add',
                item_category_edit = '$item_category_edit',

                customer_cart_module = '$customer_cart_module',
                customer_cart_remind = '$customer_cart_remind',

                report_product_ranking = '$report_product_ranking',
                report_customer_details = '$report_customer_details',
                report_customer_details_excel = '$report_customer_details_excel',
                report_customer_details_edit = '$report_customer_details_edit',
                report_customer_details_delete = '$report_customer_details_delete',

                discount_module = '$discount_module',
                discount_add = '$discount_add',
                discount_edit = '$discount_edit',
                discount_delete = '$discount_delete',

                carousel_module = '$carousel_module',
                peso_currency_settings = '$peso_currency_settings',
                update_currency_settings = '$update_currency_settings',
                carousel_add = '$carousel_add',
                carousel_edit = '$carousel_edit',
                carousel_delete = '$carousel_delete'

            WHERE user_type_id = '$user_type_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    $response = array(
      'success' => true,
      'message' => 'User Type updated successfully!'
    );
    echo json_encode($response);
    exit();
  } else {
    $response = array(
      'success' => false,
      'message' => 'Error updating user: ' . mysqli_error($conn)
    );
    echo json_encode($response);
    exit();
  }
}