<?php
namespace application\Controllers;
class Controller_Portfolio extends \application\core\Controller
{

	function __construct()
	{
		$this->model = new \application\models\Model_Portfolio;
		$this->view = new \application\core\View();
	}
	
	function action_index()
	{
		$data = $this->model->get_data();		
		$this->view->generate('portfolio_view.php', 'template_view.php', $data);
	}
}