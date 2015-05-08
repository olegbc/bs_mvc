<?php
namespace application\models;
class ModelLevelCalculation extends \application\core\model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_data()
    {
    }

    public function calculateLevelDates()
    {
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        if(isset($_POST["level"])){$level = $_POST["level"];}


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

        if(date("N",$start) == $first_week_lesson or date("N",$start) == $second_week_lesson or date("N",$start) == $third_week_lesson){

            $data = $this->getDoesCombinationExist($teacher,$timetable,$level_start);
            if(!$data){
                $this->setInsertIntoLevelsTable($level,$teacher,$timetable,$level_start);
            }

            $dataMain['badDays'] = $this->getAllBadDaysOfCombination($teacher,$timetable,$level_start);
            $numOfLessons = 0;
            $flag=true;
            for($t=0;$flag;$t++) {
                $denied = false;
                $dayOfWeek = date("N",$start + (86400*$t));
                if($dataMain['badDays']){
                    for($i=0;$i<count($dataMain['badDays']);$i++){
                        $thisDay = date("Y-m-d",($start + (86400*$t)));
                        $badDay = $dataMain['badDays'][$i];
                        if($thisDay == $badDay){
                            $denied = true;
                        }
                    }
                    if($dayOfWeek == $first_week_lesson or $dayOfWeek == $second_week_lesson or $dayOfWeek == $third_week_lesson){
                        if(!$denied){
                            $newDateOfCombination = date("Y-m-d", ($start + (86400 * $t)));
                            $dataMain['newDatesOfCombination'][] = $newDateOfCombination;
                            $this->setUpdateNumReservedToPayedLessons($numOfLessons, $newDateOfCombination, $teacher, $timetable, $level_start);
                            $numOfLessons++;
                        }
                    }
                }else{
                    if($dayOfWeek == $first_week_lesson or $dayOfWeek == $second_week_lesson or $dayOfWeek == $third_week_lesson){
                        $newDateOfCombination = date("Y-m-d",($start + (86400*$t)));
                        $dataMain['newDatesOfCombination'][] = $newDateOfCombination;
                        $this->setUpdateNumReservedToPayedLessons($numOfLessons,$newDateOfCombination,$teacher,$timetable,$level_start);
                        $numOfLessons++;
                    }
                }
                if($numOfLessons==21){$flag=false;}
            }
            return $dataMain;
        }else{
            $wrong['wrongTimetable']=true;
            return $wrong;
        }
    }

    /////////////////////////////////////////////////////////   GETTERS/SETTERS   /////////////////////////////////////////////////////////

    public function getDoesCombinationExist($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `id` FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getAllBadDaysOfCombination($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `bad_day` FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }

    public function setInsertIntoLevelsTable($level,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "INSERT INTO `levels` (`level`,`teacher`,`timetable`,`sd_1`) VALUES(:level,:teacher,:timetable,:level_start)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':level', $level, \PDO::PARAM_STR );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR );
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR );
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR );
        $stmt->execute();

        $data['lastInsert'] = $db->lastInsertId();
        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'insert';
        return $data;
    }
    public function setUpdateNumReservedToPayedLessons($i,$newDateOfCombination,$teacher,$timetable,$level_start){
        $db = $this->db;
        $i = $i+1;
        $sql="UPDATE `levels` SET sd_:i=:newDateOfCombination WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':i', $i, \PDO::PARAM_INT);
        $stmt->bindParam(':newDateOfCombination', $newDateOfCombination, \PDO::PARAM_STR);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
}