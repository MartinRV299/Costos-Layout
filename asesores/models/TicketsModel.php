<?php
require_once "conexion.php";
class TicketsModel{
   
    public static function nuevoTicket($data,$tabla){
		$segmento='';
		$segmento2='';
		$imagen='';
		$imagen2='';
		$documento='';
		$documento2='';
		$usuarioCreaTicket='';
		$usuarioCreaTicket2='';
		if($data['segmento'] != NULL){
			$segmento='segmento,';
			$segmento2=':segmento,';
		}
		if($data['imagenNombre'] != NULL){
			$imagen='imagen,';
			$imagen2=':imagen,';
		}
		if($data['documentoNombre'] != NULL){
			$documento='documento,';
			$documento2=':documento,';
		}
		if($data['idEmpleado'] != NULL){
			$usuarioCreaTicket='genera,';
			$usuarioCreaTicket2="1,";
		}
		$conexion = Conexion::conectar();
		$stmt = $conexion->prepare("INSERT INTO $tabla 
		(area,
		subcategoria,
		$segmento
		$imagen
		$documento
		asunto,
		descripcion,
        fecha_registro,
		$usuarioCreaTicket
		id_usuario) 
		VALUES 
		(:area,
		:subcategoria,
		$segmento2
		$imagen2
		$documento2
		:asunto,
		:descripcion,
		NOW(),
		$usuarioCreaTicket2
		:usuario)");		
		$stmt->bindParam(":area", $data["area"], PDO::PARAM_INT);
		$stmt->bindParam(":subcategoria", $data["subCategoria"], PDO::PARAM_INT);
		$stmt->bindParam(":asunto", $data["asunto"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $data["descripcion"], PDO::PARAM_STR);
		if($data['idEmpleado'] != NULL)
			$stmt->bindParam(":usuario", $data['idEmpleado'], PDO::PARAM_INT);
		else
			$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		if($data['segmento'] != NULL)
			$stmt->bindParam(":segmento", $data['segmento'], PDO::PARAM_INT);
		if($data['imagenNombre'] != NULL)
			$stmt->bindParam(":imagen", $data['imagenNombre'], PDO::PARAM_STR);
		if($data['documentoNombre'] != NULL)
			$stmt->bindParam(":documento", $data['documentoNombre'], PDO::PARAM_STR);
		if($stmt->execute()){
			$ultimo_id = $conexion->lastInsertId();
			return json_encode(array('error'=>false,'mensaje'=>"Ticket: $ultimo_id",'mensaje2'=>'El departamento de sistemas pronto se pondra en contacto contigo','tipo'=>'success','folio'=>$ultimo_id));
		}
		else{
			if($data['imagenNombre'] != NULL)
				unlink("../views/imagenes-tickets/".$data["imagenNombre"]);
			if($data['documentoNombre'] != NULL)
				unlink("../views/documentos-tickets/".$data["documentoNombre"]);
			return json_encode(array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error','folio'=>NULL));
		}	
		$conexion->close();
	}
	

	public static function mostrarColaTickets($situacion,$asignados,$tabla){
		$categoria='';

		$ticketSinCerrar='AND ( (DATE(fecha_finalizado) = CURDATE()  AND (reabrir <> 2 OR reabrir IS NULL) ) OR ( DATE(ultima_fecha_cierre) = CURDATE() AND reabrir = 0) )';
		
		if($situacion < 2){ // 2 = FINALIZADOS
			if($situacion==0)
				$ticketSinCerrar='';
			else
				$ticketSinCerrar='OR reabrir = 2 ';
		}
		
		if(Configuraciones::administrador() != $_SESSION['identificador2']){//super usuario
			$categoria = 'AND area = '.AccesoSoporte::usuarios($_SESSION['identificador']);
			if($asignados)
				$asignados = 'AND id_atiende_ticket ='.$_SESSION['identificador'];
			else
				$asignados='';
		}
		else{
			$asignados='';
		}
		
		$stmt = Conexion::conectar()->prepare("SELECT id_ticket,id_usuario,asunto,prioridad,area,id_atiende_ticket,fecha_finalizado,fecha_atendido,reabrir,ultima_fecha_cierre FROM $tabla WHERE ( situacion = :situacion $ticketSinCerrar) $categoria $asignados ORDER BY id_ticket");	
		$stmt->bindParam(":situacion", $situacion, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	public static function historialTickets($fecha,$usuario,$limit,$tabla,$tabla2){
		$consulta='';

		if(!empty($fecha)){
			$consulta.=' AND DATE(fecha_finalizado) = :fecha';
			if($fecha == date('Y-m-d'))
				$consulta=" AND DATE(fecha_finalizado) = '2099-01-01'";
		}

		if(Configuraciones::administrador() != $_SESSION['identificador2'])
			$consulta = ' AND area = '.AccesoSoporte::usuarios($_SESSION['identificador']);
	
		if(!empty($usuario)){
			$consulta .=" AND (CONCAT_WS(' ',nombre,paterno,materno) LIKE '%$usuario%' COLLATE utf8_general_ci OR asunto LIKE '%$usuario%' COLLATE utf8_general_ci OR descripcion LIKE '%$usuario%' COLLATE utf8_general_ci )";
			//$consulta .= ' AND (nombre LIKE "%'.$usuario.'%" COLLATE utf8_general_ci OR paterno LIKE "%'.$usuario.'%"  COLLATE utf8_general_ci OR materno LIKE "%'.$usuario.'%"  COLLATE utf8_general_ci OR asunto LIKE "%'.$usuario.'%"  COLLATE utf8_general_ci OR descripcion LIKE "%'.$usuario.'%"  COLLATE utf8_general_ci)';
		}
		

		/*$stmt = Conexion::conectar()->prepare("SELECT id_ticket,$tabla.id_usuario,asunto,numero_ticket,prioridad,area,id_atiende_ticket,fecha_finalizado,fecha_atendido,nombre,paterno,materno FROM $tabla INNER JOIN $tabla2 ON $tabla.id_usuario = $tabla2.id_usuario
												WHERE $tabla.situacion=2 AND DATE(fecha_finalizado) != CURDATE()  $consulta ORDER BY DATE(fecha_finalizado) DESC,$tabla.area,numero_ticket $limit");*/
												
		$stmt = Conexion::conectar()->prepare("SELECT id_ticket,$tabla.id_usuario,asunto,prioridad,area,id_atiende_ticket,fecha_finalizado,fecha_atendido,nombre,paterno,materno FROM $tabla INNER JOIN $tabla2 ON $tabla.id_usuario = $tabla2.id_usuario
												WHERE $tabla.situacion=2 AND DATE(fecha_finalizado) != CURDATE()  $consulta ORDER BY id_ticket DESC,DATE(fecha_finalizado) DESC $limit");	
												
		if(!empty($fecha) AND $fecha != date('Y-m-d'))
			$stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	public static function historialTicketsUsuario($tabla,$tabla2){//fecha_finalizado,fecha_atendido,prioridad,$tabla.id_usuario
		$limit = 'LIMIT 0,30';
		$stmt = Conexion::conectar()->prepare("SELECT id_ticket,asunto,area,id_atiende_ticket,fecha_registro,nombre,paterno,materno,$tabla.situacion,visto FROM $tabla INNER JOIN $tabla2 ON $tabla.id_usuario = $tabla2.id_usuario
												WHERE $tabla.id_usuario = :user ORDER BY fecha_registro DESC $limit");						
		$stmt->bindParam(":user", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}
	
	public static function mostaraDatosTicket($data,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id_ticket = :id");	
		$stmt->bindParam(":id", $data, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
	}
	
	public static function asignarTicket($ticket,$area,$atiende,$tabla){
		//$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_atiende_ticket = :atiende,situacion=1,fecha_atendido = NOW() WHERE numero_ticket = :id AND DATE(fecha_registro) = CURDATE() AND area=:area");	
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_atiende_ticket = :atiende,situacion=1,visto=1,mensaje=1,fecha_atendido = NOW() WHERE id_ticket = :id AND fecha_atendido IS NULL AND area=:area");
		$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
		//$stmt->bindParam(":atiende",$_SESSION['identificador'], PDO::PARAM_INT);
		$stmt->bindParam(":atiende",$atiende, PDO::PARAM_INT);
		$stmt->bindParam(":area",$area, PDO::PARAM_INT);
		if($stmt -> execute())
			return 1;
		else
			return 0;
		$stmt -> close();
	}
	
	public static function cerrarTicket($ticket,$solucion,$causa,$problema,$tabla){//cerrar ticket por primera vez
		$condicion='';
		if(!empty($solucion))
			$condicion.=',solucion = :solucion';
		if(!empty($causa))
			$condicion.=',causa = :causa';
		if(!empty($problema))
			$condicion.=',problema = :problema';

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET situacion=2,visto=1,mensaje=1,fecha_finalizado = NOW() $condicion WHERE id_ticket = :id");	
		$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
		if(!empty($solucion))
			$stmt->bindParam(":solucion", $solucion, PDO::PARAM_STR);
		if(!empty($causa))
			$stmt->bindParam(":causa", $causa, PDO::PARAM_STR);
		if(!empty($problema))
			$stmt->bindParam(":problema", $problema, PDO::PARAM_STR);

		if($stmt -> execute())
			return 1;
		else
			return 0;
		$stmt -> close();
	}

	public static function actualizarSolucion($ticket,$solucion,$causa,$problema,$tabla){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET solucion = :solucion,causa = :causa, problema = :problema WHERE id_ticket = :id");	
		$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
		$stmt->bindParam(":solucion", $solucion, PDO::PARAM_STR);
		$stmt->bindParam(":causa", $causa, PDO::PARAM_STR);
		$stmt->bindParam(":problema", $problema, PDO::PARAM_STR);

		if($stmt -> execute())
			return 1;
		else
			return 0;
		$stmt -> close();
	}

	public static function datosParaGraficar($id,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_ticket) FROM $tabla WHERE id_atiende_ticket = :id AND DATE(fecha_registro) = CURDATE() AND situacion = 2");	
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function datosGraficasBarras($categoria,$tabla){
		//$stmt = Conexion::conectar()->prepare("SELECT subcategoria,COUNT(id_ticket) AS total FROM $tabla WHERE DATE(fecha_registro) = CURDATE() AND situacion = 2 AND area = :cat GROUP BY subcategoria LIMIT 6");
		if($categoria == 2){
			$stmt = Conexion::conectar()->prepare("SELECT subcategoria,segmento,COUNT(id_ticket) AS total FROM $tabla WHERE DATE(fecha_registro) = CURDATE() AND situacion = 2 AND area = :cat GROUP BY subcategoria,segmento LIMIT 6");
		}
		else{
			$stmt = Conexion::conectar()->prepare("SELECT subcategoria,COUNT(id_ticket) AS total FROM $tabla WHERE DATE(fecha_registro) = CURDATE() AND situacion = 2 AND area = :cat GROUP BY subcategoria LIMIT 6");
		}
		$stmt->bindParam(":cat", $categoria, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	public static function reabrirTicket($ticket,$tabla){ //usuario solicita se reabra ticket
		$conexion = Conexion::conectar();
		$stmt = $conexion->prepare("SELECT reabrir from $tabla WHERE id_ticket = :id");	
		$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
		$stmt -> execute();
		$condicion = intval($stmt -> fetch()[0]);
		if( $condicion === 1 || $condicion === 2){
			return json_encode(array('error'=>false,'mensaje'=>'¡La solicitud ya esta siendo atendida!','mensaje2'=>'El departamento de sistemas pronto se pondra en contacto contigo','tipo'=>'success','status'=>false));
		}
		else{
			$stmt = $conexion->prepare("UPDATE $tabla SET reabrir=1,fecha_registro_reapertura=NOW() WHERE id_ticket = :id");	
			$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
			if($stmt -> execute())
				return json_encode(array('error'=>false,'mensaje'=>'Tu solicitud ha sido registrada correctamente, ticket con el folio no. '.$ticket,'mensaje2'=>'El departamento de sistemas pronto se pondra en contacto contigo','tipo'=>'success','status'=>true));
			else
				return json_encode(array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error','status'=>false));
		}
		$conexion -> close();
	}

	public static function reabrirTicketSoporte($ticket,$flag,$motivo,$tabla){//Soporte reabrir Ticket
			if(intval($flag)){//reabro el ticket
				$aperturaTicket=''; //en caso de que el ticket se abrio por solicitud del usuario conservo la fecha de reapertura
				$conexion=Conexion::conectar();
				$stmt = $conexion->prepare("SELECT id_atiende_ticket,fecha_registro_reapertura from $tabla WHERE id_ticket = :id");	
				$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
				$stmt -> execute();
				$respuesta = $stmt -> fetch();
				if( $respuesta['fecha_registro_reapertura'] == NULL )
					$aperturaTicket = ',fecha_registro_reapertura=NOW()';
			
				$usuarioAnteriorSoporte=intval($respuesta['id_atiende_ticket']);
				$stmt = $conexion->prepare("UPDATE $tabla SET reabrir = 2,fecha_registro_reatendido=NOW(),motivo_apertura=:motivo,id_atiende_ticket=:user,id_usuario_anterior=$usuarioAnteriorSoporte $aperturaTicket WHERE id_ticket = :id");		
				$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
				$stmt->bindParam(":motivo", $motivo, PDO::PARAM_STR);
				$stmt->bindParam(":user", $_SESSION['identificador'], PDO::PARAM_INT);

				if($stmt -> execute())
					return json_encode(array('error'=>false,'mensaje'=>'ok','mensaje2'=>'','tipo'=>'success'));
				else
					return json_encode(array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error'));
				$conexion -> close();
			}
			else{//no reabro el ticket he inmediatamente lo cierro
				$conexion=Conexion::conectar();
				$stmt = $conexion->prepare("SELECT id_atiende_ticket,fecha_registro_reapertura from $tabla WHERE id_ticket = :id");	
				$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
				$stmt -> execute();
				$respuesta = $stmt -> fetch();

				$usuarioAnteriorSoporte=intval($respuesta['id_atiende_ticket']);
				$stmt = $conexion->prepare("UPDATE $tabla SET fecha_registro_reatendido=NOW(),id_atiende_ticket=:user,motivo_apertura=:motivo,id_usuario_anterior=$usuarioAnteriorSoporte WHERE id_ticket = :id");		
				$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
				$stmt->bindParam(":user", $_SESSION['identificador'], PDO::PARAM_INT);
				$stmt->bindParam(":motivo", $motivo, PDO::PARAM_STR);

				if($stmt -> execute())
					return self::CerraTicketReabierto($ticket,$tabla);
				else
					return json_encode(array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error'));
				$conexion -> close();
			}
	}

	public static function CerraTicketReabierto($ticket,$tabla){ //finalizamos el ticket que se reabrio o que se solicito por parte del usuario que se reabriera
		$conexion=Conexion::conectar();
		$stmt = $conexion->prepare("SELECT fecha_registro_reapertura,fecha_registro_reatendido,id_usuario_anterior,motivo_apertura from $tabla WHERE id_ticket = :id");	
		$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
		if($stmt -> execute())
			$respuesta = $stmt -> fetch();
		else{
			return json_encode(array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error'));
			$conexion -> close();
		}
				
		$usuarioAnterior= intval($respuesta["id_usuario_anterior"]);
		$ticket = intval($ticket);
		
		if( $respuesta['fecha_registro_reatendido'] == NULL )
			$fechaAtendido = date('Y-m-d H:i:s');
		else
			$fechaAtendido = $respuesta["fecha_registro_reatendido"];
		$motivo =  $respuesta['motivo_apertura'];
		
		$stmt = $conexion->prepare("CALL historialaperturatickets(:anterior,:registrado,:atendido,:motivo,:id)");				
		$stmt->bindParam(":anterior", $usuarioAnterior, PDO::PARAM_INT);
		$stmt->bindParam(":registrado", $respuesta["fecha_registro_reapertura"], PDO::PARAM_STR);
		$stmt->bindParam(":atendido", $fechaAtendido, PDO::PARAM_STR);
		$stmt->bindParam(":motivo", $motivo, PDO::PARAM_STR);
		$stmt->bindParam(":id", $ticket, PDO::PARAM_INT);
		if($stmt -> execute())
			return json_encode(array('error'=>false,'mensaje'=>'ok','mensaje2'=>'','tipo'=>'success'));
		else
			return json_encode(array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error'));
		$conexion -> close();
	}

	public static function totalPorReabrir($tabla){ //saber cuantas solicitudes para reabrir tickets por área existen
		$categoria = ' WHERE reabrir = 1';
		if(Configuraciones::administrador() != $_SESSION['identificador2'])//super usuario omite las 3 líneas siguientes
			$categoria.= ' AND area = '.AccesoSoporte::usuarios($_SESSION['identificador']);
		
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(id_ticket) AS total FROM $tabla $categoria");
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function totalPorReabrir2($tabla,$tipo){ //saber cuantas solicitudes para reabrir tickets por área existen
		$categoria = ' WHERE reabrir = 1';
		$categoria.= ' AND area = '.$tipo;
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(id_ticket) AS total FROM $tabla $categoria");
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function mostrarSolucionTicket($id,$tabla){ 
		$stmt = Conexion::conectar()->prepare("SELECT solucion,causa,problema FROM $tabla WHERE id_ticket = :id");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
	}
	
	public static function mostrarColaTicketsReabiertos($tabla){
		$categoria = ' WHERE reabrir = 1';
		if(Configuraciones::administrador() != $_SESSION['identificador2'])//super usuario omite las 3 líneas siguientes
			$categoria.= ' AND area = '.AccesoSoporte::usuarios($_SESSION['identificador']);
		$stmt = Conexion::conectar()->prepare("SELECT id_ticket,id_usuario,asunto,prioridad,area,fecha_finalizado FROM $tabla $categoria");						
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	public static function saberSiTicketTieneHistorial($id,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_registro) FROM $tabla WHERE id_ticket_referencia = :id ");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function mostrarListaHistorial($id,$tabla,$tabla2){ 
		$stmt = Conexion::conectar()->prepare("SELECT nombre,paterno,materno,id_registro,usuario_anterior,fecha_apertura,fecha_atendido,fecha_cierre FROM $tabla INNER JOIN $tabla2 ON $tabla.usuario_anterior = $tabla2.id_usuario WHERE id_ticket_referencia = :id");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	}

	public static function detallesAperturaCierre($id,$tabla){ 
		$stmt = Conexion::conectar()->prepare("SELECT motivo FROM $tabla WHERE id_registro = :id");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function verificarSituacionTicket($id,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT reabrir from $tabla WHERE id_ticket = :id");	
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		if($stmt -> execute()){
			$condicion = intval($stmt -> fetch()[0]);
			if( $condicion === 2)
				return json_encode(array('error'=>true,'mensaje'=>'El Ticket ya se encuentra reabierto','mensaje2'=>'','tipo'=>'info'));
			else
				return json_encode(array('error'=>false,'valor'=>$condicion));
		}
		else
			return json_encode(array('error'=>true,'mensaje'=>'Ocurrio un error','mensaje2'=>'¡Intentelo nuevamente!','tipo'=>'error','status'=>false));
		$stmt -> close();
	}

	public static function comprobarRespuestaTickets($tabla){
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_ticket) FROM $tabla WHERE id_usuario =:usuario AND visto = 1");	
		$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function comprobarRespuestaTicketsMensajes($tabla){///////////TEMPORAL
		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id_ticket) FROM $tabla WHERE id_usuario =:usuario AND mensaje = 1");	
		$stmt->bindParam(":usuario", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch()[0];
		$stmt -> close();
	}

	public static function resetearMensajes($tabla){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET mensaje = 0 WHERE id_usuario = :id");	
		$stmt->bindParam(":id", $_SESSION['identificador'], PDO::PARAM_INT);
		$stmt -> execute();
		return ;
		$stmt -> close();
	}

	public static function ticketVisto($id,$tabla){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET visto = 0 WHERE id_ticket = :id");	
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt -> execute();
		return ;
		$stmt -> close();
	}
	
}
