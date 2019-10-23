<?php
$orderId = $_POST["orderId"];
$orderAmount = $_POST["amount"];

echo $orderId . "|" . $orderAmount;
$host = "https://409267f1.ngrok.io";
$notifyUrl = $host. "/cf-checkout/notify.php";
$returnUrl = $host. "/cf-checkout/return.php";

$orderDetails = array();
$orderDetails["notifyUrl"] = $notifyUrl;
$orderDetails["returnUrl"] = $returnUrl;

$userDetails = getUserDetails($orderId);
$order = getOrderDetails($orderId);

$orderDetails["customerName"] = $userDetails["customerName"];
$orderDetails["customerEmail"] = $userDetails["customerEmail"];
$orderDetails["customerPhone"] = $userDetails["customerPhone"];

$orderDetails["orderId"] = $order["orderId"];
$orderDetails["orderAmount"] = $order["orderAmount"];
$orderDetails["orderNote"] = $order["orderNote"];
$orderDetails["orderCurrency"] = $order["orderCurrency"];

$orderDetails["appId"] = "20676c22aaeaaa285cb03a657602";

$orderDetails["signature"] = generateSignature($orderDetails);

echo json_encode($orderDetails);

function generateSignature($postData){
  $secretKey = "9ebdf33c5aabc748a3750b0879ec9efe53254e95";
 ksort($postData);
 $signatureData = "";
 foreach ($postData as $key => $value){
      $signatureData .= $key.$value;
 }
 $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
 $signature = base64_encode($signature);
 return $signature;
}
?>
 <form id="redirectForm" method="post" action="https://cashfree.com/billpay/checkout/post/submit">
    <input type="hidden" name="appId" value="<?php echo $orderDetails["appId"] ?>"/>
    <input type="hidden" name="orderId" value="<?php echo $orderDetails["orderId"] ?>"/>
    <input type="hidden" name="orderAmount" value="<?php echo $orderDetails["orderAmount"] ?>"/>
    <input type="hidden" name="orderCurrency" value="<?php echo $orderDetails["orderCurrency"] ?>"/>
    <input type="hidden" name="orderNote" value="<?php echo $orderDetails["orderNote"] ?>"/>
    <input type="hidden" name="customerName" value="<?php echo $orderDetails["customerName"] ?>"/>
    <input type="hidden" name="customerEmail" value="<?php echo $orderDetails["customerEmail"] ?>"/>
    <input type="hidden" name="customerPhone" value="<?php echo $orderDetails["customerPhone"] ?>"/>
    <input type="hidden" name="returnUrl" value="<?php echo $orderDetails["returnUrl"] ?>"/>
    <input type="hidden" name="notifyUrl" value="<?php echo $orderDetails["notifyUrl"] ?>"/>
    <input type="hidden" name="signature" value="<?php echo $orderDetails["signature"] ?>"/>
  </form>

  <script>document.getElementById("redirectForm").submit();</script>

<?php


function getUserDetails($orderId) {
    return array(
      "customerName" => "rohit",
      "customerEmail" => "rohit@cashfree.com",
      "customerPhone" => "812341231"
    );
}

function getOrderDetails($orderId) {
  return array(
    "orderId" => time(),
    "orderAmount" => "10",
    "orderNote" => "test order",
    "orderCurrency" => "INR"
  );
}



 ?>
