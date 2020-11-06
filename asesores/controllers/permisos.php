<?php
class PermisosControllers{

	//NUEVA SOLICITUD DE PERMISOS
	public static function permisosNuevoControllers($datos){

		/*if($_SESSION['identificador'] == 168){
			return 'usuario: '.$datos['usuario'];
		}*/

		if(!preg_match('/^[0-9]{1,2}$/', $datos["permiso"]))
			return 0;
		if(!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9-.,:;\s]{5,}$/', $datos["motivo"]))
			return 2;
		if(!preg_match('/^[0-9]{1,}$/', $datos["usuario"]))
			return 4;
		if(!preg_match('/^[0-9]{2}[:]{1}[0-9]{2}[\s]{1}(AM|PM)$/', trim($datos["horarioInicio"])  ))
			return 6;
		if(!preg_match('/^[0-9]{2}[:]{1}[0-9]{2}[\s]{1}(AM|PM)$/', trim($datos["horarioFin"]) ))
			return 6;

		$fechas = preg_split("/[\/]+/",$datos['fecha']);

		$fechaHoraInicio = trim($fechas[0]);
		$fechaHoraFinal = trim($fechas[1]);

	
		if(!preg_match('/^[0-9]{2}[-]{1}[0-9]{2}[-]{1}[0-9]{4}$/', $fechaHoraInicio))
			return "El formato de la fecha de inicio no es correcto";
		if(!preg_match('/^[0-9]{2}[-]{1}[0-9]{2}[-]{1}[0-9]{4}$/', $fechaHoraFinal))
			return "El formato de la fecha de finalización no es correcto";
	
		$fechaInicio = substr($fechaHoraInicio,6,4).'-'.substr($fechaHoraInicio,3,2).'-'.substr($fechaHoraInicio,0,2);
		$fechaFin = substr($fechaHoraFinal,6,4).'-'.substr($fechaHoraFinal,3,2).'-'.substr($fechaHoraFinal,0,2);

		$horaInicio = $datos["horarioInicio"];
		$horaFin = $datos["horarioFin"];

		$formatoInicio24 = substr($horaInicio,6,2);
		$formatoFin24 = substr($horaFin,6,2);
	

		if($formatoInicio24 == 'PM'){
			$minutos = $horaInicio; 
			$horaInicio = 	((int)($horaInicio));
			$horaInicio = $horaInicio !== 12 ? ($horaInicio + 12) .':'.substr($minutos,3,2) : $horaInicio .':'.substr($minutos,3,2);
			if(strtotime($horaInicio) > strtotime("18:59")){
				return 'Horario fuera de rango';
			}
		}
		else{
			$horaInicio = substr($horaInicio,0,5);
			//if(strtotime($horaInicio) < strtotime("09:00") || strtotime($horaInicio) > strtotime("11:59"))
			if(strtotime($horaInicio) < strtotime("07:00") || strtotime($horaInicio) > strtotime("11:59")){
				return 'Horario fuera de rango';
			}
		}


		if($formatoFin24 == 'PM'){
			$minutos = $horaFin;
			$horaFin = 	((int)($horaFin));
			$horaFin = $horaFin !== 12 ? ($horaFin + 12) .':'.substr($minutos,3,2) : $horaFin .':'.substr($minutos,3,2);
			if(strtotime($horaFin) > strtotime("19:00")){
				return 'Horario fuera de rango';
			}
		}
		else{
			$horaFin = substr($horaFin,0,5);
			//if(strtotime($horaFin) < strtotime("09:01") || strtotime($horaFin) > strtotime("11:59")){
			if(strtotime($horaFin) < strtotime("07:01") || strtotime($horaFin) > strtotime("11:59")){
				return 'Horario fuera de rango';
			}
		}


		if( strtotime($horaFin) <= strtotime($horaInicio))
			return 'El horario de término no puede ser menor o igual al horario de inicio';

		$datos['fechaInicial'] = $fechaInicio;
		$datos['fechaFinal'] = $fechaFin;
		$datos['horaInicial'] = $horaInicio;
		$datos['horaFinal'] = $horaFin;
		

		if($datos['extemporaneo']==="undefined"){
			if( date($datos['fechaInicial']) < date("Y-m-d") || date($datos['fechaFinal']) < date("Y-m-d")) 
				return 'Tu permiso tiene una fecha anterior al día de hoy, si deseas continuar selecciona la opción extemporáneo';
		}
		
		if( date($datos['fechaFinal']) < date($datos['fechaInicial']))
			return 'La fecha inicial de tu permiso no puede ser mayor a la fecha final';
	
		if(MetodosDiversos::saberQueDiaEs($datos['fechaInicial']) === 'Domingo')
			return 'No se puede solicitar un permiso que inicie en día domingo';

		if(($datos["imagenNombre"]) != NULL){
                $sizeMax = 2; // en MB
				if($datos["imagenTamano"] > $sizeMax * 1024 * 1024)
					return 'El archivo tiene un tamaño mayor al permitido, el peso máximo es de 2 MB.';
                        
                /*$extensionImagen = explode("/",$datos["imagenTipo"]);//image/jpg o image/png
				$extensionImagen = $extensionImagen[1];*/
				
				$info = new SplFileInfo($datos["imagenNombre"]);
				$extensionImagen = $info->getExtension();
                if($extensionImagen === "jpeg")
						$extensionImagen = "jpg";
						
                if($extensionImagen === 'jpg' || $extensionImagen === 'png'){
                        $aleatorio = mt_rand(100,99999999);
                        $hoy = date("YmdHis"); 
                        $nombreArchivo = $_SESSION['identificador'].'_'.$aleatorio.$hoy.'.'.$extensionImagen;
                  
                        $ruta = "../intranet/documentos-solicitudes/".$nombreArchivo;
                       
                        if($extensionImagen == "jpg")
                                $origen = imagecreatefromjpeg($datos["imagenTemporal"]);
                        else 
                                $origen = imagecreatefrompng($datos["imagenTemporal"]);
                                                      
                        if($extensionImagen== "jpg")
                                imagejpeg($origen, $ruta);
                          
                        else if($extensionImagen == "png")
                                imagepng($origen, $ruta);

                        imagedestroy($origen);//liberar memoria
						$datos["imagenNombre"]=$nombreArchivo;
				}

				else if($extensionImagen === 'pdf'){
						$aleatorio = mt_rand(100,99999999);
						$hoy = date("YmdHis"); 
						$nombreArchivo = $_SESSION['identificador'].'_'.$aleatorio.$hoy.'.'.$extensionImagen;
						$ruta = "../intranet/documentos-solicitudes/".$nombreArchivo;
						move_uploaded_file($datos["imagenTemporal"], $ruta);
                        $datos["imagenNombre"]=$nombreArchivo;
				}
				else
					return 'Sólo se permiten archivos: .jpg, .jpeg, .png y .pdf';     
		}
			
		$respuesta = PermisosModels::nuevoPermisoModels($datos,Tablas::permisos());
		echo $respuesta;
	}

	//NUEVA SOLICITUD DE VACACIONES
	public static function vacacionesNuevoControllers($datos){
		$fechas = preg_split("/[\/]+/",$datos['fecha']);
		$fechaHoraInicio = $fechas[0];
		$fechaHoraFinal = trim($fechas[1]);
		$datos['fechaInicial'] = substr($fechaHoraInicio,6,4).'-'.substr($fechaHoraInicio,3,2).'-'.substr($fechaHoraInicio,0,2);
		$datos['fechaFinal'] = substr($fechaHoraFinal,6,4).'-'.substr($fechaHoraFinal,3,2).'-'.substr($fechaHoraFinal,0,2);

		$vacacionesDisponibles = PermisosModels::vacacionesDisponibles($datos["usuario"],Tablas::usuarios());
		$vacacionesSolicitadas = MetodosDiversos::calcularDiasHabiles($datos['fechaInicial'],$datos['fechaFinal']);
		$datos['conteo'] = intval($vacacionesDisponibles - $vacacionesSolicitadas);

		if( date($datos['fechaInicial']) <= date('2019-11-18')  &&  date($datos['fechaFinal']) >= date('2019-11-18') )
			$datos['conteo'] += 1;

		if( date($datos['fechaInicial']) <= date('2019-12-25')  &&  date($datos['fechaFinal']) >= date('2019-12-25') )
			$datos['conteo'] += 1;

		if( date($datos['fechaInicial']) <= date('2020-01-01')  &&  date($datos['fechaFinal']) >= date('2020-01-01') )
			$datos['conteo'] += 1;

		if( date($datos['fechaInicial']) <= date('2020-02-03')  &&  date($datos['fechaFinal']) >= date('2020-02-03') )
			$datos['conteo'] += 1;

			if( date($datos['fechaInicial']) <= date('2020-03-16')  &&  date($datos['fechaFinal']) >= date('2020-03-16') )
			$datos['conteo'] += 1;

		if($datos['extemporaneo']==="undefined"){
			if( date($datos['fechaInicial']) < date("Y-m-d") || date($datos['fechaFinal']) < date("Y-m-d")) 
				return 'Tu permiso tiene una fecha anterior al día de hoy, si deseas continuar selecciona la opción extemporáneo';
			if( $datos['conteo'] < 0)
				return 'No puedes soliciar más días de los que tienes disponibles, actualmente cuentas con '.$vacacionesDisponibles.' días de vacaciones, si deseas continuar selecciona la opción extemporáneo';
		}

		if(date($datos['fechaFinal']) < date($datos['fechaInicial']))
			return 'La fecha inicial de tu permiso no puede ser mayor a la fecha final';

		if(MetodosDiversos::saberQueDiaEs($datos['fechaInicial']) === 'Domingo')
			return 'No se puede solicitar un permiso que inicie en día domingo';

		$respuesta = PermisosModels::nuevoVacacionesModels($datos,Tablas::permisos());
		return $respuesta;
	}

	//NUEVA SOLICITUD DE CAMBIO DE GUARDIA
	public static function guardiasNuevoControllers($datos){
		if(!preg_match('/^[0-9]{1,}$/', $datos["usuarioSecundario"]))
			return 5;
		
		/*$fechas = preg_split("/[\/]+/",$datos['fecha']);
		$fechaHoraInicio = $fechas[0];
		$fechaHoraFinal = trim($fechas[1]);*/
		
		/*$datos['fechaInicial'] = substr($fechaHoraInicio,6,4).'-'.substr($fechaHoraInicio,3,2).'-'.substr($fechaHoraInicio,0,2);
		$datos['fechaFinal'] = substr($fechaHoraFinal,6,4).'-'.substr($fechaHoraFinal,3,2).'-'.substr($fechaHoraFinal,0,2);*/
	
		/*if(date($datos['fechaInicial']) < date("Y-m-d")) 
			return 'La fecha inicial no puede ser anterior a la fecha actual';
		if(date($datos['fechaFinal']) < date("Y-m-d"))
			return 'La fecha final no puede ser anterior a la fecha actual';
		if( date($datos['fechaFinal']) < date($datos['fechaInicial']))
			return 'La fecha inicial no puede ser mayor a la fecha final';*/

		/*if(MetodosDiversos::saberQueDiaEs($datos['fechaInicial']) !== 'Sabado' || MetodosDiversos::saberQueDiaEs($datos['fechaFinal']) !== 'Sabado')*/
		if($datos['extemporaneo']==="undefined"){
			if(date($datos['fecha']) < date("Y-m-d")) 
				return 'La fecha inicial no puede ser anterior a la fecha actual, si deseas continuar selecciona la opción extemporáneo';
			if(date($datos['fecha2']) < date("Y-m-d"))
				return 'La fecha final no puede ser anterior a la fecha actual, si deseas continuar selecciona la opción extemporáneo';
		}

		
		if(MetodosDiversos::saberQueDiaEs($datos['fecha']) !== 'Sabado' || MetodosDiversos::saberQueDiaEs($datos['fecha2']) !== 'Sabado')
			return 'Los cambios de guardia, sólo se efectuan los días sabado, selecciona días validos';
			
		$respuesta = PermisosModels::verificarCambiosModels($datos["usuarioSecundario"],Tablas::permisos());//verificar que la permuta no tenga pendiente otra solicitud
		if($respuesta)
			return 7;
		
		$respuesta = PermisosModels::nuevoCambiosModels($datos,Tablas::permisos());
		return $respuesta;
	}

	//OBTENER LAS FECHAS DE SOLICITUDES PARA MOSTRARLAS EN EL CALENDARIO
	public static function actualizarCalendarioControllers($datos){
		$respuesta= PermisosModels::actualizarCalendarioModels($datos,Tablas::permisos());
		$respuesta[]['aniversario'] = PermisosModels::getAniversario($datos,Tablas::usuarios());
		echo json_encode($respuesta);
	}

	#OBTENET EL TOTAL DE SOLICITUDES QUE NO HAN SIDO VISTAS YA SE APOR PARTE DE RH O DEL JEFE
	#------------------------------------
	public static function mostrarSolicitudesPersonalAcargo($dato){
		$rh = PermisosModels::obtenerIdRecursosHumanos(Tablas::rh(),$dato);//obtengo el id de la persona encargada de RH (aprobar permisos)
		$respuesta = PermisosModels::mostrarSolicitudesPersonalAcargo($dato,$rh,Tablas::jefe(),Tablas::permisos());
		return $respuesta;
	}

	#INDICO AL USUARIO CUANDO UNA SOLICITUD HAYA SIDO AUTORIZADA O CANCELADA
	#------------------------------------
	public static function mostrarRespuestaSolicitud($dato){
		$respuesta = PermisosModels::mostrarRespuestaSolicitud($dato,Tablas::permisos());
		return $respuesta;
	}

	#INDICO SI EXISTE UNA SOLICITUD DE CAMBIO DE GUARDIA PARA UN USUARIO
	public static function mostrarCambiosDeGuardia($dato){
		$respuesta = PermisosModels::mostrarCambiosDeGuardia($dato,Tablas::permisos());
		return $respuesta;
	}
	
	#########TOTAL DE REGISTROS PARA CALCULAR LA PAGINACIÓN DEL MODULO ADMINISTRAR
	public static function totalSolicitudes2Controllers($data){ //sólo aplica para jefe y rh
		$respuesta = PermisosModels::buscarPermisosModels($data,$limite='',Tablas::permisos(),Tablas::usuarios(),Tablas::jefe());
		return count($respuesta);
	}

	public function mostrarPermisosController($idUsuario,$limit){ 
		$respuesta = PermisosModels::mostrarPermisosModels($idUsuario,$limit,Tablas::permisos());
		$colorFila= true;
		$contador = gestionUsuarios::indice($limit);
		$cadena='';
		foreach ($respuesta as $row => $item){
				
				$color='';
				$boton='';
				$icono = '<i class="fa fa-eye-slash"></i>';
				if($item['visto'])
					$icono = '<i class="fa fa-eye text-black"></i>';
				//$respuesta2 = Datos::mostrarUsuarioUnicoModel2($idUsuario,Tablas::usuarios());
				
				if($item['tipo_solicitud'] == 1){
					switch($item['tipo_permiso'] ){
						case '1':
							$color = '#605CA8';
						break;
						case '2':
							$color = '#605CA8';
						break;
						case '3':
							$color = '#605CA8';
						break;
						case '4':
							$color = '#605CA8';
						break;
						case '5':
							$color = '#605CA8';
						break;
						case '6':
							$color = '#605CA8';
						break;
						case '7':
							$color = '#00A65A';
						break;
						case '8':
							$color = '#605CA8';
						break;
						case '9':
							$color = '#DD4B39';
						break;
						case '10':
							$color = '#605CA8';
						break;
						case '11':
							$color = '#605CA8';
						break;
						case '12':
							$color = '#605CA8';
						break;
					}
				}
				else if($item['tipo_solicitud'] == 2){
					$color = '#00C0EF';
				}
				else if($item['tipo_solicitud'] == 3){
					$color = '#605CA8';
				}
			
				if($item['autorizacion_jefe'] == 0)
					$estado_jefe ='<i class="fa fa-eye-slash text-blue fa-2x"></i>';
				else if($item['autorizacion_jefe'] == 1)
					$estado_jefe ='<i class="fa fa-eye text-green text-blue fa-2x"></i>';
				else if($item['autorizacion_jefe'] == 2)
					$estado_jefe ='<i class="fa fa-check-square text-green fa-2x"></i>';
				else if($item['autorizacion_jefe'] == 3)
					$estado_jefe ='<i class="fa fa-window-close text-red fa-2x"></i>';
				
				if($item['autorizacion_rh'] == 0)
					$estado_rh ='<i class="fa fa-eye-slash text-blue fa-2x"></i>';
				else if($item['autorizacion_rh'] == 1)
					$estado_rh ='<i class="fa fa-eye text-green text-blue fa-2x"></i>';
				else if($item['autorizacion_rh'] == 2)
					$estado_rh ='<i class="fa fa-check-square text-green fa-2x"></i>';
				else if($item['autorizacion_rh'] == 3)
					$estado_rh ='<i class="fa fa-window-close text-red fa-2x"></i>';

				if($item['autorizacion_rh'] == 3 || $item['autorizacion_jefe'] == 3){
					$boton='<div class="campoOpciones1 divContenedorHijo"><button class="btn btn-danger detallesSolicitudUsuario" data-toggle="modal" data-target="#detallesSolicitudUsuario" style="text-align:left;"><span>'.$icono.'</span> Cancelada</button></div>';
					$color='rgba(0,0,0,0)';
				}					
				else if($item['autorizacion_rh'] == 2){
					$boton='<div class="campoOpciones1 divContenedorHijo"><button class="btn btn-success detallesSolicitudUsuario" data-toggle="modal" data-target="#detallesSolicitudUsuario" style="text-align:left;"><span>'.$icono.'</span> Autorizada</button></div>';
				}	
				else{
					if($item['enterado_cambio'] == 2){
						$boton='<div class="campoOpciones1 divContenedorHijo"><button class="btn btn-danger detallesSolicitudUsuario" data-toggle="modal" data-target="#detallesSolicitudUsuario" style="text-align:left;"><span>'.$icono.'</span> Rechazada</button></div>';
						$color='rgba(0,0,0,0)';
					}
					else{
						if($item['autorizacion_rh'] < 2 && $item['autorizacion_jefe'] < 2)
							$boton='<div class="campoOpciones1 divContenedorHijo"><div class="bg-yellow text-left" style="width:110px;margin-left:-4px;"><span style="line-height:35px;font-size:13px;position:relative;padding-left:4px;">Por autorizar   <i class="fa fa-square fa-lg" style="position:absolute;top:3px;right:-24px;cursor:pointer;"></i> <i class="fa fa-window-close text-red fa-2x borrarPermisoUsuario" style="position:absolute;top:-5px;right:-28px;cursor:pointer;"></i> </span></div></div>';
							//$boton='<div class="campoOpciones1 divContenedorHijo"><div class="bg-yellow color-palette"><span class="text-center" style="padding-left:4px;line-height:35px;font-size:12px;text-align:left;">Por autorizar <i class="fa fa-window-close text-red fa-2x borrarPermisoUsuario" style="position:relative;top:6px;padding-right:2px;cursor:pointer;"></i></span></div></div>';
						else
							$boton='<div class="campoOpciones1 divContenedorHijo"><div class="bg-yellow text-center" style="width:110px;"><span style="line-height:34px;">Por autorizar</span></div></div>';
						$color='#F39C12';
					}
					
				}
				
				$cadena.='<div class="renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_permiso"].'">
						<div class="campoId"><span class="max-min"><img class="botonMaxMin" src="views/img/circle-max.png" height="25" width="25"></span>'.$contador.'</div>
						<div class="campoNombre"><i class="fa fa-square" style="color:'.$color.';"></i>&nbsp;'.PermisosModels::traducirPermisos($item["tipo_solicitud"],$item["tipo_permiso"]).'</div>
						<div class="campoReferencia  textoMay">'.MetodosDiversos::formatearFecha($item['fecha_solicitud'],true).'</div>
						<div class="campoFecha textoMay">'.MetodosDiversos::formatearFecha($item['fecha_inicio'],true).'</div>
						<div class="campoJefe">'.$estado_jefe.'</div>
						<div class="campoRH">'.$estado_rh.'</div>';
						$cadena.=$boton;
				$cadena.='</div>';
				$contador++;
		}
		return $cadena;
	
	}
			
	public static function buscarUsuariosPermisosController($data,$limite=''){ //sólo aplica para jefe y rh
		$respuesta = PermisosModels::buscarPermisosModels($data,$limite,Tablas::permisos(),Tablas::usuarios(),Tablas::jefe());
		$colorFila= true;
		$contador = gestionUsuarios::indice($limite);
		$cadena='';
		foreach ($respuesta as $row => $item){
			if($item['autorizacion_jefe'] == 0)
				$estado_jefe ='<i class="fa fa-eye-slash text-blue fa-2x"></i>';
			else if($item['autorizacion_jefe'] == 1)
				$estado_jefe ='<i class="fa fa-eye text-green text-blue fa-2x"></i>';
			else if($item['autorizacion_jefe'] == 2)
				$estado_jefe ='<i class="fa fa-check-square text-green fa-2x"></i>';
			else if($item['autorizacion_jefe'] == 3)
				$estado_jefe ='<i class="fa fa-window-close text-red fa-2x"></i>';
					
			if($item['autorizacion_rh'] == 0)
				$estado_rh ='<i class="fa fa-eye-slash text-blue fa-2x"></i>';
			else if($item['autorizacion_rh'] == 1)
				$estado_rh ='<i class="fa fa-eye text-green text-blue fa-2x"></i>';
			else if($item['autorizacion_rh'] == 2)
				$estado_rh ='<i class="fa fa-check-square text-green fa-2x"></i>';
			else if($item['autorizacion_rh'] == 3)
				$estado_rh ='<i class="fa fa-window-close text-red fa-2x"></i>';
					
			$cadena.='<div class="renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_permiso"].'">
					<div class="campoId"><span class="max-min"><img class="botonMaxMin" src="views/img/circle-max.png" height="25" width="25"></span>'.$contador.'</div>
					<div class="campoNombre">'.$item["nombre"].' '.$item["paterno"].' '.$item["materno"].'</div>
					<div class="campoSucursal">'.Sucursales::traducirSucursalesModel($item["id_sucursal"],"sucursales_ae").'</div>
					<div class="campoAcceso">'.PermisosModels::traducirPermisos($item["tipo_solicitud"],$item["tipo_permiso"]).'</div>
					<div class="campoFecha textoMay">'.MetodosDiversos::formatearFecha($item['fecha_inicio'],true).'</div>
					<div class="campoJefe">'.$estado_jefe.'</div>
					<div class="campoRH">'.$estado_rh.'</div>
					<div class="campoOpciones1 divContenedorHijo"><button class="btn btn-success mostrarPermisoUsuario" data-toggle="modal" data-target="#mostrarPermisosModal">Mostrar</button></div>
				</div>';
			$contador++;
		}
		return $cadena;
	}

	public static function mostrarSolicitudesControllers($idSolicitud,$id){

		$respuesta = PermisosModels::permisoUnicoModels($idSolicitud,Tablas::permisos(),Tablas::jefe());

		$rh = PermisosModels::obtenerIdRecursosHumanos(Tablas::rh(),$id);//obtengo el id de la persona encargada de RH (aprobar permisos)
		$icono = 'campoJefe'; //me indica si debo actualizar el icono de jefe o de RH
		$status = 0;
		
		$incluyeSabado = MetodosDiversos::saberDiasSabado($respuesta['fecha_inicio'],$respuesta['fecha_fin']);//////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
		if($_SESSION['identificador'] != 168){
			if( $rh===TRUE){
				$icono = 'campoRH';
				if($respuesta['autorizacion_rh'] == 0){
					PermisosModels::solicitudVistaModels($idSolicitud,"soy_rh",Tablas::permisos());
					$status = 1;
				}
			}
	
			else{
				//$sinValor = 'SOY JEFE';
				if($respuesta['autorizacion_jefe'] == 0){
					PermisosModels::solicitudVistaModels($idSolicitud,"soy_jefe",Tablas::permisos());
					$status = 2;
				}
			}
		}
		

		$respuesta2 = Datos::mostrarUsuarioUnicoModel2($respuesta["id_usuario"],Tablas::usuarios());

		$salida='';

		$salida.='<div class="col-md-12">
						<!-- Custom Tabs -->
						<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab_1" data-toggle="tab">Solicitud</a></li>
							<li><a href="#tab_2" data-toggle="tab">Autorización</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_1">
								
								
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Nombre: </span> '.$respuesta2["nombre"].' '.$respuesta2["paterno"].' '.$respuesta2["materno"].'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Sucursal:</span> '.Sucursales::traducirSucursalesModel($respuesta2["id_sucursal"],"sucursales_ae").'
									</div>
								</div>
								<br>
		
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Departamento: </span>'.Departamentos::vistaDepartamentos2Model($respuesta2['id_departamento'],"departamentos_ae") .'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Puesto: </span>'.Departamentos::vistaPuestos2Model($respuesta2['id_puesto'],"puestos_ae") .'
									</div>
								</div>
								<hr>
								
								<div class="row">
									<div class="col-md-12">
										<span class="encabezadoDato">Tipo de solicitud: </span>'.PermisosModels::traducirPermisos($respuesta["tipo_solicitud"],$respuesta["tipo_permiso"]).'
									</div>
								</div>
								<br>

								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Fecha solicitada: </span>'.substr($respuesta['fecha_inicio'],8,2).'-'.substr($respuesta['fecha_inicio'],5,2).'-'.substr($respuesta['fecha_inicio'],0,4) .'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Fecha de finalización: </span>'.substr($respuesta['fecha_fin'],8,2).'-'.substr($respuesta['fecha_fin'],5,2).'-'.substr($respuesta['fecha_fin'],0,4) .'
									</div>
								</div>';
			if($respuesta["fecha_incorporacion"] != NULL ){
				$salida.=		'<br>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Fecha de reincorporación: </span>'.substr($respuesta['fecha_incorporacion'],8,2).'-'.substr($respuesta['fecha_incorporacion'],5,2).'-'.substr($respuesta['fecha_incorporacion'],0,4) .'
									</div>
									<div class="col-md-6">
									</div>
								</div>';
			}
			if($respuesta["tipo_solicitud"] == 1){
				$salida.=		'<br>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Hora de inicio: </span> '.self::formatoHora($respuesta['horario_inicio']) .'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Hora de fin: </span> '.self::formatoHora($respuesta['horario_fin']) .'
									</div>
								</div>
								<br>

			
								<div class="row">
									<div class="col-md-12">
										<span class="encabezadoDato">Motivo: </span>'.$respuesta['motivo'].'
									</div>
								</div>';
			}

			if($respuesta["justificante"] != NULL){
				$respuesta['justificante'] = $respuesta['justificante'] == 0 ? 'No' : 'Sí';
				$respuesta['goce_sueldo'] = $respuesta['goce_sueldo'] == 0 ? 'No' : 'Sí';
				$salida.=	'<br>
							<div class="row">
								<div class="col-md-6">
									<span class="encabezadoDato">Presento justificante: </span>'.$respuesta['justificante'].'
								</div>
								<div class="col-md-6">
								<span class="encabezadoDato">Permiso con goce de sueldo: </span>'.$respuesta['goce_sueldo'].'
								</div>
							</div>';
			}

			if($respuesta["tipo_solicitud"] == 3){
				$respuesta3 = Datos::mostrarUsuarioUnicoModel2($respuesta["id_usuario_cambio"],Tablas::usuarios());
				$salida.=		'<br>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato"> Cambia con: </span>'.$respuesta3["nombre"].' '.$respuesta3["paterno"].' '.$respuesta3["materno"].'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Puesto: </span>'.Departamentos::vistaPuestos2Model($respuesta3['id_puesto'],"puestos_ae") .'
									</div>
								</div>';
			}
	
			if( $rh===TRUE){
				$respuesta4 = Datos::mostrarUsuarioUnicoModel2($respuesta["id_jefe"],Tablas::usuarios());
				$textoRespuestaJefe='';
				if($respuesta['autorizacion_jefe']==0)
					$textoRespuestaJefe='Pendiente (No vista)';
				else if($respuesta['autorizacion_jefe']==1)
					$textoRespuestaJefe='Pendiente (Vista)';
				else if($respuesta['autorizacion_jefe']==2)
					$textoRespuestaJefe='Sí';
				else if($respuesta['autorizacion_jefe']==3)
					$textoRespuestaJefe='No';
				$salida.=		'<hr>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato"> Jefe inmediato: </span>'.$respuesta4["nombre"].' '.$respuesta4["paterno"].' '.$respuesta4["materno"].'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Autorizó: </span>'.$textoRespuestaJefe.'
									</div>
								</div>';
			}
			$salida.=			'<hr>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Fecha de registro: </span>'.substr($respuesta['fecha_solicitud'],8,2).'-'.substr($respuesta['fecha_solicitud'],5,2).'-'.substr($respuesta['fecha_solicitud'],0,4) .'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Hora de registro: </span>'.self::formatoHora($respuesta['hora_solicitud']) .'
									</div>
								</div>
							</div>
							<!-- /.tab-pane -->';


			if( $respuesta['autorizacion_rh'] < 2 ){ // si RH no ha autorizado ni denegado la solicitud
				if( $rh===TRUE){//si es RH
					if( $respuesta['autorizacion_jefe'] == 3){
						$salida .= '<div class="tab-pane" id="tab_2">
										<div class="row">
											<div class="col-md-12">
												<p class="callout callout-danger">La solicitud no fue autorizada</p>
											</div>
										</div>
										<div class="row">
										<div class="col-md-12">
											<span class="encabezadoDato">Motivo de no autorización: </span>'.$respuesta['motivo_denegacion'].'
										</div>
									</div>
									</div>
									<!-- /.tab-pane -->';
					}
					else{
						$salida.='<div class="tab-pane" id="tab_2">
									<form method="POST" style="margin-top: 2%;" id="formularioSolicitudAutorizacion">
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
													<p class="callout callout-default">
														<label>Opinión del jefe: </label>
														<br>
														<span class="textoMay">'.$respuesta['comentario_jefe'].'</span>
													</p>
												</div>
											</div>';
						if($respuesta['extemporaneo']== 1){
							$salida.=       '<div class="row">
												<div class="col-md-12">
												<p class="callout callout-warning">Esta solicitud es extemporánea, se solicita con fecha anterior a la del día en que se registro.</p>
												</div>
											</div>';
						}
						if($respuesta['extemporaneo']== 2){
								$vacacionesDisponibles = PermisosModels::vacacionesDisponibles($respuesta["id_usuario"],Tablas::usuarios());
								$vacacionesSolicitadas = MetodosDiversos::calcularDiasHabiles($respuesta['fecha_inicio'],$respuesta['fecha_fin']);
							$salida.=       '<div class="row">
												<div class="col-md-12">
												<p class="callout callout-warning">Esta solicitud es extemporánea, el empleado solicita '. $vacacionesSolicitadas.' día(s) de vacaciones, pero tiene '. $vacacionesDisponibles .' disponibles .</p>
												</div>
											</div>';
						}
						 if($respuesta['imagen']!=NULL){
							$salida.=       '<div class="row">
												<div class="col-md-12">
													<span class="encabezadoDato">Archivo anexo: </span>
													<div class="btn btn-default btn-file">
														<a href="intranet/documentos-solicitudes/'.$respuesta['imagen'].'" download="documento-'.date("YmdHis").'"><i class="fa fa-download text-black"></i> Archivo</a>
													</div>
												</div>
											</div>';
						}
						if($incluyeSabado AND $respuesta["tipo_solicitud"] == 2){
							$salida.=		'<div class="row">
												<div class="col-md-8">
													<p class="callout callout-success">El periodo de vacaciones incluye '.$incluyeSabado.' sábado(s), ¿se descontará de los días por disfrutar? <i class="fa fa-question-circle-o fa-2x"></i></p>
												</div>
												<div class="col-md-4">
													<label>Sabados por descontar: </label>
													<select class="form-control textoMay" name="contarDiaSabado">
														<option value="0">NINGUNO</option>';
														
														for($i=1;$i<=$incluyeSabado;$i++)
															$salida.='<option value="'.$i.'">'.$i.'</option>';
														
							$salida.=				'</select>
												</div>
											</div>';
						}
						$salida.=			'<br>
											<div class="row">
												<div class="col-md-6">
													<label for="">Autorizar: </label> <i class="fa fa-check-circle text-green"></i>
													<select class="form-control textoMay" name="autorizarSolicitud" id="autorizarSolicitud" required>
														<option value=""></option>
														<option value="1">SÍ</option>
														<option value="0">NO</option>
													</select>
												</div>
												<div class="col-md-6" id="ocultarSueldo">
													<label for="">Goce de sueldo: </label> <i class="fa fa-check-circle text-green"></i>
													<select class="form-control textoMay" name="autorizarGoceSueldo" required>
														<option value=""></option>
														<option value="1">SÍ</option>
														<option value="0">NO</option>
													</select>
												</div>
											</div>
											<br>
											<span id="targetDenegarSolicitud"></span>
											<div class="row">
												<div class="col-md-6" id="ocultarJustificante">
													<label for="">Presentó justificante: </label> <i class="fa fa-check-circle text-green"></i>
													<select class="form-control textoMay" name="presentarJustificante" required>
														<option value=""></option>
														<option value="1">SÍ</option>
														<option value="0">NO</option>
													</select>
												</div>
												<div class="col-md-6" id="ocultarReincorporacion">
													<label for="">Fecha de reincorporación: </label>
													<input  class="form-control" type="date" name="fechaReincorporacion">
												</div>
											</div>
										</div>
								
										<hr>
										<div class="row">
											<div class="col-md-12" id="ocultarMensaje">
												<p class="callout callout-success">¿Deseas modificar datos de la solicitud? <i class="fa fa-question-circle-o fa-2x"></i></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6" id="ocultarFechaSolicitada">
												<label for="">Fecha solicitada: </label>
												<input type="date" class="select-style" name="actualizarFechaSolicitada" value="'.$respuesta['fecha_inicio'].'">
											</div>
											<div class="col-md-6" id="ocultarFechaFin">
												<label for="">Fecha finalización: </label>
												<input type="date" class="select-style" name="actualizarFechaFin" value="'.$respuesta['fecha_fin'].'">
											</div>
										</div>
										
										<hr>
										<div class="estilos-centrar">
											<button type="submit" id="guardarFormularioAutorizacionSolicitud" class="btn btn-success">Aceptar</button>
										</div>
									</form>
								</div>
									<!-- /.tab-pane -->';
					}
					
				}
				else{ //si es JEFE
						if($respuesta['autorizacion_jefe'] < 2 ){// si JEFE no ha autorizado ni denegado la solicitud
							$salida.='<div class="tab-pane" id="tab_2">';
							if($respuesta['imagen']!=NULL){
								$salida.=       '<div class="row">
													<div class="col-md-12">
														<span class="encabezadoDato">Archivo anexo: </span>
														<div class="btn btn-default btn-file">
															<a href="intranet/documentos-solicitudes/'.$respuesta['imagen'].'" download="documento-'.date("YmdHis").'"><i class="fa fa-download text-black"></i> Archivo</a>
														</div>
													</div>
												</div>';
							}
							if($respuesta['extemporaneo']== 1){
								$salida.=       '<div class="row">
													<div class="col-md-12">
													<p class="callout callout-warning">Esta solicitud es extemporánea, se solicita con fecha anterior a la del día en que se registro.</p>
													</div>
												</div>';
							}
							if($respuesta['extemporaneo']== 2){
									$vacacionesDisponibles = PermisosModels::vacacionesDisponibles($respuesta["id_usuario"],Tablas::usuarios());
									$vacacionesSolicitadas = MetodosDiversos::calcularDiasHabiles($respuesta['fecha_inicio'],$respuesta['fecha_fin']);
								$salida.=       '<div class="row">
													<div class="col-md-12">
													<p class="callout callout-warning">Esta solicitud es extemporánea, el empleado solicita '. $vacacionesSolicitadas.' día(s) de vacaciones, pero tiene '. $vacacionesDisponibles .' disponibles .</p>
													</div>
												</div>';
							}
							$salida.=		'<form method="POST" style="margin-top: 2%;" id="formularioSolicitudAutorizacionJefe">
												<div class="form-group">
													<div class="row">
														<div class="col-md-12">
															<label for="">Autorizar: </label> <i class="fa fa-check-circle text-green"></i>
															<select class="form-control textoMay" name="autorizarSolicitud2" id="autorizarSolicitud2" required>
																<option value=""></option>
																<option value="1">SÍ</option>
																<option value="0">NO</option>
															</select>
														</div>
													</div>
												</div>

												<span id="targetDenegarSolicitud2">';
						if($incluyeSabado AND $respuesta["tipo_solicitud"] == 2){
							$salida.=				'<div class="row">
														<div class="col-md-12">
															<p class="callout callout-success">El periodo de vacaciones incluye '.$incluyeSabado.' sábado(s), podrías indicar si también se restara del total de días por disfrutar. <i class="fa fa-question-circle-o fa-2x"></i></p>
														</div>
													</div>';
						}
						
							$salida.=			'</span>
												<hr>
												<div class="estilos-centrar">
													<button type="submit" id="guardarFormularioAutorizacionSolicitudJefe" class="btn btn-success">Aceptar</button>
												</div>
											</form>
										</div>
										<!-- /.tab-pane -->';
							}
						else if($respuesta['autorizacion_jefe'] == 2 ){ //si JEFE autoriza la solicitud
							$salida .='<div class="tab-pane" id="tab_2">
											<div class="row">
												<div class="col-md-12">
													<p class="callout callout-success">La solicitud fue autorizada, falta la autorización del departamento de Recursos Humanos.</p>
												</div>
											</div>
									  </div>';
						}
						else if( $respuesta['autorizacion_jefe'] == 3 ){//si JEFE cancela la solicitud
							$salida .=		'<div class="tab-pane" id="tab_2">
												<div class="row">
													<div class="col-md-12">
														<p class="callout callout-danger">La solicitud no fue autorizada.</p>
													</div>
												</div>
												<div class="row">
												<div class="col-md-12">
													<span class="encabezadoDato">Motivo de no autorización: </span>'.$respuesta['motivo_denegacion'].'
												</div>
											</div>
											</div>
											<!-- /.tab-pane -->';
							}
									
					}
			}
			else if( $respuesta['autorizacion_rh'] == 2 ){//si RH autorizo
			$salida .=		'<div class="tab-pane" id="tab_2">
								<!--<form method="POST" style="margin-top: 2%;" id="formularioFormatoSolicitud">-->
									<div class="row">
										<div class="col-md-12">
											<p class="callout callout-success">La solicitud ya fue autorizada ¿Deseas imprimirla? <i class="fa fa-question-circle-o fa-2x"></i></p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 estilos-centrar">
											<button type="submit" id="'.$idSolicitud.'" class="btn btn-default formatoPdf">Imprimir formato</button>
											<!--<form action="imprimirSolicitud" method="post" target=_blank> <button type="submit" name="idSolicitud" value="'.$idSolicitud.'" class="btn btn-default">Imprimir solicitud</button></form>-->
										</div>		
									</div>
								<!--</form>-->
							</div>
							<!-- /.tab-pane -->';
			}

			else if( $respuesta['autorizacion_rh'] == 3 ){ //si RH no autorizo
				$salida .=		'<div class="tab-pane" id="tab_2">
									<div class="row">
										<div class="col-md-12">
											<p class="callout callout-danger">La solicitud no fue autorizada.</p>
										</div>
									</div>
									<div class="row">
									<div class="col-md-12">
										<span class="encabezadoDato">Motivo de no autorización: </span>'.$respuesta['motivo_denegacion'].'
									</div>
								</div>
								</div>
								<!-- /.tab-pane -->';
				}
	
			$salida .= '</div>
						<!-- /.tab-content -->
						</div>
						<!-- nav-tabs-custom -->
					</div>';

		$informacion = array("datos"=>$salida,"tipoSolicitud"=>$respuesta['tipo_solicitud'],"usuarioSolicitante"=>$respuesta['id_usuario'],"imagen"=>$respuesta2["imagen"],"status"=>$status,"status2"=>$rh,"campoAcualizar"=>$idSolicitud,"fechaInicio"=>$respuesta['fecha_inicio'],"fechaFin"=>$respuesta['fecha_fin'],"icono"=>$icono);
		echo json_encode($informacion);
	}

	public static function formatoHora($hora){
		$hours = intval($hora);
		$prefijo =" AM";
		if($hours>=12){
			if($hours != 12)
				$hours = $hours-12;
			$prefijo=" PM";
		}
		$sHours = $hours;
		if($hours<10) 
			$sHours = "0" . $sHours;
		return ($sHours . ":" . substr($hora,3) . $prefijo);
	}

	//ACTUALIZAR FORMULARIO (AUTORIZAR.CANCELAR) RH
	public static function actualizarFormularioSolicitudControllers($datos){

		if(!preg_match('/^[0-9]{1,}$/', $datos["idSolicitud"]))
			return 100;
		if(!preg_match('/^[0-9]{1}$/', $datos["autorizar"]))
			return 2;

		if($datos["autorizar"]){
			if(!preg_match('/^[0-9]{1}$/', $datos["sueldo"]))
				return 3;
			if(!preg_match('/^[0-9]{1}$/', $datos["justificante"]))
				return 4;
			if(!empty($datos['fechaReincorporacion'])){
				if(!preg_match('/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/', $datos['fechaReincorporacion']))
					return 5;
				if( date($datos['fechaReincorporacion'])  < date($datos['fechaFinalizacion'])  )
					return 'La fecha de reincorporación no puede ser anterior a la fecha de finalización';
			}
			if(!preg_match('/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/', $datos['fechaSolicitada']))
				return 6;
			if(!preg_match('/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/', $datos['fechaFinalizacion']))
				return 7;
			/*if(date($datos['fechaSolicitada']) < date("Y-m-d")) 
				return 'La fecha solicitada no puede ser anterior a la fecha actual';
			if(date($datos['fechaFinalizacion']) < date("Y-m-d"))
				return 'La fecha de finalización no puede ser anterior a la fecha actual';
			if( date($datos['fechaFinalizacion']) < date($datos['fechaSolicitada']))
				return 'La fecha de finalizacón no puede ser menor a la fecha inicial';*/
			
			if($datos['tipoDePermiso']==2){
				$vacacionesDisponibles = PermisosModels::vacacionesDisponibles($datos["idUsuario"],Tablas::usuarios());
				$vacacionesSolicitadas = MetodosDiversos::calcularDiasHabiles($datos['fechaSolicitada'],$datos['fechaFinalizacion']);
				/*if( intval($vacacionesDisponibles - $vacacionesSolicitadas) < 0)
					return 'No se pueden asignar más días de los que se tienen disponibles. '.intval($vacacionesDisponibles - $vacacionesSolicitadas) ;*/
				$datos['dias']=$vacacionesSolicitadas;
			}
			
		}
		else {
			if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ(),.:;\s]{2,}$/', $datos["negacion"]))
				return 8;
		}
		
		$respuesta= PermisosModels::actualizarFormularioSolicitudModels($datos,Tablas::permisos());
		return $respuesta;
	}

	//ACTUALIZAR FORMULARIO (AUTORIZAR.CANCELAR) JEFE
	public static function actualizarFormularioSolicitudControllers2($datos){

		if(!preg_match('/^[0-9]{1,}$/', $datos["idSolicitud"]))
			return 100;
		if(!preg_match('/^[0-9]{1}$/', $datos["autorizar"]))
			return 2;
		if(!$datos["autorizar"]){
			if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ(),.:;\s]{2,}$/', $datos["negacion"]))
				return 8;
		}
		if(!empty($datos["comentarioJefe"])){
			if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ(),.:;\s]{2,}$/', $datos["comentarioJefe"]))
				return 100;
		}
		$respuesta= PermisosModels::actualizarFormularioSolicitudModels2($datos,Tablas::permisos());
		return $respuesta;
	}



	public static function detallesSolicitudUsuarioControllers($idPermiso){
		$respuesta= PermisosModels::permisoUnicoModels($idPermiso,Tablas::permisos(),Tablas::jefe());
		$icono = 0;
		if($respuesta['id_usuario'] == $_SESSION["identificador"]){
			if( $respuesta['visto'] == 0 ){
				PermisosModels::solicitudVistaConfirmacion($idPermiso,'principal',Tablas::permisos());//el usuario ya leyo la respuesta
				$icono = 1;
			}
		}

		else{
			if( $respuesta['enterado_cambio'] == 3 ){
				PermisosModels::solicitudVistaConfirmacion($idPermiso,'permuta',Tablas::permisos());//la permuta ya leyo la respuesta
				$icono = 1;
			}
		}
		
		$cadenaTxt=$imagenIcon=$salida=$motivoCancelacion ='';

		if($respuesta['autorizacion_jefe'] == 3 || $respuesta['autorizacion_rh'] == 3){
			$cadenaTxt ='<b>Situación:</b> CANCELADA';
			$imagenIcon = '<span class="info-box-icon-mini bg-red"><i class="fa fa-window-close"></i></span>';
			$motivoCancelacion ='<hr>
								<div class="row">
									<div class="col-md-12">
										<span class="encabezadoDato">Motivo de no autorización: </span>'.$respuesta['motivo_denegacion'].'
									</div>
								</div>';
		}
		else if($respuesta['enterado_cambio']== 2){
			$cadenaTxt ='<b>Situación:</b>	RECHAZADA';
			$imagenIcon = '<span class="info-box-icon-mini bg-red"><i class="fa fa-window-close"></i></span>';
		}
		else{
			$cadenaTxt ='<b>Situación:</b> AUTORIZADA';
			$imagenIcon = '<span class="info-box-icon-mini bg-green"><i class="fa fa-check-square"></i></span>';
		}
		$respuesta['justificante'] = $respuesta['justificante'] == 0 ? 'No' : 'Sí';
		$respuesta['goce_sueldo'] = $respuesta['goce_sueldo'] == 0 ? 'No' : 'Sí';
		$salida.='
						<div class="info-box-mini">
								'. $imagenIcon .'
							<div class="info-box-content-mini">
								<span> '. $cadenaTxt .' </span>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<span class="encabezadoDato">Tipo de solicitud: </span>'.PermisosModels::traducirPermisos($respuesta["tipo_solicitud"],$respuesta["tipo_permiso"]).'
							</div>
						</div>
						<br>

						<div class="row">
							<div class="col-md-6">
										<span class="encabezadoDato">Fecha solicitada: </span>'.substr($respuesta['fecha_inicio'],8,2).'-'.substr($respuesta['fecha_inicio'],5,2).'-'.substr($respuesta['fecha_inicio'],0,4)  .'
							</div>
							<div class="col-md-6">
								<span class="encabezadoDato">Fecha de finalización: </span>'.substr($respuesta['fecha_fin'],8,2).'-'.substr($respuesta['fecha_fin'],5,2).'-'.substr($respuesta['fecha_fin'],0,4) .'
							</div>
						</div>';

			if($respuesta["tipo_solicitud"] == 1){
				$salida.=		'<br>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Hora de inicio: </span> '.self::formatoHora($respuesta['horario_inicio']) .'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Hora de fin: </span> '.self::formatoHora($respuesta['horario_fin']) .'
									</div>
								</div>';
				if($respuesta["fecha_incorporacion"] != NULL ){
				$salida.=		'<br>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Fecha de reincorporación: </span>'.substr($respuesta['fecha_incorporacion'],8,2).'-'.substr($respuesta['fecha_incorporacion'],5,2).'-'.substr($respuesta['fecha_incorporacion'],0,4) .'
									</div>
									<div class="col-md-6">
									</div>
								</div>';
			}
				$salida.=		'<br>
								<div class="row">
									<div class="col-md-12">
										<span class="encabezadoDato">Motivo: </span>'.$respuesta['motivo'].'
									</div>
								</div>';
			}

				$salida.=		'<br>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Presento justificante: </span>'.$respuesta['justificante'].'
									</div>
									<div class="col-md-6">
									<span class="encabezadoDato">Permiso con goce de sueldo: </span>'.$respuesta['goce_sueldo'].'
									</div>
								</div>';

			if($respuesta["tipo_solicitud"] == 3){
				if($respuesta["id_usuario_cambio"] == $_SESSION["identificador"]){
					$respuesta["id_usuario_cambio"] = $respuesta["id_usuario"];
				}
				$respuesta3 = Datos::mostrarUsuarioUnicoModel2($respuesta["id_usuario_cambio"],Tablas::usuarios());
				$salida.=		'<br>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato"> Cambia con: </span>'.$respuesta3["nombre"].' '.$respuesta3["paterno"].' '.$respuesta3["materno"].'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Puesto: </span>'.Departamentos::vistaPuestos2Model($respuesta3['id_puesto'],"puestos_ae") .'
									</div>
								</div>';
			}
			$rh = PermisosModels::obtenerIdRecursosHumanos2(Tablas::rh());
			$respuesta4 = Datos::mostrarUsuarioUnicoModel2($respuesta["id_jefe"],Tablas::usuarios());
			if($respuesta['autorizacion_jefe']==0)
				$respuesta['autorizacion_jefe']='Pendiente (No vista)';
			else if($respuesta['autorizacion_jefe']==1)
				$respuesta['autorizacion_jefe']='Pendiente (Vista)';
			else if($respuesta['autorizacion_jefe']==2)
				$respuesta['autorizacion_jefe']='Sí';
			else if($respuesta['autorizacion_jefe']==3)
				$respuesta['autorizacion_jefe']='No';
			$salida.=		'<hr>
							<div class="row">
								<div class="col-md-6">
									<span class="encabezadoDato"> Jefe inmediato: </span>'.$respuesta4["nombre"].' '.$respuesta4["paterno"].' '.$respuesta4["materno"].'
								</div>
								<div class="col-md-6">
									<span class="encabezadoDato">Autorizó: </span>'.$respuesta['autorizacion_jefe'].'
								</div>
							</div>
							<br>';

			$respuesta5 = Datos::mostrarUsuarioUnicoModel2($rh,Tablas::usuarios());
			$flagFormato = false;
			if($respuesta['autorizacion_rh']==0)
				$respuesta['autorizacion_rh']='Pendiente (No vista)';
			else if($respuesta['autorizacion_rh']==1)
				$respuesta['autorizacion_rh']='Pendiente (Vista)';
			else if($respuesta['autorizacion_rh']==2){
				$respuesta['autorizacion_rh']='Sí';
				$flagFormato = true;
			}	
			else if($respuesta['autorizacion_rh']==3)
				$respuesta['autorizacion_rh']='No';
			$salida.=		'<div class="row">
								<div class="col-md-6">
									<span class="encabezadoDato"> Recursos Humanos: </span>'.$respuesta5["nombre"].' '.$respuesta5["paterno"].' '.$respuesta5["materno"].'
								</div>
								<div class="col-md-6">
									<span class="encabezadoDato">Autorizó: </span>'.$respuesta['autorizacion_rh'].'
								</div>
							</div>
							'.$motivoCancelacion.'
							';
			if($respuesta["tipo_solicitud"] == 2 AND $flagFormato){
				$salida.=       '<div class="row">
									<div class="col-md-12 estilos-centrar">
										<button type="button" id="'.$idPermiso.'" class="btn btn-success formatoPdf">Imprimir formato</button>
									</div>		
								</div>';
				}

			$salida.=			'<hr>
								<div class="row">
									<div class="col-md-6">
										<span class="encabezadoDato">Fecha de registro: </span>'.substr($respuesta['fecha_solicitud'],8,2).'-'.substr($respuesta['fecha_solicitud'],5,2).'-'.substr($respuesta['fecha_solicitud'],0,4) .'
									</div>
									<div class="col-md-6">
										<span class="encabezadoDato">Hora de registro: </span>'.self::formatoHora($respuesta['hora_solicitud']) .'
									</div>
								</div>
			</div>';
			$informacion = array("datos"=>$salida,"icono"=>$icono);
			echo json_encode($informacion);
	
	
	}

	/***************************************************MARCADORES USUARIO*********************************/
	public static function marcadoresPermisosUsuario($usuario,$tipo){
		$respuesta = PermisosModels::marcadoresPermisosUsuario($usuario,$tipo,Tablas::permisos());
		return $respuesta;
	}

	/************PERMITE SABER EL TOTAL DE REGISTROS BAJO CRITERIOS DE: VISTO,NO VISTO, AUTORIZADO,ETC. (UNICAMENTE PARA LOS MARCADORES) PARA JEFES Y RH ***************/
	public static function totalSolicitudesControllers($idUsuario,$tipo){ 
		$respuesta = PermisosModels::totalSolicitudesModels($idUsuario,$tipo,Tablas::permisos(),Tablas::jefe());
		return $respuesta;
	}

	#RESPUESTA AL CAMBIO DE GUARDIA
	public static function reponderCambioGuardia($idPermiso,$respuesta){
		if(!preg_match('/^[1-2]{1}$/', $respuesta))
			return array('error'=>true,'tipo'=>'error','mensaje'=>'Ocurrio un error intentelo nuevamente','mensaje2'=>'Formulario corrupto');
		$respuesta = PermisosModels::reponderCambioGuardia($idPermiso,$respuesta,Tablas::permisos());
		return $respuesta;
	}

	#MUESTRA EL TOTAL DE VACACIONES DISPONIBLES
	##################################################
	public static function vacacionesDisponibles($empleado,$anio){
		$disponibles = PermisosModels::vacacionesDisponibles($empleado,Tablas::usuarios());
		$anioActual = date('Y');
		if($anio < $anioActual){
			return '';
			//$disfrutadasPorAnio = PermisosModels::vacacionesDisfrutadas($empleado,Tablas::bitacora(), $anioActual);
			//$agregadasPorAnio = PermisosModels::agregadasPorAnio($empleado,Tablas::bitacora(),$anioActual);
			//$disponibles = intval($disponibles) + intval($disfrutadasPorAnio) - intval($agregadasPorAnio);

		}
		return $disponibles;
	}

	#MUESTRA EL TOTAL DE VACACIONES DISFRUTADAS DURANTE EL AÑO EN CURSO
	##################################################
	public static function vacacionesDisfrutadas($empleado){
		$respuesta = PermisosModels::vacacionesDisfrutadas($empleado,Tablas::bitacora());
		return $respuesta;
	}

	public static function borrarPermisoUsuario($id){
		$respuesta = PermisosModels::borrarPermisoUsuario($id,Tablas::permisos());
		return $respuesta;
	}

	public static function cargarPermisos($anio,$usuario){
		$usuario = $usuario === NULL ? $_SESSION['identificador'] : $usuario;
		$disfrutadas = PermisosModels::vacacionesDisfrutadas($usuario,Tablas::bitacora(),$anio);
		$bonos = PermisosModels::marcadoresPermisosUsuario($usuario,2,Tablas::permisos(),$anio);
		$permisos = PermisosModels::marcadoresPermisosUsuario($usuario,4,Tablas::permisos(),$anio);
		$faltas = PermisosModels::marcadoresPermisosUsuario($usuario,3,Tablas::permisos(),$anio);
		$porAutorizar = PermisosModels::marcadoresPermisosUsuario($usuario,0,Tablas::permisos(),$anio);
		$disponibles = self::vacacionesDisponibles($usuario,$anio);
		return array('disfrutadas'=>$disfrutadas,'bonos'=>$bonos,'permisos'=>$permisos,'faltas'=>$faltas,'porAutorizar'=>$porAutorizar,'disponibles'=>$disponibles);
	  }
	
	
}

