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

            if(intval(date("N",$start)) == $first_week_lesson or intval(date("N",$start)) == $second_week_lesson or intval(date("N",$start)) == $third_week_lesson){
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
    public function studentsInformation(){
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

        if($timetable){
            $students = $gettersSetters->getPersonIdStartStop($teacher,$timetable,$level_start,$intensive);
            if(count($students) != 0){
                $arr['paymentExists'] = 0;
                $arr['attendenceExists'] = 0;
                for ($i=0; $i <count($students); $i++) {
                    $id=$students[$i]['id_person'];
                    $arr['id'][]=$students[$i]['id_person'];
                    $arr['discount'][]=$gettersSetters->getDiscount($id,$teacher,$timetable,$level_start);
                    $arr['name'][]=$gettersSetters->getName($id);
                    $arr['personStart'][]=$students[$i]['person_start'];
                    $arr['personStop'][]=$students[$i]['person_stop'];
                    $data=$gettersSetters->getNumPayedNumReserved($id,$teacher,$timetable,$level_start);
                    $arr['numPayed'][]=$data[0]['num_payed'];
                    if($data[0]['num_payed'] > 0){$arr['paymentExists'] = 1;}
                    $arr['numReserved'][]=$data[0]['num_reserved'];
                    $data=$gettersSetters->getAttenedDates($id,$teacher,$timetable,$level_start);
                    $arr['attenedDates'][]=$data;
                    if(count($data) > 0){$arr['attendenceExists'] = 1;}
                    $arr['frozenDates'][]=$gettersSetters->getFrozenDates($id,$teacher,$timetable,$level_start);
                }
                $arr['dates']=$gettersSetters->getCombinationDatesAttendance($teacher,$timetable,$level_start);
                $arr['archive']=$gettersSetters->getIsItAnArchiveCombination($teacher,$timetable,$level_start);
                return $arr;
            }else{
                return false;
            }
        }
        if($intensive){
            $students = $gettersSetters->getPersonIdStartStop($teacher,'undefined',$level_start,$intensive);
            if(count($students) != 0){
                $arr['paymentExists'] = 0;
                $arr['attendenceExists'] = 0;
                for ($i=0; $i <count($students); $i++) {
                    $id=$students[$i]['id_person'];
                    $arr['id'][]=$students[$i]['id_person'];
                    $arr['discount'][]=$gettersSetters->getDiscount($id,$teacher,$timetable,$level_start,$intensive);
                    $arr['name'][]=$gettersSetters->getName($id);
                    $arr['personStart'][]=$students[$i]['person_start'];
                    $arr['personStop'][]=$students[$i]['person_stop'];
                    $data=$gettersSetters->getNumPayedNumReserved($id,$teacher,$timetable,$level_start,$intensive);
                    $arr['numPayed'][]=$data[0]['num_payed'];
                    if($data[0]['num_payed'] > 0){$arr['paymentExists'] = 1;}
                    $arr['numReserved'][]=$data[0]['num_reserved'];
                    $data=$gettersSetters->getAttenedDates($id,$teacher,$timetable,$level_start,$intensive);
                    $arr['attenedDates'][]=$data;
                    if(count($data) > 0){$arr['attendenceExists'] = 1;}
                    $arr['frozenDates'][]=$gettersSetters->getFrozenDates($id,$teacher,$timetable,$level_start,$intensive);
                }
                $arr['dates']=$gettersSetters->getCombinationDatesAttendance($teacher,'undefined',$level_start,$intensive);
                $arr['archive']=$gettersSetters->getIsItAnArchiveCombination($teacher,'undefined',$level_start,$intensive);
                return $arr;
            }else{
                return false;
            }
        }
    }
    public function buildingBlocks(){
        $gettersSetters = $this->gettersSetters;
        $data=$gettersSetters->getAllCombinationsExistedFromLevels();
        $mainData = $data;
        foreach($data as $key=>$value){
            $intensive = $value['intensive'];
            $teacher = $value['teacher'];
            $timetable = $value['timetable'];
            $level_start = $value['sd_1'];
            $intensive = $value['intensive'];
            if($intensive){
                $payment = $gettersSetters->getPayment($teacher, false, $level_start,$intensive);
                $attendance = $gettersSetters->getAttendance($teacher, false, $level_start, $intensive);
            }else {
                $payment = $gettersSetters->getPayment($teacher, $timetable, $level_start, false);
                $attendance = $gettersSetters->getAttendance($teacher, $timetable, $level_start, false);
            }
            $mainData[$key]['paymentExists'] = 0;
            $mainData[$key]['attendanceExists'] = 0;
            foreach ($payment as $k => $v) {
                if ($v['num_payed'] > 0) {
                    $mainData[$key]['paymentExists'] = 1;
                }
            }
            foreach ($attendance as $k => $v) {
                if (count($v['date_of_visit']) > 0) {
                    $mainData[$key]['attendanceExists'] = 1;
                }
            }
        }
        return $mainData;
    }
    public function deleteAttenedDateFromAttendanceTable(){
        $gettersSetters = $this->gettersSetters;
        $id = $_POST["id"];
        $attenedDate = $_POST["attenedDate"];
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
        $data=$gettersSetters->getIdFromAttendanceTable($attenedDate,$teacher,$timetable,$level_start,$id,$intensive);
        $existedId = $data[0]['id'];
        $data=$gettersSetters->setDeleteIdFromAttendanceTable($existedId);
        return $data;
    }
    public function insertAttenedDateToAttendanceTable(){
        $gettersSetters = $this->gettersSetters;
        $id = $_POST["id"];
        $attenedDate = $_POST["attenedDate"];
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
        $data=$gettersSetters->setInsertFromAttendanceTable($id,$attenedDate,$teacher,$timetable,$level_start,$intensive);
        return $data;
    }
    public function changeLevelStartDate(){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST["teacher"];
        $timetable = false;
        if(isset($_POST["timetable"])){$timetable = $_POST["timetable"];}
        $level_start = $_POST["level_start"];
        $new_level_start = $_POST["new_level_start"];
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
        if($intensive){$IdOfFirstTenStudents = $gettersSetters->getIdOfFirstTenStudents();}

        $wrong = array();
        $start = strtotime($new_level_start);

        if(!$intensive) {
            if ($timetable == "ПУ" or $timetable == "ПД" or $timetable == "ПВ") {
                $first_week_lesson = 1;
                $second_week_lesson = 3;
                $third_week_lesson = 5;
            }
            if ($timetable == "ВУ" or $timetable == "ВД" or $timetable == "ВВ") {
                $first_week_lesson = 2;
                $second_week_lesson = 4;
                $third_week_lesson = 6;
            }
        }

        $data = $gettersSetters->getIdOfCombination($teacher,$timetable,$level_start,$intensive);
        if(!empty($data['id'])){
            $dataMain['id'] = $data['id'];
            if(!$intensive) {
                if (intval(date("N", $start)) == $first_week_lesson or intval(date("N", $start)) == $second_week_lesson or intval(date("N", $start)) == $third_week_lesson) {
                }else{
                    $wrong['wrongTimetable'] = true;
                    return $wrong;
                }
            }

            if(!$intensive) {
                $data = $gettersSetters->getAllBadDaysOfCombination($teacher, $timetable, $level_start);
                $dataMain['badDays'] = $data;
            }

            $flag=true;
            if($intensive){
                for ($t = 0; $flag; $t++) {
//                    $denied = 0;
                    $day_of_week = (int)date("N", $start + (86400 * $t));

//                    for ($i = 0; $i < count($dataMain['badDays']); $i++) {
//                        if (strtotime(date("Y-m-d", ($start + (86400 * $t)))) == strtotime($dataMain['badDays'][$i])) {
//                            $denied = 1;
//                        }
//                    }
                    if ($day_of_week != 6 and $day_of_week != 7) {
//                        if ($denied == 0) {
                            $dataMain['newDatesInDayFormat'][] = date("Y-m-d", $start + (86400 * $t));
//                        }
                    }
                    if (count($dataMain['newDatesInDayFormat']) == 10) {
                        $flag = false;
                    }
                }
            }else {
                for ($t = 0; $flag; $t++) {
                    $denied = 0;
                    $day_of_week = intval(date("N", $start + (86400 * $t)));
                    for ($i = 0; $i < count($dataMain['badDays']); $i++) {
                        if (strtotime(date("Y-m-d", ($start + (86400 * $t)))) == strtotime($dataMain['badDays'][$i])) {
                            $denied = 1;
                        }
                    }
                    if ($day_of_week == $first_week_lesson or $day_of_week == $second_week_lesson or $day_of_week == $third_week_lesson) {
                        if ($denied == 0) {
                            $dataMain['newDatesInDayFormat'][] = date("Y-m-d", $start + (86400 * $t));
                        }
                    }
                    if (count($dataMain['newDatesInDayFormat']) == 21) {
                        $flag = false;
                    }
                }
            }

            if($intensive){
                if(isset($dataMain['newDatesInDayFormat'][9])){
                    $calculatedLevelStop = $dataMain['newDatesInDayFormat'][9];
                }
            }else{
                if(isset($dataMain['newDatesInDayFormat'][20])){
                    $calculatedLevelStop = $dataMain['newDatesInDayFormat'][20];
                }
            }

            $data = $gettersSetters->getPersonIdStartStop($teacher,$timetable,$level_start,$intensive);
            $dataMain['PersonIdStartStop'] = $data;

            for($i=0;$i<count($dataMain['PersonIdStartStop']);$i++){
                $id_person = $dataMain['PersonIdStartStop'][$i]['id_person'];
                $person_start = $dataMain['PersonIdStartStop'][$i]['person_start'];
                $person_stop = $dataMain['PersonIdStartStop'][$i]['person_stop'];
                $dataMain['numPayedNumReserved'] = $gettersSetters->getNumPayedNumReserved($id_person,$teacher,$timetable,$level_start,$intensive);
                $numPayed = $dataMain['numPayedNumReserved'][0]['num_payed'];
                $numReserved = $dataMain['numPayedNumReserved'][0]['num_reserved'];
                $dataMain['combinationDates'] = $gettersSetters->getCombinationDatesAttendance($teacher,$timetable,$level_start,$intensive);
                if(isset($calculatedLevelStop)){
                    if(strtotime($person_stop)>strtotime($calculatedLevelStop)){ //←
                        $num_minus=0;	//	количество скушаных в конце уроков
                        if(strtotime($person_start)>strtotime($calculatedLevelStop)){
                            $this->removePersonCombination($id_person,$teacher,$timetable,$level_start,$intensive,$IdOfFirstTenStudents);
                        }else{
                            for($j=0;$j<count($dataMain['combinationDates']);$j++){
                                if( strtotime($dataMain['combinationDates'][$j]) == strtotime($calculatedLevelStop) ){
                                    if($intensive){
                                        $num_minus = 9-$j;
                                    }else{
                                        $num_minus = 20-$j;
                                    }
                                }
                            }
                        }
//                            if(strtotime($person_start)>$calculatedLevelStop){
//                                $num_minus = $numPayed;
//                                $num_minus_reserverd = $numReserved;
//                            }

                        if($numPayed>($numReserved-$num_minus)){
                            $discount=$gettersSetters->getDiscount($id_person,$teacher,$timetable,$level_start,$intensive);
                            if($intensive){
                                $isFirstTenStudent = $gettersSetters->getIsItOneOfFirstTenStudents($IdOfFirstTenStudents, $id_person);
                                $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, $isFirstTenStudent);
                                $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaultCostOfOneLesson);
                                $gettersSetters->setUpdateBalanceAttendance($costOfOneLessonWithDiscount,$num_minus,$id_person);
                                $gettersSetters->setUpdateNumPayedToPayedLessons($num_minus,$id_person,$teacher,$timetable,$level_start,$intensive);
                            }else {
                                $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson(false, false);
                                $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount, $defaultCostOfOneLesson);
                                $gettersSetters->setUpdateBalanceAttendance($costOfOneLessonWithDiscount,$num_minus,$id_person);
                                $gettersSetters->setUpdateNumPayedToPayedLessons($num_minus,$id_person,$teacher,$timetable,$level_start,$intensive);
                            }
                        }
                        if(strtotime($person_start)>strtotime($calculatedLevelStop)){
                        }else{
                            $gettersSetters->setUpdateNumReservedByNumMinusToPayedLessons($num_minus,$id_person,$teacher,$timetable,$level_start,$intensive);
                        }
                        $gettersSetters->setUpdatePersonStopToLevelsPerson($calculatedLevelStop,$id_person,$teacher,$timetable,$level_start,$intensive);
                        $gettersSetters->setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start,$intensive);
                        $gettersSetters->setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable,$level_start,$intensive);
                    }
                    else if(strtotime($person_start)<strtotime($new_level_start)){ //→
                        $num_eaten=0;	//	съедено в начале
                        $num_eaten_from_level_start=0; // количество от даты старта уровня, а не стурта персоны
                        $numEmptynessBetweenLevelStartAndPersonStart = 0;
                        $firstDate = 0;
                        $lastDate = 0;
//                            $num_minus=0;	//	съедено в конце
                        for($j=0;$j<count($dataMain['combinationDates']);$j++){
                            if(strtotime($person_stop)<strtotime($new_level_start)){
                                if(strtotime($person_start)  == strtotime($dataMain['combinationDates'][$j])){$firstDate=$j;}
                                if(strtotime($person_stop)  == strtotime($dataMain['combinationDates'][$j])){$lastDate=$j+1;}
                                $num_eaten = $lastDate - $firstDate;
                            }else {
                                if (strtotime($dataMain['combinationDates'][$j]) == strtotime($new_level_start)) {
                                    $num_eaten_from_level_start = $j;
                                }
                                if (strtotime($dataMain['combinationDates'][$j]) == strtotime($person_start)) {
                                    $numEmptynessBetweenLevelStartAndPersonStart = $j;
                                }
                                $num_eaten = $num_eaten_from_level_start - $numEmptynessBetweenLevelStartAndPersonStart;
                            }
                        }
                        $dataMain['num_eaten'][] = $num_eaten;
//                            if(!empty($numPayed)){
//                            $person_stop = $gettersSetters->getPersonStop($id_person,$teacher,$timetable,$level_start,$intensive);
                            for($e=0;$e<count($dataMain['newDatesInDayFormat']);$e++){
//                                $dataMain['new_person_stop'][] = $person_stop;
                                if(strtotime($person_stop)<strtotime($new_level_start)) {
                                    if ($e == $num_eaten - 1) {
                                        $new_person_stop = $dataMain['newDatesInDayFormat'][$e];
                                    }
                                }else{
                                    if (strtotime($dataMain['newDatesInDayFormat'][$e])==strtotime($person_stop)) {
                                        $new_person_stop = $dataMain['newDatesInDayFormat'][$e+$num_eaten];
                                    }
                                }
                            }
//                        $dataMain['new_person_stop'][] = $new_person_stop;
                        $gettersSetters->setUpdatePersonStopWithNewPersonStopToLevelsPerson($new_person_stop,$id_person,$teacher,$timetable,$level_start,$intensive);
//                        if(strtotime($person_stop)<strtotime($new_level_start)){
//                            }else{
//                                $gettersSetters->setUpdatePersonStopToLevelsPerson($calculatedLevelStop,$id_person,$teacher,$timetable,$level_start,$intensive);
//                            }
//                            }
                        $gettersSetters->setUpdatePersonStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start,$intensive);
                        $gettersSetters->setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start,$intensive);
                        $gettersSetters->setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable,$level_start,$intensive);
                    }
                    else{
                        $gettersSetters->setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable,$level_start,$intensive);
                        $gettersSetters->setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable,$level_start,$intensive);
                    }
                }
                if(!empty($new_person_stop)){$dataMain['new_person_stop'][] = $new_person_stop;}
            }

            if($dataMain['newDatesInDayFormat']){
                for($i=0;$i<count($dataMain['newDatesInDayFormat']);$i++){
                    if($i==0){
                        $gettersSetters->setUpdateLevelStartToLevels($i,$dataMain['newDatesInDayFormat'][$i],$teacher,$timetable,$level_start,$intensive);
                    }
                    else{
                        $gettersSetters->setUpdateLevelStartToLevels($i,$dataMain['newDatesInDayFormat'][$i],$teacher,$timetable,$new_level_start,$intensive);
                    }
                }
            }
//            }
//            else{
//                $wrong['wrongTimetable']=true;
//                return $wrong;
//            }
            return $dataMain;
        }

    }
    public function removePersonCombination($id,$teacher,$timetable=null,$level_start,$intensive=null,$IdOfFirstTenStudents=null){
        $gettersSetters = $this->gettersSetters;
        if(isset($_POST["id"]) and isset($_POST["teacher"]) and isset($_POST["timetable"]) and isset($_POST["level_start"])) {
            $id = $_POST["id"];
            $teacher = $_POST["teacher"];
            $timetable = $_POST["timetable"];
            $level_start = $_POST["level_start"];
        }
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

        $discount = $gettersSetters->getDiscount($id,$teacher,$timetable,$level_start,$intensive);

        if($intensive){
            $isFirstTenStudent = $gettersSetters->getIsItOneOfFirstTenStudents($IdOfFirstTenStudents, $id);
            $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, $isFirstTenStudent);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount,$defaultCostOfOneLesson);
        }else {
            $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive, false);
            $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount, $defaultCostOfOneLesson);
        }

        $numPayed = $gettersSetters->getNumPayedNumReserved($id,$teacher,$timetable,$level_start,$intensive);
        $numPayed = $numPayed[0]['num_payed'];

        $backToBalanceSum=$numPayed*$costOfOneLessonWithDiscount;

        $gettersSetters->setUpdateBalanceWithBackToBalanceSum($backToBalanceSum,$id);
        $gettersSetters->setDeletePersonCombinationFromLevelsPeson($id,$teacher,$timetable,$level_start,$intensive);
        $gettersSetters->setDeletePersonCombinationFromPayedLessons($id,$teacher,$timetable,$level_start,$intensive);

        $gettersSetters->setDeletePersonCombinationFromAttendance($id,$teacher,$timetable,$level_start,$intensive);
        $gettersSetters->setDeletePersonCombinationFromDiscount($id,$teacher,$timetable,$level_start,$intensive);
        $gettersSetters->setDeletePersonCombinationFromFreeze($id,$teacher,$timetable,$level_start,$intensive);
    }
    public function removeCombination(){
        $gettersSetters = $this->gettersSetters;
        $teacher=$_POST['teacher'];
        $level_start=$_POST['level_start'];
        $timetable = false;
        if(isset($_POST["timetable"])){
            $timetable = $_POST["timetable"];
        }
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

        $gettersSetters->setDeleteCombination($teacher,$timetable,$level_start,$intensive);
    }
    public function toArchive(){
        $gettersSetters = $this->gettersSetters;
        $teacher=$_POST['teacher'];
        $level_start=$_POST['level_start'];
        $timetable = false;
        if(isset($_POST["intensive"])){
            $timetable = $_POST["timetable"];
        }
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

        $gettersSetters->setUpdateArchive($teacher,$timetable,$level_start,$intensive);
    }

/*
    /////////////////////////////////////////////////////////   GETTERS/SETTERS   /////////////////////////////////////////////////////////
    /*
    public function getAllCombinations($teacher,$timetable=null,$level_start,$intensive=null)
    {
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT id,level,teacher,timetable,sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,archive FROM `levels` WHERE `sd_1`='" . $level_start . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "'";
        }else{
            $sql = "SELECT id,level,teacher,timetable,sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21,archive FROM `levels` WHERE `sd_1`='" . $level_start . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0])){return $data[0];}else{return false;}
    }
    public function getPersonIdStartStop($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `id_person`,`person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'";
        }else {
            $sql = "SELECT `id_person`,`person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }



    public function getPersonStop($id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `person_stop` FROM `levels_person` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' AND `id_person`=" . $id_person;
        }else {
            $sql = "SELECT `person_stop` FROM `levels_person` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' AND `id_person`=" . $id_person;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_NUM);
        return $data;
    }
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
    public function getName($id){
        $db = $this->db;
        $sql = "SELECT `fio` FROM `main` WHERE `id`='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        $name = $data[0]['fio'];
        return $name;

    }
    public function getCombinationDates($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive) {
            $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10 FROM `levels` WHERE teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND sd_1='" . $level_start . "'";
        }else {
            $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND sd_1='" . $level_start . "'";
        }
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        if(!empty($everyLessonDate[0])){return $everyLessonDate[0];}else{return false;}
    }

    public function getIsItAnArchiveCombination($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive) {
            $sql = "SELECT `archive` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `sd_1`='" . $level_start . "' AND `intensive`='" . $intensive . "'";
        }else{
            $sql = "SELECT `archive` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `sd_1`='" . $level_start . "' AND `timetable`='" . $timetable . "'";
        }
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate[0];
    }


    public function getNumPayedNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive) {
            $sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE id_person='" . $id . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'";
        }else {
            $sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE id_person='" . $id . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(!empty($data[0])){return $data[0];}else{return false;}
    }
    public function getFrozenDates($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive) {
            $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.intensive='" . $intensive . "' AND freeze.id_person=" . $id;
        }else {
            $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND freeze.id_person=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }

    public function getAttenedDates($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `intensive`='" . $intensive . "' AND `id_visit`=" . $id;
        }else{
            $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `timetable`='" . $timetable . "' AND `id_visit`=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }

    public function getAllCombinationsExistedFromLevels(){
        $db = $this->db;
        $sql = "SELECT `teacher`, `timetable`, `sd_1`,`level`,`archive`,`intensive` FROM `levels` ORDER BY `teacher` ";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getIdFromAttendanceTable($attenedDate,$teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `id` FROM `attendance` WHERE `date_of_visit`='" . $attenedDate . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' AND `id_visit`='" . $id . "'";
        }else {
            $sql = "SELECT `id` FROM `attendance` WHERE `date_of_visit`='" . $attenedDate . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' AND `id_visit`='" . $id . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getIdOfCombination($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `id` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `sd_1`='" . $level_start . "'";
        }else {
            $sql = "SELECT `id` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `sd_1`='" . $level_start . "'";
        }
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

    public function getDefaulCostOfOneLesson($intensive=null)
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
    }

    public function getCostOfOneLessonWithDiscount($discount,$defaulCostOfOneLesson){
        $CostOfOneLessonWithDiscount = $defaulCostOfOneLesson - round(($defaulCostOfOneLesson*($discount*0.01)),2);
        $arr['CostOfOneLessonWithDiscount'] = $CostOfOneLessonWithDiscount;
        return $CostOfOneLessonWithDiscount;
    }

    public function getAttendance($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getPayment($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `num_payed` FROM `payed_lessons` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
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
    public function setInsertFromAttendanceTable($id,$attenedDate,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "INSERT INTO `attendance` (`date_of_visit`,`id_visit`,`teacher`,`intensive`,`level_start`) VALUES(:date_of_visit,:id_visit,:teacher,:intensive,:level_start)";
        }else {
            $sql = "INSERT INTO `attendance` (`date_of_visit`,`id_visit`,`teacher`,`timetable`,`level_start`) VALUES(:date_of_visit,:id_visit,:teacher,:timetable,:level_start)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':date_of_visit', $attenedDate, \PDO::PARAM_INT );
        $stmt->bindParam(':id_visit', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR );
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
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

    public function setDeleteCombination($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "DELETE FROM `levels` WHERE `teacher`=:teacher AND `intensive`=:intensive AND `sd_1`=:level_start";
        }else {
            $sql = "DELETE FROM `levels` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromAttendance($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "DELETE FROM `attendance` WHERE `id_visit`=:id AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else {
            $sql = "DELETE FROM `attendance` WHERE `id_visit`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromDiscount($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "DELETE FROM `discounts` WHERE `id_person`=:id AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else {
            $sql = "DELETE FROM `discounts` WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromFreeze($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "DELETE FROM `freeze` WHERE `id_person`=:id AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else {
            $sql = "DELETE FROM `freeze` WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromLevelsPeson($id, $teacher, $timetable=null, $level_start, $intensive=null){
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
    public function setDeletePersonCombinationFromPayedLessons($id, $teacher, $timetable=null, $level_start, $intensive=null){
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
    public function setUpdateNumPayedToPayedLessons($num_minus,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_payed`=num_payed-:num_minus WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `payed_lessons` SET `num_payed`=num_payed-:num_minus WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_minus', $num_minus, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
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
    public function setUpdateNumReservedByNumMinusToPayedLessons($num_minus,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-:num_minus WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-:num_minus WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_minus', $num_minus, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }

    public function setUpdatePersonStopToLevelsPerson($calculatedLevelStop,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
//        return $calculatedLevelStop;
        if(!is_string($calculatedLevelStop)){$calculatedLevelStop = date('Y-m-d',$calculatedLevelStop);}
        if($intensive){
            $sql = "UPDATE `levels_person` SET `person_stop`=:calculatedLevelStop WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `levels_person` SET `person_stop`=:calculatedLevelStop WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':calculatedLevelStop', $calculatedLevelStop, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdatePersonStopWithNewPersonStopToLevelsPerson($new_person_stop,$id_person,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `levels_person` SET `person_stop`=:new_person_stop WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `levels_person` SET `person_stop`=:new_person_stop WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_person_stop', $new_person_stop, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `levels_person` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `levels_person` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `payed_lessons` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdatePersonStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `levels_person` SET `person_start`=:new_level_start WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `levels_person` SET `person_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToLevels($i,$newDatesOfCombination,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        $i = $i+1;
        if($intensive){
            $sql = "UPDATE `levels` SET sd_:i=:newDatesOfCombination WHERE `teacher`=:teacher AND `intensive`=:intensive AND `sd_1`=:level_start";
        }else {
            $sql = "UPDATE `levels` SET sd_:i=:newDatesOfCombination WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':i', $i, \PDO::PARAM_INT);
        $stmt->bindParam(':newDatesOfCombination', $newDatesOfCombination, \PDO::PARAM_STR);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateArchive($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        $archiveTrue = 1;
        if($intensive){
            $sql = "UPDATE `levels` SET `archive`=:archiveTrue WHERE `teacher`=:teacher AND `intensive`=:intensive AND `sd_1`=:level_start";
        }else {
            $sql = "UPDATE `levels` SET `archive`=:archiveTrue WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':archiveTrue', $archiveTrue, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
*/

}