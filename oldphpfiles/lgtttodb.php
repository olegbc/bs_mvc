<?php
	require "db.php";

	header('Content-Type: text/html; charset=utf-8');
	
	// var_dump($_POST);

	if(isset($_POST["id_person"]) and $_POST["id_person"]!="" and 
		isset($_POST["fio_person"]) and $_POST["fio_person"]!="" and 
		isset($_POST["level_soch"]) and $_POST["level_soch"]!="" and
		isset($_POST["teacher"]) and $_POST["teacher"]!="" and
		isset($_POST["timetable_sel"]) and $_POST["timetable_sel"]!="" and
		isset($_POST["level_start_sel"]) and $_POST["level_start_sel"]!="" and
		isset($_POST["person_start_sel"]) and $_POST["person_start_sel"]!="" and
		isset($_POST["person_stop_sel"]) and $_POST["person_stop_sel"]!="" ){

		$level = $_POST["level_soch"];
		$teacher = $_POST["teacher"];
		$timetable = $_POST["timetable_sel"];
		$level_start = $_POST["level_start_sel"];
		$person_start = $_POST["person_start_sel"];
		$person_stop = $_POST["person_stop_sel"];
		$id_person = $_POST["id_person"];
		$fio_person = $_POST["fio_person"];

		$sql = "SELECT `id` FROM `levels_person` WHERE `id_person`='".$id_person."'
		 AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows ($result) == 0){
			$sql = "INSERT INTO `levels_person` (level,id_person,teacher,timetable,level_start,person_start,person_stop) 
				VALUES('".$level."','".$id_person."','".$teacher."','".$timetable."','".$level_start."','".$person_start."','".$person_stop."')";
			// echo $sql.PHP_EOL;
			mysql_query($sql) or die(mysql_error());
			$id = mysql_insert_id();
		}else{
			$sql="UPDATE `levels_person` SET `person_start`='".$person_start."',`person_stop`='".$person_stop."' WHERE `id_person`='".$id_person."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
		$result = mysql_query($sql) or die(mysql_error());
			mysql_query($sql) or die(mysql_error());
			$id = mysql_insert_id();
		}

		$sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
		$result = mysql_query($sql) or die(mysql_error());
		$rows_exist = mysql_num_rows ($result);
		// echo $rows_exist.PHP_EOL;die();
		if(mysql_num_rows ($result) != 0){
			$person_start_on_sochitanie=0;
			$person_stop_on_sochitanie=0;
			while($row = mysql_fetch_row($result)){
				for($u=0;$u<21;$u++){
					if($row[$u] == $person_start){$person_start_on_sochitanie=$u;};
					if($row[$u] == $person_stop){$person_stop_on_sochitanie=$u;};
				}
			}
			$num_lessons_person_on_sochitanie = (abs($person_stop_on_sochitanie - $person_start_on_sochitanie))+1;
		}
		// echo $num_lessons_person_on_sochitanie.PHP_EOL;die();

		$sql = "SELECT `id` FROM `payed_lessons` WHERE `id_person`='".$id_person."'
		 AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows ($result) == 0){
			$sql = "INSERT INTO `payed_lessons` (id_person,num_reserved,teacher,timetable,level_start) 
				VALUES('".$id_person."','".$num_lessons_person_on_sochitanie."','".$teacher."','".$timetable."','".$level_start."')";
			// echo $sql;die();
			mysql_query($sql) or die(mysql_error());
		}else{
			$sql = "UPDATE `payed_lessons` SET `num_reserved`='".$num_lessons_person_on_sochitanie."' 
				WHERE `id_person`='".$id_person."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
			// echo $sql;die();
			mysql_query($sql) or die(mysql_error());
		}
			
		$sql = "SELECT * FROM `levels_person` WHERE `id`=".$id;
		$result = mysql_query($sql) or die(mysql_error());
		if($result){
			while ($row = mysql_fetch_row($result)){
				echo $level."|".$fio_person."|".$id_person."|".$id."|".$row[1]."|".$row[2]."|".$row[3]."|".$row[4]."|".$row[5];
			}
		}  
	}	
	
?>