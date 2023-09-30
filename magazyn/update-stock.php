<?php
$Date=date("Y-m-d H:i:s");

$data = array();   
$data2 = array();    
/*

$query = "SELECT id, amount, product_code FROM pz WHERE amount > 0 ORDER BY id DESC";
        // FETCHING DATA FROM DATABASE
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {
                $data2 = array(
                    'product_code' => $row['product_code'],
                    'amount' => $row['amount']
                );
                $data[] = $data2;
            }  
        } 
        $data[] = $data2;
        $json_data = json_encode($data);
        file_put_contents('artilon-stock.json', $json_data);

  */
 
        /// zsumowane po kodach

        $product_code_sum = "";
        $amount_sum = 0;

        $query = "SELECT DISTINCT id, amount, product_code FROM pz WHERE amount > 0 GROUP BY product_code";
        // FETCHING DATA FROM DATABASE
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            // OUTPUT DATA OF EACH ROW
            while($row = mysqli_fetch_assoc($result)) {
                
                $product_code_sum = $row['product_code'];

                $amount_sum = 0;

                    $query = "SELECT id, amount, product_code FROM pz WHERE amount > 0 AND product_code = '$product_code_sum'";
                    // FETCHING DATA FROM DATABASE
                    $result2 = mysqli_query($link, $query);

                    if (mysqli_num_rows($result2) > 0) {
                        // OUTPUT DATA OF EACH ROW
                        while($row2 = mysqli_fetch_assoc($result2)) {
                            $amount_sum = $amount_sum + $row2['amount'];
                           
                        }  
                    }
                    //echo $product_code_sum . " - " . $amount_sum . "<br>";    
                    $data2 = array(
                        'product_code' => $product_code_sum,
                        'amount' => $amount_sum
                    );
                $data[] = $data2;        
                
            }  
        } 

        $data[] = $data2;
        $json_data = json_encode($data);
        file_put_contents('artilon-stock.json', $json_data);
     

        
?>




