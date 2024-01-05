<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php


$curl = curl_init();

$order_id = 40;
$url = "https://artilon-test.pl/api/orderSet/" . $order_id;

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'mIuQHYxLYSXTWX63UYfJE0X75YKOjfsr2SeHaUHeyqJSXrcpOFauUZ2pqybwiPi8','status' => '3','paid' => 'true'),
));

$response = curl_exec($curl);

curl_close($curl);




?>

</body>
</html>

