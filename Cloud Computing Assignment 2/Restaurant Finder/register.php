<?php
$dsn = getenv('MYSQL_DSN');
$user = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');

// create the PDO client
//$db = new PDO($dsn, $user, $password);
$conn = mysqli_connect(null, $user, $password, 'users',null,'/cloudsql/cloud-assignment-2-254823:australia-southeast1:s3557584-2019');

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
include_once 'aes.php';
$aes = new Aes('abcdefgh01234567', 'CBC', '1234567890abcdef');
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
	// Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
	if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = $aes->encrypt($password);// Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                //header("location: /");
				echo '<script type="text/javascript"> window.open("login.php","_self");</script>';
				echo"Added";
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
	
	mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Register</title>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="css/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
    <div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login100-form validate-form p-l-55 p-r-55 p-t-178">
					<span class="login100-form-title">
						Register
					</span>
					<label>Username</label>
					<div class="wrap-input100 validate-input m-b-16 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
						<input type="text" name="username" class="input100" value="<?php echo $username; ?>">
						<span class="help-block"><?php echo $username_err; ?></span>
					</div>
					<label>Password</label>
					<div class="wrap-input100 validate-input m-b-16 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
						<input type="password" name="password" class="input100" value="<?php echo $password; ?>">
						<span class="help-block"><?php echo $password_err; ?></span>
					</div>
					<label>Confirm Password</label>
					<div class="wrap-input100 validate-input m-b-16 <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
						<input type="password" name="confirm_password" class="input100" value="<?php echo $confirm_password; ?>">
						<span class="help-block"><?php echo $confirm_password_err; ?></span>
					</div>
				<div class="container-login100-form-btn">
					<input type="submit" class="login100-form-btn" value="Submit">
				</div>
				<div class="flex-col-c p-t-170 p-b-40">
						<span class="txt1 p-b-9">
							Already have an account?
						</span>
						<a href="/" class="txt3">
							Login now
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>   
<script src="javascript/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="javascript/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="javascript/popper.js"></script>
	<script src="javascript/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="javascript/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="javascript/moment.min.js"></script>
	<script src="javascript/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="javascript/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="javascript/main.js"></script>	
</body>
</html>