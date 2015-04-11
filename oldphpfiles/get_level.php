<?php
	require "db.php";
	if(isset($_POST['teacher']) and $_POST['teacher']!="" and 
		isset($_POST['timetable']) and $_POST['timetable']!="" and 
		isset($_POST['level_start']) and $_POST['level_start']!="" and 
		isset($_POST['id_person']) and $_POST['id_person']!=""){
		$payedLessonsExist=0;
		$attendancedLessonsExist=0;
		$frozenLessonsExist=0;
		
		$sql = "SELECT `num_payed` FROM `payed_lessons` WHERE `teacher`=".$_POST['teacher']." AND `timetable`=".$_POST['timetable']." AND `level_start`=".$_POST['level_start']." AND `id_person`=".$_POST['id_person'];
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_fetch_row($result)!=0){
			$payedLessonsExist=1;
		}

		$sql = "SELECT `id` FROM `attendance` WHERE `teacher`=".$_POST['teacher']." AND `timetable`=".$_POST['timetable']." AND `level_start`=".$_POST['level_start']." AND `id_visit`=".$_POST['id_person'];
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_fetch_row($result)){
			$attendancedLessonsExist=1;
		}

		$sql = "SELECT `id` FROM `freeze` WHERE `teacher`=".$_POST['teacher']." AND `timetable`=".$_POST['timetable']." AND `level_start`=".$_POST['level_start']." AND `id_person`=".$_POST['id_person'];
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_fetch_row($result)){
			$frozenLessonsExist=1;
		}

		$sql = "SELECT `level`,sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE `teacher`=".$_POST['teacher']." AND `timetable`=".$_POST['timetable']." AND `sd_1`=".$_POST['level_start'];
		$result = mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_row($result);

		// $sql = "SELECT `level` FROM `levels` WHERE `teacher`=".$_POST['teacher']." AND `timetable`=".$_POST['timetable']." AND `sd_1`=".$_POST['level_start'];
		// $result = mysql_query($sql) or die(mysql_error());
		// $row=mysql_fetch_row($result);
		// $level=$row[0];

		if($payedLessonsExist!=1 && $attendancedLessonsExist!=1 && $frozenLessonsExist!=1){
			echo json_encode($row);
		}else{echo json_encode($row[0]);}
	}
