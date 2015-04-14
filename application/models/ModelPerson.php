<?php
namespace application\models;
class ModelPerson extends \application\core\model
{
    public function __construct(){
        parent::__construct();
    }
	public function get_data(){
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
    public function getCombinationDates($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate;
    }
    public function getPersonStart($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `person_start` FROM `levels_person` WHERE id_person=".$id." AND levels_person.teacher='".$teacher."' AND levels_person.timetable='".$timetable."' AND levels_person.level_start='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['person_start'])){$personStart = $data[0]['person_start'];}
        return $personStart;
    }
    public function getPersonStop($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `person_stop` FROM `levels_person` WHERE id_person=".$id." AND levels_person.teacher='".$teacher."' AND levels_person.timetable='".$timetable."' AND levels_person.level_start='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['person_stop'])){$personStop = $data[0]['person_stop'];}
        return $personStop;
    }
    public function getAllCombinationsOfThisPerson($id){
        $db = $this->db;
        $sql="SELECT `teacher`,`timetable`,`level_start`,`level` FROM `levels_person` WHERE id_person=".$id;
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
    public function getFrozenDates($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher='".$teacher."' AND levels_person.level_start='".$level_start."' AND levels_person.timetable='".$timetable."' AND freeze.id_person=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getPersonDiscountReason(){
        $db = $this->db;
        $sql="SELECT `discount`,`reason` FROM `discounts` WHERE `id_person`=".$_POST["id"]." AND `teacher`=".$_POST["teacher"]." AND `timetable`=".$_POST["timetable"]." AND `level_start`=".$_POST["level_start"];
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data['0'])){
            return $data['0'];
        }else{
            return $data;
        }
    }
    public function getAllCombinations($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT * FROM `levels` WHERE `sd_1`='".$level_start."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    public function getDatesOfVisit($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND `id_person`=" . $id;
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
    public function getNumPayedNumReserved($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='".$id."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND `timetable` ='".$timetable."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getCheckForAnyAttendedLessonOfPerson($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT DISTINCT `id` FROM `attendance` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_visit`='".$id."' LIMIT 1";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0])){return $data[0];}else{return;}
    }
    public function getCheckForAnyFrozenDates($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `id` FROM `freeze` WHERE teacher='".$teacher."' AND level_start='".$level_start."' AND timetable='".$timetable."' AND id_person=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }

    /////////////////////////////////////////////////////////   /GETTERS   /////////////////////////////////////////////////////////

	public function nameSumCombinationsFrozenDatesBalance(){
        $db = $this->db;
        $id=$_POST['id'];
        $numOfLessonsOnCombinationArr = array();

        $name = $this->getName($id);

        $sum = $this->getSumGiven($id);

        $allCombinationsOfThisPerson = $this->getAllCombinationsOfThisPerson($id);

        if(count($allCombinationsOfThisPerson)){
            foreach($allCombinationsOfThisPerson as $key=>$value){
                extract($value); // $teacher,$timetable,$level_start
                $personStart = $this->getPersonStart($id,$teacher,$timetable,$level_start);
                $personStop = $this->getPersonStop($id,$teacher,$timetable,$level_start);

                $everyLessonDate = $this->getCombinationDates($teacher,$timetable,$level_start);
                foreach($everyLessonDate as $key=>$value){
                    for($u=0;$u<21;$u++){
                        if($value[$u] == $personStart){$numberOfStartLesson=$u;};
                        if($value[$u] == $personStop){$numberOfStopLesson=$u;};
                    }
                }
                $numOfLessonsOnCombination = (abs($numberOfStopLesson - $numberOfStartLesson))+1;
                $numOfLessonsOnCombinationArr[]=$numOfLessonsOnCombination;

                $frozenDatesOfStudent[] = $this->getFrozenDates($id,$teacher,$timetable,$level_start);
            }
        }

        $balance = $this->getBalance($id);

		$mainArr = array();

		$mainArr['name']=$name;
		$mainArr['sum']=$sum;
		if($allCombinationsOfThisPerson){$mainArr['allCombinationsOfThisPerson']=$allCombinationsOfThisPerson;}else{$mainArr['allCombinationsOfThisPerson']=0;}
//		$mainArr[3]=$num_lessons_payed;
//		if($numOfLessonsOnCombinationArr){$mainArr[4]=$numOfLessonsOnCombinationArr;}else{$mainArr[4]=0;}
		if(isset($frozenDatesOfStudent)){$mainArr['frozenDatesOfStudent']=$frozenDatesOfStudent;}else{$mainArr['frozenDatesOfStudent']=0;}
		$mainArr['balance']=$balance;

		return $mainArr;
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

    public function studentNameAndDates(){
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $id = $_POST["id"];
        $numberOfStartLesson = 0;
        $arrAll = array();

        $personStart = $this->getPersonStart($id,$teacher,$timetable,$level_start);
        $personStop = $this->getPersonStop($id,$teacher,$timetable,$level_start);

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

    public function numPayedNumReservedCostOfOneLessonWithDiscount(){
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        $id = $_POST["id"];
        $arr = array();

        $NumPayedNumReserved = $this->getNumPayedNumReserved($id,$teacher,$timetable,$level_start);
        $NumPayed = $NumPayedNumReserved[0]['num_payed'];
        $NumReserved = $NumPayedNumReserved[0]['num_reserved'];
        $arr['num_payed']=$NumPayed;
        $arr['num_reserved']=$NumReserved;

        $discount = $this->getDiscount($id,$teacher,$timetable,$level_start);

        $defaulCostOfOneLesson = $this->getDefaulCostOfOneLesson();
        $costOfOneLessonWithDiscount = $this->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);
        $arr['CostOfOneLessonWithDiscount']=$costOfOneLessonWithDiscount;

        return $arr;
    }

    public function addOrRemovePayedDate(){
        $db = $this->db;
        $id = $_POST["id"];
        $addOrRemove = $_POST["addOrRemove"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $discount = $this->getDiscount($id,$teacher,$timetable,$level_start);

        $defaulCostOfOneLesson = $this->getDefaulCostOfOneLesson();
        $costOfOneLessonWithDiscount = $this->getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson);
        $arr['CostOfOneLessonWithDiscount'] = $costOfOneLessonWithDiscount;

        $balance = $this->getBalance($id);

        $NumPayedNumReserved = $this->getNumPayedNumReserved($id,$teacher,$timetable,$level_start);
        $NumPayed = $NumPayedNumReserved[0]['num_payed'];
        $NumReserved = $NumPayedNumReserved[0]['num_reserved'];

        if($addOrRemove>0){
            //	BALANCE DECREASE + PAYED LESSONS INCREASE
            if($balance>=$costOfOneLessonWithDiscount){
                $balanceDecreased = $balance - $costOfOneLessonWithDiscount;

                if($NumPayed<$NumReserved){
                    $sql="UPDATE `balance` SET `balance`=".$balanceDecreased." WHERE `id_person`=".$id;
                    $db->query($sql);
                    $num_payedIncreased = $NumPayed+1;
                    $sql="UPDATE `payed_lessons` SET `num_payed`=".$num_payedIncreased." WHERE `id_person`=".$id." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
                    $db->query($sql);
                }
            }
        }elseif($addOrRemove<0){
            //	BALANCE INCREASE + PAYED LESSONS DECREASE
            $balanceIncreased = $balance + $costOfOneLessonWithDiscount;

            if($NumPayed>=1){
                $sql="UPDATE `balance` SET `balance`=".$balanceIncreased." WHERE `id_person`=".$id;
                $db->query($sql);
                $num_payedDecreased = $NumPayed-1;
                $sql="UPDATE `payed_lessons` SET `num_payed`=".$num_payedDecreased." WHERE `id_person`=".$id." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
                $db->query($sql);
            }
        }
    }

    public function areAnyPayedOrAttenedOrFrozenLessonsExist(){
        $payedLessonExists=0;
        $attendedLessonExists=0;
        $frozenLessonExists=0;
        $id = $_POST['id'];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $data = $this->numPayedNumReservedCostOfOneLessonWithDiscount($id,$teacher,$timetable,$level_start);
        if($data['num_payed']!=0){ $payedLessonExists=1; }

        $data = $this->getCheckForAnyAttendedLessonOfPerson($id,$teacher,$timetable,$level_start);
        if(isset($data['id'])){$attendedLessonExists=1;}

        $data = $this->getCheckForAnyFrozenDates($id,$teacher,$timetable,$level_start);
        if(!empty($data)){$frozenLessonExists=1;}

        if($payedLessonExists==1 or $attendedLessonExists==1 or $frozenLessonExists==1){return false;}else{return true;}
    }

    public function removePersonComboPayedLessonsFrozenLessons(){
        $id = $_POST["id"];
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];

        $db = $this->db;
        $sql = "DELETE FROM `levels_person` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id;
        $db->query($sql);

        $sql = "DELETE FROM `payed_lessons` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id;
        $db->query($sql);

        $sql = "DELETE FROM `freeze` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`=".$id;
        $db->query($sql);
    }

}