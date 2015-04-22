<?php
namespace application\Controllers;
class ControllerFreeze extends \application\core\Controller
{
    public function __construct()
    {
        $this->model = new \application\models\ModelFreeze;
        $this->view = new \application\core\View;
    }
    public function actionIndex()
    {
        $data = $this->model->get_data();
        $this->view->generate('ViewFreeze.php', 'ViewTemplateFreeze.php', $data);
    }
    public function actionBuildFreezeTable(){
        $data = $this->model->buildFreezeTable();
        echo json_encode($data);
    }
    public function actionCombinationDatesFittedToTimetable(){
        $data = $this->model->combinationDatesFittedToTimetable();
        echo json_encode($data);
    }
    public function actionStudentNameAndDates(){
        $data = $this->model->studentNameAndDates();
        echo json_encode($data);
    }
    public function actionChangeFrozenDate(){
        $data = $this->model->changeFrozenDate();
        echo json_encode($data);
    }
}