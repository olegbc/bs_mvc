<?php
namespace application\models;
class ModelSPU extends \application\core\model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function spuCalculation()
    {
        $gettersSetters = $this->gettersSetters;
        $datetime1 = new \DateTime($_POST["from"]);
        $datetime2 = new \DateTime($_POST["to"]);
        $interval = $datetime1->diff($datetime2);
        $num_weeks = ($interval->days + 1) / 7;

        $dataSpuSum = [];
        $countCombinationByAllTeachers = 0;

        $start_range_unix = strtotime($_POST["from"]);
        $stop_range_unix = strtotime($_POST["to"]);

        $start_week_range_unix = strtotime($_POST["from"]);

        $allPersonsStartsAndStops = $gettersSetters->getAllPersonsStartsAndStopsAndOtherInformation();


        $allTeachers = $gettersSetters->getAllTeachers();

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

                    $datesOfCombination = $gettersSetters->getCombinationDates($teacher, $timetable, $level_start, $intensive);

                    $discount = $gettersSetters->getDiscount($id_person, $teacher, $timetable, $level_start, $intensive);
                    $defaultCostOfOneLesson = $gettersSetters->getDefaultCostOfOneLesson($intensive);
                    $costOfOneLessonWithDiscount = $gettersSetters->getCostOfOneLessonWithDiscount($discount, $defaultCostOfOneLesson);

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
}
