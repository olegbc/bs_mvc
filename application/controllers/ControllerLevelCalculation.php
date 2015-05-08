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
        $data = $this->model->get_data();
        $this->view->generate('ViewLevelCalculation.php', 'ViewTemplateLevelCalculation.php', $data);
    }

    public function actionCalculateLevelDates()
    {
        $data = $this->model->calculateLevelDates();
        echo json_encode($data);
    }
}