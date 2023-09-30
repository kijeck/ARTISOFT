<div class="confirmbox" id="confirmbox">
	<div class="content">
       
    
            <table border='0' width='100%'>
                <tr height="150">
                <td width="150">
                <?php
                        $filename = "../images/".htmlspecialchars($_SESSION["username"]).".png";

                        if (file_exists($filename)) {
                            
                        } else {
                            $filename = "../images/user-icon.svg";
                        }
                        ?>
                        <img  class="img-foto-top" style="width:120px; height:120px;" src="<?php echo $filename;?>"">
                    </td>
                    <td><div class="confirm-text"><?php echo htmlspecialchars($_SESSION["name"]); ?>, potwierdź czy się zgadza.</div>
                    </td>   
                            
                    <td align="right">
                        <div class="btn-confirm-yes" onclick="save()">TAK</div>
                        <div class="btn-confirm-no" onclick="cancel()">NIE</div>
                    </td>    
                </tr>
            </table>

            <div>
            </div>

   
    </div>
</div>