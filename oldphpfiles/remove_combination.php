<?php
	require "db.php";
	// var_dump($_POST);

	if(isset($_POST['teacher']) and $_POST['teacher']!="" and 
		isset($_POST['timetable']) and $_POST['timetable']!="" and 
		isset($_POST['level_start']) and $_POST['level_start']!=""){
		$teacher=$_POST['teacher'];
		$timetable=$_POST['timetable'];
		$level_start=$_POST['level_start'];

		$sql = "DELETE FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";
		// echo $sql;
		$result = mysql_query($sql)	or die(mysql_error());

	}