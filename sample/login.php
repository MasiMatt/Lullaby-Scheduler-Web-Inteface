<!DOCTYPE html>
<html>
<body>
<img src="images/logo.png" alt="lullaby" width="169" height="64">
</body>
</html>

<?php
	session_start();
	// If form submitted, check user credentials
	if(isset($_POST["username"], $_POST["password"])) 
    {     
		$con=mysqli_connect("localhost","root","root","sample");
		if (mysqli_connect_errno()) {
		throw new Exception(mysqli_connect_error(), mysqli_connect_errno());
		}

        $username = $_POST["username"]; 
        $password = $_POST["password"]; 

        $query = "SELECT * FROM `user`"; 
		$result = mysqli_query($con,$query);
		
        if($result->num_rows > 0)
        { 
			$query1 = "SELECT * FROM `user` WHERE username='$username' and password='$password'";
			$result1 = mysqli_query($con,$query1);
			
			if($result1->num_rows > 0){
				$_SESSION['username'] = $username;
				header("Location:control.php");
				//echo "<div class='registrationSuccess'><h3>You have logged in successfully.</h3><br/></div>";
			}
			else
				echo "<div class='errorCredentials'><h3>Wrong credentials.</h3><br/></div>";
        }
		
        else
        {
			header("Location:registration.php");
			//echo "<div class='errorCredentials'><h3>Wrong credentials.</h3><br/></div>";
        }
}	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Log In</title>
	<link rel="stylesheet" href="css/style1.css?v=3">
</head>
<body>

<div class="form">
<h1>Log In</h1>
<form action="login.php" method="post">
	<input type="text" name="username" placeholder="Enter username"/>
	<input type="password" name="password" placeholder="Enter password"/>
	<input class="btn btn-success" name="submit" type="submit" value="Login" />
</form>
</body>
</html>
