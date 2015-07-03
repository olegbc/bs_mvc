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
        $gettersSetters = $this->gettersSetters;
        $id = $_POST['id'];
        $name = $gettersSetters->getName($id);
        $sum = $gettersSetters->getSumGiven($id);

        $dataMain['name'] = $name;
        $dataMain['sum'] = $sum;

        $data = $gettersSetters->getAllCombinationsOfThisPerson($id);
        if(!empty($data)){
            foreach($data as $key=>$value){
                $dataMain['combinations'][] = $value;
                $intensive = 0;
                extract($value);    // $level,$teacher,$timetable,$level_start,$intensive
                if($level){$dataMain['level'] = $level;}

//                $data = $this->getPersonStartStop($id,$teacher,$timetable,$level_start,$intensive);
                if($intensive){
                    $personStart = $gettersSetters->getPersonStart($id, $teacher, 'undefined', $level_start,$intensive);
                    $personStop = $gettersSetters->getPersonStop($id, $teacher, 'undefined', $level_start,$intensive);
                }else{
                    $personStart = $gettersSetters->getPersonStart($id, $teacher, $timetable, $level_start,$intensive);
                    $personStop = $gettersSetters->getPersonStop($id, $teacher, $timetable, $level_start,$intensive);
                }
//                $data[0]['person_start'];
//                $personStop= $data[0]['person_stop'];

                $everyLessonDate = $gettersSetters->getCombinationDates($teacher,$timetable,$level_start,$intensive);

                foreach($everyLessonDate as $key=>$value){
                    for($u=0;$u<count($everyLessonDate[0]);$u++){
                        if($value[$u] == $personStart){$numberOfStartLesson=$u;};
                        if($value[$u] == $personStop){$numberOfStopLesson=$u;};
                    }
                }
                $numOfLessonsOnCombination = (abs($numberOfStopLesson - $numberOfStartLesson))+1;
                $numOfLessonsOnEachCombination[]=$numOfLessonsOnCombination;

                $frozenDatesOfStudentOnEachCombination[] = $gettersSetters->getFrozenDates($id,$teacher,$timetable,$level_start,$intensive);
            }
//            return $level;
            $dataMain['numOfLessonsOnEachCombination'] = $numOfLessonsOnEachCombination;
            $dataMain['frozenDatesOfStudentOnEachCombination'] = $frozenDatesOfStudentOnEachCombination;
        }

        return $dataMain;

    }
    public function combinationDatesFittedToTimetable(){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST["teacher"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
        $level_start = $_POST["level_start"];
        $intensive = false;
        if(isset($_POST['intensive'])) {
            $intensive = $_POST["intensive"];
            if ($_POST["intensive"] == 'false') {
                $intensive = false;
            }
            if ($_POST["intensive"] == 'true') {
                $intensive = true;
            }
            if ($_POST["intensive"] == '0') {
                $intensive = false;
            }
            if ($_POST["intensive"] == '1') {
                $intensive = true;
            }
        }

        $start = strtotime($level_start);

//        if($timetable == "ПУ" or $timetable == "ПД" or $timetable == "ПВ"){
//            $first_week_lesson=1;
//            $second_week_lesson=3;
//            $third_week_lesson=5;
//        }
//        if($timetable == "ВУ" or $timetable == "ВД" or $timetable == "ВВ"){
//            $first_week_lesson=2;
//            $second_week_lesson=4;
//            $third_week_lesson=6;
//        }
//
//        if(date("N",$start)== $first_week_lesson or date("N",$start)== $second_week_lesson or date("N",$start)== $third_week_lesson){
//            $data = $this->getAllCombinations($teacher,$timetable,$level_start);
//            return $data;
//        }else{
//            echo "Дата старта уровня не соответствует расписанию";
//        }
        if($timetable){
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
                $data = $gettersSetters->getAllCombinations($teacher,$timetable,$level_start);
                return $data;
            }else{
                echo "Дата старта уровня не соответствует расписанию";
            }
        }
        if($intensive){
            $data = $gettersSetters->getAllCombinations($teacher,'undefined',$level_start,$intensive);
            return $data;
        }
    }
    public function studentNameAndDates(){
        $gettersSetters = $this->gettersSetters;
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
        $id = $_POST["id"];
        $intensive = false;
        if(isset($_POST['intensive'])) {
            $intensive = $_POST["intensive"];
            if ($_POST["intensive"] == 'false') {
                $intensive = false;
            }
            if ($_POST["intensive"] == 'true') {
                $intensive = true;
            }
            if ($_POST["intensive"] == '0') {
                $intensive = false;
            }
            if ($_POST["intensive"] == '1') {
                $intensive = true;
            }
        }
        $numberOfStartLesson = 0;
        $arrAll = array();

        if($intensive){
            $personStart = $gettersSetters->getPersonStart($id, $teacher, 'undefined', $level_start, $intensive);
            $personStop = $gettersSetters->getPersonStop($id, $teacher, 'undefined', $level_start, $intensive);
        }else{
            $personStart = $gettersSetters->getPersonStart($id, $teacher, $timetable, $level_start, $intensive);
            $personStop = $gettersSetters->getPersonStop($id, $teacher, $timetable, $level_start, $intensive);
        }

        if($intensive){
            $combinationDates = $gettersSetters->getCombinationDates($teacher,'undefined',$level_start,$intensive);
        }else{
            $combinationDates = $gettersSetters->getCombinationDates($teacher,$timetable,$level_start,$intensive);
        }
//        $combinationDates = $this->getCombinationDates($teacher,$timetable,$level_start,$intensive);
        foreach($combinationDates[0] as $key=>$combinationDate){
            if($combinationDate == $personStart){$numberOfStartLesson = $key+1;}
        }

        if($intensive){
            $discount = $gettersSetters->getDiscount($id,$teacher,'undefined',$level_start,$intensive);
        }else{
            $discount = $gettersSetters->getDiscount($id,$teacher,$timetable,$level_start,$intensive);
        }

        $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive);
        $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaultCostOfOneLesson);

        if($intensive){
            $datesOfVisit = $gettersSetters->getDatesOfVisit($id, $teacher, 'undefined', $level_start,$intensive);
        }else{
            $datesOfVisit = $gettersSetters->getDatesOfVisit($id, $teacher, $timetable, $level_start,$intensive);
        }

        $name = $gettersSetters->getName($id);

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
//    public function studentNameAndDates(){
//        $level_start = $_POST["level_start"];
//        $teacher = $_POST["teacher"];
//        $timetable = $_POST["timetable"];
//        $id = $_POST["id"];
//        $numberOfStartLesson = 0;
//        $arrAll = array();
//
//        $data = $this->getPersonStartStop($id,$teacher,$timetable,$level_start);
//        $personStart = $data[0]['person_start'];
//        $personStop = $data[0]['person_stop'];
//
//        $combinationDates = $this->getCombinationDates($teacher,$timetable,$level_start);
//        foreach($combinationDates[0] as $key=>$combinationDate){
//            if($combinationDate == $personStart){$numberOfStartLesson = $key+1;}
//        }
//
//        $discount = $this->getDiscount($id,$teacher,$timetable,$level_start);
//
//        $defaulCostOfOneLesson = $this->getDefaulCostOfOneLesson();
//        $costOfOneLessonWithDiscount = $this->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);
//
//        $datesOfVisit = $this->getDatesOfVisit($id,$teacher,$timetable,$level_start);
//
//        $name = $this->getName($id);
//
//        $arrAll['name']=$name;
//        $arrAll['id']=$id;
//        $arrAll['person_start']=$personStart;
//        $arrAll['person_stop']=$personStop;
//        $arrAll['numberOfStartLesson']=$numberOfStartLesson;
//        $arrAll['combinationDates']=$combinationDates;
//        $arrAll['datesOfVisit'] = $datesOfVisit;
//        $arrAll['CostOfOneLessonWithDiscount'] = $costOfOneLessonWithDiscount;
//
//        return $arrAll;
//    }
    public function changeFrozenDate(){
        $gettersSetters = $this->gettersSetters;
        $isPayed = $_POST["isPayed"];
        $isFrozen = $_POST["isFrozen"];
        $id = $_POST["id"];
        $date = $_POST["date"];
        $teacher = $_POST["teacher"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
        $level_start = $_POST["level_start"];
        $intensive = false;
        if(isset($_POST['intensive'])) {
            $intensive = $_POST["intensive"];
            if ($_POST["intensive"] == 'false') {
                $intensive = false;
            }
            if ($_POST["intensive"] == 'true') {
                $intensive = true;
            }
            if ($_POST["intensive"] == '0') {
                $intensive = false;
            }
            if ($_POST["intensive"] == '1') {
                $intensive = true;
            }
        }
        $costOfOneLessonWithDiscount = $_POST["costOfOneLessonWithDiscount"];

        if($isPayed=="true"){
            $gettersSetters->setUpdateIncreseBalance($costOfOneLessonWithDiscount,$id);
            $gettersSetters->setInsertFrozenDateToDb($date,$id,$teacher,$timetable,$level_start,$intensive);
            $gettersSetters->setUpdateDecreseNumPayedNumReserved($id,$teacher,$timetable,$level_start,$intensive);
        }
        else if($isFrozen=="true"){
            $frozenDateId = $gettersSetters->getFrozenDateId($date,$id,$teacher,$timetable,$level_start,$intensive);
            $gettersSetters->setDeleteFrozenDate($frozenDateId[0]);
            $gettersSetters->setUpdateIncreseNumReserved($id,$teacher,$timetable,$level_start,$intensive);
        }
        else if($isPayed=="false" and $isFrozen=="false"){
            $gettersSetters->setInsertFrozenDateToDb($date,$id,$teacher,$timetable,$level_start,$intensive);
            $gettersSetters->setUpdateDecreseNumReserved($id,$teacher,$timetable,$level_start,$intensive);
        }

    }

    ////////////////////////////////////  GETTERS/SETTERS   ////////////////////////////////////
    /*
    public function getPersonStart($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `person_start` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND level_start='" . $level_start . "'";
        }else{
            $sql = "SELECT `person_start` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND level_start='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['person_start'])){$personStart = $data[0]['person_start'];}
        return $personStart;
    }
    public function getPersonStop($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `person_stop` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND level_start='" . $level_start . "'";
        }else {
            $sql = "SELECT `person_stop` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND level_start='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['person_stop'])){$personStop = $data[0]['person_stop'];}
        return $personStop;
    }
    public function getCombinationDates($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10 FROM `levels` WHERE teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND sd_1='" . $level_start . "'";
        }else {
            $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND sd_1='" . $level_start . "'";
        }
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate;
    }
    public function getAllCombinations($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT * FROM `levels` WHERE `sd_1`='" . $level_start . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "'";
        }else{
            $sql = "SELECT * FROM `levels` WHERE `sd_1`='" . $level_start . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    */
//    public function getPersonStartStop($id,$teacher,$timetable,$level_start){
//        $db = $this->db;
//        $sql="SELECT `person_start`,`person_stop` FROM `levels_person` WHERE id_person=".$id." AND levels_person.teacher='".$teacher."' AND levels_person.timetable='".$timetable."' AND levels_person.level_start='".$level_start."'";
//        $data = $db->query($sql);
//        $data = $data->fetchAll($db::FETCH_ASSOC);
////        if(isset($data[0]['person_start'])){$personStart = $data[0]['person_start'];}
//        return $data;
//    }
/*
    public function getAllCombinationsOfThisPerson($id){
        $db = $this->db;
        $sql="SELECT `teacher`,`timetable`,`level_start`,`level`,`intensive` FROM `levels_person` WHERE id_person=".$id;
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
    */
//    public function getCombinationDates($teacher,$timetable,$level_start){
//        $db = $this->db;
//        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
//        $everyLessonDate = $db->query($sql);
//        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
//        return $everyLessonDate;
//    }
//    public function getFrozenDates($id,$teacher,$timetable,$level_start){
//        $db = $this->db;
////        $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher= AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND freeze.id_person=" . $id;
//        $sql = "SELECT `frozen_day` FROM `freeze` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "'  AND `timetable`='" . $timetable . "'  AND `id_person`=" . $id;
//        $data = $db->query($sql);
//        $data = $data->fetchAll($db::FETCH_COLUMN);
//        return $data;
//    }
/*
    public function getFrozenDates($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `frozen_day` FROM `freeze` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "'  AND `intensive`='" . $intensive . "'  AND `id_person`=" . $id;
        }else{
            $sql = "SELECT `frozen_day` FROM `freeze` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "'  AND `timetable`='" . $timetable . "'  AND `id_person`=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    */
//    public function getAllCombinations($teacher,$timetable,$level_start){
//        $db = $this->db;
//        $sql = "SELECT * FROM `levels` WHERE `sd_1`='".$level_start."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."'";
//        $data = $db->query($sql);
//        $data = $data->fetchAll($db::FETCH_ASSOC);
//        return $data[0];
//    }
   /* public function getDiscount($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `discount` FROM `discounts` WHERE `id_person`='".$id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['discount'])){$discount = $data[0]['discount'];}else{$discount = 0;}
        return $discount;
    }*/
   /* public function getDefaulCostOfOneLesson($intensive=null)
    {
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `one intensive default` FROM `constants`";
        }else {
            $sql = "SELECT `one lesson default` FROM `constants`";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['one lesson default'])) {
            $defaulCostOfOneLesson = $data[0]['one lesson default'];
        }
        if(isset($data[0]['one intensive default'])) {
            $defaulCostOfOneLesson = $data[0]['one intensive default'];
        }
        return $defaulCostOfOneLesson;
    } */
//    public function getDefaulCostOfOneLesson()
//    {
//        $db = $this->db;
//        $sql = "SELECT `one lesson default` FROM `constants`";
//        $data = $db->query($sql);
//        $data = $data->fetchAll($db::FETCH_ASSOC);
//        if (isset($data[0]['one lesson default'])) {
//            $defaulCostOfOneLesson = $data[0]['one lesson default'];
//        }
//        return $defaulCostOfOneLesson;
//    }
    /*public function getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson){
        $CostOfOneLessonWithDiscount = $defaulCostOfOneLesson - round(($defaulCostOfOneLesson*($discount*0.01)),2);
        $arr['CostOfOneLessonWithDiscount'] = $CostOfOneLessonWithDiscount;
        return $CostOfOneLessonWithDiscount;
    }*/
   /* public function getDatesOfVisit($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND `id_person`=" . $id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }*/
   /* public function getFrozenDateId($date,$id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `id` FROM `freeze` WHERE `frozen_day`='" . $date . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' AND `id_person`='" . $id . "'";
        }else {
            $sql = "SELECT `id` FROM `freeze` WHERE `frozen_day`='" . $date . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' AND `id_person`='" . $id . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function setInsertFrozenDateToDb($frozenDate,$id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `freeze` (frozen_day,id_person,teacher,intensive,level_start) VALUES(:frozenDate,:id,:teacher,:intensive,:level_start)";
        }else{
            $sql = "INSERT INTO `freeze` (frozen_day,id_person,teacher,timetable,level_start) VALUES(:frozenDate,:id,:teacher,:timetable,:level_start)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':frozenDate', $frozenDate, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'insert';
        return $data;
    }
    public function setUpdateDecreseNumPayedNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=`num_reserved`-1,`num_payed`=`num_payed`-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else{
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=`num_reserved`-1,`num_payed`=`num_payed`-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
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
    public function setUpdateIncreseNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved+1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else{
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved+1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateDecreseNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else{
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
*/

}