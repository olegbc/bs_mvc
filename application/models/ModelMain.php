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
    public function studentStartStop(){
        $id = $_POST['id'];
        $teacher = $_POST["teacher"];
        $timetable = false;
        if(isset($_POST['timetable'])){$timetable = $_POST["timetable"];}
        $level_start = $_POST["level_start"];
        $intensive = false;
        if(isset($_POST['intensive'])){$intensive = $_POST["intensive"];}

        $data['studentStart'] = $this->getPersonStart($id,$teacher,$timetable,$level_start,$intensive);
        $data['studentStop'] = $this->getPersonStop($id,$teacher,$timetable,$level_start,$intensive);
        return $data;
    }
    public function timetables(){
        $teacher = $_POST['teacher'];
        $data = $this->getTimetables($teacher);
        return $data;
    }
    public function intensiveLevelStart(){
        $teacher = $_POST['teacher'];
        $data = $this->getLevelStart($teacher);
        return $data;
    }
    public function levelStart(){
        $teacher = $_POST['teacher'];
        $timetable = $_POST['timetable'];
        $data = $this->getLevelStart($teacher,$timetable);
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
        $intensive = $_POST["intensive"];

        $data = $this->getNumPayedNumReserved($id,$teacher,$timetable,$level_start,$intensive);
        if(isset($data[0])){
            if($data[0]['num_payed'] > 0){$payedLessonExists=1;}
        }

        $data = $this->getCheckForAnyAttendedLessonOfPerson($id,$teacher,$timetable,$level_start,$intensive);
        if(isset($data['id'])){$attendedLessonExists=1;}

        $data = $this->getCheckForAnyFrozenDates($id,$teacher,$timetable,$level_start,$intensive);
        if(!empty($data)){$frozenLessonExists=1;}

        if($payedLessonExists==1 or $attendedLessonExists==1 or $frozenLessonExists==1){return true;}else{return false;}
    }
    public function levelCombinationDates(){
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $intensive = $_POST["intensive"];

        $data['combinationDates'] = $this->getCombinationDates($teacher,$timetable,$level_start,$intensive);
        if(!$intensive){$data['combinationLevel'] = $this->getCombinationLevel($teacher,$timetable,$level_start);}
        return $data;
    }
    public function saveUpdateStudentCombination(){
        $intensive = 0;
        $level = false;
        $timetable = false;
        if(isset($_POST["IntensiveCheckIn"])){$intensive = true;}
        if(!$intensive){
            $level = $_POST["level_soch"];
            $timetable = $_POST["timetable_sel"];
        }
        $teacher = $_POST["teacher"];
        $level_start = $_POST["level_start_sel"];
        $person_start = $_POST["person_start_sel"];
        $person_stop = $_POST["person_stop_sel"];
        $id = $_POST["id_person"];
        $dataMain['fio_person'] = $fio_person = $_POST["fio_person"];

        $data = $this->getIsThereStudentOnASuchCombination($id,$teacher,$timetable,$level_start,$intensive);

        if($data){
            $data = $this->setUpdatePersonStartStopToLevelPerson($id,$teacher,$timetable,$level_start,$person_start,$person_stop,$intensive);
            return $data;
        }else{
            $this->setInsertPersonStartStopToLevelPerson($id,$teacher,$timetable,$level_start,$person_start,$person_stop,$intensive);
        }

        $everyLessonDate = $this->getCombinationDates($teacher,$timetable,$level_start,$intensive);


        $dataMain['personStart'] = $personStart = $this->getPersonStart($id,$teacher,$timetable,$level_start,$intensive);
        $dataMain['personStop'] = $personStop = $this->getPersonStop($id,$teacher,$timetable,$level_start,$intensive);
        foreach($everyLessonDate as $key=>$value){
            for($u=0;$u<count($everyLessonDate[0]);$u++){
                if($value[$u] == $personStart){$numberOfStartLesson=$u;};
                if($value[$u] == $personStop){$numberOfStopLesson=$u;};
            }
        }

        $dataMain['numOfLessonsOnCombination'] = $numOfLessonsOnCombination = (abs($numberOfStopLesson - $numberOfStartLesson))+1;

        $data = $this->getIsAnyPayedLessons($id,$teacher,$timetable,$level_start,$intensive);
        if($data){
            $data = $this->getUpdateNumReservedToPayedLessons($id,$teacher,$timetable,$level_start,$numOfLessonsOnCombination,$intensive);
            $dataMain['state'] = 'update';
            $dataMain['MMMMM'] = $data;
        }else{
            $this->getInsertNumPayedNumReservedToPayedLessons($id,$teacher,$timetable,$level_start,$numOfLessonsOnCombination,$intensive);
            $dataMain['state'] = 'insert';
        }

        if($level){$dataMain['level'] = $level;}

        return $dataMain;
    }
    public function saveAmountOfMoney(){
        $id = $_POST["id"];
        $AmountOfMoney = $_POST["amount"];

        $data = $this->setInsertAmountOfMoneyToPaymentHas($AmountOfMoney,$id);

        if($data){
            $data = $this->getIsThereAPersonBalance($id);

            if ($data) {
                $this->setUpdateBalanceToBalance($AmountOfMoney, $id);
            } else {
                $this->setInsertBalanceToBalance($AmountOfMoney, $id);
            }
            return (bool)true;
        }else{return (bool)false;}
    }
    public function deleteStudent(){
        $id = $_POST["id"];

        $this->setDeleteStudent($id);
    }
    public function addStudent(){
        $name = $_POST["name"];

        $data = $this->getIfStudentExists($name);
//        return $data;

        if($data){
            return $data['studentExisted'] = true;
        }else{
            $data = $this->setInsertStudentToMain($name);
            $id = $data['lastInsert'];
            $data = $this->getRowByIdFromMain($id);
            $data[0]['id'] = $id;
            $data[0]['studentExisted'] = false;

            return $data[0];
        }
    }
    public function buildingBlocks(){
        $data = $this->getAllCombinations();
        return $data;
    }


/////////////////////////////////////////////////////////   GETTERS/SETTERS   /////////////////////////////////////////////////////////

    public function getLevelStart($teacher,$timetable=null){
        $db = $this->db;
        if($timetable==null){
            $sql = "SELECT `sd_1` FROM `levels` WHERE `teacher`='".$teacher."' AND `intensive`=1";
        }else{
            $sql = "SELECT `sd_1` FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."'  AND `intensive`=0";;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getTimetables($teacher){
        $db = $this->db;
        $sql = "SELECT DISTINCT `timetable` FROM `levels` WHERE `teacher`='".$teacher."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getNumPayedNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql="SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='".$id."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND `intensive` ='".$intensive."'";
        }else{
            $sql="SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='".$id."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND `timetable` ='".$timetable."'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getCheckForAnyAttendedLessonOfPerson($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT DISTINCT `id` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' AND `id_visit`='" . $id . "' LIMIT 1";
        }else{
            $sql = "SELECT DISTINCT `id` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' AND `id_visit`='" . $id . "' LIMIT 1";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0])){return $data[0];}else{return;}
    }
    public function getCheckForAnyFrozenDates($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `id` FROM `freeze` WHERE teacher='" . $teacher . "' AND level_start='" . $level_start . "' AND intensive='" . $intensive . "' AND id_person=" . $id;
        }else {
            $sql = "SELECT `id` FROM `freeze` WHERE teacher='" . $teacher . "' AND level_start='" . $level_start . "' AND timetable='" . $timetable . "' AND id_person=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getCombinationDates($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10 FROM `levels` WHERE levels.teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND sd_1='" . $level_start . "'";
        }else {
            $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND sd_1='" . $level_start . "'";
        }
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
    public function getIntensiveCombinationDates($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14 FROM `levels` WHERE levels.teacher='".$teacher."' AND levels.timetable='".$timetable."' AND levels.sd_1='".$level_start."'";
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate;
    }
    public function getIsThereStudentOnASuchCombination($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `id` FROM `levels_person` WHERE `id_person`='".$id."'
		 AND `teacher`='".$teacher."' AND `intensive`='".$intensive."' AND `level_start`='".$level_start."'";
        }else{
            $sql = "SELECT `id` FROM `levels_person` WHERE `id_person`='".$id."'
		 AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}
    }
    public function setInsertPersonStartStopToLevelPerson($id,$teacher,$timetable=null,$level_start,$person_start,$person_stop,$level,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `levels_person` (level,id_person,teacher,intensive,level_start,person_start,person_stop) VALUES (:level,:id_person,:teacher,:intensive,:level_start,:person_start,:person_stop)";
        }else {
            $sql = "INSERT INTO `levels_person` (level,id_person,teacher,timetable,level_start,person_start,person_stop) VALUES (:level,:id_person,:teacher,:timetable,:level_start,:person_start,:person_stop)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':level', $level, \PDO::PARAM_INT );
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT );}
        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
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
    public function setUpdatePersonStartStopToLevelPerson($id,$teacher,$timetable,$level_start,$person_start,$person_stop,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `levels_person` SET `person_start`=:person_start,`person_stop`=:person_stop WHERE `id_person`=:id_person AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else {
            $sql = "UPDATE `levels_person` SET `person_start`=:person_start,`person_stop`=:person_stop WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT );}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':person_start', $person_start, \PDO::PARAM_STR);
        $stmt->bindParam(':person_stop', $person_stop, \PDO::PARAM_STR);

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['intensive'] = $intensive;
        $data['state'] = 'update';
        return $data;
    }
    public function getPersonStart($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `person_start` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND level_start='" . $level_start . "'";
        }else{
            $sql = "SELECT `person_start` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND level_start='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['person_start'])){$personStart = $data[0]['person_start']; return $personStart;}
        return false;
    }
    public function getPersonStop($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `person_stop` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND level_start='" . $level_start . "'";
        }else {
            $sql = "SELECT `person_stop` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND level_start='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['person_stop'])){$personStop = $data[0]['person_stop']; return $personStop;}
        return false;
    }
    public function getIsAnyPayedLessons($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `id` FROM `payed_lessons` WHERE `id_person`='" . $id . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' LIMIT 1";
        }else {
            $sql = "SELECT `id` FROM `payed_lessons` WHERE `id_person`='" . $id . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' LIMIT 1";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0])){return true;}else{return false;}

    }
    public function getInsertNumPayedNumReservedToPayedLessons($id,$teacher,$timetable=null,$level_start,$numOfLessonsOnCombination,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `payed_lessons` (id_person,num_reserved,teacher,intensive,level_start) VALUES (:id_person,:num_reserved,:teacher,:intensive,:level_start)";
        }else {
            $sql = "INSERT INTO `payed_lessons` (id_person,num_reserved,teacher,timetable,level_start) VALUES (:id_person,:num_reserved,:teacher,:timetable,:level_start)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_reserved', $numOfLessonsOnCombination, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT );}
        $stmt->bindParam(':id_person', $id, \PDO::PARAM_STR );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'insert';
        return $data;

    }
    public function getUpdateNumReservedToPayedLessons($id,$teacher,$timetable=null,$level_start,$numOfLessonsOnCombination,$intensive=null){
//        $arr[] = $id;
//        $arr[] = $teacher;
//        $arr[] = $timetable;
//        $arr[] = $level_start;
//        $arr[] = $numOfLessonsOnCombination;
//        $arr[] = $intensive;
//        return $arr;
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=:num_reserved WHERE `id_person`=:id_person AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else {
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=:num_reserved WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_reserved', $numOfLessonsOnCombination, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT );}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function getAllFieldsFromLevelPersons($id,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `level`,`teacher`,`timetable`,`level_start`,`person_start`,`person_stop` FROM `levels_person` WHERE `id_person`='".$id."' AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' LIMIT 1";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;

    }
    public function setInsertAmountOfMoneyToPaymentHas($AmountOfMoney,$id){
        $db = $this->db;
        $sql = "INSERT INTO `payment_has` (`given`,`fio_id`) VALUES(:given,:id)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':given', $AmountOfMoney, \PDO::PARAM_INT );
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        try{
            $stmt->execute();
            return true;
        }catch(\PDOException $e){
            return false;
        }

//        $data['errorCode'] = $stmt->errorCode();
//        $data['rowCount'] = $stmt->rowCount();
//        $data['lastInsert'] = $db->lastInsertId();
//        $data['state'] = 'insert';
//        return $data;
//        return true;
    }
    public function getIsThereAPersonBalance($id){
        $db = $this->db;
        $sql = "SELECT `balance` FROM `balance` WHERE  `id_person`='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}

    }
    public function setUpdateBalanceToBalance($AmountOfMoney,$id){
        $db = $this->db;
        $sql = "UPDATE `balance` SET `balance`=`balance`+:amount WHERE `id_person`=:id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':amount', $AmountOfMoney, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setInsertBalanceToBalance($AmountOfMoney,$id){
        $db = $this->db;
        $sql = "INSERT INTO `balance` (`id_person`,`balance`) VALUES(:id,:amount)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':amount', $AmountOfMoney, \PDO::PARAM_INT );
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function setDeleteStudent($id){
        $db = $this->db;
        $sql = "DELETE FROM main WHERE id=:id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function getIfStudentExists($name){
        $db = $this->db;
        $sql="SELECT `id` FROM `main` WHERE `fio`='".$name."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}

    }
    public function setInsertStudentToMain($name){
        $db = $this->db;
        $sql = "INSERT INTO `main` (`fio`) VALUES(:name)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $name, \PDO::PARAM_STR );

        $stmt->execute();

        $data['lastInsert'] = $db->lastInsertId();
        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function getRowByIdFromMain($id){
        $db = $this->db;
        $sql="SELECT `fio`,`dog_num` FROM `main` WHERE `id` ='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getAllCombinations(){
        $db = $this->db;
        $sql = "SELECT `teacher`, `timetable`, `sd_1`,`level`,`status` FROM `levels` ORDER BY `teacher` ";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getIsCombinationAnIntensive($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `intensive` FROM `levels` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `sd_1`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
        if(empty($data)){return false;}else{return true;}
    }
}


