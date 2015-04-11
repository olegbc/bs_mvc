<?php 
	require "db.php";

	if(isset($_POST['person']) and $_POST['person']!=""){
		$num_lessons_person_on_sochitanie_arr = 0;
		$balance =0;
		$freeze_dates_arr = array();
		$sql = "SELECT main.fio,payment_has.given FROM `main` LEFT JOIN `payment_has` ON payment_has.fio_id=".$_POST['person']." WHERE main.id=".$_POST['person'];
		// echo $sql.PHP_EOL;
		// die();
		$result = mysql_query($sql)	or die(mysql_error());
		$sum = 0;
		$flag = 0;
		while($row = mysql_fetch_row($result)){
			if($flag == 0){?><?php /* !!!!!!  echo $row[0]."<br />";*/$fio=$row[0];$flag=1; ?><?php }
			if($row[1]){$sum = $sum + $row[1];}
		}
		$sql="SELECT `teacher`,`timetable`,`level_start` FROM `levels_person` WHERE id_person=".$_POST['person'];
		$result = mysql_query($sql) or die(mysql_error());
		$sochitanie_arr = array(); // массив со всеми сочетаниями данной персоны
		$t=0;
		while($row = mysql_fetch_row($result)){
			$sochitanie_arr[$t][0] = $row[0];
			$sochitanie_arr[$t][1] = $row[1];
			$sochitanie_arr[$t][2] = $row[2];
			$t++;
		}
		if(count($sochitanie_arr)){
			$sum_sochitanie_arr = array();
			$num_lessons_person_on_sochitanie_arr = array(); // массив с количеством уроков на конкретном сочетании данной персоны
			$lesons_left_arr = array(); // массив осталось уроков
			$pay_left_arr = array(); // массив осталось заплатить за сочетание
			$freeze_dates_arr = array();// массив дат заморозки
			for($i=0;$i<count($sochitanie_arr);$i++){
				$sql="SELECT `person_start`,`person_stop` FROM `levels_person` WHERE id_person=".$_POST['person']." AND levels_person.teacher='".$sochitanie_arr[$i][0]."' AND levels_person.timetable='".$sochitanie_arr[$i][1]."' AND levels_person.level_start='".$sochitanie_arr[$i][2]."'";
				$result = mysql_query($sql) or die(mysql_error());
				while($row = mysql_fetch_row($result)){
					$person_start = $row[0];
					$person_stop = $row[1];
				}

				$sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21
						 FROM `levels` WHERE levels.teacher='".$sochitanie_arr[$i][0]."' AND levels.timetable='".$sochitanie_arr[$i][1]."' AND levels.sd_1='".$sochitanie_arr[$i][2]."'";
				$result = mysql_query($sql) or die(mysql_error());
				$person_start_on_sochitanie=0;
				$person_stop_on_sochitanie=0;
				while($row = mysql_fetch_row($result)){
					for($u=0;$u<21;$u++){
						if($row[$u] == $person_start){$person_start_on_sochitanie=$u;};
						if($row[$u] == $person_stop){$person_stop_on_sochitanie=$u;};
					}
				}
				$num_lessons_person_on_sochitanie = (abs($person_stop_on_sochitanie - $person_start_on_sochitanie))+1;
				$num_lessons_person_on_sochitanie_arr[$i]=$num_lessons_person_on_sochitanie;

				$sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON 
							levels_person.id_person = freeze.id_person AND 
							levels_person.teacher = freeze.teacher AND 
							levels_person.timetable = freeze.timetable AND 	
							levels_person.level_start = freeze.level_start 
						WHERE levels_person.teacher='".$sochitanie_arr[$i][0]."' AND 
								levels_person.level_start='".$sochitanie_arr[$i][2]."' AND 
								levels_person.timetable='".$sochitanie_arr[$i][1]."' AND 
								freeze.id_person=".$_POST['person'];
					// echo "i= ".$i.PHP_EOL;
					// echo $sql.PHP_EOL;
				$result = mysql_query($sql)	or die(mysql_error());
				$er=0;
				while ($row = mysql_fetch_row($result)){
					$freeze_dates_arr[$i][$er] = $row[0];
					$er++;			
				}
			}
			// print_r($freeze_dates_arr);
		}
	}




	$num_lessons_payed=0;

	$main_arr = array();
	$main_arr[0]=$fio; // фамилия персоны
	$main_arr[1]=$sum; // все что заплатила персона
	if($sochitanie_arr){$main_arr[2]=$sochitanie_arr;}else{$main_arr[2]=0;} // какие есть у персоны сочетания
	$main_arr[3]=$num_lessons_payed; // количество оплаченных уроков
	if($num_lessons_person_on_sochitanie_arr){$main_arr[4]=$num_lessons_person_on_sochitanie_arr;}else{$main_arr[4]=0;} // количество уроков на конкретном сочетании данной персоны
	
	if($freeze_dates_arr){$main_arr[5]=$freeze_dates_arr;}else{$main_arr[5]=0;}

	echo json_encode($main_arr);	

?>

