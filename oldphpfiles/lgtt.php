<?php	
	require "db.php";
	require "func_lib.php";	
	 
	if(isset($_POST["id"]) and $_POST["id"]!=""){
		$id = $_POST["id"];	
		$sql = "SELECT `fio` FROM `main` WHERE `id`=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
		while ($row = mysql_fetch_row($result)){
			echo $row[0];
		}
		
	}
	
?>