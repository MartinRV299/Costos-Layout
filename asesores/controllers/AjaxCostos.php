<?PHP
session_start();
require_once '../models/config.php';
require_once '../models/CostosModel.php';
require '../views/excel/vendor/autoload.php';
require_once 'MetodoMartin.php';
require_once 'Costos.php';

    class AjaxConciliacion{
        public function registrosMasivos(){

            $respuesta = Conciliacion::registrosMasivos($this->archivo);

            if ($respuesta['version'] === false) {
                echo json_encode(array('log'=>false,'dataLog'=>$respuesta['data'],'version'=>false));
            }else if ($respuesta['log'] === false) {
                echo json_encode(array('log'=>false,'dataLog'=>$respuesta['data'],'version'=>true));
            }else if ($respuesta['log'] === true) {
                echo json_encode(array('log'=>true,'dataLog'=>$respuesta['data'],'error'=>$respuesta['errores'],'alerta'=>true,'version'=>true));
                
            }elseif ($respuesta['version'] === false) {
                echo json_encode(array('log'=>false,'dataLog'=>$respuesta['data'],'version'=>false));
            }elseif ($respuesta['alerta'] > 0) {

                echo json_encode(array('log'=>false,'dataLog'=>$respuesta['data'],'error'=>$respuesta['errores'],'alerta'=>$respuesta['alerta'],'version'=>true,'fila'=>$respuesta['fila']));
            }else{
                echo json_encode(array('log'=>false,'dataLog'=>$respuesta['data'],'error'=>$respuesta['errores'],'alerta'=>$respuesta['alerta'],'version'=>true));
            }

         // echo json_encode(array('log'=>false,'dataLog'=>$respuesta['data'],'errores'=>$respuesta['errores']));

        }
        
    }

    if( isset($_FILES["cargarRegistros"]["name"] )){
        $a = New AjaxConciliacion();
        $a->archivo = $_FILES["cargarRegistros"];
        $a->registrosMasivos();
    }
?>