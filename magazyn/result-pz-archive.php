<?php


$SearchWord = "";
$SearchWord=$_GET['item'];
$ProductName = $_GET['ProductName'];
$ProductVariant = $_GET['ProductVariant'];
$Category=$_GET['Category'];
$SortByProduct="";
$SortByWord="";
$SortByCategory="";
$SortByVariant="";
$CountTotal = 0;
include "../sql.php";

include "../colors.php";

if ($ProductName != ""){
    $SortByProduct = "AND product_name='$ProductName'";
}

if ($Category != ""){
    $SortByCategory = "AND category='$Category'";
}

if ($ProductVariant != ""){
    $SortByVariant = "AND product_variant='$ProductVariant'";
}

if ($SearchWord != ""){

    $SortByWord = "AND (id LIKE '%$SearchWord%' OR product_code LIKE '%$SearchWord%' OR product_name LIKE '%$SearchWord%' OR invoice_number LIKE '%$SearchWord%' OR amount LIKE '$SearchWord' OR product_variant LIKE '%$SearchWord%' OR supplier_name LIKE '%$SearchWord%' OR category LIKE '%$SearchWord%')";
}

if ($SearchWord != "" || $ProductName != "" || $Category != "" || $ProductVariant != ""){
        echo "<table width='100%' cellpadding=5 cellspacing=0 border=0>";
        echo "<tr class='header-table'>";
        echo "<td width='30'></td>";
        echo "<td width='150'>ID PZ</td>";
        echo "<td width='110'>Dostawca</td>";
        echo "<td width='110'>FV</td>";
        echo "<td>Produkt</td>";
        echo "<td width='90' align='center'>Kod produktu</td>";
        echo "<td width='30'></td>";
        echo "<td width='100'>Wariant</td>";
        echo "<td width='120' align='right'>Stan</td>";
        echo "<td width='40' align='center'>Jm</td>";
        echo "<td width='100' align='right'>Cena zakupu</td>";
        echo "<td width='30'></td>";
        echo "</tr>";


        $query = "SELECT * FROM pz WHERE id > 0 $SortByProduct $SortByCategory $SortByVariant $SortByWord ORDER BY id DESC;";
        // FETCHING DATA FROM DATABASE
        $result = mysqli_query($link, $query);


        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {

                echo "<tr class='row-table row-table-select-2'>";
                echo "<td align='center'>";
                    if ($row['amount'] != 0){
                       echo "<img class='left-icon' src='../images/archive-icon.svg'>";     
                    }
                echo "</td>";
                echo "<td>PZ ".$row['id'] . "<div class='lowerfont'>".$row['date']."</div></td>";
                echo "<td>" .$row['supplier_name']. "</td>";
                echo "<td><span class='lowerfont'>" .$row['invoice_number']. "</span></td>";
                echo "<td>" .$row['product_name']; 
                echo "</td>";
                echo "<td align='center'>" .$row['product_code']. "</td>"; 
                echo "<td><div class='color-icon' style='background-color:".ColorIcon($row['product_variant'])."'></div></td>";
                echo "<td><div>" .$row['product_variant']. "</div></td>";
                echo "<td align='right'><strong>" .$row['amount']. " / " . $row['amount_come'] . "</strong></td>";
                echo "<td align='center'>szt.</td>";
                echo "<td align='right'><strong>" .$row['total_netto']. " zł</strong></td>";
                echo "<td></td>";
                echo "</tr>";


                $id_pz = $row['id'];

                    $query2 = "SELECT *, wz.id AS wzid, wz.amount AS wzamount, wz.unit_netto AS wzunitnetto FROM wz LEFT JOIN ordered_product ON wz.order_id = ordered_product.id LEFT JOIN orders ON ordered_product.order_number = orders.number WHERE pz_id=$id_pz;";
                    // FETCHING DATA FROM DATABASE
                    $result2 = mysqli_query($link, $query2);
                    
                    if (mysqli_num_rows($result2) > 0) {
                        // OUTPUT DATA OF EACH ROW
                        while($row2 = mysqli_fetch_assoc($result2)) {
                        
                            echo "<tr class='row-table-2 row-table-select-2'>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td><div style='width: 15px; display: inline-block;'><img src='../images/vertical-line.svg'></div><div style='width: 100px; display: inline-block;'><strong>WZ</strong> ". $row2['wzid'] ."</div><div style='width: 100px; display: inline-block;'><strong>ZK</strong> <a href='#'>". $row2['order_number'] ."/". $row2['id'] ."</a></div><div style='display: inline-block;'>". $row2['company_name'] ." - ". $row2['description'] ."</div></td>";
                echo "<td align='center'></td>"; 
                echo "<td></td>";
                echo "<td><div></div></td>";
                echo "<td align='right'>" .$row2['wzamount']. "</td>";
                echo "<td align='center'>szt.</td>";
                echo "<td align='right'>" .$row2['wzunitnetto'] * $row2['wzamount'] . " zł</td>";
                echo "<td></td>";
                echo "</tr>";
                    }
                    } 

                $CountTotal = $CountTotal + $row['amount'];

                
            }
        } 

 
}
?>

<br>

