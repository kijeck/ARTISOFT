<?php


$SearchWord = "";
$SearchWord=$_GET['item'];
$ClientName = $_GET['ClientName'];
$Status = $_GET['Status'];
$Category=$_GET['Category'];
$Payment=$_GET['Payment'];
$Guardian=$_GET['Guardian'];
$SortByClient="";
$SortByPayment="";
$SortByWord="";
$SortByCategory="";
$SortByStatus="";
$SortByGuardian="";
$CountTotal = 0;
$shipment_tax=0;
$Date=date("Y-m-d");
session_start();
$username = htmlspecialchars($_SESSION["username"]);


include "../sql.php";

include "../colors.php";



if ($ClientName != ""){
    $SortByClient = "AND orders.company_name='$ClientName'";
}

if ($Category != ""){
    $SortByCategory = "AND ordered_product.product_name='$Category'";
}

if ($Payment != ""){
    $SortByPayment = "AND orders.payment='$Payment'";
}

if ($Guardian!= ""){
    $SortByPayment = "AND orders.order_guardian='$Guardian'";
}

if ($Status == ""){
    $SortByStatus = "AND (ordered_product.status='przyjęte' OR ordered_product.status='wizualizacja' OR ordered_product.status='akceptacja' OR ordered_product.status='przygotowalnia' OR ordered_product.status='w produkcji' OR ordered_product.status='w realizacji' OR ordered_product.status='gotowe' )";
}
else{
    $SortByStatus = "AND ordered_product.status='$Status'";
}




if ($SearchWord != ""){

    $SortByWord = "AND (surname LIKE '%$SearchWord%' OR  username LIKE '%$SearchWord%' OR product_code LIKE '$SearchWord' OR status LIKE '%$SearchWord%' OR product_name LIKE '%$SearchWord%' OR product_variant LIKE '%$SearchWord%' OR description LIKE '%$SearchWord%' OR category LIKE '%$SearchWord%' OR order_number LIKE '$SearchWord' OR company_name LIKE '%$SearchWord%' OR client_name LIKE '%$SearchWord%' OR nip LIKE '%$SearchWord%'  OR email LIKE '%$SearchWord%'  OR address LIKE '%$SearchWord%' )";
}

$totalSumOrder = 0;
$vat=0;


        // TABLE HEADER

        echo "<table width='100%' cellpadding=7 cellspacing=0 border=0>";
        echo "<tr class='header-table'>";
        echo "<td width='50'></td>";
        echo "<td width='20'></td>";
        echo "<td width='70'>id</td>";
        echo "<td>Klient</td>";
        echo "<td>Produkt</td>";     
        echo "<td align='right'>wz</td>"; // WZ
        echo "<td width='80' align='right'>Ilość</td>";
        echo "<td width='250'>Znakowanie</td>";
        echo "<td width='30' align='center'></td>";  // FACE ICON - PRODUCTION INDICATOR
        
        echo "<td width='20'></td>";
        echo "<td width='100' align='center'>Cena</td>";
        echo "<td width='10' align='center'></td>";
        echo "<td width='80' align='center'>Przesyłka</td>";
        
        echo "<td width='100' align='right'>Termin</td>";
        echo "<td width='10'></td>";  //VERTICAL RED LINE ALERT
        echo "<td width='60' align='center'>Akcje</td>";
        echo "</tr>";


        $query = "SELECT DISTINCT * FROM orders LEFT JOIN ordered_product ON ordered_product.order_number = orders.number LEFT JOIN marking ON order_id = orders.number LEFT JOIN shipment ON shipment.orders_number = orders.number LEFT JOIN users ON orders.order_guardian = users.username WHERE ordered_product.id > 0 $SortByClient $SortByCategory $SortByStatus $SortByWord $SortByPayment $SortByGuardian GROUP BY product_group ORDER BY order_number DESC;";
        // FETCHING DATA FROM DATABASE  ////////////////////////////////////////////////////////////////////////////////^^^^^^^^^^^^^^GROUP BY ordered_product.product_group
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {
            $list_of_variants[] = "";

            $product_group = $row['product_group'];    
            $order_number = $row['order_number'];
            $vat = $row['vat'];
            
            $date_of_order = $row['date_of_order'];
            $client_name = $row['client_name'];
            $company_name = $row['company_name'];
            $payment = $row['payment'];
            $paid = $row['paid'];
            $due_date = $row['due_date']; 

            $variants_amount = 0;
            $sum_total_netto = 0;
            $sum_total_gross = 0;
                      
            // MAIN LIST OF ORDERS - ROWS IN MAIN TABLE

            echo "<tr class='row-table row-table-select-2'>";
            echo "<td valign='middle' align='center'>";
                
                // THUMB ICON
                    
                    echo "<a href='../images/product-0001.jpg' target='_blank'><img src='../images/product-0002_thumb.jpg' class='thumb-icon'></a>";

                // END

            echo "</td>";
            echo "<td>";
                        
                // STATUS ICONS
                
                echo "<div id='statusicon" .$row['id']. "' onclick='changestatus(1, " .$row['id']. ", ".$row['order_number']."), showwaiting(" .$row['id']. ")' style='cursor:pointer'>";

                    if ($row['status'] == 'złożone'){
                        echo "<div ><img src='../images/status-zlozone.svg' class='status-icon'></div>";
                    }   
                    if ($row['status'] == 'przyjęte'){
                        echo "<div ><img src='../images/status-przyjete.svg' class='status-icon'></div>";
                    } 
                    if ($row['status'] == 'wizualizacja'){
                        echo "<div ><img src='../images/status-wizualizacja.svg' class='status-icon'></div>";
                    }  
                    if ($row['status'] == 'akceptacja'){
                        echo "<div ><img src='../images/status-akceptacja.svg' class='status-icon'></div>";
                    }  
                    if ($row['status'] == 'przygotowalnia'){
                        echo "<div ><img src='../images/status-przygotowalnia.svg' class='status-icon'></div>";
                    }  
                    if ($row['status'] == 'w realizacji'){
                        echo "<div ><img src='../images/status-w-realizacji.svg' class='status-icon'></div>";
                    }     
                    if ($row['status'] == 'w produkcji'){
                        echo "<div ><img src='../images/status-w-produkcji.svg' class='status-icon'></div>";
                    }      
                    if ($row['status'] == 'gotowe'){
                        echo "<div ><img src='../images/status-gotowe.svg' class='status-icon'></div>";
                    } 
                echo "</div>";

                echo "<div style='display: none' id='statuswaiting" .$row['id']. "' class='spinner'></div>";
                echo "<div style='display: block' id='statusiconclick" .$row['id']. "')'></div>";
            

                // END
                    
            $date_of_order = date("d-m-Y", strtotime($date_of_order));  

            echo "</td>";
            echo "<td>".$row['order_number']."<div class='lowerfont' ondblclick='changestatus(2, " .$row['id']. ", ".$row['order_number']." ), showwaiting(" .$row['id']. ")'>" . $date_of_order . "</div></td>";
            echo "<td>".$row['company_name']. " <div class='lowerfont'>" . $client_name . "</div></td>";
            

                        
            echo "<td>";
            echo $row['product_name'] . " <span style='font-weight:500'>".$row['description']. "</span>";
            echo "<br>";
                

                // LIST OF VARIANTS AND SUM TOGETHER IN 1 POSITION

                $variantsarray = array();
            

                $query2 = "SELECT * FROM ordered_product  WHERE product_group = '$product_group' AND order_number = '$order_number'";
                // FETCHING DATA FROM DATABASE
                $result2 = mysqli_query($link, $query2);
                
                if (mysqli_num_rows($result2) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row2 = mysqli_fetch_assoc($result2)) {

                        $variants_amount = $variants_amount + $row2['amount'];
                        $sum_total_netto = $sum_total_netto + $row2['total_netto'];
                        $productComment = $row2['comment'];

                        array_push($variantsarray, $row2['id']);
                        
                            //echo "<div id='".$row2['id']."' class='order-variant'><a href='../magazyn/index.php?id=".$row2['id']."&amount=".$row2['amount']."&product_code=".$row2['product_code']."' target='_blank'><div class='color-icon-small' style='background-color:".ColorIcon($row2['product_variant'])."'></div></a><div style='display:inline-block'>". $row2['product_code'] ." - </div>". $row2['amount'] . " szt.</div>";
                            echo "<div  class='order-variant-2' title='".$row2['product_variant'] . " - " . $row2['amount']." szt.' onclick='variantshow(".$row2['id'].")' onmouseleave='varianthide(".$row2['id'].")'><div id='variant-".$row2['id']."' class='variant-box' ><div  class='order-variant-2' ><div class='color-icon-small' style='background-color:".ColorIcon($row2['product_variant'])."'></div>".$row2['product_variant']."</div><hr class='hr-2'><div>". $row2['product_code'] ."</div>". $row2['amount'] . " szt.<div><hr class='hr-2'><div class='action-icon'><a target='_blank' href='../magazyn/index.php?id=".$row2['id']."&amount=".$row2['amount']."&product_code=".$row2['product_code']."'><img src='../images/wz-icon-new.svg' width='100%' height='100%' border='0'></a></div></div></div><div style='display:inline-block'>". $row2['product_code'] ."</div></a></div></div></div>";
                        
                        //echo "<div id='".$row2['id']."'><a href='../magazyn/index.php?id=".$row2['id']."&amount=".$row2['amount']."&product_code=".$row2['product_code']."' target='_blank'><div class='color-icon-small' style='background-color:".ColorIcon($row2['product_variant'])."'></div></a></div>";
                    }
                }  
                

            echo "</td>";
            
            
            echo "<td align='right'>";
            
                // WZ INDICATORS

                $countwz=0;
                
                foreach ($variantsarray as &$value) {
                    $query3 = "SELECT * FROM wz LEFT JOIN users ON wz.user = users.username WHERE order_id = '$value'";
                    // FETCHING DATA FROM DATABASE
                    $result3 = mysqli_query($link, $query3);
        
                    if (mysqli_num_rows($result3) > 0) {
                        // OUTPUT DATA OF EACH ROW
                        while($row3 = mysqli_fetch_assoc($result3)) {
                            //echo $row3['username'];
                            $countwz = $countwz + $row3['amount'];
                            echo "<div class='action-icon' title='WZ-".$row3['id']." | ".$row3['product_variant']." ".$row3['product_code']." | ilość: ".$row3['amount']." | ".$row3['name']." ".$row3['surname']."'><img src='../images/wz-edit-2.svg' width='100%' height='100%' border='0'></div>";
                    }
                } 
                }

                if ($countwz >= $variants_amount){
                        echo "<div class='action-icon'><img src='../images/wz-ok.svg' width='100%' height='100%' border='0'></div>";
                
                }

                    //echo "<div class='wz-dot'><img src='../images/wz-icon-new.svg' width='100%' height='100%'></div>";
                    //echo "<div class='wz-dot'><img src='../images/wz-icon-new.svg' width='100%' height='100%'></div>";        
                    
                    /*
                    $query3 = "SELECT * FROM wz WHERE product_group = '$product_group' AND order_number = '$order_number'";
                // FETCHING DATA FROM DATABASE
                $result3 = mysqli_query($link, $query3);
        
                if (mysqli_num_rows($result3) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row3 = mysqli_fetch_assoc($result3)) {

                    }
                } 
                */

                // END

            echo "</td>";
            echo "<td align='right'>".$variants_amount." szt.</td>";
            

            echo "<td>";

                // MARKING INDOCATORS

                $ordered_product_group = $row['product_group'];
                
                $currentuser="";
                $ifmark=0;
                $markingCost = 0;
                $markingTotalCost = 0;
                $query3 = "SELECT * FROM marking WHERE ordered_product_group = '$ordered_product_group' AND order_id = '$order_number'";
                // FETCHING DATA FROM DATABASE
                $result3 = mysqli_query($link, $query3);

        
                if (mysqli_num_rows($result3) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row3 = mysqli_fetch_assoc($result3)) {
                        $ifmark++;
                        //echo "<img class='marking-icon' src='../images/marking/".$row3['marking_code']."-".$row3['number_of_colors'].".svg' title='".$row3['marking_location']."'>";
                        echo "<div style='display: inline-block'>";
                            echo "<div class='marking-line'></div>";
                            echo "<div class='marking-icon'>";
                            $markingCost = floatval($row3['price']);
                            
                            if ($row3['current']==true){
                                $currentmarkingclass = "currentmark";
                                $currentuser = $row3['user2'];
                            }
                            else{
                                $currentmarkingclass = "";
                            }
                                echo "<div class='marking-code-text ".$currentmarkingclass."'>".$row3['marking_code']."</div>";
                                echo "<div class='marking-location-text'>".substr(($row3['marking_location']),0,6)."</div>";
                                if ($row3['fullcolor']==true){
                                    echo "<div class='numberofcolorsfullcolor'></div>";
                                }
                                else{
                                    for ($i = 1; $i <= $row3['number_of_colors']; $i++) {
                                        echo "<div class='numberofcolors'></div>";
                                    }
                                }

                            
                            echo "</div>";
                        echo "</div>";   
                       
                    }
                }  
                if ($ifmark==0){
                    
                    echo "---";
                }

                
                

                // END

            echo "</td>";
            
            echo "<td align='center'>";
            
            
                // FACE ICON - IN PRODUCTION
                if ($currentuser != ''){
                    echo "<img src='../images/".$currentuser.".png' class='currentmarkavatar' title='Przemysław Kijewski'>";
                } 
                $currentuser="";

                // END
            
            echo "</td>";
            
            echo "<td align='center'>";

                // PAYMENT INDICATOR

                
                // NO PAYMENT
                if (($row['payment'] == 'przelew 7' || $row['payment'] == 'przelew 14' || $row['payment'] == 'przelew 21' || $row['payment'] == 'przelew 30') && $row['paid'] == false){
                    echo "<img src='../images/payment-bank-nopaid-2.svg' class='payment-icon' title='".$row['payment']."'>";
                }

                // PAID

                if (($row['payment'] == 'przelew 7' || $row['payment'] == 'przelew 14' || $row['payment'] == 'przelew 21' || $row['payment'] == 'przelew 30') && $row['paid'] == true){
                    echo "<img src='../images/payment-bank-paid.svg' class='payment-icon' title='".$row['payment']."'>";
                }

                // PRE PAYMENT

                if ($row['payment'] == 'przedpłata' && $row['paid'] == true){
                    echo "<img src='../images/payment-paid.svg' class='payment-icon' title='".$row['payment']."'>";
                }

                if ($row['payment'] == 'przedpłata' && $row['paid'] == false){
                    echo "<img src='../images/payment-waiting-2.svg' class='payment-icon' title='".$row['payment']."'>";
                }

                // CASH

                if ($row['payment'] == 'gotówka' && $row['paid'] == true){
                    echo "<img src='../images/payment-cash-paid.svg' class='payment-icon' title='".$row['payment']."'>";
                }

                if ($row['payment'] == 'gotówka' && $row['paid'] == false){
                    echo "<img src='../images/payment-cash.svg' class='payment-icon' title='".$row['payment']."'>";
                }

                echo "<img src='../images/payment-bank-paid.svg' class='payment-icon' title='".$row['payment']."'>";

            echo "</td>";
            $sum_total_netto = $sum_total_netto + $markingCost;
            $totalSumOrder = $totalSumOrder + $sum_total_netto;
            $sum_total_gross = $sum_total_netto * ((100+$vat)/100);
            

            echo "<td align='center'><div>" . sprintf('%01.2f', $sum_total_gross) . " zł</div><div class='lowerfont'>" .  sprintf('%01.2f', $sum_total_netto) ." zł</div></td>";
            
                // SHIPMENT DETAILS
               
          
                $total_shipment=0;
                $shipment_variant="";
                $shipment_indicator="";
                
                
                $query4 = "SELECT * FROM shipment WHERE orders_number = '$order_number'";
                // FETCHING DATA FROM DATABASE
                $result4 = mysqli_query($link, $query4);
        
                if (mysqli_num_rows($result4) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row4 = mysqli_fetch_assoc($result4)) {
                        
                        $shipment_tax = $row4['tax'];
                        if ($row4['noname'] == true){
                            $shipment_variant = "<div class='shipment_variant'>NONAME</div>";
                            $shipment_indicator = "<img src='../images/red-line-vert.svg' width='6' height='40'>";
                            
                        }
                        $total_shipment = $total_shipment + $row4['price_netto'];                      
                    }  
                } 

                $total_shipment = $total_shipment * (($shipment_tax/100)+1);


                // END
                echo "<td align='center'>";
                

            echo $shipment_indicator;
            echo "</td>";
            echo "<td align='center'>";
            echo "kurier"; 

                // SHIPMENTS RESULTS

                if ($total_shipment > 0){
                    echo sprintf('%01.2f', $total_shipment) . " zł";
                }

                echo $shipment_variant;
                
                // END
                        
            echo "</td>";
            
            echo "<td align='right'>";
            
                // DUE DATE DETAILS
                echo "2024-01-24"; 

                $date_before = new DateTime($due_date); // For today/now, don't pass an arg.
                $date_before->modify("-1 day");
                $date_before = $date_before->format("Y-m-d");
               

                $origin = date_create($due_date);
                $target = date_create($Date);
                $interval = date_diff($origin, $target);
                $interval = $interval->format('%a');


                if ($due_date == $Date){
                    echo $due_date;
                    echo "<div style='font-weight:1000; font-size: 12px;'>";
                    echo "DZIŚ";
                    echo "</div>";
                    

                }
                else if ($date_before == $Date){
                    echo $due_date;
                    echo "<div style='font-weight:1000; font-size: 12px;'>";
                    echo "JUTRO";
                    echo "</div>";
                }

   

                else
                {
                    echo "<div title='".$interval." dni po terminie'>" . $due_date . "</div>";
                    
                    if ($Date < $due_date){
                        echo "<div style='font-size: 14px;'>";
                        echo "<div>za ".$interval." dni</div>";
                        echo "</div>";
                    }

                }

            echo "</td>";


            echo "<td valign='middle'>";

                // ORDER TIME MODE - SLOW, NORMAL, FAST

                // IF SLOW
               
                    
                    if ($due_date == $Date){
                        echo "<img src='../images/red-line-vert.svg' width='6' height='40'></td>";
    
                    }

           
                    if ($due_date == $Date){
                        echo "<img src='../images/red-line-vert.svg' width='6' height='40'></td>";
                    }
                

              




                // END

            
            echo "<td align='right'>";
            
                // ACTION ICONS

                 // IF SLOW
                 if ($row['mode'] == 2){
                    
             
                    echo "<div class='mode-icon'><img src='../images/slow-mode.svg' width='25' height='25'></div>";

                }   

              
                //IF FAST
                
                if ($row['mode'] == 1){
                    

                    
                        echo "<div class='mode-icon'><img src='../images/fast-2-mode.svg' width='25' height='25'></div>";
                    
                } 
                
                
                //echo "<div class='action-icon'><a href='' target='_blank'><img src='../images/message-icon.svg' width='100%' height='100%'></a></div>";
                //echo "<div class='action-icon'><a href='' target='_blank'><img src='../images/close-icon.svg' width='100%' height='100%'></a></div>";
                echo "<div id='arrow-down-".$row['order_number']. $row['id']. "' class='action-icon' onclick='expandrow(".$row['order_number']. $row['id']. ",".$row['order_number'].",".$row['id'].")'><img src='../images/arrow-down.svg' width='100%' height='100%'></div>";
                echo "<div id='arrow-up-".$row['order_number']. $row['id']. "' style='display:none' class='action-icon' onclick='colapserow(".$row['order_number']. $row['id']. ")'><img src='../images/arrow-up.svg' width='100%' height='100%'></div>";
                

                // END

            echo "</td>";
            echo "</tr>";

                // SECOND ROW

            echo "<tr width='100%' style='display:none' id='row-".$row['order_number']. $row['id']. "'>";
            echo "<td valign='top'></td>";
            echo "<td valign='top'></td>";
            echo "<td valign='top' align='left'><div style='width=100%' class='lowerfontbold'>Opiekun</div>";

                // ORDER GUARDIAN - OWNER

                echo "<div><img src='../images/".$row['order_guardian'].".png' class='guardian-icon-inline' title='".$row['name']." " .$row['surname']."'></div>";

                echo "<div>".$row['name']."</div>";

            echo "</td>";
            echo "<td valign='top'><div class='lowerfontbold'>Kontakt</div>";
            
                // CLIENT DETAILS

                echo "<div>".$row['client_name']."</div>";
                echo "<div>tel. ".$row['phone_number']."</div>";
                echo "<div>".$row['email']."</div><br>";
                echo "<div class='lowerfontbold'>Uwagi</div>";
                echo "<div>".$row['comments']."</div>";

            echo "</td>";
            echo "<td colspan='2' valign='top'>";

            echo "<div class='lowerfontbold'>Warianty</div>";
                
            foreach ($variantsarray as &$value) {
                $query3 = "SELECT * FROM ordered_product WHERE id = '$value'";
                // FETCHING DATA FROM DATABASE
                $result3 = mysqli_query($link, $query3);
    
                if (mysqli_num_rows($result3) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row3 = mysqli_fetch_assoc($result3)) {
                        //echo $row3['username'];
                        echo "<div>";
                        echo "<div class='display:inline-block; padding:3px; color-icon-small' style='background-color:".ColorIcon($row3['product_variant'])."'></div>";
                        echo "<a target='_blank' href='../magazyn/index.php?id=".$row3['id']."&amount=".$row3['amount']."&product_code=".$row3['product_code']."'><div style='display:inline-block; padding:3px;'>" . $row3['product_code'] . "</div></a>";
                        echo "<div style='display:inline-block; padding:3px;'>" . $row3['product_variant'] . "</div>";
                        echo "<div style='display:inline-block; padding:3px;'>" . $row3['amount'] . " szt.</div>";
                        echo "</div>";
                }
            } 
            }
                echo "<br>";
                echo "<div class='lowerfontbold'>Uwagi do produktu</div>";
                // COMMENTS FROM CLIENT

                echo "<div>".$productComment."</div>";
        


            echo "</td>";     
            
            echo "<td  colspan='5' valign='top'><div class='lowerfontbold'>Komenatarze</div>";
                
                // COMMENTS TO MARKING

                echo "<div id='commentslist-".$row['order_number']. $row['id']. "'>";

                echo "</div>";

                echo "<div class='comments-box'>";
                
                    
                    echo "<div class='inline-element'><img src='../images/".$username.".png' class='comments-avatar'></div>";         
                    echo "<input id='CommentText-".$row['order_number']."".$row['id']."' class='inline-element comments-text' type='text' placeholder='...' onkeypress='commentpress(".$row['order_number'].",".$row['id'].")'></input>";
                    echo "<div class='inline-element comments-send-icon'><div class='action-icon' onclick='savecomment(1,".$row['order_number'].",".$row['id'].")' ><img src='../images/arrow-right.svg' width='100%' height='100%'></div>";
                echo "</div>";

            echo "</td>";
            echo "<td align='center' valign='top'></td>";

            // SHIPPING ADDRESS FROM ORDER

            echo "<td colspan='2' valign='top'><div class='lowerfontbold'>Adres do wysyłki </div>";

            echo "<div>" . $row['nameShipping'] . "</div>";
            echo "<div>" . $row['companyNameShipping'] . "</div>";
            echo "<div>" . $row['addressShipping'] . " " .  $row['numberOfBuildingShipping'] . "</div>";
            echo "<div>" . $row['zipCodeShipping'] . " " . $row['cityShipping'] . "</div>";
            echo "<div>" . $row['countryShipping'] . "</div>";
            
            // END 


            echo "</td>";
            echo "<td valign='top'></td>";
            echo "<td align='center' valign='top'></td>";
           
    
            echo "</tr>";

            }
        } 

        echo "razem: " . round(($totalSumOrder),2) . " zł netto / " . round(($totalSumOrder * ((100+$vat)/100)),2) . " zł brutto";
        $testowy = "5";
?>
<style>
.spinner {
    width: 18px;
    height: 18px;
    border-radius: 80%;
    background: radial-gradient(farthest-side,#000000 10%,#0000) 30% 0.5px/3.8px 5.8px no-repeat,
         radial-gradient(farthest-side,#0000 calc(100% - 4px),rgba(0,0,0,0.1) 0);
    animation: spinner-aur408 0.8s infinite linear;
 }
 
 @keyframes spinner-aur408 {
    to {
       transform: rotate(1turn);
    }
 }
</style>
<br>




