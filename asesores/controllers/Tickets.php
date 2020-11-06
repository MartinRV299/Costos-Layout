<?php
class Tickets{
   
    public static function nuevoTicket($data){

        if(($data["imagenNombre"]) != NULL){
                $sizeMax = 2; // en MB
                if($data["imagenTamano"] > $sizeMax * 1024 * 1024)
                        return json_encode(array('error'=>true,'mensaje'=>"La imagen tiene un tamaño mayor al permitido",'mensaje2'=>"El peso máximo es de 2 MB",'tipo'=>'error'));
                
                $extensionImagen = explode("/",$data["imagenTipo"]);//image/jpg o image/png
                $extensionImagen = $extensionImagen[1];
                if($extensionImagen == "jpeg")
                        $extensionImagen = "jpg";
                if($extensionImagen == 'jpg' || $extensionImagen == 'png'){
                        $aleatorio = mt_rand(100,99999999);
                        $hoy = date("YmdHis"); 
                        $nombreArchivo = $aleatorio.$hoy.'.'.$extensionImagen;
                  
                        $ruta = "../intranet/imagenes-tickets/".$nombreArchivo;
                       
                        if($extensionImagen == "jpg")
                                $origen = imagecreatefromjpeg($data["imagenTemporal"]);
                        else 
                                $origen = imagecreatefrompng($data["imagenTemporal"]);
                                                      
                        if($extensionImagen== "jpg")
                                imagejpeg($origen, $ruta);
                          
                        else if($extensionImagen == "png")
                                imagepng($origen, $ruta);

                        imagedestroy($origen);//liberar memoria
                        $data["imagenNombre"]=$nombreArchivo;
                }
                else
                        return json_encode(array('error'=>true,'mensaje'=>"Sólo se permiten imagenes",'mensaje2'=>"Formato: .jpg, .jpeg y .png",'tipo'=>'error'));
        }

        if(($data["documentoNombre"]) != NULL){
                $sizeMax = 2; // en MB
                if($data["documentoTamano"] > $sizeMax * 1024 * 1024)
                        return json_encode(array('error'=>true,'mensaje'=>"El documento tiene un tamaño mayor al permitido",'mensaje2'=>"El peso máximo es de 2 MB",'tipo'=>'error'));
                
                $info = new SplFileInfo($data["documentoNombre"]);
                $extensionImagen = $info->getExtension();

                if($extensionImagen == 'doc' || $extensionImagen == 'docx' || $extensionImagen == 'xls' || $extensionImagen == 'xlsx' || $extensionImagen == 'pdf'){
                        $aleatorio = mt_rand(100,99999999);
                        $hoy = date("YmdHis"); 
                        $nombreArchivo = $aleatorio.$hoy.'.'.$extensionImagen;               
                        $ruta = "../intranet/documentos-tickets/".$nombreArchivo;       
                        move_uploaded_file($data['documentoTemporal'], $ruta);
                        $data["documentoNombre"]=$nombreArchivo;
                }
                else
                        return json_encode(array('error'=>true,'mensaje'=>"Formato no válido",'mensaje2'=>"Formatos válidos: .doc, .docx, .xls, .xlsx, y .pdf", "error",'tipo'=>'error'));
        }              
        /********validar datos */
        $respuesta = TicketsModel::nuevoTicket($data,Tablas::tickets());
        return $respuesta;
    }

    public static function mostrarColaTickets($data,$asignados=false){
        $respuesta = TicketsModel::mostrarColaTickets($data,$asignados,Tablas::tickets());
        $html='';
        $colorFila= true;
        //$boton='';
        foreach($respuesta as $row => $item){

          if( intval($_SESSION['identificador2']) === 6 AND $data < 1){      
                if($item["area"]==1){
                        $grupo='<ul id="'.$item["id_ticket"].'" value="1" class="dropdown-menu">
                                        <li><a href="#" id="'.AccesoSoporte::idUsuarios('Ulises').'" class="administradorAsignaTicket">Ulises</a></li>
                                        <li><a href="#" id="'.AccesoSoporte::idUsuarios('Juan').'" class="administradorAsignaTicket">Juan</a></li>
                                </ul>';
                }
                else if($item["area"]==2){
                        $grupo='<ul id="'.$item["id_ticket"].'" value="2" class="dropdown-menu">
                                        <li><a href="#" id="'.AccesoSoporte::idUsuarios('Miguel').'" class="administradorAsignaTicket">Miguel</a></li>
                                        <li><a href="#" id="'.AccesoSoporte::idUsuarios('Salvador').'" class="administradorAsignaTicket">Salvador</a></li>
                                </ul>';
                }
                else if($item["area"]==3){
                         $grupo='<ul id="'.$item["id_ticket"].'" value="3" class="dropdown-menu">
                                        <li><a href="#" id="'.AccesoSoporte::idUsuarios('Uriel').'" class="administradorAsignaTicket">Uriel</a></li>
                                </ul>';
                }
                
                $boton='<div class="btn-group campoDetalleEncabezado">
                                <a class="btn btn-success mostrarDatosTicket" data-toggle="modal" data-target="#mostrarTicketSoporte" href="#">Mostrar</a>
                                <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
                                        <span class="fa fa-caret-down" title="Toggle dropdown menu"></span>
                                </a>
                                '.$grupo.'
                        </div>';
               
          }
          else{
                $boton = '<div class="campoDetalleEncabezado"><button type="button" value="" class="btn btn-success mostrarDatosTicket" data-toggle="modal" data-target="#mostrarTicketSoporte">Mostrar</button></div>';
                if($data == 1){
                        $boton = '<div class="campoDetalleEncabezado"><button type="button" value="" class="btn btn-success mostrarDatosTicketAtendidos" data-toggle="modal" data-target="#mostrarDatosTicketAtendidos">Mostrar</button></div>';
                }
                else if($data == 2){
                        $tieneHistorial=TicketsModel::saberSiTicketTieneHistorial($item["id_ticket"],Tablas::tickets_historial());
                        if($tieneHistorial){
                                $boton='<div class="btn-group campoDetalleEncabezado">
                                                <a class="btn btn-success mostrarDatosTicketFinalizados" data-toggle="modal" data-target="#mostrarDatosTicketFinalizados" href="#">Mostrar</a>
                                                <a class="btn btn-success dropdown-toggle mostrarDatosHistorialTickets" value="'.$item["id_ticket"].'" data-toggle="modal" href="#" data-target="#mostrarHistorialTicketsLista">
                                                        <span class="fa fa-history" title="Historial del ticket"></span>
                                                </a>
                                        </div>';
                        }             
                        else{
                                $boton='<div class="btn-group campoDetalleEncabezado">
                                                <a class="btn btn-success mostrarDatosTicketFinalizados" data-toggle="modal" data-target="#mostrarDatosTicketFinalizados" href="#">Mostrar</a>
                                                <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
                                                        <span class="fa fa-minus" title="Sin historial"></span>
                                                </a>
                                        </div>';
                        }
                        ////////////////////////////
                        //$boton = '<div class="campoDetalleEncabezado"><button type="button" value="" class="btn btn-success mostrarDatosTicketFinalizados" data-toggle="modal" data-target="#mostrarDatosTicketFinalizados">Mostrar</button></div>';
                } 
          }

            $prioridad='text-green';
            if($item["prioridad"]==1){
                $prioridad='text-yellow';
            }
            else if($item["prioridad"]==2){
                $prioridad='text-red';
            }

            $icono='<i class="fa fa-wrench fa-2x '.$prioridad.'"></i>';
            if($item["area"]==2){
                $icono='<i class="fa fa-chrome fa-2x '.$prioridad.'"></i>';
            }
            else if($item["area"]==3){
                $icono='<i class="fa fa-file-code-o fa-2x '.$prioridad.'"></i>';
            }

            $usuario = Datos::mostrarUsuarioUnicoModel2($item["id_usuario"],Tablas::usuarios());
            $atiende='<div class="campoNombreTicketEncabezado">'.$usuario['nombre'].' '.$usuario['paterno'].' '.$usuario['materno'].'</div>';
            if($item["fecha_atendido"] != NULL && Configuraciones::administrador() == $_SESSION['identificador2']){
                $usuarioSoporte = Datos::mostrarUsuarioUnicoModel2($item["id_atiende_ticket"],Tablas::usuarios());
                $atiende='<div class="campoNombreTicketEncabezado" style="flex-direction:column"><div style="width:100%">'.$usuario['nombre'].' '.$usuario['paterno'].' '.$usuario['materno'].'</div><div style="width:100%;font-size:12px;background:#186E80;color:#fff;padding-left:10px;border:1px dotted #000">Atendido por: '.$usuarioSoporte['nombre'].'</div></div>';
            }
            else if($item["fecha_finalizado"] != NULL){
                $usuarioSoporte = Datos::mostrarUsuarioUnicoModel2($item["id_atiende_ticket"],Tablas::usuarios());
                $atiende='<div class="campoNombreTicketEncabezado" style="flex-direction:column"><div style="width:100%">'.$usuario['nombre'].' '.$usuario['paterno'].' '.$usuario['materno'].'</div><div style="width:100%;font-size:12px;background:#186E80;color:#fff;padding-left:10px;border:1px dotted #000">Atendido por: '.$usuarioSoporte['nombre'].'</div></div>';
            }

            $ticketDiaAnterior='';
            $reabrir='';

            if($item['reabrir'] == 2 || $item['ultima_fecha_cierre'] == date('Y-m-d')){
                $reabrir = 'style="background:#ff851b;color:#ffffff"';
            }

            else if($item['fecha_atendido']){
                $atendido = explode ( " ", $item['fecha_atendido']);
                if($atendido[0] != date("Y-m-d")){
                        $ticketDiaAnterior = '-<i class="fa fa-exclamation-triangle text-red" title="EL TICKET LEVA AL MENOS UN DÍA EN ESPERA DE SER ATENDIDO" style="cursor:pointer;"></i>';
                }
            }
       
            $html.='<div class="divContenedorPadre renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_ticket"].'">
                    <div class="campoIdTicketEncabezado" '.$reabrir.'>'.$item["id_ticket"].$ticketDiaAnterior.'</div>';
            $html.= $atiende;        
            $html.='<div class="campoAsuntoEncabezado">'.$item["asunto"].'</div>
                    <div class="campoSituacionEncabezado">'.$icono.'</div>';
            $html.=$boton;        
            $html.= '</div>';
        }
        return $html;
    }

    public static function totalHistorialTickets($fecha='',$usuario='',$limite=''){ 
        $respuesta = TicketsModel::historialTickets($fecha,$usuario,$limite,Tablas::tickets(),Tablas::usuarios());
        return count($respuesta);
    }

    public static function historialTickets($fecha,$usuario,$limite=''){
        $respuesta = TicketsModel::historialTickets($fecha,$usuario,$limite,Tablas::tickets(),Tablas::usuarios());
        $html='';
        $colorFila= true;

        foreach($respuesta as $row => $item){
           $tieneHistorial=TicketsModel::saberSiTicketTieneHistorial($item["id_ticket"],Tablas::tickets_historial());
           if($tieneHistorial){
                $boton='<div class="btn-group campoDetalleEncabezado">
                                <a class="btn btn-success mostrarDatosTicketFinalizados" data-toggle="modal" data-target="#mostrarDatosTicketFinalizados" href="#">Mostrar</a>
                                <a class="btn btn-success dropdown-toggle mostrarDatosHistorialTickets" value="'.$item["id_ticket"].'" data-toggle="modal" href="#" data-target="#mostrarHistorialTicketsLista">
                                        <span class="fa fa-history" title="Historial del ticket"></span>
                                </a>
                        </div>';
           }             
           else{
                $boton='<div class="btn-group campoDetalleEncabezado">
                                <a class="btn btn-success mostrarDatosTicketFinalizados" data-toggle="modal" data-target="#mostrarDatosTicketFinalizados" href="#">Mostrar</a>
                                <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
                                        <span class="fa fa-minus" title="Sin historial"></span>
                                </a>
                        </div>';
           }
                //$boton = '<div class="campoDetalleEncabezado"><button type="button" value="" class="btn btn-success mostrarDatosTicketFinalizados" data-toggle="modal" data-target="#mostrarDatosTicketFinalizados">Mostrar</button></div>';
           
            
            $prioridad='text-green';
            if($item["prioridad"]==1){
                $prioridad='text-yellow';
            }
            else if($item["prioridad"]==2){
                $prioridad='text-red';
            }

            $icono='<i class="fa fa-wrench fa-2x '.$prioridad.'"></i>';
            if($item["area"]==2){
                $icono='<i class="fa fa-chrome fa-2x '.$prioridad.'"></i>';
            }
            else if($item["area"]==3){
                $icono='<i class="fa fa-file-code-o fa-2x '.$prioridad.'"></i>';
            }

            $finalizado = explode ( " ", $item['fecha_finalizado']);

            $usuarioSoporte = Datos::mostrarUsuarioUnicoModel2($item["id_atiende_ticket"],Tablas::usuarios());
            $atiende='<div class="campoNombreTicketEncabezado" style="flex-direction:column"><div style="width:100%">'.$item['nombre'].' '.$item['paterno'].' '.$item['materno'].'</div><div style="width:100%;font-size:12px;background:#186E80;color:#fff;padding-left:10px;border:1px dotted #000">Atendido por: '.$usuarioSoporte['nombre'].'</div></div>';
            

            $html.='<div class="divContenedorPadre renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_ticket"].'">
                    <div class="campoIdTicketEncabezado">'.$item["id_ticket"].'</div>';
            $html.= $atiende;        
            $html.='<div class="campoAsuntoEncabezado">'.$item["asunto"].'</div>
                    <div class="campoCierreEncabezado textoMay">'.MetodosDiversos::formatearFecha($finalizado[0],true).'</div>
                    <div class="campoSituacionEncabezado">'.$icono.'</div>';
            $html.=$boton;        
            $html.= '</div>';
        }
        return $html;
    }

    public static function historialTicketsUsuario(){
        $respuesta = TicketsModel::historialTicketsUsuario(Tablas::tickets(),Tablas::usuarios());
        $html='';
        $colorFila= true;

        foreach($respuesta as $row => $item){

            $icono2 = '<i class="fa fa-eye text-black"></i>';
            if($item['visto'])
              $icono2 = '<i class="fa fa-eye-slash"></i>';  

            $boton = '<div class="campoDetalleEncabezado"><button type="button" value="" class="btn btn-success mostrarDatosTicketUsuario" data-toggle="modal" data-target="#mostrarDatosTicket"><span>'.$icono2.'</span> Mostrar</button></div>';

            $icono= 'ABIERTO';
            if($item['situacion'] == 1)
                $icono= 'ASIGNADO';
            else if($item['situacion'] == 2)
                $icono= 'CERRADO';
            
           

            $registrado = explode ( " ", $item['fecha_registro']);

            $nombre='<b>PENDIENTE POR SER ATENDIDO</b>';
            if($item["id_atiende_ticket"] != NULL){
                $usuarioSoporte = Datos::mostrarUsuarioUnicoModel2($item["id_atiende_ticket"],Tablas::usuarios());
                $nombre = $usuarioSoporte['nombre'].' '.$usuarioSoporte['paterno'].' '.$usuarioSoporte['materno'];
            }
            
            $atiende='<div class="campoNombreTicketEncabezado">'.$nombre.'</div>';
            
            $html.='<div class="divContenedorPadre renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_ticket"].'">
                    <div class="campoIdTicketEncabezado">'.$item["id_ticket"].'</div>';
            $html.= $atiende;        
            $html.='<div class="campoAsuntoEncabezado">'.$item["asunto"].'</div>
                    <div class="campoCierreEncabezado textoMay">'.MetodosDiversos::formatearFecha($registrado[0],true).'</div>
                    <div class="campoSituacionEncabezado" style="font-size:13px;">'.$icono.'</div>';
            $html.=$boton;        
            $html.= '</div>';
        }
        return $html;     
    }

    public static function mostaraDatosTicket($ticket){
        $html=$html2=$html3='';
        $respuesta = TicketsModel::mostaraDatosTicket($ticket,Tablas::tickets());
        $empleado = Datos::mostrarDatosEmpleadoAgenda($respuesta['id_usuario'],Tablas::usuarios());
        $sucursal = Sucursales::mostrarSucursalActualizarModel($empleado["id_sucursal"],"sucursales_ae");
        $fecha = explode ( " ", $respuesta['fecha_registro']);
        $fAtendido=$horaAtendido='';
        $fFinalizado=$horaFinalizado='';
        $tiempoRespuesta='';
        if($respuesta['fecha_atendido']!= NULL){
             $atendido = explode ( " ", $respuesta['fecha_atendido']);
             $fAtendido = date("g:i a",strtotime($atendido[1]));
             $horaAtendido = MetodosDiversos::formatearFecha($atendido[0],true);
        }
        if($respuesta['fecha_finalizado']!= NULL){
            $finalizado = explode ( " ", $respuesta['fecha_finalizado']);
            $fFinalizado = date("g:i a",strtotime($finalizado[1]));
            $horaFinalizado = MetodosDiversos::formatearFecha($finalizado[0],true);
            $tiempoRespuesta=MetodosDiversos::tiempoRespuesta($respuesta['fecha_registro'],$respuesta['fecha_finalizado']);
       }
       $area='';
       $subcategoria='';
       $segmento='';
       if($respuesta["area"] == 1){
            $area='SOPORTE TÉCNICO';
            switch($respuesta["subcategoria"]){
                case 1: $subcategoria='Carpetas en Red';
                        break;
                case 2: $subcategoria='CONTPAQi Adminpaq';
                        break;
                case 3: $subcategoria='CONTPAQi Contabilidad y Bancos';
                        break;
                case 4: $subcategoria='CONTPAQi Facturación';
                        break;
                case 5: $subcategoria='CONTPAQi Nomipaq';
                        break;
                case 6: $subcategoria='Correo electrónico';
                        break;
                case 7: $subcategoria='Impresoras y Toner';
                        break;
                case 8: $subcategoria='Paquetería Office(Excel,Word, Power Point, etc.)';
                        break;
                case 9: $subcategoria='Red e Internet';
                        break;
                case 10: $subcategoria='Spark';
                        break;
                case 11: $subcategoria='XML';
                        break;
                case 12: $subcategoria='Otra';
                        break;                          
            }
       }
       else if($respuesta["area"] == 2){
            $area='GIRO';
            switch($respuesta["subcategoria"]){
                case 1: $subcategoria='Nóminas';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Cálculos Extraordinarios';
                                    break;
                            case 2: $segmento='Finiquitos';
                                    break;
                            case 3: $segmento='Aguinaldos';
                                    break;
                            case 4: $segmento='Conexión a escritorio remoto';
                                    break;
                            case 5: $segmento='Usuarios y contraseñas';
                                    break;
                            case 6: $segmento='Reportes';
                                    break;
                            case 7: $segmento='Cálculos Extraordinarios';
                                    break;
                            case 8: $segmento='Timbrado';
                                    break;
                            case 9: $segmento='Alta de Clientes / Tipos de Nómina';
                                    break;
                            case 10: $segmento='Alta de Puestos';
                                    break;
                            case 11: $segmento='Alta de Turnos';
                                    break;
                            case 12: $segmento='Movimientos masivos';
                                    break;
                            case 13: $segmento='Otros';
                                    break;
                        }
                        break;
                case 2: $subcategoria='Procesos IMSS';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Altas';
                                    break;
                            case 2: $segmento='Bajas';
                                    break;
                            case 3: $segmento='Modificaciones salariales';
                                    break;
                            case 4: $segmento='Reingresos';
                                    break;
                            case 5: $segmento='Alta de Registros patronales';
                                    break;
                            case 6: $segmento='INFONAVIT';
                                    break;
                            case 7: $segmento='FONACOT';
                                    break;
                            case 8: $segmento='Movimientos masivos';
                                    break;
                            case 9: $segmento='Otros';
                                    break;
                        }
                        break;
                case 3: $subcategoria='Módulo Pre Alta';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Captura de información';
                                    break;
                            case 2: $segmento='Correo electrónico';
                                    break;
                            case 3: $segmento='Exportación de empleados';
                                    break;
                            case 4: $segmento='Otros';
                                    break;
                        }
                        break;
                case 4: $subcategoria='Módulo Recibos CFDI';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Error en timbre';
                                    break;
                            case 2: $segmento='XML y PDF';
                                    break;
                            case 3: $segmento='Reportes';
                                    break;
                            case 4: $segmento='Falta de timbres';
                                    break;
                            case 5: $segmento='Otros';
                                    break;
                        }
                        break;
                case 5: $subcategoria='Módulo Archivo Electrónico';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Alta de nuevos documentos';
                                    break;
                            case 2: $segmento='Otros';
                                    break;
                        }
                        break;
                case 6: $subcategoria='Otra';
                        break;
            }
        }
        else{
            $area='DESARROLLO DE SOFTWARE';
            switch($respuesta["subcategoria"]){
                case 1: $subcategoria='Ingreso al sistema';
                        break;
                case 2: $subcategoria='Módulo agenda empresarial';
                        break;
                case 3: $subcategoria='Módulo inicio';
                        break;
                case 4: $subcategoria='Módulo mi cuenta';
                        break;
                case 5: $subcategoria='Módulo paquetería';
                        break;
                case 6: $subcategoria='Módulo solicitudes';
                        break;
                case 7: $subcategoria='Módulo tickets';
                        break;
                case 8: $subcategoria='Otra';
                        break;
            }
        }

        //$finalizado = explode ( " ", $respuesta['fecha_finalizado']);
        $html.='<div class="row" style="margin-top:10px;" id="situacionApertura" value='.$respuesta['situacion'].'>
                    <div class="col-md-6">
                        <span><b>Ticket: </b><span id="categoriaTicketModal" value="'.$respuesta['area'].'" style="font-size:25px;background:#00a65a;padding:10px;color:#fff;border-radius:5px;">'.$respuesta['id_ticket'].'</span></span>
                    </div>
                    <div class="col-md-6">
                        <span id="folioTicket" value="'.$respuesta['id_ticket'].'"><b></b><span style="line-height:25px;"></span></span>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-4">
                        <span><b>Registrado: </b><span class="textoMay" style="font-size:13px;">'.date("g:i a",strtotime($fecha[1])).' <i class="fa fa-clock-o text-aqua"></i> - '.MetodosDiversos::formatearFecha($fecha[0],true).' <i class="fa fa-calendar text-yellow"></i></span></span>
                    </div>
                    <div class="col-md-4">
                        <span><b>Atendido: </b><span class="textoMay" style="font-size:13px;">'.$fAtendido .' <i class="fa fa-clock-o text-aqua"></i> - '.$horaAtendido.' <i class="fa fa-calendar text-yellow"></i></span></span>
                    </div>
                    <div class="col-md-4">
                        <span><b>Finalizado: </b><span class="textoMay" style="font-size:13px;">'.$fFinalizado.' <i class="fa fa-clock-o text-aqua"></i> - '.$horaFinalizado.' <i class="fa fa-calendar text-yellow"></i></span></span>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">   
                    <div class="col-md-12">
                        <span><b>Tiempo de respuesta: </b><span class="textoMay" style="font-size:13px;">'.$tiempoRespuesta.'</span></span>
                    </div>
                </div>
                <hr>';
        $html2.='<div class="row" style="margin-top:-15px;">
					<div class="col-md-12">
						<span class="encabezadoDato">Nombre: </span>'.$empleado['nombre'] .' '.$empleado['paterno'].' '.$empleado['materno'].'
					</div>
                </div>
                <div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Sucursal: </span>'.$sucursal['nombre'].'
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Departamento: </span>'.Departamentos::vistaDepartamentos2Model($empleado["id_departamento"],"departamentos_ae").'
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Puesto: </span>'.Departamentos::vistaPuestos2Model($empleado["id_puesto"],"puestos_ae").'
					</div>
                </div>
                <br>
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Correo: </span>'.$empleado["correo"].'
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Teléfono: </span>'.gestionSucursales::mostrarTelefonos2Controllers($sucursal["id_sucursal"]).'
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Extensión: </span>'.$empleado["extension"].'
					</div>
                </div>
                <hr>';
        $html3='<div class="row">
                    <div class="col-md-6">
                        <span class="encabezadoDato">Categoria: </span>'.$area.'
                    </div>
                    <div class="col-md-6">
                        <span class="encabezadoDato">Subcategoria: </span><span class="textoMay">'.$subcategoria.'</span>
                    </div>
                </div>';
        if($respuesta["segmento"] != NULL){
        $html3.='<div class="row">
                    <div class="col-md-12">
                        <span class="encabezadoDato">Segmento: </span><span class="textoMay">'.$segmento.'</span>
                    </div>
                </div>';
        }
        $html3.='<div class="row">
                    <div class="col-md-6">
                        <span class="encabezadoDato">Asunto: </span><span id="getAsunto">'.$respuesta["asunto"].'</span>
                    </div>
                    <div class="col-md-6">
                        <span class="encabezadoDato">Archivos: </span>';
        if($respuesta['imagen']!=NULL){
                $html3.='<div class="btn btn-default btn-file">
                                <i class="fa fa-download"></i>
                                <a href="intranet/imagenes-tickets/'.$respuesta['imagen'].'" download="imagen-'.$empleado['nombre'].'-'.$respuesta['id_ticket'].'">Imagen</a>
                        </div>';
        }
        if($respuesta['documento']!=NULL){
                $html3.='<div class="btn btn-default btn-file">
                                <i class="fa fa-download"></i>
                                <a href="intranet/documentos-tickets/'.$respuesta['documento'].'" download="documento-'.$empleado['nombre'].'-'.$respuesta['id_ticket'].'">Documento</a>
                        </div>';
        }
           $html3.='</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        '.mb_strtoupper($respuesta["descripcion"],'utf-8').'
                    </div>
                </div>';
                
                echo json_encode(array('html'=>$html,'html2'=>$html2,'html3'=>$html3,'imagen'=>$empleado['imagen'],'numero'=>$respuesta['id_ticket']));
    }

    public static function mostaraDatosTicket2($ticket){
        $html=$html2=$html3='';
        $respuesta = TicketsModel::mostaraDatosTicket($ticket,Tablas::tickets());
        $empleado = Datos::mostrarDatosEmpleadoAgenda($respuesta['id_atiende_ticket'],Tablas::usuarios());
        $sucursal = Sucursales::mostrarSucursalActualizarModel($empleado["id_sucursal"],Tablas::sucursales());

        if($respuesta['visto']){
                TicketsModel::ticketVisto($ticket,Tablas::tickets());//el usuario ya leyo la respuesta
                //$icono = 1;
        }

        $fecha = explode ( " ", $respuesta['fecha_registro']);
        $fAtendido=$horaAtendido='';
        $fFinalizado=$horaFinalizado='';
        $tiempoRespuesta='';
        if($respuesta['fecha_atendido']!= NULL){
             $atendido = explode ( " ", $respuesta['fecha_atendido']);
             $fAtendido = date("g:i a",strtotime($atendido[1]));
             $horaAtendido = MetodosDiversos::formatearFecha($atendido[0],true);
        }
        if($respuesta['fecha_finalizado']!= NULL){
            $finalizado = explode ( " ", $respuesta['fecha_finalizado']);
            $fFinalizado = date("g:i a",strtotime($finalizado[1]));
            $horaFinalizado = MetodosDiversos::formatearFecha($finalizado[0],true);
            $tiempoRespuesta=MetodosDiversos::tiempoRespuesta($respuesta['fecha_registro'],$respuesta['fecha_finalizado']);
       }
       $area='';
       $subcategoria='';
       $segmento='';
       if($respuesta["area"] == 1){
            $area='SOPORTE TÉCNICO';
            switch($respuesta["subcategoria"]){
                case 1: $subcategoria='Carpetas en Red';
                        break;
                case 2: $subcategoria='CONTPAQi Adminpaq';
                        break;
                case 3: $subcategoria='CONTPAQi Contabilidad y Bancos';
                        break;
                case 4: $subcategoria='CONTPAQi Facturación';
                        break;
                case 5: $subcategoria='CONTPAQi Nomipaq';
                        break;
                case 6: $subcategoria='Correo electrónico';
                        break;
                case 7: $subcategoria='Impresoras y Toner';
                        break;
                case 8: $subcategoria='Paquetería Office(Excel,Word, Power Point, etc.)';
                        break;
                case 9: $subcategoria='Red e Internet';
                        break;
                case 10: $subcategoria='Spark';
                        break;
                case 11: $subcategoria='XML';
                        break;
                case 12: $subcategoria='Otra';
                        break;                                  
            }
       }
       else if($respuesta["area"] == 2){
            $area='GIRO';
            switch($respuesta["subcategoria"]){
                case 1: $subcategoria='Nóminas';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Cálculos Extraordinarios';
                                    break;
                            case 2: $segmento='Finiquitos';
                                    break;
                            case 3: $segmento='Aguinaldos';
                                    break;
                            case 4: $segmento='Conexión a escritorio remoto';
                                    break;
                            case 5: $segmento='Usuarios y contraseñas';
                                    break;
                            case 6: $segmento='Reportes';
                                    break;
                            case 7: $segmento='Cálculos Extraordinarios';
                                    break;
                            case 8: $segmento='Timbrado';
                                    break;
                            case 9: $segmento='Alta de Clientes / Tipos de Nómina';
                                    break;
                            case 10: $segmento='Alta de Puestos';
                                    break;
                            case 11: $segmento='Alta de Turnos';
                                    break;
                            case 12: $segmento='Movimientos masivos';
                                    break;
                            case 13: $segmento='Otros';
                                    break;
                        }
                        break;
                case 2: $subcategoria='Procesos IMSS';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Altas';
                                    break;
                            case 2: $segmento='Bajas';
                                    break;
                            case 3: $segmento='Modificaciones salariales';
                                    break;
                            case 4: $segmento='Reingresos';
                                    break;
                            case 5: $segmento='Alta de Registros patronales';
                                    break;
                            case 6: $segmento='INFONAVIT';
                                    break;
                            case 7: $segmento='FONACOT';
                                    break;
                            case 8: $segmento='Movimientos masivos';
                                    break;
                            case 9: $segmento='Otros';
                                    break;
                        }
                        break;
                case 3: $subcategoria='Módulo Pre Alta';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Captura de información';
                                    break;
                            case 2: $segmento='Correo electrónico';
                                    break;
                            case 3: $segmento='Exportación de empleados';
                                    break;
                            case 4: $segmento='Otros';
                                    break;
                        }
                        break;
                case 4: $subcategoria='Módulo Recibos CFDI';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Error en timbre';
                                    break;
                            case 2: $segmento='XML y PDF';
                                    break;
                            case 3: $segmento='Reportes';
                                    break;
                            case 4: $segmento='Falta de timbres';
                                    break;
                            case 5: $segmento='Otros';
                                    break;
                        }
                        break;
                case 5: $subcategoria='Módulo Archivo Electrónico';
                        switch($respuesta["segmento"]){
                            case 1: $segmento='Alta de nuevos documentos';
                                    break;
                            case 2: $segmento='Otros';
                                    break;
                        }
                        break;
                case 6: $subcategoria='Otra';
                        break;
            }
        }
        else{
            $area='DESARROLLO DE SOFTWARE';
            switch($respuesta["subcategoria"]){
                case 1: $subcategoria='Ingreso al sistema';
                        break;
                case 2: $subcategoria='Módulo agenda empresarial';
                        break;
                case 3: $subcategoria='Módulo inicio';
                        break;
                case 4: $subcategoria='Módulo mi cuenta';
                        break;
                case 5: $subcategoria='Módulo paquetería';
                        break;
                case 6: $subcategoria='Módulo solicitudes';
                        break;
                case 7: $subcategoria='Módulo tickets';
                        break;
                case 8: $subcategoria='Otra';
                        break;
            }
        }

        $labelAtiende = 'Te atiende: ';
        if($respuesta['situacion'] == 2)
                $labelAtiende = 'Te atendio: ';
        //$finalizado = explode ( " ", $respuesta['fecha_finalizado']);
        $html.='<div class="row" style="margin-top:10px;">
                    <div class="col-md-6">
                        <span><b>Ticket: </b><span id="categoriaTicketModal" value="'.$respuesta['area'].'" style="font-size:25px;background:#00a65a;padding:10px;color:#fff;border-radius:5px;">'.$respuesta['id_ticket'].'</span></span>
                    </div>
                    <div class="col-md-6">
                        <span id="folioTicket" value="'.$respuesta['id_ticket'].'"><b></b><span style="line-height:25px;"></span></span>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-4">
                        <span><b>Registrado: </b><span class="textoMay" style="font-size:13px;">'.date("g:i a",strtotime($fecha[1])).' <i class="fa fa-clock-o text-aqua"></i> - '.MetodosDiversos::formatearFecha($fecha[0],true).' <i class="fa fa-calendar text-yellow"></i></span></span>
                    </div>
                    <!--<div class="col-md-4">
                        <span><b>Atendido: </b><span class="textoMay" style="font-size:13px;">'.$fAtendido .' <i class="fa fa-clock-o text-aqua"></i> - '.$horaAtendido.' <i class="fa fa-calendar text-yellow"></i></span></span>
                    </div>-->
                    <div class="col-md-4">
                        <span><b>Finalizado: </b><span class="textoMay" style="font-size:13px;">'.$fFinalizado.' <i class="fa fa-clock-o text-aqua"></i> - '.$horaFinalizado.' <i class="fa fa-calendar text-yellow"></i></span></span>
                    </div>
                </div>
                <!--<div class="row" style="margin-top:10px;">   
                    <div class="col-md-12">
                        <span><b>Tiempo de respuesta: </b><span class="textoMay" style="font-size:13px;">'.$tiempoRespuesta.'</span></span>
                    </div>
                </div>-->
                <hr>';
        $html2.='<div class="row" style="margin-top:-15px;">
					<div class="col-md-12">
						<span class="encabezadoDato">'.$labelAtiende.' </span>'.$empleado['nombre'] .' '.$empleado['paterno'].' '.$empleado['materno'].'
					</div>
                </div>
                <div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Sucursal: </span>'.$sucursal['nombre'].'
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Departamento: </span>'.Departamentos::vistaDepartamentos2Model($empleado["id_departamento"],"departamentos_ae").'
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Puesto: </span>'.Departamentos::vistaPuestos2Model($empleado["id_puesto"],"puestos_ae").'
					</div>
                </div>
                <br>
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Correo: </span>'.$empleado["correo"].'
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Teléfono: </span>'.gestionSucursales::mostrarTelefonos2Controllers($sucursal["id_sucursal"]).'
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<span class="encabezadoDato">Extensión: </span>'.$empleado["extension"].'
					</div>
                </div>
                <hr>';
        $html3='<div class="row">
                    <div class="col-md-6">
                        <span class="encabezadoDato">Categoria: </span>'.$area.'
                    </div>
                    <div class="col-md-6">
                        <span class="encabezadoDato">Subcategoria: </span><span class="textoMay">'.$subcategoria.'</span>
                    </div>
                </div>';
        if($respuesta["segmento"] != NULL){
        $html3.='<div class="row">
                    <div class="col-md-12">
                        <span class="encabezadoDato">Segmento: </span><span class="textoMay">'.$segmento.'</span>
                    </div>
                </div>';
        }
        $html3.='<div class="row">
                    <div class="col-md-6">
                        <span class="encabezadoDato">Asunto: </span>'.$respuesta["asunto"].'
                    </div>
                    <div class="col-md-6">
                        <span class="encabezadoDato">Archivos: </span>';
        if($respuesta['imagen']!=NULL){
                $html3.='<div class="btn btn-default btn-file">
                                <i class="fa fa-download"></i>
                                <a href="intranet/imagenes-tickets/'.$respuesta['imagen'].'" download="imagen-'.$empleado['nombre'].'-'.$respuesta['id_ticket'].'">Imagen</a>
                        </div>';
        }
        if($respuesta['documento']!=NULL){
                $html3.='<div class="btn btn-default btn-file">
                                <i class="fa fa-download"></i>
                                <a href="intranet/documentos-tickets/'.$respuesta['documento'].'" download="documento-'.$empleado['nombre'].'-'.$respuesta['id_ticket'].'">Documento</a>
                        </div>';
        }
           $html3.='</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        '.mb_strtoupper($respuesta["descripcion"],'utf-8').'
                    </div>
                </div>';

        if($respuesta['situacion'] == 2){
          $html3.='<hr>
                <div class="row">
                        <div class="col-md-12">
                                <p class="callout callout-success">*En caso de que consideres que tu problema no fue resuelto puedes solicitar al equipo de sistemas que reabran el ticket para dar seguimiento al caso.</p>
                        </div>
                 </div>';
        }        
                echo json_encode(array('html'=>$html,'html2'=>$html2,'html3'=>$html3,'imagen'=>$empleado['imagen'],'numero'=>$respuesta['id_ticket'],'estadoBoton'=>$respuesta['situacion']));
    }

    public static function asignarTicket($ticket,$area,$atiende){
        /********validar datos */
        $respuesta = TicketsModel::asignarTicket($ticket,$area,$atiende,Tablas::tickets());
        echo $respuesta;
    }

    public static function cerrarTicket($ticket,$solucion,$causa,$problema){
        /********validar datos */
        $respuesta = TicketsModel::cerrarTicket($ticket,$solucion,$causa,$problema,Tablas::tickets());
        echo $respuesta;
    }

    public static function cerrarTicket2($ticket){
        /********validar datos */
        $respuesta = TicketsModel::CerraTicketReabierto($ticket,Tablas::tickets());
        echo $respuesta;
    }

    public static function actualizarSolucion($ticket,$solucion,$causa,$problema){
        /********validar datos */
        $respuesta = TicketsModel:: actualizarSolucion($ticket,$solucion,$causa,$problema,Tablas::tickets());
        echo $respuesta;
    }
   
    public static function usuarios($sucursal){
		$respuesta=Datos::usuariosEquiposComputo($sucursal,Tablas::usuarios());
		$campo='<select class="form-control textoMay" name="usuarioTicketCreado" id="usuarioTicketCreado" required><option value=""></option>';
		foreach($respuesta as $row => $item){
			$campo.='<option value="'.$item["id_usuario"].'">'.$item["nombre"].' '.$item["paterno"].' '.$item["materno"].'</option>';
		}
		$campo.='</select>';
		return $campo;
    }
    
    public static function datosParaGraficar($id){
        $respuesta =  TicketsModel::datosParaGraficar($id,Tablas::tickets());
        return $respuesta;
    }

    public static function datosGraficasBarras($categoria){
        $respuesta =  TicketsModel::datosGraficasBarras($categoria,Tablas::tickets());
        return $respuesta;
    }

    public static function reabrirTicket($idTicket){ //usuario solicita se reabra el ticket
        $respuesta =  TicketsModel::reabrirTicket($idTicket,Tablas::tickets());
        return $respuesta;
    }

    public static function reabrirTicketSoporte($idTicket,$flag,$motivo){ //soporte reabrir ticket
        $respuesta =  TicketsModel::reabrirTicketSoporte($idTicket,$flag,$motivo,Tablas::tickets());
        return $respuesta;
    }

    public static function totalPorReabrir(){
        $respuesta =  TicketsModel::totalPorReabrir(Tablas::tickets());
        return $respuesta;
    }

    public static function mostrarSolucionTicket($idTicket){
        $respuesta =  TicketsModel::mostrarSolucionTicket($idTicket,Tablas::tickets());
        return $respuesta;
    }

    public static function mostrarColaTicketsReabiertos(){
        $respuesta = TicketsModel::mostrarColaTicketsReabiertos(Tablas::tickets());
        $html='';
        $colorFila= true;
        foreach($respuesta as $row => $item){
            $boton = '<div class="campoDetalleEncabezado"><button type="button" value="" class="btn btn-success mostrarDatosTicketFinalizados" data-toggle="modal" data-target="#ventanaReabrirTicket">Mostrar</button></div>';
           
            $icono='<i class="fa fa-wrench fa-2x text-green"></i>';

            if($item["area"]==2)
                $icono='<i class="fa fa-chrome fa-2x text-green"></i>';
            
            else if($item["area"]==3)
                $icono='<i class="fa fa-file-code-o fa-2x text-green"></i>';
            
            $finalizado = explode ( " ", $item['fecha_finalizado']);

            $usuario = Datos::mostrarUsuarioUnicoModel2($item["id_usuario"],Tablas::usuarios());
            $atiende='<div class="campoNombreTicketEncabezado">'.$usuario['nombre'].' '.$usuario['paterno'].' '.$usuario['materno'].'</div>';
            
            $html.='<div class="divContenedorPadre renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_ticket"].'">
                    <div class="campoIdTicketEncabezado">'.$item["id_ticket"].'</div>';
            $html.= $atiende;        
            $html.='<div class="campoAsuntoEncabezado">'.$item["asunto"].'</div>
                    <div class="campoCierreEncabezado textoMay">'.MetodosDiversos::formatearFecha($finalizado[0],true).'</div>
                    <div class="campoSituacionEncabezado">'.$icono.'</div>';
            $html.=$boton;        
            $html.= '</div>';
        }
        return $html;     
    }

    public static function mostrarListaHistorial($idTicket){
        $respuesta =  TicketsModel::mostrarListaHistorial($idTicket,Tablas::tickets_historial(),Tablas::usuarios());
        $colorFila= true;
        $numero=1;
        $html="";
        foreach($respuesta as $row => $item){
                $apertura = explode ( " ", $item['fecha_apertura']);
                $atendido = explode ( " ", $item['fecha_atendido']);
                $cierre = explode ( " ", $item['fecha_cierre']);
                $html.='<div class="divContenedorTicket renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" value="'.$item["id_registro"].'">
                                <div class="campoIdTicket">'.$numero.'</div>
                                <div class="campoNombreTicket">'.$item["nombre"].' '.$item["paterno"].' '.$item["materno"].'</div>
                                <div class="campoAsunto textoMay">'.MetodosDiversos::formatearFecha($apertura[0],true).'   '.$apertura[1].'</div>
                                <div class="campoSituacion textoMay">'.MetodosDiversos::formatearFecha($atendido[0],true).' '.$atendido[1].'</div>
                                <div class="campoCierre textoMay">'.MetodosDiversos::formatearFecha($cierre[0],true).' '.$cierre[1].'</div>
                                <div class="campoDetalle"><button type="button" value="'.$item["id_registro"].'" class="btn btn-success motivoAperturaCierreTicket" data-toggle="modal" data-target="#motivoAperturaCierreTicket">Mostrar</button></div>
                        </div>';
                $numero++;
        }
        return $html;
    } 

    public static function detallesAperturaCierre($idTicket){
        $respuesta = TicketsModel::detallesAperturaCierre($idTicket,Tablas::tickets_historial());
        return $respuesta;
    }

    public static function verificarSituacionTicket($idTicket){
        $respuesta = TicketsModel::verificarSituacionTicket($idTicket,Tablas::tickets());
        return $respuesta;
    }

    public static function comprobarRespuestaTickets(){
        $respuesta = TicketsModel::comprobarRespuestaTickets(Tablas::tickets());
        return $respuesta;
    }

    public static function resetearMensajes(){
        $respuesta = TicketsModel::resetearMensajes(Tablas::tickets());
        return $respuesta;
    }

    public static function comprobarRespuestaTicketsMensajes(){
        $respuesta = TicketsModel::comprobarRespuestaTicketsMensajes(Tablas::tickets());
        return $respuesta;
    }

    public static function TotalReabiertosPantalla($area){
        $respuesta = TicketsModel::totalPorReabrir2(Tablas::tickets(),$area);
        return $respuesta;
    } 
 
}

if(isset($_POST['totalReabiertosPantalla'])){
        require_once "../models/TicketsModel.php";
        require_once "../models/config.php";
        $respuesta1 = Tickets::TotalReabiertosPantalla(1);
        $respuesta2 = Tickets::TotalReabiertosPantalla(2);
        $respuesta3 = Tickets::TotalReabiertosPantalla(3);
        echo json_encode(array('soporte'=>$respuesta1,'giro'=>$respuesta2,'desarrollo'=>$respuesta3));
}




