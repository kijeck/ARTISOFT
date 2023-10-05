<?php


$SearchWord = "";
$SearchWord=$_GET['item'];
$ClientName = $_GET['ClientName'];
$Status = $_GET['Status'];
$Category=$_GET['Category'];
$Payment=$_GET['Payment'];
$SortByClient="";
$SortByWord="";
$SortByCategory="";
$SortByStatus="";
$CountTotal = 0;
$shipment_tax=0;
$Date=date("Y-m-d");


include "../sql.php";

include "../colors.php";

if ($ClientName != ""){
    $SortByClient = "AND orders.company_name='$ClientName'";
}

if ($Category != ""){
    $SortByCategory = "AND ordered_product.product_name='$Category'";
}

if ($Status == ""){
    $SortByStatus = "AND (ordered_product.status='przyjęte' OR ordered_product.status='wizualizacja' OR ordered_product.status='akceptacja' OR ordered_product.status='w produkcji' OR ordered_product.status='w realizacji' OR ordered_product.status='gotowe' )";
}
else{
    $SortByStatus = "AND ordered_product.status='$Status'";
}




if ($SearchWord != ""){

    $SortByWord = "AND (product_code LIKE '%$SearchWord%' OR status LIKE '%$SearchWord%' OR product_name LIKE '%$SearchWord%' OR product_variant LIKE '%$SearchWord%' OR description LIKE '%$SearchWord%' OR category LIKE '%$SearchWord%' OR 'marking' LIKE '%$SearchWord%'  OR marking_code LIKE '%$SearchWord%'  OR amount LIKE '%$SearchWord%' OR order_number LIKE '%$SearchWord%' OR company_name LIKE '%$SearchWord%' OR client_name LIKE '%$SearchWord%' OR nip LIKE '%$SearchWord%'  OR email LIKE '%$SearchWord%'  OR address LIKE '%$SearchWord%'  OR place LIKE '%$SearchWord%'  OR phone_number LIKE '%$SearchWord%'  OR number LIKE '%$SearchWord%'  OR user LIKE '%$SearchWord%')";
}


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
        echo "<td width='30' align='center'></td>";  // FACE ICON - PRODUCTION INDICATOR
        echo "<td>Znakowanie</td>";
        echo "<td width='20'></td>";
        echo "<td  width='100' align='center'>Cena</td>";
        echo "<td width='10' align='center'></td>";
        echo "<td width='80' align='center'>Przesyłka</td>";
        
        echo "<td width='100' align='right'>Termin</td>";
        echo "<td width='10'></td>";  //VERTICAL RED LINE ALERT
        echo "<td width='90' align='center'>Akcje</td>";
        echo "</tr>";


        $query = "SELECT DISTINCT * FROM orders LEFT JOIN ordered_product ON ordered_product.order_number = orders.number LEFT JOIN marking ON ordered_product_number = ordered_product.product_group LEFT JOIN shipment ON shipment.orders_number = orders.number  WHERE ordered_product.id > 0 $SortByClient $SortByCategory $SortByStatus $SortByWord GROUP BY product_group ORDER BY order_number DESC;";
        // FETCHING DATA FROM DATABASE  ////////////////////////////////////////////////////////////////////////////////^^^^^^^^^^^^^^GROUP BY ordered_product.product_group
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {
            $wynik = 0;
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

                if ($row['status'] == 'przyjęte'){
                    echo "<img src='../images/status-przyjete.svg' class='status-icon'>";
                }   
                if ($row['status'] == 'wizualizacja'){
                    echo "<img src='../images/status-wizualizacja.svg' class='status-icon'>";
                }  
                if ($row['status'] == 'akceptacja'){
                    echo "<img src='../images/status-akceptacja.svg' class='status-icon'>";
                }  
                if ($row['status'] == 'w realizacji'){
                    echo "<img src='../images/status-w-realizacji.svg' class='status-icon'>";
                }     
                if ($row['status'] == 'w produkcji'){
                    echo "<img src='../images/status-w-produkcji.svg' class='status-icon'>";
                }      
                if ($row['status'] == 'gotowe'){
                    echo "<img src='../images/status-gotowe.svg' class='status-icon'>";
                } 
            

                // END

            echo "</td>";
            echo "<td>".$row['order_number']. "-" .$row['id']. "<div class='lowerfont'>" . $date_of_order . "</div></td>";
            echo "<td>".$row['company_name']. " <div class='lowerfont'>" . $client_name . "</div></td>";
                        
            echo "<td>";
            echo $row['product_name'] . " <span style='font-weight:500'>".$row['description']. "</span>";
            echo "<br>";
                

                // LIST OF VARIANTS AND SUM TOGETHER IN 1 POSITION

                $query2 = "SELECT * FROM ordered_product WHERE product_group = '$product_group' AND order_number = '$order_number'";
                // FETCHING DATA FROM DATABASE
                $result2 = mysqli_query($link, $query2);
        
                if (mysqli_num_rows($result2) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row2 = mysqli_fetch_assoc($result2)) {
                        $variants_amount = $variants_amount + $row2['amount'];
                        $sum_total_netto = $sum_total_netto + $row2['total_netto'];
                        $wynik++;
                        echo "<div id='".$row2['id']."' class='order-variant'><a href='../magazyn/index.php?id=".$row2['id']."&amount=".$row2['amount']."&product_code=".$row2['product_code']."' target='_blank'><div class='color-icon-small' style='background-color:".ColorIcon($row2['product_variant'])."'></div></a><div style='display:inline-block'>". $row2['product_code'] ." - </div>". $row2['amount'] . " szt.</div>";
                    }
                }  

            echo "</td>";
            
            
            echo "<td align='right'>";
            
                // WZ INDICATORS

                    echo "<div class='wz-dot'><img src='../images/wz-icon-new.svg' width='100%' height='100%'></div>";
                    echo "<div class='wz-dot'><img src='../images/wz-icon-new.svg' width='100%' height='100%'></div>";               

                // END

            echo "</td>";
            echo "<td align='right'>".$variants_amount." szt.</td>";
            echo "<td align='center'>";
            
                // FACE ICON - IN PRODUCTION
                if ($row['status'] == 'w produkcji'){
                    echo "<img src='../images/".$row['user'].".png' class='face-icon-inline' title='Przemysław Kijewski'>";
                } 

                // END
            
            echo "</td>";

            echo "<td>";

                // MARKING INDOCATORS

                $ordered_product_number = $row['product_group'];

                $query3 = "SELECT * FROM marking WHERE ordered_product_number = '$ordered_product_number'";
                // FETCHING DATA FROM DATABASE
                $result3 = mysqli_query($link, $query3);
        
                if (mysqli_num_rows($result3) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row3 = mysqli_fetch_assoc($result3)) {

                        echo "<img class='marking-icon' src='../images/marking/".$row3['marking_code']."-".$row3['number_of_colors'].".svg' title='".$row3['marking_location']."'>";
                    }
                }  

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

            echo "</td>";

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

                // SHIPMENTS RESULTS

                if ($total_shipment > 0){
                    echo sprintf('%01.2f', $total_shipment) . " zł";
                }

                echo $shipment_variant;
                
                // END
                        
            echo "</td>";
            
            echo "<td align='right'>";
            
                // DUE DATE DETAILS


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
                if ($due_date == $Date){
                    echo "<img src='../images/red-line-vert.svg' width='6' height='40'></td>";

                }

                // END

            
            echo "<td align='center'>";
            
                // ACTION ICONS
                
                echo "<div class='action-icon'><a href='' target='_blank'><img src='../images/message-icon.svg' width='100%' height='100%'></a></div>";
                echo "<div class='action-icon'><a href='' target='_blank'><img src='../images/close-icon.svg' width='100%' height='100%'></a></div>";
                echo "<div class='action-icon'><a href='' target='_blank'><img src='../images/arrow-down.svg' width='100%' height='100%'></a></div>";
                

                // END

            echo "</td>";
            echo "</tr>";

            }
        } 


?>

<br>

