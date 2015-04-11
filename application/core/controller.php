<?php
namespace application\core;
class Controller {
	public $model;
	public $view;
	
	function __construct()
	{
		$this->view = new \application\core\View;
	}
	
	function actionIndex()
	{
	}
}