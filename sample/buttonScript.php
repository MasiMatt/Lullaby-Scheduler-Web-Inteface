<?php
	if($selSched == 1 || $selSched == 2 || $selSched == 3)
    {
		$query = "UPDATE `time_slot` SET active = 0";
		$result = mysqli_query($db,$query);
		$query = "UPDATE `time_slot` SET active = 1 WHERE schedule_num = '$selSched'";
		$result = mysqli_query($db,$query);
	}
?>