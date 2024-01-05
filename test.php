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

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://artilon-test.pl/api/orderList?token=mIuQHYxLYSXTWX63UYfJE0X75YKOjfsr2SeHaUHeyqJSXrcpOFauUZ2pqybwiPi8',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => 'UTF-8',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

curl_close($curl);

$response = json_decode(curl_exec($curl), true);

print_r($response['orders'][2]);




echo "<br><br>";
$licz=-1;
foreach ($response['orders'] as &$value) {
    $licz++;
    echo $value['id'] . " - " . $value['apiStatusText'] . " / ";
    
    if ($value['paid'] == true){
        echo "opłacone";
    }

    
    
    echo "<br>";

    echo "Produkty:<br>";

    foreach ($response['orders'][$licz]['orderedProducts'] as &$value2) {
        echo $value2['name'] . " " . $value2['amount'] . " " . $value2['productColor'] . " " . $value2['totalNetto'];
        echo "<br>";
    
    }
    echo "<br>";
    echo $value['comment'];
    
    if ($value['deliveryType'] == '1'){
        echo "<br>PILNE";
    }

    echo "<hr>";

    
}
echo "<hr>";


foreach ($response['orders'][2]['orderedProducts'] as &$value) {
    echo $value['name'] . " " . $value['amount'] . " " . $value['productColor'] . " " . $value['totalNetto'];
    echo "<br>";

}
echo "<hr>";












//print_r($response);





//var_dump(json_decode($response, true));


?>

</body>
</html>

