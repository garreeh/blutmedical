<?php

include '../../connections/connections.php';

if (isset($_POST['add_product'])) {

    // Initialize response array
    $response = array('success' => false, 'message' => '');

    $target_dir = "../../uploads/";
    $uploaded_main_file = "";
    $main_picture = NULL; // Set main_picture to NULL initially
    $additional_files_uploaded = [];

    // Handle the main product image (single file)
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $main_filename = $_FILES["fileToUpload"]["name"];
        $main_target_file = $target_dir . $main_filename;
        $main_imageFileType = strtolower(pathinfo($main_target_file, PATHINFO_EXTENSION));

        // Validate file types
        if (!in_array($main_imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'pdf'])) {
            $response['message'] = "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed for the main product image.";
            echo json_encode($response);
            exit();
        }

        // Attempt file upload for the main product image
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $main_target_file)) {
            $uploaded_main_file = basename($main_filename); // Save the file name
            $main_picture = $target_dir . $uploaded_main_file;
        } else {
            $response['message'] = "Sorry, there was an error uploading the main product image.";
            echo json_encode($response);
            exit();
        }
    }

    // Get form data
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $product_sku = $conn->real_escape_string($_POST['product_sku']);
    $product_description = $conn->real_escape_string($_POST['product_description']);
    $product_sellingprice = $conn->real_escape_string($_POST['product_sellingprice']);
    $supplier_id = $conn->real_escape_string($_POST['supplier_id']);
    $category_id = $conn->real_escape_string($_POST['category_id']);
    $subcategory_id = $conn->real_escape_string($_POST['subcategory_id']);


    // Insert product into the `product` table
    $sql = "INSERT INTO `product` (product_name, product_sku, product_description, product_sellingprice, product_stocks, product_image, supplier_id, category_id, subcategory_id)
            VALUES ('$product_name', '$product_sku', '$product_description', '$product_sellingprice', '0', '$main_picture', '$supplier_id', '$category_id', '$subcategory_id')";

    if (mysqli_query($conn, $sql)) {
        $product_id = $conn->insert_id; // Get the last inserted product ID
        $response['success'] = true;
        $response['message'] = 'Product added successfully!';

        if (isset($_POST['value']) && isset($_POST['price']) && isset($_POST['product_code'])) {
            $variation_names = $_POST['value'];
            $variation_prices = $_POST['price'];
            $variation_codes = $_POST['product_code'];

            foreach ($variation_names as $index => $variation_name) {
                $variation_name = $conn->real_escape_string($variation_name);
                $variation_price = $conn->real_escape_string($variation_prices[$index]);
                $variation_code = $conn->real_escape_string($variation_codes[$index]);

                // Insert into variations table
                $sql_variation = "INSERT INTO `variations` (product_id, `value`, price, product_code)
                              VALUES ('$product_id', '$variation_name', '$variation_price', '$variation_code')";
                mysqli_query($conn, $sql_variation);
            }
        }

        // Handle variation colors separately
        if (isset($_POST['color'])) {
            $variation_colors = $_POST['color'];

            foreach ($variation_colors as $color) {
                $color = $conn->real_escape_string($color);

                $sql_color = "INSERT INTO `variations_colors` (product_id, color)
                          VALUES ('$product_id', '$color')";
                mysqli_query($conn, $sql_color);
            }
        }

        // Handle additional product images (if any)
        if (!empty($_FILES["productImagePath"]["name"])) {
            foreach ($_FILES["productImagePath"]["name"] as $key => $image_name) {
                // Ensure each file has its own name
                $additional_filename = $_FILES["productImagePath"]["name"][$key];
                $additional_target_file = $target_dir . basename($additional_filename);

                $imageFileType = strtolower(pathinfo($additional_target_file, PATHINFO_EXTENSION));

                // Validate file types
                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'pdf'])) {
                    continue; // Skip invalid files
                }

                // Attempt upload
                if (move_uploaded_file($_FILES["productImagePath"]["tmp_name"][$key], $additional_target_file)) {
                    // Insert image into `product_images` table
                    $sql_images = "INSERT INTO `product_image` (product_id, product_image_path) 
                                   VALUES ('$product_id', '$additional_target_file')";
                    mysqli_query($conn, $sql_images);
                }
            }
        }
    } else {
        $response['message'] = 'Error adding product: ' . mysqli_error($conn);
    }

    echo json_encode($response);
    exit();
}
