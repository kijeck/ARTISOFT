<?php
include "../sql.php";
session_start();

$user = htmlspecialchars($_SESSION["username"]);
$UserName = htmlspecialchars($_SESSION["name"]) . " " . htmlspecialchars($_SESSION["surname"]);
$Date=date("Y-m-d H:i:s");
$ordered_product_id = $_GET['ordered_product_id'];
$type = $_GET['type'];
$Date=date("Y-m-d H:i:s");
$groupitem="";
$statusname="";

        // GET NAME OF GROUP

        $query = "SELECT id, product_group, status FROM ordered_product WHERE id = $ordered_product_id";
        // FETCHING DATA FROM DATABASE
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {

                $groupitem = $row['product_group'];

                if ($type==1){
                    
                    // NAMES OF STATUS - FORWARD

                    if ($row['status']=='przyjęte'){
                        $statusname = 'wizualizacja';
                    }

                    if ($row['status']=='wizualizacja'){
                        $statusname = 'akceptacja';
                    }

                    if ($row['status']=='akceptacja'){
                        $statusname = 'w realizacji';
                    }

                    if ($row['status']=='w realizacji'){
                        $statusname = 'przygotowalnia';
                    }

                    if ($row['status']=='przygotowalnia'){
                        $statusname = 'w produkcji';
                    }

                    if ($row['status']=='w produkcji'){
                        $statusname = 'gotowe';
                    }

                    if ($row['status']=='gotowe'){
                        //$statusname = 'wysłane';
                    }

                    if ($row['status']=='wysłane'){
                        //$statusname = 'rozliczone';
                    }

                    if ($row['status']=='rozliczone'){
                        //$statusname = 'zakończone';
                    }
                }

                if ($type==2){
                    
                    // NAMES OF STATUS - BACKWARD

                    if ($row['status']=='zakończone'){
                        $statusname = 'rozliczone';
                    }

                    if ($row['status']=='rozliczone'){
                        $statusname = 'wysłane';
                    }

                    if ($row['status']=='wysłane'){
                        $statusname = 'gotowe';
                    }

                    if ($row['status']=='gotowe'){
                        $statusname = 'w produkcji';
                    }

                    if ($row['status']=='w produkcji'){
                        $statusname = 'w realizacji';
                    }

                    if ($row['status']=='w realizacji'){
                        $statusname = 'przygotowalnia';
                    }

                    if ($row['status']=='przygotowalnia'){
                        $statusname = 'akceptacja';
                    }

                    if ($row['status']=='akceptacja'){
                        $statusname = 'wizualizacja';
                    }

                    if ($row['status']=='wizualizacja'){
                        $statusname = 'przyjęte';
                    }
                }
                



   
        } 
        }


        // CHANGE STATUS TO ALL VARIANTS OF GROUP

            if ($statusname!=''){
                $query2 = "SELECT product_group FROM ordered_product WHERE product_group = '$groupitem';";
            // FETCHING DATA FROM DATABASE
            $result2 = mysqli_query($link, $query2);

            if (mysqli_num_rows($result2) > 0) {
                // OUTPUT DATA OF EACH ROW
                while($row2 = mysqli_fetch_assoc($result2)) {
                
                    $sql = "UPDATE ordered_product SET status='$statusname' WHERE product_group='$groupitem'";
                    if (mysqli_query($link, $sql)) {
                      } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($link);
                    } 
                }
            } 
            }

            // SAVE TO LOG    
            $ActionType = "ORDERS";
            $Content = "Zmiana statusu na: " . $statusname;
            $sql = "INSERT INTO log (user_id, user_name, type, type_id, time, content) VALUES ('$user', '$UserName', '$ActionType', '$ordered_product_id', '$Date', '$Content')";
                if (mysqli_query($link, $sql)) {
            } 
            else {
            echo "Error: " . $sql . "<br>" . mysqli_error($link);
            }
            // END OF LOG
                
            
        // END


        // GET ACTUAL STATUS ICON

        $query = "SELECT id, product_group, status FROM ordered_product WHERE id = $ordered_product_id";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {
                        
                      

                        if ($row['status'] == 'przyjęte'){
                            echo "<div><img src='../images/status-przyjete.svg' class='status-icon'></div>";
                        }   
                        if ($row['status'] == 'wizualizacja'){
                            echo "<div><img src='../images/status-wizualizacja.svg' class='status-icon'></div>";
                        }  
                        if ($row['status'] == 'akceptacja'){
                            echo "<div><img src='../images/status-akceptacja.svg' class='status-icon'></div>";
                        }  
                        if ($row['status'] == 'przygotowalnia'){
                            echo "<div ><img src='../images/status-przygotowalnia.svg' class='status-icon'></div>";
                        } 
                        if ($row['status'] == 'w realizacji'){
                            echo "<div ><img src='../images/status-w-realizacji.svg' class='status-icon'></div>";
                        }     
                        if ($row['status'] == 'w produkcji'){
                            echo "<div ><img src='../images/status-w-produkcji.svg' class='status-icon'></div>";
                        }      
                        if ($row['status'] == 'gotowe'){
                            echo "<div ><img src='../images/status-gotowe.svg' class='status-icon'></div>";
                        } 

                        

                } 
            }






    // DELETE COMMENT

      /*
        if ($comments_id != ""){
            
            $sql = "UPDATE comments SET active='0' WHERE comments_id='$comments_id'";
            if (mysqli_query($link, $sql)) {
                //echo "ok";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($link);
            }   
            
        }
        */

         
    
    
// END



    // LIST OF COMMENTS


                /*     
                $query = "SELECT * FROM comments LEFT JOIN users ON users.username = comments.user WHERE order_number = $order_number AND ordered_product_id = $ordered_product_id ORDER BY comments_id DESC";
                // FETCHING DATA FROM DATABASE
                $result = mysqli_query($link, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    // OUTPUT DATA OF EACH ROW
                    while($row = mysqli_fetch_assoc($result)) {

                        // icon

                } 
                */

            
?>