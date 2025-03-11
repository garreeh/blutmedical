<?php

include '../../connections/connections.php';
// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
  exit;
}

// Allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Your Xendit API Key Live
// $apiKey = "xnd_production_ix3HduEa6QV2elDOTDkHzP9ZBS6PyKRyD4CmYPWEhRFF8YaKXKDFTwdkOIU2CBr";

// API KEY FOR TEST MODE
$apiKey = "xnd_development_UALkcpzM2t2axMBtO1AVa0Qq9g0rWLy8EfVxD7J7yXqXZoFWxKcbAIAtRO1DwYN";

// Generate a unique reference ID
$referenceId = "order-id-" . uniqid();

// Query to get the dollar_currency
$sqlCurrency = "SELECT dollar_currency FROM currency WHERE dollar_id = 1";
$resultCurrency = mysqli_query($conn, $sqlCurrency);

if ($resultCurrency && mysqli_num_rows($resultCurrency) > 0) {
  $rowCurrency = mysqli_fetch_assoc($resultCurrency);
  $dollarCurrency = $rowCurrency['dollar_currency'];
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to fetch currency data.']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Decode the incoming JSON request data
  $jsonData = file_get_contents('php://input');
  if (!$jsonData) {
    echo json_encode(['status' => 'error', 'message' => 'No data received.']);
    exit;
  }

  $formData = json_decode($jsonData, true);
  if (is_null($formData) || !isset($formData['localStorageItems'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required data.']);
    exit;
  }

  $cartData = $formData['localStorageItems'];
  $payment_category = $formData['payment_category'];
  $delivery_guest_fullname = isset($formData['fullname']) ? $formData['fullname'] : null;
  $delivery_address = isset($formData['address']) ? $formData['address'] : null;
  $delivery_guest_contact_number = isset($formData['contact_number']) ? $formData['contact_number'] : null;
  $delivery_guest_email = isset($formData['email']) ? $formData['email'] : null;
  $totalAmount = 0;

  // Handle localStorage data
  foreach ($cartData as $item) {
    $product_id = $item['product_id'];
    $cart_quantity = $item['cart_quantity'];
    $variation_id = $item['variation_id'];
    $productPrice = ($variation_id === '-') ? $item['product_sellingprice'] : $item['price'];
    $productTotalPrice = $productPrice * $cart_quantity;

    // Multiply the total price by dollarCurrency
    $productTotalPrice *= $dollarCurrency;

    $totalAmount += $productTotalPrice; // Sum up all items

    // Insert into cart table
    $sql = "INSERT INTO cart (product_id, reference_no, cart_quantity, variation_id, total_price, cart_status, payment_method, delivery_guest_fullname, delivery_address, delivery_guest_contact_number, delivery_guest_email, payment_status) 
                        VALUES ('$product_id', '$referenceId', '$cart_quantity', '$variation_id', '$productTotalPrice', 'Cart', 'GCash', '$delivery_guest_fullname', '$delivery_address', '$delivery_guest_contact_number', '$delivery_guest_email', 'Unpaid')";

    if (!mysqli_query($conn, $sql)) {
      throw new Exception('Error saving cart: ' . mysqli_error($conn));
    }
  }

  // Define payment details
  $paymentMethod = "GCash";
  $referenceNo = strtoupper(bin2hex(random_bytes(3)));

  $data = [
    "reference_id" => $referenceId,
    "currency" => "PHP",
    "amount" => $totalAmount,
    "checkout_method" => "ONE_TIME_PAYMENT",
    "channel_code" => "PH_GCASH",
    "channel_properties" => [
      "success_redirect_url" => "https://blutmedical.com/v2/thankyou_payment.php",
      "failure_redirect_url" => "https://blutmedical.com/v2/sorry.php"
    ]
  ];

  // Initialize cURL request
  $ch = curl_init('https://api.xendit.co/ewallets/charges');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  // Decode the response
  $result = json_decode($response, true);

  // Check if request was successful
  if (isset($result['actions'])) {
    $paymentUrl = $result['actions']['desktop_web_checkout_url'] ?? $result['actions']['mobile_web_checkout_url'] ?? '';
    echo json_encode(['success' => true, 'payment_url' => $paymentUrl, 'reference_id' => $referenceId]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Payment request failed.', 'error_details' => $result]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
