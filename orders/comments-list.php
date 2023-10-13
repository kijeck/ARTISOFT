<?php
include "../sql.php";
session_start();
$username = htmlspecialchars($_SESSION["username"]);
$order_number = $_GET['order_number'];
$ordered_product_id = $_GET['ordered_product_id'];
$type=0;
$commenttext="";
$Date=date("Y-m-d H:i:s");
$textstyle="";
$comments_id="";

if (isset($_GET['comments_id'])){
    $comments_id = $_GET['comments_id'];
}

    // SAVE COMMENT TO DB

        if (isset($_GET['type'])){
            $type = $_GET['type'];
            $commenttext = $_GET['commenttext'];
            
            if ($type==1){
                
                $sql = "INSERT INTO comments (order_number, ordered_product_id, comments, active, user, date) VALUES ('$order_number', '$ordered_product_id', '$commenttext', '1', '$username', '$Date')";
                if (mysqli_query($link, $sql)) {
                } 
                else {
                echo "Error: " . $sql . "<br>" . mysqli_error($link);
            }

            }         
        }
        
    // END


    // DELETE COMMENT

      
        if ($comments_id != ""){
            
            $sql = "UPDATE comments SET active='0' WHERE comments_id='$comments_id'";
            if (mysqli_query($link, $sql)) {
                //echo "ok";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($link);
            }   
            
        }

         
    
    
// END



    // LIST OF COMMENTS


            echo "<table width='100%' cellpadding=3 cellspacing=0 border=0 >";
                        
                $query = "SELECT * FROM comments LEFT JOIN users ON users.username = comments.user WHERE order_number = $order_number AND ordered_product_id = $ordered_product_id ORDER BY comments_id DESC";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {

                        if ($row['active']==false){
                            $textstyle = 'deleted-comment';
                        }

                                                           
                        echo "<tr class='' style='margin-bottom:10px'>";
                        echo "<td width='30' valign='top' align='center'>";
                        echo "<div><img src='../images/".$row['user'].".png' class='comments-avatar' title='".$row['name']." ".$row['surname']." - ".$row['date']."'></div>";   
                          
                        echo "<td width='300' >"; 
                        echo "<span class='".$textstyle."'>".$row['comments']."</span>";
                        echo "";
                        echo "</td>"; 
                        echo "<td width='20' valign='top' >";
                        
                        if ($row['user']==$username && $row['active']==true){
                            echo "<div class='action-icon-2' onclick='deletecomment(".$row['comments_id'].",".$order_number.",".$ordered_product_id.")'><img src='../images/close-icon.svg' width='100%' height='100%'></div>";
                        }
                        
                        echo "</td>"; 
                        echo "</tr>";
                        $textstyle="";
                    }
                } 
            echo "</table>";
            
?>