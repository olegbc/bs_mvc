<?php 
require "db.php";
header('Content-Type: text/html; charset=utf-8');

function __autoload($class_name) {
	include $class_name . '.php';
}

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

preg_match_all('/([^?&=#]+)=([^&#]*)/',$url,$m);
// print_r($m);

//combine the keys and values onto an assoc array
$data=array_combine( $m[1], $m[2]);
// print_r($data);

$arrControllerAction=explode('/',$data['r']);
// print_r($arrControllerAction);
$controller = $arrControllerAction[0];
$class = $controller;
$action = $arrControllerAction[1];


$obj = new $class;
// $obj->action;

// $obj = new bad_day_to_dbController();
$obj->go();
