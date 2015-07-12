<?php
namespace application\models;
class ModelNumberOfStudents extends \application\core\model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function allTeachers(){
        $gettersSetters = $this->gettersSetters;
        $data = $gettersSetters->getallTeachers();
        return $data;
    }
    public function datepicker(){
        $gettersSetters = $this->gettersSetters;
        $allTeacher = $_POST['allTeachers'];

        $start_week_range = strtotime($_POST["from"]);
        $stop_week_range = strtotime($_POST["to"]);
        $num_weeks = (($stop_week_range - $start_week_range)+86400)/604800;

        for($t=0;$t<$num_weeks;$t++){
            $num_students = 0;
            $start_week = $start_week_range + (604800*$t);
            $stop_week = $start_week_range + ((604800*($t+1))-86400);

            for($i=0;$i<count($allTeacher);$i++){
                $thisTeacher = $allTeacher[$i];

                $data = $gettersSetters->getPersonStratStopOfEveryStudentOfThisTeacher($thisTeacher);

                foreach($data as $key=>$value){
                    $startPerson = strtotime($data[$key]['person_start']);
                    $stopPerson = strtotime($data[$key]['person_stop']);
                    if(($start_week < $startPerson and $stop_week < $startPerson) or ($start_week > $stopPerson and $stop_week > $stopPerson)){
                    }else{
                        $num_students++;
                    }
                }

                $dataMain[$t][0][$i] = $num_students;
                $num_students=0;
                $dataMain[$t][1] = date("d-m-Y",$start_week)." : ".date("d-m-Y",$stop_week);
            }
        }
        return $dataMain;

    }
}