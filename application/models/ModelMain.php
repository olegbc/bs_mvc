<?php
namespace application\models;
class ModelMain extends \application\core\Model
{
    public function __construct(){
        parent::__construct();
    }

	public function getData()
	{
        $db = $this->db;

        $sql = 'SELECT * FROM main';
		$data = $db->query($sql);
        $dataArr['main'] = $data->fetchAll($db::FETCH_ASSOC);

        $sql = 'SELECT DISTINCT `teacher` FROM `levels`';
        $data = $db->query($sql);
        $dataArr['allTeachers'] = $data->fetchAll($db::FETCH_ASSOC);

		return $dataArr;
	}
    public function timeTables(){
        $db = $this->db;
        $teacher = $_POST['teacher'];
        $sql = "SELECT DISTINCT `timetable` FROM `levels` WHERE `teacher`='".$teacher."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function levelStart(){
        $db = $this->db;
        $teacher = $_POST['teacher'];
        $timetable = $_POST['timetable'];
        $sql = "SELECT `sd_1` FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function areAnyPayedOrAttenedOrFrozenLessonsExist(){
        $payedLessonExists=0;
        $attendedLessonExists=0;
        $frozenLessonExists=0;
        $id = $_POST['id'];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];

        $arr[] = $id;
        $arr[] = $teacher;
        $arr[] = $timetable;
        $arr[] = $level_start;

        $data = $this->getNumPayedNumReserved($id,$teacher,$timetable,$level_start);
        if(!empty($data['num_payed'])){ $payedLessonExists=1; }

        $data = $this->getCheckForAnyAttendedLessonOfPerson($id,$teacher,$timetable,$level_start);
        if(isset($data['id'])){$attendedLessonExists=1;}

        $data = $this->getCheckForAnyFrozenDates($id,$teacher,$timetable,$level_start);
        if(!empty($data)){$frozenLessonExists=1;}

        if($payedLessonExists==1 or $attendedLessonExists==1 or $frozenLessonExists==1){return true;}else{return false;}
    }
    public function levelCombinationDates(){
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];

        $db = $this->db;
        $data['combinationDates'] = $this->getCombinationDates($teacher,$timetable,$level_start);
        $data['combinationLevel'] = $this->getCombinationLevel($teacher,$timetable,$level_start);
        return $data;
    }
    public function saveUpdateStudentCombination(){
//        return $_POST;// {"id_person":"148","fio_person":"\u041a\u0430\u043c\u0443\u0437 \u041e\u043b\u0435\u0433","teacher":"\u041e\u043b\u0435\u0433","timetable_sel":"\u041f\u0423","level_start_sel":"2014-12-01","level_soch":"1","person_start_sel":"2014-12-01","person_stop_sel":"2015-01-16"}
        $level = $_POST["level_soch"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable_sel"];
        $level_start = $_POST["level_start_sel"];
        $person_start = $_POST["person_start_sel"];
        $person_stop = $_POST["person_stop_sel"];
        $id_person = $_POST["id_person"];
        $fio_person = $_POST["fio_person"];

        $data = $this->getIsThereSuchACombination($id_person,$teacher,$timetable,$level_start);
//        return $data;

//        $result = mysql_query($sql) or die(mysql_error());
        if($data){
            $data = $this->setUpdateCombination($id_person,$teacher,$timetable,$level_start,$person_start,$person_stop,$level);
            return $data;
//            $sql = "INSERT INTO `levels_person` (level,id_person,teacher,timetable,level_start,person_start,person_stop)
//				VALUES('".$level."','".$id_person."','".$teacher."','".$timetable."','".$level_start."','".$person_start."','".$person_stop."')";
            // echo $sql.PHP_EOL;
//             mysql_insert_id();
        }else{
            $data = $this->setNewCombination($id_person,$teacher,$timetable,$level_start,$person_start,$person_stop,$level);
            return $data;
//            $sql="UPDATE `levels_person` SET `person_start`='".$person_start."',`person_stop`='".$person_stop."' WHERE `id_person`='".$id_person."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
//            $result = mysql_query($sql) or die(mysql_error());
//            mysql_query($sql) or die(mysql_error());
//            $id = mysql_insert_id();
        }

//        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
//        $result = mysql_query($sql) or die(mysql_error());
//        $rows_exist = mysql_num_rows ($result);
//        // echo $rows_exist.PHP_EOL;die();
//        if(mysql_num_rows ($result) != 0){
//            $person_start_on_sochitanie=0;
//            $person_stop_on_sochitanie=0;
//            while($row = mysql_fetch_row($result)){
//                for($u=0;$u<21;$u++){
//                    if($row[$u] == $person_start){$person_start_on_sochitanie=$u;};
//                    if($row[$u] == $person_stop){$person_stop_on_sochitanie=$u;};
//                }
//            }
//            $num_lessons_person_on_sochitanie = (abs($person_stop_on_sochitanie - $person_start_on_sochitanie))+1;
//        }
//        // echo $num_lessons_person_on_sochitanie.PHP_EOL;die();
//
//        $sql = "SELECT `id` FROM `payed_lessons` WHERE `id_person`='".$id_person."'
//		 AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
//        $result = mysql_query($sql) or die(mysql_error());
//        if(mysql_num_rows ($result) == 0){
//            $sql = "INSERT INTO `payed_lessons` (id_person,num_reserved,teacher,timetable,level_start)
//				VALUES('".$id_person."','".$num_lessons_person_on_sochitanie."','".$teacher."','".$timetable."','".$level_start."')";
//            // echo $sql;die();
//            mysql_query($sql) or die(mysql_error());
//        }else{
//            $sql = "UPDATE `payed_lessons` SET `num_reserved`='".$num_lessons_person_on_sochitanie."'
//				WHERE `id_person`='".$id_person."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
//            // echo $sql;die();
//            mysql_query($sql) or die(mysql_error());
//        }
//
//        $sql = "SELECT * FROM `levels_person` WHERE `id`=".$id;
//        $result = mysql_query($sql) or die(mysql_error());
//        if($result){
//            while ($row = mysql_fetch_row($result)){
//                echo $level."|".$fio_person."|".$id_person."|".$id."|".$row[1]."|".$row[2]."|".$row[3]."|".$row[4]."|".$row[5];
//            }
//        }
    }

/////////////////////////////////////////////////////////   GETTERS   /////////////////////////////////////////////////////////

    public function getNumPayedNumReserved($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='".$id."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND `timetable` ='".$timetable."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getCheckForAnyAttendedLessonOfPerson($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT DISTINCT `id` FROM `attendance` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_visit`='".$id."' LIMIT 1";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0])){return $data[0];}else{return;}
    }
    public function getCheckForAnyFrozenDates($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `id` FROM `freeze` WHERE teacher='".$teacher."' AND level_start='".$level_start."' AND timetable='".$timetable."' AND id_person=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getCombinationDates($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate;
    }
    public function getCombinationLevel($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `level` FROM `levels` WHERE `teacher` = '".$teacher."' AND `sd_1` = '".$level_start."' AND `timetable` ='".$timetable."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_NUM);
        return $data;
    }
    public function getIsThereSuchACombination($id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `id` FROM `levels_person` WHERE `id_person`='".$id_person."'
		 AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}
    }
    public function setNewCombination($id_person,$teacher,$timetable,$level_start,$person_start,$person_stop,$level){
        $db = $this->db;
        $sql = "INSERT INTO `levels_person` (level,id_person,teacher,timetable,level_start,person_start,person_stop) VALUES (:level,:id_person,:teacher,:timetable,:level_start,:person_start,:person_stop)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':level', $level, \PDO::PARAM_INT );
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':person_start', $person_start, \PDO::PARAM_STR);
        $stmt->bindParam(':person_stop', $person_stop, \PDO::PARAM_STR);

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function setUpdateCombination($id_person,$teacher,$timetable,$level_start,$person_start,$person_stop){
        $db = $this->db;
        $sql="UPDATE `levels_person` SET `person_start`=:person_start,`person_stop`=:person_stop WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':person_start', $person_start, \PDO::PARAM_STR);
        $stmt->bindParam(':person_stop', $person_stop, \PDO::PARAM_STR);

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'update';
        return $data;
    }
}


