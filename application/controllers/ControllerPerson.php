<?php
namespace application\Controllers;
class ControllerPerson extends \application\core\Controller
{

	public function __construct()
	{
		$this->model = new \application\models\ModelPerson;
		$this->view = new \application\core\View;
	}

    public function actionIndex()
	{
		$data = $this->model->get_data();
		$this->view->generate('ViewPerson.php', 'ViewTemplatePerson.php', $data);
	}
    public function actionNameSumCombinationsFrozenDatesBalance(){
        $data = $this->model->nameSumCombinationsFrozenDatesBalance();
        echo json_encode($data);
    }
    public function actionCombinationDatesFittedToTimetable(){
        $data = $this->model->combinationDatesFittedToTimetable();
        echo json_encode($data);
    }
    public function actionPersonDiscountReason(){
        $data = $this->model->personDiscountReason();
        echo json_encode($data);
    }
    public function actionStudentNameAndDates(){
        $data = $this->model->studentNameAndDates();
        echo json_encode($data);
    }
    public function actionNumPayedNumReservedCostOfOneLessonWithDiscount(){
        $data = $this->model->numPayedNumReservedCostOfOneLessonWithDiscount();
        echo json_encode($data);
    }
    public function actionAddOrRemovePayedDate(){
        $data = $this->model->addOrRemovePayedDate();
        echo json_encode($data);
    }
    public function actionAreAnyPayedOrAttenedOrFrozenLessonsExist(){
        $data = $this->model->areAnyPayedOrAttenedOrFrozenLessonsExist();
        echo json_encode($data);
    }
    public function actionRemovePersonOnThisCombinationFromLevelsPersonAndPayedLessons(){
        $data = $this->model->removePersonOnThisCombinationFromLevelsPersonAndPayedLessons();
        echo json_encode($data);
    }
    public function actionAddDiscount(){
        $data = $this->model->addDiscount();
        echo json_encode($data);
    }
    public function actionAddDiscountReason(){
        $data = $this->model->addDiscountReason();
        echo json_encode($data);
    }
}