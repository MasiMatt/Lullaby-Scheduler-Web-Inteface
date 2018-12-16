<!DOCTYPE html>
<html>
<head>
	<img src="images/logo.png" alt="lullaby" width="169" height="64">
	<title>Log In</title>
	<link rel="stylesheet" href="css/style1.css?v=2">
</head>
<body>

<?php	
session_start();	
		if (isset($_REQUEST['username'])){
			
		$con=mysqli_connect("localhost","root","root","sample");
		if (mysqli_connect_errno()) {
		throw new Exception(mysqli_connect_error(), mysqli_connect_errno());
		}
	
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		$query = "INSERT into `user` (username, email, password, active) VALUES ('$username', '$email', '$password', 1)";
		$result = mysqli_query($con,$query);
		
		if($result){
			$_SESSION['username'] = $username;
			header("Location:control.php");
			//echo "<div class='registrationSuccess'><h3>You have registered successfully.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
		}
	}
?>

<h1>Create account</h1>

<div class="container">
<div id="accordion" role="tablist" aria-multiselectable="true">
  <div class="card">
    <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
      <div class="card-block">
        
		<!-- FORM -->
		
		<form action="registration.php" method="post">
		
			<input type="hidden" name="formType" value="4" />
			
			<input type="text" name="username" placeholder="Username" required />
			<input type="password" name="password" placeholder="Password" required />
			<input type="email" name="email" placeholder="Email" required />	
			<input class="btn btn-primary" type="submit" name="submit" value="Register" />
		</form>
      </div>
    </div>
  </div>
</div>
</div>
</body>
</html>
