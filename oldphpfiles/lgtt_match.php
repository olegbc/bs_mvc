<?php	
	require "db.php";
	
	// var_dump($_POST);

	if(isset($_POST["level_start_choose"]) and isset($_POST["teacher_choose"]) and isset($_POST["timetable_choose"])
		and $_POST["level_start_choose"]!="" and $_POST["teacher_choose"]!="" and $_POST["timetable_choose"]!=""
	)
    {

		$level_start = $_POST["level_start_choose"];	
		$teacher = $_POST["teacher_choose"];	
		$timetable = $_POST["timetable_choose"];	
		
		$arr_id = array();
		$arr_fio = array();
		$arr_date = array();
		$arr_fio_id_date = array();
		$arr_person_start = array();
		$arr_person_stop = array();
		$arr_num_payed = array();
		$arr_num_reserved = array();

		$arr_pay = array();
		$arr_new_arj = array();
		$arr_all = array();
		$arr_discounts = array();
		$arr_lessons_num = array();
		$freeze_dates_arr = array();//массив дат заморозки
		$arr_one_lesson = array(); // массив стоимость одного урока на сочетании
		//------  $arr_id
		$op=0;
		$sql = "SELECT DISTINCT `id_person`,`person_start`,`person_stop` FROM `levels_person` 
				WHERE levels_person.teacher='".$teacher."' AND levels_person.level_start='".$level_start."' AND levels_person.timetable='".$timetable."'";
		// echo $sql.PHP_EOL;
		$result = mysql_query($sql)	or die(mysql_error());
		while ($row = mysql_fetch_row($result)){
			$arr_id[$op] = $row[0];
			$arr_person_start[$op] = $row[1];
			$arr_person_stop[$op] = $row[2];
			$op++;
		}

			// DISCOUNTS
		for ($i=0; $i <count($arr_id); $i++) { 
			// die();
			$sql = "SELECT `discount` FROM `discounts` WHERE `id_person` ='".$arr_id[$i]."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND 
			`timetable` ='".$timetable."'";
			// echo $sql;
			$result = mysql_query($sql)	or die(mysql_error());
			$row = mysql_fetch_row($result);
			$arr_discounts[$i] = $row[0];
			// /DISCOUNTS
			// ONE LESSONS
		$sql = "SELECT `one lesson default` FROM `constants`" ;
			$result = mysql_query($sql) or die(mysql_error());
			while($row = mysql_fetch_row($result)){
				$one_lesson_default = $row[0];
				$arr_one_lesson[$i] = $one_lesson_default;
			}
		}
		
		//--------  arr_date
		for($ty=0;$ty<count($arr_id); $ty++){
			$sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON 
					levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='".$teacher."' 
					AND levels_person.level_start='".$level_start."' AND 
					levels_person.timetable='".$timetable."' AND `id_person`=".$arr_id[$ty];
			//	echo $sql;
			$result = mysql_query($sql)	or die(mysql_error());
			$er=0;
			while ($row = mysql_fetch_row($result)){
				$arr_date[$ty][$er] = $row[0];
				$er++;			
			}
		}
		// print_r($arr_date);
		// die();
		//---- arr_fio
		for($ff=0;$ff<count($arr_id);$ff++){
			$sql = "SELECT `fio` FROM `main` WHERE `id`=".$arr_id[$ff];
			$result = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_row($result);				
			$arr_fio[$ff] = $row[0];
		}

		// print_r($arr_id);
		
		// echo $i.PHP_EOL;
		for($froz=0;$froz<count($arr_id);$froz++){
			$sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON 
						levels_person.id_person = freeze.id_person AND 
						levels_person.teacher = freeze.teacher AND 
						levels_person.timetable = freeze.timetable AND 	
						levels_person.level_start = freeze.level_start 
					WHERE levels_person.teacher='".$teacher."' AND 
							levels_person.level_start='".$level_start."' AND 
							levels_person.timetable='".$timetable."' AND 
							freeze.id_person=".$arr_id[$froz];
			// echo "i= ".$i.PHP_EOL;
			// echo $sql.PHP_EOL;
			$result = mysql_query($sql)	or die(mysql_error());
			$er=0;
			while ($row = mysql_fetch_row($result)){
				$freeze_dates_arr[$froz][$er] = $row[0];
				$er++;			
			}
		}

		//--------  arr_num_payed arr_num_reserved
		for($ty=0;$ty<count($arr_id); $ty++){
			$sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `teacher`='".$teacher."' 
					AND `level_start`='".$level_start."' AND 
					`timetable`='".$timetable."' AND `id_person`=".$arr_id[$ty];
			// echo $sql;
			$result = mysql_query($sql)	or die(mysql_error());
			$er=0;
			while ($row = mysql_fetch_row($result)){
				$arr_num_payed[$ty][$er] = $row[0];
				$arr_num_reserved[$ty][$er] = $row[1];
				$er++;
			}
		}


		$sql = "SELECT `status` FROM `levels` WHERE `teacher`='".$teacher."' 
				AND `sd_1`='".$level_start."' AND 
				`timetable`='".$timetable."'";
		// echo $sql;
		// die();
		$result = mysql_query($sql)	or die(mysql_error());
		$row = mysql_fetch_row($result);
		$status = $row[0];

		// print_r($freeze_dates_arr);

		for ($i=0; $i < count($arr_id); $i++) { 
			$arr_all[$arr_fio[$i]."|".$arr_id[$i]."|".$arr_person_start[$i]."|".$arr_person_stop[$i]]['dates'] = $arr_date[$i];
			$arr_all[$arr_fio[$i]."|".$arr_id[$i]."|".$arr_person_start[$i]."|".$arr_person_stop[$i]]['one_lesson'][$i] = $arr_one_lesson[$i];

			if($arr_num_payed != null){
				$arr_all[$arr_fio[$i]."|".$arr_id[$i]."|".$arr_person_start[$i]."|".$arr_person_stop[$i]]['num_payed'] = $arr_num_payed[$i];
			}
			if($arr_num_reserved != null){
				$arr_all[$arr_fio[$i]."|".$arr_id[$i]."|".$arr_person_start[$i]."|".$arr_person_stop[$i]]['num_reserved'] = $arr_num_reserved[$i];
			}
			$arr_all[$arr_fio[$i]."|".$arr_id[$i]."|".$arr_person_start[$i]."|".$arr_person_stop[$i]]['status'] = $status;
		}

		ksort($arr_all);
		echo json_encode($arr_all);

	}
	//*/
?>