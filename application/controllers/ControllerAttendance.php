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
        $data = $this->model->get_data();
        $this->view->generate('ViewAttendance.php', 'ViewTemplateAttendance.php', $data);
    }

//    public function actionNameSumCombinationsFrozenDatesBalance()
//    {
//        $data = $this->model->nameSumCombinationsFrozenDatesBalance();
//        echo json_encode($data);
//    }
}