<?php
    session_start();
	$sched = $_SESSION['sched'];
	include '../dbConfig.php';
	$sql = "SELECT tid, dates, start_time, end_time, parent FROM `time_slot` WHERE schedule_num = '$sched'";
	$result = mysqli_query($db,$sql);
	
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
			
			$days .= convertToDay($digit);
				// Do something with digit
		}
		echo $days;
	}
	
	function convertToDay($num)
	{
		switch ($num) 
		{
			case 1:
				$days = "M";
				break;
			case 2:
				$days = "T";
				break;
			case 3:
				$days = "W";
				break;
			case 4:
				$days = "Th";
				break;
			case 5:
				$days = "F";
				break;
			case 6:
				$days = "Sa";
				break;
			case 7:
				$days = "Su";
				break;
		}
		return $days;
	}
	
	function TimeToAMPM($time)
    {
		list($hour, $minute) = array_pad(explode(":", $time, 2),2, null);
		if($hour < 12)
		{
			if($hour == 0)
			{
				$hour = 12;
			}

			$new_time = "$hour:$minute AM";
			return $new_time;
		}
		else
		{
			if($hour != 12)
			{
				$hour -= 12;
			}
         
			$new_time =  "$hour:$minute PM";
			return $new_time;
		
		}
	}
?>
<html>
	<link rel="stylesheet" type="text/css" href="delete.css?v=7" />
	<body bgcolor="#ffffff">
	<form name="frmTimeslots" method="post" action="deleteForm.php">
		<div style="width:350px;">
			<table border="1" cellpadding="1" cellspacing="0" width="350" class="tblListForm">
				<tr class="listheader">
				<td></td>
				<td>Dates</td>
				<td>Start</td>
				<td>End</td>
				<td>Parent</td>
				</tr>

				<?php
					$i=0;
					while($row = mysqli_fetch_array($result)) {
						if($i%2==0)
							$classname="evenRow";
						else
							$classname="oddRow";
				?>
				
				<tr class="<?php if(isset($classname)) echo $classname;?>">
				<td><input type="checkbox" name="timeslots[]" value="<?php echo $row["tid"]; ?>" ></td>
				<td><?php getDigits($row["dates"]);?></td>
				<td><?php echo TimeToAMPM(date('G:i', strtotime($row['start_time']))); ?></td>
				<td><?php echo TimeToAMPM(date('G:i', strtotime($row['end_time']))); ?></td>
				<td><?php echo $row["parent"]; ?></td>
				</tr>
				
				<?php
					$i++;
					}
				?>
				
				<tr class="listheader">
				<td colspan="5"><input type="submit" name="delete" value="Delete" /></td>
				</tr>
			</table>
		</form>
		</body>
	</div>
</html>

<?php
	if (isset($_POST["timeslots"]) ) 
	{
		$rowCount = count($_POST["timeslots"]);
		for($i=0;$i<$rowCount;$i++) {
			$query = "DELETE FROM `time_slot` WHERE tid='" . $_POST["timeslots"][$i] . "'";
			$result = mysqli_query($db,$query);
		}
		echo "<script type='text/javascript'>window.parent.location.reload()</script>";
	}

?>