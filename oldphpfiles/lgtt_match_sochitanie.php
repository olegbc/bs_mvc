<?php	
	require "db.php";
	require "func_lib.php";	
	
		// var_dump($_POST);

	if(isset($_POST["person"]) and isset($_POST["person"]) and 
		isset($_POST["level_start_choose"]) and 
		isset($_POST["teacher_choose"]) and 
		isset($_POST["timetable_choose"]) and 
		$_POST["level_start_choose"]!="" and 
		$_POST["teacher_choose"]!="" and 
		$_POST["timetable_choose"]!=""){

		$level_start = $_POST["level_start_choose"];	
		$teacher = $_POST["teacher_choose"];	
		$timetable = $_POST["timetable_choose"];	
		$person = $_POST["person"];
		// ARRs
		$arr_id = array();
		$fio = "";
		$arr_date = array();
		$arr_fio_id_date = array();
		$arr_person_start = array();
		$arr_person_stop = array();

		$arr_pay = array();
		$arr_new_arj = array();
		$arr_all = array();
		$discount = 0;
		$arr_lessons_num = array();
		$freeze_dates_arr = array();//массив дат заморозки
		$arr_one_lesson = array(); // массив стоимость одного урока на сочетании
		$arr_sochitanie = array(); // массив сочитаний

			// LEVELS_PERSON
		// $sql="SELECT `teacher`,`timetable`,`level_start` FROM `levels_person` WHERE `id_person`='".$person."'";
		// $result = mysql_query($sql)	or die(mysql_error());
		// $i=0;
		// while($row = mysql_fetch_row($result)){
		// 	$arr_sochitanie[$i]['teacher']=$row[0];
		// 	$arr_sochitanie[$i]['timetable']=$row[1];
		// 	$arr_sochitanie[$i]['level_start']=$row[2];
		// 	$i++;
		// }
			// /LEVELS_PERSON

				//------  $arr_id
		// for($i=0;$i<count($arr_sochitanie);$i++){
			$sql = "SELECT DISTINCT `person_start`,`person_stop` FROM `levels_person` 
					WHERE id_person='".$person."' AND  teacher='".$teacher."' AND levels_person.level_start='".$level_start."' AND levels_person.timetable='".$timetable."'";
			// echo $sql.PHP_EOL;
			$result = mysql_query($sql)	or die(mysql_error());
			while ($row = mysql_fetch_row($result)){
				$arr_person_start = $row[0];
				$arr_person_stop = $row[1];
			}
		// }
			//------  /$arr_id

			// DISCOUNTS
		// for ($i=0; $i <count($arr_sochitanie); $i++) { 
			$sql = "SELECT `discount` FROM `discounts` WHERE `id_person` ='".$person."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND 
			`timetable` ='".$timetable."'";
			// echo $sql;
			$result = mysql_query($sql)	or die(mysql_error());
			$row = mysql_fetch_row($result);
			$discount = $row[0];
			// /DISCOUNTS
		// }

			// ONE LESSON
		$sql = "SELECT `one lesson default` FROM `constants`" ;
			$result = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_row($result);
		// for($i=0;$i<count($arr_sochitanie);$i++){
				$one_lesson_default = $row[0];
				$one_lesson =$one_lesson_default - round(($one_lesson_default*($discount*0.01)),2);
				// $arr_one_lesson[$i] = $one_lesson;
		// }
			// /ONE LESSON
		
		//--------  arr_date
		// for($ty=0;$ty<count($arr_sochitanie); $ty++){
			$sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON 
					levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='".$teacher."' 
					AND levels_person.level_start='".$level_start."' AND 
					levels_person.timetable='".$timetable."' AND `id_person`=".$person;
			$result = mysql_query($sql)	or die(mysql_error());
			$er=0;
			while ($row = mysql_fetch_row($result)){
				$arr_date[$er] = $row[0];
				$er++;			
			}
		// }

		//---- fio
		$sql = "SELECT `fio` FROM `main` WHERE `id`='".$person."'";
		$result = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_row($result);				
		$fio = $row[0];
		//---- /fio
		
		// for($froz=0;$froz<count($arr_sochitanie);$froz++){
			$sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON 
						levels_person.id_person = freeze.id_person AND 
						levels_person.teacher = freeze.teacher AND 
						levels_person.timetable = freeze.timetable AND 	
						levels_person.level_start = freeze.level_start 
					WHERE levels_person.teacher='".$teacher."' AND 
							levels_person.level_start='".$level_start."' AND 
							levels_person.timetable='".$timetable."' AND 
							freeze.id_person=".$person;
			$result = mysql_query($sql)	or die(mysql_error());
			$er=0;
			while ($row = mysql_fetch_row($result)){
				$freeze_dates_arr[$er] = $row[0];
				$er++;			
			}
		// }

		// for ($i=0; $i < count($arr_sochitanie); $i++) { 
			$arr_all[$fio."|".$person."|".$arr_person_start."|".$arr_person_stop]['dates'] = $arr_date;
			if($freeze_dates_arr != null){
				$arr_all[$fio."|".$person."|".$arr_person_start."|".$arr_person_stop]['freeze'] = $freeze_dates_arr;
			}
			$arr_all[$fio."|".$person."|".$arr_person_start."|".$arr_person_stop]['one_lesson'] = $one_lesson;
			// $arr_all[$arr_fio[$i]."|".$arr_id[$i]."|".$arr_person_start[$i]."|".$arr_person_stop[$i]]['pays'] = $arr_pay[$i];
			// $arr_all[$arr_fio[$i]."|".$arr_id[$i]."|".$arr_person_start[$i]."|".$arr_person_stop[$i]]['lessons_num'] = $arr_lessons_num[$i];
		// }

		ksort($arr_all);
		echo json_encode($arr_all);

	} 
?>