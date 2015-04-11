<?php
	require "db.php";
	require "func_lib.php";	
	
	if(isset($_POST["id"])){
		$id = $_POST["id"];	
		echo $id;
		$sql = "DELETE FROM main WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}
	
?>