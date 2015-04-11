<?php
	require "db.php";
	require "func_lib.php";	
	header('Content-Type: text/html; charset=utf-8');
	// var_dump($_POST);

	if(isset($_POST["teacher"]) and $_POST["teacher"]!="" and 
		isset($_POST["timetable"]) and $_POST["timetable"]!="" and
		isset($_POST["level_start"]) and $_POST["level_start"]!="" and
		isset($_POST["id_person"]) and $_POST["id_person"]!=""){

		$teacher = $_POST["teacher"];
		$timetable = $_POST["timetable"];
		$level_start = $_POST["level_start"];
		$person = $_POST["id_person"];

		$arr = array();

		$sql="SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='".$person."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND 
			`timetable` ='".$timetable."'";
		$result=mysql_query($sql) or die(mysql_error());
		$arr[0]=mysql_fetch_row($result);

		
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
		$arr[1]=$one_lesson;

		echo json_encode($arr);
	}