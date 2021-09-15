<?php
session_start();
include_once("../configs/config.php");

if ($mysqli->connect_errno) {
    echo json_encode("Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    return http_response_code(500);
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    header("Content-type: application/json; charset=utf-8");
    if( !(isset($_POST['username'])) || !(isset($_POST["pass"])) || empty($_POST["username"]) || empty($_POST["pass"])){
        echo json_encode(array('message'=>"no provide information for authentication"));
        return http_response_code(403);
    }
    $username = $_POST['username'];
    $password = $_POST['pass'];
    
    if(!($sentencia =$mysqli->prepare("SELECT * FROM users WHERE username = ?"))){
        echo json_encode(array('message'=>"Error while insert values"));
        return http_response_code(500);
    }
    try {
        $sentencia->bind_param("s",$username);
        
        if (!$sentencia->execute()) {
            echo json_encode(array('message'=>"Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error));
            return http_response_code(500);
        }
        $resultado = $sentencia->get_result();
        $fila = $resultado->fetch_assoc();
        if($fila){
            if(password_verify($password, $fila["password"])){
                $_SESSION["user"] = $fila["username"];
                echo json_encode(array('username'=>$fila["username"], "auth"=>"true"));
                $sentencia->close();
                return http_response_code(200);
            }else{
                $sentencia->close();
                echo json_encode(array('message'=>"password invalid"));
                return http_response_code(403);
            }
        }
        $sentencia->close();
        echo json_encode(array('message'=>"error"));
        return http_response_code(500);
    } catch (\Throwable $th) {
        echo json_encode(array('message'=>"an ocurre error ".$th));
        return http_response_code(500);
    }
    echo(json_encode(array('message'=>'error into the sever')));
    return http_response_code(200);
}
?>