<?php
	require "db.php";
	header('Content-Type: text/html; charset=utf-8');

	var_dump($_POST);

	if(isset($_POST["person_id"]) and $_POST["person_id"]!="" and 
		isset($_POST["payment_data"]) and $_POST["payment_data"]!="" and 
		isset($_POST["teacher"]) and $_POST["teacher"]!="" and 
		isset($_POST["timetable"]) and $_POST["timetable"]!="" and 
		isset($_POST["level_start"]) and $_POST["level_start"]!=""){
	
		$person_id = $_POST["person_id"];
        $payment_data = $_POST["payment_data"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        // DISCONT
        $sql="SELECT `discount` FROM `discounts` WHERE `id_person`='".$person_id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $result = mysql_query($sql) or die(mysql_error());
        if(mysql_num_rows ($result) == 0){
            $discount=0;
        }else{
            $row=mysql_fetch_row($result);
            $discount=$row[0]*0.01;
        }

        // ONE DEFAULT LESSON & ONE LESSON
        $sql = "SELECT `one lesson default` FROM `constants`" ;
        $result = mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_row($result);
        $one_lesson_default = $row[0];
        $one_lesson =$one_lesson_default - ($one_lesson_default*$discount);

        // BALANCE ALTERING
        $sql="SELECT `balance` FROM `balance` WHERE `id_person`=".$person_id;
        $result = mysql_query($sql) or die(mysql_error());
        if(mysql_num_rows ($result) == 0){
            echo "НЕТ ЗАПИСИ";
        }else{
            $row=mysql_fetch_row($result);
            $balance=$row[0];
            if($payment_data>0){
                //	BALANCE DECREASE
                if($balance>=$one_lesson){$new_balance = $balance - $one_lesson;
                    // PAYED LESSONS INCREASE + BALANCE DECREASE
                    $sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person`=".$person_id." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
                    $result=mysql_query($sql) or die(mysql_error());
                    if(mysql_num_rows ($result) == 0){
                        // $discount=0;
                        echo "такой записи нет CRITICAL ERROR!!!";
                    }else{
                        $row=mysql_fetch_row($result);
                        $num_payed = $row[0];
                        $num_reserved = $row[1];
                        if($num_payed<$num_reserved){
                            $sql="UPDATE `balance` SET `balance`=".$new_balance." WHERE `id_person`=".$person_id;
                            $result=mysql_query($sql) or die(mysql_error());
                            $new_num_payed = $num_payed+1;
                            $sql="UPDATE `payed_lessons` SET `num_payed`=".$new_num_payed." WHERE `id_person`=".$person_id." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
                            $result=mysql_query($sql) or die(mysql_error());
                            echo "БАЛАНС ОТНЯТ + УРОК ДОБВЛЕН";
                        }else{echo "все уроки оплачены";}
                    }
                }elseif($balance<$one_lesson){echo "баланс менее чем стоимость одного урока";}
            }elseif($payment_data<0){
                //	BALANCE INCREASE
                $new_balance = $balance + $one_lesson;
                $sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person`=".$person_id." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
                // echo $sql.PHP_EOL;
                $result=mysql_query($sql) or die(mysql_error());
                if(mysql_num_rows ($result) == 0){
                    echo "такой записи нет CRITICAL ERROR!!!";
                }else{
                    $row=mysql_fetch_row($result);
                    $num_payed = $row[0];
                    $num_reserved = $row[1];
                    if($num_payed>=1){
                        $sql="UPDATE `balance` SET `balance`=".$new_balance." WHERE `id_person`=".$person_id;
                        echo $sql.PHP_EOL;
                        $result=mysql_query($sql) or die(mysql_error());
                        $new_num_payed = $num_payed-1;
                        $sql="UPDATE `payed_lessons` SET `num_payed`=".$new_num_payed." WHERE `id_person`=".$person_id." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
                        echo $sql.PHP_EOL;
                        $result=mysql_query($sql) or die(mysql_error());
                        echo "БАЛАНС УВЕЛИЧЕН + УРОК ОТНЯТ";
                    }else{echo "ни один урок не оплачен";}
                }
            }
        }
	}
	
?>