<?php
namespace application\models;
class ModelPerson extends \application\core\model
{
    public function __construct(){
        parent::__construct();
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

        if($intensive){
            $IdOfFirstTenStudents = $gettersSetters->getIdOfFirstTenStudents();
            $isFirstTenStudent = $gettersSetters->getIsItOneOfFirstTenStudents($IdOfFirstTenStudents, $id);
            $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, $isFirstTenStudent);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaultCostOfOneLesson);
        }else {
            $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, false);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount, $defaultCostOfOneLesson);
        }

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
            $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, $isFirstTenStudent);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaultCostOfOneLesson);
            $arr['CostOfOneLessonWithDiscount']=$costOfOneLessonWithDiscount;
        }else {
            $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, false);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount, $defaultCostOfOneLesson);
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

        if($intensive){
            $IdOfFirstTenStudents = $gettersSetters->getIdOfFirstTenStudents();
            $isFirstTenStudent = $gettersSetters->getIsItOneOfFirstTenStudents($IdOfFirstTenStudents, $id);
            $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, $isFirstTenStudent);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaultCostOfOneLesson);
            $arr['CostOfOneLessonWithDiscount']=$costOfOneLessonWithDiscount;
        }else {
            $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, false);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount, $defaultCostOfOneLesson);
            $arr['CostOfOneLessonWithDiscount'] = $costOfOneLessonWithDiscount;
        }

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


        $data = $this->numPayedNumReservedCostOfOneLessonWithDiscount($id,$teacher,$timetable,$level_start,$intensive);
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
}