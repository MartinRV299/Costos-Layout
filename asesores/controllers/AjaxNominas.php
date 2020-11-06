<?php 
session_start();
if(!$_SESSION["validar"]){
	header("location:ingreso");
	exit();
}

require_once 'Nominas.php';
require_once '../models/NominasModel.php';
require_once '../models/config.php';
require_once 'MetodosDiversos.php';
require_once "ajaxPaginacion.php";

require '../views/excel/vendor/autoload.php';

class AjaxNominas{
    public $id_nomina = NULL;
    public $nomina_origen = NULL;
    public $tipo_esquema;
    public $devengada = 0;
    public $devengadaFactura = NULL;
    public $id_cliente;
    public $tipo_sindicato = NULL;
    public $tipo_pago;
    public $regimen;
    public $comision;
    public $empresa_facturadora;
    public $subtotal;
    public $iva;
    public $total;
    public $empresa_imss;
    public $total_imss;
    public $empresa_asimilados;
    public $total_asmilados;
    public $tipo_periodo;
    public $numero_periodo;
    public $socios;
    public $ingreso;
    public $infonavit;
    public $fonacot;
    public $donativo;
    public $pension;
    public $excedente_cargas;
    public $cargas_patronal;
    public $isn;
    public $comision_monto;
    public $imss_obrera;
    public $carga_social_imss;
    public $prenomina_imss;
    public $isr_isp;
    public $isr_142;
    public $cuota_sindical;
    public $despensa;
    public $caja_ahorro;
    public $descuento_imss;
    public $apoyo_sindical;
    public $descuento_comedor;
    public $otros;
    public $haberes;
    public $excedente_ingreso;
    public $excedente_isr;
    public $excedente_imss;
    public $excedente_gmm;
    public $excedente_infonavit;
    public $excedente_fonacot;
    public $excedente_prestamos;
    public $excedente_pension;
    public $excedente_terceros;
    public $excedente_clientes;
    public $excedente_subsidio;
    public $excedente_recuperacion;
    public $excedente_comision;
    public $excedente_prenomina;
    public $excedente_prenomina_gmm;
    public $excedente_otros;
    public $comentarios_nominas;

    public $descuentos_sys='';
    public $descuentos_asesores='';
    public $descuentos_terceros='';
    public $prestamos_empleados='';
    public $prestamos_ayudate='';
    public $excedente_caja_ahorro='';
    public $retencion_iva='';
    public $ajuste_subsidio_empleo='';
    public $descuento_ayudate='';
    public $retencion_isn = '';


    public $numero_factura;
    public $financiada = 0;
    public $fecha_envio;
    public $hora_envio;
    public $observaciones;
    public $fecha_liberacion;
    public $fondeo_imss = 0;
    public $fondeo_asimilados = 0;
    public $comentarios_finanzas;

    public $estatus_facturacion;
    public $numero_nota;

    public $location;


    public $paginaActual=1;

    public $registrosPorPagina=30;

    public $target='nominas';
    public $cliente="";
    public $nomina="";
    public $facturado="";
    public $liberado="";
    public $nominista="";
    public $autorizacion="";


    public $documentoNombre = NULL;
    public $documentoTipo = NULL;
    public $documentoTemporal =NULL;
    public $documentoTamano = NULL;

    public $url;

    public $documentsName = array();
    public $documentsTemp = array();
    public $documentsSize = array();
    public $documentsPatch = array();
    public $totalFile;

    public $peso = 0;


    public function registrarNomina(){
        $data = array(
            'id_nomina'=>$this->id_nomina,
            'tipo_esquema'=>$this->tipo_esquema,
            'devengada'=>$this->devengada,
            'devengadaFactura'=>$this->devengadaFactura,
            'tipo_sindicato'=>$this->tipo_sindicato,
            'id_cliente'=>$this->id_cliente,
            'tipo_pago'=>$this->tipo_pago,
            'regimen'=>$this->regimen,
            'comision'=>$this->comision,
            'empresa_facturadora'=>$this->empresa_facturadora,
            'subtotal'=>$this->subtotal,
            'iva'=>$this->iva,
            'total'=>$this->total,
            'empresa_imss'=>$this->empresa_imss,
            'total_imss'=>$this->total_imss,
            'empresa_asimilados'=>$this->empresa_asimilados,
            'total_asimilados'=>$this->total_asimilados,
            'tipo_periodo'=>$this->tipo_periodo,
            'numero_periodo'=>$this->numero_periodo,
            'socios'=>$this->socios,
            'ingreso'=>$this->ingreso,
            'infonavit'=>$this->infonavit,
            'fonacot'=>$this->fonacot,
            'donativo'=>$this->donativo,
            'pension'=>$this->pension,
            'excedente_cargas'=>$this->excedente_cargas,
            'cargas_patronal'=>$this->cargas_patronal,
            'isn'=>$this->isn,
            'comision_monto'=>$this->comision_monto,
            'imss_obrera'=>$this->imss_obrera,
            'carga_social_imss'=>$this->carga_social_imss,
            'prenomina_imss'=>$this->prenomina_imss,
            'isr_isp'=>$this->isr_isp,
            'isr_142'=>$this->isr_142,
            'cuota_sindical'=>$this->cuota_sindical,
            'despensa'=>$this->despensa,
            'caja_ahorro'=>$this->caja_ahorro,
            'descuento_imss'=>$this->descuento_imss,
            'apoyo_sindical'=>$this->apoyo_sindical,
            'descuento_comedor'=>$this->descuento_comedor,
            'otros'=>$this->otros,
            'haberes'=>$this->haberes,
            'excedente_ingreso'=>$this->excedente_ingreso,
            'excedente_isr'=>$this->excedente_isr,
            'excedente_imss'=>$this->excedente_imss,
            'excedente_gmm'=>$this->excedente_gmm,
            'excedente_infonavit'=>$this->excedente_infonavit,
            'excedente_fonacot'=>$this->excedente_fonacot,
            'excedente_prestamos'=>$this->excedente_prestamos,
            'excedente_pension'=>$this->excedente_pension,
            'excedente_terceros'=>$this->excedente_terceros,
            'excedente_clientes'=>$this->excedente_clientes,
            'excedente_subsidio'=>$this->excedente_subsidio,
            'excedente_recuperacion'=>$this->excedente_recuperacion,
            'excedente_comision'=>$this->excedente_comision,
            'excedente_prenomina'=>$this->excedente_prenomina,
            'excedente_prenomina_gmm'=>$this->excedente_prenomina_gmm,
            'excedente_otros'=>$this->excedente_otros,
            'comentarios_nominas'=>$this->comentarios_nominas,
            'documentsName'=>$this->documentsName,
            'documentsTemp'=>$this->documentsTemp,
            'documentsSize'=>$this->documentsSize,
            'url'=>$this->url,
            'nomina_origen'=>$this->nomina_origen,
            'descuentos_sys'=>$this->descuentos_sys,
            'descuentos_asesores'=>$this->descuentos_asesores,
            'descuentos_terceros'=>$this->descuentos_terceros,
            'prestamos_empleados'=>$this->prestamos_empleados,
            'prestamos_ayudate'=>$this->prestamos_ayudate,
            'excedente_caja_ahorro'=>$this->excedente_caja_ahorro,
            'retencion_iva'=>$this->retencion_iva,
            'ajuste_subsidio_empleo'=>$this->ajuste_subsidio_empleo,
            'descuento_ayudate'=>$this->descuento_ayudate,
            'retencion_isn'=>$this->retencion_isn
        );
       
       echo Nominas::registrarNomina($data);
    }


    public function mostrarDataNomina(){
        echo Nominas::mostrarDataNomina2($this->id_nomina,$this->url);
    }
    
    public function verificarPermisoModificacion(){
        return Nominas::verificarPermisoModificacion($this->id_nomina);
    }

    public function actualizarFinanzasLiberacion(){
        echo Nominas::actualizarFinanzasLiberacion($this->id_nomina);
    }

    public function actualizarTesoreriaLiberacion(){
        echo Nominas::actualizarTesoreriaLiberacion($this->id_nomina);
    }
    
    public function actualizarNominasLiberacion(){
        echo Nominas::actualizarNominasLiberacion($this->id_nomina);
    }

    public function refrescarNominas(){
        $data = array(  'pendientes'=> Nominas::marcadores(1),
                        'liberadas'=> Nominas::marcadores(2),
                        'canceladas'=> Nominas::marcadores(3),
                        'pendientes2'=> Nominas::marcadores(4),
                        'pagadas'=> Nominas::marcadores(5),
                        'canceladas2'=> Nominas::marcadores(6));

        if($this->location === "/asesores/finanzas"){
            $this->liberado = 1;
            $this->url=$this->location;
        }
        else if($this->location === "/asesores/tesoreria"){
            $this->liberado = 2;
            $this->observaciones = 1;
            $this->url=$this->location;
        }
        else if($this->location === "/asesores/nominas"){
            $this->autorizacion = 0;
            $this->url=$this->location;
        }
        else
            $this->url=$this->location;

        $this->nominista = GrupoNominas::pertenece2($_SESSION['identificador']) ? $_SESSION['identificador'] : '';
           
        $this->paginador($data);
    }

    public function paginador($marcadores=""){

        $data = array( 'cliente'=>$this->cliente,
                       'facturado'=>$this->facturado,
                       'liberado'=>$this->liberado,
                       'nomina'=>$this->nomina,
                       'pago'=>$this->observaciones,
                       'nominista'=>$this->nominista,
                       'autorizacion'=>$this->autorizacion       
        ); 
 
         $paginacion = new Paginacion($this->registrosPorPagina);
         $paginacion->target($this->target);
         $totalRegistros = Nominas::contarRegistros($data,$this->url);
         $paginacion->totalPaginas($totalRegistros);
         $paginacion->paginaActual($this->paginaActual);
         $paginacion->parametroCliente($this->cliente);
         $paginacion->parametroNomina($this->nomina);
         $paginacion->parametroNominista($this->nominista);
         $paginacion->parametroFacturado($this->facturado);
         $paginacion->parametroLiberado($this->liberado);
         $paginacion->parametroPago($this->observaciones);
         $paginacion->parametrosAutorizacion($this->autorizacion);
         $mostrar = $paginacion->mostrar();
         //$flag = $this->url === "/asesores/liberacion" ? true : false; 
         $data = Nominas::mostrarNominas($paginacion->limitRegistros(),$data,$this->url);
        
         echo json_encode(array("error"=>false,"paginador"=>$mostrar,"html"=>$data,'total'=>$totalRegistros,'pendientes'=>$marcadores['pendientes'],'liberadas'=>$marcadores['liberadas'],'canceladas'=>$marcadores['canceladas'],'pendientes2'=>$marcadores['pendientes2'],'pagadas'=>$marcadores['pagadas'],'canceladas2'=>$marcadores['canceladas2']));
     }

    public function obtenerPorcentaje(){
        echo json_encode(Nominas::obtenerPorcentaje($this->id_cliente));
    }

    public function actualizarTesoreria(){
        $data = array(
            'id_nomina'=>$this->id_nomina,
            'estatus'=>$this->observaciones,
            'comentarios_tesoreria'=>$this->comentarios,
            'documentsName'=>$this->documentsName,
            'documentsTemp'=>$this->documentsTemp,
            'documentsSize'=>$this->documentsSize,
            'url'=>$this->url
        );
        $respuesta = Nominas::actualizarTesoreria($data);
        if( !$respuesta['error'] AND $this->observaciones == 4 AND $this->observaciones != $respuesta['anterior'])
            $a = Nominas::comunicarNominista($this->id_nomina);
        echo json_encode($respuesta);
    }

    public function archivosMasivos(){
        $respuesta = Nominas::archivosMasivos($this->documentsPatch,$this->url,$this->documentsName,$this->documentsTemp,$this->documentsSize);
        echo json_encode(array("error"=>false,"titulo"=>"Carga terminada","subtitulo"=>$respuesta));
    }

    public function actualizarFinanzas(){
        $data = array(
            'id_nomina'=>$this->id_nomina,
                //'numero_factura'=>$this->numero_factura,
            'financiada'=>$this->financiada,
            'fecha_envio'=>$this->fecha_envio,
            'hora_envio'=>$this->hora_envio,
            'observaciones'=>$this->observaciones,
            'fecha_liberacion'=>$this->fecha_liberacion,
            'fondeo_imss'=>$this->fondeo_imss,
            'fondeo_asimilados'=>$this->fondeo_asimilados,
            'comentarios_finanzas'=>$this->comentarios_finanzas,
            'documentsName'=>$this->documentsName,
            'documentsTemp'=>$this->documentsTemp,
            'documentsSize'=>$this->documentsSize,
            'url'=>$this->url
        );
        echo Nominas::actualizarFinanzas($data);
    }


    public function nominasManual(){

        $data=array('documentoNombre'=>$this->documentoNombre,
                    'documentoTipo'=>$this->documentoTipo,
                    'documentoTemporal'=>$this->documentoTemporal,
                    'documentoTamano'=>$this->documentoTamano
                    );
        
   
        $respuesta = Nominas::nominasManual($data,$this->target);

        if($respuesta['error'] === 'return')
            echo json_encode(array('error'=>true,"titulo"=>$respuesta['titulo'], "subtitulo"=>$respuesta['subtitulo']));

        else if($respuesta['totalCorrectos'] === 0){
            if(empty($respuesta['data'])){
                $respuesta['data'] = "1.- Verifica que el archivo que intentas subir tenga el formato correcto."."\r\n";
                $respuesta['data'].= "2.- En caso de que sea un único registro el que intentas guardar, asegurate de que el NOMBRE DEL CLIENTE se encuentre seleccionado."."\r\n";
            }
            echo json_encode(array('error'=>true,"alerta"=>false,"titulo"=>'Ocurrio un error', "subtitulo"=>"No se cargó ningún registro, consulta el archivo de texto que se descargó para conocer el motivo",'log'=>true,'dataLog'=>$respuesta['data']));
        }

        else if($respuesta['totalCorrectos'] > 0 AND $respuesta['totalAlertas'] > 0 AND $respuesta['totalErrores'] > 0) 
            echo json_encode(array('error'=>false,"alerta"=>true,"titulo"=>'Proceso correcto, pero con ALERTAS y ERRORES, consulta el archivo de texto que se descargó', "subtitulo"=>"Registro(s) correcto(s): ".$respuesta['totalCorrectos'].", Registro(s) error(es): ".$respuesta['totalErrores'],'log'=>true,'dataLog'=>$respuesta['data']));
        
        else if($respuesta['totalCorrectos'] > 0 AND $respuesta['totalAlertas'] > 0 AND $respuesta['totalErrores'] === 0) 
            echo json_encode(array('error'=>false,"alerta"=>true,"titulo"=>'Proceso correcto, pero con ALERTAS, consulta el archivo de texto que se descargó', "subtitulo"=>"Registro(s) correcto(s): ".$respuesta['totalCorrectos'],'log'=>true,'dataLog'=>$respuesta['data']));

        else if($respuesta['totalCorrectos'] > 0 AND $respuesta['totalAlertas'] === 0 AND $respuesta['totalErrores'] > 0) 
            echo json_encode(array('error'=>false,"alerta"=>true,"titulo"=>'Proceso correcto pero con ERRORES, consulta el archivo de texto que se descargó', "subtitulo"=>"Registro(s) correcto(s): ".$respuesta['totalCorrectos'].", Registro(s) error(es): ".$respuesta['totalErrores'],'log'=>true,'dataLog'=>$respuesta['data']));

        else {
            if($_SESSION['identificador'] == 168 || $_SESSION['identificador'] == 187)
                echo json_encode(array('error'=>false,"alerta"=>false,"titulo"=>'Proceso correcto', "subtitulo"=>"Se cargargaron correctamente: ".$respuesta['totalCorrectos']." registro(s)",'log'=>true,'dataLog'=>$respuesta['data']));
            else
                echo json_encode(array('error'=>false,"alerta"=>false,"titulo"=>'Proceso correcto', "subtitulo"=>"Se cargargaron correctamente: ".$respuesta['totalCorrectos']." registro(s)",'log'=>false,'dataLog'=>$respuesta['data']));
        }
       
           
    }

    public function perteneceNominas(){
        echo json_encode(array('error'=>false,'validacion'=>GrupoNominas::pertenece($_SESSION['identificador'])));
    }

    public function dataEliminar(){
        $sql = $this->dataArray($this->nomina);
        Nominas::dataEliminar($sql);
        echo json_encode(array('error'=>false,'data'=>$sql));
    }

    public function idNominaCargar(){
        $resp = Nominas::idNominaCargar($this->id_nomina);
        echo json_encode(array('error'=>$resp['error'],'data'=>$resp['data']));
    }

    public function dataLiberar(){
        $sql = $this->dataArray($this->nomina);
        Nominas::dataLiberar($sql);
        echo json_encode(array('error'=>false,'data'=>$sql));
    }

    private function dataArray($data){
        $miArreglo = substr($data,1);
        $miArreglo = substr($miArreglo,0,-1);
        $array = explode(",", $miArreglo);
        $sql ='';
        $tamano = count($array);

        for($i=0;$i<$tamano;$i++){
            $sql .= intval($array[$i]);
            if($i<$tamano-1)
                $sql.=',';
        }

        return $sql;
    }

    public function archivosNominas(){
        $respuesta = Nominas::archivosNominas($this->id_nomina,$this->nominista);
        echo json_encode(array('error'=>false,'archivos'=>$respuesta['archivos'],'total'=>$respuesta['total']));
    }
    
    public function archivosFinanzas(){
        $respuesta = Nominas::archivosFinanzas($this->id_nomina,$this->nominista);
        echo json_encode(array('error'=>false,'archivos'=>$respuesta['archivos'],'total'=>$respuesta['total']));
    }
    
    public function archivosTesoreria(){
        $respuesta = Nominas::archivosTesoreria($this->id_nomina,$this->nominista);
        echo json_encode(array('error'=>false,'archivos'=>$respuesta['archivos'],'total'=>$respuesta['total']));
    }

    public function eliminarArchivo(){
        if(!Nominas::eliminarArchivo($this->id_nomina,$this->url))
            echo json_encode(array('error'=>true,'titulo'=>'El archivo no puede ser borrado','subtitulo'=>'¡Intentalo nuevamente!'));
        else
            echo json_encode(array('error'=>false));
    }

    public function archivosAdjuntos(){
        if($this->location == 1)
            $respuesta = Nominas::archivosAdjuntos($this->id_nomina);
        else if($this->location == 2)
            $respuesta = Nominas::archivosAdjuntosFinanzas($this->id_nomina);
        else if($this->location == 3)
            $respuesta = Nominas::archivosAdjuntosTesoreria($this->id_nomina);
        else
            $respuesta = Nominas::archivosAdjuntosFacturacion($this->id_nomina);

        echo json_encode(array('error'=>false,'html'=>$respuesta['html'],'total'=>$respuesta['total']));
    }

    public function actualizarFacturacion(){
      $data = array(    'id'=>$this->id_nomina,
                        'estatus'=>$this->estatus_facturacion,
                        'numeroFactura'=>$this->numero_factura,
                        'numeroNota'=>$this->numero_nota,
                        'fechaPago'=>$this->fecha_liberacion,
                        'fechaFactura'=>$this->fecha_envio,
                        'comentarios'=>$this->comentarios_nominas,
                        'retencion_isn'=>$this->retencion_isn,
                        'total'=>$this->total,
                        'documentsName'=>$this->documentsName,
                        'documentsTemp'=>$this->documentsTemp,
                        'documentsSize'=>$this->documentsSize,
                        'url'=>$this->url
                    );
        echo Nominas::actualizarFacturacion($data);
    }
}

if(isset($_POST['nominasCliente'])){
    $a = New AjaxNominas();
    if(isset($_POST['actualizarNomina'])){
        $a->id_nomina = $_POST['actualizarNomina'];
        if(!$a->verificarPermisoModificacion()){
            echo json_encode(array('error'=>true,'titulo'=>'No tienes autorización para modificar esta nómina','subtitulo'=>'Sólo la persona que captura la nómina y su jefe inmediato pueden hacerlo'));
            return;
        }   
    }
    if(isset($_POST['nomina_origen']))
        $a->nomina_origen = $_POST['nomina_origen'];
    $a->tipo_esquema = $_POST['tipoEsquema'];
    if(isset($_POST['devengada']))
        $a->devengada = $_POST['devengada'];
    if(isset($_POST['devengadaFactura']))
        $a->devengadaFactura = $_POST['devengadaFactura'];
    if(isset($_POST['tipoSindicato']))
        $a->tipo_sindicato = $_POST['tipoSindicato'];
    $a->id_cliente =  $_POST['nominasCliente'];
    $a->tipo_pago =  $_POST['nominasTipoPago'];
    $a->regimen =  $_POST['nominasRegimen'];
    $a->comision =  $_POST['nominasComision'];
    $a->empresa_facturadora =  $_POST['nominasEmpresaFactura'];
    $a->subtotal =  $_POST['nominasSubtotal'];
    $a->iva =  $_POST['nominasIva'];
    $a->total =  $_POST['nominasTotal'];
    $a->empresa_imss =  $_POST['nominasEmpresaImss'];
    $a->total_imss =  $_POST['nominasTotalImss'];
    $a->empresa_asimilados =  $_POST['nominasEmpresaAsimilados'];
    $a->total_asimilados =  $_POST['nominasTotalAsimilados'];
    $a->tipo_periodo =  $_POST['nominasPeriodo'];
    $a->numero_periodo =  $_POST['nominasNumeroPeriodo'];
    $a->socios =  $_POST['nominasSocios'];
    $a->ingreso =  $_POST['nominasIngreso'];
    $a->infonavit =  $_POST['nominasInfonavit'];
    $a->fonacot =  $_POST['nominasFonacot'];
    $a->donativo =  $_POST['nominasDonativo'];
    $a->pension =  $_POST['nominasPensionAlimenticia'];
    $a->excedente_cargas =  $_POST['nominasExcedenteCargas'];
    $a->cargas_patronal =  $_POST['nominasCargaPatronal'];
    $a->isn =  $_POST['nominasIsn'];
    $a->comision_monto =  $_POST['nominasComisionMonto'];
    $a->imss_obrera =  $_POST['nominasImssObrera'];
    $a->carga_social_imss =  $_POST['nominasCargaSocialImss'];
    $a->prenomina_imss =  $_POST['nominasPrenominaImss'];
    $a->isr_isp =  $_POST['nominasIsrIsp'];
    $a->isr_142 =  $_POST['nominasIsr142'];
    $a->cuota_sindical =  $_POST['nominasCuotaSindical'];
    $a->despensa =  $_POST['nominasDespensa'];
    $a->caja_ahorro =  $_POST['nominasCajaAhorro'];
    $a->descuento_imss =  $_POST['nominasDescuentoImss'];
    $a->apoyo_sindical =  $_POST['nominasApoyoSindical'];
    $a->descuento_comedor =  $_POST['nominasDescuentoComedor'];
    $a->otros =  $_POST['nominasOtros'];
    $a->haberes =  $_POST['nominasHaberes'];
    $a->excedente_ingreso =  $_POST['nominasExcedenteIngreso'];
    $a->excedente_isr =  $_POST['nominasExcedenteIsr'];
    $a->excedente_imss =  $_POST['nominasExcedenteImss'];
    $a->excedente_gmm =  $_POST['nominasExcedenteGmm'];
    $a->excedente_infonavit =  $_POST['nominasExcedenteInfonavit'];
    $a->excedente_fonacot =  $_POST['nominasExcedenteFonacot'];
    $a->excedente_prestamos =  $_POST['nominasExcedentePrestamos'];
    $a->excedente_pension =  $_POST['nominasExcedentePensionAlimencia'];
    $a->excedente_terceros =  $_POST['nominasExcedenteTerceros'];//se cambio el nombre por INGRESOS SIN TIMBRAR
    $a->excedente_clientes =  $_POST['nominasExcedenteClientes'];
    $a->excedente_subsidio =  $_POST['nominasExcedenteSubsidio'];
    $a->excedente_recuperacion =  $_POST['nominasExcedenteRecuperacion'];
    $a->excedente_comision =  $_POST['nominasExcedenteComisionSocio'];
    $a->excedente_prenomina =  $_POST['nominasExcedentePrenominaImss'];
    $a->excedente_prenomina_gmm =  $_POST['nominasExcedentePrenominaGmm'];
    $a->excedente_otros =  $_POST['nominasExcedenteOtros'];
    $a->comentarios_nominas =  trim(nl2br(  $_POST['nominasComentarios'] ));

    $a->descuentos_sys = $_POST["nominasDescuentosSys"];
    $a->descuentos_asesores = $_POST["nominasDescuentosAsesores"];
    $a->descuentos_terceros = $_POST["nominasDescuentosTerceros"];
    $a->prestamos_empleados = $_POST["nominasPrestamosEmpleados"];
    $a->prestamos_ayudate = $_POST["nominasPrestamosAyudate"];
    $a->excedente_caja_ahorro = $_POST["nominasExcedenteCajaAhorro"];
    $a->retencion_iva = $_POST['retencionIva'];
    $a->ajuste_subsidio_empleo = $_POST['ajusteSubsidioEmpleo'];
    $a->descuento_ayudate = $_POST['descuentoAyudate'];
    if(isset($_POST['retencionIsn']))
        $a->retencion_isn = $_POST['retencionIsn'];
   
    if(isset($_POST['url']))
        $a->url= $_POST['url'];

    if(isset($_POST['totalFile'])){
        $a->totalFile = $_POST['totalFile'];
        for($i=0;$i<$a->totalFile;$i++){
            $a->documentsName[$i]= $_FILES["files".$i]["name"];
            $a->documentsTemp[$i]= $_FILES["files".$i]["tmp_name"];
            $a->documentsSize[$i]= $_FILES["files".$i]["size"];
        }
    }

    $a->registrarNomina();
}

else if(isset($_POST['folioNomina'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['folioNomina'];
    $a->url = $_POST['url'];
    $a->mostrarDataNomina();
}

else if(isset($_POST['refrescarNominas'])){
    $a = New AjaxNominas();
    $a->location=$_POST['location'];
    $a->liberado = "";
    $a->observaciones = "";
    $a->autorizacion = "";
    $a->refrescarNominas();
}

else if(isset($_POST['paginaActual'])){
    $a = New AjaxNominas();
    $a->paginaActual=$_POST['paginaActual'];
    $a->registrosPorPagina=$_POST['registrosPorPagina'];
    $a->target=$_POST['target'];
    $a->cliente=$_POST['cliente'];
    $a->nomina=$_POST['nomina'];
    $a->facturado=$_POST['facturado'];
    $a->liberado=$_POST['liberado'];
    $a->observaciones=$_POST['pago'];
    $a->url = $_POST['url'];
    $a->nominista = $_POST['nominista'];
    $a->autorizacion = $_POST['autorizacion'];
    $a->paginador();
}

else if(isset($_POST['obtenerPorcentaje'])){
    $a = New AjaxNominas();
    $a->id_cliente = $_POST['obtenerPorcentaje'];
    $a->obtenerPorcentaje();
}

else if(isset($_POST['actualizarFinanzas'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['actualizarFinanzas'];
    //$a->numero_factura =  $_POST['finanzasNumeroFactura'];
    if(isset($_POST['finanzasFinanciada']))
        $a->financiada =  $_POST['finanzasFinanciada'];
    $a->fecha_envio =  $_POST['finanzasFechaEnvio'];
    $a->hora_envio =  $_POST['finanzasHoraEnvio'];
    $a->observaciones =  $_POST['finanzasObservaciones'];
    $a->fecha_liberacion =  $_POST['finanzasFechaLiberaciones'];
    if(isset($_POST['finanzasFondeoImss']))
        $a->fondeo_imss =  $_POST['finanzasFondeoImss'];
    if(isset($_POST['finanzasFondeoAsimilados']))
        $a->fondeo_asimilados =  $_POST['finanzasFondeoAsimilados'];
    $a->comentarios_finanzas = trim(nl2br($_POST['finanzasComentarios']));



    if(isset($_POST['url']))
        $a->url= $_POST['url'];

    if(isset($_POST['totalFile'])){
        $a->totalFile = $_POST['totalFile'];
        for($i=0;$i<$a->totalFile;$i++){
            $a->documentsName[$i]= $_FILES["files".$i]["name"];
            $a->documentsTemp[$i]= $_FILES["files".$i]["tmp_name"];
            $a->documentsSize[$i]= $_FILES["files".$i]["size"];
        }
    }

    $a->actualizarFinanzas();
}

else if( isset($_POST['cargaManual'])){
    /*if($_POST['cargaManual'] =='/asesores/finanzas' || $_POST['cargaManual'] =='/asesores/tesoreria'){
        if($_SESSION['identificador'] != 168){
            echo json_encode(array('error'=>false,"alerta"=>false,"titulo"=>'En mantenimiento, disculpa las molestias', "subtitulo"=>"A la brevedad estará disponible una nueva versión",'log'=>false,'dataLog'=>false));
            return;
        }    
    }*/

    $a = New AjaxNominas();
    $a->documentoNombre = $_FILES["cargarRegistrosNominas"]["name"];
    $a->documentoTipo = $_FILES["cargarRegistrosNominas"]["type"];
    $a->documentoTemporal = $_FILES["cargarRegistrosNominas"]["tmp_name"];
    $a->documentoTamano = $_FILES["cargarRegistrosNominas"]["size"];
    $a->target = $_POST['cargaManual'];
    $a->nominasManual();
}

else if(isset($_POST['grupoAjax'])){
    $a = New AjaxNominas();
    $a->perteneceNominas();
}

else if(isset($_POST['idNominaFinanzas'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['idNominaFinanzas'];
    $a->actualizarFinanzasLiberacion();
}

else if(isset($_POST['idNominaTesoreria'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['idNominaTesoreria'];
    $a->actualizarTesoreriaLiberacion();
}

else if(isset($_POST['archivosNominas'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['archivosNominas'];
    $a->nominista = $_POST['nominista'];
    $a->archivosNominas();
}

else if(isset($_POST['archivosFinanzas'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['archivosFinanzas'];
    $a->nominista = $_POST['nominista'];
    $a->archivosFinanzas();
}

else if(isset($_POST['archivosTesoreria'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['archivosTesoreria'];
    $a->nominista = $_POST['nominista'];
    $a->archivosTesoreria();
}

else if(isset($_POST['actualizarTesoreria'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['actualizarTesoreria'];
    $a->observaciones=$_POST['tesoreriaEstatus'];
    $a->comentarios=trim(nl2br($_POST['tesoreriaComentarios']));


    if(isset($_POST['url']))
        $a->url= $_POST['url'];

    if(isset($_POST['totalFile'])){
        $a->totalFile = $_POST['totalFile'];
        for($i=0;$i<$a->totalFile;$i++){
            $a->documentsName[$i]= $_FILES["files".$i]["name"];
            $a->documentsTemp[$i]= $_FILES["files".$i]["tmp_name"];
            $a->documentsSize[$i]= $_FILES["files".$i]["size"];
        }
    }

    $a->actualizarTesoreria();
}

else if(isset($_POST['dataEliminar'])){
    $a = New AjaxNominas();
    $a->nomina = $_POST['dataEliminar'];
    $a->dataEliminar();
}

else if(isset($_POST['dataLiberar'])){
    $a = New AjaxNominas();
    $a->nomina = $_POST['dataLiberar'];
    $a->dataLiberar();
}

else if(isset($_POST['eliminarArchivo'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['eliminarArchivo'];
    $a->url = $_POST['rutaCarpeta'];
    $a->eliminarArchivo();
}

else if(isset($_POST['totalArchivosMasivos'])){
    $a = New AjaxNominas();
    $a->totalFile = $_POST['totalArchivosMasivos'];
    $a->url = $_POST['ruta'];
    
    for($i=0;$i<$a->totalFile;$i++){
        $a->documentsName[$i]= $_FILES["files".$i]["name"];
        $a->documentsTemp[$i]= $_FILES["files".$i]["tmp_name"];
        $a->documentsSize[$i]= $_FILES["files".$i]["size"];
        $a->peso += $_FILES["files".$i]["size"];

        $ruta = explode("/", $_POST["ruta".$i]);
        $ruta=array_reverse($ruta);
        $a->documentsPatch[$i]= $ruta[1];
    }

    if($a->peso > (50 * 1024 * 1024))
        return;
    
    $a->archivosMasivos();
}

else if(isset($_POST['archivosAdjuntos'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['archivosAdjuntos'];
    $a->location =  $_POST['location'];
    $a->archivosAdjuntos();
}

else if(isset($_POST['idNominaCargar'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['idNominaCargar'];
    if(!$a->verificarPermisoModificacion()){
        echo json_encode(array('error'=>false,'data'=>''));
        return;
    }
    $a->idNominaCargar();
}

else if(isset($_POST['actualizarFacturacion'])){
    $a = New AjaxNominas();
    $a->id_nomina = $_POST['actualizarFacturacion'];
    $a->estatus_facturacion =  $_POST['estatusFactura'];
    $a->numero_factura = $_POST['numeroFactura'];
    $a->numero_nota  = $_POST['numeroNota'];
    $a->fecha_liberacion = $_POST['fechaPagoFacturacion'];
    $a->fecha_envio = $_POST['fechaFacturacion'];
    $a->comentarios_nominas = $_POST['comentariosFacturacion'];
    $a->retencion_isn = $_POST['retencionIsn'];
    $a->total =  $_POST['nominasTotalCalculado'];
    if(isset($_POST['totalFile'])){
        $a->totalFile = $_POST['totalFile'];
        for($i=0;$i<$a->totalFile;$i++){
            $a->documentsName[$i]= $_FILES["files".$i]["name"];
            $a->documentsTemp[$i]= $_FILES["files".$i]["tmp_name"];
            $a->documentsSize[$i]= $_FILES["files".$i]["size"];
        }
    }
    $a->actualizarFacturacion();
}

