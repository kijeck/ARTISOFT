<?php
// Initialize the session
session_start();
include "../sql.php";
$Date=date("Y-m-d H:i:s");


if(isset($_GET['id'])) {
    $ordered_product_id=$_GET['id'];
}
else{
    $ordered_product_id=null;

}

if(isset($_GET['product_code'])) {
    $product_code=$_GET['product_code'];
}
else{
    $product_code=null;
}


 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Magazyn</title>

    <link href="../style.css" rel="stylesheet" type="text/css" /></head>

    <script type="text/javascript">
    
        function logout(){
            document.getElementById('logout').style.display = 'block';
        }

        function logoutoff(){
            document.getElementById('logout').style.display = 'none';
        }
        
    </script>

<script>

    function searchproduct() {

        item = document.getElementById("SearchField").value;
        
        ProductName = document.getElementById("ProductName").value;
        Category = document.getElementById("Category").value;
        ProductVariant = document.getElementById("ProductVariant").value;
        ordered_product_id = "<?php echo $ordered_product_id; ?>";

        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("SearchResult").innerHTML = this.responseText;
        }
        };
       
        xmlhttp.open("GET","result-main.php?item="+item+"&ProductName="+ProductName+"&ProductVariant="+ProductVariant+"&Category="+Category+"&ordered_product_id="+ordered_product_id,true);
        xmlhttp.send();
        RowPrev=0;
   
        
       

    }

    function searchproduct_load(){
        item = "<?php echo $product_code; ?>";
        document.getElementById("SearchField").value = item;
        searchproduct();
    }

    function HideSearch(){
        document.getElementById("SearchField").value = null;
        searchproduct();
    }

    </script>

</head>

<body onload="searchproduct_load()">

<?php 
$title = "Magazyn";
include "top.php";
include 'mag-menu.php';
?>


<div class="content">

    <div class="col-5">
        <div class="naglowek-3">Znajdź produkt</div>
        <div class="tresc"><input type="text" class="textfield" id="SearchField" placeholder="..." onChange="searchproduct()" onClick="searchproduct()"> 

        </div>
    </div>    

    <div class="col-2">
        <div class="naglowek-3">Nazwa produktu</div>
        <div class="tresc"><select class="textfield" id="ProductName" onChange="searchproduct()"> 
        <option value="">-</option>
            <?php
                
                $query = "SELECT DISTINCT product_name, amount FROM pz WHERE amount > 0 GROUP BY product_name;";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['product_name']."'>".$row['product_name']."</option>";

                    }
                } 
            ?>
           
        </select>
        </div>
    </div>

    <div class="col-5">
        <div class="naglowek-3">Kategoria</div>
        <div class="tresc"><select class="textfield" id="Category" onChange="searchproduct()"> 
            <option value="">-</option>

            <?php
                
                $query = "SELECT DISTINCT category, amount FROM pz WHERE amount > 0 GROUP BY category;";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['category']."'>".$row['category']."</option>";

                    }
                } 
            ?>
     
        </select>
        </div>
    </div>

    <div class="col-5">
        <div class="naglowek-3">Wariant</div>
        <div class="tresc"><select class="textfield" id="ProductVariant" onChange="searchproduct()"> 
            <option value="">-</option>

            <?php
                
                $query = "SELECT DISTINCT product_variant, amount FROM pz WHERE amount > 0 GROUP BY product_variant;";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['product_variant']."'>".$row['product_variant']."</option>";

                    }
                } 
                
            ?>
     
        </select>
        </div>
    </div>

</div>

<div id="SearchResult" class="productlist"></div>

<div style="height:80px;"></div>
<div class="footer">
<div class="content">
                
    </div>
</div>

</body>
</html>


