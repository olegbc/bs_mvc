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

    public function actionGetPersonDiscountReason(){
        $data = $this->model->getPersonDiscountReason();
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

    public function actionRemovePersonComboPayedLessonsFrozenLessons(){
        $data = $this->model->removePersonComboPayedLessonsFrozenLessons();
        echo json_encode($data);
    }
}