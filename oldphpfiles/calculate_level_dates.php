<?php
	require "db.php";
	header('Content-Type: text/html; charset=utf-8');	
	
	$arr_dates = array();
	$arr_bad_days = array();
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
		
		if(date("N",$start)== $first_week_lesson or date("N",$start)== $second_week_lesson or date("N",$start)== $third_week_lesson){

			$sql = "SELECT `id` FROM `levels` WHERE `teacher`='".$teacher_choose."' AND `timetable`='".$timetable_choose."' AND `sd_1`='".$level_start_date_choose."'";
			$result = mysql_query($sql) or  die(mysql_error());
			if(mysql_num_rows($result)==0){
				$sql = "INSERT INTO `levels` (`level`,`teacher`,`timetable`,`sd_1`) VALUES(".$level_choose.",'".$teacher_choose."','".$timetable_choose."','".$level_start_date_choose."')";
				$result = mysql_query($sql) or die(mysql_error());
			}	
			
			$sql = "SELECT `bad_day` FROM `bad_days` WHERE `teacher`='".$teacher_choose."' AND `timetable`='".$timetable_choose."' AND `level_start`='".$level_start_date_choose."'";
			$result = mysql_query($sql) or die(mysql_error());
			$p = 0;
			while($row=mysql_fetch_row($result)){
				$arr_bad_days[$p] = $row[0];
				$p++;
			}

			while($repeats-- and $all_queries_num >0){
				$denied = 0;
				$day_of_week = date("N",$start + (86400*$t));
				for($i=0;$i<count($arr_bad_days);$i++){
					if(date("Y-m-d",($start + (86400*$t))) == $arr_bad_days[$i]){$denied = 1;}
				}
				if($day_of_week == $first_week_lesson or $day_of_week == $second_week_lesson or $day_of_week == $third_week_lesson){
					if($denied==0){
						$arr_dates[$num] = $start + (86400*$t);
						$arr_dates_day[$num] = date("Y-m-d",$start + (86400*$t));
						$all_queries_num--;
						$num++;
					}
				}
				$t++;	
			}

			for($e=0;$e<count($arr_dates);$e++){
				$sql="UPDATE `levels` SET `sd_".($e+1)."`='".date("Y-m-d",$arr_dates[$e])."' WHERE `teacher`='".$teacher_choose."' AND `timetable`='".$timetable_choose."' AND `sd_1`='".$level_start_date_choose."'";
				$result = mysql_query($sql) or die(mysql_error());
			}
		}else{
			echo "bad";
		}
	}
