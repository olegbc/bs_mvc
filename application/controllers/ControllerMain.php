<?php
namespace application\Controllers;
class ControllerMain extends \application\core\Controller
{
	function __construct()
	{
		parent::__construct();
		$this->model = new \application\models\ModelMain;
	}
	function actionIndex()
	{
        $data = $this->model->getData();
		$this->view->generate('ViewMain.php', 'ViewTemplate.php',$data);
	}
}

