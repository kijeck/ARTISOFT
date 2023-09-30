<?php


$SearchWord = "";
$SearchWord=$_GET['item'];
$Supplier=$_GET['supplier'];
$SortBySupplier="";
$SortBySupplier2="";
include "../sql.php";

if ($Supplier != ""){
    $SortBySupplier = "dostawca='$Supplier' AND ";
    $SortBySupplier2 = "supplier_name='$Supplier' AND ";
}

echo "<table  width='100%' cellpadding=5 cellspacing=0 border=0>";
echo "<tr class='header-table'>";
echo "<td>Dostawca</td>";
echo "<td>Kod</td>";
echo "<td>Nazwa</td>";
echo "<td>Wariant</td>";
echo "</tr>";

$query = "SELECT DISTINCT id, dostawca, symbol, nazwa, kategoria, wariant FROM produkty WHERE $SortBySupplier (symbol LIKE '%$SearchWord%' OR nazwa LIKE '%$SearchWord%' OR wariant LIKE '%$SearchWord%' OR dostawca LIKE '%$SearchWord%' OR kategoria LIKE '%$SearchWord%' OR opis LIKE '%$SearchWord%');";
// FETCHING DATA FROM DATABASE
$result = mysqli_query($link, $query);



if (mysqli_num_rows($result) > 0) {
    // OUTPUT DATA OF EACH ROW
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr id='TableRow-".$row['id']."1'class='row-table row-table-select cursor-pointer' onClick='SelectProduct(".$row['id']."1)'>";
        echo "<td><input style='display:none' type='text' id='SupplierName".$row['id']."1' value='".$row['dostawca']."'>".$row["dostawca"]."</input></td>";
        echo "<td><input style='display:none' type='text' id='ProductCode".$row['id']."1' value='".$row['symbol']."'>".$row["symbol"]."</input></td>";
        echo "<td><input style='display:none' type='text' id='ProductName".$row['id']."1' value='".$row['nazwa']."'>".$row["nazwa"]."</input>
        <input style='display:none' type='text' id='Category".$row['id']."1' value='".$row['kategoria']."'></input>
        </td>";
        echo "<td><input style='display:none' type='text' id='ProductVariant".$row['id']."1' value='".$row['wariant']."'>".$row["wariant"]."</input></td>";
         echo "</tr>";
         
    }
} 

echo "<tr class='productlistheader' height='20'>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "<tr class='productlistheader' style='padding-top:30px'>";
echo "<td>Historia PZ</td>";
echo "<td>Kod</td>";
echo "<td>Nazwa</td>";
echo "<td>Wariant</td>";
echo "</tr>";


$query = "SELECT DISTINCT * FROM pz WHERE $SortBySupplier2 (product_code LIKE '%$SearchWord%' OR product_name LIKE '%$SearchWord%' OR product_variant LIKE '%$SearchWord%' OR supplier_name LIKE '%$SearchWord%' OR category LIKE '%$SearchWord%') GROUP BY product_variant;";
// FETCHING DATA FROM DATABASE
$result = mysqli_query($link, $query);


if (mysqli_num_rows($result) > 0) {
    // OUTPUT DATA OF EACH ROW
    while($row = mysqli_fetch_assoc($result)) {

        echo "<tr id='TableRow-".$row['id']."2'class='row-table row-table-select cursor-pointer' onClick='SelectProduct(".$row['id']."2)'>";
        echo "<td><input style='display:none' type='text' id='SupplierName".$row['id']."2' value='".$row['supplier_name']."'>".$row["supplier_name"]."</input></td>";
        echo "<td><input style='display:none' type='text' id='ProductCode".$row['id']."2' value='".$row['product_code']."'>".$row["product_code"]."</input></td>";
        echo "<td><input style='display:none' type='text' id='ProductName".$row['id']."2' value='".$row['product_name']."'>".$row["product_name"]."</input>
        <input style='display:none' type='text' id='Category".$row['id']."2' value='".$row['category']."'></input>
        </td>";
        echo "<td><input style='display:none' type='text' id='ProductVariant".$row['id']."2' value='".$row['product_variant']."'>".$row["product_variant"]."</input></td>";
        echo "</tr>";
         
    }
} 

echo "</table>";

?>
<br>
<div onclick="HideSearch()">Zwiń</div>
<br>

