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
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];

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

        if(date("N",$start)== $first_week_lesson or date("N",$start)== $second_week_lesson or date("N",$start)== $third_week_lesson){
            $data = $this->getAllCombinations($teacher,$timetable,$level_start);
            return $data;
        }else{
            echo "Дата старта уровня не соответствует расписанию";
        }
    }
    public function studentsInformation(){
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $students = $this->getPersonIdStartStop($teacher,$timetable,$level_start);
        if(count($students) != 0){
            for ($i=0; $i <count($students); $i++) {
                $id=$students[$i]['id_person'];
                $arr['id'][]=$students[$i]['id_person'];
                $arr['discount'][]=$this->getDiscount($id,$teacher,$timetable,$level_start);
                $arr['name'][]=$this->getName($id);
                $arr['personStart'][]=$students[$i]['person_start'];
                $arr['personStop'][]=$students[$i]['person_stop'];
                $arr['personStop'][]=$students[$i]['person_stop'];
                $data=$this->getNumPayedNumReserved($id,$teacher,$timetable,$level_start);
                $arr['numPayed'][]=$data['num_payed'];
                $arr['numReserved'][]=$data['num_reserved'];
                $data=$this->getAttenedDates($id,$teacher,$timetable,$level_start);
                $arr['attenedDates'][]=$data;
                $arr['frozenDates'][]=$this->getFrozenDates($id,$teacher,$timetable,$level_start);
            }
            $arr['dates']=$this->getCombinationDates($teacher,$timetable,$level_start);
            $arr['status']=$this->getCombinationStatus($teacher,$timetable,$level_start);
        }
        if(!empty($arr)){return $arr;}else{return;}
    }

    /////////////////////////////////////////////////////////   /GETTERS   /////////////////////////////////////////////////////////

    public function getAllCombinations($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT * FROM `levels` WHERE `sd_1`='".$level_start."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    public function getPersonIdStartStop($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `id_person`,`person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getDiscount($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `discount` FROM `discounts` WHERE `id_person`='".$id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
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
    public function getCombinationDates($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate[0];
    }
    public function getCombinationStatus($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `status` FROM `levels` WHERE `teacher`='".$teacher."' AND `sd_1`='".$level_start."' AND `timetable`='".$timetable."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate[0];
    }
    public function getNumPayedNumReserved($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE id_person='".$id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
//        return $sql;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    public function getFrozenDates($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `frozen_day` FROM `levels_person` LEFT JOIN `freeze` ON levels_person.id_person = freeze.id_person AND levels_person.teacher = freeze.teacher AND levels_person.timetable = freeze.timetable AND levels_person.level_start = freeze.level_start WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND freeze.id_person=" . $id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getAttenedDates($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `timetable`='" . $timetable . "' AND `id_visit`=" . $id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
}