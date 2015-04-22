<?php	
	require "db.php";
	require "func_lib.php";	
	$arr = Array();
	 
	if(isset($_POST["id"]) and $_POST["id"]!=""){
		$id = $_POST["id"];	
		$sql = "SELECT `fio` FROM `main` WHERE `id`=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
		while ($row = mysql_fetch_row($result)){
			// echo $row[0];
			$arr[0] = $row[0];
		}	
		$sql = "SELECT `teacher`,`timetable`,`level_start`,`level` FROM `levels_person` WHERE `id_person`=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
		$i=0;
		while ($row = mysql_fetch_row($result)){
			// echo $row[0];
			$arr[$i+1][0] = $row[0];
			$arr[$i+1][1] = $row[1];
			$arr[$i+1][2] = $row[2];
			$arr[$i+1][3] = $row[3];
			$i++;
		}
		// echo $arr[0]."|".$arr[1]."|".$arr[2]."|".$arr[3]."|".$arr[4];
		echo json_encode($arr);
		
	}