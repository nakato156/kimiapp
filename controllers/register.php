<?php
session_start();
include("../configs/config.php");

if ($mysqli->connect_errno) {
    echo json_encode(array('message'=>"Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error));
    return;
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    header("Content-type: application/json; charset=utf-8");
    if( !(isset($_POST['username'])) || !(isset($_POST['email'])) || !(isset($_POST["pass"])) || empty($_POST["username"]) || empty($_POST["pass"]) || empty($_POST["email"])){
        echo json_encode(array('message'=>"no provide information for authentication"));
        return http_response_code(503);
    }
    $username = $_POST['username'];

    if (!avilable_user($username, $mysqli)){
        echo json_encode(array('message'=>"usuario existente"));
        return http_response_code(403);
    }
    
    $email = $_POST['email'];
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    if(!($sentencia =$mysqli->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)"))){
        echo json_encode(array('message'=>"Error while insert values", ));
        return http_response_code(500);
    }
    try {
        $sentencia->bind_param("sss", $username, $email, $password);

        if (!$sentencia->execute()) {
            echo json_encode(array('message'=>"Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error));
            return http_response_code(500);
        }

        if($sentencia){
            $_SESSION["user"] = $username;
            echo json_encode(array('username'=>$username, 'auth'=>"true"));
            return;
        }
        echo json_encode(array('message'=>"Error al procesar datos"));
        return;
    } catch (\Throwable $th) {
        echo json_encode(array('message'=>"an ocurre error ".$th));
        return http_response_code(500);
    }
    $sentencia->close();
    return http_response_code(200);
}

function avilable_user($user, $conn){
    if(!($sentencia =$conn->prepare("SELECT username FROM users WHERE username=?"))){
        return false;
    }
    try{
        $sentencia->bind_param("s",$user);
        $sentencia->execute();
        $data = $sentencia->get_result();
        $count = count($data->fetch_all());
        return $count != 0 ? false : true;
    } catch (\Throwable $th) {
        return false;
    }
}
?>