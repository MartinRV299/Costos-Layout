<?php
require_once "conexion.php";

class ConfiguracionesModel{
    
    public static function obtenerConfiguracionModel($usurio,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT color_pantalla, menu_izquierdo, tamano_pantalla, avisos FROM $tabla WHERE id_usuario = :usuario");	
		$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
		$stmt->close();

    }
  
    public static function actualizarConfiguracionModel($opcion,$valor,$tabla){

        if($opcion == "color"){
            $opcion = 'color_pantalla';
        }
        else if ($opcion == "layout"){
            $opcion = 'tamano_pantalla';
        }
        else {
            $opcion = 'menu_izquierdo';
        }

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $opcion = :valor WHERE id_usuario = :id");
        $stmt->bindParam(":id", $_SESSION['identificador'], PDO::PARAM_INT);
        $stmt->bindParam(":valor", $valor, PDO::PARAM_INT);
        
       if($stmt->execute())
           return 'ok';
        else
            return 'bad';
		$stmt->close();
    }


}

