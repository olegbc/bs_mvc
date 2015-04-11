<?php	
	require "db.php";

	$arr = array();	
	$n = 0;
	$status_combination = 0;
	$day_now = strtotime(date("d-m-Y"));

	if(isset($_POST["teacher"]) and $_POST["teacher"]!="" and 
		isset($_POST["timetable"]) and  $_POST["timetable"]!="" and 
		isset($_POST["level_start"]) and $_POST["level_start"]!=""){

		$teacher=$_POST["teacher"];
		$timetable=$_POST["timetable"];
		$level_start=$_POST["level_start"];

		$sql = "SELECT `sd_21` FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";;
		$result = mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_row($result);
		if(strtotime($level_start) < $day_now and strtotime($row[0]) < $day_now){$status_combination = -1;}
		if(strtotime($level_start) > $day_now and strtotime($row[0]) > $day_now){$status_combination = 1;}
		echo json_encode($status_combination);
	}