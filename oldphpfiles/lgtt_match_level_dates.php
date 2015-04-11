<?php	
	require "db.php";
	require "func_lib.php";	

	// var_dump($_POST);
	// die();

	if(isset($_POST["level_start_choose"]) and isset($_POST["teacher_choose"]) and isset($_POST["timetable_choose"])
		and $_POST["level_start_choose"]!="" and $_POST["teacher_choose"]!="" and $_POST["timetable_choose"]!=""
	){
		$level_start = $_POST["level_start_choose"];
		$teacher = $_POST["teacher_choose"];
		$timetable = $_POST["timetable_choose"];
		
		$start = strtotime($level_start);
			
		if($timetable == "ПУ" or $timetable == "ПД" or $timetable == "ПВ"){
			$first_week_lesson=1;
			$second_week_lesson=3;
			$third_week_lesson=5;
		}	
		if($timetable == "ВУ" or $timetable == "ВД" or $timetable == "ВВ"){
			$first_week_lesson=2;
			$second_week_lesson=4;
			$third_week_lesson=6;
		}
		
		//	echo $second_week_lesson;
		//	echo (date("N",$start));
		if(date("N",$start)== $first_week_lesson or date("N",$start)== $second_week_lesson or date("N",$start)== $third_week_lesson){
		
		
			$sql = "SELECT * FROM `levels` WHERE `sd_1`='".$level_start."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."'";
			// echo $sql."<br />";
			$result = mysql_query($sql) or die(mysql_error());
			$arr_level_dates = mysql_fetch_row($result);
			//	var_dump($arr_level_dates);
			echo json_encode($arr_level_dates);
		}else{
			echo "Дата старта уровня не соответствует расписанию";
		}	
	}
	
	
	
?>