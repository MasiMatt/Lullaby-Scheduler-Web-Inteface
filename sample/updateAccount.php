<!DOCTYPE html>
<html>
<head>
	<img src="images/logo.png" alt="lullaby" width="169" height="64">
	<title>Log In</title>
	<link rel="stylesheet" href="css/style1.css?v=3">
</head>
<body>

<?php		
include 'dbConfig.php';
session_start();

		$username = isset($_POST['username']) ? $_POST['username'] : '';
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';

		if (isset($_POST["submit"]) ) 
		{
			
			$query = "UPDATE `user` SET username = '$username', email = '$email', password = '$password'";
			$result = mysqli_query($db,$query);
				
			if($result){
				$_SESSION['username'] = $username;
				echo "<div class='registrationSuccess'><h4>Account Updated</h4>Click here to <a href='control.php'>go back</a></div>";
			}
		}	
		
		$query = "SELECT * from `user`";
		$result = mysqli_query($db,$query);
		
		if(mysqli_num_rows($result) > 0 )
		{
			while($row = $result->fetch_assoc()) {
				$username = $row["username"];
				$email = $row["email"];
				$password = $row["password"];
			}
		}
?>

<h1>Update account</h1>

<div class="container">
<div id="accordion" role="tablist" aria-multiselectable="true">
  <div class="card">
    <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
      <div class="card-block">
        
		<!-- FORM -->
		
		<form action="updateAccount.php" method="post">
		
			<input type="hidden" name="formType" value="4" />
			
			<input type="text" name="username" value = "<?php echo htmlspecialchars($username); ?>" placeholder="Username" required />
			<input type="password" name="password" value = "<?php echo htmlspecialchars($password); ?>" placeholder="Password" required />
			<input type="email" name="email" value = "<?php echo htmlspecialchars($email); ?>" placeholder="Email" required />	
			<input class="btn btn-primary" type="submit" name="submit" value="Update" />
		</form>
      </div>
    </div>
  </div>
</div>
</div>
<p class = "logout"> <?=$username?> <a href="logout.php">Logout</a></p>
</body>
</html>
