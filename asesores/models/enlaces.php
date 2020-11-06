<?php

class EnlacesModels{

	static public function enlacesModel($enlaces){
		if($enlaces == "inicio" ||
		   $enlaces == "nominas" ||
		   $enlaces == "facturacion" ||
		   $enlaces == "costos" ||
		   $enlaces == "salir"){
			$module = "views/modules/main/".$enlaces.".php";
		}	
		else
			$module = "views/modules/interfaz/interfazIngreso.php";	
		return $module;
	
	}
}
