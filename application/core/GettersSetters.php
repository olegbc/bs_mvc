<?php
namespace application\core;
class GettersSetters
{
    public $db;

    public function __construct(){
        $this->db = Db::getInstance();
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
    public function getIdOfFirstTenStudents()
    {
        $db = $this->db;
        $sql = "SELECT `id_person` FROM `levels_person` WHERE `intensive` = 1 ORDER BY `created` LIMIT 10";
        $id_person = $db->query($sql);
        $id_person = $id_person->fetchAll($db::FETCH_COLUMN);
        return $id_person;
    }
    public function getIsItOneOfFirstTenStudents($IdOfFirstTenStudents,$id_person)
    {
        $isItOneOfTheTen = false;
        foreach($IdOfFirstTenStudents as $idCheck){
            if($idCheck == $id_person){$isItOneOfTheTen = true;}
        }
        return $isItOneOfTheTen;
    }
    public function getDefaultCostOfOneLesson($intensive=null,$isFirstTenStudent=null)
    {
        $db = $this->db;
        if($intensive){
            if($isFirstTenStudent){
                $sql = "SELECT `one intensive super` FROM `constants`";
            }else {
                $sql = "SELECT `one intensive default` FROM `constants`";
            }
        }else {
            $sql = "SELECT `one lesson default` FROM `constants`";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['one lesson default'])) {
            $defaultCostOfOneLesson = $data[0]['one lesson default'];
        }
        if(isset($data[0]['one intensive default'])) {
            $defaultCostOfOneLesson = $data[0]['one intensive default'];
        }
        if(isset($data[0]['one intensive super'])) {
            $defaultCostOfOneLesson = $data[0]['one intensive super'];
        }
        return $defaultCostOfOneLesson;
    }
    public function getCostOfOneLessonWithDiscount($discount,$defaultCostOfOneLesson){
        $CostOfOneLessonWithDiscount = $defaultCostOfOneLesson - round(($defaultCostOfOneLesson*($discount*0.01)),2);
        $arr['CostOfOneLessonWithDiscount'] = $CostOfOneLessonWithDiscount;
        return $CostOfOneLessonWithDiscount;
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
    public function getCombinationDatesAttendance($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive) {
            $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10 FROM `levels` WHERE teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND sd_1='" . $level_start . "'";
        }else {
            $sql = "SELECT sd_1,sd_2,sd_3,sd_4,sd_5,sd_6,sd_7,sd_8,sd_9,sd_10,sd_11,sd_12,sd_13,sd_14,sd_15,sd_16,sd_17,sd_18,sd_19,sd_20,sd_21 FROM `levels` WHERE teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND sd_1='" . $level_start . "'";
        }
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        if(!empty($everyLessonDate[0])){return $everyLessonDate[0];}else{return false;}
    }
    /*    public function getPersonStart($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `person_start` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND level_start='" . $level_start . "'";
        }else{
            $sql = "SELECT `person_start` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND level_start='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['person_start'])){$personStart = $data[0]['person_start'];}
        return $personStart;
    }*/
    /*    public function getPersonStop($id,$teacher,$timetable=null,$level_start,$intensive=null){
            $db = $this->db;
            if($intensive){
                $sql = "SELECT `person_stop` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND intensive='" . $intensive . "' AND level_start='" . $level_start . "'";
            }else {
                $sql = "SELECT `person_stop` FROM `levels_person` WHERE id_person=" . $id . " AND teacher='" . $teacher . "' AND timetable='" . $timetable . "' AND level_start='" . $level_start . "'";
            }
            $data = $db->query($sql);
            $data = $data->fetchAll($db::FETCH_ASSOC);
            if(isset($data[0]['person_stop'])){$personStop = $data[0]['person_stop'];}
            return $personStop;
        }*/
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
    public function getAllCombinationsOfThisPerson($id){
        $db = $this->db;
        $sql="SELECT `teacher`,`timetable`,`level_start`,`level`,`intensive`,`created` FROM `levels_person` WHERE id_person=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getBalance($id){
        $db = $this->db;
        $sql="SELECT `balance` FROM `balance` WHERE id_person=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data[0]['balance'])){$balance = $data[0]['balance'];}else{$balance = 0;}
        return $balance;
    }
    public function getFrozenDates($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `frozen_day` FROM `freeze` WHERE `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'  AND `timetable`='" . $timetable . "'  AND `id_person`=" . $id;
        }else{
            $sql = "SELECT `frozen_day` FROM `freeze` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "'  AND `timetable`='" . $timetable . "'  AND `id_person`=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getPersonDiscountReason($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `discount`,`reason` FROM `discounts` WHERE `id_person`='" . $id . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'";
        }else {
            $sql = "SELECT `discount`,`reason` FROM `discounts` WHERE `id_person`='" . $id . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        if(isset($data['0'])){
            return $data['0'];
        }else{
            return $data;
        }
    }
    public function getAllCombinations($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT * FROM `levels` WHERE `sd_1`='" . $level_start . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "'";
        }else{
            $sql = "SELECT * FROM `levels` WHERE `sd_1`='" . $level_start . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    public function getDatesOfVisit($id,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.intensive='" . $intensive . "' AND `id_person`=" . $id;
        }else {
            $sql = "SELECT `date_of_visit` FROM `levels_person` LEFT JOIN `attendance` ON levels_person.id_person = attendance.id_visit WHERE levels_person.teacher='" . $teacher . "' AND levels_person.level_start='" . $level_start . "' AND levels_person.timetable='" . $timetable . "' AND `id_person`=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getName($id){
        $db = $this->db;
        $sql = "SELECT `fio` FROM `main` WHERE `id`='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        $name = $data[0]['fio'];
        return $name;
    }
    public function getSumGiven($id){
        $db = $this->db;
        $sql = "SELECT `given` FROM `payment_has` WHERE `fio_id`=".$id;
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_NUM);
        $sum = 0;
        foreach($data as $value){
            $sum = $sum + $value[0];
        }
        return $sum;
    }
    public function getNumPayedNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='" . $id . "' AND `teacher` = '" . $teacher . "' AND `level_start` = '" . $level_start . "' AND `intensive` ='" . $intensive . "'";
        }else {
            $sql = "SELECT `num_payed`,`num_reserved` FROM `payed_lessons` WHERE `id_person` ='" . $id . "' AND `teacher` = '" . $teacher . "' AND `level_start` = '" . $level_start . "' AND `timetable` ='" . $timetable . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getCheckForAnyAttendedLessonOfPerson($id,$teacher=null,$timetable,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT DISTINCT `id` FROM `attendance` WHERE `teacher`='".$teacher."' AND `intensive`='".$intensive."' AND `level_start`='".$level_start."' AND `id_visit`='".$id."' LIMIT 1";
        }else {
            $sql = "SELECT DISTINCT `id` FROM `attendance` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_visit`='".$id."' LIMIT 1";
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
    public function getIsThereDiscountForThisCombinationAndThisStudent($id,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql="SELECT `discount` FROM `discounts` WHERE `teacher`='".$teacher."' AND `intensive`='".$intensive."' AND `level_start`='".$level_start."' AND `id_person`='".$id."'";
        }else{
            $sql="SELECT `discount` FROM `discounts` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `id_person`='".$id."'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}
    }
    public function getIsThereDiscountReasonForThisCombinationAndThisStudent($id,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `reason` FROM `discounts` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' AND `id_person`='" . $id . "'";
        }else {
            $sql = "SELECT `reason` FROM `discounts` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' AND `id_person`='" . $id . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}
    }

    public function setUpdateBalance($balanceUpdate,$id){
        $db = $this->db;
        $sql="UPDATE `balance` SET `balance`=:balanceUpdate WHERE `id_person`=:id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':balanceUpdate', $balanceUpdate, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateNumPayed($num_payedUpdate,$id,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql="UPDATE `payed_lessons` SET `num_payed`=:num_payedUpdate WHERE `id_person`=:id AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else{
            $sql="UPDATE `payed_lessons` SET `num_payed`=:num_payedUpdate WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_payedUpdate', $num_payedUpdate, \PDO::PARAM_INT);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateDiscont($discountValue,$teacher,$timetable,$level_start,$id,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `discounts` SET `discount`=:discountValue WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else{
            $sql = "UPDATE `discounts` SET `discount`=:discountValue WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':discountValue', $discountValue, \PDO::PARAM_INT);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setInsertDiscont($discountValue,$teacher,$timetable,$level_start,$id,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`intensive`,`level_start`,`discount`) VALUE (:id,:teacher,:intensive,:level_start,:discountValue)";
        }else {
            $sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`timetable`,`level_start`,`discount`) VALUE (:id,:teacher,:timetable,:level_start,:discountValue)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':discountValue', $discountValue, \PDO::PARAM_INT);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function setUpdateDiscontReason($discountReason,$teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `discounts` SET `reason`=:discountReason WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else{
            $sql = "UPDATE `discounts` SET `reason`=:discountReason WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':discountReason', $discountReason, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setInsertDiscontReason($discountReason,$teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`intensive`,`level_start`,`reason`) VALUE (:id,:teacher,:intensive,:level_start,:discountReason)";
        }else {
            $sql = "INSERT INTO `discounts` (`id_person`,`teacher`,`timetable`,`level_start`,`reason`) VALUE (:id,:teacher,:timetable,:level_start,:discountReason)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':discountReason', $discountReason, \PDO::PARAM_INT);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function setDeletePersonOnThisCombinationFromLevelsPerson($teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "DELETE FROM `levels_person` WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else{
            $sql = "DELETE FROM `levels_person` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonOnThisCombinationFromPayedLessons($teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "DELETE FROM `payed_lessons` WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else {
            $sql = "DELETE FROM `payed_lessons` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }

    //////// FROZEN ////////
    public function getFrozenDateId($date,$id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `id` FROM `freeze` WHERE `frozen_day`='" . $date . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' AND `id_person`='" . $id . "'";
        }else {
            $sql = "SELECT `id` FROM `freeze` WHERE `frozen_day`='" . $date . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' AND `id_person`='" . $id . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function setInsertFrozenDateToDb($frozenDate,$id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `freeze` (frozen_day,id_person,teacher,intensive,level_start) VALUES(:frozenDate,:id,:teacher,:intensive,:level_start)";
        }else{
            $sql = "INSERT INTO `freeze` (frozen_day,id_person,teacher,timetable,level_start) VALUES(:frozenDate,:id,:teacher,:timetable,:level_start)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':frozenDate', $frozenDate, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'insert';
        return $data;
    }
    public function setUpdateDecreseNumPayedNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=`num_reserved`-1,`num_payed`=`num_payed`-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else{
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=`num_reserved`-1,`num_payed`=`num_payed`-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateIncreseBalance($costOfOneLessonWithDiscount,$id){
        $db = $this->db;
        $sql = "UPDATE `balance` SET `balance`=balance+:costOfOneLessonWithDiscount WHERE `id_person`=:id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':costOfOneLessonWithDiscount', $costOfOneLessonWithDiscount, \PDO::PARAM_INT );
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setDeleteFrozenDate($frozenDateId){
        $db = $this->db;
        $sql = "DELETE FROM `freeze` WHERE `id`=:existedFrozenDateId";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':existedFrozenDateId', $frozenDateId, \PDO::PARAM_INT );
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setUpdateIncreseNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved+1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else{
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved+1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    public function setUpdateDecreseNumReserved($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else{
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-1 WHERE `id_person`=:id_person AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    //////// MAIN //////////
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
    public function getIsThereAPersonBalance($id){
        $db = $this->db;
        $sql = "SELECT `balance` FROM `balance` WHERE  `id_person`='".$id."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}

    }
    public function getIfStudentExists($name){
        $db = $this->db;
        $sql="SELECT `id` FROM `main` WHERE `fio`='".$name."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}

    }
    public function getRowByIdFromMain($id){
        $db = $this->db;
        $sql="SELECT `fio`,`dog_num` FROM `main` WHERE `id` ='".$id."'";
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
        }else{
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
    public function getAllCombinationsExisted(){
        $db = $this->db;
        $sql = "SELECT `teacher`, `timetable`, `sd_1`,`level`,`status` FROM `levels` ORDER BY `teacher` ";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    ////////// ATTENDANCE ///////////
    public function getPersonIdStartStop($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `id_person`,`person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'";
        }else {
            $sql = "SELECT `id_person`,`person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getIsItAnArchiveCombination($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive) {
            $sql = "SELECT `archive` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `sd_1`='" . $level_start . "' AND `intensive`='" . $intensive . "'";
        }else{
            $sql = "SELECT `archive` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `sd_1`='" . $level_start . "' AND `timetable`='" . $timetable . "'";
        }
        $everyLessonDate = $db->query($sql);
        $everyLessonDate = $everyLessonDate->fetchAll($db::FETCH_NUM);
        return $everyLessonDate[0];
    }
    public function getAttenedDates($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `intensive`='" . $intensive . "' AND `id_visit`=" . $id;
        }else{
            $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `timetable`='" . $timetable . "' AND `id_visit`=" . $id;
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getAllCombinationsExistedFromLevels(){
        $db = $this->db;
        $sql = "SELECT `teacher`, `timetable`, `sd_1`,`level`,`archive`,`intensive` FROM `levels` ORDER BY `teacher` ";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getIdFromAttendanceTable($attenedDate,$teacher,$timetable=null,$level_start,$id,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `id` FROM `attendance` WHERE `date_of_visit`='" . $attenedDate . "' AND `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "' AND `id_visit`='" . $id . "'";
        }else {
            $sql = "SELECT `id` FROM `attendance` WHERE `date_of_visit`='" . $attenedDate . "' AND `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "' AND `id_visit`='" . $id . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getIdOfCombination($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `id` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `sd_1`='" . $level_start . "'";
        }else {
            $sql = "SELECT `id` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `sd_1`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data[0];
    }
    public function getAllBadDaysOfCombination($teacher,$timetable,$level_start){
        $db = $this->db;
        $sql = "SELECT `bad_day` FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getAttendance($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='".$teacher."' AND `intensive`='".$intensive."' AND `level_start`='".$level_start."'";
        }else {
            $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function getPayment($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "SELECT `num_payed` FROM `payed_lessons` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `level_start`='" . $level_start . "'";
        }else {
            $sql = "SELECT `num_payed` FROM `payed_lessons` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `level_start`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function setUpdatePersonStartEqualCalculatedLevelStop($calculatedLevelStop,$teacher,$timetable,$level_start,$id_person){
//        return $calculatedLevelStop;
        $DayOfCalculatedLevelStop = date("Y-m-d",$calculatedLevelStop);
        $db = $this->db;
        $sql="UPDATE `levels_person` SET `person_start`=:calculatedLevelStop  WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':calculatedLevelStop', $DayOfCalculatedLevelStop, \PDO::PARAM_STR);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);

        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setInsertFromAttendanceTable($id,$attenedDate,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "INSERT INTO `attendance` (`date_of_visit`,`id_visit`,`teacher`,`intensive`,`level_start`) VALUES(:date_of_visit,:id_visit,:teacher,:intensive,:level_start)";
        }else {
            $sql = "INSERT INTO `attendance` (`date_of_visit`,`id_visit`,`teacher`,`timetable`,`level_start`) VALUES(:date_of_visit,:id_visit,:teacher,:timetable,:level_start)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':date_of_visit', $attenedDate, \PDO::PARAM_INT );
        $stmt->bindParam(':id_visit', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR );
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR );
        $stmt->execute();

        $data['lastInsert'] = $db->lastInsertId();
        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'insert';
        return $data;
    }
    public function setDeleteIdFromAttendanceTable($existedId){
        $db = $this->db;
        $sql = "DELETE FROM `attendance` WHERE `id`=:existedId";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':existedId', $existedId, \PDO::PARAM_INT );
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeleteCombination($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "DELETE FROM `levels` WHERE `teacher`=:teacher AND `intensive`=:intensive AND `sd_1`=:level_start";
        }else {
            $sql = "DELETE FROM `levels` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromAttendance($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "DELETE FROM `attendance` WHERE `id_visit`=:id AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else {
            $sql = "DELETE FROM `attendance` WHERE `id_visit`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromDiscount($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "DELETE FROM `discounts` WHERE `id_person`=:id AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else {
            $sql = "DELETE FROM `discounts` WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromFreeze($id,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if ($intensive) {
            $sql = "DELETE FROM `freeze` WHERE `id_person`=:id AND `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start";
        }else {
            $sql = "DELETE FROM `freeze` WHERE `id_person`=:id AND `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id, \PDO::PARAM_INT );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromLevelsPeson($id, $teacher, $timetable=null, $level_start, $intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "DELETE FROM `levels_person` WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else{
            $sql = "DELETE FROM `levels_person` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setDeletePersonCombinationFromPayedLessons($id, $teacher, $timetable=null, $level_start, $intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "DELETE FROM `payed_lessons` WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id";
        }else {
            $sql = "DELETE FROM `payed_lessons` WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['lastInsert'] = $db->lastInsertId();
        $data['state'] = 'delete';
        return $data;
    }
    public function setUpdateBalanceAttendance($costOfOneLessonWithDiscount,$num_minus,$id_person){
        $db = $this->db;
        $sql="UPDATE `balance` SET `balance`=balance+:costOfOneLessonWithDiscount*:num_minus WHERE `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':costOfOneLessonWithDiscount', $costOfOneLessonWithDiscount, \PDO::PARAM_INT);
        $stmt->bindParam(':num_minus', $num_minus, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateBalanceWithBackToBalanceSum($backToBalanceSum,$id){
        $db = $this->db;
        $sql="UPDATE `balance` SET `balance`= balance+:backToBalanceSum WHERE `id_person`=:id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':backToBalanceSum', $backToBalanceSum, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateNumPayedToPayedLessons($num_minus,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_payed`=num_payed-:num_minus WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `payed_lessons` SET `num_payed`=num_payed-:num_minus WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_minus', $num_minus, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateNumReservedToPayedLessons($num_minus_reserverd,$id_person,$teacher,$timetable,$level_start){
        $db = $this->db;
        $sql="UPDATE `payed_lessons` SET `num_reserved`=num_reserved-(:num_minus_reserverd-1) WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_minus_reserverd', $num_minus_reserverd, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateNumReservedByNumMinusToPayedLessons($num_minus,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-:num_minus WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `payed_lessons` SET `num_reserved`=num_reserved-:num_minus WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':num_minus', $num_minus, \PDO::PARAM_INT);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdatePersonStopToLevelsPerson($calculatedLevelStop,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
//        return $calculatedLevelStop;
        if(!is_string($calculatedLevelStop)){$calculatedLevelStop = date('Y-m-d',$calculatedLevelStop);}
        if($intensive){
            $sql = "UPDATE `levels_person` SET `person_stop`=:calculatedLevelStop WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `levels_person` SET `person_stop`=:calculatedLevelStop WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':calculatedLevelStop', $calculatedLevelStop, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdatePersonStopWithNewPersonStopToLevelsPerson($new_person_stop,$id_person,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `levels_person` SET `person_stop`=:new_person_stop WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `levels_person` SET `person_stop`=:new_person_stop WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_person_stop', $new_person_stop, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `levels_person` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `levels_person` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToPayedLessons($new_level_start,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `payed_lessons` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `payed_lessons` SET `level_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdatePersonStartToLevelsPerson($new_level_start,$id_person,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `levels_person` SET `person_start`=:new_level_start WHERE `teacher`=:teacher AND `intensive`=:intensive AND `level_start`=:level_start AND `id_person`=:id_person";
        }else {
            $sql = "UPDATE `levels_person` SET `person_start`=:new_level_start WHERE `teacher`=:teacher AND `timetable`=:timetable AND `level_start`=:level_start AND `id_person`=:id_person";
        }

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':new_level_start', $new_level_start, \PDO::PARAM_STR);
        $stmt->bindParam(':id_person', $id_person, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateLevelStartToLevels($i,$newDatesOfCombination,$teacher,$timetable,$level_start,$intensive){
        $db = $this->db;
        $i = $i+1;
        if($intensive){
            $sql = "UPDATE `levels` SET sd_:i=:newDatesOfCombination WHERE `teacher`=:teacher AND `intensive`=:intensive AND `sd_1`=:level_start";
        }else {
            $sql = "UPDATE `levels` SET sd_:i=:newDatesOfCombination WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':i', $i, \PDO::PARAM_INT);
        $stmt->bindParam(':newDatesOfCombination', $newDatesOfCombination, \PDO::PARAM_STR);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    public function setUpdateArchive($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        $archiveTrue = 1;
        if($intensive){
            $sql = "UPDATE `levels` SET `archive`=:archiveTrue WHERE `teacher`=:teacher AND `intensive`=:intensive AND `sd_1`=:level_start";
        }else {
            $sql = "UPDATE `levels` SET `archive`=:archiveTrue WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':archiveTrue', $archiveTrue, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;

    }
    /////// LEVEL CALCULATION /////
    public function getDoesCombinationExist($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `id` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `intensive`='" . $intensive . "' AND `sd_1`='" . $level_start . "'";
        }else {
            $sql = "SELECT `id` FROM `levels` WHERE `teacher`='" . $teacher . "' AND `timetable`='" . $timetable . "' AND `sd_1`='" . $level_start . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    public function setInsertIntoLevelsTable($level,$teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "INSERT INTO `levels` (`level`,`teacher`,`intensive`,`sd_1`) VALUES(:level,:teacher,:intensive,:level_start)";
        }else {
            $sql = "INSERT INTO `levels` (`level`,`teacher`,`timetable`,`sd_1`) VALUES(:level,:teacher,:timetable,:level_start)";
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':level', $level, \PDO::PARAM_STR );
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR );
        if($intensive){
            $stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);
        }
        if($intensive == 1){$intensive = true;}
        if(!$intensive){
            $stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);
        }
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR );
        $stmt->execute();

        $data['lastInsert'] = $db->lastInsertId();
        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'insert';
        return $data;
    }
    public function setIntensiveToTrueAtLevels($teacher, $timetable=null, $level_start, $intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "UPDATE `levels` SET intensive=:IntensiveTrue WHERE `teacher`=:teacher AND `intensive`=:intensive AND `sd_1`=:level_start";
        }else {
            $sql = "UPDATE `levels` SET intensive=:IntensiveTrue WHERE `teacher`=:teacher AND `timetable`=:timetable AND `sd_1`=:level_start";
        }
        $stmt = $db->prepare($sql);

        $true = 1;

        $stmt->bindParam(':IntensiveTrue', $true, \PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, \PDO::PARAM_STR);
        if($intensive){$stmt->bindParam(':intensive', $intensive, \PDO::PARAM_INT);}
        if(!$intensive){$stmt->bindParam(':timetable', $timetable, \PDO::PARAM_STR);}
        $stmt->bindParam(':level_start', $level_start, \PDO::PARAM_STR);
        $stmt->execute();

        $data['errorCode'] = $stmt->errorCode();
        $data['rowCount'] = $stmt->rowCount();
        $data['state'] = 'update';
        return $data;
    }
    ////// NUMBER OF STUDENTS //////
    public function getallTeachers(){
        $db = $this->db;
        $sql="SELECT DISTINCT `teacher` FROM `levels`";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    public function getPersonStratStopOfEveryStudentOfThisTeacher($thisTeacher){
        $db = $this->db;
        $sql = "SELECT `person_start`,`person_stop` FROM `levels_person` WHERE `teacher`='".$thisTeacher."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_ASSOC);
        return $data;
    }
    /////// AMOUNT OF MONEY /////
    public function getSumMoneyForThisWeek($start_week,$stop_week){
        $db = $this->db;
        $sql = "SELECT SUM(`given`) FROM `payment_has` WHERE `date` between '".$start_week."' AND '".$stop_week."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }
    /////// SPU /////
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
    /////// BAD DAYS //////
    public function getIsThisBadDayExist($teacher,$timetable,$level_start,$badDayClicked){
        $db = $this->db;
        $sql = "SELECT `id` FROM `bad_days` WHERE `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."' AND `bad_day`='".$badDayClicked."'";
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        if(empty($data)){return false;}else{return true;}
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
    public function getAllAttenedDates($teacher,$timetable=null,$level_start,$intensive=null){
        $db = $this->db;
        if($intensive){
            $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `intensive`='" . $intensive . "'";
        }else{
            $sql = "SELECT `date_of_visit` FROM `attendance` WHERE `teacher`='" . $teacher . "' AND `level_start`='" . $level_start . "' AND `timetable`='" . $timetable . "'";
        }
        $data = $db->query($sql);
        $data = $data->fetchAll($db::FETCH_COLUMN);
        return $data;
    }

}