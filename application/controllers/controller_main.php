<?php
namespace application\Controllers;
class Controller_Main extends \application\core\Controller
{
	function action_index()
	{
		$this->view->generate('main_view.php', 'template_view.php');
	}
}