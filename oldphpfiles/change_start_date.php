<?php
	require "db.php";
	header('Content-Type: text/html; charset=utf-8');
		// var_dump($_POST);

			//	если есть входные данные teacher, timetable, level_start, new_level_start
	if(isset($_POST['teacher']) and $_POST['teacher']!="" and isset($_POST['timetable']) and $_POST['timetable']!="" and 
			isset($_POST['level_start']) and $_POST['level_start']!="" and isset($_POST['new_level_start']) and $_POST['new_level_start']!=""){
		$teacher = $_POST["teacher"];	
		$timetable = $_POST["timetable"];	
		$level_start = $_POST["level_start"];	
		$new_level_start = $_POST["new_level_start"];

			//	инициализируем переменные необходимые в дальнейшем для работы
		$arr = array();
		$arr_persons = array();
		$arr_dates_day = array();
		$arr[0] = $teacher;
		$arr[1] = $timetable;
		$arr[2] = $level_start;
		$arr[3] = $new_level_start;
		$t=0;
		$num=0;
		$all_queries_num = 21;
		$repeats = 1120;
		$arr_bad_days = array();
		$start = strtotime($new_level_start);
		$arr_dates=array();

			//	опредиляем тип расписания
		if($timetable == "ПУ" or $timetable == "ПД" or $timetable == "ПВ"){
			$first_week_lesson=1;
			$second_week_lesson=3;
			$third_week_lesson=5;
		}	
		if($timetable == "ВУ" or $timetable == "ВД" or $timetable == "ВВ"){
			$first_week_lesson=2;
			$second_week_lesson=4;
			$third_week_lesson=6;
		}

			// формирование $arr_dates
		$exist = 0;
		//	есть ли такая запись в таблице levels?
		$sql = "SELECT `id` FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";
		// echo $sql.PHP_EOL;
		$result = mysql_query($sql) or  die(mysql_error());
		if(mysql_num_rows($result)){
			$exist = 1;
			$row =mysql_fetch_row($result);$id = $row[0];
		}
		if($exist == 1){
			// echo date("N",$start).PHP_EOL;
			if(date("N",$start)== $first_week_lesson or date("N",$start)== $second_week_lesson or date("N",$start)== $third_week_lesson){
				//	все бед деи
				$sql = "SELECT `bad_day` FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
				$result = mysql_query($sql) or die(mysql_error());
				$p = 0;
				while($row=mysql_fetch_row($result)){
					$arr_bad_days[$p] = $row[0];
					$p++;
				}

				while($repeats-- and $all_queries_num >0){
					$denied = 0;
					$day_of_week = date("N",$start + (86400*$t));
					for($i=0;$i<count($arr_bad_days);$i++){
						if(date("Y-m-d",($start + (86400*$t))) == $arr_bad_days[$i]){$denied = 1;}
					}
					if($day_of_week == $first_week_lesson or $day_of_week == $second_week_lesson or $day_of_week == $third_week_lesson){
						if($denied==0){
							$arr_dates[$num] = $start + (86400*$t);
							$arr_dates_day[$num] = date("Y-m-d",$start + (86400*$t));
							$all_queries_num--;
							$num++;
						}
					}
					$t++;	
				}
				if(isset($arr_dates[20])){
					$calculatedLevelStop = $arr_dates[20];
					// 8
				}
				if(isset($arr_dates[0])){
					$calculatedLevelStart = $arr_dates[0];
					// 8
				}

					// формироание $arr_persons
				$sql="SELECT `id_person`,`person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
				// echo $sql.PHP_EOL;
				$result= mysql_query($sql) or die(mysql_error());
				$i=0;
				while($row=mysql_fetch_row($result)){
					$id_person[$i]=$row[0];
					$person_start[$i]=$row[1];
					$person_stop[$i]=$row[2];
					$i++;
				}

					// зменение базы
				for($i=0;$i<count($id_person);$i++){
					if(isset($calculatedLevelStop)){
						if(strtotime($person_stop[$i])>$calculatedLevelStop){
							echo "back".PHP_EOL;
							$sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";
							$result = mysql_query($sql) or die(mysql_error());
							$row=mysql_fetch_row($result);
							$num_minus=0;	//	количество скушаных в конце уроков
							if(strtotime($person_start[$i])>$calculatedLevelStop){
								$sql="UPDATE `levels_person` SET `person_start`='".date("Y-m-d",$arr_dates[20])."'  WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
								$result = mysql_query($sql)	or die(mysql_error());
							}else{
								for($j=0;$j<count($row);$j++){
									if($new_level_start){}
									if($row[$j]==date("Y-m-d",$arr_dates[20])){
										$num_minus = 20-$j;
									}
								}
							}
							$sql="SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person`=".$id_person[$i]." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
							$result = mysql_query($sql)	or die(mysql_error());
							$row=mysql_fetch_row($result);
							$numPayed = $row[0];
							$numReserved = $row[1];
							if(strtotime($person_start[$i])>$calculatedLevelStop){
								$num_minus = $numPayed;
								$num_minus_reserverd = $numReserved;
							}
							echo "num_minus: ".$num_minus.PHP_EOL;
							echo "num_minus_reserverd: ".$num_minus_reserverd.PHP_EOL;
							echo "numReserved: ".$num_minus_reserverd.PHP_EOL;
							echo "is numPayed more then numReserved-num_minus: ".$numPayed>($numReserved-$num_minus).PHP_EOL;
							if($numPayed>($numReserved-$num_minus)){
								$sql = "SELECT `discount` FROM `discounts` WHERE `id_person` ='".$id_person[$i]."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND `timetable` ='".$timetable."'";
								$result = mysql_query($sql)	or die(mysql_error());
								$row = mysql_fetch_row($result);
								$discount = $row[0];

								$sql = "SELECT `one lesson default` FROM `constants`";
								$result = mysql_query($sql) or die(mysql_error());
								$row = mysql_fetch_row($result);
								$one_lesson_default = $row[0];

								$one_lesson=$one_lesson_default-($one_lesson_default*($discount/100));
								// echo gettype($one_lesson*$num_minus);

								$sql="UPDATE `balance` SET `balance`=balance+'".($one_lesson*($num_minus-1))."' WHERE `id_person`=".$id_person[$i];
								$result = mysql_query($sql)	or die(mysql_error());
								$sql="UPDATE `payed_lessons` SET `num_payed`=num_payed-'".($num_minus-1)."' WHERE `id_person`=".$id_person[$i];
								$result = mysql_query($sql)	or die(mysql_error());
							}
							if(strtotime($person_start[$i])>$calculatedLevelStop){
								$sql="UPDATE `payed_lessons` SET `num_reserved`=num_reserved-'".($num_minus_reserverd-1)."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
								$result = mysql_query($sql)	or die(mysql_error());
							}else{
								$sql="UPDATE `payed_lessons` SET `num_reserved`=num_reserved-'".$num_minus."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
								$result = mysql_query($sql)	or die(mysql_error());
							}
							// echo gettype(date("Y-m-d",$arr_dates[20]));
							$sql="UPDATE `levels_person` SET `person_stop`='".date("Y-m-d",$arr_dates[20])."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
							$result = mysql_query($sql)	or die(mysql_error());
							$sql="UPDATE `levels_person` SET `level_start`='".$new_level_start."'  WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
							$result = mysql_query($sql)	or die(mysql_error());
							$sql="UPDATE `payed_lessons` SET `level_start`='".$new_level_start."'  WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
							$result = mysql_query($sql)	or die(mysql_error());
						}else if(strtotime($person_start[$i])<strtotime($new_level_start)){
							echo "forward".PHP_EOL;
							//	обновить: levels_person(level_start,person_start,person_stop), payed_lessons(num_payed,num_reserved,level_start), balance(balance)

							$sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";
							$result = mysql_query($sql) or die(mysql_error());
							$row=mysql_fetch_row($result);
							$last_lesson_date=$row[20];
							$num_eaten=0;	//	съедено в начале
							$num_minus=0;	//	съедено в конце
							for($j=0;$j<count($row);$j++){
								if($row[$j]==$new_level_start){
									$num_eaten = $j;
								}
							}

							$sql = "SELECT `num_payed`,`id` FROM `payed_lessons` WHERE `id_person`=".$id_person[$i]." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
							$result = mysql_query($sql)	or die(mysql_error());
							$row=mysql_fetch_array($result);
							$num_payed=$row[0];
							$id_of_payed_row=$row[1];
							

							if($id_of_payed_row){
								$sql="SELECT `person_stop` FROM `levels_person` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
								$result = mysql_query($sql)	or die(mysql_error());
								$row=mysql_fetch_row($result);
								$person_stop = $row[0];
								for($e=0;$e<count($arr_dates_day);$e++){
									if(strtotime($arr_dates_day[$e])==strtotime($person_stop)){
										$new_person_stop = $arr_dates_day[$e+$num_eaten];
									}
								}
								if(strtotime($person_stop[$i])<strtotime($new_level_start)){
									$sql="UPDATE `levels_person` SET `person_stop`='".date("Y-m-d",$arr_dates[20])."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
									$result= mysql_query($sql) or die(mysql_error());
								}else{
									$sql="UPDATE `levels_person` SET `person_stop`='".$new_person_stop."'WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
									$result = mysql_query($sql)	or die(mysql_error());
								}
							}
							$sql="UPDATE `levels_person` SET `person_start`='".$new_level_start."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
							$result= mysql_query($sql) or die(mysql_error());
							$sql="UPDATE `levels_person` SET `level_start`='".$new_level_start."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
							$result= mysql_query($sql) or die(mysql_error());
							$sql="UPDATE `payed_lessons` SET `level_start`='".$new_level_start."'WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
							// echo $sql;
							$result = mysql_query($sql)	or die(mysql_error());

						}else{
							echo "default".PHP_EOL;
							$sql="UPDATE `payed_lessons` SET `level_start`='".$new_level_start."'WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
							$result = mysql_query($sql)	or die(mysql_error());
							

							$sql="UPDATE `levels_person` SET `level_start`='".$new_level_start."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person[$i];
							$result= mysql_query($sql) or die(mysql_error());
						}
					}
				}
					//	обновление дат уровня
				if($arr_dates_day){
					for($i=0;$i<count($arr_dates_day);$i++){
						// echo $arr_dates_day[$i];
						if($i==0){
							$sql="UPDATE `levels` SET sd_".($i+1)."='".$arr_dates_day[$i]."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";
							$result= mysql_query($sql) or die(mysql_error());
						}
						else{
							$sql="UPDATE `levels` SET sd_".($i+1)."='".$arr_dates_day[$i]."' WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$new_level_start."'";
							$result= mysql_query($sql) or die(mysql_error());
						}
					}
				}
			}
		}
	}