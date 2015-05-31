<?php
namespace application\models;
class ModelFreeze extends \application\core\model {
    public function __construct()
    {
        parent::__construct();
    }
    public function get_data()
    {
    }

    public function buildFreezeTable()
    {
        $id = $_POST['id'];
        $data = $this->getName($id);
        $name = $data;
        $data = $this->getSumGiven($id);
        $sum = $data;;

        $dataMain['name'] = $name;
        $dataMain['sum'] = $sum;

        $data = $this->getAllCombinationsOfThisPerson($id);
        if(!empty($data)){
            foreach($data as $key=>$value){
                $dataMain['combinations'][] = $value;
                extract($value);    // $level,$teacher,$timetable,$level_start

                $data = $this->getPersonStartStop($id,$teacher,$timetable,$level_start);
                $personStart= $data[0]['person_start'];
                $personStop= $data[0]['person_stop'];

                $everyLessonDate = $this->getCombinationDates($teacher,$timetable,$level_start);
                foreach($everyLessonDate as $key=>$value){
                    for($u=0;$u<21;$u++){
                        if($value[$u] == $personStart){$numberOfStartLesson=$u;};
                        if($value[$u] == $personStop){$numberOfStopLesson=$u;};
                    }
                }
                $numOfLessonsOnCombination = (abs($numberOfStopLesson - $numberOfStartLesson))+1;
                $numOfLessonsOnEachCombination[]=$numOfLessonsOnCombination;

                $frozenDatesOfStudentOnEachCombination[] = $this->getFrozenDates($id,$teacher,$timetable,$level_start);
            }
//            return $level;
            $dataMain['numOfLessonsOnEachCombination'] = $numOfLessonsOnEachCombination;
            $dataMain['frozenDatesOfStudentOnEachCombination'] = $frozenDatesOfStudentOnEachCombination;
        }

        return $dataMain;

    }
    public function combinationDatesFittedToTimetable(){
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

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
    public function studentNameAndDates(){
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $id = $_POST["id"];
        $numberOfStartLesson = 0;
        $arrAll = array();

        $data = $this->getPersonStartStop($id,$teacher,$timetable,$level_start);
        $personStart = $data[0]['person_start'];
        $personStop = $data[0]['person_stop'];

        $combinationDates = $this->getCombinationDates($teacher,$timetable,$level_start);
        foreach($combinationDates[0] as $key=>$combinationDate){
            if($combinationDate == $personStart){$numberOfStartLesson = $key+1;}
        }

        $discount = $this->getDiscount($id,$teacher,$timetable,$level_start);

        $defaulCostOfOneLesson = $this->getDefaulCostOfOneLesson();
        $costOfOneLessonWithDiscount = $this->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);

        $datesOfVisit = $this->getDatesOfVisit($id,$teacher,$timetable,$level_start);

        $name = $this->getName($id);

        $arrAll['name']=$name;
        $arrAll['id']=$id;
        $arrAll['person_start']=$personStart;
        $arrAll['person_stop']=$personStop;
        $arrAll['numberOfStartLesson']=$numberOfStartLesson;
        $arrAll['combinationDates']=$combinationDates;
        $arrAll['datesOfVisit'] = $datesOfVisit;
        $arrAll['CostOfOneLessonWithDiscount'] = $costOfOneLessonWithDiscount;

        return $arrAll;
    }
    public function changeFrozenDate(){
        $isPayed = $_POST["isPayed"];
        $isFrozen = $_POST["isFrozen"];
        $id = $_POST["id"];
        $date = $_POST["date"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        $costOfOneLessonWithDiscount= $_POST["costOfOneLessonWithDiscount"];

        if($isPayed=="true"){
            $this->setUpdateIncreseBalance($costOfOneLessonWithDiscount,$id);
            $this->setInsertFrozenDateToDb($date,$id,$teacher,$timetable,$level_start);
            $this->setUpdateDecreseNumPayedNumReserved($id,$teacher,$timetable,$level_start);
        }
        else if($isFrozen=="true"){
            $frozenDateId = $this->getFrozenDateId($date,$id,$teacher,$timetable,$level_start);
            $this->setDeleteFrozenDate($frozenDateId[0]);
            $this->setUpdateIncreseNumReserved($id,$teacher,$timetable,$level_start);
        }
        else if($isPayed=="false" and $isFrozen=="false"){
            $this->setInsertFrozenDateToDb($date,$id,$teacher,$timetable,$level_start);
            $this->setUpdateDecreseNumReserved($id,$teacher,$timetable,$level_start);
        }

    }

    ////////////////////////////////////  GETTERS/SETTERS   ////////////////////////////////////

    public function getPersonStartStop($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `person_start`,`person_stop` FROM `levels_person` WHERE id_person=".$id." AND levels_person.teacher='".$teacher."' AND levels_person.timetable='".$timetable."' AND levels_person.level_start='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
//        if(isset($data[0]['person_start'])){$personStart = $data[0]['person_start'];}
        return $data;
    }
    public function getAllCombinationsOfThisPerson($id){
        $db = $this->db;
        $sql="SELECT `teacher`,`timetable`,`level_start`,`level` FROM `levels_person` WHERE id_person=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getName($id){
        $db = $this->db;
        $sql = "SELECT `fio` FROM `main` WHERE `id`='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        $name = $data[0]['fio'];
        return $name;
    }
    public function getSumGiven($id){
        $db = $this->db;
        $sql = "SELECT `given` FROM `payment_has` WHERE `fio_id`=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_NUM);
        $sum = 0;
        foreach($data as $value){
            $sum = $sum + $value[0];
        }
        return $sum;
    }
    public function getCombinationDates($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate;
    }
    public function getFrozenDates($id,$teacher,$timetable,$level_start){
        $db = $this->db;
//        $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher= AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND freeze.id_person=" . $id;
        $sql = "SELECT `frozen_day` FROM `freeze` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "'  AND `timetable`='" . $timetable . "'  AND `id_person`=" . $id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }

    public function getAllCombinations($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT * FROM `levels` WHERE `sd_1`='".$level_start."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    public function getDiscount($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `discount` FROM `discounts` WHERE `id_person`='".$id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['discount'])){$discount = $data[0]['discount'];}else{$discount = 0;}
        return $discount;
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
    public function getDatesOfVisit($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND `id_person`=" . $id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getFrozenDateId($date,$id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `id` FROM `freeze` WHERE `frozen_day`='".$date."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
//        if(isset($data)){return $data;}else{return 777;}
        return $data;
    }
    public function setInsertFrozenDateToDb($frozenDate,$id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "INSERT INTO `freeze` (frozen_day,id_person,teacher,timetable,level_start) VALUES(:frozenDate,:id,:teacher,:timetable,:level_start)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':frozenDate', $frozenDate, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'insert';
        return $data;
    }
    public function setUpdateDecreseNumPayedNumReserved($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "UPDATE `payed_lessons` SET `num_reserved`=`num_reserved`-1,`num_payed`=`num_payed`-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateIncreseBalance($costOfOneLessonWithDiscount,$id){
        $db = $this->db;
        $sql = "UPDATE `balance` SET `balance`=balance+:costOfOneLessonWithDiscount WHERE `id_person`=:id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':costOfOneLessonWithDiscount', $costOfOneLessonWithDiscount, \PDO::PARAM_INT );
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setDeleteFrozenDate($frozenDateId){
        $db = $this->db;
        $sql = "DELETE FROM `freeze` WHERE `id`=:existedFrozenDateId";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':existedFrozenDateId', $frozenDateId, \PDO::PARAM_INT );
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setUpdateIncreseNumReserved($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved+1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateDecreseNumReserved($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
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