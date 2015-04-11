<?php
	require "db.php";
	require "func_lib.php";	
	
//	var_dump($_POST);
	
	
	if(isset($_POST["id_take"]) and $_POST["id_take"]!="" and 
		isset($_POST["take_person_money"]) and $_POST["take_person_money"]!="" and 
		isset($_POST["fio_take"]) and $_POST["fio_take"]!=""
		// isset($_POST["date_take"]) and $_POST["date_take"]!="" and 
		// isset($_POST["combination_take"]) and $_POST["combination_take"]!=""
		){
	
		$fio_id = $_POST["id_take"];
		$take_person_money = $_POST["take_person_money"];
		$fio_take = $_POST["fio_take"];
		// $date_take = $_POST["date_take"];
		// $combi_stack = $_POST["combination_take"];
		// $combi = explode("|", $combination_take);
		// var_dump($combination_take_exploded);
		
		$sql = "INSERT INTO `payment_has` (`given`,`fio_id`) VALUES('".$take_person_money."','".$fio_id."')";
		// echo $sql;
		$result = mysql_query($sql) or die(mysql_error());
		$id = mysql_insert_id();
		$sql = "SELECT * FROM `payment_has` WHERE `id`=".$id;
		$result2 = mysql_query($sql) or die(mysql_error());
		if($result && $result2){
			while ($row = mysql_fetch_row($result2)){
				echo $take_person_money."|".$fio_take."|".$fio_id."|".$row[0]."|".$row[3].PHP_EOL;
			}
		}

		$sql = "SELECT `balance` FROM `balance` WHERE  `id_person`='".$fio_id."'";
		echo $sql.PHP_EOL;
		$result = mysql_query($sql) or die(mysql_error());
		if($row=mysql_fetch_row($result)){
			print_r( $row);
			$sum = $take_person_money+$row[0];
			$sql = "UPDATE `balance` SET `balance`='".$sum."' WHERE `id_person`='".$fio_id."'";
			echo $sql.PHP_EOL;
			$result = mysql_query($sql) or die(mysql_error());
		}else{
			$sql = "INSERT INTO `balance` (`id_person`,`balance`) VALUES('".$fio_id."', '".$take_person_money."')";
			echo $sql.PHP_EOL;
			$result = mysql_query($sql) or die(mysql_error());
		}

	}
	



 ?>