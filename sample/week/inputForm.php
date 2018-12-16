<!DOCTYPE html>
<html>
<body bgcolor="#ffffff">
<img src="../images/logo.png" alt="lullaby" width="169" height="64">
</body>
</html>

<?php
	session_start();
	$sched = $_SESSION['sched'];
	include '../dbConfig.php';
	
	function overlap ($st, $en, $sc, $da){
		include '../dbConfig.php';
		$query = "SELECT * FROM `time_slot` WHERE schedule_num = '$sc'";
		$result = mysqli_query($db,$query);	
		$overlap = false;
		
		if($result){
			while($row = mysqli_fetch_array($result))
			{
				$i = 0;
				$j = 0;
				$digOld = getDigits($row['dates']);
				$digNew = getDigits($da);
				$eOld = explode(',', $digOld);
				$eNew = explode(',', $digNew);
				
				foreach($eOld as $i =>$key) {
					foreach($eNew as $j =>$key) {
						//echo $i .$j .$eOld[$i] .$eNew[$j] ." ";
						if($eOld[$i] == $eNew[$j])
							$overlap = timeOverlap($st, date('H:i', strtotime($row['start_time'])), $en, date('H:i', strtotime($row['end_time'])));
						if($overlap == true)
							break;
					}
					if($overlap == true)
							break;
				}
				if($overlap == true)
							break;
			}
		}
		return $overlap;
	}
	
	function getDigits($num)
	{
		$days = "";
		$n = $num;
		while(true) 
		{
			$digit = $n % 10;
			$n /= 10;
			
			if($digit == 0)
				break;
			
			$days .= $digit . ",";
				// Do something with digit
		}
		$daysTrim = rtrim($days,",");
		return $daysTrim;
	}
	
	function timeOverlap($s1, $s2, $e1, $e2){
		if($s1 >= $e2 || $e1 <= $s2)
			$overlap = false;
		else
			$overlap = true;
		
		return $overlap;
	}
	
	$parent = isset($_POST['parent']) ? $_POST['parent'] : '';
	$start = isset($_POST['start']) ? $_POST['start'] : '';
	$end = isset($_POST['end']) ? $_POST['end'] : '';

	$datesAll = "";
	if(isset($_POST['dates'])){ // select_name will be replaced with your input filed name
		$getInput = $_POST['dates']; // select_name will be replaced with your input filed name
		//$datesAll = "";
		foreach ($getInput as $option => $value) {
			$datesAll .= $value; // I am separating Values with a comma (,) so that I can extract data using explode()
		} 
	}
	
	$over = overlap($start, $end, $sched, $datesAll); 
	if($over == false)
		echo "False";
	else
		echo "True";
	
	if (isset($_POST["dates"])) 
	{
		if($over == false)
		{
			$query = "INSERT into `time_slot` (dates, start_time, end_time, parent, schedule_num) values ('$datesAll', '$start', '$end', '$parent', '$sched')";
			$result = mysqli_query($db,$query);
			
			if($result)
				echo "<script type='text/javascript'>window.parent.location.reload()</script>";
			else
				echo "<div class='fail'><h4>Select a schedule first!</h4></div>";
		}
		
		else if($over == true)
			echo "<div class='fail'><h4>Overlaping time slot!</h4></div>";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Time Slot</title>
	<link rel="stylesheet" href="../css/style1.css?v=2">
</head>
<body>

<div class="form">
<form action="inputForm.php" method="post">
	<p><b>Parent: </b><select name="parent">
		<option value="A">Parent A</option>
		<option value="B">Parent B</option>
	</select></p>
	<p><b>Start: </b><input type="time" name="start" id="start" value="12:00"/></p>
	<p><b>End: </b><input type="time" name="end" id="end" value="18:00"/></p>
	
	<p><b>Dates: </b><select size="7" name="dates[]" multiple>
		<option value="1">Monday</option>
		<option value="2">Tuesday</option>
		<option value="3">Wednesday</option>
		<option value="4">Thursday</option>
		<option value="5">Friday</option>
		<option value="6">Saturday</option>
		<option value="7">Sunday</option>
	</select></p>
	<input type="submit" id="addTimeBtn" value="Add"/>
</form>

</body>
</html>
