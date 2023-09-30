<?php
// Initialize the session
session_start();
include "../sql.php";
include "../colors.php";

$results_per_page = 10;
$page = 1;
if(isset($_GET['page'])){
$current_page = $_GET['page'];
}
else{
    $current_page = 1;
}

$start_from = ($page-1) * $results_per_page;
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$query = "SELECT COUNT(ID) AS total FROM wz;";
// FETCHING DATA FROM DATABASE
$result = mysqli_query($link, $query);


if (mysqli_num_rows($result) > 0) {
    // OUTPUT DATA OF EACH ROW
    while($row = mysqli_fetch_assoc($result)) {
        $total_records = $row["total"];
}
}
$total_pages = ceil($total_records / $results_per_page); 

/*

for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages
echo "<a href='wz-archive.php?page=".$i."'";
if ($i==$page)  echo " class='curPage'";
echo ">".$i."</a> ";
};
*/
?>
 
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Archiwum WZ</title>

    <link href="../style.css" rel="stylesheet" type="text/css" /></head>

    <script type="text/javascript">
    
        function logout(){
            document.getElementById('logout').style.display = 'block';
        }

        function logoutoff(){
            document.getElementById('logout').style.display = 'none';
        }
        
    </script>

</head>

<body>

<?php 
$title = "Archiwum WZ";
include "top.php";
include 'mag-menu.php';
?>

<div style="height:30px;"></div>
<?php


echo "<table width='100%' cellpadding=7 cellspacing=0 border=0>";
        echo "<tr class='header-table'>";
        echo "<td width='30'></td>";
        echo "<td width='60'>ID WZ</td>";
        echo "<td width='110'>Zamówienie</td>";
        echo "<td width='150'>Opis</td>";
        echo "<td>Produkt</td>";
        echo "<td width='120' align='center'>Kod produktu</td>";
        echo "<td width='30'></td>";
        echo "<td width='100'>Wariant</td>";
        echo "<td width='70' align='right'>Ilość</td>";
        echo "<td width='30'>Jm</td>";
        echo "<td width='120' align='center'>Wartość netto</td>";
        echo "<td width='100' align='center'>Akcje</td>";
        echo "<td width='30'></td>";
        echo "</tr>";

        $select_page = ($current_page*$results_per_page)-$results_per_page;


        $query = "SELECT * FROM wz ORDER BY id DESC LIMIT $select_page, $results_per_page;";
        // FETCHING DATA FROM DATABASE
        $result = mysqli_query($link, $query);


        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {

                echo "<tr class='row-table row-table-select'>";
                echo "<td></td>";
                echo "<td><span class='lowerfont' title='" .$row['date']. "'>WZ ".$row['id']."</span></td>";
                echo "<td>" .$row['order_id']. "<div class='lowerfont'>Klient</div></td>";
                echo "<td>opis</td>";
                echo "<td>" .$row['product_name']. "</td>";
                echo "<td align='center'>" .$row['product_code']. "</td>";
                echo "<td><div class='color-icon' style='background-color:".ColorIcon($row['product_variant'])."'></div></td>";
                echo "<td><div>" .$row['product_variant']. "</div></td>";
                echo "<td align='right'>" .$row['amount']. "</td>";
                echo "<td align='center'>szt.</td>";
                echo "<td align='right'>" .$row['unit_netto'] * $row['amount'] . " zł</td>";
                echo "<td align='center'>";
                
                    if ($row['amount'] != 0){
                    echo "<div class='action-icon'><a href='../save.php?wz=".$row['id']."&Action=WZ-RETURN'><img src='../images/wz-back.svg' width='100%' height='100%' border='0'></a></div>";
                    }

                echo "</td>";
                echo "<td></td>";
                echo "</tr>";
                
            }
        } 

        echo "</table>";

?>


<div style="height:120px;"></div>
<div class="footer">
	<div class="content" style="font-size:14px; padding-top:15px; padding-bottom:0px;">

    <table border='0' width='100%'>
                <tr>
                
            
                    <td>Zakres: <?php echo $select_page . "-". $select_page + $results_per_page ?>
                    </td>   
                    <td align="right">Strona: <?php echo $current_page . " / " . $total_pages; ?>
                    </td>        
                    <td align="right" width="120">

                    <?php
                        
                        // TOGGLE PAGES BUTTONS

                        if ($current_page == 1){
                            $change_page = $current_page-1;
                            echo "<div class='btn-pages-2'><</div>";                   
                        }

                        if ($current_page > 1){
                            $change_page = $current_page-1;
                            echo "<a href='wz-archive.php?page=".$change_page."'><div class='btn-pages'><</div></a>";                       
                        }
        
                        if ($current_page < $total_pages){
                            $change_page = $current_page+1;
                            echo "<a href='wz-archive.php?page=".$change_page."'><div class='btn-pages'>></div></a>";   
                        }

                        if ($current_page == $total_pages){        
                            $change_page = $current_page-1;
                            echo "<div class='btn-pages-2'>></div>";                      
                        }
                    ?>
                        
                    </td>    
                </tr>
            </table>

        
        <br>
        
    </div>


</body>
</html>