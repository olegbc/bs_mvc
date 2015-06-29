<?php
namespace application\models;
class ModelPerson extends \application\core\model
{
    public function __construct(){
        parent::__construct();
    }
	public function get_data(){
	}

	public function nameSumCombinationsFrozenDatesBalance(){
        $gettersSetters = $this->gettersSetters;
        $id=$_POST['id'];
        $name = $gettersSetters->getName($id);
        $sum = $gettersSetters->getSumGiven($id);
        $allCombinationsOfThisPerson = $gettersSetters->getAllCombinationsOfThisPerson($id);
        $intensive = false;

        if(count($allCombinationsOfThisPerson)){
            foreach($allCombinationsOfThisPerson as $key=>$value){
                $timetable = 'undefined';
                extract($value);
                $frozenDatesOfStudent[] = $gettersSetters->getFrozenDates($id, $teacher, $timetable, $level_start, $intensive);
            }
        }

        $balance = $gettersSetters->getBalance($id);
		$mainArr = array();

		$mainArr['name']=$name;
		if($intensive){$mainArr['intensive']=$intensive;}
		$mainArr['sum']=$sum;
		if($allCombinationsOfThisPerson){$mainArr['allCombinationsOfThisPerson']=$allCombinationsOfThisPerson;}else{$mainArr['allCombinationsOfThisPerson']=0;}
		if(isset($frozenDatesOfStudent)){$mainArr['frozenDatesOfStudent']=$frozenDatesOfStudent;}else{$mainArr['frozenDatesOfStudent']=0;}
		$mainArr['balance']=$balance;

		return $mainArr;
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

        $defaulCostOfOneLesson = $gettersSetters->getDefaulCostOfOneLesson($intensive);
        $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);

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
    public function numPayedNumReservedCostOfOneLessonWithDiscount($id=null,$teacher=null,$timetable=null,$level_start=null,$intensive=null){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST["teacher"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
        $level_start = $_POST["level_start"];
        $intensive = false;
//        if(isset($_POST["intensive"])){$intensive = $_POST["intensive"];if($intensive == 'intensive'){ $intensive = 1;}}
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
        $id = $_POST["id"];
        $arr = array();

        $NumPayedNumReserved = $gettersSetters->getNumPayedNumReserved($id,$teacher,$timetable,$level_start,$intensive);
        $NumPayed = $NumPayedNumReserved[0]['num_payed'];
        $NumReserved = $NumPayedNumReserved[0]['num_reserved'];

        $arr['num_payed']=$NumPayed;
        $arr['num_reserved']=$NumReserved;

        $discount = $gettersSetters->getDiscount($id,$teacher,$timetable,$level_start,$intensive);

        if($intensive){
            $IdOfFirstTenStudents = $gettersSetters->getIdOfFirstTenStudents();
            $isFirstTenStudent = $gettersSetters->getIsItOneOfFirstTenStudents($IdOfFirstTenStudents, $id);
            $defaulCostOfOneLesson = $gettersSetters->getDefaulCostOfOneLesson($intensive, $isFirstTenStudent);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);
            $arr['CostOfOneLessonWithDiscount']=$costOfOneLessonWithDiscount;
        }else {
            $defaulCostOfOneLesson = $gettersSetters->getDefaulCostOfOneLesson($intensive, false);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount, $defaulCostOfOneLesson);
            $arr['CostOfOneLessonWithDiscount'] = $costOfOneLessonWithDiscount;
        }

        return $arr;
    }
    public function addOrRemovePayedDate(){
        $gettersSetters = $this->gettersSetters;
        $id = $_POST["id"];
        $addOrRemove = $_POST["addOrRemove"];
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

        if($intensive){
            $discount = $gettersSetters->getDiscount($id,$teacher,'undefined',$level_start,$intensive);
        }else{
            $discount = $gettersSetters->getDiscount($id,$teacher,$timetable,$level_start,$intensive);
        }

        $defaulCostOfOneLesson = $gettersSetters->getDefaulCostOfOneLesson($intensive);
        $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);
        $arr['CostOfOneLessonWithDiscount'] = $costOfOneLessonWithDiscount;

        $balance = $gettersSetters->getBalance($id);

        $NumPayedNumReserved = $gettersSetters->getNumPayedNumReserved($id,$teacher,$timetable,$level_start,$intensive);
        $NumPayed = $NumPayedNumReserved[0]['num_payed'];
        $NumReserved = $NumPayedNumReserved[0]['num_reserved'];

        if($addOrRemove>0){
            //	BALANCE DECREASE + PAYED LESSONS INCREASE
            if($balance>=$costOfOneLessonWithDiscount){
                $balanceUpdate = $balance - $costOfOneLessonWithDiscount;
                if($NumPayed<$NumReserved){
                    $gettersSetters->setUpdateBalance($balanceUpdate,$id);
                    $num_payedUpdate = $NumPayed+1;
                    if($intensive){
                        $gettersSetters->setUpdateNumPayed($num_payedUpdate,$id,$teacher,'undefined',$level_start,$intensive);
                    }else{
                        $gettersSetters->setUpdateNumPayed($num_payedUpdate,$id,$teacher,$timetable,$level_start,$intensive);
                    }
                }
            }
        }elseif($addOrRemove<0){
            //	BALANCE INCREASE + PAYED LESSONS DECREASE
            $balanceUpdate = $balance + $costOfOneLessonWithDiscount;

            if($NumPayed>=1){
                $gettersSetters->setUpdateBalance($balanceUpdate,$id);
                $num_payedUpdate = $NumPayed-1;
                if($intensive){
                    $gettersSetters->setUpdateNumPayed($num_payedUpdate, $id, $teacher, 'undefined', $level_start, $intensive);
                }else {
                    $gettersSetters->setUpdateNumPayed($num_payedUpdate, $id, $teacher, $timetable, $level_start, $intensive);
                }
            }
        }
    }
    public function areAnyPayedOrAttenedOrFrozenLessonsExist(){
        $gettersSetters = $this->gettersSetters;
        $payedLessonExists=0;
        $attendedLessonExists=0;
        $frozenLessonExists=0;
        $id = $_POST['id'];
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


        $data = $gettersSetters->numPayedNumReservedCostOfOneLessonWithDiscount($id,$teacher,$timetable,$level_start,$intensive);
        if($data['num_payed']!=0){ $payedLessonExists=1; }

        $arr[] = $payedLessonExists;

        $data = $gettersSetters->getCheckForAnyAttendedLessonOfPerson($id,$teacher,$timetable,$level_start,$intensive);
        if(isset($data['id'])){$attendedLessonExists=1;}

        $arr[] = $attendedLessonExists;

        $data = $gettersSetters->getCheckForAnyFrozenDates($id,$teacher,$timetable,$level_start,$intensive);
        if(!empty($data)){$frozenLessonExists=1;}

        $arr[] = $frozenLessonExists;

//        return $arr;

        if($payedLessonExists==1 or $attendedLessonExists==1 or $frozenLessonExists==1){return true;}else{return false;}
    }
    public function removePersonOnThisCombinationFromLevelsPersonAndPayedLessons(){
        $gettersSetters = $this->gettersSetters;
        $id = $_POST["id"];
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
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

        $gettersSetters->setDeletePersonOnThisCombinationFromLevelsPerson($teacher,$timetable,$level_start,$id,$intensive);
        $gettersSetters->setDeletePersonOnThisCombinationFromPayedLessons($teacher,$timetable,$level_start,$id,$intensive);
    }
    public function personDiscountReason(){
        $gettersSetters = $this->gettersSetters;
        $id = $_POST["id"];
        $teacher = $_POST["teacher"];
        $level_start = $_POST["level_start"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
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

        $data = $gettersSetters->getPersonDiscountReason($id,$teacher,$timetable,$level_start,$intensive);
        return $data;
    }
    public function addDiscount(){
        $gettersSetters = $this->gettersSetters;
        $teacher=$_POST["teacher"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
        $level_start=$_POST["level_start"];
        $discountValue=$_POST["discountValue"];
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

        $id=$_POST["id"];

        $data = $gettersSetters->getIsThereDiscountForThisCombinationAndThisStudent($id,$teacher,$timetable,$level_start,$intensive);

        if($data){
            $gettersSetters->setUpdateDiscont($discountValue,$teacher,$timetable,$level_start,$id,$intensive);
        }else{
            $gettersSetters->setInsertDiscont($discountValue,$teacher,$timetable,$level_start,$id,$intensive);
        }
    }
    public function addDiscountReason(){
        $gettersSetters = $this->gettersSetters;
        $teacher=$_POST["teacher"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
        $level_start=$_POST["level_start"];
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

        $id=$_POST["id"];
        $discountReason=$_POST["reason"];

        $data = $gettersSetters->getIsThereDiscountReasonForThisCombinationAndThisStudent($id,$teacher,$timetable,$level_start,$intensive);

        if($data){
            $gettersSetters->setUpdateDiscontReason($discountReason,$teacher,$timetable,$level_start,$id,$intensive);
        }else{
            $gettersSetters->setInsertDiscontReason($discountReason,$teacher,$timetable,$level_start,$id,$intensive);
        }
    }

    /*
    /////////////////////////////////////////////////////////   GETTERS/SETTERS   /////////////////////////////////////////////////////////
/*
    public function getDiscount($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `discount` FROM `discounts` WHERE `id_person`='" . $id . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'";
        }else{
            $sql = "SELECT `discount` FROM `discounts` WHERE `id_person`='" . $id . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['discount'])){$discount = $data[0]['discount'];}else{$discount = 0;}
        return $discount;
    }
    public function getIdOfFirstTenStudents()
    {
        $db = $this->db;
        $sql = "SELECT `id_person` FROM `levels_person` WHERE `intensive` = 1 ORDER BY `created` LIMIT 10";
        $id_person = $db->query($sql);
        $id_person = $id_person->fetchAll($db::FETCH_COLUMN);
        return $id_person;
    }
    public function getIsItOneOfFirstTenStudents($IdOfFirstTenStudents,$id_person)
    {
        $isItOneOfTheTen = false;
        foreach($IdOfFirstTenStudents as $idCheck){
            if($idCheck == $id_person){$isItOneOfTheTen = true;}
        }
        return $isItOneOfTheTen;
    }
    public function getDefaulCostOfOneLesson($intensive=null,$isFirstTenStudent=null)
    {
        $db = $this->db;
        if($intensive){
            if($isFirstTenStudent){
                $sql = "SELECT `one intensive super` FROM `constants`";
            }else {
                $sql = "SELECT `one intensive default` FROM `constants`";
            }
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
        if(isset($data[0]['one intensive super'])) {
            $defaulCostOfOneLesson = $data[0]['one intensive super'];
        }
        return $defaulCostOfOneLesson;
    }
    public function getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson){
        $CostOfOneLessonWithDiscount = $defaulCostOfOneLesson - round(($defaulCostOfOneLesson*($discount*0.01)),2);
        $arr['CostOfOneLessonWithDiscount'] = $CostOfOneLessonWithDiscount;
        return $CostOfOneLessonWithDiscount;
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
    public function getAllCombinationsOfThisPerson($id){
        $db = $this->db;
        $sql="SELECT `teacher`,`timetable`,`level_start`,`level`,`intensive`,`created` FROM `levels_person` WHERE id_person=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getBalance($id){
        $db = $this->db;
        $sql="SELECT `balance` FROM `balance` WHERE id_person=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['balance'])){$balance = $data[0]['balance'];}else{$balance = 0;}
        return $balance;
    }
    public function getFrozenDates($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
//        if($intensive){
//            $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.intensive='" . $intensive . "' AND freeze.id_person=" . $id;
//        }else{
//            $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND freeze.id_person=" . $id;
//        }
        if($intensive){
            $sql = "SELECT `frozen_day` FROM `freeze` WHERE `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'  AND `timetable`='" . $timetable . "'  AND `id_person`=" . $id;
        }else{
            $sql = "SELECT `frozen_day` FROM `freeze` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "'  AND `timetable`='" . $timetable . "'  AND `id_person`=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getPersonDiscountReason($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `discount`,`reason` FROM `discounts` WHERE `id_person`='" . $id . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'";
        }else {
            $sql = "SELECT `discount`,`reason` FROM `discounts` WHERE `id_person`='" . $id . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data['0'])){
            return $data['0'];
        }else{
            return $data;
        }
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
    public function getDatesOfVisit($id,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.intensive='" . $intensive . "' AND `id_person`=" . $id;
        }else {
            $sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND `id_person`=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
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
    public function getNumPayedNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='" . $id . "' AND `teacher` = '" . $teacher . "' AND `level_start` = '" . $level_start . "' AND `intensive` ='" . $intensive . "'";
        }else {
            $sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='" . $id . "' AND `teacher` = '" . $teacher . "' AND `level_start` = '" . $level_start . "' AND `timetable` ='" . $timetable . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getCheckForAnyAttendedLessonOfPerson($id,$teacher=null,$timetable,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT DISTINCT `id` FROM `attendance` WHERE `teacher`='".$teacher."' AND `intensive`='".$intensive."' AND `level_start`='".$level_start."' AND `id_visit`='".$id."' LIMIT 1";
        }else {
            $sql = "SELECT DISTINCT `id` FROM `attendance` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_visit`='".$id."' LIMIT 1";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0])){return $data[0];}else{return;}
    }
    public function getCheckForAnyFrozenDates($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `id` FROM `freeze` WHERE teacher='" . $teacher . "' AND level_start='" . $level_start . "' AND intensive='" . $intensive . "' AND id_person=" . $id;
        }else {
            $sql = "SELECT `id` FROM `freeze` WHERE teacher='" . $teacher . "' AND level_start='" . $level_start . "' AND timetable='" . $timetable . "' AND id_person=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getIsThereDiscountForThisCombinationAndThisStudent($id,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql="SELECT `discount` FROM `discounts` WHERE `teacher`='".$teacher."' AND `intensive`='".$intensive."' AND `level_start`='".$level_start."' AND `id_person`='".$id."'";
        }else{
            $sql="SELECT `discount` FROM `discounts` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`='".$id."'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}
    }
    public function getIsThereDiscountReasonForThisCombinationAndThisStudent($id,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `reason` FROM `discounts` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' AND `id_person`='" . $id . "'";
        }else {
            $sql = "SELECT `reason` FROM `discounts` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' AND `id_person`='" . $id . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}
    }

    public function setUpdateBalance($balanceUpdate,$id){
        $db = $this->db;
        $sql="UPDATE `balance` SET `balance`=:balanceUpdate WHERE `id_person`=:id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':balanceUpdate', $balanceUpdate, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateNumPayed($num_payedUpdate,$id,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql="UPDATE `payed_lessons` SET `num_payed`=:num_payedUpdate WHERE `id_person`=:id AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else{
            $sql="UPDATE `payed_lessons` SET `num_payed`=:num_payedUpdate WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_payedUpdate', $num_payedUpdate, \PDO::PARAM_INT);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateDiscont($discountValue,$teacher,$timetable,$level_start,$id,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `discounts` SET `discount`=:discountValue WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else{
            $sql = "UPDATE `discounts` SET `discount`=:discountValue WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':discountValue', $discountValue, \PDO::PARAM_INT);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setInsertDiscont($discountValue,$teacher,$timetable,$level_start,$id,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`intensive`,`level_start`,`discount`) VALUE (:id,:teacher,:intensive,:level_start,:discountValue)";
        }else {
            $sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`timetable`,`level_start`,`discount`) VALUE (:id,:teacher,:timetable,:level_start,:discountValue)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':discountValue', $discountValue, \PDO::PARAM_INT);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function setUpdateDiscontReason($discountReason,$teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `discounts` SET `reason`=:discountReason WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else{
            $sql = "UPDATE `discounts` SET `reason`=:discountReason WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':discountReason', $discountReason, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setInsertDiscontReason($discountReason,$teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`intensive`,`level_start`,`reason`) VALUE (:id,:teacher,:intensive,:level_start,:discountReason)";
        }else {
            $sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`timetable`,`level_start`,`reason`) VALUE (:id,:teacher,:timetable,:level_start,:discountReason)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':discountReason', $discountReason, \PDO::PARAM_INT);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function setDeletePersonOnThisCombinationFromLevelsPerson($teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "DELETE FROM `levels_person` WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else{
            $sql = "DELETE FROM `levels_person` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonOnThisCombinationFromPayedLessons($teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "DELETE FROM `payed_lessons` WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else {
            $sql = "DELETE FROM `payed_lessons` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
*/
}