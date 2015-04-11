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
	// print_r($teacher_arr);

	if($teacher_arr){
		if(isset($_POST["from"]) and isset($_POST["to"])
			and $_POST["from"]!="" and $_POST["to"]!=""){
			$start_week_range = strtotime($_POST["from"]);
			$stop_week_range = strtotime($_POST["to"]);
			$arr = array();
			$num_weeks = (($stop_week_range - $start_week_range)+86400)/604800;
			
			for($t=0;$t<$num_weeks;$t++){
				// echo (int)$num_weeks.PHP_EOL;
				$num_students = 0;
				$start_week = $start_week_range + (604800*$t);
				$stop_week = $start_week_range + ((604800*($t+1))-86400);
				// echo count($teacher_arr).PHP_EOL;
				for($i=0;$i<count($teacher_arr);$i++){
					// echo $teacher_arr[$i].PHP_EOL;
					$sql = "SELECT `person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='".$teacher_arr[$i]."'";
					// echo $sql.PHP_EOL;
					$result = mysql_query($sql)	or die(mysql_error());
					while($row = mysql_fetch_row($result)){
						$start_person = strtotime($row[0]);
						$stop_person = strtotime($row[1]);
						if(($start_week < $start_person and $stop_week < $start_person) or ($start_week > $stop_person and $stop_week > $stop_person)){
						}else{
							$num_students++;
							// echo "plus one student";
						}
					}
					$arr[$t][0][$i] = $num_students;
					$num_students=0;
					$arr[$t][1] = date("d-m-Y",$start_week)." : ".date("d-m-Y",$stop_week);
				}
				// mysql_data_seek($result,0);
			}
			echo json_encode($arr);
		}
	}
 ?>