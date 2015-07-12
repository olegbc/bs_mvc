<?php
namespace application\Controllers;
class ControllerLevelCalculation extends \application\core\Controller
{

    public function __construct()
    {
        $this->model = new \application\models\ModelLevelCalculation;
        $this->view = new \application\core\View;
    }

    public function actionIndex()
    {
        $this->view->generate('ViewLevelCalculation.php', 'ViewTemplateLevelCalculation.php');
    }

    public function actionCalculateLevelDates()
    {
        $data = $this->model->calculateLevelDates();
        echo json_encode($data);
    }
}