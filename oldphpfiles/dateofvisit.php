<?php	
	require "db.php";
	require "func_lib.php";	
	 
	if(isset($_POST["id"]) and $_POST["id"]!=""){
		$id = $_POST["id"];	
		$arr=array();
		$sql = "SELECT `fio` FROM `main` WHERE `id`=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
		while ($row = mysql_fetch_row($result)){
			$arr[0] = $row[0];
		}
		$sql = "SELECT `person_start` FROM `levels_person` WHERE `id_person`=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
		while ($row = mysql_fetch_row($result)){
			$arr[1] = $row[0];
		}
		echo json_encode($arr);
	}
	
?>