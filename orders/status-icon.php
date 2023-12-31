<?php
include "../sql.php";
session_start();

$user = htmlspecialchars($_SESSION["username"]);
$UserName = htmlspecialchars($_SESSION["name"]) . " " . htmlspecialchars($_SESSION["surname"]);
$Date=date("Y-m-d H:i:s");
$ordered_product_id = $_GET['ordered_product_id'];
$order_number = $_GET['order_number'];
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

                    if ($row['status']=='złożone'){
                        $statusname = 'przyjęte';
                        // zmiana opiekuna i statusu
                    }
                    
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

                    if ($row['status']=='przyjęte'){
                        $statusname = 'złożone';
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

            // CHECK ALL PRODUCTS IF THE SAME STATUS

            $count_status = 0;
            $query3 = "SELECT product_group, order_number, status FROM ordered_product WHERE order_number = '$order_number'";
            // FETCHING DATA FROM DATABASE
            $result3 = mysqli_query($link, $query3);

            if (mysqli_num_rows($result2) > 0) {
                // OUTPUT DATA OF EACH ROW
                while($row3 = mysqli_fetch_assoc($result3)) {

                    //echo "<br>".$row3['status'];
                    if ($statusname != $row3['status']){
                        $count_status++;
                    }
                    
                }
            } 

            //echo "<br>".$count_status;

            // CHANGE MAIN STATUS AND GO TO API


            if ($count_status == 0){

                $sql = "UPDATE orders SET main_status='$statusname' WHERE number='$order_number'";
                    if (mysqli_query($link, $sql)) {
                      } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($link);
                    } 
                
                if ($statusname == 'złożone'){
                    changeStatusApi($order_number, 0);
                }

                if ($statusname == 'przyjęte'){
                    changeStatusApi($order_number, 1);
                    // be the guardian

                    $sql = "UPDATE orders SET order_guardian='$user' WHERE number='$order_number'";
                    if (mysqli_query($link, $sql)) {
                      } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($link);
                    } 
                }

                if ($statusname == 'w realizacji'){
                    changeStatusApi($order_number, 2);
                }

                if ($statusname == 'gotowe'){
                    changeStatusApi($order_number, 3);
                }

                if ($statusname == 'zrealizowane'){
                    changeStatusApi($order_number, 4);
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
                        
                      
                        echo "<div id='statusicon2" .$row['id']. "' onclick='changestatus(1, " .$row['id']. ", ".$order_number." ) , showwaiting2(" .$row['id']. ")' style='cursor:pointer;'>";
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
                        echo "</div>";
                } 
            }

            // FUNCTION CHANFE STATUS TO API

            function changeStatusApi($order_id, $status_number){

                $curl = curl_init();

                $url = "https://artilon-test.pl/api/orderSet/" . $order_id;

                curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('token' => 'mIuQHYxLYSXTWX63UYfJE0X75YKOjfsr2SeHaUHeyqJSXrcpOFauUZ2pqybwiPi8','status' => $status_number),
                ));

                $response = curl_exec($curl);

                curl_close($curl);


            }
?>