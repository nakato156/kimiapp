<?php
include("./configs/config.php");
function getApp($id){
	
    if(!($sentencia =$conn->prepare("SELECT * FROM aplicaciones WHERE id=?"))){
        return false;
    }
    try{
        $sentencia->bind_param("s",$id);
        $sentencia->execute();
        $data = $sentencia->get_result();
        $app = $data->fetch_all();
		print_r($app);
    } catch (\Throwable $th) {
        return false;
    }
}

?>