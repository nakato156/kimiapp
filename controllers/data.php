<?php
include("../configs/config.php");

if ($mysqli->connect_errno) {
    echo json_encode(array('message'=>"Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error));
    return http_response_code(500);
}

if($_SERVER['REQUEST_METHOD'] == "GET"){
    $query = mysqli_query($mysqli, "SELECT * FROM aplicaciones");
    while($row = mysqli_fetch_array($query)){
        $app[] = array(
            'id' => $row[0],
            'name' => $row[1],
            'type' => $row[2],
            'version' => $row[3],
            'author' => $row[4],
            'imagen'=> $row[5],
        );
    }
    echo json_encode($app);
    return http_response_code(200);
}

elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    header("Content-type: application/json; charset=utf-8");
    
    check_basic();

    if(!(isset($_POST["name"])) || !(isset($_POST["type"])) || !(isset($_POST["version"])) || !(isset($_POST["descripcion"])) || !(isset($_FILES["image"])) || !(isset($_FILES["app"])) || empty($_POST["name"]) || empty($_POST["type"]) || empty($_POST["version"]) || empty($_POST["descripcion"]) || empty($_FILES["image"]) || empty($_FILES["app"]) ){
        echo json_encode(array('message'=>"Data incomplete"));
        return http_response_code(403);
    }

    $name = $_POST["name"];
    $type = $_POST["type"];
    $img = $_FILES["image"];
    $app = $_FILES["app"];
    $version = $_POST["version"];
    $descripcion = $_POST["descripcion"];
    $author = $_SESSION['user'];

    if (!send_app($author, $mysqli)){
        echo json_encode(array('message'=>"Alcanzó el máximo de aplicaciones publicadas."));
        return http_response_code(202);
    }
    // preparamos la sentencia
    if(!($sentencia =$mysqli->prepare("INSERT INTO aplicaciones (name, type, version, author, imagen, descripcion, app) VALUES (?,?,?,?,?,?,?)"))){
        echo json_encode(array('message'=>"Error while insert values"));
        return http_response_code(500);
    }
    try {
        $name_img = sanity($img["name"]);
        $name_app = sanity($app["name"]);

        $sentencia->bind_param("sssssss",$name, $type, $version, $author, $name_img, $descripcion, $name_app);
        
        // movemos la imagen
        $tmp = $img["tmp_name"];
        move_uploaded_file($tmp, "../apps/img/".$name_img);
        
        // movemos la app
        $tmp_app = $app["tmp_name"];
        move_uploaded_file($tmp_app, "../apps/app/".$name_app);

        if (!$sentencia->execute()) {
            echo json_encode(array('message'=>"Fallo la ejecucion: (".$sentencia->errno.")". $sentencia->error));
            return http_response_code(400);
        }
        $sentencia->close();
        echo json_encode(array('message'=>"Registro de app exitoso"));
        return http_response_code(201);
    } catch (\Throwable $th) {
        $sentencia->close();
        unlink('../apps/img/'.$img["name"]);
        unlink('../apps/app/'.$app["name"]);
        echo json_encode(array('message'=>"an ocurre error "));
        return http_response_code(400);
    }
    return http_response_code(201);
}
elseif ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    header("Content-type: application/json; charset=utf-8");
    
    check_basic();

    if(!(isset($_POST["name"])) || empty($_POST["name"])){
        echo json_encode(array('message'=>"Data incomplete"));
        return http_response_code(403);
    }
    $name = $_POST["name"];
    $author = $_SESSION['user'];

    if(!($sentencia =$mysqli->prepare("DELETE FROM aplicaciones WHERE author = ? AND name=?)"))){
        echo json_encode(array('message'=>"Error while delete aplication"));
        return http_response_code(500);
    }
    try {
        $sentencia->bind_param("ss",$author, $name);
        
        if (!$sentencia->execute()) {
            echo json_encode(array('message'=>"Fallo la ejecucion: (" . $sentencia->errno . ") " . $sentencia->error));
            return http_response_code(400);
        }
        $sentencia->close();
        echo json_encode(array('message'=>"Registro de app exitoso"));
        return http_response_code(201);
    } catch (\Throwable $th) {
        $sentencia->close();
        echo json_encode(array('message'=>"An ocurre error ".$th));
        return http_response_code(500);
    }
    return http_response_code(200);
}else{
    return http_response_code(501);
}

function sanity($file){
    $forbidden_chars = array(
        "?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&",
        "$", "#", "*", "(", ")" , "|", "~", "`", "!", "{", "}", "%", "+" , chr(0)
    );
    
    $replace_chars = array(
        'áéíóúäëïöüàèìòùñ ',
        'aeiouaeiouaeioun_'
    );      
    $source = strtolower($file);

    for( $i=0 ; $i < strlen($source) ; $i++ ) {
        $sane_char = $source_char = $source[$i];
        if ( in_array( $source_char, $forbidden_chars ) ) {
          $sane_char = "_";
          $sane .= $sane_char;
          continue;
        }
        $pos = strpos( $replace_chars[0], $source_char);
        if ( $pos !== false ) {
          $sane_char = $replace_chars[1][$pos];
          $sane .= $sane_char;
          continue;
        }
        if ( ord($source_char) < 32 || ord($source_char) > 128 ) {
          $sane_char = "_";
          $sane .= $sane_char;
          continue;
        }
        $sane .= $sane_char;
    }
    return $sane;
}

function check_basic(){    
    if(!(isset($_SESSION['user']))){
        echo json_encode(array('message'=>"No autenticado"));
        return http_response_code(403);
    }
}

function send_app($user, $mysqli){
    if(!($sentencia =$mysqli->prepare("SELECT author FROM aplicaciones WHERE author=?"))){
        return false;
    }
    try{
        $sentencia->bind_param("s",$user);
        $sentencia->execute();
        $data = $sentencia->get_result();
        $count = count($data->fetch_all());
        return $count>3? false : true;
    } catch (\Throwable $th) {
        return false;
    }
}
?>