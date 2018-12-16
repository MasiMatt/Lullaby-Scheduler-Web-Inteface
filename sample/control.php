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
?>
<!DOCTYPE html>
<html>
<head>
	<title>Control Panel</title>
	<link rel="stylesheet" href="css/style1.css?v=3">
</head>

<body>
<form action="week/sampleschedule.php">
    <input type="submit" value="Weekly Calendar" />
</form>
<form action="alarm.php">
    <input type="submit" value="Alarm Settings" />
</form>
<form action="sensitivity.php">
    <input type="submit" value="Sensitivity Settings" />
</form>
<form action="updateAccount.php">
    <input type="submit" value="Account Settings" />
</form>

<form action="videoFeed.php">
    <input type="submit" value="Camera Setup" />
</form>
<p class = "logout"> <?=$username?> <a href="logout.php">Logout</a></p>


</body>
</html>