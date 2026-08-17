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
$apiKey = "xnd_production_bVjZslIVmC9NeJsiEVha5hFY9fSYMZewWjdzjGHcvvWbQ4hOGlWqUMNW5nhxKjU";

// API KEY FOR TEST MODE
// $apiKey = "xnd_development_UALkcpzM2t2axMBtO1AVa0Qq9g0rWLy8EfVxD7J7yXqXZoFWxKcbAIAtRO1DwYN";

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
$voucher_id = (int) ($input['voucher_id'] ?? 0);

$user_id = $_SESSION['user_id'] ?? null;
$paymentMethod = "GCash";
$referenceId = strtoupper(bin2hex(random_bytes(3)));

if (!$user_id) {
  echo json_encode([
    'success' => false,
    'message' => 'User not authenticated.'
  ]);
  exit;
}

// ==============================
// RESET CART (ALWAYS REMOVE VOUCHER FIRST)
// ==============================
$sql = "
UPDATE cart
SET
    voucher_id = 0,
    reference_no = NULL,
    payment_method = NULL,
    payment_status = NULL
WHERE user_id = '$user_id'
AND cart_status = 'Cart'
";

if (!mysqli_query($conn, $sql)) {
  echo json_encode([
    'success' => false,
    'message' => mysqli_error($conn)
  ]);
  exit;
}

$discountAmount = 0;

// ==============================
// APPLY VOUCHER IF SELECTED
// ==============================
if ($voucher_id > 0) {

  $voucherQuery = mysqli_query($conn, "
        SELECT *
        FROM voucher
        WHERE voucher_id = '$voucher_id'
        LIMIT 1
    ");

  if (!$voucherQuery || mysqli_num_rows($voucherQuery) == 0) {
    echo json_encode([
      'success' => false,
      'message' => 'Voucher not found.'
    ]);
    exit;
  }

  $voucher = mysqli_fetch_assoc($voucherQuery);

  if ($voucher['voucher_status'] != 'Active') {
    echo json_encode([
      'success' => false,
      'message' => 'Voucher has expired.'
    ]);
    exit;
  }

  // Total cart quantity
  $cartQuery = mysqli_query($conn, "
        SELECT IFNULL(SUM(cart_quantity),0) total_qty
        FROM cart
        WHERE user_id='$user_id'
        AND cart_status='Cart'
    ");

  $cart = mysqli_fetch_assoc($cartQuery);

  $totalQty = (int) $cart['total_qty'];
  $minimumCart = (int) $voucher['minimum_cart'];

  if ($totalQty < $minimumCart) {
    echo json_encode([
      'success' => false,
      'message' => 'Voucher expired or minimum order not met.'
    ]);
    exit;
  }

  // Apply discount
  $voucherPercentage = (float) $voucher['voucher_percentage'];

  $discountAmount = ($amount * $voucherPercentage) / 100;

  $amount -= $discountAmount;

  if ($amount < 0) {
    $amount = 0;
  }
}

// ==============================
// CONVERT CURRENCY
// ==============================
$resultCurrency = mysqli_query($conn, "
    SELECT dollar_currency
    FROM currency
    WHERE dollar_id=1
");

if (!$resultCurrency || mysqli_num_rows($resultCurrency) == 0) {

  echo json_encode([
    'success' => false,
    'message' => 'Failed to fetch currency.'
  ]);
  exit;
}

$rowCurrency = mysqli_fetch_assoc($resultCurrency);

$conversionRate = (float) $rowCurrency['dollar_currency'];

// Convert USD to PHP
$amount = $amount * $conversionRate;

// IMPORTANT: Xendit only accepts max 2 decimal places
$amount = round($amount, 2);

// Ensure valid amount
if ($amount <= 0) {
  echo json_encode([
    'success' => false,
    'message' => 'Invalid payment amount.'
  ]);
  exit;
}

// ==============================
// FINAL UPDATE CART
// ==============================
$sql = "
UPDATE cart
SET
    reference_no='$referenceId',
    payment_method='GCash',
    payment_status='Unpaid',
    voucher_id='$voucher_id'
WHERE user_id='$user_id'
AND cart_status='Cart'
";

if (!mysqli_query($conn, $sql)) {

  echo json_encode([
    'success' => false,
    'message' => mysqli_error($conn)
  ]);
  exit;
}

// ==============================
// XENDIT DATA
// ==============================
$data = [
  "reference_id" => $referenceId,
  "currency" => "PHP",
  "amount" => $amount,
  "checkout_method" => "ONE_TIME_PAYMENT",
  "channel_code" => "PH_GCASH",
  "channel_properties" => [
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

  $paymentUrl = $result['actions']['desktop_web_checkout_url']
    ?? $result['actions']['mobile_web_checkout_url'];

  echo json_encode([
    'success' => true,
    'payment_url' => $paymentUrl,
    'reference_id' => $referenceId
  ]);

} else {

  echo json_encode([
    'success' => false,
    'message' => 'Payment request failed.',
    'xendit_response' => $result,
    'raw_response' => $response
  ]);
}
