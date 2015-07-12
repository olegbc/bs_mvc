<?php
namespace application\models;
class ModelAttendance extends \application\core\Model
{
    public function __construct()
    {
        parent::__construct();
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
        $badDayPage = 0;
        $badDayPage = $_POST["badDayPage"];
        $data=$gettersSetters->getAllCombinationsExistedFromLevels($badDayPage);
        $mainData = $data;
        foreach($data as $key=>$value){
            $intensive = false;
            if($badDayPage == 0){$intensive = $value['intensive'];}
            $teacher = $value['teacher'];
            $timetable = $value['timetable'];
            $level_start = $value['sd_1'];
            if($intensive && $badDayPage == 0){
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
}