<?php
namespace application\Controllers;
class ControllerAmountOfMoney extends \application\core\Controller
{

    public function __construct()
    {
        $this->model = new \application\models\ModelAmountOfMoney;
        $this->view = new \application\core\View;
    }
    public function actionIndex()
    {
        $data = $this->model->get_data();
        $this->view->generate('ViewAmountOfMoney.php', 'ViewTemplateAmountOfMoney.php', $data);
    }

    public function actionAmountOfMoney()
    {
        $data = $this->model->amountOfMoney();
        echo json_encode($data);
    }
}