<?php
	require "db.php";
	if(isset($_POST['teacher']) and $_POST['teacher']!="" and 
		isset($_POST['timetable']) and $_POST['timetable']!="" and 
		isset($_POST['level_start']) and $_POST['level_start']!="" and 
		isset($_POST['status']) and $_POST['status']!=""){
		$sql = "UPDATE `levels` SET `status`='".$_POST['status']."' WHERE `teacher`=".$_POST['teacher']." AND `timetable`=".$_POST['timetable']." AND `sd_1`=".$_POST['level_start'];
		$result = mysql_query($sql) or die(mysql_error());
	}
