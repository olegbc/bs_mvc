<?php
namespace application\Controllers;
class ControllerSPU extends \application\core\Controller
{

    public function __construct()
    {
        $this->model = new \application\models\ModelSPU;
        $this->view = new \application\core\View;
    }
    public function actionIndex()
    {
        $data = $this->model->get_data();
        $this->view->generate('ViewSPU.php', 'ViewTemplateSPU.php', $data);
    }

    public function actionSPU()
    {
        $data = $this->model->spuCalculation();
        echo json_encode($data);
    }
}