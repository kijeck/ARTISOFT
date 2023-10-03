<?php


$SearchWord = "";
$SearchWord=$_GET['item'];
$ProductName = $_GET['ProductName'];
$ProductVariant = $_GET['ProductVariant'];
$Category=$_GET['Category'];

if(isset($_GET['ordered_product_id'])) {
    $ordered_product_id = $_GET['ordered_product_id'];
}


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

    $SortByWord = "AND (product_code LIKE '%$SearchWord%' OR product_name LIKE '%$SearchWord%' OR invoice_number LIKE '%$SearchWord%' OR amount LIKE '$SearchWord' OR product_variant LIKE '%$SearchWord%' OR supplier_name LIKE '%$SearchWord%' OR category LIKE '%$SearchWord%')";
}

if ($SearchWord != "" || $ProductName != "" || $Category != "" || $ProductVariant != ""){
        echo "<table width='100%' cellpadding=7 cellspacing=0 border=0>";
        echo "<tr class='header-table'>";
        echo "<td width='30'></td>";
        echo "<td width='60'>ID PZ</td>";
        echo "<td width='110'>Dostawca</td>";
        echo "<td width='110'>FV</td>";
        echo "<td>Produkt</td>";
        echo "<td width='90' align='center'>Kod produktu</td>";
        echo "<td width='30'></td>";
        echo "<td width='100'>Wariant</td>";
        echo "<td width='70' align='right'>Stan</td>";
        echo "<td width='40' align='center'>Jm</td>";
        echo "<td width='100' align='right'>Cena / szt.</td>";
        echo "<td width='100' align='center'>Akcje</td>";
        echo "<td width='30'></td>";
        echo "</tr>";


        $query = "SELECT * FROM pz WHERE amount > 0 $SortByProduct $SortByCategory $SortByVariant $SortByWord ORDER BY product_variant;";
        // FETCHING DATA FROM DATABASE
        $result = mysqli_query($link, $query);


        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {

                echo "<tr class='row-table row-table-select'>";
                echo "<td></td>";
                echo "<td><span class='lowerfont' title='" .$row['date']. "'>PZ ".$row['id']."</span></td>";
                echo "<td>" .$row['supplier_name']. "</td>";
                echo "<td><span class='lowerfont'>" .$row['invoice_number']. "</span></td>";
                echo "<td>" .$row['product_name']. "</td>";
                echo "<td align='center'>" .$row['product_code']. "</td>";

                
                echo "<td><div class='color-icon' style='background-color:".ColorIcon($row['product_variant'])."'></div></td>";
                echo "<td><div>" .$row['product_variant']. "</div></td>";
                echo "<td align='right'>" .$row['amount']. "</td>";
                echo "<td align='center'>szt.</td>";
                echo "<td align='right'>" .$row['unit_netto']. " zł</td>";
                echo "<td align='center'>
                
                <div class='action-icon'><a target='_blank' href='___.php?zmienna=10753'><img src='../images/wz-edit.svg' width='100%' height='100%' border='0'></a></div>
                <div class='action-icon'><a target='_blank' href='wz-add.php?pz=".$row['id']."&ordered_product_id=".$ordered_product_id."'><img src='../images/wz-icon-new.svg' width='100%' height='100%' border='0'></a></div>

                </td>";
                echo "<td></td>";
                echo "</tr>";

                $CountTotal = $CountTotal + $row['amount'];

                
            }
        } 

        echo "<tr class='sum-table'>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td align='center'></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td align='right'><span style='font-weight:600'>" .$CountTotal. "</span></td>";
                echo "<td align='center'><span style='font-weight:600'>szt.</span></td>";
                echo "<td align='right'></td>";
                echo "<td align='center'></td>";
                echo "<td></td>";
                echo "</tr>";

        echo "</table>";
}
?>

<br>

