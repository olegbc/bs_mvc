<?php
	require "db.php";
	// var_dump($_POST);
	// die();
	if(isset($_POST["id"]) and $_POST["id"]!="" and 
		isset($_POST["teacher"]) and $_POST["teacher"]!="" and 
		isset($_POST["timetable"]) and $_POST["timetable"]!="" and 
		isset($_POST["level_start"]) and $_POST["level_start"]!=""){
		$person = $_POST["id"];	
		$teacher = $_POST["teacher"];
		$timetable = $_POST["timetable"];
		$level_start = $_POST["level_start"];

		$sql = "DELETE FROM `levels_person` WHERE `id_person`=".$person." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
		echo $sql;
		$result = mysql_query($sql)	or die(mysql_error());

		$sql = "SELECT `num_payed` FROM `payed_lessons` WHERE `id_person`=".$person." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
		$result = mysql_query($sql)	or die(mysql_error());
		$row=mysql_fetch_array($result);
		$num=$row[0];

		$sql = "SELECT `discount` FROM `discounts` WHERE `id_person` ='".$person."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND 
			`timetable` ='".$timetable."'";
		$result = mysql_query($sql)	or die(mysql_error());
		$row = mysql_fetch_row($result);
		$discount = $row[0];

		$sql = "SELECT `one lesson default` FROM `constants`" ;
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_row($result);
		$one_lesson_default = $row[0];
		$one_lesson =$one_lesson_default - round(($one_lesson_default*($discount*0.01)),2);

		$back_to_balance=$num*$one_lesson;

		$sql="UPDATE `balance` SET `balance`= balance +".$back_to_balance." WHERE `id_person`=".$person;
		$result=mysql_query($sql) or die(mysql_error());

		$sql = "DELETE FROM `payed_lessons` WHERE `id_person`=".$person." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
		$result = mysql_query($sql)	or die(mysql_error());
	}