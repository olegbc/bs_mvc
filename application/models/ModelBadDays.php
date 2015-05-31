<?php
namespace application\models;
class ModelBadDays extends \application\core\model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_data()
    {
    }

    public function combinationAndBadDaysDates(){
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $dataMain['combinationDates'] = $this->getCombinationDates($teacher,$timetable,$level_start);
        $dataMain['BadDaysOfCombination'] = $this->getAllBadDaysOfCombination($teacher,$timetable,$level_start);

        return $dataMain;
    }
    public function insertOrDeleteBadDay(){
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        $badDayClicked = $_POST["badDayClicked"];

        $data = $this->getIsThisBadDayExist($teacher,$timetable,$level_start,$badDayClicked);
        if($data){
            $this->setDeleteBadDay($teacher,$timetable,$level_start,$badDayClicked);
        }else{
            $this->setInsertBadDay($teacher,$timetable,$level_start,$badDayClicked);
        }
    }
    public function attenedDates(){
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $data = $this->getAttenedDates($teacher,$timetable,$level_start);
        return $data;
    }

    /////////////////////////////////////////////////////////   GETTERS/SETTERS   /////////////////////////////////////////////////////////

    public function getCombinationDates($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        if(!empty($everyLessonDate[0])){return $everyLessonDate[0];}else{return false;}
    }
    public function getAllBadDaysOfCombination($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `bad_day` FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getIsThisBadDayExist($teacher,$timetable,$level_start,$badDayClicked){
        $db = $this->db;
        $sql = "SELECT `id` FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `bad_day`='".$badDayClicked."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}
    }
    public function getAttenedDates($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `timetable`='" . $timetable . "'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }

    public function setDeleteBadDay($teacher,$timetable,$level_start,$badDayClicked){
        $db = $this->db;
        $sql = "DELETE FROM `bad_days` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `bad_day`=:badDayClicked";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':badDayClicked', $badDayClicked, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setInsertBadDay($teacher,$timetable,$level_start,$badDayClicked){
        $db = $this->db;
        $sql = "INSERT INTO `bad_days` (`bad_day`,`teacher`,`timetable`,`level_start`) VALUES (:badDayClicked,:teacher,:timetable,:level_start)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR );
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR );
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR );
        $stmt->bindParam(':badDayClicked', $badDayClicked, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
}