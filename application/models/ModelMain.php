<?php
namespace application\models;
class ModelMain extends \application\core\Model
{
    public function __construct(){
        parent::__construct();
    }

	public function main()
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
        $gettersSetters = $this->gettersSetters;
        $id = $_POST['id'];
        $teacher = $_POST["teacher"];
        $timetable = false;
        if(isset($_POST['timetable'])){$timetable = $_POST["timetable"];}
        $level_start = $_POST["level_start"];
        $intensive = false;
        if(isset($_POST['intensive'])) {
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

        $data['studentStart'] = $gettersSetters->getPersonStart($id,$teacher,$timetable,$level_start,$intensive);
        $data['studentStop'] = $gettersSetters->getPersonStop($id,$teacher,$timetable,$level_start,$intensive);
        return $data;
    }
    public function timetables(){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST['teacher'];
        $data = $gettersSetters->getTimetables($teacher);
        return $data;
    }
    public function intensiveLevelStart(){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST['teacher'];
        $data = $gettersSetters->getLevelStart($teacher);
        return $data;
    }
    public function levelStart(){
        $gettersSetters = $this->gettersSetters;
        $teacher = $_POST['teacher'];
        $timetable = $_POST['timetable'];
        $data = $gettersSetters->getLevelStart($teacher,$timetable);
        return $data;
    }
    public function areAnyPayedOrAttenedOrFrozenLessonsExist(){
        $gettersSetters = $this->gettersSetters;
        $payedLessonExists=0;
        $attendedLessonExists=0;
        $frozenLessonExists=0;
        $id = $_POST['id'];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $level_start = $_POST["level_start"];
        $intensive = false;
        if(isset($_POST['intensive'])) {
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

        $data = $gettersSetters->getNumPayedNumReserved($id,$teacher,$timetable,$level_start,$intensive);
        if(isset($data[0])){
            if($data[0]['num_payed'] > 0){$payedLessonExists=1;}
        }

        $data = $gettersSetters->getCheckForAnyAttendedLessonOfPerson($id,$teacher,$timetable,$level_start,$intensive);
        if(isset($data['id'])){$attendedLessonExists=1;}

        $data = $gettersSetters->getCheckForAnyFrozenDates($id,$teacher,$timetable,$level_start,$intensive);
        if(!empty($data)){$frozenLessonExists=1;}

        if($payedLessonExists==1 or $attendedLessonExists==1 or $frozenLessonExists==1){return true;}else{return false;}
    }
    public function levelCombinationDates(){
        $gettersSetters = $this->gettersSetters;
        $level_start = $_POST["level_start"];
        $teacher = $_POST["teacher"];
        $timetable = $_POST["timetable"];
        $intensive = false;
        if(isset($_POST['intensive'])) {
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

        $data['combinationDates'] = $gettersSetters->getCombinationDates($teacher,$timetable,$level_start,$intensive);
        if(!$intensive){$data['combinationLevel'] = $gettersSetters->getCombinationLevel($teacher,$timetable,$level_start);}
        return $data;
    }
    public function saveUpdateStudentCombination(){
        $gettersSetters = $this->gettersSetters;
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

        $data = $gettersSetters->getIsThereStudentOnASuchCombination($id,$teacher,$timetable,$level_start,$intensive);

        if($data){
            $gettersSetters->setUpdatePersonStartStopToLevelPerson($id,$teacher,$timetable,$level_start,$person_start,$person_stop,$intensive);
            $dataMain['state'] = 'update';
        }else{
            $gettersSetters->setInsertPersonStartStopToLevelPerson($id,$teacher,$timetable,$level_start,$person_start,$person_stop,$level,$intensive);
            $dataMain['state'] = 'insert';
        }

        $everyLessonDate = $gettersSetters->getCombinationDates($teacher,$timetable,$level_start,$intensive);


        $dataMain['personStart'] = $personStart = $gettersSetters->getPersonStart($id,$teacher,$timetable,$level_start,$intensive);
        $dataMain['personStop'] = $personStop = $gettersSetters->getPersonStop($id,$teacher,$timetable,$level_start,$intensive);
        foreach($everyLessonDate as $key=>$value){
            for($u=0;$u<count($everyLessonDate[0]);$u++){
                if($value[$u] == $personStart){$numberOfStartLesson=$u;};
                if($value[$u] == $personStop){$numberOfStopLesson=$u;};
            }
        }

        $dataMain['numOfLessonsOnCombination'] = $numOfLessonsOnCombination = (abs($numberOfStopLesson - $numberOfStartLesson))+1;

        $data = $gettersSetters->getIsAnyPayedLessons($id,$teacher,$timetable,$level_start,$intensive);
        if($data){
            $gettersSetters->getUpdateNumReservedToPayedLessons($id,$teacher,$timetable,$level_start,$numOfLessonsOnCombination,$intensive);
        }else{
            $gettersSetters->getInsertNumPayedNumReservedToPayedLessons($id,$teacher,$timetable,$level_start,$numOfLessonsOnCombination,$intensive);
        }

        if($level){$dataMain['level'] = $level;}

        if($teacher){$dataMain['teacher'] = $teacher;}
        if($timetable){$dataMain['timetable'] = $timetable;}
        if($level_start){$dataMain['level_start'] = $level_start;}

        $dataMain['intensive'] = 0;
        if($intensive){$dataMain['intensive'] = $intensive;}


        return $dataMain;
    }
    public function saveAmountOfMoney(){
        $gettersSetters = $this->gettersSetters;
        $id = $_POST["id"];
        $AmountOfMoney = $_POST["amount"];

        $data = $gettersSetters->setInsertAmountOfMoneyToPaymentHas($AmountOfMoney,$id);

        if($data){
            $data = $gettersSetters->getIsThereAPersonBalance($id);

            if ($data) {
                $gettersSetters->setUpdateBalanceToBalance($AmountOfMoney, $id);
            } else {
                $gettersSetters->setInsertBalanceToBalance($AmountOfMoney, $id);
            }
            return (bool)true;
        }else{return (bool)false;}
    }
    public function deleteStudent(){
        $gettersSetters = $this->gettersSetters;
        $id = $_POST["id"];

        $gettersSetters->setDeleteStudent($id);
    }
    public function addStudent(){
        $gettersSetters = $this->gettersSetters;
        $name = $_POST["name"];

        $data = $gettersSetters->getIfStudentExists($name);
//        return $data;

        if($data){
            return $data['studentExisted'] = true;
        }else{
            $data = $gettersSetters->setInsertStudentToMain($name);
            $id = $data['lastInsert'];
            $data = $gettersSetters->getRowByIdFromMain($id);
            $data[0]['id'] = $id;
            $data[0]['studentExisted'] = false;

            return $data[0];
        }
    }
    public function buildingBlocks(){
        $gettersSetters = $this->gettersSetters;
        $data = $gettersSetters->getAllCombinationsExisted();
        return $data;
    }
    public function agreementNumber(){
        $gettersSetters = $this->gettersSetters;

        $id = $_POST["id"];
        $info = $_POST["info"];

        $data = $gettersSetters->setUpdateAgreementNumber($id,$info);
        return $data;
    }
}


