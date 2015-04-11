<?php
	require "db.php";
	if(isset($_POST['teacher']) and $_POST['teacher']!="" and 
		isset($_POST['timetable']) and $_POST['timetable']!=""){
		$sql = "SELECT `sd_1` FROM `levels` WHERE `teacher`=".$_POST['teacher']." AND `timetable`=".$_POST['timetable'];
		$result = mysql_query($sql) or die(mysql_error());
		$i=0;
		$arr = array();
		while($row=mysql_fetch_row($result)){
			$arr[$i]=$row[0];
			$i++;
		}
		echo json_encode($arr);
	}
