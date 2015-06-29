<?php
namespace application\models;
class ModelSPU extends \application\core\model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_data()
    {
    }

    public function spuCalculation()
    {
        $datetime1 = new \DateTime($_POST["from"]);
        $datetime2 = new \DateTime($_POST["to"]);
        $interval = $datetime1->diff($datetime2);
        $num_weeks = ($interval->days + 1) / 7;

        $dataSpuSum = [];
        $countCombinationByAllTeachers = 0;

        $start_range_unix = strtotime($_POST["from"]);
        $stop_range_unix = strtotime($_POST["to"]);

        $start_week_range_unix = strtotime($_POST["from"]);

        $allPersonsStartsAndStops = $this->getAllPersonsStartsAndStopsAndOtherInformation();


        $allTeachers = $this->getAllTeachers();

        foreach ($allPersonsStartsAndStops as $value) {
            foreach ($allTeachers as $theTeacher) {
                if ($value['teacher'] == $theTeacher) {
                    $allPersonsStartsAndStopsFilteredByTeacher[$theTeacher][] = $value;
                }
            }
        }

        $noFittedCombinationInRegion = 1;

        foreach($allPersonsStartsAndStopsFilteredByTeacher as $byThisTeacher){
            foreach ($byThisTeacher as $key => $value) {
                $personStart = strtotime($byThisTeacher[$key]['person_start']);
                $personStop = strtotime($byThisTeacher[$key]['person_stop']);

                if ($personStart > $stop_range_unix or $personStop < $start_range_unix) {
                } else {
                    $allPersonsStartsAndStopsFilteredByTeacherByRange[$byThisTeacher[$key]['teacher']][] = $byThisTeacher[$key];
                    if($noFittedCombinationInRegion=1){$noFittedCombinationInRegion=0;}
                }
            }
        }

        if($noFittedCombinationInRegion == 0) {
            foreach ($allPersonsStartsAndStopsFilteredByTeacherByRange as $byTeacherByRange) {
                $dataSpu = [];
                $countCombinationByTeacher = 0;
                foreach ($byTeacherByRange as $key => $value) {

                    $id_person = $byTeacherByRange[$key]['id_person'];
                    $teacher = $byTeacherByRange[$key]['teacher'];
                    $timetable = $byTeacherByRange[$key]['timetable'];
                    $level_start = $byTeacherByRange[$key]['level_start'];
                    $intensive = $byTeacherByRange[$key]['intensive'];

                    $datesOfCombination = $this->getCombinationDates($teacher, $timetable, $level_start, $intensive);

                    $discount = $this->getDiscount($id_person, $teacher, $timetable, $level_start, $intensive);
                    $defaultCostOfOneLesson = $this->getDefaulCostOfOneLesson($intensive);
                    $costOfOneLessonWithDiscount = $this->getCostOfOneLessonWithDiscount($discount, $defaultCostOfOneLesson);

                    for ($t = 0; $t < $num_weeks; $t++) {
                        $start_week = date('Y-m-d', $start_week_range_unix + (604800 * $t));
                        $stop_week = date('Y-m-d', $start_week_range_unix + ((604800 * ($t + 1)) - 86400));

                        $begin = new \DateTime($start_week);
                        $end = new \DateTime($stop_week);
                        $end = $end->modify('+1 day');

                        $interval = new \DateInterval('P1D');
                        $period = new \DatePeriod($begin, $interval, $end);

                        $weekDates = [];

                        foreach ($period as $dataTime) {
                            $weekDates[] = $dataTime->format("Y-m-d");
                        }

                        $numbersOfLessonsInWeek = count(array_intersect($weekDates, $datesOfCombination[0]));
                        //                            $dataMain[$teacher][$count][$t]['numbersOfLessonsInWeek'] = $numbersOfLessonsInWeek;

                        $SPUForCombinationInWeek = $numbersOfLessonsInWeek * $costOfOneLessonWithDiscount;
                        //                            $dataMain[$count]['SPUForCombinationInWeek'][$t] = $SPUForCombinationInWeek;
                        if ($countCombinationByTeacher == 0) {
                            $dataSpu[$countCombinationByTeacher][$t] = $SPUForCombinationInWeek;
                        } else {
                            $dataSpu[$countCombinationByTeacher][$t] = $dataSpu[($countCombinationByTeacher - 1)][$t] + $SPUForCombinationInWeek;
                        }

                        if ($countCombinationByAllTeachers == 0) {
                            $dataSpuSum[$countCombinationByAllTeachers][$t] = $SPUForCombinationInWeek;
                        } else {
                            $dataSpuSum[$countCombinationByAllTeachers][$t] = $dataSpuSum[($countCombinationByAllTeachers - 1)][$t] + $SPUForCombinationInWeek;
                        }

                        if ($countCombinationByAllTeachers == 0) {
                            $dataMain['weekRange'][] = date('M', $start_week_range_unix + (604800 * $t)) . " :" . $start_week . " - " . $stop_week;
                        }

                        $key = count($dataSpu) - 1;
                        $keySum = count($dataSpuSum) - 1;
                        $dataMain['teachers'][$teacher]['amount'] = $dataSpu[$key];
                        $dataMain['sum']['amount'] = $dataSpuSum[$keySum];
                        //                            $dataMain['amount'] = $dataSpu[$key];
                    }
                    $countCombinationByTeacher++;
                    $countCombinationByAllTeachers++;
                }
            }
        }else{
//            $dataMain['noFittedCombinationInRegion'] = 1;
        }

        return $dataMain;
    }

    /////////////////////////////////////////////////////////   GETTERS/SETTERS   /////////////////////////////////////////////////////////

    public function getAllPersonsStartsAndStopsAndOtherInformation(){
        $db = $this->db;
        $sql = "SELECT `id`, `id_person`, `person_start`, `person_stop`,`teacher`,`timetable`,`level_start`,`intensive` FROM `levels_person`";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getNumReserved($id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `num_reserved` FROM `payed_lessons` WHERE `id_person` ='" . $id_person . "' AND `teacher` = '" . $teacher . "' AND `level_start` = '" . $level_start . "' AND `intensive` ='" . $intensive . "'";
        }else {
            $sql = "SELECT `num_reserved` FROM `payed_lessons` WHERE `id_person` ='" . $id_person . "' AND `teacher` = '" . $teacher . "' AND `level_start` = '" . $level_start . "' AND `timetable` ='" . $timetable . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
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
    public function getAllTeachers(){
        $db = $this->db;
        $sql = "SELECT DISTINCT `teacher` FROM `levels`";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
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
}
