<?php
	require "db.php";
	require "func_lib.php";	
	header('Content-Type: text/html; charset=utf-8');

	// var_dump($_POST);
	// echo "jjjjjj";

	if(isset($_POST["id_visit"]) and $_POST["id_visit"]!="" and isset($_POST["date_of_visit"]) and $_POST["date_of_visit"]!=""
		and isset($_POST["fio_visit"]) and $_POST["fio_visit"]!=""){
	
		$id_visit = $_POST["id_visit"];
		$date_of_visit = $_POST["date_of_visit"];
		$person_start = $_POST["person_start"];
		$fio_visit = $_POST["fio_visit"];
		
		//	/*
		if($date_of_visit < $person_start){ // echo $date_of_visit."|".$fio_visit."|".$id_visit."|55|480|3";
		}else{
			$sql = "SELECT `id` FROM `attendance` WHERE `date_of_visit` ='".$date_of_visit."' AND `id_visit`='".$id_visit."'";
			echo $sql."<br />";
			$result = mysql_query($sql)	or die(mysql_error());
			if ($row = mysql_fetch_row($result)){
				$uniq = 0;
				echo $date_of_visit."|".$fio_visit."|".$id_visit."|".$id."|".$row[1]."|".$uniq;
				
			}else{
				$sql = "INSERT INTO `attendance` (date_of_visit,id_visit) VALUES('".$date_of_visit."','".$id_visit."')";
				//echo $sql;
				$result = mysql_query($sql)	or die(mysql_error());
				$uniq = 1;
			
				$id = mysql_insert_id();
				$sql = "SELECT * FROM `attendance` WHERE `id`=".$id;
				$result2 = mysql_query($sql) or die(mysql_error());
				if($result && $result2){
					while ($row = mysql_fetch_row($result2)){
						echo $date_of_visit."|".$fio_visit."|".$id_visit."|".$id."|".$row[1]."|".$uniq;
					}
				}
			}
		}
		//	*/
	}
		
	
	if(isset($_POST["person_id"]) and $_POST["person_id"]!="" and isset($_POST["person_date"]) and $_POST["person_date"]!="" and 
		isset($_POST["teacher"]) and $_POST["teacher"]!="" and isset($_POST["timetable"]) and $_POST["timetable"]!="" and 
		isset($_POST["level_start"]) and $_POST["level_start"]!="" ){

		// echo "Yes";
			
		$person_id = $_POST["person_id"];
		$person_date = $_POST["person_date"];

		$teacher = $_POST["teacher"];
		$timetable = $_POST["timetable"];
		$level_start = $_POST["level_start"];
		
		$sql = "SELECT `person_start` FROM `levels_person` WHERE `id_person`='".$person_id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
		$result = mysql_query($sql)	or die(mysql_error());
		$row = mysql_fetch_row($result);
		$person_start = $row[0];

		// echo $person_date." - ".$person_start;
		
		if($person_date >= $person_start){
			$sql = "SELECT `id` FROM `attendance` WHERE `date_of_visit`='".$person_date."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_visit`='".$person_id."'";
			echo $sql.PHP_EOL;
			$result = mysql_query($sql)	or die(mysql_error());
			$row = mysql_fetch_row($result);
			// print_r($row);
			
			if ($row){
				$sql = "DELETE FROM `attendance` WHERE `id` = '".$row[0]."'";
				// echo $sql.PHP_EOL;
				$result = mysql_query($sql)	or die(mysql_error());
			}else{
				//echo "NO";
				$sql = "INSERT INTO `attendance` (date_of_visit,id_visit,teacher,timetable,level_start) VALUES('".$person_date."','".$person_id."','".$teacher."','".$timetable."','".$level_start."')";
				// echo $sql;
				$result = mysql_query($sql)	or die(mysql_error());
			}
		}
		/*
		$sql = "INSERT INTO `attendance` (date_of_visit,id_visit) VALUES('".$person_date."','".$person_id."')";
		$result = mysql_query($sql)	or die(mysql_error());
		*/
	}	
	
?>