<?php
include '../../connections/connections.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize POST data
    $user_fullname = isset($_POST['user_fullname']) ? $conn->real_escape_string($_POST['user_fullname']) : '';
    $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
    $user_address = isset($_POST['user_address']) ? $conn->real_escape_string($_POST['user_address']) : '';
    $user_contact = isset($_POST['user_contact']) ? $conn->real_escape_string($_POST['user_contact']) : '';
    $user_email = isset($_POST['user_email']) ? $conn->real_escape_string($_POST['user_email']) : '';
    $user_password = isset($_POST['user_password']) ? $conn->real_escape_string($_POST['user_password']) : '';
    $user_type_id = isset($_POST['user_type_id']) ? $conn->real_escape_string($_POST['user_type_id']) : '';


    // Check if any field is empty
    if (empty($user_fullname) || empty($user_address) || empty($user_contact) || empty($user_email) || empty($user_password) || empty($user_type_id)) {
        $response = array('success' => false, 'message' => 'All fields are required.');
        echo json_encode($response);
        exit();
    }

    // Validate email format
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $response = array('success' => false, 'message' => 'Invalid email format.');
        echo json_encode($response);
        exit();
    }

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $response = array('success' => false, 'message' => 'Email already exists.');
        echo json_encode($response);
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    // Insert user into the database
    $sql = "INSERT INTO users (user_fullname, username, user_address, user_contact, user_email, user_password, user_confirm_password, is_admin, account_status, user_type_id) 
            VALUES ('$user_fullname', '$username', '$user_address', '$user_contact', '$user_email', '$hashed_password', '$user_password', '1', 'Inactive', '$user_type_id')";

    if (mysqli_query($conn, $sql)) {
        $response = array('success' => true, 'message' => 'User added successfully.');
    } else {
        $response = array('success' => false, 'message' => 'Registration failed. Please try again.');
    }

    echo json_encode($response);
    exit();
}

?>
