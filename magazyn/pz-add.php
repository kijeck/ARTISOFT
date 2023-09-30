<?php
// Initialize the session
session_start();
include "../sql.php";

 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


                
    $query = "SELECT invoice_number FROM pz;";
                // FETCHING DATA FROM DATABASE
        $result = mysqli_query($link, $query);
                
        if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {
                $LastInvoiceNumber = $row['invoice_number'];
                }
            } 


?>
 
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>PZ Przyjęcie towaru</title>

    <link href="../style.css" rel="stylesheet" type="text/css" /></head>

    <script type="text/javascript">

        function logout(){
            document.getElementById('logout').style.display = 'block';
        }

        function logoutoff(){
            document.getElementById('logout').style.display = 'none';
        }

        let RowPrev=0;
        let RowIndex=0;
        function SelectProduct(RowNumber){
                document.getElementById('SupplierName').value = document.getElementById('SupplierName'+RowNumber).value;
                document.getElementById('ProductCode').value = document.getElementById('ProductCode'+RowNumber).value;
                document.getElementById('ProductName').value = document.getElementById('ProductName'+RowNumber).value;
                document.getElementById('ProductVariant').value = document.getElementById('ProductVariant'+RowNumber).value;
                document.getElementById('Category').value = document.getElementById('Category'+RowNumber).value;
                
                document.getElementById('TableRow-'+RowNumber).style.background = '#D4E703'; 
                window.scroll(0,10000000);
                if (RowPrev != 0){
                    document.getElementById('TableRow-'+RowPrev).style.background = null;
                }
            
                RowPrev=RowIndex + RowNumber;   
                
                
        }
        

        function confirm(){

            let ConditionSum = 0;

            if (document.getElementById('SupplierName').value == ""){
                document.getElementById('SupplierName').style.background = "rgba(255, 60, 60, 0.3)";
                ConditionSum++;
            }
            else{
                document.getElementById('SupplierName').style.background = null;
                
            }
            
            if (document.getElementById('InvoiceNumber').value == ""){
                document.getElementById('InvoiceNumber').style.background = "rgba(255, 60, 60, 0.3)";
                ConditionSum++;
            }
            else{
                document.getElementById('InvoiceNumber').style.background = null;
                
            }

            if (document.getElementById('ProductCode').value == ""){
                document.getElementById('ProductCode').style.background = "rgba(255, 60, 60, 0.3)";
                ConditionSum++;
            }
            else{
                document.getElementById('ProductCode').style.background = null;
                
            }

            if (document.getElementById('ProductVariant').value == ""){
                document.getElementById('ProductVariant').style.background = "rgba(255, 60, 60, 0.3)";
                ConditionSum++;
            }
            else{
                document.getElementById('ProductVariant').style.background = null;
                
            }

            if (document.getElementById('Amount').value == ""){
                document.getElementById('Amount').style.background = "rgba(255, 60, 60, 0.3)";
                ConditionSum++;
            }
            else{
                document.getElementById('Amount').style.background = null;
               
            }

            if (document.getElementById('TotalPriceNet').value == ""){
                document.getElementById('TotalPriceNet').style.background = "rgba(255, 60, 60, 0.3)";
                ConditionSum++;
            }
            else{
                document.getElementById('TotalPriceNet').style.background = null;
                
            }
            
            if (ConditionSum == 0){
                document.getElementById('confirmbox').style.display = 'block';
            }
            else{
                document.getElementById('infobox').style.display = 'block';
            }

                
                    
        window.scroll(0,10000000);
        }	

        function cancel(){
        document.getElementById('confirmbox').style.display = 'none';
        document.getElementById('infobox').style.display = 'none';
        }
        
        function save(){

            SupplierName = document.getElementById('SupplierName').value;
            InvoiceNumber = document.getElementById('InvoiceNumber').value;
            ProductName = document.getElementById('ProductName').value;
            ProductCode = document.getElementById('ProductCode').value;
            ProductVariant = document.getElementById('ProductVariant').value;
            Amount = document.getElementById('Amount').value;
            TotalPriceNet = document.getElementById('TotalPriceNet').value;
            Tax = document.getElementById('Tax').value;
            Category = document.getElementById('Category').value;
            Comments = document.getElementById('Comments').value;

            window.location.href = "../save.php?SupplierName="+SupplierName+"&InvoiceNumber="+InvoiceNumber+"&ProductName="+ProductName+"&ProductCode="+ProductCode+"&ProductVariant="+ProductVariant+"&Amount="+Amount+"&TotalPriceNet="+TotalPriceNet+"&Tax="+Tax+"&Comments="+"&Category="+Category+"&Comments="+Comments+"&Action=PZ-SAVE";
        
        }

        function FillInvoiceNumber(){

            document.getElementById('InvoiceNumber').value = "<?php echo $LastInvoiceNumber; ?>"; 
        }

    </script>

    <script>
    function searchproduct() {
        item = document.getElementById("SearchField").value;
        SupplierName = document.getElementById("SupplierName").value;
    if (item == "") {
        document.getElementById("SearchResult").innerHTML = "";
        return;
    } else {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("SearchResult").innerHTML = this.responseText;
        }
        };
       
        xmlhttp.open("GET","result.php?item="+item+"&supplier="+SupplierName,true);
        xmlhttp.send();
        RowPrev=0;
        
       
    }
    }

    function HideSearch(){
        document.getElementById("SearchField").value = null;
        searchproduct();
    }

    </script>
</head>

<body>

<?php 
$title = "Przyjęcie towaru";
include "top.php";
include 'mag-menu.php';

?>

<div class="content">

    <div class="naglowek-2">Znajdź produkt</div>

        <div class="col-1">
            <div class="tresc"><input class="textfield"  id="SearchField" placeholder="..." onChange="searchproduct()" onClick="searchproduct()"></div>
        </div>
        <br>
        <div id="SearchResult" class="col-1 productlist">
       
        </div>


    <div class="naglowek-2">Dostawca</div>

        <div class="col-5">
        <div class="naglowek-3">Nazwa</div>
        <div class="tresc"><input type="text" list="Suppliers" class="textfield" id="SupplierName"> 
        <datalist id="Suppliers">
            <?php
                
                $query = "SELECT DISTINCT supplier_name FROM pz;";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['supplier_name']."'></option>";

                    }
                } 
            ?>
        </datalist>    
        </select>
        </div>
        </div>

        <div class="col-5">
            <div class="naglowek-3">Numer faktury</div>
            <div class="tresc"><input class="textfield" id="InvoiceNumber" placeholder="Kontynuuj fakturę - kliknij dwa razy" ondblclick="FillInvoiceNumber()"> 
                
            </div>
        </div>
    
        <br><br>
    <div class="naglowek-2">Towar</div>
        
        <div class="col-5">
        <div class="naglowek-3">Nazwa</div>
        <div class="tresc"><input type='text' class="textfield" id="ProductName"> 
            
        </div>
    </div>

    <div class="col-5">
        <div class="naglowek-3">Kod produktu</div>
        <div class="tresc"><input type='text' class="textfield" id="ProductCode"> 
            
        </div>
    </div>

   <div class="col-5">
       <div class="naglowek-3">Wariant</div>
       <div class="tresc"><input type='text' class="textfield" id="ProductVariant"> 

       </div>
   </div>

   <div class="col-5">
       <div class="naglowek-3">Kategoria</div>
       <div class="tresc"><input type='text' list="category" class="textfield" id="Category"> 
            <datalist id="category">
            <?php
                
                $query = "SELECT DISTINCT category FROM pz;";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['category']."'></option>";

                    }
                } 
            ?>
            </datalist>
       </div>
   </div>

   <br><br>

   <div class="col-5">
       <div class="naglowek-3">Ilość</div>
       <div class="tresc"><input type="number" min="0" class="textfield" id="Amount"> 
       </div>
   </div>

   <div class="col-5">
       <div class="naglowek-3">Cena zakupu netto</div>
       <div class="tresc"><input type="number" min="0" class="textfield" id="TotalPriceNet"> 
        
       </div>
   </div>

    

   <div class="col-5">
       <div class="naglowek-3">Stawka VAT [%]</div>
       <div class="tresc"><select class="textfield" type="number" min="0" id="Tax"> 
        
       <?php
            
            $query = "SELECT DISTINCT name, percent FROM tax;";
            // FETCHING DATA FROM DATABASE
            $result = mysqli_query($link, $query);
            
            if (mysqli_num_rows($result) > 0) {
                // OUTPUT DATA OF EACH ROW
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='".$row['percent']."'>".$row['name']."</option>";
                }
            } 
        ?>     

       </select>
       </div>
   </div>
    
   <div class="col-5">
       <div class="naglowek-3">Uwagi</div>
       <div class="tresc"><input class="textfield" id="Comments"> 
        
       </div>
   </div>

<hr>

    <div class="btn-primary" onclick="confirm()">Przyjmij towar</div>
    <a href="index.php"><div class="btn-secondary">Anuluj</div></a>

</div>
<div style="height:80px;"></div>
<div class="footer" id="footer">

<?php include "confirmbox.php"; ?>
<?php include "infobox.php"; ?>

    <div class="content">
         
    </div>
</div>

</body>
</html>
<?php mysql_close($link); ?>