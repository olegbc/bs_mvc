<?php
namespace application\models;
class ModelFreeze extends \application\core\model {
    public function __construct()
    {
        parent::__construct();
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
}