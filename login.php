<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "sql.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Wpisz nazwę użytkownika";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Wpisz hasło";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT users_id, username, surname, name, type, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $surname, $name, $type, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;  
                            $_SESSION["name"] = $name;
                            $_SESSION["surname"] = $surname;
                            $_SESSION["type"] = $type;                          
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Błędna nazwa użytkownika lub hasło.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Błędna nazwa użytkownika lub hasło.";
                }
            } else{
                echo "Ups! Coś poszło nie tak. Spróbuj ponownie.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>ZAMÓWIENIA</title>
    <link href="style.css" rel="stylesheet" type="text/css" /></head>

</head>
<body>


<div class="top">
	<div class="content">
       
    
            <table border='0' width='100%'>
                <tr>
                    <td height='60' width='250'><img class="main-logo" src="images/artilon-logo-r.svg">
                    </td>
                    <td><div class="naglowek-top">Logowanie</div>
                    </td>   

                     
                       
                </tr>
            </table>

            <div>
            </div>

        
        
   
    </div>
</div>


<div class="content">


<?php 
if(!empty($login_err)){
    echo '<div class="alert alert-danger">' . $login_err . '</div>';
}        
?>

<div style="width:300px;">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="col-5">
        <div class="naglowek-3">Nazwa użytkownika</div>
            <div class="tresc">
            <input type="text" name="username" class="textfield <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
    </div>    
    <div class="col-5">
    <div class="naglowek-3">Hasło</div>
        <div class="tresc">
        <input type="password" name="password" class="textfield <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
    </div>
    <br><br>
    <div class="col-5">
        <div class="tresc">
        <input type="submit" class="btn-primary btn-primary-submit" value="Zaloguj">
        </div>
    </div>
    <p></p>
</form>
</div>

</div>

<div class="footer">
	<div class="content">
        Nie masz jeszcze konta? <a href="register.php">Załóż teraz</a>.
    </div>
</div>


    
</body>
</html>