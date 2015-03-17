<?php
namespace Controllers;
use core\Controller;
use models;
class Controller_Portfolio extends Controller
{

	function __construct()
	{
		$this->model = new \models\Model_Portfolio;
		$this->view = new \core\View();
	}
	
	function action_index()
	{
		$data = $this->model->get_data();		
		$this->view->generate('portfolio_view.php', 'template_view.php', $data);
	}
}