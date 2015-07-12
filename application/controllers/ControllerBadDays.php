<?php
namespace application\Controllers;
class ControllerBadDays extends \application\core\Controller
{

    public function __construct()
    {
        $this->model = new \application\models\ModelBadDays;
        $this->view = new \application\core\View;
    }

    public function actionIndex()
    {
        $this->view->generate('ViewBadDays.php', 'ViewTemplateBadDays.php');
    }

    public function actionCombinationAndBadDaysDates()
    {
        $data = $this->model->combinationAndBadDaysDates();
        echo json_encode($data);
    }
    public function actionInsertOrDeleteBadDay()
    {
        $data = $this->model->insertOrDeleteBadDay();
        echo json_encode($data);
    }
    public function actionAttenedDates()
    {
        $data = $this->model->attenedDates();
        echo json_encode($data);
    }
}