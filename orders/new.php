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
    <title>Nowe zamówienie</title>

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

        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("SearchResult").innerHTML = this.responseText;
        }
        };
       
        xmlhttp.open("GET","result-main.php?item="+item+"&ProductName="+ProductName+"&ProductVariant="+ProductVariant+"&Category="+Category,true);
        xmlhttp.send();
        RowPrev=0;
   
        
       

    }

    function HideSearch(){
        document.getElementById("SearchField").value = null;
        searchproduct();
    }

    </script>

</head>

<body>

<?php 
$title = "Nowe zamówienie";
include "top.php";
include 'orders-menu.php';
?>


<div class="content">


        <div class="col-1">
            <div class="tresc"><input class="textfield"  id="SearchOrder" placeholder="Znajdź podobne" onChange="searchorder()" onClick="searchorder()"></div>
            <div id="SearchOrderResult" class="col-1 productlist">
       
       </div>
        </div>
        <br>
   

        <div class="col-5">
        <div class="naglowek-2">Dane do faktury</div>
            <div class="col-5">
            <div class="naglowek-3">NIP</div>
            <div class="tresc"><input type="number" class="textfield" id=""> 
            </select>
            </div>
            </div>

            <div class="col-5">
                <div class="naglowek-3">Nazwa</div>
                <div class="tresc"><input class="textfield" id="" placeholder=""> 
                </div>
            </div>

            <div class="col-5">
                <div class="naglowek-3">Adres</div>
                <div class="tresc"><input class="textfield" id="" placeholder=""> 
                </div>
            </div>

            <div class="col-5">
                <div class="naglowek-3">Kod pocztowy</div>
                <div class="tresc"><input class="textfield" id="" placeholder=""> 
                </div>
            </div>

            <div class="col-5">
                <div class="naglowek-3">Miejscowość</div>
                <div class="tresc"><input class="textfield" id="" placeholder=""> 
                </div>
            </div>
        </div>    

        <div class="col-5">
            <div class="naglowek-2">Dane klienta</div>

            <div class="col-5">
            <div class="naglowek-3">Imię i nazwisko</div>
            <div class="tresc"><input type="text" class="textfield" id=""> 
            </select>
            </div>
            </div>

            <div class="col-5">
                <div class="naglowek-3">E-mail</div>
                <div class="tresc"><input class="textfield" type="email" id="" placeholder=""> 
                </div>
            </div>

            <div class="col-5">
                <div class="naglowek-3">Telefon</div>
                <div class="tresc"><input typ="number" class="textfield" id="" placeholder=""> 
                </div>
            </div>
        </div>
        

        <div class="col-5">
            <div class="naglowek-2">Dane zamówienia</div>

            <div class="col-5">
            <div class="naglowek-3">Status</div>
            <div class="tresc"><select class="textfield" type="text" id="">
                <option value="Przyjęte">Przyjęte</option>
                <option value="Wizualizacja">Wizualizacja</option>
            </select>
            </div>
            </div>

            <div class="col-5">
            <div class="naglowek-3">Płatność</div>
                <div class="tresc"><select class="textfield" type="text" id="">
                    <option value="1">TAK</option>
                    <option value="0">NIE</option>
                </select>
            </div>
            </div>

            <div class="col-5">
            <div class="naglowek-3">Forma płatności</div>
                <div class="tresc"><select class="textfield" type="text" id="">
                    <option value="p7">Przelew 7 dni</option>
                    <option value="p14">Przelew 14 dni</option>
                    <option value="p21">Przelew 21 dni</option>
                    <option value="p30">Przelew 30 dni</option>
                    <option value="prepayment">Przedpłata</option>
                    <option value="cash">Gotówka</option>

                </select>
            </div>
            </div>

            <div class="col-5">
            <div class="naglowek-3">Termin realizacji</div>
            <div class="tresc"><input type="date" class="textfield" id=""> 
            </select>
            </div>
            </div>
        </div>

        <div class="col-5">
            <div class="naglowek-2">Dostawa</div>

            <div class="col-5">
            <div class="naglowek-3">Przesyłka</div>
            <div class="tresc"><input type="text" list="delivery" class="textfield" id=""> 
            </select>

            <datalist id="delivery">
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

            <div class="col-5">
            <div class="naglowek-3">Cena</div>
            <div class="tresc"><input type="number" class="textfield" id=""> 
            </select>
            </div>
            </div>

            <div class="col-5">
            <div class="naglowek-3">Ilość</div>
            <div class="tresc"><input type="number" class="textfield" id=""> 
            </select>
            </div>
            </div>

            <div class="col-5">
            <div class="naglowek-3">Numer listu</div>
            <div class="tresc"><input type="text" class="textfield" id=""> 
            </select>
            </div>
            </div>

        </div>
        
        <div class="col-5">
            <div class="naglowek-2">Inne</div>
            <div class="col-5">
        <div class="naglowek-3">Uwagi od klienta</div>
        <div class="tresc"><input class="textfield" id="Comments"> 
        </div>
        </div>    
        </div>    
        

        <br>
        <br>
        <div class="naglowek-2">Produkt</div>


        <div class="col-1">
        <div class="tresc"><input class="textfield"  id="SearchProduct" placeholder="..." onChange="searchproduct()" onClick="searchorder()"></div>
        </div>
        <br>
        <div id="SearchProductResult" class="col-1 productlist">
       
        </div>
        <br>
        
        <div class="col-1">
        <div class="naglowek-3">Nazwa produktu</div>
        <div class="tresc"><input type='text' class="textfield" id="ProductName"> 
        </div>
    </div>
    <br>
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

   <div class="col-5">
       <div class="naglowek-3">Ilość</div>
       <div class="tresc"><input type="number" min="0" class="textfield" id="Amount"> 
       </select>
       </div>
   </div>

   <div class="col-5">
       <div class="naglowek-3">Cena netto</div>
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
    


<hr>

    <div class="btn-primary" onclick="confirm()">Zapisz</div>
    <a href="index.php"><div class="btn-secondary">Anuluj</div></a>

</div>
<div style="height:80px;"></div>
<div class="footer" id="footer">



    <div class="content">
         
    </div>
</div>

</body>
</html>
<?php mysql_close($link); ?>