<?php
namespace application\models;
class ModelLevelCalculation extends \application\core\model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function calculateLevelDates()
    {
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST["teacher"];
        $timetable = false;
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        if (isset($_POST["level"])) {
            $level = $_POST["level"];
        }
        $intensive = false;
        if (isset($_POST['intensive'])) {
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
        if ($intensive) {
            if (date("N", $start) != 6 and date("N", $start) != 7) {

                $data = $gettersSetters->getDoesCombinationExist($teacher, $timetable, $level_start, $intensive);
                if (!$data) {
                    $gettersSetters->setInsertIntoLevelsTable($level, $teacher, $timetable, $level_start, $intensive);
                }

                $numOfLessons = 0;
                $flag = true;

                for ($t = 0; $flag; $t++) {
                    $dayOfWeek = (int)date("N", $start + (86400 * $t));
                    if ($dayOfWeek != 6 and $dayOfWeek != 7) {
                        $newDateOfCombination = date("Y-m-d", ($start + (86400 * $t)));
                        $dataMain['newDatesOfCombination'][] = $newDateOfCombination;
                        $gettersSetters->setUpdateLevelStartToLevels($numOfLessons, $newDateOfCombination, $teacher, $timetable, $level_start, $intensive);
                        $numOfLessons++;
                    }

                    if ($numOfLessons == 1) {
                        $gettersSetters->setIntensiveToTrueAtLevels($teacher, $timetable, $level_start, $intensive);
                    }
                    if ($numOfLessons == 10) {
                        $flag = false;
                    }
                }

                return $dataMain;
            } else {
                $wrong['wrongTimetable'] = true;
                return $wrong;
            }
        }else{

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


            if (date("N", $start) == $first_week_lesson or date("N", $start) == $second_week_lesson or date("N", $start) == $third_week_lesson) {

                $data = $gettersSetters->getDoesCombinationExist($teacher, $timetable, $level_start, $intensive);
                if (!$data) {
                    $gettersSetters->setInsertIntoLevelsTable($level, $teacher, $timetable, $level_start, $intensive);
                }

                $dataMain['badDays'] = $gettersSetters->getAllBadDaysOfCombination($teacher, $timetable, $level_start);

                $numOfLessons = 0;
                $flag = true;

                for ($t = 0; $flag; $t++) {
                    $denied = false;
                    $dayOfWeek = date("N", $start + (86400 * $t));
                    if ($dataMain['badDays']) {
                        for ($i = 0; $i < count($dataMain['badDays']); $i++) {
                            $thisDay = date("Y-m-d", ($start + (86400 * $t)));
                            $badDay = $dataMain['badDays'][$i];
                            if ($thisDay == $badDay) {
                                $denied = true;
                            }
                        }
                        if ($dayOfWeek == $first_week_lesson or $dayOfWeek == $second_week_lesson or $dayOfWeek == $third_week_lesson) {
                            if (!$denied) {
                                $newDateOfCombination = date("Y-m-d", ($start + (86400 * $t)));
                                $dataMain['newDatesOfCombination'][] = $newDateOfCombination;
                                $gettersSetters->setUpdateLevelStartToLevels($numOfLessons, $newDateOfCombination, $teacher, $timetable, $level_start, $intensive);
                                $numOfLessons++;
                            }
                        }
                    } else {
                        if ($dayOfWeek == $first_week_lesson or $dayOfWeek == $second_week_lesson or $dayOfWeek == $third_week_lesson) {
                            $newDateOfCombination = date("Y-m-d", ($start + (86400 * $t)));
                            $dataMain['newDatesOfCombination'][] = $newDateOfCombination;
                            $gettersSetters->setUpdateLevelStartToLevels($numOfLessons, $newDateOfCombination, $teacher, $timetable, $level_start, $intensive);
                            $numOfLessons++;
                        }
                    }

                    if ($numOfLessons == 21) {
                        $flag = false;
                    }
                }

                return $dataMain;
            } else {
                $wrong['wrongTimetable'] = true;
                return $wrong;
            }
    }
    }
}