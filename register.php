<?php
// Include config file
require_once "sql.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Wpisz nazwę użytkonika";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Ten użytkownik już istnieje. Wymyśl coś innego.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Ups! Coś poszło nie tak. Spróbuj ponownie.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Wpisz hasło";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Hasło powinno być dłuższe niż 6 znaków.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Powtórz hasło.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Błędne hasło.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>Rejestracja</title>
    <link href="style.css" rel="stylesheet" type="text/css" /></head>
</head>
<body>

<div class="top">
	<div class="content">
           <table border='0' width='100%'>
                <tr>
                    <td height='60' width='250'><img class="main-logo" src="images/artilon-logo-r.svg">
                    </td>
                    <td><div class="naglowek-top">Rejestracja</div>
                    </td>   
                </tr>
            </table>
    </div>
</div>

<div class="content">
    
        <h2>Wypełnij formularz aby założyć konto</h2>
        <div style="width:500px;">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="col-5">
            <div class="naglowek-3">Nazwa użytownika</div>
                <input type="text" name="username" class="textfield <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="col-5">
            <div class="naglowek-3">Hasło</div>
                <input type="password" name="password" class="textfield <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="col-5">
            <div class="naglowek-3">Powtórz hasło</div>
                <input type="password" name="confirm_password" class="textfield <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <br><br>
            <div class="col-5">
                <div><input type="submit" class="btn-primary btn-primary-submit" value="Załóż konto"></div>
                <input type="reset" class="btn-secondary-submit" value="Wyczyść">
            </div>
            
        </form>
        </div>    
</div>

<div class="footer">
	<div class="content">
    Masz już konto? <a href="login.php">Zaloguj się</a>.
    </div>
</div>


</body>
</html>