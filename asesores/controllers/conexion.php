<?php
class Conexion{
	public static function conectar(){
		$user='root';
		try{
			$link = new PDO("mysql:host=localhost;dbname=asesores_empresariales", "root", "");
			return $link;
		}
		catch(PDOException $e){
			return false;
		}
	}
}
