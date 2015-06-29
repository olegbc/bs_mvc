<?php
namespace application\core;
class Model
{
	public $db;
	public function __construct(){
		$this->db = Db::getInstance();
//        $this->helper = HelperInitialization::getInstance();
        $this->gettersSetters = GettersSettersInitialization::getInstance();
	}
	public function getData()
	{
	}
}