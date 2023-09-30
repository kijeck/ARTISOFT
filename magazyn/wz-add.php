<?php
// Initialize the session
session_start();
include "../sql.php";
include "../colors.php";

$pz=$_GET['pz'];
$order_id=$_GET['order_id'];

 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

                
$query = "SELECT * FROM pz WHERE id=$pz;";
// FETCHING DATA FROM DATABASE
$result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        // OUTPUT DATA OF EACH ROW
        while($row = mysqli_fetch_assoc($result)) {
        $product_name = $row['product_name'];
        $product_variant = $row['product_variant'];
        $product_code = $row['product_code'];
        $amount = $row['amount'];
        $unit_netto = $row['unit_netto'];

        }
    } 

?>
 
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>WZ: <?php echo $product_name . " " . $product_variant?></title>

    <link href="../style.css" rel="stylesheet" type="text/css" /></head>

    <script type="text/javascript">

        function logout(){
            document.getElementById('logout').style.display = 'block';
        }

        function logoutoff(){
            document.getElementById('logout').style.display = 'none';
        }

        
        
        function save(){

            Amount = document.getElementById('Amount').value;
            AmountExtra = document.getElementById('AmountExtra').value;
            Comments = document.getElementById('Comments').value;
            //OrderNumber = document.getElementById('OrderNumber').value;
            OrderNumber="666";
            pz = <?php echo $pz; ?>;

            window.location.href = "../save.php?pz="+pz+"&Amount="+Amount+"&AmountExtra="+AmountExtra+"&OrderNumber="+OrderNumber+"&Comments="+Comments+"&Action=WZ-SAVE";
        
        }


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

    function CountState(){

        var stan = "<?php echo $amount; ?>";
        var ilosc = document.getElementById('Amount').value;
        var dodatkowe = document.getElementById('AmountExtra').value;


        document.getElementById('StateAfter').innerHTML = stan - ilosc - dodatkowe;
        stan = document.getElementById('StateAfter');

            if (document.getElementById('Amount').value < 0)
            {
            document.getElementById('Amount').value = "0";
            document.getElementById('StateAfter').innerHTML = stan;
            
            }
            
            if (document.getElementById('Amount').value > stan)
            {
            document.getElementById('Amount').value = stan;
            document.getElementById('StateAfter').innerHTML = stan;
            }
            
            if (ilosc > <?php echo $amount; ?>)
            {
            document.getElementById('StateAfter').innerHTML = "0";
            document.getElementById('Amount').value = "<?php echo $amount; ?>";
            }

	    // pole dodatkowe sztuki - warunki
	
            if (document.getElementById('AmountExtra').value < 0)
            {
            document.getElementById('StateAfter').innerHTML = "0";
            document.getElementById('AmountExtra').value = "0";
            
            }	
            
            if (document.getElementById('AmountExtra').value >= ("<?php echo $amount; ?>"-document.getElementById('Amount').value))
            {
            document.getElementById('AmountExtra').value = "<?php echo $amount; ?>" - document.getElementById('Amount').value;
            }
	
}

    function SetFun(){
        document.getElementById('StateAfter').innerHTML = "<?php echo $amount; ?>";
        document.getElementById('Amount').value = 0;
        document.getElementById('AmountExtra').value = 0;
   
    }

    function confirm(){

let ConditionSum = 0;

if (document.getElementById('Amount').value == ""){
    document.getElementById('Amount').style.background = "rgba(255, 60, 60, 0.3)";
    ConditionSum++;
}
else{
    document.getElementById('Amount').style.background = null;
    
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

    </script>
</head>

<body onload="SetFun()">

<?php 
$title = "Wydanie towaru";
include "top.php";
include 'mag-menu.php';

?>

<div class="content">

    <div class="naglowek-2">Do zamówienia</div>

        <div class="col-1">
            <div class="tresc"><input class="textfield"  id="SearchField" placeholder="..." onChange="searchproduct()" onClick="searchproduct()"></div>
        </div>
        <br>
        <div id="SearchResult" class="col-1 productlist">
       
        </div>


        <br><br>
        <div>
        <div class='color-icon-large' style='background-color:<?php echo ColorIcon($product_variant); ?>'></div>
        <div class="naglowek-2" style="display:inline-block"><?php echo $product_name . " " . $product_variant?></div>
        </div>

    
        
    <div class="naglowek-3">Stan po wydaniu:<span class="naglowek-3" id="StateAfter"></span></div>

    <div class="col-5">
       <div class="naglowek-3">Ilość</div>
       <div class="tresc"><input type="number" min="0" class="textfield" id="Amount"  onclick="CountState()" onchange="CountState()" onkeyup="CountState()" onkeydown="CountState()" onblur="CountState()"> 
        
       </select>
       </div>
    </div>

   <div class="col-5">
       <div class="naglowek-3">Dodatkowe / uszkodzone sztuki</div>
       <div class="tresc"><input type="number" min="0" class="textfield" id="AmountExtra" onclick="CountState()" onchange="CountState()" onkeyup="CountState()" onkeydown="CountState()" onblur="CountState()"> 
        
       </div>
   </div>

    
   <div class="col-5">
       <div class="naglowek-3">Uwagi</div>
       <div class="tresc"><input class="textfield" id="Comments"> 
        
       </div>
   </div>

<hr>

    <div class="btn-primary" onclick="confirm()">Wydaj towar</div>
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
<?php //mysql_close($link); ?>