<?php	
	// require "db.php";
	// header('Content-Type: text/html; charset=utf-8');
	// function __autoload($class_name) {
	// 	include $class_name . '.php';
	// }
	class bad_day_to_db{
		public function go(){
			$checkArr = array("teacher","timetable","level_start","badDayClicked");

			if(CheckValue::check($checkArr)){
					extract($_POST);
				
					$sql = "SELECT `bad_day` FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `bad_day`='".$badDayClicked."'";
						// echo $sql.PHP_EOL;
					$result = mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($result)){
						$sql = "DELETE FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `bad_day`='".$badDayClicked."'";
						// echo $sql.PHP_EOL;
						$result = mysql_query($sql) or die(mysql_error());
					}else{
						$sql = "INSERT INTO `bad_days` (`bad_day`,`teacher`,`timetable`,`level_start`) VALUES ('".$badDayClicked."','".$teacher."','".$timetable."','".$level_start."')";
						// echo $sql.PHP_EOL;
						$result = mysql_query($sql) or die(mysql_error());
					}

			}
		}
	}
