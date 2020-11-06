<?php

require_once "conexion.php";

class PaqueteriaModel{

	#MOSTRAR TODOS LOS PAQUETES INTERNOS
	#------------------------------------------------------------
	public static function buscarPaquetesInternos($data,$limit,$tabla){
		$consulta='';
		$rol = '(id_remitente = :usuario OR (id_destinatario = :usuario AND situacion >= 3))';
		if($data['fecha'] != '')
			$consulta .= ' AND DATE(fecha_registro) = :fecha';
		if($data['situacion'] != 0){
			if(intval($data['situacion']) <= 4){
				$rol = 'id_remitente = :usuario';
				$consulta .= ' AND situacion = '.$data['situacion'];
			}
			else if($data['situacion'] == 5)
				$rol = 'id_destinatario = :usuario AND situacion = 5';
			else if($data['situacion'] == 6)
				$rol = 'id_destinatario = :usuario AND situacion = 4';
			else if($data['situacion'] == 7)
				$rol = 'id_remitente = :usuario AND situacion = 5';
			else if($data['situacion'] == 8)
				$rol = 'id_destinatario = :usuario AND situacion = 3';
		}
			
		$stmt = Conexion::conectar()->prepare("SELECT id_paquete,id_destinatario,id_remitente,fecha_registro,situacion,estado_destinatario,estado_remitente FROM $tabla WHERE $rol $consulta ORDER BY fecha_registro DESC $limit");
		$stmt->bindParam(":usuario", $data['idUsuario'], PDO::PARAM_INT);
		if($data['fecha'] != '')
			$stmt->bindParam(":fecha", $data['fecha'], PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	#MOSTRAR TODOS LOS PAQUETES INTERNOS PRIVILEGIOS DE PAQUETERIA
	#------------------------------------------------------------
	public static function buscarPaquetesInternosPlus($data,$limit,$tabla,$tabla2,$tabla3){
		$rol="$tabla.id_remitente";
		$consulta = '';
		
		if($data['fecha'] != '')
			$consulta .= ' AND DATE(fecha_registro) = :fecha';
		if($data['situacion'] != 0){
			if(intval($data['situacion']) <= 4){
					$consulta .= " AND $tabla.situacion = ".$data['situacion'];
			}
			else if (intval($data['situacion']) == 8){
					$consulta .= " AND $tabla.situacion = 5";
			}
			else{
				$rol = "$tabla.id_destinatario";
				if(intval($data['situacion']) == 5)
					$consulta .= " AND $tabla.situacion > 2";
				else if(intval($data['situacion']) == 6)
					$consulta .= " AND $tabla.situacion = 5";
				else if(intval($data['situacion']) == 7)
					$consulta .= " AND $tabla.situacion = 4";

				else if(intval($data['situacion']) == 9)
					$consulta .= " AND $tabla.situacion = 3";

			}
		}
			
		$stmt = Conexion::conectar()->prepare("SELECT $tabla.id_paquete,$tabla.id_destinatario,$tabla.id_remitente,$tabla.fecha_registro,$tabla.situacion,$tabla.estado_recepcion,$tabla.estado_recepcion_destinatario FROM $tabla2 INNER JOIN $tabla 
											   WHERE $tabla2.id_usuario = $rol
											   AND $tabla2.id_sucursal IN ((SELECT sucursal_secundaria FROM $tabla3 WHERE sucursal_primaria = (SELECT id_sucursal FROM $tabla2 WHERE id_usuario = :usuario) )) $consulta ORDER BY $tabla.fecha_registro DESC $limit");
		
		$stmt->bindParam(":usuario", $data['idUsuario'], PDO::PARAM_STR);
		if($data['fecha'] != '')
			$stmt->bindParam(":fecha", $data['fecha'], PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	#MOSTRAR TODOS LOS PAQUETES EXTERNOS
	#------------------------------------------------------------
	public static function buscarPaquetesExternos($data,$limit,$tabla,$tabla2){
		$consulta='';
		if($data['fecha'] != '')
			$consulta .= " AND DATE($tabla.fecha_registro) = :fecha";
		if($data['situacion'] != 0)
			$consulta .= " AND $tabla.situacion = ".$data['situacion'];
	
		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			$tabla3 = Tablas::dependenciasPaqueteria();
			$stmt = Conexion::conectar()->prepare("SELECT $tabla.id_paquete,$tabla.compania,$tabla.contacto,$tabla.fecha_registro,$tabla.situacion FROM $tabla2 INNER JOIN $tabla 
												   WHERE $tabla2.id_usuario = $tabla.id_remitente
												   AND $tabla2.id_sucursal IN ((SELECT sucursal_secundaria FROM $tabla3 WHERE sucursal_primaria = (SELECT id_sucursal FROM $tabla2 WHERE id_usuario = :usuario) )) $consulta ORDER BY $tabla.fecha_registro DESC $limit");
		}
		else{
			$stmt = Conexion::conectar()->prepare("SELECT id_paquete,compania,contacto,fecha_registro,situacion,estado_remitente FROM $tabla WHERE id_remitente = :usuario $consulta ORDER BY fecha_registro DESC $limit");
		}

		$stmt->bindParam(":usuario", $data['idUsuario'], PDO::PARAM_INT);
		if($data['fecha'] != ''){
			$stmt->bindParam(":fecha", $data['fecha'], PDO::PARAM_STR);
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	#REGISTRAR PAQUETE INTERNO
	public static function crearInterno($datos,$tabla){
		$valor = 1;
		if($_SESSION['identificador2'] == Configuraciones::recepcion())
			$valor = 0;

		$mensajero=$mensajero2="";
		if(!empty($datos['mensajero'])){
			$mensajero="guia,mensajeria,situacion,";
			$mensajero2=":guia,2,3,";
			
		}
		
		$conexion = Conexion::conectar();
		$stmt = $conexion->prepare("INSERT INTO $tabla 
		(id_remitente,
		id_destinatario,
		tipo_envio,
		seguro,
		comentarios,
		descripcion,
		fecha_registro,
		$mensajero
		estado_recepcion) 
		VALUES 
		(:remitente,
		:destinatario,
		:tipo,
		:seguro,
		:comentarios,
		:descripcion,
		NOW(),
		$mensajero2
		:valor)");		
		$stmt->bindParam(":remitente", $datos["remitente"], PDO::PARAM_INT);
		$stmt->bindParam(":destinatario", $datos["destinatario"], PDO::PARAM_INT);
		$stmt->bindParam(":tipo", $datos["envio"], PDO::PARAM_INT);
		$stmt->bindParam(":seguro", $datos["seguro"], PDO::PARAM_INT);
		$stmt->bindParam(":comentarios", $datos["comentarios"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":valor", $valor, PDO::PARAM_INT);
		if(!empty($datos['mensajero']))
			$stmt->bindParam(":guia", $datos['mensajero'], PDO::PARAM_INT);

		if($stmt->execute()){
			$ultimo_id = $conexion->lastInsertId();
			return array('error'=>false,'mensaje'=>'El registro se realizo correctamente','mensaje2'=>'Tu número de envio es: '.$ultimo_id,'tipo'=>'success');
		}
			
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$conexion->close();
	}

	#REGISTRAR PAQUETE EXTERNO
	public static function crearExterno($datos,$tabla){
		$conexion = Conexion::conectar();
		$stmt = $conexion->prepare("INSERT INTO $tabla 
		(id_remitente,
		compania,
		contacto,
		tipo_envio,
		seguro,
		comentarios,
		fecha_registro,
		correo,
		telefono,
		codigo,
		estado,
		municipio,
		colonia,
		direccion,
		exterior,
		interior) 
		VALUES 
		(:remitente,
		:compania,
		:contacto,
		:tipo,
		:seguro,
		:comentarios,
		NOW(),
		:correo,
		:telefono,
		:codigo,
		:estado,
		:municipio,
		:colonia,
		:direccion,
		:exterior,
		:interior)");		

		$stmt->bindParam(":remitente", $datos["remitente"], PDO::PARAM_INT);
		$stmt->bindParam(":compania", $datos["compania"], PDO::PARAM_STR);
		$stmt->bindParam(":contacto", $datos["contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo", $datos["envio"], PDO::PARAM_INT);
		$stmt->bindParam(":seguro", $datos["seguro"], PDO::PARAM_INT);
		$stmt->bindParam(":comentarios", $datos["comentarios"], PDO::PARAM_STR);
		$stmt->bindParam(":correo", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":municipio", $datos["municipio"], PDO::PARAM_STR);
		$stmt->bindParam(":colonia", $datos["colonia"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":exterior", $datos["exterior"], PDO::PARAM_STR);
		$stmt->bindParam(":interior", $datos["interior"], PDO::PARAM_STR);

		if($stmt->execute()){
			$ultimo_id = $conexion->lastInsertId();
			return array('error'=>false,'mensaje'=>'El registro se realizo correctamente','mensaje2'=>'Tu número de envio es: '.$ultimo_id.'-E','tipo'=>'success');
		}
			
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	#DETALLE PAQUETE INTERNO Y EXTERNO
	public static  function detallePaqueteInterno($idPaquete,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id_paquete = :paquete");
		$stmt->bindParam(":paquete", $idPaquete, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
	}

	#INDICA LA CANTIDAD DE PAQUETES INTERNOS DEPENDIENDO DE SU SITUACION
	public static function marcadoresInternos($situacion,$entradaSalida,$tabla,$tabla2){

		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			$tabla3 = Tablas::dependenciasPaqueteria();
			$consulta='';
			$rol="$tabla.id_remitente";

			if($situacion == 0 AND $entradaSalida){
				$rol="$tabla.id_destinatario";
				$consulta = " AND $tabla.situacion >= 3";
			}
			else if($situacion == 1)
				$consulta = " AND $tabla.situacion = 1";
			else if($situacion == 2)
				$consulta = " AND $tabla.situacion = 2";
			else if($situacion == 3){
				if($entradaSalida)
					$rol="$tabla.id_destinatario";
				$consulta = " AND $tabla.situacion = 3";
			}
			else if($situacion == 4){
				if($entradaSalida)
					$rol="$tabla.id_destinatario";
				$consulta = " AND $tabla.situacion = 4";
			}
			else if($situacion == 5){
				if($entradaSalida)
					$rol="$tabla.id_destinatario";
				$consulta = " AND $tabla.situacion = 5";
			}

			$stmt = Conexion::conectar()->prepare("SELECT COUNT($tabla.id_paquete) FROM $tabla INNER JOIN $tabla2 WHERE $tabla2.id_usuario = $rol AND $tabla2.id_sucursal IN ((SELECT sucursal_secundaria FROM $tabla3 WHERE sucursal_primaria = (SELECT id_sucursal FROM $tabla2 WHERE id_usuario = :usuario) )) $consulta");
			$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
			$stmt -> execute();
			return $stmt -> fetch()[0];
			$stmt -> close();
		}
		else{
			$consulta='';
			$rol='id_remitente = :rol';

			if($situacion == 0 AND $entradaSalida){
				$rol='id_destinatario = :rol';
				$consulta = " AND situacion >= 3";
			}

			else if($situacion == 1)
				$consulta = " AND situacion = 1";
			else if($situacion == 2)
				$consulta = " AND situacion = 2";
			else if($situacion == 3){
				if($entradaSalida)
					$rol='id_destinatario = :rol';
				$consulta = " AND situacion = 3";
			}
			else if($situacion == 4){
				if($entradaSalida)
					$rol='id_destinatario = :rol';
				$consulta = " AND situacion = 4";
			}

			else if($situacion == 5){
				if($entradaSalida)
					$rol='id_destinatario = :rol';
				$consulta = " AND situacion = 5";
			}

			$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_paquete) FROM $tabla WHERE $rol $consulta");
			$stmt->bindParam(":rol", $_SESSION['identificador'], PDO::PARAM_INT);
			$stmt -> execute();
			return $stmt -> fetch()[0];
			$stmt -> close();
		}
	}

	#INDICA LA CANTIDAD DE PAQUETES EXTERNOS DEPENDIENDO DE SU SITUACION
	public static function marcadoresExternos($situacion,$tabla,$tabla2){
		
		$consulta = '';
		if($situacion != 0){
			$consulta = " AND $tabla.situacion = ".intval($situacion);
		}

		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			$tabla3 = Tablas::dependenciasPaqueteria();
			$stmt = Conexion::conectar()->prepare("SELECT COUNT($tabla.id_paquete) FROM $tabla INNER JOIN $tabla2 ON $tabla2.id_usuario = $tabla.id_remitente WHERE $tabla2.id_sucursal IN ((SELECT sucursal_secundaria FROM $tabla3 WHERE sucursal_primaria = (SELECT id_sucursal FROM $tabla2 WHERE id_usuario = :usuario) )) $consulta");
		}
		else{
			$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_paquete) FROM $tabla WHERE id_remitente = :usuario $consulta");
		}
	
		$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();	
	}

	#SE ASIGNA LA PAQUETERIA Y NUMERO DE GUIA AL PAQUETE EXTERNO
	public static function actualizarPaqueteExterno($idPaquete,$paqueteria,$guia,$tabla){

		$actualizarEstadoRemitente='estado_remitente = 1,';
	
		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			$idRemitente = self::verificarRecepcionExterno($idPaquete,$tabla);
			if( $idRemitente == $_SESSION['identificador'] )
				$actualizarEstadoRemitente = '';
		}
		
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		mensajeria = :mensajeria, 
		guia = :guia,
		situacion = 3,
		$actualizarEstadoRemitente
		fecha_recolectado = NOW()
		WHERE id_paquete = :id AND situacion !=2");

		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		$stmt->bindParam(":mensajeria", $paqueteria, PDO::PARAM_INT);
		$stmt->bindParam(":guia", $guia, PDO::PARAM_STR);
	
		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	#SE ASIGNA LA PAQUETERIA Y NUMERO DE GUIA AL PAQUETE INTERNO
	public static function actualizarPaqueteInterno($idPaquete,$paqueteria,$guia,$tabla){
		$actualizarEstadoRemitente='estado_remitente = 1,';
		$actualizarEstadoDestinatario='estado_destinatario = 1,';
		//$actualizarEstadoRecepcionista = 'estado_recepcion_destinatario = 1,';
		$respuesta = self::verificarRecepcion($idPaquete,$tabla);
		
		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			if( $respuesta['id_remitente'] == $_SESSION['identificador'] ){
				$actualizarEstadoRemitente = '';
				//$actualizarEstadoRecepcionista = '';
			}
				
		}
		$verificarPrivilegios = self::verificarPrivilegiosRemitente($respuesta['id_destinatario'],Tablas::usuarios()); //verifico si el remitente tiene privilegios de recepcionista para no mandarle notificación única 
		if( Configuraciones::recepcion() == $verificarPrivilegios){
			$actualizarEstadoDestinatario = '';
			//$actualizarEstadoRecepcionista = '';
		}

		//$actualizarEstadoRecepcionista
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		mensajeria = :mensajeria, 
		guia = :guia,
		situacion = 3,
		$actualizarEstadoRemitente
		$actualizarEstadoDestinatario
		estado_recepcion_destinatario = 1,
		fecha_por_recolectar = NOW()
		WHERE id_paquete = :id AND situacion !=2");

		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		$stmt->bindParam(":mensajeria", $paqueteria, PDO::PARAM_INT);
		$stmt->bindParam(":guia", $guia, PDO::PARAM_STR);
	
		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	#SE MUESTRAN LAS PAQUETERIAS POSIBLES A SELECCIONAR
	public static function paqueterias($tabla){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY nombre");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	#OBTENGO EL NOMBRE DE LA PAQUETERIA DEPENDIENDO DE SU ID ASIGNADO EN EL SELECT
	public static function nombrePaqueteria($id,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT nombre FROM $tabla WHERE id_paqueteria = :id");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	#SE FINALIZA EL PROCESO DE ENVIO DEL PAQUETE INTERNO
	public static function finalizarPaqueteInterno($idPaquete,$tabla){
		$actualizarEstadoRemitente='estado_remitente = 1,';

		$respuesta = self::verificarRecepcion($idPaquete,$tabla);
		$verificarPrivilegios = self::verificarPrivilegiosRemitente($respuesta['id_remitente'],Tablas::usuarios()); //verifico si el remitente tiene privilegios de recepcionista para no mandarle notificación única 

		if( Configuraciones::recepcion() == $verificarPrivilegios )
			$actualizarEstadoRemitente = '';
	
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		situacion = 4,
		$actualizarEstadoRemitente
		fecha_recibido = NOW()
		WHERE id_paquete = :id");
		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	#SE FINALIZA EL PROCESO DE ENVIO DEL PAQUETE INTERNO
	public static function cancelarPaqueteInterno($idPaquete,$tabla){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		situacion = 2,
		fecha_cancelacion = NOW()
		WHERE id_paquete = :id");
		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	#SE FINALIZA EL PROCESO DE ENVIO DEL PAQUETE EXTERNO
	public static function finalizarPaqueteExterno($idPaquete,$tabla){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		situacion = 4,
		fecha_recibido = NOW()
		WHERE id_paquete = :id");
		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	#SE FINALIZA EL PROCESO DE ENVIO DEL PAQUETE EXTERNO
	public static function cancelarPaqueteExterno($idPaquete,$tabla){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		situacion = 2,
		fecha_cancelacion = NOW()
		WHERE id_paquete = :id");
		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	public static function validarEntradaSalidaRecepcion($sucursal,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT sucursal_secundaria FROM $tabla WHERE sucursal_primaria = :sucursal");
		$stmt->bindParam(":sucursal", $sucursal, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	#VERIFICAR SI EXISTEN CAMBIOS EN PAQUETES INTERNOS
	public static function comprobarExistenciaPaquetes($tabla){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_paquete) FROM $tabla WHERE (id_destinatario = :destinatario AND estado_destinatario = 1) OR (id_remitente  = :destinatario AND estado_remitente = 1)");
		$stmt->bindParam(":destinatario", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}


	public static function comprobarExistenciaPaquetesRecepcion($tabla,$tabla2,$tabla3){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_paquete) FROM $tabla WHERE 
												 ( id_remitente IN (  SELECT id_usuario FROM $tabla3 WHERE id_sucursal IN (SELECT sucursal_secundaria FROM $tabla2 WHERE sucursal_primaria = (SELECT id_sucursal FROM $tabla3 WHERE id_usuario = :user) ) )  AND estado_recepcion = 1)
												 OR
												 ( id_destinatario IN (  SELECT id_usuario FROM $tabla3 WHERE id_sucursal IN (SELECT sucursal_secundaria FROM $tabla2 WHERE sucursal_primaria = (SELECT id_sucursal FROM $tabla3 WHERE id_usuario = :user) ) )  AND estado_recepcion_destinatario = 1)
												 ");
		$stmt->bindParam(":user", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	#VERIFICAR SI EXISTEN CAMBIOS EN PAQUETES EXTERNOS
	public static function comprobarExistenciaPaquetesExternos($tabla){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_paquete) FROM $tabla WHERE (id_remitente = :remitente AND estado_remitente = 1)");
		$stmt->bindParam(":remitente", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	#INDICA QUE EL USUARIO YA ESTA ENTERADO DE UN CAMBIO EN EL ESTADO DEL PAQUETE
	public static function paqueteVisto($idPaquete,$rol,$tabla){
		$usuario ='estado_destinatario';
		if(!$rol){
			$usuario ='estado_remitente';
		}
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		$usuario = 0
		WHERE id_paquete = :id");
		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		$stmt->execute();
			return;
		$stmt->close();
	}


	#INDICA QUE EL DE RECEPCION YA ESTA ENTERADO DE UN CAMBIO EN EL ESTADO DEL PAQUETE
	public static function paqueteVistoRecepcionista($idPaquete,$tabla,$estado){
		$campo='estado_recepcion_destinatario';
		if($estado)
			$campo='estado_recepcion';
		
			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $campo = 0 WHERE id_paquete = :id");
		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		$stmt->execute();
			return;
		$stmt->close();
	}



	#INDICA QUE EL USUARIO YA ESTA ENTERADO DE UN CAMBIO EN EL ESTADO DEL PAQUETE EXTERNO
	public static function paqueteExternoVisto($idPaquete,$tabla){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		estado_remitente = 0
		WHERE id_paquete = :id");
		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		$stmt->execute();
			return;
		$stmt->close();
	}

	#CUESTIONARIO RECEPCION PAQUETE INTERNO
	public static function formularioPaqueteInterno($idPaquete,$estadoRecibido,$completoRecibido,$comentarioRecibido,$tabla){
		$comentarioRecibido= empty($comentarioRecibido) ? NULL : $comentarioRecibido;
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		situacion_paquete = :situacion,
		estado_paquete = :estado,
		comentarios_recibido = :comentarios
		WHERE id_paquete = :id");
		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);
		$stmt->bindParam(":situacion", $completoRecibido, PDO::PARAM_INT);
		$stmt->bindParam(":estado", $estadoRecibido, PDO::PARAM_INT);
		$stmt->bindParam(":comentarios", $comentarioRecibido, PDO::PARAM_STR);
		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	#PAQUETE INTERNO EN RUTA
	public static function enviadoPaqueteInterno($idPaquete,$tabla){
		$actualizarEstadoRemitente='estado_remitente = 1,';
		$actualizarEstadoDestinatario='estado_destinatario = 1,';
		$respuesta = self::verificarRecepcion($idPaquete,$tabla);
		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			if( $respuesta['id_remitente'] == $_SESSION['identificador'] )
				$actualizarEstadoRemitente = '';
		}
		$verificarPrivilegios = self::verificarPrivilegiosRemitente($respuesta['id_destinatario'],Tablas::usuarios()); //verifico si el remitente tiene privilegios de recepcionista para no mandarle notificación única 
		if( Configuraciones::recepcion() == $verificarPrivilegios )
			$actualizarEstadoDestinatario = '';
		

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		situacion = 5,
		$actualizarEstadoRemitente
		$actualizarEstadoDestinatario
		estado_recepcion_destinatario = 1,
		fecha_envio = NOW()
		WHERE id_paquete = :id");

		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);

		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}

	#PAQUETE EXTERNO EN RUTA
	public static function enviadoPaqueteExterno($idPaquete,$tabla){
		$actualizarEstadoRemitente='estado_remitente = 1,';
	
		if($_SESSION['identificador2'] == Configuraciones::recepcion()){
			$idRemitente = self::verificarRecepcionExterno($idPaquete,$tabla);
			if( $idRemitente == $_SESSION['identificador'] )
				$actualizarEstadoRemitente = '';
		}
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		situacion = 5,
		$actualizarEstadoRemitente
		fecha_envio = NOW()
		WHERE id_paquete = :id");

		$stmt->bindParam(":id", $idPaquete, PDO::PARAM_INT);

		if($stmt->execute())
			return array('error'=>false,'mensaje'=>'Los datos se actualizaron correctamente','mensaje2'=>'','tipo'=>'success');
		else
			return array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error');
		$stmt->close();
	}
	
	public static function verificarRecepcion($idPaquete,$tabla){//verificar que el remitente o destinatario no sea el personal de recepción, para no mostrarle notificaciones individuales, sino que las visualice en el panel general
		$stmt = Conexion::conectar()->prepare("SELECT id_remitente,id_destinatario FROM $tabla WHERE id_paquete = :paquete");
		$stmt->bindParam(":paquete", $idPaquete, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
	}

	public static function verificarRecepcionExterno($idPaquete,$tabla){//verificar que el remitente o destinatario no sea el personal de recepción, para no mostrarle notificaciones individuales, sino que las visualice en el panel general
		$stmt = Conexion::conectar()->prepare("SELECT id_remitente FROM $tabla WHERE id_paquete = :paquete");
		$stmt->bindParam(":paquete", $idPaquete, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function verificarPrivilegiosRemitente($idusuario,$tabla){//verificar que el remitente o destinatario no sea el personal de recepción, para no mostrarle notificaciones individuales, sino que las visualice en el panel general
		$stmt = Conexion::conectar()->prepare("SELECT tipo_acceso FROM $tabla WHERE id_usuario = :usuario");
		$stmt->bindParam(":usuario",$idusuario, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function obtenerSucursalRemitente($paquete,$tabla,$tabla2){
		$stmt = Conexion::conectar()->prepare("SELECT id_sucursal FROM $tabla WHERE id_usuario = (SELECT id_remitente FROM $tabla2 WHERE id_paquete = $paquete)");
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function obtenerSucursalesRecepcion($tabla,$tabla2){
		$stmt = Conexion::conectar()->prepare("SELECT sucursal_secundaria FROM $tabla WHERE sucursal_primaria = (SELECT id_sucursal FROM $tabla2 WHERE id_usuario = :user)");
		$stmt->bindParam(":user",$_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	public static function getMensajerosInternos($zona,$tabla,$tabla2){//posibles mensajeros
		$stmt = Conexion::conectar()->prepare("SELECT id_usuario,nombre,paterno,materno FROM $tabla INNER JOIN $tabla2 ON $tabla.id_usuario = $tabla2.mensajero WHERE dependencia = :zona ORDER BY nombre,paterno,materno");
		$stmt->bindParam(":zona",$zona, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	public static function getMensajero($id,$tabla){ //el mensajero en especifico
		$stmt = Conexion::conectar()->prepare("SELECT CONCAT(nombre,' ',materno,' ',paterno) AS nombre FROM $tabla WHERE id_usuario = :id");
		$stmt->bindParam(":id",$id, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}
	
}
