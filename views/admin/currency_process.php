<?php
include './../../connections/connections.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["newCurrency"])) {
  $currency_rate = floatval($_POST['newCurrency']); // Convert to float

  // Update query using direct mysqli_query
  $sql = "UPDATE currency SET dollar_currency = $currency_rate WHERE dollar_id = 1";

  if (mysqli_query($conn, $sql)) {
    echo json_encode([
      "success" => true,
      "message" => "Currency rate updated successfully!",
      "new_currency" => $currency_rate
    ]);
  } else {
    echo json_encode(["success" => false, "message" => "Failed to update currency rate."]);
  }

  mysqli_close($conn);
}
?>