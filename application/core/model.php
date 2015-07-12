<?php
namespace application\core;
class Model
{
	public $db;
	public function __construct(){
		$this->db = Db::getInstance();
        $this->gettersSetters = GettersSettersInitialization::getInstance();
	}
}