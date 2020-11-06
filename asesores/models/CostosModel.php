<?php
//require_once 'models/config.php';
require_once "conexion.php";

class CostosModel{

    public static function nuevo($data,$tabla){
        $conexion = Conexion::conectar();
		$stmt = $conexion->prepare("INSERT INTO $tabla 
		    (mes,
            cliente,
            promotor,
            subcomisionista,
            codigo_cliente,
            empleados,
            imss,
            real_imss,
            ajuste_imss,
            rcv,
            real_rcv,
            ajuste_rcv,
            infonavit,
            real_infonavit,
            ajuste_infonavit,
           /* impuesto_estatal,
            gmma,
            vida_invalidez,
            gmme,
            otros,*/
            subtotal,
            imss_obrero,
            real_imss_obrero,
            ajuste_imss_obrero,
            rcv_obrero,
            real_rcv_obrero,
            ajuste_rcv_obrero,
            amortizacion,
            real_amortizacion,
            ajuste_amortizacion,
            total,
            empresa,
            registro_imss,
            comentarios_imss,
            id_imss) 
            VALUES 
            (:mes,
            :cliente,
            :promotor,
            :subcomisionista,
            :codigo_cliente,
            :empleados,
            :imss,
            :real_imss,
            :ajuste_imss,
            :rcv,
            :real_rcv,
            :ajuste_rcv,
            :infonavit,
            :real_infonavit,
            :ajuste_infonavit,
            /*:impuesto_estatal,
            :gmma,
            :vida_invalidez,
            :gmme,
            :otros,*/
            :subtotal,
            :imss_obrero,
            :real_imss_obrero,
            :ajuste_imss_obrero,
            :rcv_obrero,
            :real_rcv_obrero,
            :ajuste_rcv_obrero,
            :amortizacion,
            :real_amortizacion,
            :ajuste_amortizacion,
            :total,
            :empresa,
            NOW(),
            :comentarios,
            :id_imss)");	
        $stmt = self::validarFormularioImss($stmt,$data);
        if($stmt->execute())
            return $conexion->lastInsertId();
        else
            return false;
		$conexion->close();
    }

    public static function actualizar($data,$tabla){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
           /* ejercicio=:ejercicio,*/
            mes=:mes,
            cliente=:cliente,
           /* nombre_comercial=:nombre_comercial,*/
            promotor=:promotor,
            subcomisionista=:subcomisionista,
            codigo_cliente=:codigo_cliente,
            empleados=:empleados,
            imss=:imss,
            real_imss=:real_imss,
            ajuste_imss=:ajuste_imss,
            rcv=:rcv,
            real_rcv=:real_rcv,
            ajuste_rcv=:ajuste_rcv,
            infonavit=:infonavit,
            real_infonavit=:real_infonavit,
            ajuste_infonavit=:ajuste_infonavit,
            /*impuesto_estatal=:impuesto_estatal,
            gmma=:gmma,
            vida_invalidez=:vida_invalidez,
            gmme=:gmme,
            otros=:otros,*/
            subtotal=:subtotal,
            imss_obrero=:imss_obrero,
            real_imss_obrero=:real_imss_obrero,
            ajuste_imss_obrero=:ajuste_imss_obrero,
            rcv_obrero=:rcv_obrero,
            real_rcv_obrero=:real_rcv_obrero,
            ajuste_rcv_obrero=:ajuste_rcv_obrero,
            amortizacion=:amortizacion,
            real_amortizacion=:real_amortizacion,
            ajuste_amortizacion=:ajuste_amortizacion,
            total=:total,
            empresa=:empresa,
            registro_imss=NOW(),
            comentarios_imss=:comentarios,
            id_imss=:id_imss
            WHERE id = :id");
        $stmt = self::validarFormularioImss($stmt,$data);
        $stmt->bindParam(':id',$data['id'],PDO::PARAM_INT);
        return $stmt->execute();
        $stmt->close();
    }

    public static function validarFormularioImss($stmt,$data){
       // $stmt->bindParam(':ejercicio',$data['ejercicio'],PDO::PARAM_STR);
        $stmt->bindParam(':mes',$data['mes'],PDO::PARAM_INT);
        /*******************************************************************************/
        $stmt->bindParam(':cliente',$data['cliente'],PDO::PARAM_INT);
       // $stmt->bindParam(':nombre_comercial',$data['nombre_comercial'],PDO::PARAM_STR);
        $stmt->bindParam(':promotor',$data['promotor'],PDO::PARAM_INT);
        $stmt->bindParam(':subcomisionista',$data['subcomisionista'],PDO::PARAM_INT);
        $stmt->bindParam(':codigo_cliente',$data['codigo_cliente'],PDO::PARAM_STR);
        /*******************************************************************************/
        $stmt->bindParam(':empleados',$data['empleados'],PDO::PARAM_INT);
        $stmt->bindParam(':imss',$data['imss'],PDO::PARAM_STR);
        $stmt->bindParam(':real_imss',$data['real_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':ajuste_imss',$data['ajuste_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':rcv',$data['rcv'],PDO::PARAM_STR);
        $stmt->bindParam(':real_rcv',$data['real_rcv'],PDO::PARAM_STR);
        $stmt->bindParam(':ajuste_rcv',$data['ajuste_rcv'],PDO::PARAM_STR);
        $stmt->bindParam(':infonavit',$data['infonavit'],PDO::PARAM_STR);
        $stmt->bindParam(':real_infonavit',$data['real_infonavit'],PDO::PARAM_STR);
        $stmt->bindParam(':ajuste_infonavit',$data['ajuste_infonavit'],PDO::PARAM_STR);
        /*$stmt->bindParam(':impuesto_estatal',$data['impuesto_estatal'],PDO::PARAM_STR);
        $stmt->bindParam(':gmma',$data['gmma'],PDO::PARAM_STR);
        $stmt->bindParam(':vida_invalidez',$data['vida_invalidez'],PDO::PARAM_STR);
        $stmt->bindParam(':gmme',$data['gmme'],PDO::PARAM_STR);
        $stmt->bindParam(':otros',$data['otros'],PDO::PARAM_STR);*/
        $stmt->bindParam(':subtotal',$data['subtotal'],PDO::PARAM_STR);
        $stmt->bindParam(':imss_obrero',$data['imss_obrero'],PDO::PARAM_STR);
        $stmt->bindParam(':real_imss_obrero',$data['real_imss_obrero'],PDO::PARAM_STR);
        $stmt->bindParam(':ajuste_imss_obrero',$data['ajuste_imss_obrero'],PDO::PARAM_STR);
        $stmt->bindParam(':rcv_obrero',$data['rcv_obrero'],PDO::PARAM_STR);
        $stmt->bindParam(':real_rcv_obrero',$data['real_rcv_obrero'],PDO::PARAM_STR);
        $stmt->bindParam(':ajuste_rcv_obrero',$data['ajuste_rcv_obrero'],PDO::PARAM_STR);
        $stmt->bindParam(':amortizacion',$data['amortizacion'],PDO::PARAM_STR);
        $stmt->bindParam(':real_amortizacion',$data['real_amortizacion'],PDO::PARAM_STR);
        $stmt->bindParam(':ajuste_amortizacion',$data['ajuste_amortizacion'],PDO::PARAM_STR);
        $stmt->bindParam(':total',$data['total'],PDO::PARAM_STR);
        $stmt->bindParam(':empresa',$data['empresa'],PDO::PARAM_STR);
        $stmt->bindParam(':comentarios',$data['comentarios'],PDO::PARAM_STR);
        $stmt->bindParam(':id_imss',$_SESSION['identificador'],PDO::PARAM_INT);
        return $stmt;
    }

    public static function actualizarGm($data,$tabla){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
            gmma=:gmma,
            vida_invalidez=:vida_invalidez,
            gmme=:gmme,
            otros=:otros,
            subtotal=:subtotal,
            total=:total,
            registro_gm=NOW(),
            comentarios_gm=:comentarios,
            id_gm=:id_gm
            WHERE id = :id");
        $stmt->bindParam(':gmma',$data['gmma'],PDO::PARAM_STR);
        $stmt->bindParam(':vida_invalidez',$data['vida_invalidez'],PDO::PARAM_STR);
        $stmt->bindParam(':gmme',$data['gmme'],PDO::PARAM_STR);
        $stmt->bindParam(':otros',$data['otros'],PDO::PARAM_STR);
        $stmt->bindParam(':subtotal',$data['subtotal'],PDO::PARAM_STR);
        $stmt->bindParam(':total',$data['total'],PDO::PARAM_STR);
        $stmt->bindParam(':comentarios',$data['comentarios'],PDO::PARAM_STR);
        $stmt->bindParam(':id_gm',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt->bindParam(':id',$data['id'],PDO::PARAM_INT);
        return $stmt->execute();
        $stmt->close();
    }

    public static function actualizarNominas($data,$tabla){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
            impuesto_estatal=:impuesto_estatal,
            subtotal=:subtotal,
            total=:total,
            registro_nominas=NOW(),
            comentarios_nominas=:comentarios,
            id_nominas=:id_nominas
            WHERE id = :id");
        $stmt->bindParam(':impuesto_estatal',$data['impuesto_estatal'],PDO::PARAM_STR);
        $stmt->bindParam(':subtotal',$data['subtotal'],PDO::PARAM_STR);
        $stmt->bindParam(':total',$data['total'],PDO::PARAM_STR);
        $stmt->bindParam(':comentarios',$data['comentarios'],PDO::PARAM_STR);
        $stmt->bindParam(':id_nominas',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt->bindParam(':id',$data['id'],PDO::PARAM_INT);
        return $stmt->execute();
        $stmt->close();
    }

    //Mostrar los datos de un registro en particular
    public static function mostrar($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt->fetch();
        $stmt -> close(); 
    }


    //Mostrar los datos de los registros en la administración de registros
    public static function mostrar2($id,$cliente,$tabla){
        $limite='';
        $where=self::where($id,$cliente);
        $stmt = Conexion::conectar()->prepare("SELECT id,id_gm,id_nominas,cliente FROM $tabla WHERE estatus = 1 $where ORDER BY id desc $limite");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt -> close(); 
    }

    //obtenemos los datos del capturista
    public static function obtenerCapturista($id,$tabla,$tabla2,$tabla3){
        $stmt = Conexion::conectar()->prepare("SELECT CONCAT(U.nombre,' ',paterno,' ',materno) AS nombre,S.nombre AS sucursal,P.nombre AS puesto FROM $tabla AS U 
                                               INNER JOIN $tabla2 AS S ON U.id_sucursal = S.id_sucursal 
                                               INNER JOIN $tabla3 AS P ON U.id_puesto = P.id_puesto WHERE id_usuario = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
        $stmt -> close(); 
    }

    //Eliminar un registro
    public static function eliminar($id,$tabla){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estatus = 0 WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        return $stmt->execute();
        $stmt -> close(); 
    }

    //Saber el total de registros existentes
    public static function contarRegistros($id,$cliente,$tabla){
        $where=self::where($id,$cliente);
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(id) FROM $tabla WHERE estatus = 1 $where");
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

    //Englobamos las condiciones de busqueda para utilizarlas en distintos metodos
    public static function where($id,$cliente){
        $where='';
        if(!empty($id))
            $where .= " AND id =".intval($id);
        if(!empty($cliente))
            $where .= " AND cliente =".$cliente;
       /* $where=' WHERE status_nominas = 1';
        if($ubicacion === '/asesores/finanzas' )
            $where .= " AND liberacion_nominas = 1";
        if($ubicacion === '/asesores/tesoreria')
            $where .= " AND observaciones = 2";
        if(!empty($data['cliente']) )
            $where .= " AND $tabla.id_cliente =".intval($data['cliente']);
        if(!empty($data['facturado']) )
            $where .= " AND (total = ".$data['facturado']." OR subtotal = ".$data['facturado']." OR iva = ".$data['facturado']." OR total_imss = ".$data['facturado']." OR total_asimilados = ".$data['facturado'].")";
        if(!empty($data['nomina']) )
            $where .= " AND $tabla.id = ".intval($data['nomina']);
        if(!empty($data['liberado']) AND $data['liberado'] !== "undefined")
            $where .= " AND observaciones = ".intval($data['liberado']);
        if(!empty($data['pago']) AND $data['pago'] !== "undefined")
            $where .= " AND tesoreria_estatus = ".intval($data['pago']);
        if( $data['autorizacion'] !== "" AND $data['autorizacion'] !== "undefined")
            $where .= " AND liberacion_nominas = ".intval($data['autorizacion']);
        if( $ubicacion === '/asesores/nominas'  || $_SESSION['identificador']==172 || $_SESSION['identificador']==168){
            if( !empty($data['nominista']) AND $data['nominista'] !== "undefined" )
                $where .=" AND id_nominista =".intval($data['nominista']);
        }*/
        return $where;
    }


    //indicamos mediante contadores el estado de los registros
    public static function marcadores($estado,$tabla){
        if($estado < 4)
            $where = "WHERE observaciones = ".intval($estado) ." AND liberacion_nominas = 1 ";
        else if($estado === 4)
            $where = "WHERE tesoreria_estatus = 1 AND observaciones = 2";
        else if($estado == 10)
            $where = "WHERE liberacion_nominas = 1 AND estatus_factura = 1";
        else
            $where = "WHERE tesoreria_estatus = ".(intval($estado) - 3);

        $stmt = Conexion::conectar()->prepare("SELECT COUNT(id) FROM $tabla $where AND status_nominas = 1");
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

    public static function cargarSelect($tabla,$activos){
        $condicion = $activos  ? "WHERE activo = 1" : "";
        $stmt = Conexion::conectar()->prepare("SELECT id,nombre FROM $tabla $condicion ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close(); 	
    }

    public static function cargarSelect2($tabla,$activos){
        $condicion = $activos  ? "WHERE activo = 1" : "";
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla $condicion ORDER BY nombre ");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close(); 	
    }

    public static function getRegistro($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT nombre FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()[0];
        $stmt->close(); 	
    }

    public static function actualizarEstado($id,$valor,$tabla){
        $valor = $valor === 'true' ? 1 : 0;
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET activo = $valor WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        return $stmt->execute();
        $stmt->close(); 	
    }

    public static function nuevoCliente($nombre,$nombreComercial,$tabla){
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre,nombre_comercial) VALUES (:n,:n2)");
        $stmt->bindParam(':n',$nombre,PDO::PARAM_STR);
        $stmt->bindParam(':n2',$nombreComercial,PDO::PARAM_STR);
        return $stmt->execute();
        $stmt -> close(); 	
    }

    public static function nuevoPromotor($nombre,$tabla){
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre) VALUES (:n)");
        $stmt->bindParam(':n',$nombre,PDO::PARAM_STR);
        return $stmt->execute();
        $stmt -> close(); 	
    }

    public static function actualizarCliente($id,$nombre,$nombreComercial,$tabla){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :n,nombre_comercial = :n2 WHERE id = :id");
        $stmt->bindParam(':n',$nombre,PDO::PARAM_STR);
        $stmt->bindParam(':n2',$nombreComercial,PDO::PARAM_STR);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        return $stmt->execute();
        $stmt -> close(); 	
    }
    public static function actualizarPromotor($id,$nombre,$tabla){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :n WHERE id = :id");
        $stmt->bindParam(':n',$nombre,PDO::PARAM_STR);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        return $stmt->execute();
        $stmt -> close(); 	
    }

    public static function getNombreComercial($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT nombre_comercial FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()[0];
        $stmt->close(); 	
    }

    public static function getNombrePromotor($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT nombre FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()[0];
        $stmt->close(); 	
    }

    /********************************************* */
    public static function insersionManual($data,$tabla){
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla$data");
        return array('respuesta'=>$stmt->execute(),'total'=>$stmt->rowCount());
        $conexion -> close();	
    }

    public static function insersionManualFinanzas($data,$tabla){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $data AND (id_finanzas = :id OR id_finanzas IS NULL) ");
        $stmt->bindParam(':id',$_SESSION['identificador'],PDO::PARAM_INT);
        return array('respuesta'=>$stmt->execute(),'total'=>$stmt->rowCount());
        $conexion -> close();	
    }


    public static function actualizarFinanzasLiberacion($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT id_finanzas,captura_finanzas FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
        $stmt -> close(); 	
    }
    public static function cargarClientesLayout($tabla){
        $stmt = Conexion::conectar()->prepare("SELECT nombre FROM $tabla WHERE activo = 1 ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close(); 	
    }
    public static function cargarEmpresasLayout($tabla){
        $stmt = Conexion::conectar()->prepare("SELECT nombre FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close(); 	
    }
    public static function obtenerId($cliente,$tabla){
        $conexion = MetodoMartin::Conexion();
        $datos=mysqli_query($conexion,"SELECT id FROM $tabla WHERE nombre = '$cliente'");
        $row= $datos->fetch_array()[0];
        return $row;   
    }

    public static function insercionLayout($matriz){
        $conexion = MetodoMartin::Conexion();
        foreach ($matriz as $key => $array) {
            foreach ($array as $key => $value) {
                $res= mysqli_query($conexion,$array['sqli']);
            } 
        }
        if(!$res)
        { return 2; }else { return 3; } return 1;
    }

    public static function idInsercionCostos($identificador){
        $conexion = MetodoMartin::Conexion();
        $resultado=mysqli_query($conexion,"SELECT * FROM costos_ae WHERE id = '$identificador'");
        if (mysqli_num_rows($resultado) > 0)
            return false;
        else
            return true;
    }

    public static function obtenerIdvarios($cliente,$tabla){
        $conexion = MetodoMartin::Conexion();
        $datos=mysqli_query($conexion,"SELECT id FROM $tabla WHERE nombre = '$cliente'");
        $row= $datos->fetch_array()[0];
        return $row; 
    }

    public static function reporteCostos($inicio,$fin){
        $conexion = MetodoMartin::Conexion();
        $query="SELECT * FROM costos_ae WHERE estatus='1' AND (DATE	(registro)) BETWEEN '$inicio' AND '$fin'";
        $result=$conexion->query($query);
        $result->fetch_all();
        return $result;
    }
    
    
}