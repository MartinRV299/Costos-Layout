<?php
require_once "conexion.php";

class PermisosModels{

	//INSERTAR SOLICITUD PERMISO 
	public static function nuevoPermisoModels($datos,$tabla){
		
		$extemporaneo=$extemporaneo2='';
		if($datos['extemporaneo']!=="undefined"){
			$datos['extemporaneo']=1;
			$extemporaneo=',extemporaneo';
			$extemporaneo2=',:extemporaneo';
		}

		$imagen=$imagen2='';
		if(($datos["imagenNombre"]) != NULL){
			$imagen=',imagen';
			$imagen2=',:imagen';
		}
		
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_usuario,tipo_solicitud,tipo_permiso,fecha_solicitud,hora_solicitud,fecha_inicio,motivo,fecha_fin,horario_inicio,horario_fin$extemporaneo$imagen) VALUES (:usuario,:solicitud,:permiso,now(),now(),:fecha_inicio,:motivo,:fecha_fin,:hora_inicio,:hora_fin$extemporaneo2$imagen2)");		
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":solicitud", $datos["solicitud"], PDO::PARAM_INT);
		$stmt->bindParam(":permiso", $datos["permiso"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_inicio", $datos["fechaInicial"], PDO::PARAM_STR);
		$stmt->bindParam(":motivo", $datos["motivo"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_fin", $datos["fechaFinal"], PDO::PARAM_STR);
		$stmt->bindParam(":hora_inicio", $datos["horaInicial"], PDO::PARAM_STR);
		$stmt->bindParam(":hora_fin", $datos["horaFinal"], PDO::PARAM_STR);
		if($datos['extemporaneo']!=="undefined")
			$stmt->bindParam(":extemporaneo", $datos["extemporaneo"], PDO::PARAM_INT);
		if(($datos["imagenNombre"]) != NULL)
			$stmt->bindParam(":imagen", $datos["imagenNombre"], PDO::PARAM_STR);

		if($stmt->execute())
			return 3;//"Registro exitoso";
		else
			return 4;//"No se realizó el registro, intentelo nuevamente";
		$stmt->close();
	}

	//INSERTAR SOLICITUD VACACIONES
	public static function nuevoVacacionesModels($datos,$tabla){
		$extemporaneo=$extemporaneo2='';
		if($datos['extemporaneo']!=="undefined"){
			if( $datos['conteo'] < 0)
				$datos['extemporaneo']=2;
			else
				$datos['extemporaneo']=1;
			$extemporaneo=',extemporaneo';
			$extemporaneo2=',:extemporaneo';
		}
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_usuario,tipo_solicitud,fecha_solicitud,hora_solicitud,fecha_inicio,fecha_fin$extemporaneo) VALUES (:usuario,:solicitud,now(),now(),:fecha_inicio,:fecha_fin$extemporaneo2)");		
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":solicitud", $datos["solicitud"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_inicio", $datos["fechaInicial"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_fin", $datos["fechaFinal"], PDO::PARAM_STR);
		if($datos['extemporaneo']!=="undefined")
			$stmt->bindParam(":extemporaneo", $datos["extemporaneo"], PDO::PARAM_INT);
		
		if($stmt->execute())
			return 3;//"Registro exitoso";
		else
			return 4;//"No se realizó el registro, intentelo nuevamente";
		$stmt->close();
	}


	//INSERTAR SOLICITUD CAMBIO DE GUARDIA
	public static function nuevoCambiosModels($datos,$tabla){

		$extemporaneo=$extemporaneo2='';
		if($datos['extemporaneo']!=="undefined"){
			$datos['extemporaneo']=1;
			$extemporaneo=',extemporaneo';
			$extemporaneo2=',:extemporaneo';
		}

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_usuario,tipo_solicitud,fecha_solicitud,hora_solicitud,fecha_inicio,fecha_fin,id_usuario_cambio,enterado_cambio$extemporaneo) VALUES (:usuario,:solicitud,now(),now(),:fecha_inicio,:fecha_fin,:usuarioCambio,0$extemporaneo2)");			
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":solicitud", $datos["solicitud"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_inicio", $datos["fecha"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_fin", $datos["fecha2"], PDO::PARAM_STR);
		$stmt->bindParam(":usuarioCambio", $datos["usuarioSecundario"], PDO::PARAM_INT);
		if($datos['extemporaneo']!=="undefined")
			$stmt->bindParam(":extemporaneo", $datos["extemporaneo"], PDO::PARAM_INT);

		if($stmt->execute())
			return 3;//"Registro exitoso";
		else
			return 4;//"No se realizó el registro, intentelo nuevamente";
		$stmt->close();
	}

	public static function obtenerIdJefe($empleado,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT id_jefe FROM $tabla WHERE id_empleado = :idUsuario");
		$stmt->bindParam(":idUsuario", $empleado, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	//ESTE METODO LO UTILIZO, PARA VERIFICAR SI LA PERSONA ES DE RH(ADMINISTRADOR)
	public static function obtenerIdRecursosHumanos($tabla,$id){ 
		$stmt = Conexion::conectar()->prepare("SELECT id_usuario FROM $tabla WHERE id_usuario = :usuario");
		$stmt->bindParam(":usuario", $id, PDO::PARAM_INT);
		$stmt -> execute();
		if($stmt -> fetch()[0])
			return TRUE;
		else 
			return FALSE;
		$stmt -> close();	
	}

	public static function obtenerIdRecursosHumanos2($tabla){ /// si se van a efinir más personal de RH para evaluar se debera modificar este metodo, AL IGUAL QUE LA TABLA DE DEPENDENCIAS_RH_AE
		$stmt = Conexion::conectar()->prepare("SELECT id_usuario FROM $tabla WHERE id_usuario NOT IN (168,171,351)");
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	//LEER LOS PERMISOS DE UN USUARIO PARA MOSTRARLOS EN EL CALENDARIO
	public static function actualizarCalendarioModels($datos,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT enterado_cambio,autorizacion_jefe,autorizacion_rh,fecha_fin,fecha_inicio,horario_inicio,horario_fin,tipo_permiso,tipo_solicitud,cuenta_sabado FROM $tabla WHERE (id_usuario = :idUsuario OR id_usuario_cambio = :idUsuario) AND (enterado_cambio IS NULL OR enterado_cambio = 1 OR enterado_cambio = 3)");
		$stmt->bindParam(":idUsuario", $datos, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	public static function getAniversario($datos,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT fecha_ingreso FROM $tabla WHERE id_usuario = :idUsuario");
		$stmt->bindParam(":idUsuario", $datos, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt->fetch()[0];
		$stmt -> close();
	}


	public static function mostrarSolicitudesPersonalAcargo($dato,$jefeOrh,$tabla,$tabla2){
		$consulta2='';
		$consulta = 'WHERE '.$tabla2.'.autorizacion_jefe = 0 AND '.$tabla.'.id_jefe = :jefe';
		if($jefeOrh){
			$consulta ='AND '.$tabla2.'.autorizacion_rh = 0';
			if($respuesta=AccesoRHespecial::pertenece($_SESSION['identificador']))//última modificación 23-may-2019
				$consulta2 = " INNER JOIN usuarios_ae ON $tabla2.id_usuario = usuarios_ae.id_usuario WHERE usuarios_ae.id_sucursal IN ($respuesta)";
		}
		$stmt = Conexion::conectar()->prepare("SELECT COUNT($tabla2.id_permiso) FROM $tabla2 INNER JOIN $tabla ON $tabla2.id_usuario = $tabla .id_empleado $consulta2 $consulta AND ($tabla2.enterado_cambio IS NULL OR $tabla2.enterado_cambio = 1)");
		$stmt->bindParam(":jefe", $dato, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	#####INDICA AL SOLICITANTE SI SOLICITUD FUE AUTORIZADA O CANCELADA
	public static function mostrarRespuestaSolicitud($empleado,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_permiso) FROM $tabla WHERE (id_usuario = :usuario AND visto = 0) OR (id_usuario_cambio = :usuario AND enterado_cambio = 3)");
		$stmt->bindParam(":usuario", $empleado, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	#INDICO SI EXISTE UNA SOLICITUD DE CAMBIO DE GUARDIA PARA UN USUARIO
	public static function mostrarCambiosDeGuardia($empleado,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT fecha_inicio,fecha_fin,id_usuario,id_permiso FROM $tabla WHERE id_usuario_cambio = :usuario AND enterado_cambio = 0");
		$stmt->bindParam(":usuario", $empleado, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();	
	}

	#TOTAL DE SOLICITUDES (EN LOS MARCADORES) DEPENDIENDO DE SI FUE LEIDA, AUTORIZADA, CANCELADA. (JEFES Y RH)
	public static function totalSolicitudesModels($idUsuario,$tipo,$tabla,$tabla2){
		$id_jefe = '';
		$rh = self::obtenerIdRecursosHumanos('dependencias_rh_ae',$idUsuario);//obtengo el id de la persona encargada de RH (encargada de aprobar permisos) 

		if( $rh === TRUE)
			$jefeOrh='autorizacion_rh';
		else{
			$id_jefe = 'AND id_jefe = :idUsuario';// ver unicamente empleados subordinados
			$jefeOrh='autorizacion_jefe';
		}

		$consulta = '';
		$consulta2 = " INNER JOIN usuarios_ae ON $tabla.id_usuario = usuarios_ae.id_usuario WHERE usuarios_ae.situacion = 1";
		if($tipo == 2)//autorizada
			$consulta .= ' AND '.$jefeOrh.'  = 2';
		else if($tipo == 3)//cancelada
			$consulta .= ' AND '.$jefeOrh.'  = 3';
		else if($tipo == 4)//pendientes por autorizar
			$consulta .= ' AND '.$jefeOrh.'  > 0';

			//permisos -- jefes
		if($respuesta=AccesoRHespecial::pertenece($_SESSION['identificador']))//última modificación 23-may-2019
			$consulta2 = " INNER JOIN usuarios_ae ON $tabla.id_usuario = usuarios_ae.id_usuario WHERE usuarios_ae.id_sucursal IN ($respuesta) AND usuarios_ae.situacion = 1";
		
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_permiso) FROM $tabla INNER JOIN $tabla2 ON $tabla.id_usuario = $tabla2.id_empleado $consulta2 $consulta $id_jefe AND ($tabla.enterado_cambio IS NULL OR $tabla.enterado_cambio IN (1,3))");
		
		if( $rh === FALSE)
			$stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
	
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

#MOSTRAR PERMISOS EN "ADMINISTAR PERMISOS" CADA USUARIO
#------------------------------------------------------------
	public static function mostrarPermisosModels($idUsuario,$limit,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT id_permiso, tipo_solicitud, tipo_permiso,fecha_inicio, autorizacion_jefe, autorizacion_rh,fecha_solicitud,enterado_cambio,visto FROM $tabla WHERE id_usuario = :idUsuario OR id_usuario_cambio = :idUsuario AND (enterado_cambio IS NULL OR enterado_cambio > 0) ORDER BY fecha_solicitud  DESC,id_permiso DESC $limit");
		$stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

#BUSCAR PERMISOS EN "ADMINISTRACIÓN DE SOLICITUDES"
#------------------------------------------------------------
//permisos,usuarios,jefes
	public static function buscarPermisosModels($data,$limit,$tabla,$tabla2,$tabla3){								
		$consulta= ' WHERE situacion = 1';//empleados activos
		if($data['situacion'] < 4)
			$consulta .= ' AND autorizacion_rh = '.$data['situacion'];
		else if($data['situacion'] < 8)
			$consulta .= ' AND autorizacion_jefe = '.(intval($data['situacion']) - 4);
		if($data['fecha'] != '')
			$consulta .= ' AND fecha_inicio = :fecha';
		if(!empty($data['nombreBuscar'])){
			$cadena = $data['nombreBuscar'];
			$consulta .=" AND CONCAT_WS(' ',nombre,paterno,materno) LIKE '%$cadena%' COLLATE utf8_general_ci";
		}
			
		if($data['sucursal'] != 0)
			$consulta .= ' AND id_sucursal = :sucursal';
		else if($respuesta=AccesoRHespecial::pertenece($_SESSION['identificador']))//última modificación 23-may-2019
			$consulta .= " AND id_sucursal IN ($respuesta)";


		if(!$data['tipoUsuario'])
			$consulta .= ' AND id_jefe = :jefe';
		
		$stmt = Conexion::conectar()->prepare("SELECT id_permiso, tipo_solicitud, tipo_permiso,fecha_inicio, autorizacion_jefe, autorizacion_rh, nombre, paterno, materno, id_sucursal  
											   FROM $tabla 
											   INNER JOIN $tabla2 ON $tabla.id_usuario = $tabla2.id_usuario 
											   INNER JOIN $tabla3 ON $tabla.id_usuario = $tabla3.id_empleado 
											   $consulta AND ($tabla.enterado_cambio IS NULL OR $tabla.enterado_cambio IN (1,3)) ORDER BY $tabla.fecha_inicio DESC, $tabla.id_permiso $limit");
		if(!$data['tipoUsuario'])
		 	$stmt->bindParam(":jefe", $data['usuarioPrincipal'], PDO::PARAM_INT);
		if($data['fecha'] != '')
			$stmt->bindParam(":fecha", $data['fecha'], PDO::PARAM_STR);
		if($data['sucursal']!= 0)
			$stmt->bindParam(":sucursal", $data['sucursal'], PDO::PARAM_INT);
		
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

//$tabla.enterado_cambio IS NULL OR $tabla.enterado_cambio = 1
	#TRADUCIR USUARIOS 
	#------------------------------------------------------------
	public static function traducirPermisos($dato,$dato2){
		$solicitud = array('PERMISO','VACACIONES','CAMBIO DE GUARDIA');
		$permiso = array('JUSTIFICANTE DE IMSS','JUSTIFICANTE DEL MÉDICO PARTICULAR','DÍA COMPLETO','MEDIO DÍA','PERIODO DE AUSENCIA POR HORAS','SALIDA TEMPRANO','BONO BIMESTRAL','LUTO','FALTA INJUSTIFICADA','SUSPENSIÓN','PATERNIDAD','MATERNIDAD');
		$tipoPermiso = $dato2 != '' ? ' ('. $permiso[$dato2-1].')' : '';
		return $solicitud[$dato-1].' '.$tipoPermiso;
	}

	public static function permisoUnicoModels($idPermiso,$tabla,$tabla2){
		$stmt = Conexion::conectar()->prepare("SELECT $tabla.*,$tabla2.id_jefe FROM $tabla INNER JOIN $tabla2 WHERE $tabla.id_usuario=$tabla2.id_empleado AND id_permiso = :idPermiso");
		$stmt->bindParam(":idPermiso", $idPermiso, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();	
	}

	#CAMBIA LA SOLICITUD (DEL EMPLEADO) A VISTA, YA SEA QUE SE HAYA AUTORIZADO O DENEGADO
	public static function solicitudVistaConfirmacion($idPermiso,$quien,$tabla){
		$consulta='visto = 1 ';
		if($quien === 'permuta')
			$consulta ='enterado_cambio = 1';
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $consulta WHERE id_permiso = :idPermiso");
		$stmt->bindParam(":idPermiso", $idPermiso, PDO::PARAM_INT);
		$stmt -> execute();
		return;
		$stmt -> close();	
	}

	#CAMBIO A LEIDO LAS SOLICITUDES VISTAS POR JEFE O RH
	#------------------------------------------------------------
	public static function solicitudVistaModels($idPermiso,$jefeOrh,$tabla){
		$consulta='autorizacion_jefe';
		if($jefeOrh == 'soy_rh')
			$consulta='autorizacion_rh';
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $consulta = 1 WHERE id_permiso = :idPermiso");
		$stmt->bindParam(":idPermiso", $idPermiso, PDO::PARAM_INT);
		$stmt -> execute();
		return;
		$stmt -> close();	
	}

	#AUTORIZO O CANCELO LAS SOLICITUDES RH
	#------------------------------------------------------------
	public static function actualizarFormularioSolicitudModels($datos,$tabla){
		$avisarPermuta = $datos['tipoDePermiso']==3 ? ',enterado_cambio = 3' : ''; //si es un cambio de guardia, entonces le aviso a la permuta que fue autorizado el cambio
		if($datos["autorizar"]){
			if($datos['tipoDePermiso']==2){//ejecutar el procedimiento sólo si se trata de vacaciones
				$totalDias = $datos["dias"];
				if($datos["contarSabado"])//puede ser uno o más sabados
					$totalDias = $totalDias + $datos["contarSabado"];
				
				if(  date($datos['fechaSolicitada']) <= date('2019-11-18')  &&  date($datos['fechaFinalizacion']) >= date('2019-11-18') )
					$totalDias -= 1;

				if(  date($datos['fechaSolicitada']) <= date('2019-12-25')  &&  date($datos['fechaFinalizacion']) >= date('2019-12-25') )
					$totalDias -= 1;

				if(  date($datos['fechaSolicitada']) <= date('2020-01-01')  &&  date($datos['fechaFinalizacion']) >= date('2020-01-01') )
					$totalDias -= 1;

				if(  date($datos['fechaSolicitada']) <= date('2020-02-03')  &&  date($datos['fechaFinalizacion']) >= date('2020-02-03') )
					$totalDias -= 1;

				if(  date($datos['fechaSolicitada']) <= date('2020-03-16')  &&  date($datos['fechaFinalizacion']) >= date('2020-03-16') )
					$totalDias -= 1;
				
				$incorporacion = (!empty($datos['fechaReincorporacion'])) ? $datos['fechaReincorporacion'] : NULL ;
				$stmt = Conexion::conectar()->prepare("CALL aprobarpermisoconsabados(:sueldo,:justificante,:inicio,:fin,:reincorporacion,:dias,:permiso,:usuario,:autorizacion,:sabados)");
				
				$stmt->bindParam(":sueldo", $datos["sueldo"], PDO::PARAM_INT);
				$stmt->bindParam(":justificante", $datos["justificante"], PDO::PARAM_INT);
				$stmt->bindParam(":inicio", $datos["fechaSolicitada"], PDO::PARAM_STR);
				$stmt->bindParam(":fin", $datos["fechaFinalizacion"], PDO::PARAM_STR);

				$stmt->bindParam(":reincorporacion", $incorporacion, PDO::PARAM_STR);
				
				$stmt->bindParam(":permiso", $datos["idSolicitud"], PDO::PARAM_INT);
				$stmt->bindParam(":dias", $totalDias, PDO::PARAM_INT);
				$stmt->bindParam(":usuario", $datos["idUsuario"], PDO::PARAM_INT);
				$stmt->bindParam(":autorizacion", $_SESSION['identificador'], PDO::PARAM_INT);
				$stmt->bindParam(":sabados", $datos['contarSabado'], PDO::PARAM_INT);
			}
			else{
				$incorporacion = (!empty($datos['fechaReincorporacion'])) ? ',fecha_incorporacion = :incorporacion' : '' ;
				$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
				autorizacion_rh = 2, 
				goce_sueldo = :sueldo, 
				justificante = :justificante, 
				fecha_inicio = :inicio, 
				fecha_fin = :fin,
				id_autorizacion_rh = :usuario,
				visto = 0
				$avisarPermuta
				$incorporacion 
				WHERE id_permiso = :idPermiso");
				$stmt->bindParam(":idPermiso", $datos["idSolicitud"], PDO::PARAM_INT);
				$stmt->bindParam(":sueldo", $datos["sueldo"], PDO::PARAM_INT);
				$stmt->bindParam(":justificante", $datos["justificante"], PDO::PARAM_INT);
				$stmt->bindParam(":inicio", $datos["fechaSolicitada"], PDO::PARAM_STR);
				$stmt->bindParam(":fin", $datos["fechaFinalizacion"], PDO::PARAM_STR);
				$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
				if(!empty($datos["fechaReincorporacion"]))
					$stmt->bindParam(":incorporacion", $datos["fechaReincorporacion"], PDO::PARAM_STR);
			}
			
		}
		else{
			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET autorizacion_rh = 3, visto = 0, motivo_denegacion = :negacion,id_autorizacion_rh = :usuario $avisarPermuta WHERE id_permiso = :idPermiso");
			$stmt->bindParam(":idPermiso", $datos["idSolicitud"], PDO::PARAM_INT);
			$stmt->bindParam(":negacion", $datos["negacion"], PDO::PARAM_STR);
			$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		}
		
		if($stmt -> execute())
		 	return 1;
		else
			return 0;
		$stmt -> close();	
	}


	#AUTORIZO O CANCELO LAS SOLICITUDES JEFE
	#------------------------------------------------------------
	public static function actualizarFormularioSolicitudModels2($datos,$tabla){
		$comentario='';
		if(!empty($datos["comentarioJefe"]))
			$comentario = ',comentario_jefe = :comentario';

		if($datos["autorizar"]){
			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
			autorizacion_jefe = 2
			$comentario
			WHERE id_permiso = :idPermiso");
			$stmt->bindParam(":idPermiso", $datos["idSolicitud"], PDO::PARAM_INT);
			if(!empty($datos["comentarioJefe"]))
				$stmt->bindParam(":comentario", $datos["comentarioJefe"], PDO::PARAM_STR);
		}
		else{
			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET autorizacion_jefe = 3, visto = 0, enterado_cambio = 3, motivo_denegacion = :negacion WHERE id_permiso = :idPermiso");
			$stmt->bindParam(":idPermiso", $datos["idSolicitud"], PDO::PARAM_INT);
			$stmt->bindParam(":negacion", $datos["negacion"], PDO::PARAM_STR);
		}
		
		if($stmt -> execute())
		 	return 1;
		else
			return 0;
		$stmt -> close();	
	}


	#MARCADORES USUARIO 
	#------------------------------------------------------------
	public static function marcadoresPermisosUsuario($usuario,$tipo,$tabla,$anio=''){
		$consulta = 'id_usuario = :idUsuario OR id_usuario_cambio = :idUsuario AND (enterado_cambio IS NULL OR enterado_cambio > 0)';
	
		$anio = $anio != '' ? ' AND YEAR(fecha_fin)='.intval($anio) : '';

		if($tipo == 0){//solicitud por autorizar
			$consulta = ' (id_usuario = :idUsuario OR id_usuario_cambio = :idUsuario) AND autorizacion_rh < 2 AND autorizacion_jefe != 3 AND ( enterado_cambio IS NULL OR enterado_cambio = 1)';
		}
		else if($tipo == 1){//vacaciones
			$consulta = ' id_usuario = :idUsuario AND tipo_solicitud = 2 AND autorizacion_rh = 2';
		}
		else if($tipo == 2){//Bono plus
			$consulta = ' id_usuario = :idUsuario AND tipo_solicitud = 1 AND tipo_permiso = 7 AND autorizacion_rh = 2';
		}
		else if($tipo == 3){//Faltas
			$consulta = ' id_usuario = :idUsuario AND tipo_solicitud = 1 AND tipo_permiso = 9 AND autorizacion_rh = 2';
		}
		else if($tipo == 4){//El resto de permisos (incluyen los cambios de guardia)
			$consulta = ' (id_usuario = :idUsuario OR id_usuario_cambio = :idUsuario)  AND ((tipo_solicitud = 1 AND tipo_permiso NOT IN(7,9)) OR tipo_solicitud = 3 )  AND autorizacion_rh = 2 ';
		}
		else if($tipo == 5){//Solicitudes autorizadas
			$consulta = ' (id_usuario = :idUsuario OR id_usuario_cambio = :idUsuario) AND autorizacion_rh = 2';
		}
		else if($tipo == 6){//Solicitudes canceladas
			$consulta = ' (id_usuario = :idUsuario OR id_usuario_cambio = :idUsuario) AND (autorizacion_rh = 3 OR autorizacion_jefe = 3 OR enterado_cambio = 2)';
		}

		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_permiso) FROM $tabla WHERE $consulta $anio");
		$stmt->bindParam(":idUsuario", $usuario, PDO::PARAM_INT);
		//$stmt->bindParam(":anio", $anio, PDO::PARAM_INT);

		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	#RESPUESTA AL CAMBIO DE GUARDIA
	#------------------------------------------------------------
	public static function reponderCambioGuardia($idPermiso,$respuesta,$tabla){
		$consulta='';//si no acepta le informo,si acepta no le informo y la solicitud se envia a su jefe y RH
		if($respuesta == 2)
			$consulta =', visto = 0';
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET enterado_cambio = :respuesta $consulta WHERE id_permiso = :idPermiso");
		$stmt->bindParam(":idPermiso", $idPermiso, PDO::PARAM_INT);
		$stmt->bindParam(":respuesta", $respuesta, PDO::PARAM_INT);
		if($stmt -> execute()){
			if($respuesta == 1){
				return  array('error'=>false,'tipo'=>'success','mensaje'=>'Aceptaste la solicitud','mensaje2'=>'Ahora falta la aprobación de tu Jefe y del departamento de Recursos Humanos');
			}
			else if($respuesta == 2){
				return  array('error'=>false,'tipo'=>'success','mensaje'=>'Cancelaste la solicitud','mensaje2'=>'Proceso finalizado');
			}
		}
			
		else
			return  array('error'=>true,'tipo'=>'error','mensaje'=>'Ocurrio un error intentelo nuevamente','mensaje2'=>'');
		$stmt -> close();	
	}

	#VERIFICAR QUE LA PERMUTA NO TENGA SOLICITUD PENDIENTE
	#------------------------------------------------------------
	public static function verificarCambiosModels($permuta,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_permiso) FROM $tabla WHERE id_usuario_cambio = :permuta AND enterado_cambio = 0");
		$stmt->bindParam(":permuta", $permuta, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	#INDICA LAS VACACIONES DISPONIBLES DE CADA USUARIO
	#------------------------------------------------------------
	public static function vacacionesDisponibles($usuario,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT vacaciones FROM $tabla WHERE id_usuario = :usuario");
		$stmt->bindParam(":usuario", $usuario, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	#INDICA LAS VACACIONES DISFRUTADAS DE CADA USUARIO DURANTE EL AÑO EN CURSO
	#------------------------------------------------------------
	public static function vacacionesDisfrutadas($usuario,$tabla,$anioActual = '2019'){
		$stmt = Conexion::conectar()->prepare("SELECT IF(SUM(cantidad) > 0,SUM(cantidad),0) FROM $tabla WHERE id_usuario = :usuario AND signo = 0 AND YEAR(fecha) = $anioActual");
		$stmt->bindParam(":usuario", $usuario, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	public static function agregadasPorAnio($usuario,$tabla,$anioActual){
		$stmt = Conexion::conectar()->prepare("SELECT IF(SUM(cantidad) > 0,SUM(cantidad),0) FROM $tabla WHERE id_usuario = :usuario AND signo = 1 AND YEAR(fecha) = $anioActual");
		$stmt->bindParam(":usuario", $usuario, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	
	#BORRAR PERMISO USUARIO
	#------------------------------------------------------------
	public static function borrarPermisoUsuario($id,$tabla){
		//return  array('error'=>false,'tipo'=>'success','mensaje'=>'La solicitud se borro exitosamente','mensaje2'=>'');
		$conexion = Conexion::conectar();
		$stmt = $conexion->prepare("SELECT autorizacion_jefe,autorizacion_rh FROM $tabla WHERE id_usuario = :usuario AND id_permiso = :id");
		$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt->bindParam(":id",$id, PDO::PARAM_INT);
		$stmt -> execute();
		$respuesta = $stmt -> fetch();
		if($respuesta['autorizacion_jefe'] == 2 || $respuesta['autorizacion_rh'] == 2)
			return  array('error'=>true,'tipo'=>'error','mensaje'=>'La solicitud ya fue evaluada y ya no puedes eliminarla','mensaje2'=>'si aún así quieres invalidarla ponte en contacto con el departamento de RH');

		$stmt = $conexion->prepare("DELETE FROM $tabla WHERE id_usuario = :usuario AND id_permiso = :id");
		$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt->bindParam(":id",$id, PDO::PARAM_INT);
		if($stmt -> execute())
			return  array('error'=>false,'tipo'=>'success','mensaje'=>'La solicitud se borro exitosamente','mensaje2'=>'');
		else
			return  array('error'=>true,'tipo'=>'error','mensaje'=>'Ocurrio un error','mensaje2'=>'¡ Intentalo nuevamente !');
		$stmt->$conexion();	
	}

	#INDICA LAS VACACIONES DISFRUTADAS DE CADA USUARIO DURANTE EL AÑO EN CURSO
	#------------------------------------------------------------
	public static function verificarPermisoPertenezcaUsusario($permiso,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT IF(COUNT(id_permiso) = 1,0,1) FROM $tabla WHERE id_usuario = :usuario AND id_permiso = :permiso");
		$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt->bindParam(":permiso", $permiso, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

}
