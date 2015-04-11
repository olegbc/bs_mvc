<?php	
	require "db.php";
	require "func_lib.php";
	header('Content-Type: text/html; charset=utf-8');
	/* //OLD TASK SOLUTION
	if(	isset($_POST["teacher"]) and $_POST["teacher"]!="" and
		isset($_POST["start_week"]) and $_POST["start_week"]!="" and
		isset($_POST["stop_week"]) and $_POST["stop_week"]!=""){
		
			$start_week = strtotime($_POST["start_week"]);
			$stop_week = strtotime($_POST["stop_week"]);
			$num_students = 0;
			$sql = "SELECT `person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='Сергей'";
			$result = mysql_query($sql)	or die(mysql_error());
			while($row = mysql_fetch_row($result)){
				$start_person = strtotime($row[0]);
				$stop_person = strtotime($row[1]);
				if(($start_week < $start_person and $stop_week < $start_person) or ($start_week > $stop_person and $stop_week > $stop_person)){
				//	echo "OUT".PHP_EOL;
					$num_students++;
				}
			}
		echo $num_students;	
	}	
	*/
	
	
	if(	isset($_POST["teacher"]) and $_POST["teacher"]!="" and
		isset($_POST["start_week_range"]) and $_POST["start_week_range"]!="" and
		isset($_POST["stop_week_range"]) and $_POST["stop_week_range"]!=""){
		
			$teacher = $_POST["teacher"];
			$start_week_range = strtotime($_POST["start_week_range"]);
			$stop_week_range = strtotime($_POST["stop_week_range"]);
			$arr = array();
			
			$sql = "SELECT `person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='".$teacher ."'";
			$result = mysql_query($sql)	or die(mysql_error());
			for($t=0;$t<10;$t++){
			
				$num_students = 0;
			
				$start_week = $start_week_range + (604800*$t);
				$stop_week = $start_week_range + ((604800*($t+1))-86400);
				
				while($row = mysql_fetch_row($result)){
					$start_person = strtotime($row[0]);
					$stop_person = strtotime($row[1]);
					if(($start_week < $start_person and $stop_week < $start_person) or ($start_week > $stop_person and $stop_week > $stop_person)){
					}else{$num_students++;}
				}
			//	echo $t;
//				echo "неделя ".($t+1)." c ".date("d-m-Y",$start_week)." по ".date("d-m-Y",$stop_week)." посещений: ".$num_students.PHP_EOL;
				$arr[$t][0] = date("d-m-Y",$start_week);
				$arr[$t][1] = date("d-m-Y",$stop_week);
				$arr[$t][2] = $num_students;
				mysql_data_seek($result,0);
			}
		echo json_encode($arr);	
	//	var_dump($arr);	
	}	
	
	
?>