<?php
// Initialize the session
session_start();
include "../sql.php";
$Date=date("Y-m-d H:i:s");
 
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
    <title>Zamówienia</title>

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

let prevvariantnum = 0;

function expandrow(rownum, order_number, ordered_product_id){
    document.getElementById('row-'+rownum).style.display = 'table-row';
    document.getElementById('arrow-up-'+rownum).style.display = 'inline-block';
    document.getElementById('arrow-down-'+rownum).style.display = 'none';
    commentslist(order_number, ordered_product_id);
}

function colapserow(rownum){
    document.getElementById('row-'+rownum).style.display = 'none';
    document.getElementById('arrow-up-'+rownum).style.display = 'none';
    document.getElementById('arrow-down-'+rownum).style.display = 'inline-block';
}

function variantshow(variantnum){
    document.getElementById('variant-'+variantnum).style.display = 'block';
    if (prevvariantnum == 0){
        prevvariantnum = variantnum;
    }
    else{
        document.getElementById('variant-'+prevvariantnum).style.display = 'none';
        prevvariantnum = variantnum;
    }
    
}

    function varianthide(variantnum){
        document.getElementById('variant-'+variantnum).style.display = 'none';
        document.getElementById('variant-'+prevvariantnum).style.display = 'none';
        prevvariantnum = 0;
    }

    function deletecomment(comments_id, order_number, ordered_product_id){
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("commentslist-"+order_number+ordered_product_id).innerHTML = this.responseText;
            }
            };
            xmlhttp.open("GET","comments-list.php?order_number="+order_number+"&ordered_product_id="+ordered_product_id+"&comments_id="+comments_id,true);
            xmlhttp.send();
            
    }

    function commentpress(order_number, ordered_product_id){
        if (event.key === "Enter") {
            savecomment(1, order_number, ordered_product_id);
        }

    }

    function changestatus(type, ordered_product_id){  
        
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                
                document.getElementById("statusiconclick"+ordered_product_id).innerHTML = this.responseText;    
                document.getElementById("statuswaiting"+ordered_product_id).style.display = 'none'; 
                  

            }

            };
            
            xmlhttp.open("GET","status-icon.php?type="+type+"&ordered_product_id="+ordered_product_id,true);
            xmlhttp.send();
            
    }

    function showwaiting(ordered_product_id){
            document.getElementById("statusicon"+ordered_product_id).style.display = 'none';
            //document.getElementById("statusicon2"+ordered_product_id).style.display = 'none';
            document.getElementById("statuswaiting"+ordered_product_id).style.display = 'block';
            
    }

    function showwaiting2(ordered_product_id){
            //document.getElementById("statusicon"+ordered_product_id).style.display = 'none';
            document.getElementById("statusicon2"+ordered_product_id).style.display = 'none';
            document.getElementById("statuswaiting"+ordered_product_id).style.display = 'block';
            
    }

    function commentslist(order_number, ordered_product_id){
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("commentslist-"+order_number+ordered_product_id).innerHTML = this.responseText;
        }
        };
        xmlhttp.open("GET","comments-list.php?order_number="+order_number+"&ordered_product_id="+ordered_product_id,true);
        xmlhttp.send();
    }

    function savecomment(type, order_number, ordered_product_id){
        tresc = document.getElementById("CommentText-"+order_number+ordered_product_id).value;
        if (type==1){
            
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("commentslist-"+order_number+ordered_product_id).innerHTML = this.responseText;
            }
            };
            xmlhttp.open("GET","comments-list.php?order_number="+order_number+"&ordered_product_id="+ordered_product_id+"&type="+type+"&commenttext="+tresc,true);
            xmlhttp.send();
            document.getElementById("CommentText-"+order_number+ordered_product_id).value = "";
        }
    }


    function searchproduct() {
        item = document.getElementById("SearchField").value;
        ClientName = document.getElementById("ClientName").value;
        Category = document.getElementById("Category").value;
        Status = document.getElementById("Status").value;
        Payment = document.getElementById("Payment").value;
        Guardian = document.getElementById("Guardian").value;
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("SearchResult").innerHTML = this.responseText;
        }
        };
       
        xmlhttp.open("GET","result-orders.php?item="+item+"&ClientName="+ClientName+"&Status="+Status+"&Payment="+Payment+"&Guardian="+Guardian+"&Category="+Category,true);
        xmlhttp.send();
        RowPrev=0;
    }

    function HideSearch(){
        document.getElementById("SearchField").value = null;
        searchproduct();
    }

    </script>

</head>

<body onload="searchproduct()">

<?php 
$title = "Zamówienia";
include "top.php";
include 'orders-menu.php';

?>


<div class="content">

    <div class="col-5">
        <div class="naglowek-3">Znajdź </div>
        <div class="tresc"><input type="text" class="textfield" id="SearchField" placeholder="numer / nazwa / nazwisko / mail" onChange="searchproduct()" onClick="searchproduct()"> 

        </div>
    </div>    

    <div class="col-5">
        <div class="naglowek-3">Klient</div>
        <div class="tresc"><select class="textfield" id="ClientName" onChange="searchproduct()"> 
        <option value="">-</option>
            <?php
                
                $query = "SELECT DISTINCT * FROM orders WHERE main_status='przyjęte' OR main_status='wizualizacja' OR main_status='akceptacja' OR main_status='gotowe' GROUP BY company_name;";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['company_name']."'>".$row['company_name']."</option>";

                    }
                } 
            ?>
           
        </select>
        </div>
    </div>
          
    <div class="col-5">
        <div class="naglowek-3">Produkt</div>
        <div class="tresc"><select class="textfield" id="Category" onChange="searchproduct()"> 
            <option value="">-</option>

            <?php
                
                $query = "SELECT DISTINCT product_name FROM ordered_product WHERE status='przyjęte' OR status='wizualizacja' OR status='akceptacja' OR status='gotowe' GROUP BY product_name;";
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
        <div class="naglowek-3">Płatność</div>
        <div class="tresc"><select class="textfield" id="Payment" onChange="searchproduct()"> 
            <option value="">-</option>

            <?php
                
                $query = "SELECT DISTINCT * FROM orders";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['payment']."'>".$row['payment']."</option>";

                    }
                }
                
            ?>
     
        </select>
        </div>
    </div>

    <div class="col-5">
        <div class="naglowek-3">Status</div>
        <div class="tresc"><select class="textfield" id="Status" onChange="searchproduct()"> 
            <option value="">-</option>

            <?php
                
                $query = "SELECT DISTINCT id, status FROM ordered_product  GROUP BY status;";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['status']."'>".$row['status']."</option>";
                        

                    }
                  
                } 
            ?>
     
        </select>
        </div>
    </div>

    

    <div class="col-5">
        <div class="naglowek-3">Opiekun</div>
        <div class="tresc"><select class="textfield" id="Guardian" onChange="searchproduct()"> 
            <option value="">-</option>

            <?php
                
                $query = "SELECT DISTINCT name, username, surname, order_guardian FROM orders LEFT JOIN users ON users.username = orders.order_guardian GROUP BY order_guardian;";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['order_guardian']."'>".$row['name']." ".$row['surname']."</option>";
                        
                    }
                  
                } 
            ?>
     
        </select>
        </div>
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


