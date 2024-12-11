<?php
include '../../connections/connections.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize POST data
    $user_type_name = isset($_POST['user_type_name']) ? $conn->real_escape_string($_POST['user_type_name']) : '';

    // Check if any field is empty
    if (empty($user_type_name)) {
        $response = array('success' => false, 'message' => 'All fields are required.');
        echo json_encode($response);
        exit();
    }

    // Check if email already exists
    $sql = "SELECT * FROM usertype WHERE user_type_name = '$user_type_name'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $response = array('success' => false, 'message' => 'User Type already exists.');
        echo json_encode($response);
        exit();
    }

    // Insert user into the database
    $sql = "INSERT INTO usertype (user_type_name) 
            VALUES ('$user_type_name')";

    if (mysqli_query($conn, $sql)) {
        $response = array('success' => true, 'message' => 'User Type added successfully.');
    } else {
        $response = array('success' => false, 'message' => 'Failed to add. Please try again.');
    }

    echo json_encode($response);
    exit();
}

?>
