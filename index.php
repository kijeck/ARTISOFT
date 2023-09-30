<?php
// Initialize the session
session_start();
 
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
    <title>ZAMÓWIENIA</title>

    <link href="style.css" rel="stylesheet" type="text/css" /></head>

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
$title = "Zamówienia";
include "top.php";
include "main-menu.php";

?>

<div class="content">

</div>

<div class="footer">
	<div class="content">
        podsumowanie
    </div>
</div>

</body>
</html>