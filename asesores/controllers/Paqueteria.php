<?php

class Paqueteria{

	#REGISTRAR PAQUETE INTERNO
	#------------------------------------------------------------
	public static function validarFormularioInterno($data){
		if(!preg_match('/^[0-9]{1,6}$/', $data["remitente"]))
			return array('error'=>true,'mensaje'=>'Error interno','mensaje2'=>'No se especifico el identificador del usuario.','tipo'=>'warning');
		if(!preg_match('/^[0-9]{1,6}$/', $data["destinatario"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Selecciona el destinatario.','tipo'=>'warning');
		if(!preg_match('/^[1-2]{1}$/', $data["envio"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Selecciona el tipo de envio.','tipo'=>'warning');
		if(!preg_match('/^[1-2]{1}$/', $data["seguro"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Selecciona la opción seguro de envio.','tipo'=>'warning');
		if(!empty($data["descripcion"])){
				if(preg_match('/["\']{1,}/', $data["descripcion"]))
					return array('error'=>true,'mensaje'=>"Captura correctamente el formulario",'mensaje2'=>'El campo descripción es obligatorio y no debe tener caracteres especiales, ni comillas.','tipo'=>'warning');
			}
		else
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'El campo descripción es obligatorio y no debe tener caracteres especiales, ni comillas.','tipo'=>'warning');

		if(!empty($data["comentarios"])){
			if(preg_match('/["\']{1,}/', $data["comentarios"]))
				return array('error'=>true,'mensaje'=>"Captura correctamente el formulario",'mensaje2'=>'El campo comentarios no debe tener caracteres especiales, ni comillas.','tipo'=>'warning');
		}
		else
			$data["comentarios"] = NULL;

		if(!empty($data["mensajero"])){
			if(!preg_match('/^[0-9]{1,6}$/', $data["mensajero"]))
				return array('error'=>true,'mensaje'=>'Error interno','mensaje2'=>'El ID del mensajero no es correcto.','tipo'=>'warning');
		}
		
		$respuesta = PaqueteriaModel::crearInterno($data,Tablas::paquetesInternos());
		return $respuesta;
	}

	#REGISTRAR PAQUETE EXTERNO
	public static function validarFormularioExterno($data){
		if(!preg_match('/^[0-9]{1,6}$/', $data["remitente"]))
			return array('error'=>true,'mensaje'=>'Error interno','mensaje2'=>'No se especifico el identificador del usuario.','tipo'=>'warning');
		if(!preg_match('/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ()_.\s-]{2,}$/', $data["compania"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el nombre de la compañia, no utilices caracteres especiales.','tipo'=>'warning');
		if(!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{2,}$/', $data["contacto"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el nombre del contacto, no utilices caracteres especiales.','tipo'=>'warning');
		if(!empty($data["email"])){
			if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $data["email"]))
				return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el correo electrónico.','tipo'=>'warning');
		}
		else
			$data["email"] = NULL;

		if(!preg_match('/^[0-9()\s-]{14}$/', $data["telefono"])) 
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el teléfono.','tipo'=>'warning');
		if(!preg_match('/^[0-9]{5}$/', $data["codigo"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el código postal.','tipo'=>'warning');
		if(!preg_match('/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ.\s]{4,}$/', $data["estado"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el Estado, no utilices caracteres especiales.','tipo'=>'warning');
		if(!preg_match('/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ.\s]{4,}$/', $data["municipio"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el Municipio, no utilices caracteres especiales.','tipo'=>'warning');
		if(!preg_match('/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ.\s]{4,}$/', $data["colonia"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe la Colonia, no utilices caracteres especiales.','tipo'=>'warning');
		if(!preg_match('/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ.,_\s-]{4,}$/', $data["direccion"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe la dirección, no utilices caracteres especiales.','tipo'=>'warning');
		if(!preg_match('/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ._\s-]{1,}$/', $data["exterior"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el número exterior, no utilices caracteres especiales.','tipo'=>'warning');
		if(!empty($data["interior"])){
			if(!preg_match('/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ._\s-]{1,}$/', $data["interior"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Escribe el número interior, no utilices caracteres especiales.','tipo'=>'warning');
		}
		else
			$data["interior"] = NULL;

		if(!preg_match('/^[1-2]{1}$/', $data["envio"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Selecciona el tipo de envio.','tipo'=>'warning');
		if(!preg_match('/^[1-2]{1}$/', $data["seguro"]))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Selecciona la opción seguro de envio.','tipo'=>'warning');
		if(!empty($data["comentarios"])){
			if(preg_match('/["\']{1,}/', $data["comentarios"]))
				return array('error'=>true,'mensaje'=>"Captura correctamente el formulario",'mensaje2'=>'El campo comentarios no debe tener caracteres especiales, ni comillas.','tipo'=>'warning');
		}
		else
			$data["comentarios"] = NULL;

		$idEstado =  Estados::vistaEstados2Model($data["estado"],Tablas::estados());
		$data["estado"] = $idEstado ;
		$respuesta = PaqueteriaModel::crearExterno($data,Tablas::paquetesExternos());
		return $respuesta;
	}

	#MOSTRAR TODOS LOS PAQUETES INTERNOS
	public function buscarPaquetesInternos($data,$limite=''){
		$respuesta = PaqueteriaModel::buscarPaquetesInternos($data,$limite,Tablas::paquetesInternos());
		$cadena='';
		$colorFila= true;
		$icono='';
		foreach ($respuesta as $row => $item){
			$icono='<i class="fa fa-eye text-black"></i>';
			$situacion='PENDIENTE';
			if ($item["situacion"] == 2)
				$situacion = 'CANCELADO';
			else if ($item["situacion"] == 3)
				$situacion = 'POR RECOLECTAR';
			else if ($item["situacion"] == 4)
				$situacion = 'RECIBIDO';
			else if ($item["situacion"] == 5)
				$situacion = 'EN RUTA';
			$tipoTexto='SALIENTE';
			$tipo=$item["id_destinatario"];

			if($data['idUsuario'] == $item["id_destinatario"]){
				$tipo=$item["id_remitente"];
				$tipoTexto='ENTRANTE';
				if($item['estado_destinatario'])
					$icono = '<i class="fa fa-eye-slash"></i>';
				else
					$icono = '<i class="fa fa-eye text-black"></i>';
			}
			else if($item['estado_remitente']){
				$icono = '<i class="fa fa-eye-slash"></i>';
			}
			$destinatario = Datos::mostrarUsuarioUnicoModel2($tipo,Tablas::usuarios());
			$cadena.='<div class="renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_paquete"].'">
					<div class="campoId"><span class="max-min"><img class="botonMaxMin" src="views/img/circle-max.png" height="25" width="25"></span><b>'.$item["id_paquete"].'</b></div>
					<div class="campoDestinatario">'.$destinatario["nombre"].' '.$destinatario["paterno"].' '.$destinatario["materno"].'</div>
					<div class="campoTipo">'.$tipoTexto.'</div>
					<div class="campoSituacion">'.$situacion.'</div>
					<div class="campoFecha textoMay divContenedorHijo"><span class="spanOcultoTelefono"><b>Fecha registro: </b></span>'.MetodosDiversos::formatearFecha($item['fecha_registro'],true).'</div>
					<div class="campoDetalle divContenedorHijo"><button class="btn btn-success mostrarDetallesPaqueteInterno" data-toggle="modal" data-target="#mostrarPaqueteriaInternaModal"><span>'.$icono.'</span> Mostrar</button></div>
				</div>';
		}
		//<div class="campoDetalle"><button class="btn btn-success mostrarDetallesPaqueteInterno" data-toggle="modal" data-target="#mostrarPaqueteriaInternaModal"><span id="destinatarioVioPaquete">'.$icono.'</span> Mostrar</button></div>
		return $cadena;
	}

	#MOSTRAR TODOS LOS PAQUETES INTERNOS PERMISOS DE PAQUETERIA
	public function buscarPaquetesInternosPlus($data,$limite=''){
		$respuesta = PaqueteriaModel::buscarPaquetesInternosPlus($data,$limite,Tablas::paquetesInternos(),Tablas::usuarios(),Tablas::dependenciasPaqueteria());
		$cadena='';
		$colorFila= true;
		foreach ($respuesta as $row => $item){

			$situacion='PENDIENTE';
			if ($item["situacion"] == 2)
				$situacion = 'CANCELADO';
			else if ($item["situacion"] == 3)
				$situacion = 'POR RECOLECTAR';
			else if ($item["situacion"] == 4)
				$situacion = 'RECIBIDO';
			else if ($item["situacion"] == 5)
				$situacion = 'EN RUTA';
			
			$tipoTexto='SALIDA';
			$tipo=$item["id_remitente"];

			if(intval($data['situacion']) >= 5 && intval($data['situacion']) != 8){
				$tipo=$item["id_destinatario"];
				$tipoTexto='ENTRADA';
			}

			$activar=false;
			$obtenerSucursalRemitente = PaqueteriaModel::obtenerSucursalRemitente($item["id_paquete"],Tablas::usuarios(),Tablas::paquetesInternos());
			$obtenerSucursalesAtiende = PaqueteriaModel::obtenerSucursalesRecepcion('dependencias_paqueteria_ae',Tablas::usuarios());
			foreach ($obtenerSucursalesAtiende as $row => $item2){
				if( $item2['sucursal_secundaria'] == $obtenerSucursalRemitente ){ //1
					$activar = true;
					break;
				}
			}

			if($activar){
				if($item['estado_recepcion'])
					$icono = '<i class="fa fa-eye-slash"></i>';
				else
					$icono = '<i class="fa fa-eye text-black"></i>';
			}
			else{
				if($item['estado_recepcion_destinatario'])
					$icono = '<i class="fa fa-eye-slash"></i>';
				else
					$icono = '<i class="fa fa-eye text-black"></i>';
			}
			

			$destinatario = Datos::mostrarUsuarioUnicoModel2($tipo,Tablas::usuarios());
			$cadena.='<div class="renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_paquete"].'">
					<div class="campoId"><span class="max-min"><img class="botonMaxMin" src="views/img/circle-max.png" height="25" width="25"></span><b>'.$item["id_paquete"].'</b></div>
					<div class="campoDestinatario">'.$destinatario["nombre"].' '.$destinatario["paterno"].' '.$destinatario["materno"].'</div>
					<div class="campoTipo">'.$tipoTexto.'</div>
					<div class="campoSituacion">'.$situacion.'</div>
					<div class="campoFecha textoMay divContenedorHijo"><span class="spanOcultoTelefono"><b>Fecha registro: </b></span>'.MetodosDiversos::formatearFecha($item['fecha_registro'],true).'</div>
					<div class="campoDetalle divContenedorHijo"><button class="btn btn-success mostrarDetallesPaqueteInterno" data-toggle="modal" data-target="#mostrarPaqueteriaInternaModal"><span>'.$icono.'</span> Mostrar</button></div>
				</div>';
		}
		return $cadena;
	}

	#MOSTRAR TODOS LOS PAQUETES EXTERNOS
	public function buscarPaquetesExternos($data,$limite=''){
		$respuesta = PaqueteriaModel::buscarPaquetesExternos($data,$limite,Tablas::paquetesExternos(),Tablas::usuarios());
		$cadena='';
		$colorFila= true;
		foreach ($respuesta as $row => $item){
			$icono='';
			if($_SESSION['identificador2'] != Configuraciones::recepcion()){
				if($item['estado_remitente'])
					$icono = '<i class="fa fa-eye-slash"></i>';
				else
					$icono='<i class="fa fa-eye text-black"></i>';
			}
			$situacion='PENDIENTE';
			if ($item["situacion"] == 2)
				$situacion = 'CANCELADO';
			else if ($item["situacion"] == 3)
				$situacion = 'POR RECOLECTAR';
			else if ($item["situacion"] == 4)
				$situacion = 'RECIBIDO';
			else if ($item["situacion"] == 5)
				$situacion = 'EN RUTA';
			
			$cadena.='<div class="renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'" id="'.$item["id_paquete"].'">
					<div class="campoId"><span class="max-min"><img class="botonMaxMin2" src="views/img/circle-max.png" height="25" width="25"></span><b>'.$item["id_paquete"].'-E</b></div>
					<div class="campoDestinatario">'.$item["compania"].'</div>
					<div class="campoFecha textoMay">'.$item["contacto"].'</div>
					<div class="campoTipo textoMay">'.MetodosDiversos::formatearFecha($item['fecha_registro'],true).'</div>
					<div class="campoSituacion">'.$situacion.'</div>
					<div class="campoDetalle divContenedorHijo"><button class="btn btn-success mostrarDetallesPaqueteExterno" data-toggle="modal" data-target="#mostrarPaqueteriaExternaModal"><span>'.$icono.'</span> Mostrar</button></div>
				</div>';
		}
		return $cadena;
	}

	#DETALLE PAQUETE INTERNO
	public static  function detallePaqueteInterno($usuario,$idPaquete){
		$tipo='';
		$rol='';
		$labelTipoPaquete='';
		$labelTipoPaquete='';
		$actualizarIcono = 0;
		$respuesta = PaqueteriaModel::detallePaqueteInterno($idPaquete,Tablas::paquetesInternos());

		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			$recepcionista= Datos::mostrarUsuarioUnicoModel2($usuario,Tablas::usuarios());//saber la sucursal de la recepcionista
			$validar= PaqueteriaModel::validarEntradaSalidaRecepcion($recepcionista["id_sucursal"],Tablas::dependenciasPaqueteria());//validar a cuantas sucursales atiende la recepcionista
			$remitente= Datos::mostrarUsuarioUnicoModel2($respuesta['id_remitente'],Tablas::usuarios());//saber la sucursal del remitente para saber si es o no de las sucursales de la recepcionista

			$activar=false;
			$obtenerSucursalRemitente = PaqueteriaModel::obtenerSucursalRemitente($idPaquete,Tablas::usuarios(),Tablas::paquetesInternos());
			$obtenerSucursalesAtiende = PaqueteriaModel::obtenerSucursalesRecepcion('dependencias_paqueteria_ae',Tablas::usuarios());
			foreach ($obtenerSucursalesAtiende as $row => $item2){
				if( $item2['sucursal_secundaria'] == $obtenerSucursalRemitente ){ //1
					$activar = true;
					break;
				}
			}

			if($activar){
				if($respuesta['estado_recepcion']){
					PaqueteriaModel::paqueteVistoRecepcionista($idPaquete,Tablas::paquetesInternos(),true);
					$actualizarIcono = 1;
				}
			}
			else{
				if($respuesta['estado_recepcion_destinatario']){
					PaqueteriaModel::paqueteVistoRecepcionista($idPaquete,Tablas::paquetesInternos(),false);
					$actualizarIcono = 1;
				}
			}

			$flag = false;
			$tipo=$respuesta["id_remitente"];
			$rol = 'Remitente';
			$labelTipoPaquete = '<i class="fa fa-sign-in text-blue fa-2x"></i> ENTRADA';
			foreach($validar as $row => $item){
				if($item['sucursal_secundaria'] == $remitente['id_sucursal']){
					$flag = true;
					$tipo=$respuesta["id_destinatario"];
					$rol = 'Destinatario';
					$labelTipoPaquete = '<i class="fa fa-sign-out text-green fa-2x"></i> SALIDA';
					break;
				}
			}
		}

		else{
			$tipo=$respuesta["id_destinatario"];
			$rol = 'Destinatario';
			$labelTipoPaquete = '<i class="fa fa-sign-out text-green fa-2x"></i> SALIDA';
			if($usuario == $respuesta["id_destinatario"]){
				$tipo=$respuesta["id_remitente"];
				$rol = 'Remitente';
				$labelTipoPaquete = '<i class="fa fa-sign-in text-blue fa-2x"></i> ENTRADA';

				if($respuesta['estado_destinatario']){
					PaqueteriaModel::paqueteVisto($idPaquete,true,Tablas::paquetesInternos());
					$actualizarIcono = 1;
				}
			}
			else if($respuesta['estado_remitente']){
					PaqueteriaModel::paqueteVisto($idPaquete,false,Tablas::paquetesInternos());
					$actualizarIcono = 1;
			}
		}
		
		$destinatario = Datos::mostrarUsuarioUnicoModel2($tipo,Tablas::usuarios());
		$envio = $respuesta['tipo_envio'] == 1 ? 'ESTANDAR' : 'EXPRESS';
		$seguro = $respuesta['seguro'] == 1 ? 'NO' : 'SÍ';
		$situacion = 'PENDIENTE POR ENVIAR';
		$labelSituacionPaquete = '<i class="fa fa-question-circle-o text-blue fa-2x"></i> ';
		if($respuesta['situacion'] == 2){
			$situacion = 'CANCELADO';
			$labelSituacionPaquete = '<i class="fa fa-window-close text-red fa-2x"></i> ';
		}
		else if($respuesta['situacion'] == 3){
			$situacion = 'POR RECOLECTAR';
			$labelSituacionPaquete = '<i class="fa fa-clock-o text-yellow fa-2x"></i> ';
		}	
		elseif($respuesta['situacion'] == 4){
			$situacion = 'RECIBIDO';
			$labelSituacionPaquete = '<i class="fa fa-check-square text-green fa-2x"></i> ';
		}
		else if($respuesta['situacion'] == 5){
			$situacion = 'EN RUTA';
			$labelSituacionPaquete = '<i class="fa fa-truck text-black fa-2x"></i> ';
		}
		$html=$html2='';
		$html.='<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<span><b>'.$rol.': </b>'.$destinatario["nombre"].' '.$destinatario["paterno"].' '.$destinatario["materno"].'</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<span><b>Sucursal: </b>'.Sucursales::traducirSucursalesModel($destinatario["id_sucursal"],Tablas::sucursales()).'</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<span><b>Departamento: </b>'.Departamentos::vistaDepartamentos2Model($destinatario['id_departamento'],Tablas::departamentosIntranet()).'</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<span><b>Puesto: </b>'.Departamentos::vistaPuestos2Model($destinatario['id_puesto'],Tablas::puestos()).'</span>
						</div>
					</div>
				</div>
				<hr style="margin-bottom: -8px;">';

		$html2.='<div class="form-group">
					<div class="row" >
						<div class="col-md-12">
							<span><h5><b>SITUACIÓN DEL PAQUETE: </b>'.$labelSituacionPaquete.$situacion.'</h5></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<span><b>No. Envio: </b>'.$respuesta['id_paquete'].'</span>
						</div>
						
						<div class="col-md-6">
							<span><b>Tipo de envio: </b>'.$envio.'</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<span><b>Asegurado: </b>'.$seguro.'</span>
						</div>
						
						<div class="col-md-6">
							<span><b>Registrado: </b><span class="textoMay">'.MetodosDiversos::formatearFecha($respuesta['fecha_registro'],true).'</span></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<span><b>Listo para ser recolectado: </b><span class="textoMay">'.MetodosDiversos::formatearFecha($respuesta['fecha_por_recolectar'],true).'</span></span>
						</div>
						
						<div class="col-md-6">
							<span><b>En ruta: </b><span class="textoMay">'.MetodosDiversos::formatearFecha($respuesta['fecha_envio'],true).'</span></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<span><b>Recibido: </b><span class="textoMay">'.MetodosDiversos::formatearFecha($respuesta['fecha_recibido'],true).'</span></span>
						</div>
						<div class="col-md-6">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<span><b>Descripción del paquete: </b>'.$respuesta['descripcion'].'</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<span><b>Comentarios: </b>'.$respuesta['comentarios'].'</span>
						</div>
					</div>
				</div>';

		if($_SESSION['identificador2'] == Configuraciones::recepcion() && $flag && $respuesta['situacion'] != 4 && $respuesta['situacion'] != 2 ){
			$etiqueta='Guia: ';
			if($respuesta['mensajeria'] == 2){
				$etiqueta='Mensajero: ';
				$respuesta['guia'] = PaqueteriaModel::getMensajero($respuesta['guia'],Tablas::usuarios());
			}

			$html2.='<form method="POST"  id="formularioEnviarPaqueteInterno">
						<div class="row">
							<div class="col-md-6">
								<span><b>Paquetería: </b></span><span id="actualizarMensajeriaInterna">'.PaqueteriaModel::nombrePaqueteria($respuesta['mensajeria'],Tablas::paqueterias()).'</span>
							</div>
							<div class="col-md-6">
								<span><b>'.$etiqueta.' </b></span><span id="actualizarGuiaInterna">'.$respuesta['guia'].'</span>
							</div>
						</div>
						<hr>';

			if($respuesta['situacion'] == 1 && $usuario == $respuesta['id_remitente']){
				$html2.='<div class="row">
							<div class="col-md-6 estilos-centrar">
								<span id="cambiarBotonInterna"><button type="button" class="btn btn-info" id="actualizarPaqueteRecepcionInterna">Asignar paquetería y guía</button></span>
							</div>
							<div class="col-md-6 estilos-centrar">
								<button type="button" class="btn btn-danger" id="paqueteCancelarInterno">Cancelar envio</button>
							</div>
						</div>';
			}

			else if($respuesta['situacion'] == 3 ){
				$html2.='<div class="row">
							<div class="col-md-4 estilos-centrar">
								<span id="cambiarBotonInterna"><button type="button" class="btn btn-info" id="actualizarPaqueteRecepcionInterna">Actualizar paquetería y guía</button></span>
							</div>
							<div class="col-md-8 estilos-centrar">
								<button type="button" class="btn btn-warning" id="paqueteRecolectadoInterno">¿ La paquetería ha recogido el paquete ?</button>
							</div>
						</div>';
			}
			else if($respuesta['situacion'] != 5){
				$html2.='<div class="row">
							<div class="col-md-12 estilos-centrar">
								<span id="cambiarBotonInterna"><button type="button" class="btn btn-info" id="actualizarPaqueteRecepcionInterna">Asignar paquetería y guía</button></span>
							</div>
						</div>';
			}

				$html2.='</form>';
		}
		else if($respuesta['situacion'] == 5 && $rol != 'Destinatario'){
			$etiqueta='Guia: ';
			if($respuesta['mensajeria'] == 2){
				$etiqueta='Mensajero: ';
				$respuesta['guia'] = PaqueteriaModel::getMensajero($respuesta['guia'],Tablas::usuarios());
			}
			$html2.='<div class="row">
						<div class="col-md-6">
							<span><b>Paquetería: </b></span><span id="actualizarMensajeriaExterna">'.PaqueteriaModel::nombrePaqueteria($respuesta['mensajeria'],Tablas::paqueterias()).'</span>
						</div>
						<div class="col-md-6">
							<span><b>'.$etiqueta.' </b></span><span id="actualizarGuiaExterna">'.$respuesta['guia'].'</span>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12 estilos-centrar">
							<span id="cambiarBotonInterna"><button type="button" class="btn btn-success btn-lg" id="paqueteEntregado">¿ Ya recibiste el paquete ?</button></span>
						</div>
					</div>';
		}
		else if($respuesta['situacion'] == 1 && $rol != 'Remitente'){
			$html2.='<hr>
					<div class="row">
						<div class="col-md-12 estilos-centrar">
							<span id="cambiarBotonInterna"><button type="button" class="btn btn-danger" id="paqueteCancelarInterno">Cancelar</button></span>
						</div>
					</div>';
			}
		else{
			$etiqueta='Guia: ';
			if($respuesta['mensajeria'] == 2){
				$etiqueta='Mensajero: ';
				$respuesta['guia'] = PaqueteriaModel::getMensajero($respuesta['guia'],Tablas::usuarios());
			}
			$html2.='<div class="row">
						<div class="col-md-6">
							<span><b>Paquetería: </b></span><span id="actualizarMensajeriaExterna">'.PaqueteriaModel::nombrePaqueteria($respuesta['mensajeria'],Tablas::paqueterias()).'</span>
						</div>
						<div class="col-md-6">
							<span><b>'.$etiqueta.' </b></span><span id="actualizarGuiaExterna">'.$respuesta['guia'].'</span>
						</div>
					</div>';
		}
		
		if($respuesta['situacion'] == 4 ){

			if($respuesta['situacion_paquete'] == 1)
				$respuesta['situacion_paquete'] = 'Sí';
			else if($respuesta['situacion_paquete'] == 2)
				$respuesta['situacion_paquete'] = 'No';
			if($respuesta['estado_paquete'] == 1)
				$respuesta['estado_paquete'] = 'Sí';
			else if($respuesta['estado_paquete'] == 2)
				$respuesta['estado_paquete'] = 'No';

			$html2.='<hr>
					<div class="row" style="margin-top: -28px;">
						<div class="col-md-12">
							<span><h5><b>DETALLES DE RECEPCIÓN DEL PAQUETE</b> <i class="fa fa-archive text-blue fa-2x"></i> </h5></span>
						</div>
					</div>
					<br>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<span><b>El paquete llegó completo: </b></span>'.$respuesta['situacion_paquete'].'
							</div>
							<div class="col-md-6">
								<span><b>El paquete llegó en buen estado: </b></span>'.$respuesta['estado_paquete'].'
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<span><b>Comentarios: </b></span>'.$respuesta['comentarios_recibido'].'
							</div>
						</div>
					</div>';
		}

		$paqueterias = self::paqueterias();
		echo json_encode(array('datos'=>$html,'datos2'=>$html2,'imagen'=>$destinatario['imagen'],'tipo'=>$labelTipoPaquete,'paqueterias'=>$paqueterias,'paquete'=>$idPaquete,'icono'=>$actualizarIcono));
	}

	#DETALLE PAQUETE EXTERNO
	public static  function detallePaqueteExterno($idPaquete){
		$respuesta = PaqueteriaModel::detallePaqueteInterno($idPaquete,Tablas::paquetesExternos());
		$actualizarIcono = 0;

		if($_SESSION['identificador2'] != Configuraciones::recepcion()){
			if($respuesta['estado_remitente']){
				PaqueteriaModel::paqueteExternoVisto($idPaquete,Tablas::paquetesExternos());
				$actualizarIcono = 1;
			}
		}

		$envio = $respuesta['tipo_envio'] == 1 ? 'ESTANDAR' : 'EXPRESS';
		$seguro = $respuesta['seguro'] == 1 ? 'NO' : 'SÍ';

		$situacion = 'PENDIENTE POR ENVIAR';
		$labelSituacionPaquete = '<i class="fa fa-question-circle-o text-blue fa-2x"></i> ';
		if($respuesta['situacion'] == 2){
			$situacion = 'CANCELADO';
			$labelSituacionPaquete = '<i class="fa fa-window-close text-red fa-2x"></i> ';
		}
		else if($respuesta['situacion'] == 3){
			$situacion = 'POR RECOLECTAR';
			$labelSituacionPaquete = '<i class="fa fa-clock-o text-yellow fa-2x"></i> ';
		}	
		elseif($respuesta['situacion'] == 4){
			$situacion = 'RECIBIDO';
			$labelSituacionPaquete = '<i class="fa fa-check-square text-green fa-2x"></i> ';
		}
		elseif($respuesta['situacion'] == 5){
			$situacion = 'EN RUTA';
			$labelSituacionPaquete = '<i class="fa fa-check-square text-green fa-2x"></i> ';
		}
		$html=$html2=$imagen='';
		
		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			$remitente = Datos::mostrarUsuarioUnicoModel2($respuesta['id_remitente'],Tablas::usuarios());
			$html2.='<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<span><b>Remitente: </b>'.$remitente["nombre"].' '.$remitente["paterno"].' '.$remitente["materno"].'</span>
							</div>
						</div>
					</div>
					<!--<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<span><b>Sucursal: </b>'.Sucursales::traducirSucursalesModel($remitente["id_sucursal"],Tablas::sucursales()).'</span>
							</div>
						</div>
					</div>-->
					<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<span><b>Departamento: </b>'.Departamentos::vistaDepartamentos2Model($remitente['id_departamento'],Tablas::departamentosIntranet()).'</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<span><b>Puesto: </b>'.Departamentos::vistaPuestos2Model($remitente['id_puesto'],Tablas::puestos()).'</span>
							</div>
						</div>
					</div>
					<hr>';
			$imagen=$remitente["imagen"];
		}

		$html.='<div class="row">
					<div class="col-md-12">
						<span><b>Destinatario: </b>'.$respuesta['compania'].'</span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<span><b>Contacto: </b>'.$respuesta['contacto'].'</span>
					</div>
					<div class="col-md-6">
						<span><b>Telefóno: </b>'.$respuesta['telefono'].'</span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<span><b>Correo: </b>'.$respuesta['correo'].'</span>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<span><b>Dirección: </b>'.$respuesta['direccion'].'</span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<span><b>No. Ext: </b>'.$respuesta['exterior'].'</span>
					</div>
					<div class="col-md-6">
						<span><b>No. Int: </b>'.$respuesta['interior'].'</span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<span><b>Colonia: </b>'.$respuesta['colonia'].'</span>
					</div>
					<div class="col-md-6">
						<span><b>Municipio: </b>'.$respuesta['municipio'].'</span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<span><b>Estado: </b>'.mb_strtoupper(Estados::vistaEstadosModel($respuesta['estado'],Tablas::estados()),'utf-8').'</span>
					</div>
					<div class="col-md-6">
						<span><b>C.P.: </b>'.$respuesta['codigo'].'</span>
					</div>
				</div>
			
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<span><b>Situación del paquete: </b>'.$labelSituacionPaquete.$situacion.'</span>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<span><b>No. Envio: </b>'.$respuesta['id_paquete'].'-E</span>
					</div>
						
					<div class="col-md-6">
						<span><b>Tipo de envio: </b>'.$envio.'</span>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<span><b>Asegurado: </b>'.$seguro.'</span>
					</div>
						
					<div class="col-md-6">
						<span><b>Registrado: </b><span class="textoMay">'.MetodosDiversos::formatearFecha($respuesta['fecha_registro'],true).'</span></span>
					</div>
				</div>
				
			
				<div class="row">
					<div class="col-md-6">
						<span><b>Fecha para ser recolectado: </b><span class="textoMay">'.MetodosDiversos::formatearFecha($respuesta['fecha_recolectado'],true).'</span></span>
					</div>
			
					<div class="col-md-6">
						<span><b>Enviado: </b><span class="textoMay">'.MetodosDiversos::formatearFecha($respuesta['fecha_envio'],true).'</span></span>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<span><b>Recibido: </b><span class="textoMay">'.MetodosDiversos::formatearFecha($respuesta['fecha_recibido'],true).'</span></span>
					</div>
				</div>
			
				<div class="row">
					<div class="col-md-12">
						<span><b>Comentarios: </b>'.$respuesta['comentarios'].'</span>
					</div>
				</div>';
		if($_SESSION['identificador2'] == Configuraciones::recepcion() && $respuesta['situacion'] != 4 && $respuesta['situacion'] != 2){
			$html.='<form method="POST"  id="formularioEnviarPaqueteExterno">
						<div class="row">
							<div class="col-md-6">
								<span><b>Paquetería: </b></span><span id="actualizarMensajeriaExterna">'.PaqueteriaModel::nombrePaqueteria($respuesta['mensajeria'],Tablas::paqueterias()).'</span>
							</div>
							<div class="col-md-6">
								<span><b>Guia: </b></span><span id="actualizarGuiaExterna">'.$respuesta['guia'].'</span>
							</div>
						</div>
						<hr>';
					
			if($respuesta['situacion'] == 3){
					$html.='<div class="col-md-4 estilos-centrar">
								<span id="cambiarBoton"><button type="button" class="btn btn-info" id="actualizarPaqueteRecepcion">Actualizar paquetería y guía</button></span>
							</div>
							<div class="col-md-8 estilos-centrar">
								<button type="button" class="btn btn-warning" id="paqueteRecolectadoExterno">¿ La paquetería ha recogido el paquete ?</button>
							</div>';
			}	

			else if($respuesta['situacion'] == 1 && $_SESSION['identificador'] == $respuesta['id_remitente']){
				$html.='<div class="row">
							<div class="col-md-6 estilos-centrar">
								<span id="cambiarBotonInterna"><button type="button" class="btn btn-info" id="actualizarPaqueteRecepcion">Asignar paquetería y guía</button></span>
							</div>
							<div class="col-md-6 estilos-centrar">
								<button type="button" class="btn btn-danger" id="paqueteCancelarExterno">Cancelar envio</button>
							</div>
						</div>';
			}

			else if($respuesta['situacion'] == 1){		
				$html.='<div class="row">
							<div class="col-md-12 estilos-centrar">
								<span id="cambiarBoton"><button type="button" class="btn btn-info" id="actualizarPaqueteRecepcion">Asignar paquetería y guía</button></span>
							</div>
						</div>';
			}	
			else if($respuesta['situacion'] == 5){		
				$html.='<div class="row">
							<div class="col-md-12 estilos-centrar">
								<button type="button" class="btn btn-success btn-lg" id="paqueteEntregadoExterno">¿ El paquete ya fue recibido ?</button>
							</div>
						</div>';
			}	
			$html.='</form>';
		}
		else{
			$html.='<div class="row">
						<div class="col-md-6">
							<span><b>Paquetería: </b></span><span id="actualizarMensajeriaExterna">'.PaqueteriaModel::nombrePaqueteria($respuesta['mensajeria'],Tablas::paqueterias()).'</span>
						</div>
						<div class="col-md-6">
							<span><b>Guia: </b></span><span id="actualizarGuiaExterna">'.$respuesta['guia'].'</span>
						</div>
					</div>';
			if($respuesta['situacion'] == 1){
			$html.='<hr>
					<div class="row">
						<div class="col-md-12 estilos-centrar">
							<span id="cambiarBotonInterna"><button type="button" class="btn btn-danger" id="paqueteCancelarExterno">Cancelar envio</button></span>
						</div>
					</div>';
			}
			else if($respuesta['situacion'] == 5){
			$html.='<hr>
					<div class="row">
						<div class="col-md-12 estilos-centrar">
							<span id="cambiarBotonInterna"><button type="button" class="btn btn-success btn-lg" id="paqueteEntregadoExterno">¿ El paquete ya fue recibido ?</button></span>
						</div>
					</div>';
			}
		}
		
		

		$paqueterias = self::paqueterias();
		echo json_encode(array('datos2'=>$html2,'datos'=>$html,'imagen'=>$imagen,'paquete'=>$idPaquete,'paqueterias'=>$paqueterias,'icono'=>$actualizarIcono));
	}

	#TOTAL DE REGISTROS PARA CALCULAR LA PAGINACIÓN DEL MODULO ADMINISTRAR PAQUETES INTERNOS
	public static function totalPaquetesInternos($data){
		$respuesta = PaqueteriaModel::buscarPaquetesInternos($data,$limite='',Tablas::paquetesInternos());
		return count($respuesta);
	}

	#TOTAL DE REGISTROS PARA CALCULAR LA PAGINACIÓN DEL MODULO ADMINISTRAR PAQUETES INTERNOS PERMISOS DE PAQUETERIA
	public static function totalPaquetesInternosPlus($data){
		$respuesta = PaqueteriaModel::buscarPaquetesInternosPlus($data,$limite='',Tablas::paquetesInternos(),Tablas::usuarios(),Tablas::dependenciasPaqueteria());
		return count($respuesta);
	}

	#TOTAL DE REGISTROS PARA CALCULAR LA PAGINACIÓN DEL MODULO ADMINISTRAR PAQUETES EXTERNOS
	public static function totalPaquetesExternos($data){
		$respuesta = PaqueteriaModel::buscarPaquetesExternos($data,$limite='',Tablas::paquetesExternos(),Tablas::usuarios());
		return count($respuesta);
	}

	#INDICA LA CANTIDAD DE PAQUETES INTERNOS DEPENDIENDO DE SU SITUACION
	public static function marcadoresInternos($situacion,$entradaSalida=false){
		$respuesta = PaqueteriaModel::marcadoresInternos($situacion,$entradaSalida,Tablas::paquetesInternos(),Tablas::usuarios());
		return $respuesta;
	}

	#INDICA LA CANTIDAD DE PAQUETES EXTERNOS DEPENDIENDO DE SU SITUACION
	public static function marcadoresExternos($situacion){
		$respuesta = PaqueteriaModel::marcadoresExternos($situacion,Tablas::paquetesExternos(),Tablas::usuarios());
		return $respuesta;
	}

	#SE ASIGNA LA PAQUETERIA Y NUMERO DE GUIA AL PAQUETE EXTERNO
	public static function actualizarPaqueteExterno($idPaquete,$paqueria,$guia){
		if(!preg_match('/^[0-9]{1}$/', $paqueria))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Selecciona el nombre de la paquetería.','tipo'=>'warning');
		$respuesta = PaqueteriaModel::actualizarPaqueteExterno($idPaquete,$paqueria,$guia,Tablas::paquetesExternos());
		return $respuesta;
	}

	#SE ASIGNA LA PAQUETERIA Y NUMERO DE GUIA AL PAQUETE INTERNO
	public static function actualizarPaqueteInterno($idPaquete,$paqueria,$guia){
		if(!preg_match('/^[0-9]{1}$/', $paqueria))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Selecciona el nombre de la paquetería.','tipo'=>'warning');
		$respuesta = PaqueteriaModel::actualizarPaqueteInterno($idPaquete,$paqueria,$guia,Tablas::paquetesInternos());
		return $respuesta;
	}

	#SE MUESTRAN LAS PAQUETERIAS POSIBLES A SELECCIONAR
	public static function paqueterias(){
		$respuesta = PaqueteriaModel::paqueterias(Tablas::paqueterias());
		$select ='<select class="textoMay miSelect" id="nombrePaqueteria" name="nombrePaqueteria" pattern="[0-9]{1}" required>';
		$select.='<option value=""></option>';
		foreach($respuesta as $row => $item){
			$select.='<option value="'.$item["id_paqueteria"].'">'.$item["nombre"].'</option>';
		}
		$select.='</select>';
		return $select;
	}

	#SE FINALIZA EL PROCESO DE ENVIO DEL PAQUETE INTERNO
	public static function finalizarPaqueteInterno($idPaquete){
		$respuesta = PaqueteriaModel::finalizarPaqueteInterno($idPaquete,Tablas::paquetesInternos());
		echo json_encode($respuesta);
	}

	#SE FINALIZA EL PROCESO DE ENVIO DEL PAQUETE INTERNO
	public static function cancelarPaqueteInterno($idPaquete){
		$respuesta = PaqueteriaModel::cancelarPaqueteInterno($idPaquete,Tablas::paquetesInternos());
		echo json_encode($respuesta);
	}

	#SE FINALIZA EL PROCESO DE ENVIO DEL PAQUETE EXTERNO
	public static function finalizarPaqueteExterno($idPaquete){
		$respuesta = PaqueteriaModel::finalizarPaqueteExterno($idPaquete,Tablas::paquetesExternos());
		echo json_encode($respuesta);
	}

	#SE FINALIZA EL PROCESO DE ENVIO DEL PAQUETE EXTERNO
	public static function cancelarPaqueteExterno($idPaquete){
		$respuesta = PaqueteriaModel::cancelarPaqueteExterno($idPaquete,Tablas::paquetesExternos());
		echo json_encode($respuesta);
	}

	#OBTENEMOS LAS CANTIDADA DE PAQUETES QUE SE NOTIFICARAN AL USUARIO
	public static function comprobarExistenciaPaquetes(){
		if($_SESSION['identificador2'] == Configuraciones::recepcion())
			$respuesta = PaqueteriaModel::comprobarExistenciaPaquetesRecepcion(Tablas::paquetesInternos(),'dependencias_paqueteria_ae',Tablas::usuarios());//busco cambios en paquetes por todo el personal de mi recepcion a cargo
		else
			$respuesta = PaqueteriaModel::comprobarExistenciaPaquetes(Tablas::paquetesInternos());//cada persona busca cambios en sus paquetes
		return $respuesta;
	}

	#OBTENEMOS LAS CANTIDADA DE PAQUETES EXTERNOS QUE SE NOTIFICARAN AL USUARIO
	public static function comprobarExistenciaPaquetesExternos(){
		$respuesta = PaqueteriaModel::comprobarExistenciaPaquetesExternos(Tablas::paquetesExternos());
		return $respuesta;
		
	}
	
	#CUESTIONARIO RECEPCION PAQUETE INTERNO
	public static function formularioPaqueteInterno($idPaquete,$estadoRecibido,$completoRecibido,$comentarioRecibido){
		if(!preg_match('/^[1-2]{1}$/', $estadoRecibido))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Indica si el contenido del paquete llegó en buen estado.','tipo'=>'warning');
		if(!preg_match('/^[1-2]{1}$/', $completoRecibido))
			return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'Indica si el contenido del paquete llegó completo.','tipo'=>'warning');
		if(!empty($comentarioRecibido)){
			if(!preg_match('/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ()._,:;\s-]{2,}$/', $comentarioRecibido))
				return array('error'=>true,'mensaje'=>'Captura correctamente el formulario','mensaje2'=>'El campo comentarios no debe tener caracteres especiales, ni comillas.','tipo'=>'warning');
		}	

		$respuesta = PaqueteriaModel::formularioPaqueteInterno($idPaquete,$estadoRecibido,$completoRecibido,$comentarioRecibido,Tablas::paquetesInternos());
		return $respuesta;
	}

	#PAQUETE INTERNO EN CAMINO O RUTA
	public static function enviadoPaqueteInterno($idPaquete){
		$respuesta = PaqueteriaModel::enviadoPaqueteInterno($idPaquete,Tablas::paquetesInternos());
		echo json_encode($respuesta);
	}

	#PAQUETE EXTERNO EN CAMINO O RUTA
	public static function enviadoPaqueteExterno($idPaquete){
		$respuesta = PaqueteriaModel::enviadoPaqueteExterno($idPaquete,Tablas::paquetesExternos());
		echo json_encode($respuesta);
	}

	
	public static function getMensajerosInternos($zona){
		$respuesta = PaqueteriaModel::getMensajerosInternos($zona,Tablas::usuarios(),Tablas::mensajeros());
		$mensajeros=array();
		foreach ($respuesta as $row)
			array_push($mensajeros,array($row['id_usuario'],$row['nombre'].' '.$row['paterno'].' '.$row['materno']));
		echo json_encode(array("mensajeros"=>$mensajeros));
	}


	public static function getMensajerosInternos2($idPaquete){
		$sucursal = Sucursales::mostrarSucursalUsuario(Tablas::usuarios(),$_SESSION['identificador']);
		$zona = Sucursales::verificarSucursalLocal($sucursal,Tablas::usuarios(),'dependencias_sucursales_paqueteria_ae');
		$respuesta = PaqueteriaModel::getMensajerosInternos($zona,Tablas::usuarios(),Tablas::mensajeros());
		$mensajeros=array();
		foreach ($respuesta as $row)
			array_push($mensajeros,array($row['id_usuario'],$row['nombre'].' '.$row['paterno'].' '.$row['materno']));
		echo json_encode(array("mensajeros"=>$mensajeros));
	}

}
