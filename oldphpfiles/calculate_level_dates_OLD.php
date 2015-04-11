<?php	
	require "db.php";
	require "func_lib.php";
	header('Content-Type: text/html; charset=utf-8');	
	
	$arr_dates = array();
	$arr_bad_days = array();
	
	// var_dump($_POST);
	
	$t=0;
	$num=0;
	$all_queries_num = 21;
	$repeats = 1120;
	if(isset($_POST["level_start_date"]) and $_POST["level_start_date"]!="" and
		isset($_POST["level_choose"]) and $_POST["level_choose"]!="" and
		isset($_POST["timetable_choose"]) and $_POST["timetable_choose"]!="" and
		isset($_POST["teacher_choose"]) and $_POST["teacher_choose"]!=""){
		
		$level_start_date_choose = $_POST["level_start_date"];
		$level_choose = $_POST["level_choose"];
		$timetable_choose = $_POST["timetable_choose"];
		$teacher_choose = $_POST["teacher_choose"];
		
		$start = strtotime($level_start_date_choose);
		
			
		if($timetable_choose == "ПУ" or $timetable_choose == "ПД" or $timetable_choose == "ПВ"){
			$first_week_lesson=1;
			$second_week_lesson=3;
			$third_week_lesson=5;
		}	
		if($timetable_choose == "ВУ" or $timetable_choose == "ВД" or $timetable_choose == "ВВ"){
			$first_week_lesson=2;
			$second_week_lesson=4;
			$third_week_lesson=6;
		}
		
		//	echo $second_week_lesson;
		//	echo (date("N",$start));
		if(date("N",$start)== $first_week_lesson or date("N",$start)== $second_week_lesson or date("N",$start)== $third_week_lesson){
			
			// существует ли уже такой уровень? если да, то какой у него айди, если нет то создай новый уровень и скажи его айди
			$sql = "SELECT `teacher`,`timetable`,`sd_1` FROM `levels`";
			$result2 = mysql_query($sql) or die(mysql_error());
			$exist = 0;
			// while($row = mysql_fetch_row($result2)){
			// 	if($teacher_choose == $row[0] and $timetable_choose == $row[1] and $level_start_date_choose == $row[2]){
			// 		$exist = 1;
			// 		$sql = "SELECT `id` FROM `levels` WHERE `teacher`='".$teacher_choose."' AND `timetable`='".$timetable_choose."' AND `sd_1`='".$level_start_date_choose."'";
			// 		$result = mysql_query($sql) or  die(mysql_error());
			// 		$row = mysql_fetch_row($result);
			// 		$id =  $row[0];	
			// 	}
			// }

			$sql = "SELECT `id` FROM `levels` WHERE `teacher`='".$teacher_choose."' AND `timetable`='".$timetable_choose."' AND `sd_1`='".$level_start_date_choose."'";
			$result = mysql_query($sql) or  die(mysql_error());
			$row=mysql_fetch_row($result);
			if(mysql_num_rows($result)){
				$exist = 1;
				$id = $row[0];	
			}

			if($exist == 0){
				$sql = "INSERT INTO `levels` (`level`,`teacher`,`timetable`,`sd_1`) VALUES(".$level_choose.",'".$teacher_choose."','".$timetable_choose."','".$level_start_date_choose."')";
				$result = mysql_query($sql) or  die(mysql_error());
				$id =  mysql_insert_id();
				echo "Новый уровень установлен";
			}	
			
			// расчет неугодных дней
			if(isset($_POST["bad_day_choose"]) and $_POST["bad_day_choose"]!=""){
				// 	есть ли уже такой бэд дэй для данного уровня(сочетания)
				//echo $id;
				$exist_bad_day =0;
				$sql = "SELECT * FROM `bad_days` WHERE `id_level`=".$id." AND `bad_day`='".$_POST["bad_day_choose"]."'";
				$result = mysql_query($sql) or die(mysql_error());
				$row=mysql_fetch_row($result);
				if($row){
					$exist_bad_day = 1;
				}
				//внос нового неугодного дня
				if($exist_bad_day != 1){
					$sql = "INSERT INTO `bad_days` (`bad_day`,`id_level`) VALUE ('".$_POST["bad_day_choose"]."',".$id.")";
					$result = mysql_query($sql) or die(mysql_error());
				}
				// формирование массива
				$sql = "SELECT `bad_day` FROM `bad_days` WHERE `id_level`=".$id;
				$result = mysql_query($sql) or die(mysql_error());
				$p = 0;
				while($row=mysql_fetch_row($result)){
					$arr_bad_days[$p] = $row[0];
					$p++;
				}
				//	var_dump($arr_bad_days);
			}
			
			// создание массива дат уроков с учетом неугодных дней
			while($repeats-- and $all_queries_num >0){
				$denied = 0;
				$day_of_week = date("N",$start + (86400*$t));
				for($i=0;$i<count($arr_bad_days);$i++){
					//	echo date("Y-m-d",($start + (86400*$t))).PHP_EOL;
					//	echo $arr_bad_days[$i].PHP_EOL;
					if(date("Y-m-d",($start + (86400*$t))) == $arr_bad_days[$i]){$denied = 1;}
					//	echo $denied.PHP_EOL.PHP_EOL;
				}
					//	echo $denied.PHP_EOL;
					//	echo $day_of_week." - ".$third_week_lesson.PHP_EOL;
				if($day_of_week == $first_week_lesson or $day_of_week == $second_week_lesson or $day_of_week == $third_week_lesson){
					//	echo $denied.PHP_EOL;
					if($denied==0){
							//	echo date("Y-m-d",$start + (86400*$t)).PHP_EOL;
						$arr_dates[$num] = $start + (86400*$t);
						$arr_dates_day[$num] = date("Y-m-d",$start + (86400*$t));
						$all_queries_num--;
						$num++;
					}
				}
				$t++;	
			}
				//	var_dump($arr_dates_day);
			// заполнение дат уроков
			for($e=0;$e<count($arr_dates);$e++){
				$sql="UPDATE `levels` SET `sd_".($e+1)."`='".date("Y-m-d",$arr_dates[$e])."' WHERE `id`=".$id;
				$result = mysql_query($sql) or die(mysql_error());
			}
		}else{
			echo "Дата старта уровня не соответствует расписанию";
		}
	}
 ?>