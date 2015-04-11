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
	
		$sql = "UPDATE main SET `".$info_type."`='".$info."' WHERE id=".$id;
	//	echo	$sql;
		$result = mysql_query($sql)	or die(mysql_error());
			
		$sql = "SELECT `".$info_type."` FROM main WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
		while ($row = mysql_fetch_row($result)){
			echo $row[0];
		}
		
	} 
	
	
	
/*	
 	if(isset($_POST["fio"]) and $_POST["fio"]!=""){
		$sql = "UPDATE main SET fio="."'".$_POST["fio"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	} 	 
	if(isset($_POST["dog_num"]) and $_POST["dog_num"]!=""){

		$sql = "UPDATE main SET dog_num="."'".$_POST["dog_num"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}	
	if(isset($_POST["discount"]) and $_POST["discount"]!=""){
		$sql = "UPDATE main SET discount="."'".$_POST["discount"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}	
	if(isset($_POST["num_lessons"]) and $_POST["num_lessons"]!=""){
		$sql = "UPDATE main SET num_lessons="."'".$_POST["num_lessons"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}	
	if(isset($_POST["start"]) and $_POST["start"]!=""){
		$sql = "UPDATE main SET start="."'".$_POST["start"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}	
	if(isset($_POST["cost_per_lesson"]) and $_POST["cost_per_lesson"]!=""){
		$sql = "UPDATE main SET cost_per_lesson="."'".$_POST["cost_per_lesson"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}	
	if(isset($_POST["spy"]) and $_POST["spy"]!=""){
		$sql = "UPDATE main SET spy="."'".$_POST["spy"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}	
	if(isset($_POST["teacher"]) and $_POST["teacher"]!=""){
		$sql = "UPDATE main SET teacher="."'".$_POST["teacher"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}	
	if(isset($_POST["group"]) and $_POST["group"]!=""){
		$sql = "UPDATE main SET group="."'".$_POST["group"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}
	if(isset($_POST["level"]) and $_POST["level"]!=""){
		$sql = "UPDATE main SET level="."'".$_POST["level"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}
	if(isset($_POST["timetable"]) and $_POST["timetable"]!=""){
		$sql = "UPDATE main SET timetable="."'".$_POST["timetable"]."' WHERE id=".$id;
		$result = mysql_query($sql)	or die(mysql_error());
	}
	
*/	
	
?>