<?php 
	require "db.php";
	require "func_lib.php";
	$arr=array();
	$m=0;
	if(isset($_POST['teacher']) and $_POST['teacher']!='' and isset($_POST['timetable']) and $_POST['timetable']!=''){
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