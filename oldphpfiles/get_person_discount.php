<?php
	require "db.php";
	require "func_lib.php";
	// var_dump($_POST);
	$data = array(); 
	if(isset($_POST["person"]) and $_POST["person"]!="" and 
		isset($_POST["teacher"]) and $_POST["teacher"]!="" and 
		isset($_POST["timetable"]) and $_POST["timetable"]!="" and 
		isset($_POST["level_start"]) and $_POST["level_start"]!=""){
		// die();
		$sql="SELECT `discount`,`reason` FROM `discounts` WHERE `id_person`=".$_POST["person"]." AND `teacher`=".$_POST["teacher"]." AND `timetable`=".$_POST["timetable"]." AND `level_start`=".$_POST["level_start"];
		// echo $sql.PHP_EOL;
		// die();
		$result = mysql_query($sql) or die(mysql_error());
		// $i=0;
		// while($row=mysql_fetch_row($result)){
			// $data[0]=$row[0];
			// echo $row;
			// $data[1]=$row[1];
			// $i++;
		// }
		// echo "ooooo";
		echo json_encode($row=mysql_fetch_row($result));
	}	