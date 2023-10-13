<?php

?>

<div class="top">
	<div class="content">
       
    
            <table border='0' width='100%'>
                <tr>
                <td height='60' width='250'><a href="../index.php"><img class="main-logo" src="../images/artilon-logo-r.svg" border="0"></a>
                    </td>
                    <td><div class="naglowek-top"><?php echo $title; ?></div>
                    </td>   
                    <td><div class="login-box"><?php echo htmlspecialchars($_SESSION["name"]) . " " . htmlspecialchars($_SESSION["surname"]); ?></div>
                    
                    
                    </td>
                        <?php
                        $filename = "../images/".htmlspecialchars($_SESSION["username"]).".png";

                        if (file_exists($filename)) {
                            
                        } else {
                            $filename = "../images/user-icon.svg";
                        }
                        ?>
                    <td width="60"><img  class="img-foto-top" src="<?php echo $filename;?>" onmouseover="logout()">
                    <a href="logout.php" ><img id="logout" class="img-foto-top" style="opacity:0.9; display:none" src="../images/logout-icon.svg" onmouseout="logoutoff()"></a>
                    
                    </td>    
                </tr>
            </table>

            <div>
            </div>

        
        
   
    </div>
</div>