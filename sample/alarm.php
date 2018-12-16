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
		
		$timer = isset($_POST['timer']) ? $_POST['timer'] : '';
		$sound = isset($_POST['sound']) ? $_POST['sound'] : '';
		
		$toggle = isset($_POST['togBtn']);
		
		if (isset($_POST["togBtn"]) ) {
			$toggle = 1;
		} else {
			$toggle = 0;
		}
		
		if (isset($_POST["sub"]) ) 
		{
			$query = "SELECT * from `alarm`";
			$result = mysqli_query($db,$query);
			
			if(mysqli_num_rows($result) > 0 )
			{
				$query = "UPDATE `alarm` SET active = '$toggle', timer = '$timer', sound = '$sound'";
				$result = mysqli_query($db,$query);
				
				if($result){
						echo "<div class='registrationSuccess'><h3>Alarm Saved</h3>Click here to <a href='control.php'>go back</a></div><br>";
					}
			}
			else
			{
				$query = "INSERT into `alarm` (active, timer, sound) values ('$toggle', '$timer', '$sound')";
				$result = mysqli_query($db,$query);
				
				if($result){
						echo "<div class='registrationSuccess'><h3>Alarm Saved</h3>Click here to <a href='control.php'>go back</a></div>";
					}
			}
				
		}
		
		$query = "SELECT * from `alarm`";
		$result = mysqli_query($db,$query);
		
		if(mysqli_num_rows($result) > 0 )
		{
			while($row = $result->fetch_assoc()) {
				$toggle = $row['active'];
				$timer = $row['timer'];
				$sound = $row['sound'];;
			}
		}
		
		if($toggle == 1)
			$check = "checked";
		else
			$check = " ";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Settings</title>
	<link rel="stylesheet" href="css/style1.css?v=5">
</head>

<body>
<div class="form">
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 90px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ca2222;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2ab934;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(55px);
  -ms-transform: translateX(55px);
  transform: translateX(55px);
}

.on
{
  display: none;
}

.on, .off
{
  color: white;
  position: absolute;
  transform: translate(-50%,-50%);
  top: 50%;
  left: 50%;
  font-size: 10px;
  font-family: Verdana, sans-serif;
}

input:checked+ .slider .on
{display: block;}

input:checked + .slider .off
{display: none;}

/*--------- END --------*/

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;}
</style>

<style type="text/css">     
    select {
		height:38px;
        width:320px;
    }
</style>

<form action="alarm.php" method="post">
<h2>ALARM ON/OFF</h2>
<label class="switch">
	<input type="checkbox" name="togBtn" id="tog" <?php echo $check ?>> 
	<div class="slider round">
		<span class="on">ON</span>
		<span class="off">OFF</span>
	</div>
</label>
<br>Removing the audio alarm feature will eliminate<br>
the systems safety feature. The user will only<br>
be notified with the bracelet.<br>

<h2>TIMER LIMIT</h2>
<input type="number" name="timer" value = "<?php echo htmlspecialchars($timer); ?>" placeholder="Enter Alarm Timer Limit" min="0">

<h2>ALARM SOUND</h2>
<select name="sound">
  <option value="alarms/bell.mp3">Old Bell</option>
  <option <?php if ($sound == "alarms/buzzer.mp3") echo 'selected' ; ?> value="alarms/buzzer.mp3">Alarm Clock Buzzer</option>
  <option <?php if ($sound == "alarms/siren.mp3") echo 'selected' ; ?> value="alarms/siren.mp3">Siren</option>
</select>
<br>
    <input type="submit" value=" Save" name= "sub" />
</form>
<p class = "logout"> <?=$username?> <a href="logout.php">Logout</a></p>
</body>
</html>