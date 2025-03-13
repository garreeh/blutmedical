<?php
session_start(); // Start the session to get user_id
include './connections/connections.php';

// Live Webhook
$xenditXCallbackToken = 'MGy04Msqrgv08FW0ZLlyl7VAVhwn1ak8yBoy3j3IIOy3Osvv';

// Dummy Webhook
// $xenditXCallbackToken = 'DE4pAuRgm0SL8IAZHjH9LU0UdUBoo5XlzqLPJGEnvJaWaeZc';


// Get headers
$reqHeaders = getallheaders();
$xIncomingCallbackTokenHeader = "";

// Normalize header key names
foreach ($reqHeaders as $key => $value) {
  if (strtolower($key) === 'x-callback-token') {
    $xIncomingCallbackTokenHeader = $value;
    break;
  }
}

// Log headers for debugging
file_put_contents("headers_log.txt", print_r($reqHeaders, true));

// Validate Webhook Token
if ($xIncomingCallbackTokenHeader !== $xenditXCallbackToken) {
  file_put_contents("unauthorized_access_log.txt", print_r($reqHeaders, true));
  http_response_code(403);
  echo json_encode(["message" => "Forbidden"]);
  exit;
}

// Read the raw JSON request body **ONCE**
$rawRequestInput = file_get_contents("php://input");
file_put_contents("webhook_log.txt", $rawRequestInput); // Log raw data for debugging

// Decode JSON
$arrRequestInput = json_decode($rawRequestInput, true);

// Check if JSON is valid
if (!$arrRequestInput) {
  file_put_contents("debug_webhook_error_log.txt", "Invalid JSON received: " . $rawRequestInput);
  http_response_code(400);
  echo json_encode(["status" => "error", "message" => "Invalid JSON received"]);
  exit;
}

// Log decoded JSON data
file_put_contents("debug_webhook_log.txt", print_r($arrRequestInput, true));

// Extract required data
$_referenceId = $arrRequestInput['data']['reference_id'] ?? null;
$_status = $arrRequestInput['data']['status'] ?? null;
$user_id = $_SESSION['user_id'] ?? null; // Get user_id from session

// Process payment success
if ($_referenceId && $_status === 'SUCCEEDED') {
  try {
    // Update the order status to 'Processing' using reference_no (when user_id is not available)
    $updateOrderQuery = "UPDATE cart SET cart_status = 'Processing' WHERE reference_no = '$_referenceId'";
    if (!mysqli_query($conn, $updateOrderQuery)) {
      throw new Exception('Error updating order: ' . mysqli_error($conn));
    }

    // Send success response
    echo json_encode([
      "status" => "success",
      "message" => "Payment verified and order updated using reference_no"
    ]);
    exit;
  } catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    exit;
  }
} else {
  http_response_code(200);
  echo json_encode(["message" => "Payment not successful or missing data"]);
  exit;
}
