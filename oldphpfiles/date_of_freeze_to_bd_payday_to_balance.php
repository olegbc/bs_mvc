<?php
	require "db.php";
	header('Content-Type: text/html; charset=utf-8');

	// var_dump($_POST);
	
	if(isset($_POST["person"]) and $_POST["person"]!="" and 
		isset($_POST["date"]) and $_POST["date"]!="" and 
		isset($_POST["teacher"]) and $_POST["teacher"]!="" and 
		isset($_POST["timetable"]) and $_POST["timetable"]!="" and 
		isset($_POST["level_start"]) and $_POST["level_start"]!=""  and 
		isset($_POST["one_lesson"]) and $_POST["one_lesson"]!="" ){
			
		$person = $_POST["person"];
		$frozenDate = $_POST["frozenDate"];

		$teacher = $_POST["teacher"];
		$timetable = $_POST["timetable"];
		$level_start = $_POST["level_start"];
		$one_lesson= $_POST["one_lesson"];
		
		$sql = "SELECT `person_start` FROM `levels_person` WHERE `id_person`='".$person."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
		$result = mysql_query($sql)	or die(mysql_error());
		$row = mysql_fetch_row($result);
		$person_start = $row[0];
		// echo $date." - ".$person_start;
		if($frozenDate >= $person_start){
			$sql = "SELECT `id` FROM `freeze` WHERE `frozen_day`='".$frozenDate."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`='".$person."'";
			// echo $sql.PHP_EOL;
			$result = mysql_query($sql)	or die(mysql_error());
			$row = mysql_fetch_row($result);
			
			if (mysql_num_rows($result) == 0){
				$sql = "INSERT INTO `freeze` (frozen_day,id_person,teacher,timetable,level_start) VALUES('".$frozenDate."','".$person."','".$teacher."','".$timetable."','".$level_start."')";
				// echo $sql.PHP_EOL;
				$result = mysql_query($sql)	or die(mysql_error());
				$sql="UPDATE `payed_lessons` SET `num_reserved`=num_reserved-1 WHERE `id_person`='".$person."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
				$result = mysql_query($sql)	or die(mysql_error());
				$sql="UPDATE `payed_lessons` SET `num_payed`=num_payed-1 WHERE `id_person`='".$person."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
				$result = mysql_query($sql)	or die(mysql_error());
				$sql="UPDATE `balance` SET `balance`=balance+".$one_lesson." WHERE `id_person`='".$person."'";
				$result = mysql_query($sql)	or die(mysql_error());
			}else{
				// echo $row[0].PHP_EOL;
				$sql = "DELETE FROM `freeze` WHERE `id` = '".$row[0]."'";
				// echo $sql.PHP_EOL;
				$result = mysql_query($sql)	or die(mysql_error());
				$sql="UPDATE `payed_lessons` SET `num_reserved`=num_reserved+1 WHERE `id_person`='".$person."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
				// echo $sql.PHP_EOL;
				$result = mysql_query($sql)	or die(mysql_error());
			}
		}
	}