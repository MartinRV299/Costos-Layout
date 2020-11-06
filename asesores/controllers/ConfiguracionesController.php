<?php

class ConfiguracionesController{
    
    public static function obtenerConfiguracionController($usuario){
        $respuesta =  ConfiguracionesModel::obtenerConfiguracionModel($usuario,'usuarios_configuracion_ae');
        return $respuesta;
    }

    public static function actualizarConfiguracionController($opcion,$valor){
        if(!preg_match('/^[a-z]{5,}$/', $opcion))
            return;
        if(!preg_match('/^[0-9]{1,2}$/', $valor))
            return;
        $respuesta = ConfiguracionesModel::actualizarConfiguracionModel($opcion,$valor,'usuarios_configuracion_ae');
        echo $respuesta;
    }

}

