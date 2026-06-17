<?php

include '../../connections/connections.php';

require './../../assets/PHPMailer/src/Exception.php';
require './../../assets/PHPMailer/src/PHPMailer.php';
require './../../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['remind_specific_customer'])) {

    $cart_id = mysqli_real_escape_string($conn, $_POST['cart_id']);

    $sql = "
        SELECT
            product.product_id,
            users.user_email,
            users.user_fullname,
            product.product_name,
            product.product_sellingprice,
            cart.cart_quantity,
            variations.value AS variation_value,
            variations_colors.color
        FROM cart
        LEFT JOIN users ON users.user_id = cart.user_id
        LEFT JOIN product ON product.product_id = cart.product_id
        LEFT JOIN variations ON variations.variation_id = cart.variation_id
        LEFT JOIN variations_colors ON variations_colors.variation_color_id = cart.variation_color_id
        WHERE cart.cart_id = '$cart_id'
    ";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        $firstRow = mysqli_fetch_assoc($result);

        $email = $firstRow['user_email'];
        $fullname = $firstRow['user_fullname'];

        $tableRows = '';
        $grandTotal = 0;

        mysqli_data_seek($result, 0);

        while ($row = mysqli_fetch_assoc($result)) {

            $price = (float) $row['product_sellingprice'];
            $qty = (int) $row['cart_quantity'];
            $total = $price * $qty;

            $grandTotal += $total;

            $tableRows .= "
            <tr>
                <td style='padding:10px;border:1px solid #ddd;'>{$row['product_name']}</td>
                <td style='padding:10px;border:1px solid #ddd;'>{$row['variation_value']}</td>
                <td style='padding:10px;border:1px solid #ddd;'>{$row['color']}</td>
                <td style='padding:10px;border:1px solid #ddd;'>$ " . number_format($price, 2) . "</td>
                <td style='padding:10px;border:1px solid #ddd;text-align:center;'>{$qty}</td>
                <td style='padding:10px;border:1px solid #ddd;'>$ " . number_format($total, 2) . "</td>
            </tr>
            ";
        }

        try {

            $mail = new PHPMailer(true);


            // Enable temporarily while debugging
            $mail->SMTPDebug = 0;

            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;                                      // Enable SMTP authentication
            $mail->Username = 'admin@vetaidonline.info';             // SMTP username
            $mail->Password = 'Mybossrocks081677!';                        // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('admin@vetaidonline.info', 'VetAID Online');
            $mail->addAddress($email, $fullname);

            $mail->isHTML(true);
            $mail->Subject = 'Your Cart Is Waiting For You';

            $mail->Body = "
      <div style='font-family:Arial,Helvetica,sans-serif;background:#f4f6f9;padding:20px;'>

          <div style='max-width:800px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e5e5e5;'>

            <div style='background:#1f7ae0;padding:20px;color:#ffffff;'>

            <table width='100%' cellpadding='0' cellspacing='0' border='0'>
                <tr>

                <!-- LEFT LOGO -->
                <td width='20%' align='left' valign='middle'>
                    <img src='https://blutmedical.com/v2/controllers/admin/logo.jpeg'
                        alt='Logo'
                        style='height:100px;width:auto;border-radius:6px;'>
                </td>

                <!-- CENTER TITLE -->
                <td width='60%' align='center' valign='middle'>
                    <h2 style='margin:0;font-size:20px;'>
                    Your Cart Is Waiting!
                    </h2>
                </td>

                <!-- RIGHT SPACER -->
                <td width='20%'></td>

                </tr>
            </table>

            </div>

              <div style='padding:30px;'>

                  <p>Hi <strong>{$fullname}</strong>,</p>

                  <p>
                      We noticed that you left some items in your cart.
                      Complete your order today before these products become unavailable.
                  </p>

                  <table style='width:100%;border-collapse:collapse;margin-top:20px;'>

                      <thead>
                          <tr style='background:#1f7ae0;color:#ffffff;'>
                              <th style='padding:12px;border:1px solid #ddd;'>Product</th>
                              <th style='padding:12px;border:1px solid #ddd;'>Variation</th>
                              <th style='padding:12px;border:1px solid #ddd;'>Color</th>
                              <th style='padding:12px;border:1px solid #ddd;'>Price</th>
                              <th style='padding:12px;border:1px solid #ddd;'>Qty</th>
                              <th style='padding:12px;border:1px solid #ddd;'>Total</th>
                          </tr>
                      </thead>

                      <tbody>
                          {$tableRows}
                      </tbody>

                      <tfoot>
                          <tr style='background:#fafafa;font-weight:bold;'>
                              <td colspan='5'
                                  style='padding:12px;border:1px solid #ddd;text-align:right;'>
                                  Grand Total
                              </td>
                              <td style='padding:12px;border:1px solid #ddd;'>
                                  &#8369;" . number_format($grandTotal, 2) . "
                              </td>
                          </tr>
                      </tfoot>

                  </table>

                  <div style='text-align:center;margin-top:30px;'>

                      <a href='https://blutmedical.com/'
                        style='display:inline-block;
                                background:#1f7ae0;
                                color:#ffffff;
                                text-decoration:none;
                                padding:14px 28px;
                                border-radius:5px;
                                font-weight:bold;'>
                          Complete My Order
                      </a>

                  </div>

                  <p style='margin-top:30px;'>
                      Thank you for choosing <strong>VetAID Online</strong>.
                  </p>

                  <p style='color:#777;font-size:12px;margin-top:30px;'>
                      This is an automated reminder regarding items currently in your shopping cart.
                  </p>

              </div>

          </div>

      </div>
            ";

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'Reminder email sent successfully.'
            ]);

        } catch (Exception $e) {

            echo json_encode([
                'success' => false,
                'message' => $mail->ErrorInfo,
                'exception' => $e->getMessage()
            ]);
        }

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'No cart record found.'
        ]);
    }

    exit();
}