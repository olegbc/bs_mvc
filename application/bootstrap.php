<?php
function load($class_name) {
//	require_once strtolower($class_name) . '.php';
	require_once $class_name . '.php';
}
set_include_path(dirname(__FILE__));
spl_autoload_register('load');
application\core\Route::start();
