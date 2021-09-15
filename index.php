<?php 
namespace kimiapp\index;

session_start();
require_once("./routes/routes.php");
require_once("./templates/header.php");
use routes\Router;

$router = new Router();
$router->use = "/kimiapp";
$router->path_render = "./templates";

// definiendo rutas
$router->view('/', "home.php");

$router->view('/login', "login.php", ["middleware"=>"not_session"]);

$router->view("/register", "register.php", ["middleware"=>"not_session"]);

$router->get('/perfil', ["middleware"=>"use_session",function(){
	echo('<link rel="stylesheet" href="./css/perfil.css">');
	include_once("./templates/perfil.php");
}]);

$router->get("/app/:id/:name", [function($id, $name){
	include_once("./configs/config.php");
    if(!($sentencia =$mysqli->prepare("SELECT * FROM aplicaciones WHERE id=?"))){
        return false;
    }
    try{
        $sentencia->bind_param("i",$id);
        $sentencia->execute();
        $data = $sentencia->get_result();
        $app = $data->fetch_all()[0];

		$type = $app[2];
		$version = $app[3];
		$author = $app[4];
		$img = $app[5];
		$descripcion = $app[6];
		$appA = $app[7];

		echo('<link rel="stylesheet" href="../../css/index.css">');
		echo('<link rel="stylesheet" href="../../css/apps.css">');
		include_once("./templates/app.php");
    } catch (\Throwable $th) {
        echo("<h1>Error</h1>");
    }
}]);

$router->get("/salir", [function(){
	session_unset();
	session_destroy();
	die("<script>window.location.href='./'</script>");
}]);

$router->error_404(__DIR__);
include("./templates/footer.php");
?>