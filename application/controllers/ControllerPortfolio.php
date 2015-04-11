<?php
namespace application\Controllers;
class ControllerPortfolio extends \application\core\Controller
{

	function __construct()
	{
		$this->model = new \application\models\ModelPortfolio;
		$this->view = new \application\core\View();
	}
	
	function actionIndex()
	{
		$data = $this->model->get_data();		
		$this->view->generate('ViewPortfolio.php', 'ViewTemplate.php', $data);
	}
}