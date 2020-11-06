<?php
class Conexion{
	public static function conectar(){
		try{
			$link = new PDO('mysql:host=localhost;dbname=asesores_empresariales', 'root' , '',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
			return $link;
		}
		catch(PDOException $e){
			return false;
		}
	}
}
