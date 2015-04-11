<?php	
	require "db.php";

	// var_dump($_POST);

	if(isset($_POST["from"]) and isset($_POST["to"])
		and $_POST["from"]!="" and $_POST["to"]!=""){
		$start_week_range = date('Y-m-d',strtotime($_POST["from"]));
		$stop_week_range = date('Y-m-d',strtotime($_POST["to"]));
		$start_week_range_unix = strtotime($_POST["from"]);
		$stop_week_range_unix = strtotime($_POST["to"]);
		$arr = array();
		$amount = 0;
		$num_weeks = (($stop_week_range_unix - $start_week_range_unix)+86400)/604800;
		// echo 'num_weeks : '.$num_weeks.PHP_EOL;
		for($t=0;$t<$num_weeks;$t++){
			$amount = 0;
			$start_week = date('Y-m-d',$start_week_range_unix + (604800*$t));
			$stop_week = date('Y-m-d',$start_week_range_unix + ((604800*($t+1))-86400));
			// echo 'start : '.$start_week.PHP_EOL;
			// echo 'stop : '.$stop_week.PHP_EOL;
			$sql = "SELECT `given` FROM `payment_has` WHERE `date` between '".$start_week."' AND '".$stop_week."'";
			// echo 'sql : '.$sql.PHP_EOL;
			$result = mysql_query($sql)	or die(mysql_error());
			while($row = mysql_fetch_row($result)){
				$amount = $amount + (int)($row[0]);
			}
			$arr[$t][0] = $amount;
			$arr[$t][1] = $start_week." - ".$stop_week;
			// $arr[$t][1] = 0;
		}
		echo json_encode($arr); 
	}
 ?>