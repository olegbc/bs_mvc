<?php
namespace application\models;
class ModelMain extends \application\core\Model
{
    public function __construct(){
        parent::__construct();
    }

	public function getData()
	{
        $db = $this->db;
		$data = $db->query('SELECT * FROM main ');
		return $data;
	}
}


