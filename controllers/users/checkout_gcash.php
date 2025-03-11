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
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Your Xendit API Key Live
// $apiKey = "xnd_production_ix3HduEa6QV2elDOTDkHzP9ZBS6PyKRyD4CmYPWEhRFF8YaKXKDFTwdkOIU2CBr";

// API KEY FOR TEST MODE
$apiKey = "xnd_development_UALkcpzM2t2axMBtO1AVa0Qq9g0rWLy8EfVxD7J7yXqXZoFWxKcbAIAtRO1DwYN";

// Generate a unique reference ID
$referenceId = "order-id-" . uniqid();

// Read the incoming JSON data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
  echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
  exit;
}

session_start();

$amount = $input['amount'];
$user_id = $_SESSION['user_id'] ?? null;
$paymentMethod = "GCash";
$referenceNo = strtoupper(bin2hex(random_bytes(3)));

// Query to get the dollar_currency
$sqlCurrency = "SELECT dollar_currency FROM currency WHERE dollar_id = 1";
$resultCurrency = mysqli_query($conn, $sqlCurrency);

if ($resultCurrency && mysqli_num_rows($resultCurrency) > 0) {
  $rowCurrency = mysqli_fetch_assoc($resultCurrency);
  $dollarCurrency = $rowCurrency['dollar_currency'];

  // ✅ Convert the amount based on the dollar currency rate
  $amount *= $dollarCurrency;
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to fetch currency data.']);
  exit;
}

if ($user_id) {
  // ✅ Update the cart with the reference number, payment method, and updated total price
  $sql = "UPDATE cart 
          SET reference_no = '$referenceId', 
              payment_method = 'GCash', 
              payment_status = 'Unpaid', 
              total_price = '$amount'
          WHERE user_id = '$user_id' AND cart_status = 'Cart'";

  if (!mysqli_query($conn, $sql)) {
    echo json_encode(['success' => false, 'message' => 'Failed to update cart: ' . mysqli_error($conn)]);
    exit;
  }
} else {
  echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
  exit;
}

$data = [
  "reference_id" => $referenceId,
  "currency" => "PHP",
  "amount" => $amount,
  "checkout_method" => "ONE_TIME_PAYMENT",
  "channel_code" => "PH_GCASH",
  "channel_properties" => [
    // ✅ Change this for LIVE deployment
    "success_redirect_url" => "https://blutmedical.com/v2/thankyou_payment.php",
    "failure_redirect_url" => "https://blutmedical.com/v2/sorry.php"
  ]
];

$ch = curl_init('https://api.xendit.co/ewallets/charges');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result['actions']['desktop_web_checkout_url']) || isset($result['actions']['mobile_web_checkout_url'])) {
  $paymentUrl = isset($result['actions']['desktop_web_checkout_url']) ? $result['actions']['desktop_web_checkout_url'] : $result['actions']['mobile_web_checkout_url'];
  echo json_encode(['success' => true, 'payment_url' => $paymentUrl, 'reference_id' => $referenceId]);
} else {
  echo json_encode(['success' => false, 'message' => 'Payment request failed.']);
}
