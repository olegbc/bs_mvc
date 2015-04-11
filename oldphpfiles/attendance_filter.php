<?php 
	require "db.php";
	
	$arr=array();
	$m=0;
	// var_dump($_POST);
	if(isset($_POST['teacher']) and $_POST['teacher']!=''){
		$sql="SELECT `timetable` FROM `levels` WHERE `teacher`='".$_POST['teacher']."'";
		// echo $sql;
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_row($result)){
			$arr[$m]=$row[0];
			$m++;
		}
	} 
	if(isset($_POST['teacher']) and $_POST['teacher']!='' and isset($_POST['timetable_select']) and $_POST['timetable_select']!=''){
		$sql="SELECT `sd_1` FROM `levels` WHERE `teacher`='".$_POST['teacher']."' AND `timetable`='".$_POST['timetable']."'";
		// echo $sql;
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_row($result)){
			$arr[$m]=$row[0];
			$m++;
		}
	} 

	echo json_encode($arr);
 ?>