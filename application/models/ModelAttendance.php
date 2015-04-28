<?php
namespace application\models;
class ModelAttendance extends \application\core\Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_data(){
    }

    public function combinationDatesFittedToTimetable(){
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];

        $start = strtotime($level_start);

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

        if(date("N",$start)== $first_week_lesson or date("N",$start)== $second_week_lesson or date("N",$start)== $third_week_lesson){
            $data = $this->getAllCombinations($teacher,$timetable,$level_start);
            return $data;
        }else{
            echo "Дата старта уровня не соответствует расписанию";
        }
    }
    public function studentsInformation(){
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];


        $students = $this->getPersonIdStartStop($teacher,$timetable,$level_start);
        if(count($students) != 0){
            for ($i=0; $i <count($students); $i++) {
                $id=$students[$i]['id_person'];
                $arr['id'][]=$students[$i]['id_person'];
                $arr['discount'][]=$this->getDiscount($id,$teacher,$timetable,$level_start);
                $arr['name'][]=$this->getName($id);
                $arr['personStart'][]=$students[$i]['person_start'];
                $arr['personStop'][]=$students[$i]['person_stop'];
                $data=$this->getNumPayedNumReserved($id,$teacher,$timetable,$level_start);
                $arr['numPayed'][]=$data['num_payed'];
                $arr['numReserved'][]=$data['num_reserved'];
                $data=$this->getAttenedDates($id,$teacher,$timetable,$level_start);
                $arr['attenedDates'][]=$data;
                $arr['frozenDates'][]=$this->getFrozenDates($id,$teacher,$timetable,$level_start);
            }
            $arr['dates']=$this->getCombinationDates($teacher,$timetable,$level_start);
            $arr['status']=$this->getCombinationStatus($teacher,$timetable,$level_start);
        }
        if(!empty($arr)){return $arr;}else{return;}
    }
    public function buildingBlocks(){
        $data=$this->getAllCombinationsExistedFromLevels();
        return $data;
    }
    public function deleteAttenedDateFromAttendanceTable(){
        $id = $_POST["id"];
        $attenedDate = $_POST["attenedDate"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        $data=$this->getIdFromAttendanceTable($attenedDate,$teacher,$timetable,$level_start,$id);
        $existedId = $data[0]['id'];
        $data=$this->setDeleteIdFromAttendanceTable($existedId);
        return $data;
    }
    public function insertAttenedDateToAttendanceTable(){
        $id = $_POST["id"];
        $attenedDate = $_POST["attenedDate"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        $data=$this->setInsertFromAttendanceTable($id,$attenedDate,$teacher,$timetable,$level_start);
        return $data;
    }
    public function changeLevelStartDate(){
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        $new_level_start = $_POST["new_level_start"];

        $wrong = array();
        $start = strtotime($new_level_start);

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

        $data = $this->getIdOfCombination($teacher,$timetable,$level_start);
        if(!empty($data['id'])){
            $dataMain['id'] = $data['id'];
            $id = $data['id'];
            if(date("N",$start)== $first_week_lesson or date("N",$start)== $second_week_lesson or date("N",$start)== $third_week_lesson){
                $data = $this->getAllBadDaysOfCombination($teacher,$timetable,$level_start);
                $dataMain['badDays'] = $data;
                $flag=true;
                for($t=0;$flag;$t++) {
                    $denied = 0;
                    $day_of_week = date("N",$start + (86400*$t));
                    for ($i = 0; $i < count($dataMain['badDays']); $i++) {
                        if (strtotime(date("Y-m-d", ($start + (86400 * $t)))) == strtotime($dataMain['badDays'][$i])) {
                            $denied = 1;
                        }
                    }
                    if($day_of_week == $first_week_lesson or $day_of_week == $second_week_lesson or $day_of_week == $third_week_lesson) {
                        if ($denied == 0){
                            $dataMain['newDatesInDayFormat'][] = date("Y-m-d",$start + (86400*$t));
                        }
                    }
                    if(count($dataMain['newDatesInDayFormat'])==21){$flag = false;}
                }
                if(isset($dataMain['newDatesInDayFormat'][20])){
                    $calculatedLevelStop = $dataMain['newDatesInDayFormat'][20];
                }
                $data = $this->getPersonIdStartStop($teacher,$timetable,$level_start);
                $dataMain['PersonIdStartStop'] = $data;

                for($i=0;$i<count($dataMain['PersonIdStartStop']);$i++){
                    $id_person = $dataMain['PersonIdStartStop'][$i]['id_person'];
                    $person_start = $dataMain['PersonIdStartStop'][$i]['person_start'];
                    $person_stop = $dataMain['PersonIdStartStop'][$i]['person_stop'];
                    $dataMain['numPayedNumReserved'] = $this->getNumPayedNumReserved($id_person,$teacher,$timetable,$level_start);
                    $numPayed = $dataMain['numPayedNumReserved']['num_payed'];
                    $numReserved = $dataMain['numPayedNumReserved']['num_reserved'];
                    $dataMain['combinationDates'] = $this->getCombinationDates($teacher,$timetable,$level_start);
                    if(isset($calculatedLevelStop)){
                        if(strtotime($person_stop)>strtotime($calculatedLevelStop)){
                            $num_minus=0;	//	количество скушаных в конце уроков
                            if(strtotime($person_start)>$calculatedLevelStop){
                                $this->setUpdatePersonStartEqualCalculatedLevelStop($calculatedLevelStop,$teacher,$timetable,$level_start,$id_person);
                            }else{
                                for($j=0;$j<count($dataMain['combinationDates']);$j++){
                                    if($dataMain['combinationDates'][$j]==date("Y-m-d",$calculatedLevelStop)){
                                        $num_minus = 20-$j;
                                    }
                                }
                            }
                            if(strtotime($person_start)>$calculatedLevelStop){
                                $num_minus = $numPayed;
                                $num_minus_reserverd = $numReserved;
                            }
                            if($numPayed>($numReserved-$num_minus)){
                                $discount=$this->getDiscount($id_person,$teacher,$timetable,$level_start);
                                $defaulCostOfOneLesson=$this->getDefaulCostOfOneLesson();
                                $costOfOneLessonWithDiscount=$this->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);
                                $this->setUpdateBalance($costOfOneLessonWithDiscount,$num_minus,$id_person);
                                $this->setUpdateNumPayedToPayedLessons($num_minus,$id_person);
                            }
                            if(strtotime($person_start)>$calculatedLevelStop){
                                $this->setUpdateNumReservedToPayedLessons($num_minus_reserverd,$id_person,$teacher,$timetable,$level_start);
                            }else{
                                $this->setUpdateNumReservedByNumMinusToPayedLessons($num_minus,$id_person,$teacher,$timetable,$level_start);
                            }
                            $this->setUpdatePersonStopToLevelsPerson($calculatedLevelStop,$id_person,$teacher,$timetable,$level_start);
                            $this->setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start);
                            $this->setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable,$level_start);
                        }
                        else if(strtotime($person_start)<strtotime($new_level_start)){
                            $num_eaten=0;	//	съедено в начале
//                            $num_minus=0;	//	съедено в конце
                            for($j=0;$j<count($dataMain['combinationDates']);$j++){
                                if($dataMain['combinationDates'][$j]==$new_level_start){
                                    $num_eaten = $j;
                                }
                            }
                            $dataMain['num_eaten'][] = $num_eaten;
//                            if(!empty($numPayed)){
                                $person_stop = $this->getPersonStop($id_person,$teacher,$timetable,$level_start);
                                $person_stop = $person_stop[0][0];
                                for($e=0;$e<count($dataMain['newDatesInDayFormat']);$e++){
                                    $dataMain['new_person_stop'][] = $person_stop;
                                    if(strtotime($dataMain['newDatesInDayFormat'][$e])==strtotime($person_stop)){
                                        $new_person_stop = $dataMain['newDatesInDayFormat'][$e+$num_eaten];
                                    }
                                }
                                if(strtotime($person_stop)<strtotime($new_level_start)){
                                    $this->setUpdatePersonStopToLevelsPerson($calculatedLevelStop,$id_person,$teacher,$timetable,$level_start);
                                }else{
                                    $this->setUpdatePersonStopWithNewPersonStopToLevelsPerson($new_person_stop,$id_person,$teacher,$timetable,$level_start);
                                }
//                            }
                            $this->setUpdatePersonStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start);
                            $this->setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start);
                            $this->setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable,$level_start);
                        }
                        else{
                           $this->setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable,$level_start);
                            $this->setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start);
                        }
                    }
                    if(!empty($new_person_stop)){$dataMain['new_person_stop'][] = $new_person_stop;}
                }

                if($dataMain['newDatesInDayFormat']){
                    for($i=0;$i<count($dataMain['newDatesInDayFormat']);$i++){
                        if($i==0){
                            $this->setUpdateLevelStartToLevels($i,$dataMain['newDatesInDayFormat'][$i],$teacher,$timetable,$level_start);
                        }
                        else{
                            $this->setUpdateLevelStartToLevels($i,$dataMain['newDatesInDayFormat'][$i],$teacher,$timetable,$new_level_start);
                        }
                    }
                }
            }
            else{
                $wrong['wrongTimetable']=true;
                return $wrong;
            }
            return $dataMain;
        }

    }
    public function removePersonCombination(){
        $id = $_POST["id"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $discount = $this->getDiscount($id,$teacher,$timetable,$level_start);
        $defaulCostOfOneLesson=$this->getDefaulCostOfOneLesson();
        $costOfOneLessonWithDiscount=$this->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);
        $numPayed = $this->getNumPayedNumReserved($id,$teacher,$timetable,$level_start);
        $numPayed = $numPayed['num_payed'];

        $backToBalanceSum=$numPayed*$costOfOneLessonWithDiscount;

        $this->setUpdateBalanceWithBackToBalanceSum($backToBalanceSum,$id);
        $this->setDeletePersonCombinationFromLevelsPeson($id,$teacher,$timetable,$level_start);
        $this->setDeletePersonCombinationFromPayedLessons($id,$teacher,$timetable,$level_start);

        $this->setDeletePersonCombinationFromAttendance($id,$teacher,$timetable,$level_start);
        $this->setDeletePersonCombinationFromDiscount($id,$teacher,$timetable,$level_start);
        $this->setDeletePersonCombinationFromFreeze($id,$teacher,$timetable,$level_start);
    }

    /////////////////////////////////////////////////////////   /GETTERS   /////////////////////////////////////////////////////////
    public function getAllCombinations($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT * FROM `levels` WHERE `sd_1`='".$level_start."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    public function getPersonIdStartStop($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `id_person`,`person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getPersonStop($id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `person_stop` FROM `levels_person` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id_person;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_NUM);
        return $data;
    }
    public function getDiscount($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `discount` FROM `discounts` WHERE `id_person`='".$id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['discount'])){$discount = $data[0]['discount'];}else{$discount = 0;}
        return $discount;
    }
    public function getName($id){
        $db = $this->db;
        $sql = "SELECT `fio` FROM `main` WHERE `id`='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        $name = $data[0]['fio'];
        return $name;

    }
    public function getCombinationDates($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        if(!empty($everyLessonDate[0])){return $everyLessonDate[0];}else{return false;}
    }
    public function getCombinationStatus($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `status` FROM `levels` WHERE `teacher`='".$teacher."' AND `sd_1`='".$level_start."' AND `timetable`='".$timetable."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate[0];
    }
    public function getNumPayedNumReserved($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE id_person='".$id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
//        return $sql;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(!empty($data[0])){return $data[0];}else{return false;}
    }
    public function getFrozenDates($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND freeze.id_person=" . $id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getAttenedDates($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `timetable`='" . $timetable . "' AND `id_visit`=" . $id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getAllCombinationsExistedFromLevels(){
        $db = $this->db;
        $sql = "SELECT `teacher`, `timetable`, `sd_1`,`level`,`status` FROM `levels` ORDER BY `teacher` ";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getIdFromAttendanceTable($attenedDate,$teacher,$timetable,$level_start,$id){
        $db = $this->db;
        $sql = "SELECT `id` FROM `attendance` WHERE `date_of_visit`='".$attenedDate."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_visit`='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getIdOfCombination($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `id` FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    public function getAllBadDaysOfCombination($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `bad_day` FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function setUpdatePersonStartEqualCalculatedLevelStop($calculatedLevelStop,$teacher,$timetable,$level_start,$id_person){
//        return $calculatedLevelStop;
        $DayOfCalculatedLevelStop = date("Y-m-d",$calculatedLevelStop);
        $db = $this->db;
        $sql="UPDATE `levels_person` SET `person_start`=:calculatedLevelStop  WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':calculatedLevelStop', $DayOfCalculatedLevelStop, \PDO::PARAM_STR);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function getDefaulCostOfOneLesson()
    {
        $db = $this->db;
        $sql = "SELECT `one lesson default` FROM `constants`";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if (isset($data[0]['one lesson default'])) {
            $defaulCostOfOneLesson = $data[0]['one lesson default'];
        }
        return $defaulCostOfOneLesson;
    }
    public function getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson){
        $CostOfOneLessonWithDiscount = $defaulCostOfOneLesson - round(($defaulCostOfOneLesson*($discount*0.01)),2);
        $arr['CostOfOneLessonWithDiscount'] = $CostOfOneLessonWithDiscount;
        return $CostOfOneLessonWithDiscount;
    }

    public function setInsertFromAttendanceTable($id,$attenedDate,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "INSERT INTO `attendance` (`date_of_visit`,`id_visit`,`teacher`,`timetable`,`level_start`) VALUES(:date_of_visit,:id_visit,:teacher,:timetable,:level_start)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':date_of_visit', $attenedDate, \PDO::PARAM_INT );
        $stmt->bindParam(':id_visit', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR );
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR );
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR );
        $stmt->execute();

        $data['lastInsert'] = $db->lastInsertId();
        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function setDeleteIdFromAttendanceTable($existedId){
        $db = $this->db;
        $sql = "DELETE FROM `attendance` WHERE `id`=:existedId";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':existedId', $existedId, \PDO::PARAM_INT );
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromAttendance($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "DELETE FROM `attendance` WHERE `id_visit`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromDiscount($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "DELETE FROM `discounts` WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromFreeze($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "DELETE FROM `freeze` WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromLevelsPeson($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "DELETE FROM `levels_person` WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;

    }
    public function setDeletePersonCombinationFromPayedLessons($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "DELETE FROM `payed_lessons` WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;

    }
    public function setUpdateBalance($costOfOneLessonWithDiscount,$num_minus,$id_person){
        $db = $this->db;
        $sql="UPDATE `balance` SET `balance`=balance+:costOfOneLessonWithDiscount*(:num_minus-1) WHERE `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':costOfOneLessonWithDiscount', $costOfOneLessonWithDiscount, \PDO::PARAM_INT);
        $stmt->bindParam(':num_minus', $num_minus, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateBalanceWithBackToBalanceSum($backToBalanceSum,$id){
        $db = $this->db;
//        return $backToBalanceSum;
        $sql="UPDATE `balance` SET `balance`= balance+:backToBalanceSum WHERE `id_person`=:id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':backToBalanceSum', $backToBalanceSum, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateNumPayedToPayedLessons($num_minus,$id_person){
        $db = $this->db;
        $sql="UPDATE `payed_lessons` SET `num_payed`=num_payed-(:num_minus-1) WHERE `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_minus', $num_minus, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateNumReservedToPayedLessons($num_minus_reserverd,$id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="UPDATE `payed_lessons` SET `num_reserved`=num_reserved-(:num_minus_reserverd-1) WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_minus_reserverd', $num_minus_reserverd, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateNumReservedByNumMinusToPayedLessons($num_minus,$id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="UPDATE `payed_lessons` SET `num_reserved`=num_reserved-:num_minus WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_minus', $num_minus, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdatePersonStopToLevelsPerson($calculatedLevelStop,$id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
//        return $calculatedLevelStop;
        if(!is_string($calculatedLevelStop)){$calculatedLevelStop = date('Y-m-d',$calculatedLevelStop);}
        $sql="UPDATE `levels_person` SET `person_stop`=:calculatedLevelStop WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':calculatedLevelStop', $calculatedLevelStop, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdatePersonStopWithNewPersonStopToLevelsPerson($new_person_stop,$id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="UPDATE `levels_person` SET `person_stop`=:new_person_stop WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_person_stop', $new_person_stop, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="UPDATE `levels_person` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="UPDATE `payed_lessons` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdatePersonStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="UPDATE `levels_person` SET `person_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToLevels($i,$newDatesOfCombination,$teacher,$timetable,$level_start){
        $db = $this->db;
        $i = $i+1;
        $sql="UPDATE `levels` SET sd_:i=:newDatesOfCombination WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':i', $i, \PDO::PARAM_INT);
        $stmt->bindParam(':newDatesOfCombination', $newDatesOfCombination, \PDO::PARAM_STR);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }


}