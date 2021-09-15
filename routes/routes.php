<?php
namespace routes;

require("./routes/middlewares/middlewares.php");
use  routes\middlewares\Middleware as Middle;

class Router extends Middle{
	public $use = "";
	public $path_render = "./";
	public $add_template = array();
	protected $ntf = true;
	
	public function get($url, $middleware) {
		if($_SERVER['REQUEST_METHOD'] != "GET"){
			http_response_code(402);
			exit();
		}

		$route = $_SERVER['REQUEST_URI'];
		$route = strstr($route, '?') ? strstr($route, '?', true): $route;  
		$url =$this->use.$url;

		$pre_route = str_replace($this->use, '', $route);

		// obteniendo parametros
		$urlRule = preg_replace('/:([^\/]+)/', '(?<\1>[^/]+)', $url);
		$urlRule = str_replace('/', '\/', $urlRule);
	
		preg_match_all('/:([^\/]+)/', $url, $parameterNames);
		$params = preg_match('/^' . $urlRule . '\/*$/s', $route, $matches);

		// verificando la ruta y url
		if($url != $pre_route && !($params)){
			return;
		}

		$this->ntf = false;

		//ejecutando middleware
		$middle = new Middle();
		$middle->exec_middleware($pre_route, $middleware);
		$closure = $middleware[0];
		
		if ($params) {
			$parameters = array_intersect_key($matches, array_flip($parameterNames[1]));
			return call_user_func_array($closure, $parameters);
		}		
		return;
	}

	public function view($url, $view, $middleware=null, $parameters=null, $links=null){
		if($_SERVER['REQUEST_METHOD'] != "GET"){
			http_response_code(403);
			exit();
		}

		$route = $_SERVER['REQUEST_URI'];
		$route = strstr($route, '?') ? strstr($route, '?', true): $route;  
		$url =$this->use.$url;

		$pre_route = str_replace($this->use, '', $route);

		// obteniendo parametros
		$urlRule = preg_replace('/:([^\/]+)/', '(?<\1>[^/]+)', $url);
		$urlRule = str_replace('/', '\/', $urlRule);
	
		preg_match_all('/:([^\/]+)/', $url, $parameterNames);
		$params = preg_match('/^' . $urlRule . '\/*$/s', $route, $matches);

		// verificando la ruta y url
		if($url != $pre_route && !($params)){
			return;
		}

		if($middleware !=null && isset($middleware["middleware"])){
			//ejecutando middleware
			$middle = new Middle();
			$middle->exec_middleware($pre_route, $middleware);
		}
		if($parameters !=null){
			var_dump($parameters);
		}
		$this->ntf = false;
		return include_once($this->path_render."\\".$view);
	}
	
	public function error_404($dir){
		if($this->ntf == false){
			return;
		}
		$closure = function ()
		{
			include_once("./templates/ntf.html");
		};
		$parameters = [];
		return call_user_func_array($closure, $parameters);
	}
}
?>