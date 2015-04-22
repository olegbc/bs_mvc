<?php
namespace application\Controllers;
class ControllerMain extends \application\core\Controller
{
	function __construct()
	{
		parent::__construct();
		$this->model = new \application\models\ModelMain;
	}

	function actionIndex()
	{
        $data = $this->model->getData();
		$this->view->generate('ViewMain.php', 'ViewTemplate.php',$data);
	}

    public function actionTimeTables(){
        $data = $this->model->timeTables();
        echo json_encode($data);
    }
    public function actionLevelStart(){
        $data = $this->model->levelStart();
        echo json_encode($data);
    }
    public function actionAreAnyPayedOrAttenedOrFrozenLessonsExist(){
        $data = $this->model->areAnyPayedOrAttenedOrFrozenLessonsExist();
        echo json_encode($data);
    }
    public function actionLevelCombinationDates(){
        $data = $this->model->levelCombinationDates();
        echo json_encode($data);
    }
    public function actionSaveUpdateStudentCombination(){
        $data = $this->model->saveUpdateStudentCombination();
        echo json_encode($data);
    }
    public function actionSaveAmountOfMoney(){
        $data = $this->model->saveAmountOfMoney();
        echo json_encode($data);
    }
    public function actionDeleteStudent(){
        $data = $this->model->deleteStudent();
        echo json_encode($data);
    }
    public function actionAddStudent(){
        $data = $this->model->addStudent();
        echo json_encode($data);
    }
}

