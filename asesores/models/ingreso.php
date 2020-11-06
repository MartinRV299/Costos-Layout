<?php
require_once "conexion.php";

class IngresoModels{

	#INGRESO USUARIO
	#-------------------------------------
	public function ingresoUsuarioModel($datosModel, $tabla){
		$stmt = Conexion::conectar()->prepare("SELECT usuario, contrasena, intentos FROM $tabla WHERE usuario = :usuario AND (situacion = 1 OR situacion = 2)");	//usuarios especiales ej. nutriologa del programa Nutrifitness
		$stmt->bindParam(":usuario", $datosModel, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch();
		$stmt->close();
	}

	#INGRESO USUARIO (OBTENER ID Y NOMBRE REAL DE USUARIO) 
	#-------------------------------------
	public function ingreso2UsuarioModel($datosModel, $tabla){
		$stmt = Conexion::conectar()->prepare("SELECT id_usuario, tipo_acceso, nombre, paterno, imagen FROM $tabla WHERE usuario = :usuario");	
		$stmt->bindParam(":usuario", $datosModel, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch();
		$stmt->close();
	}

	#INTENTOS USUARIO
	#-------------------------------------
	public function intentosUsuarioModel($datosModel, $tabla){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET intentos = :intentos WHERE usuario = :usuario");
		$stmt->bindParam(":intentos", $datosModel["actualizarIntentos"], PDO::PARAM_INT);
		$stmt->bindParam(":usuario", $datosModel["usuarioActual"], PDO::PARAM_STR);
		if($stmt->execute())
			return "success";
		else
			return "error";
		$stmt->close();
	}

	public static function existeUsuario($usuario,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_usuario) FROM $tabla WHERE usuario = :usuario");	
		$stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch()[0];
		$stmt->close();
	}

	public static function datosUsuario($usuario,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT id_usuario,nombre,paterno,materno FROM $tabla WHERE usuario = :usuario");	
		$stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch();
		$stmt->close();
	}




	public static function crearNuevaPass($id,$token,$correo,$tabla){
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_usuario,identificador,correo) VALUES (:id,:identificador,:correo)");		
		$stmt->bindParam(":id", $id, PDO::PARAM_STR);
		$stmt->bindParam(":identificador", $token, PDO::PARAM_STR);
		$stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
		$stmt->execute();
		return;
		$stmt->close();
	}

	public static function existenDatos($id,$token,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT correo FROM $tabla WHERE id_usuario = :id AND identificador = :identificador");		
		$stmt->bindParam(":id", $id, PDO::PARAM_STR);
		$stmt->bindParam(":identificador", $token, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch()[0];
		$stmt->close();
	}

	public static function actualizarPass($id,$token,$correo,$pass,$tabla,$tabla2){
		$conexion = Conexion::conectar();
		$stmt = $conexion->prepare("UPDATE $tabla SET contrasena = :pass WHERE usuario = :correo");
		$stmt->bindParam(":pass", $pass, PDO::PARAM_STR);
		$stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
		if($stmt->execute()){
			$stmt = $conexion->prepare("DELETE FROM $tabla2 WHERE id_usuario = :id AND identificador = :identificador");
			$stmt->bindParam(":id", $id, PDO::PARAM_STR);
			$stmt->bindParam(":identificador", $token, PDO::PARAM_STR);
			if($stmt->execute())
				return true;
			else
				return false;
		}
		else
			return false;
		$conexion->close();
	}

}