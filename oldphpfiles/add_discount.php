<?php
	require "db.php";
	header('Content-Type: text/html; charset=utf-8');

	if(isset($_POST["teacher"]) and $_POST["teacher"]!="" and 
		isset($_POST["timetable"]) and $_POST["timetable"]!="" and
		isset($_POST["level_start"]) and $_POST["level_start"]!="" and
		isset($_POST["i"]) and $_POST["i"]!="" and
		isset($_POST["discount_value"]) and $_POST["discount_value"]!="" and
		isset($_POST["person"]) and $_POST["person"]!=""){

		$teacher=$_POST["teacher"];
		$timetable=$_POST["timetable"];
		$level_start=$_POST["level_start"];
		$i=$_POST["i"];
		$discount_value=$_POST["discount_value"];
		$person=$_POST["person"];

		$sql="SELECT `discount` FROM `discounts` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`='".$person."'";
		$result = mysql_query($sql)	or die(mysql_error());
		if($row = mysql_fetch_row($result)){
			$sql = "UPDATE `discounts` SET `discount`=".$discount_value." WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`='".$person."'";
			$result = mysql_query($sql)	or die(mysql_error());
		}else{
			$sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`timetable`,`level_start`,`discount`) VALUE ('".$person."','".$teacher."','".$timetable."','".$level_start."','".$discount_value."')";
			// echo $sql.PHP_EOL;
			$result = mysql_query($sql)	or die(mysql_error());
		}
		// echo $sql.PHP_EOL;


		// if($row){
		// 	if ($row){
		// 		$sql = "UPDATE `discounts` FROM `discounts` WHERE `id` = '".$row[0]."'";
		// 		// echo $sql.PHP_EOL;
		// 		$result = mysql_query($sql)	or die(mysql_error());
		// 	}else{
		// 		//echo "NO";
		// 		$sql = "INSERT INTO `discounts` (discount,teacher,timetable,level_start) VALUES('".$person_date."','".$teacher."','".$timetable."','".$level_start."')";
		// 		// echo $sql;
		// 		$result = mysql_query($sql)	or die(mysql_error());
		// 	}
		// }
	}