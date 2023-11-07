<?php
session_start();
include 'sql.php';


$user = htmlspecialchars($_SESSION["username"]);
$UserName = htmlspecialchars($_SESSION["name"]) . " " . htmlspecialchars($_SESSION["surname"]);

$Action=$_GET['Action'];
$Date=date("Y-m-d H:i:s");

//DATA DO KOREKT MAGAZYNU
//$data="2022-12-31";
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ZAPIS</title>
</head>

<body>

<?php


////  PZ-SAVE   ////////////////////

if ($Action == "PZ-SAVE"){

    $SupplierName=$_GET['SupplierName'];
    $InvoiceNumber=$_GET['InvoiceNumber'];
    $ProductName=$_GET['ProductName'];
    $ProductCode=$_GET['ProductCode'];
    $ProductVariant=$_GET['ProductVariant'];
    $Amount=$_GET['Amount'];
    $TotalPriceNet=$_GET['TotalPriceNet'];
    $Tax=$_GET['Tax'];
    $Category=$_GET['Category'];
    $Comments=$_GET['Comments'];

        if ($TotalPriceNet == "" || $Amount == ""){
            $UnitPrice = 0;
        }
        else{
            $UnitPrice = round(floatval($TotalPriceNet) / floatval($Amount), 2);
        }
        
        
        
        $sql = "INSERT INTO pz (date, supplier_name, invoice_number, product_name, product_code, category, unit_netto, total_netto, product_variant, order_id, vat, amount_come, amount, user, comments) VALUES ('$Date', '$SupplierName', '$InvoiceNumber', '$ProductName', '$ProductCode', '$Category', '$UnitPrice', '$TotalPriceNet', '$ProductVariant', '', '$Tax', '$Amount', '$Amount', '$user', '$Comments')";
             if (mysqli_query($link, $sql)) {
             } 
             else {
             echo "Error: " . $sql . "<br>" . mysqli_error($link);
            }

        $query = "SELECT id FROM pz;";
            // FETCHING DATA FROM DATABASE
            $result = mysqli_query($link, $query);
            
            if (mysqli_num_rows($result) > 0) {
                // OUTPUT DATA OF EACH ROW
                while($row = mysqli_fetch_assoc($result)) {
                $LastIdNumber = $row['id'];
            }
            } 
        
        // SAVE TO LOG    
        $ActionType = "PZ";
        $Content = "Dodano: " . $ProductName . " " . $ProductVariant . ",  " . $Amount .  " szt. na stan magazynowy.";
        $sql = "INSERT INTO log (user_id, user_name, type, type_id, time, content) VALUES ('$user', '$UserName', '$ActionType', '$LastIdNumber', '$Date', '$Content')";
            if (mysqli_query($link, $sql)) {
         } 
         else {
         echo "Error: " . $sql . "<br>" . mysqli_error($link);
         }
        // END OF LOG
              

}

////  WZ-SAVE   ////////////////////


if ($Action == "WZ-SAVE"){


   $AmountExtra=$_GET['AmountExtra'];
   $OrderNumber=$_GET['OrderNumber'];
   $Amount=$_GET['Amount'];
   $Comments=$_GET['Comments'];
   $pz=$_GET['pz']; 
    if ($AmountExtra != null){
        $Amount = $Amount + $AmountExtra;
    }

   

   $query = "SELECT * FROM pz WHERE id='$pz';";
   // FETCHING DATA FROM DATABASE
   $result = mysqli_query($link, $query);
   
   if (mysqli_num_rows($result) > 0) {
       // OUTPUT DATA OF EACH ROW
       while($row = mysqli_fetch_assoc($result)) {
       $ProductName = $row['product_name'];
       $ProductCode = $row['product_code'];
       $ProductVariant = $row['product_variant'];
       $UnitPrice = $row['unit_netto'];
       $Amount_pz = $row['amount'] - $Amount;
   }
   } 

    
    $sql = "INSERT INTO wz (date, order_id, pz_id, product_name, product_variant, product_code, unit_netto, amount, amount_extra, user, comments) VALUES ('$Date', '$OrderNumber', '$pz', '$ProductName', '$ProductVariant', '$ProductCode', '$UnitPrice', '$Amount', '$AmountExtra', '$user', '$Comments')";
         if (mysqli_query($link, $sql)) {
         } 
         else {
         echo "Error: " . $sql . "<br>" . mysqli_error($link);
        }
        

    $query = "SELECT id FROM wz;";
        // FETCHING DATA FROM DATABASE
        $result = mysqli_query($link, $query);
        
        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {
            $LastIdNumber = $row['id'];
        }
        } 
    
        // PZ CHANGE AMOUNT STATE

    $sql = "UPDATE pz SET amount='$Amount_pz' WHERE id='$pz'";
        if (mysqli_query($link, $sql)) {
            //echo "ok";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($link);
        }   

        
    
    // SAVE TO LOG    
    $ActionType = "WZ";
    $Content = "Wydano: " . $ProductName . " " . $ProductVariant . ",  " . $Amount .  " szt. z magazynu. PZ-" . $pz;
    $sql = "INSERT INTO log (user_id, user_name, type, type_id, time, content) VALUES ('$user', '$UserName', '$ActionType', '$LastIdNumber', '$Date', '$Content')";
        if (mysqli_query($link, $sql)) {
     } 
     else {
     echo "Error: " . $sql . "<br>" . mysqli_error($link);
     }
    // END OF LOG
          

}



///////



////  WZ-RETURN   ////////////////////


if ($Action == "WZ-RETURN"){

    $wz=$_GET['wz']; 
    
        // GET DETAILS ABOUT WZ, PZ NUMBER, AMOUNT ETC.
        $query = "SELECT * FROM wz WHERE id='$wz';";
         // FETCHING DATA FROM DATABASE
         $result = mysqli_query($link, $query);
         
         if (mysqli_num_rows($result) > 0) {
             // OUTPUT DATA OF EACH ROW
             while($row = mysqli_fetch_assoc($result)) {
             $pz = $row['pz_id'];
             $Amount = $row['amount'];
             $ProductName = $row['product_name'];
             $ProductVariant = $row['product_variant'];
             $ProductCode = $row['product_code'];
         }
         } 

         $Amount_pz = 0;
         // GET AMOUNT STATE FROM PZ
         $query = "SELECT id, amount FROM pz WHERE id='$pz';";
         // FETCHING DATA FROM DATABASE
         $result = mysqli_query($link, $query);
         
         if (mysqli_num_rows($result) > 0) {
             // OUTPUT DATA OF EACH ROW
             while($row = mysqli_fetch_assoc($result)) {
             $Amount_pz = $row['amount'];
         }
         }  
     
         // PZ CHANGE AMOUNT STATE

        $NewAmount = $Amount_pz + $Amount;
 
        $sql = "UPDATE pz SET amount='$NewAmount' WHERE id='$pz'";
         if (mysqli_query($link, $sql)) {
             //echo "ok";
         } else {
             echo "Error: " . $sql . "<br>" . mysqli_error($link);
         }   

         // WZ CHANGE AMOUNT STATE

        $sql = "UPDATE wz SET amount='0' WHERE id='$wz'";
         if (mysqli_query($link, $sql)) {
             //echo "ok";
         } else {
             echo "Error: " . $sql . "<br>" . mysqli_error($link);
         } 
 
         
     
     // SAVE TO LOG    
     $ActionType = "WZ";
     $Content = "Cofnięcie WZ: " . $ProductName . " " . $ProductVariant . ",  " . $ProductCode . ",  " . $Amount .  " szt. do magazynu. PZ-" . $pz;
     $sql = "INSERT INTO log (user_id, user_name, type, type_id, time, content) VALUES ('$user', '$UserName', '$ActionType', '$wz', '$Date', '$Content')";
         if (mysqli_query($link, $sql)) {
      } 
      else {
      echo "Error: " . $sql . "<br>" . mysqli_error($link);
      }
     // END OF LOG
     echo "cofanie wydania";      
 
 }
 
 
 
 ///////
 

//// UPDATE STOCK ARTILON

include "magazyn/update-stock.php";

?>


<script type="text/javascript">
setTimeout(function(){
$Action="<?php echo $Action; ?>";

if ($Action == "PZ-SAVE" ) { window.location.href = "magazyn/pz-add.php"; }
if ($Action == "WZ-SAVE" ) { window.location.href = "magazyn/index.php"; }
if ($Action == "WZ-RETURN" ) { window.location.href = "magazyn/wz-archive.php"; }

},
0);
</script>

</body>
</html>











