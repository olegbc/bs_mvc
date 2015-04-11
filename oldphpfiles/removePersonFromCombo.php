<?php
	require "db.php";
	if(isset($_POST['teacher']) and $_POST['teacher']!="" and 
		isset($_POST['timetable']) and $_POST['timetable']!="" and 
		isset($_POST['level_start']) and $_POST['level_start']!="" and 
		isset($_POST['id_person']) and $_POST['id_person']!=""){

		
		$sql = "DELETE FROM `levels_person` WHERE `teacher`='".$_POST['teacher']."' AND `timetable`='".$_POST['timetable']."' AND `level_start`='".$_POST['level_start']."' AND `id_person`=".$_POST['id_person'];
		$result = mysql_query($sql) or die(mysql_error());
		
		$sql = "DELETE FROM `payed_lessons` WHERE `teacher`='".$_POST['teacher']."' AND `timetable`='".$_POST['timetable']."' AND `level_start`='".$_POST['level_start']."' AND `id_person`=".$_POST['id_person'];
		$result = mysql_query($sql) or die(mysql_error());

	}
