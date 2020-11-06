<?PHP
use PhpOffice\PhpSpreadsheet\IOFactory;
class Conciliacion {
   
    public static function numero($val,$fil,$col){
        $formato = !preg_match('/^[0-9,.]{4,}$/',$val);
        $error='';
        $flag=false;
       
        if($formato ){
            $error='Tienes un error en la fila: '.$fil.' Columna: '.$col.' No se pudo guardar, error por tipo de formato'."\r\n";
            $flag=true;
            return array("log"=>'error',"errores"=>$error);
        }else{
            $val = str_replace(',','',$val);
            return array("log"=>'correcto',"val"=>$val);
        }   
        
    }

    public static function convertirMes($indice){
        $indice=strtoupper($indice);
        $arreglo =  array(  "ENERO"=> 1,
                            "FEBRERO"=> 2,
                            "MARZO"=> 3,
                            "ABRIL"=> 4,
                            "MAYO"=> 5,
                            "JUNIO"=> 6,
                            "JULIO"=> 7,
                            "AGOSTO"=> 8,
                            "SEPTIEMBRE"=> 9,
                            "OCTUBRE"=> 10,
                            "NOVIEMBRE"=> 11,
                            "DICIEMBRE"=> 12,
                        );
        return $arreglo["$indice"];
    }

    public static function registrosMasivos($archivo){

        $info = new SplFileInfo($archivo["name"]);
        $extension = strtolower($info->getExtension());
        if($extension !== 'xlsx')
            return array('error'=>'return',"titulo"=>'Formato no válido', "subtitulo"=>'Formatos válidos: .xlsx');   
        $aleatorio = mt_rand(100,99999999);
        $hoy = date("YmdHis"); 
        $nombreArchivo = $hoy.$aleatorio.'.xlsx';               
        $ruta = "../intranet/documentos-nominas/".$nombreArchivo;       
        if(!move_uploaded_file($archivo['tmp_name'], $ruta))
             return array('error'=>'return',"titulo"=>'El archivo no pudo ser subido al servidor', "subtitulo"=>'error de carga');
        return self::cargarRegistros($ruta);

    }
    public static function cargarRegistros($ruta){
        $inicio = date("d-m-Y H:i:s");
        $start = microtime(true);

        try{
            $documento = IOFactory::load($ruta);
            $hojaActual = $documento->getSheet(1);
            $celda = $hojaActual->getCell('MNN1000');
            $valorRaw = $celda->getValue();
        }
        catch(Exception $e){
            unlink($ruta);
            return array("log"=>false,"data"=>'Necesitas utilizar la última versión del layout de COSTOS',"errores"=>0,"alerta"=>0,'version'=>false);
        }
        if($valorRaw!=="ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_MRV_COSTOS"){
            unlink($ruta);
            return array("log"=>false,"data"=>'ERROR DE VERSION !!',"version"=>false);
        }

        $hojaActual = $documento->getSheet(0);
        $errores='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $first_registro=0;
        $matriz_insert = array(); 
        $permiso_nominas=false;
        $permiso_imss=false;
        $permiso_gm=false;
        $flagformato=false;
        $privilegios = GrupoCostos::modulo($_SESSION['identificador']); // dependiendo de lo que 
        if ($privilegios === 'nominas') {
            $permiso_nominas=true;
        } else if ($privilegios === 'imss') {
            $permiso_imss=true;
        }else if ($privilegios === 'gm') {
            $permiso_imss=true;
            //$permiso_nominas=true;            
            //$permiso_gm=true;
        }
        $sqlCampos="mes,cliente, promotor,subcomisionista,codigo_cliente,empleados,imss,real_imss,rcv,real_rcv,infonavit,real_infonavit,impuesto_estatal,gmma,vida_invalidez,gmme,otros,subtotal,imss_obrero,real_imss_obrero,rcv_obrero,real_rcv_obrero,amortizacion,real_amortizacion,total,empresa,registro_imss,id_imss,comentarios_imss,registro_nominas,id_nominas,comentarios_nominas,registro_gm,id_gm,comentarios_gm,estatus,registro";
        foreach ($hojaActual->getRowIterator() as $fila){
            #VARIABLES 
            $flagObligatorio=false;
            #VARIABLES HA INSERTAR
            $subtotal_patronal=0;
            $total=0;
            $mes='';
            $clientee='';
            $codigo_cliente='';
            $subcomisionista='';
            $promotor=0;
            $empleados=0;
            $empresa='';
            $imss=0;
            $real_imss=0;
            $real_pagado_imss=0;
            $rcv=0;
            $real_rcv=0;
            $ajuste_rcv=0;
            $infonavit=0;
            $real_infonavit=0;
            $imss_obrero=0;
            $real_imss_obrero=0;
            $rcv_obrero=0;
            $real_rcv_obrero=0;
            $amortizacion=0;
            $real_amortizacion=0;
            $comentarios='';
            $codigo_cliente=0;
            $gmma=0;
            $vida_invalide=0;
            $gmme=0;
            $otros=0;
            $comentario_gm='';
            $impuesto_estatal=0;
            $comentario_nominas='';
            $id_insercion=0;
            $sqlCampos="";
            
            foreach ($fila->getCellIterator() as $celda) {

                    $fila = $celda->getRow();
                    $Columna = $celda->getColumn();
                    $valor=$celda->getFormattedValue();

                    /*if($fila > 100){   //Lo anulamos para permitir mas de 100 registros
                        $flagOverFlow = true; 
                        break;
                    }*/
                        
                    if($fila === 1){ 
                        $nombresColumnas["$Columna"]="$valor"; 
                    }
                    
                    if($fila > 3){ //En este if se realiza las validaciones y las captura de valores de cada celda 
                    
                        if($Columna === 'A' && $permiso_imss === true){
                            
                            $for_m = !preg_match('/^[a-zA-Z]+$/',$valor);
                                if($for_m == true){
                                    $errores.='Tienes un error en la fila: '.$fila.' Columna: '.$Columna.' No se pudo guardar el campo Mes es Obligatorio'."\r\n";
                                    $flagObligatorio=true;
                                    $flagformato=true;
                                    continue;
                                }else{
                                    $mes=self::convertirMes($valor);
                                    continue;
                                }
                                

                        }else if ($Columna === 'B' && $permiso_imss === true){
                            $for_c = !preg_match('/^[a-zA-Z0-9\s]+$/',$valor);
                                if($for_c == true){   
                                    $errores.='Tienes un error en la fila: '.$fila.' Columna: '.$Columna.' No se pudo guardar el campo Cliente es Obligatorio '."\r\n";
                                    $flagObligatorio=true;
                                    $flagformato=true;
                                    continue;
                                }else{
                                    $clientee=CostosModel::obtenerIdvarios($valor,'costos_clientes_ae');
                                }
                                continue;

                        }elseif ($Columna === 'C' && $permiso_imss === true){
                            if (empty($valor)){
                                $promotor=0;
                            }else {
                               $fort_p = (!preg_match('/^[a-zA-Z\s]+$/',$valor)) ?  $errores.='Tienes un error en la fila: '.$fila.' Columna: '.$Columna.' No se pudo guardar el campo Promotor '."\r\n" : $promotor=CostosModel::obtenerIdvarios($valor,'costos_promotor_ae');
                            }

                        }elseif ($Columna === 'D' && $permiso_imss === true){
                            if (empty($valor)){
                            }else {
                                $fort_s = (!preg_match('/^[a-zA-Z\s]+$/',$valor)) ?  $errores.='Tienes un error en la fila: '.$fila.' Columna: '.$Columna.' No se pudo guardar el campo Subcomisionista '."\r\n" : $subcomisionista=CostosModel::obtenerIdvarios($valor,'costos_subcomisionista_ae');
                            }
                            continue;

                        }elseif ($Columna === 'E' && $permiso_imss === true){
                            if (empty($valor)){
                                $codigo_cliente='';
                                }else{
                                    $codigo_cliente=$valor;
                                }
                            continue;

                        }elseif ($Columna === 'F' && $permiso_imss === true){
                            if (empty($valor) == true){
                                $empleados=0;
                                continue;
                            }else{
                                $empleados=$valor;
                            }
                            continue;

                        }elseif ($Columna === 'G' && $permiso_imss === true){
                            if (empty($valor)){
                                $empresa=0;
                            }else {
                                $fort_sub = (!preg_match('/^[a-zA-Z\s]+$/',$valor)) ?  $errores.='Tienes un error en la fila: '.$fila.' Columna: '.$Columna.' No se pudo guardar el campo Empresa '."\r\n" :$empresa=CostosModel::obtenerIdvarios($valor,'nominas_empresas_facturadoras_ae');
                            }
                            continue;

                        }elseif ($Columna === 'H' && $permiso_imss === true){ 
                            if(empty($valor)){
                                $valor=$imss; 
                            }else{
                                $format=self::numero($valor,$fila,$Columna);
                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $imss= $valor;
                                }
                            }
                            $subtotal_patronal+=$valor; $total+=$valor;
                            continue;

                        }elseif ($Columna === 'I' && $permiso_imss === true){ 
                            if(empty($valor)){
                                $valor=$real_imss; 
                            }else{
                                $format=self::numero($valor,$fila,$Columna);
                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $real_imss=$valor;
                                }
                            }
                            $total+=$valor;
                            continue;
                            
                        }elseif ($Columna === 'J' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$$rcv=$valor;; 
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $rcv=$valor;
                                }
                            }
                            $subtotal_patronal+=$valor;$total+=$valor;
                            continue;
                            
                        }elseif ($Columna === 'K' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$real_rcv;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $real_rcv=$valor;
                                }
                            }
                            $total+=$valor;
                            continue;
                            
                        }elseif ($Columna === 'L' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$infonavit;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $infonavit=$valor;
                                }
                            }
                            $subtotal_patronal+=$valor;$total+=$valor;
                            continue;

                        }elseif ($Columna === 'M' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$real_infonavit;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $real_infonavit=$valor;
                                }
                            }
                            $total+=$valor;
                            continue;
                            
                        }elseif ($Columna === 'N' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$imss_obrero;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $imss_obrero=$valor;
                                }
                            }
                            $total+=$valor;
                            continue;
                            
                        }elseif ($Columna === 'O' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$real_pagado_imss;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $real_pagado_imss=$valor;
                                }
                            }
                            $total+=$valor;
                            continue;

                        }elseif ($Columna === 'P' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$rcv_obrero;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $rcv_obrero=$valor;
                                }
                            }
                            $total+=$valor;
                            continue;
                            
                        }elseif ($Columna === 'Q' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$real_rcv_obrero;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $real_rcv_obrero=$valor;
                                }
                            }
                            $total+=$valor;
                            continue;
                            
                        }elseif ($Columna === 'R' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$amortizacion;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);
                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $amortizacion=$valor;
                                }
                            }
                            $total+=$valor;
                            
                        }elseif ($Columna === 'S' && $permiso_imss === true){
                            if(empty($valor)){
                                $valor=$real_amortizacion;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $real_amortizacion=$valor;
                                }
                            }
                            $total+=$valor;
                            
                            
                        }elseif ($Columna === 'T' && $permiso_imss === true){
                            $comentarios=$valor;
                            continue;
                        }else if ($Columna ==='U' && $permiso_gm === true){
                            if(empty($valor)){
                                $valor=$gmma;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $gmma=$valor;
                                }
                            } 
                            continue;
                            
                        }elseif ($Columna === 'V' && $permiso_gm === true){
                            if(empty($valor)){
                                $valor=$vida_invalide;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $vida_invalide=$valor;
                                }
                            }
                            continue;
                            
                        }elseif ($Columna === 'W' && $permiso_gm === true){
                            if(empty($valor)){
                                $valor=$gmme;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $gmme=$valor;
                                }
                            }
                            continue;
                            
                        }elseif ($Columna === 'X' && $permiso_gm === true){
                            if(empty($valor)){
                                $valor=$otros;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);

                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                }else{
                                    $valor=$format['val'];
                                    $otros=$valor;
                                }
                            }
                            continue;
    
                        }elseif ($Columna === 'Y' && $permiso_gm === true){
                            $comentario_gm=$valor;
                            continue;
                        }elseif ($Columna === 'Z' && $permiso_nominas === true){
                            if(empty($valor)){
                                $valor=$impuesto_estatal;
                            }else{
                                $format=self::numero($valor,$fila,$Columna);
                                if ($format['log']== 'error') {
                                    $errores.=$format['errores'];
                                    $flagformato=true;
                                    continue;
                                }else{
                                    $valor=$format['val'];
                                    $impuesto_estatal=$valor;
                                }
                            }
                            continue;

                        }elseif ($Columna === 'AA' && $permiso_nominas === true){
                            $comentario_nominas=$valor;
                            
                        }else if ($Columna == 'AB' AND $permiso_gm === true || $Columna == 'AB' && $permiso_nominas === true ){
                            $id_ins=!preg_match('/^[0-9]+$/',$valor);
                                if($id_ins == true){
                                    $errores.='Tienes un error en la fila: '.$fila.' Columna: '.$Columna.' Formato de ID incorrecto '."\r\n";
                                    
                                }else{
                                ($flagformato=CostosModel::idInsercionCostos($valor)) ? $errores.='Tienes un error en la fila: '.$fila.' Columna: '.$Columna.'  El ID de insercion no es existente'."\r\n" : $id_insercion = $valor;
                                continue;
                                }
                        }

                    }
                    
            }
            
            if(($fila > 3 AND !$flagObligatorio) AND !$finalizarLectura){
               // Aqui guardo la sentencia sql que se va generar por cada registro y la guardo en una matriz para depues aplicarla. dependiendo de que persona es la que realice la carga , la sentecia sera diferente
                $sqlCampos="mes,cliente, promotor,subcomisionista,codigo_cliente,empleados,imss,real_imss,rcv,real_rcv,infonavit,real_infonavit,impuesto_estatal,gmma,vida_invalidez,gmme,otros,subtotal,imss_obrero,real_imss_obrero,rcv_obrero,real_rcv_obrero,amortizacion,real_amortizacion,total,empresa,registro_imss,id_imss,comentarios_imss,registro_nominas,id_nominas,comentarios_nominas,registro_gm,id_gm,comentarios_gm,estatus,registro";
                $id_uruario=$_SESSION['identificador']; #id de quien esta realizando la carga de layout
                if ($permiso_imss == true) {
                    $sqli="INSERT INTO costos_ae ($sqlCampos) VALUES ($mes,'$clientee',$promotor,'$subcomisionista','$codigo_cliente',$empleados,$imss,$real_imss,$rcv,$real_rcv,$infonavit,$real_infonavit,NULL,NULL,NULL,NULL,NULL,$subtotal_patronal,$imss_obrero,$real_imss_obrero,$rcv_obrero,$real_rcv_obrero,$amortizacion,$real_amortizacion,$total,$empresa,NOW(),$id_uruario,'$comentarios',NULL,NULL,NULL,NULL,NULL,NULL,1,NOW())";
                } else if ($permiso_nominas == true) {
                    $sqli = "UPDATE costos_ae SET impuesto_estatal=$impuesto_estatal,comentarios_nominas='$comentario_nominas',registro_nominas=NOW(),id_nominas=$id_uruario WHERE id=$id_insercion";
                } else if($permiso_gm == true){
                    $sqli = "UPDATE costos_ae SET gmma=$gmma,vida_invalidez=$vida_invalide,gmme=$gmme,otros=$otros,comentarios_gm='$comentario_gm',registro_gm=NOW(),id_gm=$id_uruario WHERE id=$id_insercion";
                }
                $matriz_insert[$first_registro]=array('sqli'=>$sqli); // guardamos la sentencia en la variable sqli
                $first_registro++;
            }
            
        }
      //  if ($flagformato) {  errores
        if ($errores != '' ) {  // si la variable errores tiene algo es por que alguna celda fue incorrecta y no entrara , solo si no contiene algo entrara
            $encabezadoAlert = "";
            $encabezadoAlert.="##################################################################################################\r\n";
            $encabezadoAlert.="**                                         A L E R T A S                                        **\r\n";
            $encabezadoAlert.="##################################################################################################\r\n"."\r\n";
            $errores=$encabezadoAlert.$errores."\r\n";
            return array("log"=>true,"data"=>'ERROR DE TIPO DE FORMATO',"errores"=>$errores ,"alerta"=>true,"version"=>true,'fila'=>$first_registro);
        } else{
            $resConsulta=CostosModel::insercionLayout($matriz_insert);//realizamos la insercion , mandando la matriz donde guardamos las sentencias de cada registro

            if ($resConsulta == 3) {// dependiendo de la repuesta de nustra insercion , sera lo que te arrojara como alerta
                return array("log"=>false,"data"=>'Registro Completado !!',"version"=>true,"errores"=>$errores);
            } else if($resConsulta == 2){
                return array("log"=>true,"data"=>'Error en inesperado en la consulta',"errores"=>$errores,"alerta"=>5,"version"=>true,'fila'=>$first_registro);
            }else if($resConsulta == 1){
                return array("log"=>true,"data"=>'Error en permisos de insercion',"errores"=>$errores,"alerta"=>5,"version"=>true,'fila'=>$first_registro);
            }
        
        }
        return array("log"=>true,"data"=>'Se ha producido un error inesperado en el proceso ',"errores"=>$errores,"alerta"=>'ALERTA',"version"=>true,'fila'=>$first_registro);
       // return array("log"=>true,"data"=>'Se ha producido un error inesperado ');
    }

}
