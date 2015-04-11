<?php	
	require "db.php";
	require "func_lib.php";	
	
//	var_dump($_POST);
//	echo 'asdf';
//	echo  $_POST['info'].",".$_POST['id'].','.$_POST['info_type'];


	if(isset($_POST["id"]) and isset($_POST["info"]) and isset($_POST["info_type"])
		and $_POST["id"]!="" and $_POST["info"]!="" and $_POST["info_type"]!=""
	){
		$id = $_POST["id"];	
		$info = $_POST["info"];	
		$info_type = $_POST["info_type"];
	
		$sql = "UPDATE `levels_person` SET `".$info_type."`='".$info."' WHERE id=".$id;
	//	echo	$sql;
		$result = mysql_query($sql)	or die(mysql_error());
			
		$sql = "SELECT `".$info_type."` FROM `levels_person` WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
		while ($row = mysql_fetch_row($result)){
			echo $row[0];
		}
		
	}
	
?>