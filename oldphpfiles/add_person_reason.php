<?php
	require "db.php";
	require "func_lib.php";	
	header('Content-Type: text/html; charset=utf-8');
	// var_dump($_POST);
	// die();
	if(isset($_POST["teacher"]) and $_POST["teacher"]!="" and 
		isset($_POST["timetable"]) and $_POST["timetable"]!="" and
		isset($_POST["level_start"]) and $_POST["level_start"]!="" and
		isset($_POST["i"]) and $_POST["i"]!="" and
		isset($_POST["person"]) and $_POST["person"]!="" and
		isset($_POST["reason"]) and $_POST["reason"]!=""){

		$teacher=$_POST["teacher"];
		$timetable=$_POST["timetable"];
		$level_start=$_POST["level_start"];
		$i=$_POST["i"];
		$person=$_POST["person"];
		$reason=$_POST["reason"];

		$sql="SELECT `reason` FROM `discounts` WHERE `id_person`=".$_POST["person"]." AND `teacher`=".$_POST["teacher"]." AND `timetable`=".$_POST["timetable"]." AND `level_start`=".$_POST["level_start"];
		// echo $sql.PHP_EOL;
		// die();
		$result = mysql_query($sql) or die(mysql_error());
		if($row=mysql_fetch_row($result)){
			$sql="UPDATE `discounts` SET `reason`='".$reason."' WHERE `id_person`=".$_POST["person"]." AND `teacher`=".$_POST["teacher"]." AND `timetable`=".$_POST["timetable"]." AND `level_start`=".$_POST["level_start"];
			// echo $sql.PHP_EOL;
			// die();
			$result = mysql_query($sql) or die(mysql_error());
		}else{
			$sql="INSERT INTO `discounts` (`reason`,`teacher`,`timetable`,`level_start`,`id_person`) VALUE ('".$reason."',".$teacher.",".$timetable.",".$level_start.",".$person.")";
			// echo $sql.PHP_EOL;
			// die();
			$result = mysql_query($sql) or die(mysql_error());
		}
		echo json_encode($reason);
	}