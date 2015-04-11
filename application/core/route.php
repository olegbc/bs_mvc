<?php
namespace application\core;
class Route
{
	static function start()
	{
		$controller_name = 'Main';
		$action_name = 'Index';

		$routes = $_SERVER['REQUEST_URI'];

		// http://test.ru/bigstep/person.php?person=131
		// 'http://test.ru/bs_mvc/person?id=131'
		// '/blog/post/(?P<id>\d+)\.html'
		// preg_match('#id=(?P<id>[\d]+)#', $routes, $matches);
		// preg_match('#bs_mvc/(?P<controller>[\w-]+)#', $routes, $matches);
		// preg_match('#bs_mvc/portfolio/(?P<action>[\w-]+)#', $routes, $matches);
		$rules=	array(
			'bs_mvc/(?P<controller>[\w-]+)',
			'bs_mvc/[\w]+/(?P<action>[\w-]+)',
			'id=(?P<id>[\d]+)'
		);

		foreach ($rules as $pattern) {
			preg_match('#' . $pattern . '#', $routes, $matches);
			if (!empty($matches['controller']))
			{	
				$controller_name = $matches['controller'];
			}
			if (!empty($matches['action']))
			{	
				$action_name = $matches['action'];
			}
			if (!empty($matches['id']))
			{	
				$id = $matches['id'];
			}
		}
		// if(!empty($controller_name)){echo $controller_name.'<br />';}
		// if(!empty($action_name)){echo $action_name.'<br />';}
		// if(!empty($id)){echo $id.'<br />';}
		$model_name = 'Model'.$controller_name;
		$controller_name = 'Controller'.$controller_name;
		$action_name = 'action'.$action_name;

		$controller_alias =  'application\Controllers\\'.$controller_name;
		$controller = new $controller_alias;
		$action = $action_name;
		
		if(method_exists($controller, $action))
		{
            if(!empty($id)){
                $controller->$action($id);
            }else{
                $controller->$action();
            }
		}
		else
		{
			Route::ErrorPage404();
		}
	
	}
	
	function ErrorPage404()
	{
		$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
	}
}