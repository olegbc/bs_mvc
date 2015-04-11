i<?php	
class findComboDays{
	public function go(){

		$checkArr = array("teacher","timetable","level_start","shift");
		if(CheckValue::check($checkArr)){
			extract($_POST);
			$sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20, sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
			$result = mysql_query($sql) or die(mysql_error());
			$row1=mysql_fetch_row($result);

			$baddays=array();

			$sql = "SELECT `bad_day` FROM `bad_days` WHERE teacher='".$teacher."' AND timetable='".$timetable."' AND level_start='".$level_start."'";
			$result = mysql_query($sql) or die(mysql_error());
			$i=0;
			while($row2=mysql_fetch_row($result)){
				$baddays[$i] = $row2[0];
				$i++;
			}
			$rows = array();
			$rows[0]=$row1;
			$rows[1]=$baddays;

			echo json_encode($rows);

		}
	}
}