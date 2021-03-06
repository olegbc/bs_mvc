<?php
namespace application\Controllers;
class ControllerAttendance extends \application\core\Controller
{

    public function __construct()
    {
        $this->model = new \application\models\ModelAttendance;
        $this->view = new \application\core\View;
    }

    public function actionIndex()
    {
        $this->view->generate('ViewAttendance.php', 'ViewTemplateAttendance.php');
    }

    public function actionCombinationDatesFittedToTimetable()
    {
        $data = $this->model->combinationDatesFittedToTimetable();
        echo json_encode($data);
    }
    public function actionStudentsInformation()
    {
        $data = $this->model->studentsInformation();
        echo json_encode($data);
    }
    public function actionBuildingBlocks()
    {
        $data = $this->model->buildingBlocks();
        echo json_encode($data);
    }
    public function actionDeleteAttenedDateFromAttendanceTable()
    {
        $data = $this->model->deleteAttenedDateFromAttendanceTable();
        echo json_encode($data);
    }
    public function actionInsertAttenedDateToAttendanceTable()
    {
        $data = $this->model->insertAttenedDateToAttendanceTable();
        echo json_encode($data);
    }
    public function actionChangeLevelStartDate()
    {
        $data = $this->model->changeLevelStartDate();
        echo json_encode($data);
    }
    public function actionRemovePersonCombination()
    {
        $data = $this->model->removePersonCombination();
        echo json_encode($data);
    }
    public function actionRemoveCombination()
    {
        $data = $this->model->removeCombination();
        echo json_encode($data);
    }
    public function actionIsTodayWithinCombinationDates()
    {
        $data = $this->model->isTodayWithinCombinationDates();
        echo json_encode($data);
    }
    public function actionToArchive()
    {
        $data = $this->model->toArchive();
        echo json_encode($data);
    }
}