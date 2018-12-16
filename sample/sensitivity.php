<!DOCTYPE html>
<html>
<body>
<img src="images/logo.png" alt="lullaby" width="169" height="64">
</body>
</html>

<?php
include 'dbConfig.php';
session_start();

		$username = $_SESSION['username'];
		
		$query = "SELECT * FROM `user` WHERE username='$username'";
		$result = mysqli_query($db,$query);
		
		while ($row = $result->fetch_assoc())
			$username = $row['username'];
		
		$cry = isset($_POST['cry']) ? $_POST['cry'] : '';
		$move = isset($_POST['move']) ? $_POST['move'] : '';
		
		if (isset($_POST["sub"]) ) 
		{
			$query = "SELECT * from `sensitivity`";
			$result = mysqli_query($db,$query);
		
			if(mysqli_num_rows($result) > 0 )
			{
				$query = "UPDATE `sensitivity` SET move_time = '$move', cry_time = '$cry'";
				$result = mysqli_query($db,$query);
			
				if($result){
					echo "<div class='registrationSuccess'><h4>Sensitivity Saved</h4>Click here to <a href='control.php'>go back</a></div>";
				}
			}
			
			else
			{
				$query = "INSERT into `sensitivity` (move_time, cry_time) values ('$move', '$cry')";
				$result = mysqli_query($db,$query);
			
				if($result){
					echo "<div class='registrationSuccess'><h4>Sensitivity Saved</h4>Click here to <a href='control.php'>go back</a></div>";
				}
			}	
		}
		
		$query = "SELECT * from `sensitivity`";
		$result = mysqli_query($db,$query);
		
		if(mysqli_num_rows($result) > 0 )
		{
			while($row = $result->fetch_assoc()) {
				$cry = $row["move_time"];
				$move = $row["cry_time"];
				
			}
		}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Settings</title>
	<link rel="stylesheet" href="css/style1.css?v=2">
</head>

<body>
<div class="form">
<form action="sensitivity.php" method="post">

<h2>CRYING SENSITIVITY</h2>
Enter the amount of time (in seconds) that your<br> 
child is crying until you want to be notified.<br>
Note: low values give high sensitivity and high<br>
values give low sensitivty.<br>
<input type="number" name="cry" value = "<?php echo htmlspecialchars($cry); ?>" placeholder="Enter Cry Time" min="0">

<h2>MOVEMENT SENSITIVITY</h2>
Enter the amount of time (in seconds) that your<br> 
child is moving until you want to be notified.<br>
Note: low values give high sensitivity and high<br> 
values give low sensitivty.<br> 
<input type="number" name="move" value = "<?php echo htmlspecialchars($move); ?>" placeholder="Enter Move Time" min="0">

    <input type="submit" value=" Save" name= "sub" />
</form>
<p class = "logout"> <?=$username?> <a href="logout.php">Logout</a></p>
</body>
</html>