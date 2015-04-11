<?php	
	require "db.php";
	
	$teacher_arr = array();
	$sql="SELECT DISTINCT `teacher` FROM `levels`";
	$result=mysql_query($sql) or die(mysql_error());
	$i=0;
	while($row=mysql_fetch_row($result)){
		$teacher_arr[$i]=$row[0];
		$i++;
	}
	echo json_encode($teacher_arr);