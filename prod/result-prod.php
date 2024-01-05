<?php


$SearchWord = "";
$SearchWord=$_GET['item'];
$ClientName = $_GET['ClientName'];
$Status = $_GET['Status'];
$Marking=$_GET['Marking'];
$Guardian=$_GET['Guardian'];
$Category=$_GET['Category'];
$SortByClient="";
$SortByMarking="";
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

if ($Marking != ""){
    $SortByMarking = "AND marking.marking_name='$Marking'";
}

if ($Category != ""){
    $SortByCategory = "AND ordered_product.product_name='$Category'";
}

if ($Guardian!= ""){
    $SortByGuardian= "AND orders.order_guardian='$Guardian'";
}

if ($Status == ""){
    $SortByStatus = "AND (ordered_product.status='przygotowalnia' OR ordered_product.status='w realizacji' OR ordered_product.status='gotowe')";
}
else{
    $SortByStatus = "AND ordered_product.status='$Status'";
}


$totalTime = 0;



if ($SearchWord != ""){

    $SortByWord = "AND (marking_code LIKE '%$SearchWord%' OR  marking_name LIKE '%$SearchWord%' OR  surname LIKE '%$SearchWord%' OR  username LIKE '%$SearchWord%' OR product_code LIKE '$SearchWord' OR status LIKE '%$SearchWord%' OR product_name LIKE '%$SearchWord%' OR product_variant LIKE '%$SearchWord%' OR description LIKE '%$SearchWord%' OR category LIKE '%$SearchWord%' OR order_number LIKE '$SearchWord' OR company_name LIKE '%$SearchWord%' OR client_name LIKE '%$SearchWord%' OR nip LIKE '%$SearchWord%'  OR email LIKE '%$SearchWord%'  OR address LIKE '%$SearchWord%' )";
}

$totalSumOrder = 0;
$vat=0;



        // TABLE HEADER

        echo "<table width='100%' cellpadding=7 cellspacing=0 border=0 >";
        echo "<tr class='header-table'>";
        echo "<td width='20'></td>";
        echo "<td width='20'></td>";
        
        echo "<td width='50'></td>";
        echo "<td width='90'>id</td>";
        echo "<td colspan='3'>Znakowanie</td>";
      
        
        echo "<td>Produkt</td>";     
        echo "<td align='right'>wz</td>"; // WZ
        echo "<td width='80' align='right'>Ilość</td>";
        
        echo "<td width='100' align='center'>Cel</td>";        
        
        echo "<td width='100' align='right'>Termin</td>";
        echo "<td width='10'></td>";  //VERTICAL RED LINE ALERT
        echo "<td width='50' align='center'>Akcje</td>";
        echo "</tr>";


        $query = "SELECT * FROM marking LEFT JOIN ordered_product ON ordered_product.product_group = marking.ordered_product_group LEFT JOIN orders ON marking.order_id = orders.number LEFT JOIN users ON orders.order_guardian = users.username WHERE marking_id > 0 $SortByClient $SortByCategory $SortByStatus $SortByWord $SortByMarking $SortByGuardian GROUP BY marking_id ORDER BY marking_id DESC";
        // FETCHING DATA FROM DATABASE  ////////////////////////////////////////////////////////////////////////////////^^^^^^^^^^^^^^GROUP BY ordered_product.product_group
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {
            $list_of_variants[] = "";

            $product_group = $row['ordered_product_group'];    
            $order_number = $row['order_id'];
            
            $date_of_order = $row['date_added'];
       
            $due_date = $row['due_date']; 

            $variants_amount = 0;
     
                      
            // MAIN LIST OF ORDERS - ROWS IN MAIN TABLE

            echo "<tr class='row-table row-table-select-2'>";
            echo "<td valign='middle' align='center'>";
            
            echo "<td>";
                        
                // STATUS ICONS
                
                echo "<div id='statusicon" .$row['id']. "' onclick='changestatus(1, " .$row['id']. "), showwaiting(" .$row['id']. ")' style='cursor:pointer'>";

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

            echo "<td valign='middle' align='center'>";
                
                // THUMB ICON
                    
                    echo "<a href='../images/product-0001.jpg' target='_blank'><img src='../images/product-0002_thumb.jpg' class='thumb-icon'></a>";

                // END

            echo "</td>";
            
            echo "<td>".$row['order_number']."<div class='lowerfont' ondblclick='changestatus(2, " .$row['id']. " ), showwaiting(" .$row['id']. ")'>" . $date_of_order . "</div></td>";
            
            
            // MARKING INDOCATORS

            $ordered_product_group = $row['product_group'];
                
            $currentuser="";
            $ifmark=0;
            $markingCost = 0;
            $markingTotalCost = 0;

            echo "<td width='40' valign='center'>";

                echo "<div style='font-weight: 900'>".$row['marking_code']."</div>";

            echo "</td>";
            echo "<td width='100' >";
                echo "<div style='font-size:14px'>".$row['marking_location']."</div>";                  
            echo "</td>";
            echo "<td width='70' >";
           
            if ($row['fullcolor']==true){
                echo "<div class='numberofcolorsfullcolor'></div>";
            }
            else{
                for ($i = 1; $i <= $row['number_of_colors']; $i++) {
                    echo "<div class='numberofcolors'></div>";
                }
            }

            echo "</td>";
            

                        
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
            

            
            
            echo "<td align='center'>";

            echo $row['target_time'] . " min";

            $time = $row['target_time'];

            if ($row['status'] != 'gotowe'){
                $totalTime = $totalTime + $time;
            }

            

                

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

                // ORDER TIME MODE - SLOW, NORMAL, FAST

                

                // IF NORMAL

                if ($row['mode'] == 1){
           
                    if ($due_date == $Date){
                        echo "<img src='../images/red-line-vert.svg' width='6' height='40'></td>";
                    }
                } 

                //IF FAST
                
                if ($row['mode'] == 2){
                    

                    if ($due_date == $Date){
                        echo "<img src='../images/red-line-vert.svg' width='6' height='40'></td>";
    
                    }
                    else{
                        echo "<img src='../images/red-line-vert-fast.svg' width='6' height='40'></td>";
                    }
                } 




                // END

            
            echo "<td align='center'>";
            
                // ACTION ICONS
                
                
                //echo "<div class='action-icon'><a href='' target='_blank'><img src='../images/message-icon.svg' width='100%' height='100%'></a></div>";
                //echo "<div class='action-icon'><a href='' target='_blank'><img src='../images/close-icon.svg' width='100%' height='100%'></a></div>";
                echo "<div id='arrow-down-".$row['marking_id']. "' class='action-icon' onclick='expandrow(".$row['marking_id'].",".$row['order_number'].",".$row['id'].")'><img src='../images/arrow-down.svg' width='100%' height='100%'></div>";
                echo "<div id='arrow-up-".$row['marking_id']. "' style='display:none' class='action-icon' onclick='colapserow(".$row['marking_id'].")'><img src='../images/arrow-up.svg' width='100%' height='100%'></div>";
                

                // END

            echo "</td>";
            echo "</tr>";

                // SECOND ROW ----------------------------------------------------------------------------------

              
            echo "<tr width='100%' style='display:none' id='row-".$row['marking_id']."'>";
            echo "<td valign='top'></td>";    
             
            echo "<td valign='top'></td>";
            echo "<td valign='top' align='left'><div style='width=100%' class='lowerfontbold'>Opiekun</div>";

                // ORDER GUARDIAN - OWNER

                echo "<div><img src='../images/".$row['order_guardian'].".png' class='guardian-icon-inline' title='".$row['name']." " .$row['surname']."'></div>";

                echo "<div>".$row['name']."</div>";

            echo "</td>";
            echo "<td valign='top'></td>";
            
            echo "<td colspan='3' valign='top'>";
            echo "<div class='lowerfontbold'>Uwagi do produktu / znakowania</div>";
           
            echo $row['comment'];

            echo "</td>";
            
            echo "<td colspan='1' valign='top'>";

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
            

            echo "</td>";     
            
            echo "<td  colspan='4' valign='top'><div class='lowerfontbold'>Komenatarze</div>";
                
                // COMMENTS TO MARKING

                echo "<div id='commentslist-".$row['marking_id']."'>";

                echo "</div>";

                echo "<div class='comments-box'>";
                   
                    echo "<div class='inline-element'><img src='../images/".$username.".png' class='comments-avatar'></div>";         
                    echo "<input id='CommentText-".$row['marking_id']."' class='inline-element comments-text' type='text' placeholder='...' onkeypress='commentpress(".$row['order_number'].",".$row['id'].",".$row['marking_id'].")'></input>";
                    echo "<div class='inline-element comments-send-icon'><div class='action-icon' onclick='savecomment(1,".$row['order_number'].",".$row['id'].",".$row['marking_id'].")' ><img src='../images/arrow-right.svg' width='100%' height='100%'></div>";
                echo "</div>";

            echo "</td>";
           
            echo "<td valign='top'></td>";
            echo "<td align='center' valign='top'></td>";
           
    
            echo "</tr>";

            

            }
            
        } 

        echo $totalTime . " min";
        echo "<br>";
        echo round($totalTime/60) . " godz.";

        echo "<br>";
        echo round(($totalTime/480),1) . " dni";
        
       
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




