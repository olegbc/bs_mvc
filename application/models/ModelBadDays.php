<?php
namespace application\models;
class ModelBadDays extends \application\core\model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function combinationAndBadDaysDates(){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $dataMain['combinationDates'] = $gettersSetters->getCombinationDates($teacher,$timetable,$level_start,false);
        $dataMain['BadDaysOfCombination'] = $gettersSetters->getAllBadDaysOfCombination($teacher,$timetable,$level_start);

        return $dataMain;
    }
    public function insertOrDeleteBadDay(){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        $badDayClicked = $_POST["badDayClicked"];

        $data = $gettersSetters->getIsThisBadDayExist($teacher,$timetable,$level_start,$badDayClicked);
        if($data){
            $gettersSetters->setDeleteBadDay($teacher,$timetable,$level_start,$badDayClicked);
        }else{
            $gettersSetters->setInsertBadDay($teacher,$timetable,$level_start,$badDayClicked);
        }
    }
    public function attenedDates(){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $data = $gettersSetters->getAllAttenedDates($teacher,$timetable,$level_start,false);
        return $data;
    }
}