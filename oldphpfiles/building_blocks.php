<?php	
	require "db.php";

	$arr = array();	
	$n = 0;
	$day_now = strtotime(date("d-m-Y"));

	$sql = "SELECT `teacher`, `timetable`, `sd_1`,`level`,`status` FROM `levels` ORDER BY `teacher` ";
	$result = mysql_query($sql) or die(mysql_error()); 
	while($row = mysql_fetch_row($result)){
		$arr[$n][0]=$row[0];
		$arr[$n][1]=$row[1];
		$arr[$n][2]=$row[2];
		$arr[$n][3]=$row[3];
		$arr[$n][4]=$row[4];
		$n++;
	}
	echo json_encode($arr);