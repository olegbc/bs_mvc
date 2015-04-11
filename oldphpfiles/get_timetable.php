<?php
	require "db.php";
	if(isset($_POST['teacher']) and $_POST['teacher']!=""){
		$sql = "SELECT DISTINCT `timetable` FROM `levels` WHERE `teacher`=".$_POST['teacher'];
		$result = mysql_query($sql) or die(mysql_error());
		$i=0;
		$arr = array();
		while($row=mysql_fetch_row($result)){
			$arr[$i]=$row[0];
			$i++;
		}
		echo json_encode($arr);
	}
