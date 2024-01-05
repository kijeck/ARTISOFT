<?php
// Initialize the session
session_start();
include "sql.php";
$Date=date("Y-m-d H:i:s");
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}



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
$cur_id = 0;
$neworders = false;


foreach ($response['orders'] as &$value) {

    $order_id = $value['id'];

    // check order in DB

    $query2 = "SELECT number FROM orders  WHERE number = $order_id";
                // FETCHING DATA FROM DATABASE
                $result2 = mysqli_query($link, $query2);
                
                if (mysqli_num_rows($result2) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row2 = mysqli_fetch_assoc($result2)) {
                            //echo "order: " . $row2['number'] . " exist.<br>";
                            $cur_id = $row2['number'];
                    }
                } 
    
                if ($order_id != $cur_id and $order_id > 30){
                    
                    if ($order_id != 0){
                        //echo "Dodaję: " . $order_id . "<br>";
                        addorder($order_id);

                    }   
                }
                else{
                    //echo $order_id . "dodać<br>";
                    //echo $cur_id . " istnieje<br>";
                    //addorder($cur_id);
                }


}



// FUNCTION ADD ORDER WITH PARAMAETER

function addorder2($test){
    echo $test . " dodane<br>";
}

function addorder($number_of_order) {

    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $link->set_charset('utf8');
     
    // Check connection
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

$url = "https://artilon-test.pl/api/orderDetails/".$number_of_order."?token=mIuQHYxLYSXTWX63UYfJE0X75YKOjfsr2SeHaUHeyqJSXrcpOFauUZ2pqybwiPi8";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
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


$licz=-1;
foreach ($response as &$value) {
    $licz++;
    
    if (!$value['clientData'] == null){
  
    }

    // VARIABLES

    $number = $value['id'];
    $invoiceNumber = $value['invoiceNumber'];
    $dateOfOrder = $value['orderDate'];
    $comment = $value['comment'];
    $payment = "brak";
    $paid = $value['paid'];
    $mode = $value['deliveryType'];
    //$mainStatus = $value['apiStatusText'];
    $mainStatus = 'złożone';
    $source = "sklep";
    if ($value['user'] == null){
        $orderGuardian = '';
    }
    else{
        $orderGuardian = $value['user']['id'];
    }
    
    $clientName = $value['clientData']['name'];
    $email = $value['clientData']['email'];
    $phoneNumber = $value['clientData']['telephone'];
    $companyName = $value['clientData']['companyName'];
    $nip = $value['clientData']['nip'];
    $address = $value['clientData']['address'];
    $zipCode = $value['clientData']['zipCode'];
    $city = $value['clientData']['city'];
    $country = $value['clientData']['country'];

    if ($orderGuardian == '17'){
        $orderGuardian = 'pk';
        $mainStatus = 'przyjęte';
    }

    $shippingName = $value['clientData']['nameShipping'];
    $shippingCompanyName = $value['clientData']['companyNameShipping'];
    $shippingAddress = $value['clientData']['addressShipping'];
    $shippingNumberOfBuilding =  $value['clientData']['numberOfBuildingShipping'];
    $shippingZipCode =  $value['clientData']['zipCodeShipping'];
    $shippingCity =  $value['clientData']['cityShipping'];
    $shippingCountry = $value['clientData']['countryShipping'];



    // INSERT TO DB - ORDERS

        $sql = "INSERT INTO orders (number, invoice_number, date_of_order, comments, payment, paid, mode, main_status, source, order_guardian, client_name, email, phone_number, company_name, nip, address, zip_code, place, country, nameShipping, companyNameShipping, addressShipping, numberOfBuildingShipping, zipCodeShipping, cityShipping, countryShipping) 
        VALUES ('$number', '$invoiceNumber', '$dateOfOrder', '$comment' , '$payment' , '$paid' , '$mode' , '$mainStatus' , '$source' , '$orderGuardian' , '$clientName' , '$email' , '$phoneNumber' , '$companyName' , '$nip' , '$address' , '$zipCode' , '$city' , '$country' , '$shippingName' , '$shippingCompanyName' , '$shippingAddress' , '$shippingNumberOfBuilding' , '$shippingZipCode' , '$shippingCity' , '$shippingCountry' )";
            if (mysqli_query($link, $sql)) {
            } 
            else {
            echo "Error: " . $sql . "<br>" . mysqli_error($link);
            }    

    // END OF DB - ORDERS    


    // LIST OF PRODUCTS
    $grupa1 = '';
    $licz2=-1;
      foreach ($response['orders']['orderedProducts'] as &$value2) {
        $licz2++;

        // VARIABLES

        $productName = $value2['name'];
        $productCode = $value2['productVariant']['variantCode'];
        $productVariant = $value2['productVariant']['color'];
        $productGroup = $value['orderedProducts'][$licz2]['orderedProductGroup']['id'];
        $productAmount = $value2['amount'];
        $productTotalNetto = $value2['totalNetto'];
        $productUnitNetto = $value2['unitNetto'];
        $productComment = $value['orderedProducts'][$licz2]['orderedProductGroup']['comment'];
        $category = null;
        $vat = $value2['vat']*100;


        // INSERT TO DB - ORDERED PRODUCTS

        $sql = "INSERT INTO ordered_product (product_group, product_name, product_code, product_variant, category, amount, unit_netto, total_netto, vat, order_number, own_magazine, supplier, status, comment) 
        VALUES ('$productGroup', '$productName', '$productCode', '$productVariant', 'brak', '$productAmount', '$productUnitNetto', '$productTotalNetto', '$vat', '$number', '', '', '$mainStatus', '$productComment')";
            if (mysqli_query($link, $sql)) {
            } 
            else {
            echo "Error: " . $sql . "<br>" . mysqli_error($link);
            }    

        // END OF DB - ORDERED PRODUCTS  

        

        // LIST OF MARKINGS
        if ($grupa1 != $value2['name']){
            foreach ($value2['orderedProductMarkings'] as &$value3) {

            // VARIABLES

                $markingName =  $value3['technique']['technique']['name'];
                $markingCode = $value3['technique']['technique']['markingCode'];
                $markingNumberOfColor =  $value3['numberOfColors'];
                $markingPrice =  $value3['price'];
                $markingFullColor = 0;
                    if (str_contains($markingName, 'DTF')) {
                        $markingFullColor = 1;
                    }
                    if (str_contains($markingName, 'UV')) {
                        $markingFullColor = 1;
                    }
                    if (str_contains($markingName, 'SUBLI')) {
                        $markingFullColor = 1;
                    }

                $markingLocation = $value3['location']['name'];
                $markingUnits = $value3['location']['unitOfMeasure'];
                $markingPrintWidth = round($value3['location']['printWidth'],0);
                $markingPrintHeight = round($value3['location']['printHeight'],0);


                // INSERT TO DB - ORDERED PRODUCTS

                $sql = "INSERT INTO marking (order_id, ordered_product_group, marking_name, marking_code, number_of_colors, fullcolor, price, marking_location, units, print_width, print_height) 
                VALUES ('$number', '$productGroup', '$markingName', '$markingCode', '$markingNumberOfColor', '$markingFullColor', '$markingPrice', '$markingLocation', '$markingUnits', '$markingPrintWidth', '$markingPrintHeight')";
                    if (mysqli_query($link, $sql)) {
                    } 
                    else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($link);
                    }    

            // END OF DB - ORDERED PRODUCTS  

            
            }
   
        }
        $grupa1 = $value2['name'];

    }


}



}



?>
