<?php

class Enlaces{
	public $ruta = array();
	public function enlacesController(){
		
		if(isset($_GET["action"])){
			$this->ruta = explode("/",$_GET["action"]);
			$_POST['p'] = isset($this->ruta[1]) ? $this->ruta[1] :(int)1;//paginacion
		}
	
		$respuesta = EnlacesModels::enlacesModel(isset($this->ruta[0]) ? $this->ruta[0]:'exit');
		include $respuesta;
	}
}