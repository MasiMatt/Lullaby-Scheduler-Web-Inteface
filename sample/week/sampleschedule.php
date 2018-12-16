<link rel=stylesheet type=text/css href=schedule.css?v=4 />
<body class='schedule'>
<?php 
   session_start();
   include('scheduleMaker.php');
   $sm = new ScheduleMaker(770, 700, 0, 0, 5);
   $sm->setBeginEndTime("0:00", "24:00");

   $selSched = isset($_POST['selSched']) ? $_POST['selSched'] : '';
   $_SESSION['sched'] = $selSched;
   
   include '../dbConfig.php';
   $query = "SELECT * FROM `time_slot` WHERE schedule_num = '$selSched'";
   $result = mysqli_query($db,$query);
   $numRows =$result->num_rows;
   
   if($result){
	   while($row = mysqli_fetch_array($result))
	   {
			$event = new Event($row['parent'], $row['dates'], date('G:i', strtotime($row['start_time'])), date('G:i', strtotime($row['end_time'])));
			$sm->addEvent($event);
	   }
   }	

   if (isset($_POST['btnSubmit'])) 
   {
	   if($selSched == 1 || $selSched == 2 || $selSched == 3)
	   {
			$query = "UPDATE `time_slot` SET active = 0";
			$result = mysqli_query($db,$query);
			$query = "UPDATE `time_slot` SET active = 1 WHERE schedule_num = '$selSched'";
			$result = mysqli_query($db,$query);
			header("Location: ../control.php");
	   }
   }

   $sm->printHTML(); 
?>
</body>
<script src="jquery1.js"></script>
<script src="jquery2.js"></script>

<script>
$(function () {
    $("input[type='checkbox']").change(function () {
        switch (this.id) {
            case 'input':
                if ($(this).is(':checked')) {
                    $(".input").show();
                } else {
                    $(".input").hide();
                }
                break;
        };
    });
});
</script>

<script>
$(function () {
    $("input[type='checkbox']").change(function () {
        switch (this.id) {
            case 'del':
                if ($(this).is(':checked')) {
                    $(".del").show();
                } else {
                    $(".del").hide();
                }
                break;
        };
    });
});
</script>

<style>
  body {
        font-size:20px;
    }
    .input {
        position: absolute;
        display: none;
    }
</style>

<style>
  body {
        font-size:20px;
    }
    .del {
        position: absolute;
        display: none;
    }
</style>

<style>
iframe{
    position:absolute;
    z-index: 9999;
}
</style>

<input type="checkbox" id="input" name="input" />
    <label for="input"><span></span>Add Time Slot</label>
    <div class="input">
        <iframe width="365" height="460" src="inputForm.php"></iframe>
    </div>
	
<input type="checkbox" id="del" name="del" />
    <label for="del"><span></span>Delete Time Slot</label>
    <div class="del">
        <iframe width="365" height="<?php echo ($numRows*24)+72 ?> " src="deleteForm.php"></iframe>
    </div>
<br>
<form action="sampleschedule.php" method="post">
<input type="submit" value="Set As Active" name ="btnSubmit" onclick="saveAlert()">
<select name="selSched" onchange="this.form.submit();" >
  <option>Select Schedule</option>
  <option <?php if ($selSched == "1") echo 'selected' ; ?> value="1">Schedule 1</option>
  <option <?php if ($selSched == "2") echo 'selected' ; ?> value="2">Schedule 2</option>
  <option <?php if ($selSched == "3") echo 'selected' ; ?> value="3">Schedule 3</option>
</select>
</form>

<script>
function saveAlert() {
	var sch = "<?php echo $selSched ?>";
	if(sch == 1 || sch == 2 || sch == 3)
	{
		alert("Schedule " + sch + " is active schedule!");
		
	}
	else
		alert("You must select a schedule first!");
}
</script>

</html>