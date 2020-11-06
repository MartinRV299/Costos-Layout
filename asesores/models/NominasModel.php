<?php

require_once "conexion.php";

class NominasModel{
    
    public static function mostrarSelect($tabla){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY nombre");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt -> close(); 
    }

    public static function mostrarNoministas($tabla){
        $usuarios = '0';
        foreach( GrupoNominas::data() as $item => $row)
            $usuarios .= ','.$item;
        $stmt = Conexion::conectar()->prepare("SELECT id_usuario,CONCAT($tabla.nombre,' ',paterno,' ',materno) AS nombre FROM $tabla WHERE id_usuario IN ($usuarios) ORDER BY nombre,materno,paterno");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt -> close(); 
    }

    public static function mostrarNoministas2($tabla,$tabla2,$tabla3,$ruta){
        $usuarios = '0';
        if($ruta === "/asesores/nominas")
            $grupo = GrupoNominas::data();
        else if($ruta === "/asesores/finanzas")
            $grupo = GrupoFinanzas::data();
        else if($ruta === "/asesores/tesoreria")
            $grupo = GrupoTesoreria::data();
        else if($ruta === "/asesores/facturacion")
            $grupo = GrupoFacturacion::data();
        else
            $grupo = GrupoLiberacion::data();

        foreach( $grupo as $item => $row)
            $usuarios .= ','.$item;
        $stmt = Conexion::conectar()->prepare("SELECT id_usuario, CONCAT($tabla.nombre,' ',paterno,' ',materno) AS nombre,$tabla2.nombre AS sucursal,$tabla3.nombre AS puesto FROM $tabla INNER JOIN $tabla2 ON $tabla.id_sucursal = $tabla2.id_sucursal INNER JOIN $tabla3 ON $tabla.id_puesto = $tabla3.id_puesto WHERE id_usuario IN ($usuarios) AND situacion = 1 ORDER BY $tabla2.nombre,$tabla.nombre,paterno,materno");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt -> close(); 
    }

    public static function mostrarNoministasLista($tabla,$tabla2){
        $stmt = Conexion::conectar()->prepare("SELECT id_usuario,CONCAT(nombre,' ',paterno,' ',materno) AS nombre FROM $tabla WHERE id_usuario IN (SELECT id_empleado FROM $tabla2 WHERE id_jefe = :jefe) ORDER BY nombre,materno,paterno"); //AND situacion = 1
        $stmt->bindParam(':jefe',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt -> close(); 
    }

    public static function obtenerDatoNominas($cliente,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT nombre FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$cliente,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

    public static function validacionJefe($nominista,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) FROM $tabla WHERE id_empleado = :nominista AND id_jefe = :jefe");
        $stmt->bindParam(':nominista',$nominista,PDO::PARAM_INT);
        $stmt->bindParam(':jefe',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

    public static function registrarNomina($data,$tabla){
        $conexion = Conexion::conectar();
		$stmt = $conexion->prepare("INSERT INTO $tabla 
		    (id_cliente,
            esquema,
            devengada,
            tipo_sindicato,
            tipo_pago,
            regimen,
            comision_porcentaje,
            empresa_facturadora,
            subtotal,
            iva,
            total,
            empresa_imss,
            total_imss,
            empresa_asimilados,
            total_asimilados,
            tipo_periodo,
            numero_periodo,
            socios,
            ingreso,
            infonavit,
            fonacot,
            donativo,
            pension,
            excedente_cargas,
            cargas_patronal,
            isn,
            comision_monto,
            imss_obrera,
            carga_social_imss,
            prenomina_imss,
            isr_isp,
            isr_142,
            cuota_sindical,
            despensa,
            caja_ahorro,
            descuento_imss,
            apoyo_sindical,
            descuento_comedor,
            haberes,
            otros,
            excedente_ingreso,
            excedente_isr,
            excedente_imss,
            excedente_gmm,
            excedente_infonavit,
            excedente_fonacot,
            excedente_prestamos,
            excedente_pension,
            excedente_terceros,
            excedente_clientes,
            excedente_subsidio,
            excedente_recuperacion,
            excedente_comision,
            excedente_prenomina,
            excedente_prenomina_gmm,
            excedente_otros,
            comentarios_nominas,
            id_nominista,
            captura_nominista,
            nomina_origen) 
            VALUES 
            (:id_cliente,
            :esquema,
            :devengada,
            :tipo_sindicato,
            :tipo_pago,
            :regimen,
            :comision_porcentaje,
            :empresa_facturadora,
            :subtotal,
            :iva,
            :total,
            :empresa_imss,
            :total_imss,
            :empresa_asimilados,
            :total_asimilados,
            :tipo_periodo,
            :numero_periodo,
            :socios,
            :ingreso,
            :infonavit,
            :fonacot,
            :donativo,
            :pension,
            :excedente_cargas,
            :cargas_patronal,
            :isn,
            :comision_monto,
            :imss_obrera,
            :carga_social_imss,
            :prenomina_imss,
            :isr_isp,
            :isr_142,
            :cuota_sindical,
            :despensa,
            :caja_ahorro,
            :descuento_imss,
            :apoyo_sindical,
            :descuento_comedor,
            :haberes,
            :otros,
            :excedente_ingreso,
            :excedente_isr,
            :excedente_imss,
            :excedente_gmm,
            :excedente_infonavit,
            :excedente_fonacot,
            :excedente_prestamos,
            :excedente_pension,
            :excedente_terceros,
            :excedente_clientes,
            :excedente_subsidio,
            :excedente_recuperacion,
            :excedente_comision,
            :excedente_prenomina,
            :excedente_prenomina_gmm,
            :excedente_otros,
            :comentarios_nominas,
            :id_nominista,
            NOW(),
            :nomina_origen)");	
            
            $stmt->bindParam(':id_cliente',$data['id_cliente'],PDO::PARAM_INT);
            $stmt->bindParam(':esquema',$data['tipo_esquema'],PDO::PARAM_INT);
            $stmt->bindParam(':devengada',$data['devengada'],PDO::PARAM_INT);
            $stmt->bindParam(':tipo_sindicato',$data['tipo_sindicato'],PDO::PARAM_INT);
            $stmt->bindParam(':tipo_pago',$data['tipo_pago'],PDO::PARAM_INT);
            $stmt->bindParam(':regimen',$data['regimen'],PDO::PARAM_INT);
            $stmt->bindParam(':comision_porcentaje',$data['comision'],PDO::PARAM_STR);
            $stmt->bindParam(':empresa_facturadora',$data['empresa_facturadora'],PDO::PARAM_INT);
            $stmt->bindParam(':subtotal',$data['subtotal'],PDO::PARAM_STR);
            $stmt->bindParam(':iva',$data['iva'],PDO::PARAM_STR);
            $stmt->bindParam(':total',$data['total'],PDO::PARAM_STR);
            $stmt->bindParam(':empresa_imss',$data['empresa_imss'],PDO::PARAM_INT);
            $stmt->bindParam(':total_imss',$data['total_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':empresa_asimilados',$data['empresa_asimilados'],PDO::PARAM_INT);
            $stmt->bindParam(':total_asimilados',$data['total_asimilados'],PDO::PARAM_STR);
            $stmt->bindParam(':tipo_periodo',$data['tipo_periodo'],PDO::PARAM_INT);
            $stmt->bindParam(':numero_periodo',$data['numero_periodo'],PDO::PARAM_INT);
            $stmt->bindParam(':socios',$data['socios'],PDO::PARAM_INT);
            $stmt->bindParam(':ingreso',$data['ingreso'],PDO::PARAM_STR);
            $stmt->bindParam(':infonavit',$data['infonavit'],PDO::PARAM_STR);
            $stmt->bindParam(':fonacot',$data['fonacot'],PDO::PARAM_STR);
            $stmt->bindParam(':donativo',$data['donativo'],PDO::PARAM_STR);
            $stmt->bindParam(':pension',$data['pension'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_cargas',$data['excedente_cargas'],PDO::PARAM_STR);
            $stmt->bindParam(':cargas_patronal',$data['cargas_patronal'],PDO::PARAM_STR);
            $stmt->bindParam(':isn',$data['isn'],PDO::PARAM_STR);
            $stmt->bindParam(':comision_monto',$data['comision_monto'],PDO::PARAM_STR);
            $stmt->bindParam(':imss_obrera',$data['imss_obrera'],PDO::PARAM_STR);
            $stmt->bindParam(':carga_social_imss',$data['carga_social_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':prenomina_imss',$data['prenomina_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':isr_isp',$data['isr_isp'],PDO::PARAM_STR);
            $stmt->bindParam(':isr_142',$data['isr_142'],PDO::PARAM_STR);
            $stmt->bindParam(':cuota_sindical',$data['cuota_sindical'],PDO::PARAM_STR);
            $stmt->bindParam(':despensa',$data['despensa'],PDO::PARAM_STR);
            $stmt->bindParam(':caja_ahorro',$data['caja_ahorro'],PDO::PARAM_STR);
            $stmt->bindParam(':descuento_imss',$data['descuento_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':apoyo_sindical',$data['apoyo_sindical'],PDO::PARAM_STR);
            $stmt->bindParam(':descuento_comedor',$data['descuento_comedor'],PDO::PARAM_STR);
            $stmt->bindParam(':haberes',$data['haberes'],PDO::PARAM_STR);
            $stmt->bindParam(':otros',$data['otros'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_ingreso',$data['excedente_ingreso'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_isr',$data['excedente_isr'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_imss',$data['excedente_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_gmm',$data['excedente_gmm'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_infonavit',$data['excedente_infonavit'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_fonacot',$data['excedente_fonacot'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_prestamos',$data['excedente_prestamos'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_pension',$data['excedente_pension'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_terceros',$data['excedente_terceros'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_clientes',$data['excedente_clientes'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_subsidio',$data['excedente_subsidio'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_recuperacion',$data['excedente_recuperacion'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_comision',$data['excedente_comision'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_prenomina',$data['excedente_prenomina'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_prenomina_gmm',$data['excedente_prenomina_gmm'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_otros',$data['excedente_otros'],PDO::PARAM_STR);
            $stmt->bindParam(':comentarios_nominas',$data['comentarios_nominas'],PDO::PARAM_STR);
            $stmt->bindParam(':id_nominista',$_SESSION['identificador'],PDO::PARAM_INT);
            $stmt->bindParam(':nomina_origen',$data['nomina_origen'],PDO::PARAM_INT);
		if($stmt->execute()){
            return $conexion->lastInsertId();
        }
        else
            return 0;
		$conexion->close();
    }

    public static function registrarNomina2($data,$tabla){
        $conexion = Conexion::conectar();
		$stmt = $conexion->prepare("INSERT INTO $tabla 
		    (id_cliente,
            esquema,
            devengada,
            tipo_sindicato,
            tipo_pago,
            regimen,
            comision_porcentaje,
            empresa_facturadora,
            subtotal,
            iva,
            total,
            empresa_imss,
            total_imss,
            empresa_asimilados,
            total_asimilados,
            tipo_periodo,
            numero_periodo,
            socios,
            ingreso,
            infonavit,
            fonacot,
            donativo,
            pension,
            excedente_cargas,
            cargas_patronal,
            isn,
            comision_monto,
            imss_obrera,
            carga_social_imss,
            prenomina_imss,
            isr_isp,
            isr_142,
            cuota_sindical,
            despensa,
            caja_ahorro,
            descuento_imss,
            apoyo_sindical,
            descuento_comedor,
            haberes,
            otros,
            excedente_ingreso,
            excedente_isr,
            excedente_imss,
            excedente_gmm,
            excedente_infonavit,
            excedente_fonacot,
            excedente_prestamos,
            excedente_pension,
            excedente_terceros,
            excedente_clientes,
            excedente_subsidio,
            excedente_recuperacion,
            excedente_comision,
            excedente_prenomina,
            excedente_prenomina_gmm,
            excedente_otros,
            comentarios_nominas,
            id_nominista,
            captura_nominista,
            nomina_origen,
            descuentos_sys,
            descuentos_asesores,
            descuentos_terceros,
            prestamos_empleados,
            prestamos_ayudate,
            excedente_caja_ahorro,
            retencion_iva,
            devengada_factura,
            ajuste_subsidio_empleo,
            descuento_ayudate,
            retencion_isn) 
            VALUES 
            (:id_cliente,
            :esquema,
            :devengada,
            :tipo_sindicato,
            :tipo_pago,
            :regimen,
            :comision_porcentaje,
            :empresa_facturadora,
            :subtotal,
            :iva,
            :total,
            :empresa_imss,
            :total_imss,
            :empresa_asimilados,
            :total_asimilados,
            :tipo_periodo,
            :numero_periodo,
            :socios,
            :ingreso,
            :infonavit,
            :fonacot,
            :donativo,
            :pension,
            :excedente_cargas,
            :cargas_patronal,
            :isn,
            :comision_monto,
            :imss_obrera,
            :carga_social_imss,
            :prenomina_imss,
            :isr_isp,
            :isr_142,
            :cuota_sindical,
            :despensa,
            :caja_ahorro,
            :descuento_imss,
            :apoyo_sindical,
            :descuento_comedor,
            :haberes,
            :otros,
            :excedente_ingreso,
            :excedente_isr,
            :excedente_imss,
            :excedente_gmm,
            :excedente_infonavit,
            :excedente_fonacot,
            :excedente_prestamos,
            :excedente_pension,
            :excedente_terceros,
            :excedente_clientes,
            :excedente_subsidio,
            :excedente_recuperacion,
            :excedente_comision,
            :excedente_prenomina,
            :excedente_prenomina_gmm,
            :excedente_otros,
            :comentarios_nominas,
            :id_nominista,
            NOW(),
            :nomina_origen,
            :descuentos_sys,
            :descuentos_asesores,
            :descuentos_terceros,
            :prestamos_empleados,
            :prestamos_ayudate,
            :excedente_caja_ahorro,
            :retencion_iva,
            :devengada_factura,
            :ajuste_subsidio_empleo,
            :descuento_ayudate,
            :retencion_isn)");	
            
            $stmt->bindParam(':id_cliente',$data['id_cliente'],PDO::PARAM_INT);
            $stmt->bindParam(':esquema',$data['tipo_esquema'],PDO::PARAM_INT);
            $stmt->bindParam(':devengada',$data['devengada'],PDO::PARAM_INT);
            $stmt->bindParam(':tipo_sindicato',$data['tipo_sindicato'],PDO::PARAM_INT);
            $stmt->bindParam(':tipo_pago',$data['tipo_pago'],PDO::PARAM_INT);
            $stmt->bindParam(':regimen',$data['regimen'],PDO::PARAM_INT);
            $stmt->bindParam(':comision_porcentaje',$data['comision'],PDO::PARAM_STR);
            $stmt->bindParam(':empresa_facturadora',$data['empresa_facturadora'],PDO::PARAM_INT);
            $stmt->bindParam(':subtotal',$data['subtotal'],PDO::PARAM_STR);
            $stmt->bindParam(':iva',$data['iva'],PDO::PARAM_STR);
            $stmt->bindParam(':total',$data['total'],PDO::PARAM_STR);
            $stmt->bindParam(':empresa_imss',$data['empresa_imss'],PDO::PARAM_INT);
            $stmt->bindParam(':total_imss',$data['total_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':empresa_asimilados',$data['empresa_asimilados'],PDO::PARAM_INT);
            $stmt->bindParam(':total_asimilados',$data['total_asimilados'],PDO::PARAM_STR);
            $stmt->bindParam(':tipo_periodo',$data['tipo_periodo'],PDO::PARAM_INT);
            $stmt->bindParam(':numero_periodo',$data['numero_periodo'],PDO::PARAM_INT);
            $stmt->bindParam(':socios',$data['socios'],PDO::PARAM_INT);
            $stmt->bindParam(':ingreso',$data['ingreso'],PDO::PARAM_STR);
            $stmt->bindParam(':infonavit',$data['infonavit'],PDO::PARAM_STR);
            $stmt->bindParam(':fonacot',$data['fonacot'],PDO::PARAM_STR);
            $stmt->bindParam(':donativo',$data['donativo'],PDO::PARAM_STR);
            $stmt->bindParam(':pension',$data['pension'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_cargas',$data['excedente_cargas'],PDO::PARAM_STR);
            $stmt->bindParam(':cargas_patronal',$data['cargas_patronal'],PDO::PARAM_STR);
            $stmt->bindParam(':isn',$data['isn'],PDO::PARAM_STR);
            $stmt->bindParam(':comision_monto',$data['comision_monto'],PDO::PARAM_STR);
            $stmt->bindParam(':imss_obrera',$data['imss_obrera'],PDO::PARAM_STR);
            $stmt->bindParam(':carga_social_imss',$data['carga_social_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':prenomina_imss',$data['prenomina_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':isr_isp',$data['isr_isp'],PDO::PARAM_STR);
            $stmt->bindParam(':isr_142',$data['isr_142'],PDO::PARAM_STR);
            $stmt->bindParam(':cuota_sindical',$data['cuota_sindical'],PDO::PARAM_STR);
            $stmt->bindParam(':despensa',$data['despensa'],PDO::PARAM_STR);
            $stmt->bindParam(':caja_ahorro',$data['caja_ahorro'],PDO::PARAM_STR);
            $stmt->bindParam(':descuento_imss',$data['descuento_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':apoyo_sindical',$data['apoyo_sindical'],PDO::PARAM_STR);
            $stmt->bindParam(':descuento_comedor',$data['descuento_comedor'],PDO::PARAM_STR);
            $stmt->bindParam(':haberes',$data['haberes'],PDO::PARAM_STR);
            $stmt->bindParam(':otros',$data['otros'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_ingreso',$data['excedente_ingreso'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_isr',$data['excedente_isr'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_imss',$data['excedente_imss'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_gmm',$data['excedente_gmm'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_infonavit',$data['excedente_infonavit'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_fonacot',$data['excedente_fonacot'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_prestamos',$data['excedente_prestamos'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_pension',$data['excedente_pension'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_terceros',$data['excedente_terceros'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_clientes',$data['excedente_clientes'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_subsidio',$data['excedente_subsidio'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_recuperacion',$data['excedente_recuperacion'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_comision',$data['excedente_comision'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_prenomina',$data['excedente_prenomina'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_prenomina_gmm',$data['excedente_prenomina_gmm'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_otros',$data['excedente_otros'],PDO::PARAM_STR);
            $stmt->bindParam(':comentarios_nominas',$data['comentarios_nominas'],PDO::PARAM_STR);
            $stmt->bindParam(':id_nominista',$_SESSION['identificador'],PDO::PARAM_INT);
            $stmt->bindParam(':nomina_origen',$data['nomina_origen'],PDO::PARAM_INT);

            $stmt->bindParam(':descuentos_sys',$data['descuentos_sys'],PDO::PARAM_STR);
            $stmt->bindParam(':descuentos_asesores',$data['descuentos_asesores'],PDO::PARAM_STR);
            $stmt->bindParam(':descuentos_terceros',$data['descuentos_terceros'],PDO::PARAM_STR);
            $stmt->bindParam(':prestamos_empleados',$data['prestamos_empleados'],PDO::PARAM_STR);
            $stmt->bindParam(':prestamos_ayudate',$data['prestamos_ayudate'],PDO::PARAM_STR);
            $stmt->bindParam(':excedente_caja_ahorro',$data['excedente_caja_ahorro'],PDO::PARAM_STR);
            $stmt->bindParam(':retencion_iva',$data['retencion_iva'],PDO::PARAM_STR);
            $stmt->bindParam(':devengada_factura',$data['devengadaFactura'],PDO::PARAM_STR);
            $stmt->bindParam(':ajuste_subsidio_empleo',$data['ajuste_subsidio_empleo'],PDO::PARAM_STR);
            $stmt->bindParam(':descuento_ayudate',$data['descuento_ayudate'],PDO::PARAM_STR);
            $stmt->bindParam(':retencion_isn',$data['retencion_isn'],PDO::PARAM_STR);
            

            

		if($stmt->execute()){
            return $conexion->lastInsertId();
        }
        else
            return 0;
		$conexion->close();
    }

    public static function verificarStatusTesoreria($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT tesoreria_estatus FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

    public static function verificarStatusFinanzas($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT observaciones FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

    public static function actualizarNomina($data,$tabla){
        $conexion = Conexion::conectar();
		$stmt = $conexion->prepare("UPDATE $tabla SET 
		id_cliente=:id_cliente,
        devengada=:devengada,
        tipo_sindicato=:tipo_sindicato,
        tipo_pago=:tipo_pago,
        regimen=:regimen,
        comision_porcentaje=:comision_porcentaje,
        empresa_facturadora=:empresa_facturadora,
        subtotal=:subtotal,
        iva=:iva,
        total=:total,
        empresa_imss=:empresa_imss,
        total_imss=:total_imss,
        empresa_asimilados=:empresa_asimilados,
        total_asimilados=:total_asimilados,
        tipo_periodo=:tipo_periodo,
        numero_periodo=:numero_periodo,
        socios=:socios,
        ingreso=:ingreso,
        infonavit=:infonavit,
        fonacot=:fonacot,
        donativo=:donativo,
        pension=:pension,
        excedente_cargas=:excedente_cargas,
        cargas_patronal=:cargas_patronal,
        isn=:isn,
        comision_monto=:comision_monto,
        imss_obrera=:imss_obrera,
        carga_social_imss=:carga_social_imss,
        prenomina_imss=:prenomina_imss,
        isr_isp=:isr_isp,
        isr_142=:isr_142,
        cuota_sindical=:cuota_sindical,
        despensa=:despensa,
        caja_ahorro=:caja_ahorro,
        descuento_imss=:descuento_imss,
        apoyo_sindical=:apoyo_sindical,
        descuento_comedor=:descuento_comedor,
        haberes=:haberes,
        otros=:otros,
        excedente_ingreso=:excedente_ingreso,
        excedente_isr=:excedente_isr,
        excedente_imss=:excedente_imss,
        excedente_gmm=:excedente_gmm,
        excedente_infonavit=:excedente_infonavit,
        excedente_fonacot=:excedente_fonacot,
        excedente_prestamos=:excedente_prestamos,
        excedente_pension=:excedente_pension,
        excedente_terceros=:excedente_terceros,
        excedente_clientes=:excedente_clientes,
        excedente_subsidio=:excedente_subsidio,
        excedente_recuperacion=:excedente_recuperacion,
        excedente_comision=:excedente_comision,
        excedente_prenomina=:excedente_prenomina,
        excedente_prenomina_gmm=:excedente_prenomina_gmm,
        excedente_otros=:excedente_otros,
        comentarios_nominas=:comentarios_nominas
		WHERE id = :id");
        $stmt->bindParam(':id_cliente',$data['id_cliente'],PDO::PARAM_INT);
        $stmt->bindParam(':devengada',$data['devengada'],PDO::PARAM_INT);
        $stmt->bindParam(':tipo_sindicato',$data['tipo_sindicato'],PDO::PARAM_INT);
        $stmt->bindParam(':tipo_pago',$data['tipo_pago'],PDO::PARAM_INT);
        $stmt->bindParam(':regimen',$data['regimen'],PDO::PARAM_INT);
        $stmt->bindParam(':comision_porcentaje',$data['comision'],PDO::PARAM_STR);
        $stmt->bindParam(':empresa_facturadora',$data['empresa_facturadora'],PDO::PARAM_INT);
        $stmt->bindParam(':subtotal',$data['subtotal'],PDO::PARAM_STR);
        $stmt->bindParam(':iva',$data['iva'],PDO::PARAM_STR);
        $stmt->bindParam(':total',$data['total'],PDO::PARAM_STR);
        $stmt->bindParam(':empresa_imss',$data['empresa_imss'],PDO::PARAM_INT);
        $stmt->bindParam(':total_imss',$data['total_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':empresa_asimilados',$data['empresa_asimilados'],PDO::PARAM_INT);
        $stmt->bindParam(':total_asimilados',$data['total_asimilados'],PDO::PARAM_STR);
        $stmt->bindParam(':tipo_periodo',$data['tipo_periodo'],PDO::PARAM_INT);
        $stmt->bindParam(':numero_periodo',$data['numero_periodo'],PDO::PARAM_INT);
        $stmt->bindParam(':socios',$data['socios'],PDO::PARAM_INT);
        $stmt->bindParam(':ingreso',$data['ingreso'],PDO::PARAM_STR);
        $stmt->bindParam(':infonavit',$data['infonavit'],PDO::PARAM_STR);
        $stmt->bindParam(':fonacot',$data['fonacot'],PDO::PARAM_STR);
        $stmt->bindParam(':donativo',$data['donativo'],PDO::PARAM_STR);
        $stmt->bindParam(':pension',$data['pension'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_cargas',$data['excedente_cargas'],PDO::PARAM_STR);
        $stmt->bindParam(':cargas_patronal',$data['cargas_patronal'],PDO::PARAM_STR);
        $stmt->bindParam(':isn',$data['isn'],PDO::PARAM_STR);
        $stmt->bindParam(':comision_monto',$data['comision_monto'],PDO::PARAM_STR);
        $stmt->bindParam(':imss_obrera',$data['imss_obrera'],PDO::PARAM_STR);
        $stmt->bindParam(':carga_social_imss',$data['carga_social_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':prenomina_imss',$data['prenomina_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':isr_isp',$data['isr_isp'],PDO::PARAM_STR);
        $stmt->bindParam(':isr_142',$data['isr_142'],PDO::PARAM_STR);
        $stmt->bindParam(':cuota_sindical',$data['cuota_sindical'],PDO::PARAM_STR);
        $stmt->bindParam(':despensa',$data['despensa'],PDO::PARAM_STR);
        $stmt->bindParam(':caja_ahorro',$data['caja_ahorro'],PDO::PARAM_STR);
        $stmt->bindParam(':descuento_imss',$data['descuento_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':apoyo_sindical',$data['apoyo_sindical'],PDO::PARAM_STR);
        $stmt->bindParam(':descuento_comedor',$data['descuento_comedor'],PDO::PARAM_STR);
        $stmt->bindParam(':haberes',$data['haberes'],PDO::PARAM_STR);
        $stmt->bindParam(':otros',$data['otros'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_ingreso',$data['excedente_ingreso'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_isr',$data['excedente_isr'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_imss',$data['excedente_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_gmm',$data['excedente_gmm'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_infonavit',$data['excedente_infonavit'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_fonacot',$data['excedente_fonacot'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_prestamos',$data['excedente_prestamos'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_pension',$data['excedente_pension'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_terceros',$data['excedente_terceros'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_clientes',$data['excedente_clientes'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_subsidio',$data['excedente_subsidio'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_recuperacion',$data['excedente_recuperacion'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_comision',$data['excedente_comision'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_prenomina',$data['excedente_prenomina'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_prenomina_gmm',$data['excedente_prenomina_gmm'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_otros',$data['excedente_otros'],PDO::PARAM_STR);
        $stmt->bindParam(':comentarios_nominas',$data['comentarios_nominas'],PDO::PARAM_STR);
        //$stmt->bindParam(':id_nominista',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt->bindParam(':id',$data['id_nomina'],PDO::PARAM_INT);
        
        return $stmt->execute();
        $stmt->close();
    }
    
    public static function actualizarNomina2($data,$tabla){
        $conexion = Conexion::conectar();
		$stmt = $conexion->prepare("UPDATE $tabla SET 
		id_cliente=:id_cliente,
        devengada=:devengada,
        tipo_sindicato=:tipo_sindicato,
        tipo_pago=:tipo_pago,
        regimen=:regimen,
        comision_porcentaje=:comision_porcentaje,
        empresa_facturadora=:empresa_facturadora,
        subtotal=:subtotal,
        iva=:iva,
        total=:total,
        empresa_imss=:empresa_imss,
        total_imss=:total_imss,
        empresa_asimilados=:empresa_asimilados,
        total_asimilados=:total_asimilados,
        tipo_periodo=:tipo_periodo,
        numero_periodo=:numero_periodo,
        socios=:socios,
        ingreso=:ingreso,
        infonavit=:infonavit,
        fonacot=:fonacot,
        donativo=:donativo,
        pension=:pension,
        excedente_cargas=:excedente_cargas,
        cargas_patronal=:cargas_patronal,
        isn=:isn,
        comision_monto=:comision_monto,
        imss_obrera=:imss_obrera,
        carga_social_imss=:carga_social_imss,
        prenomina_imss=:prenomina_imss,
        isr_isp=:isr_isp,
        isr_142=:isr_142,
        cuota_sindical=:cuota_sindical,
        despensa=:despensa,
        caja_ahorro=:caja_ahorro,
        descuento_imss=:descuento_imss,
        apoyo_sindical=:apoyo_sindical,
        descuento_comedor=:descuento_comedor,
        haberes=:haberes,
        otros=:otros,
        excedente_ingreso=:excedente_ingreso,
        excedente_isr=:excedente_isr,
        excedente_imss=:excedente_imss,
        excedente_gmm=:excedente_gmm,
        excedente_infonavit=:excedente_infonavit,
        excedente_fonacot=:excedente_fonacot,
        excedente_prestamos=:excedente_prestamos,
        excedente_pension=:excedente_pension,
        excedente_terceros=:excedente_terceros,
        excedente_clientes=:excedente_clientes,
        excedente_subsidio=:excedente_subsidio,
        excedente_recuperacion=:excedente_recuperacion,
        excedente_comision=:excedente_comision,
        excedente_prenomina=:excedente_prenomina,
        excedente_prenomina_gmm=:excedente_prenomina_gmm,
        excedente_otros=:excedente_otros,
        comentarios_nominas=:comentarios_nominas,
        descuentos_sys=:descuentos_sys,
        descuentos_asesores=:descuentos_asesores,
        descuentos_terceros=:descuentos_terceros,
        prestamos_empleados=:prestamos_empleados,
        prestamos_ayudate=:prestamos_ayudate,
        excedente_caja_ahorro=:excedente_caja_ahorro,
        retencion_iva=:retencion_iva,
        devengada_factura=:devengada_factura,
        ajuste_subsidio_empleo=:ajuste_subsidio_empleo,
        descuento_ayudate=:descuento_ayudate,
        retencion_isn=:retencion_isn
		WHERE id = :id");


        $stmt->bindParam(':id_cliente',$data['id_cliente'],PDO::PARAM_INT);
        $stmt->bindParam(':devengada',$data['devengada'],PDO::PARAM_INT);
        $stmt->bindParam(':tipo_sindicato',$data['tipo_sindicato'],PDO::PARAM_INT);
        $stmt->bindParam(':tipo_pago',$data['tipo_pago'],PDO::PARAM_INT);
        $stmt->bindParam(':regimen',$data['regimen'],PDO::PARAM_INT);
        $stmt->bindParam(':comision_porcentaje',$data['comision'],PDO::PARAM_STR);
        $stmt->bindParam(':empresa_facturadora',$data['empresa_facturadora'],PDO::PARAM_INT);
        $stmt->bindParam(':subtotal',$data['subtotal'],PDO::PARAM_STR);
        $stmt->bindParam(':iva',$data['iva'],PDO::PARAM_STR);
        $stmt->bindParam(':total',$data['total'],PDO::PARAM_STR);
        $stmt->bindParam(':empresa_imss',$data['empresa_imss'],PDO::PARAM_INT);
        $stmt->bindParam(':total_imss',$data['total_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':empresa_asimilados',$data['empresa_asimilados'],PDO::PARAM_INT);
        $stmt->bindParam(':total_asimilados',$data['total_asimilados'],PDO::PARAM_STR);
        $stmt->bindParam(':tipo_periodo',$data['tipo_periodo'],PDO::PARAM_INT);
        $stmt->bindParam(':numero_periodo',$data['numero_periodo'],PDO::PARAM_INT);
        $stmt->bindParam(':socios',$data['socios'],PDO::PARAM_INT);
        $stmt->bindParam(':ingreso',$data['ingreso'],PDO::PARAM_STR);
        $stmt->bindParam(':infonavit',$data['infonavit'],PDO::PARAM_STR);
        $stmt->bindParam(':fonacot',$data['fonacot'],PDO::PARAM_STR);
        $stmt->bindParam(':donativo',$data['donativo'],PDO::PARAM_STR);
        $stmt->bindParam(':pension',$data['pension'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_cargas',$data['excedente_cargas'],PDO::PARAM_STR);
        $stmt->bindParam(':cargas_patronal',$data['cargas_patronal'],PDO::PARAM_STR);
        $stmt->bindParam(':isn',$data['isn'],PDO::PARAM_STR);
        $stmt->bindParam(':comision_monto',$data['comision_monto'],PDO::PARAM_STR);
        $stmt->bindParam(':imss_obrera',$data['imss_obrera'],PDO::PARAM_STR);
        $stmt->bindParam(':carga_social_imss',$data['carga_social_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':prenomina_imss',$data['prenomina_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':isr_isp',$data['isr_isp'],PDO::PARAM_STR);
        $stmt->bindParam(':isr_142',$data['isr_142'],PDO::PARAM_STR);
        $stmt->bindParam(':cuota_sindical',$data['cuota_sindical'],PDO::PARAM_STR);
        $stmt->bindParam(':despensa',$data['despensa'],PDO::PARAM_STR);
        $stmt->bindParam(':caja_ahorro',$data['caja_ahorro'],PDO::PARAM_STR);
        $stmt->bindParam(':descuento_imss',$data['descuento_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':apoyo_sindical',$data['apoyo_sindical'],PDO::PARAM_STR);
        $stmt->bindParam(':descuento_comedor',$data['descuento_comedor'],PDO::PARAM_STR);
        $stmt->bindParam(':haberes',$data['haberes'],PDO::PARAM_STR);
        $stmt->bindParam(':otros',$data['otros'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_ingreso',$data['excedente_ingreso'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_isr',$data['excedente_isr'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_imss',$data['excedente_imss'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_gmm',$data['excedente_gmm'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_infonavit',$data['excedente_infonavit'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_fonacot',$data['excedente_fonacot'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_prestamos',$data['excedente_prestamos'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_pension',$data['excedente_pension'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_terceros',$data['excedente_terceros'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_clientes',$data['excedente_clientes'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_subsidio',$data['excedente_subsidio'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_recuperacion',$data['excedente_recuperacion'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_comision',$data['excedente_comision'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_prenomina',$data['excedente_prenomina'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_prenomina_gmm',$data['excedente_prenomina_gmm'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_otros',$data['excedente_otros'],PDO::PARAM_STR);
        $stmt->bindParam(':comentarios_nominas',$data['comentarios_nominas'],PDO::PARAM_STR);

        $stmt->bindParam(':descuentos_sys',$data['descuentos_sys'],PDO::PARAM_STR);
        $stmt->bindParam(':descuentos_asesores',$data['descuentos_asesores'],PDO::PARAM_STR);
        $stmt->bindParam(':descuentos_terceros',$data['descuentos_terceros'],PDO::PARAM_STR);
        $stmt->bindParam(':prestamos_empleados',$data['prestamos_empleados'],PDO::PARAM_STR);
        $stmt->bindParam(':prestamos_ayudate',$data['prestamos_ayudate'],PDO::PARAM_STR);
        $stmt->bindParam(':excedente_caja_ahorro',$data['excedente_caja_ahorro'],PDO::PARAM_STR);
        $stmt->bindParam(':retencion_iva',$data['retencion_iva'],PDO::PARAM_STR);
        $stmt->bindParam(':devengada_factura',$data['devengadaFactura'],PDO::PARAM_STR);
        $stmt->bindParam(':ajuste_subsidio_empleo',$data['ajuste_subsidio_empleo'],PDO::PARAM_STR);
        $stmt->bindParam(':descuento_ayudate',$data['descuento_ayudate'],PDO::PARAM_STR);
        $stmt->bindParam(':retencion_isn',$data['retencion_isn'],PDO::PARAM_STR);
       
        

        //$stmt->bindParam(':id_nominista',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt->bindParam(':id',$data['id_nomina'],PDO::PARAM_INT);
        
        return $stmt->execute();
        $stmt->close();
    }

    public static function contarRegistros($tabla,$data,$ubicacion){
        $where=self::where($data,$tabla,$ubicacion);
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(id) FROM $tabla $where");
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

    public static function idNominaCargar($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :id AND tesoreria_estatus = 4");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        if($stmt->execute())
            return array('error'=>false,'data'=>$stmt->fetch());
        else
            return array('error'=>true,'data'=>'');
        $stmt -> close(); 
    }

    public static function mostrarNominas($tabla,$limite,$data,$ubicacion){
        $where=self::where($data,$tabla,$ubicacion);
        $stmt = Conexion::conectar()->prepare("SELECT id,id_nominista,id_finanzas,id_tesoreria,tipo_pago,captura_nominista,esquema,observaciones,tesoreria_estatus,liberacion_nominas,id_cliente,id_facturacion,estatus_factura FROM $tabla $where ORDER BY id desc $limite");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt -> close(); 
    }

    public static function mostrarNominas2($tabla,$tabla2,$tabla3,$tabla4,$limite,$data,$ubicacion){
        $where=self::where($data,$tabla,$ubicacion);
        $stmt = Conexion::conectar()->prepare("SELECT $tabla.id,$tabla2.nombre AS cliente,$tabla4.nombre AS sucursal,observaciones,tesoreria_estatus,esquema,id_nominista, liberacion_nominas
                                               FROM $tabla 
                                               INNER JOIN $tabla2 ON $tabla.id_cliente   = $tabla2.id 
                                               INNER JOIN $tabla3 ON $tabla.id_nominista = $tabla3.id_usuario
                                               INNER JOIN $tabla4 ON $tabla3.id_sucursal = $tabla4.id_sucursal
                                               $where ORDER BY $tabla.id desc $limite");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt -> close(); 
    }

    public static function where($data,$tabla,$ubicacion){

        $where=' WHERE status_nominas = 1';

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
        if( $ubicacion === '/asesores/nominas' /* || $_SESSION['identificador']==172 || $_SESSION['identificador']==168*/){
            if( !empty($data['nominista']) AND $data['nominista'] !== "undefined" )
                $where .=" AND id_nominista =".intval($data['nominista']);
        }
            
        return $where;
    }

//CONCAT(nombre,' ',paterno,' ',materno) AS
    public static function datos($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT CONCAT(nombre,' ',paterno,' ',materno) AS nombre FROM $tabla WHERE id_usuario = :usuario");
        $stmt->bindParam(':usuario',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

    public static function datos2($id,$tabla,$tabla2,$tabla3){
        $stmt = Conexion::conectar()->prepare("SELECT CONCAT($tabla.nombre,' ',paterno,' ',materno) AS nombre, $tabla2.nombre AS sucursal,$tabla3.nombre AS puesto FROM $tabla INNER JOIN $tabla2 ON $tabla.id_sucursal = $tabla2.id_sucursal INNER JOIN $tabla3 ON $tabla.id_puesto = $tabla3.id_puesto WHERE id_usuario = :usuario");
        $stmt->bindParam(':usuario',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
        $stmt -> close(); 
    }

    public static function datos3($id,$tabla,$tabla2){
        $stmt = Conexion::conectar()->prepare("SELECT CONCAT($tabla.nombre,' ',paterno,' ',materno) AS nombre, $tabla2.nombre AS sucursal FROM $tabla INNER JOIN $tabla2 ON $tabla.id_sucursal = $tabla2.id_sucursal WHERE id_usuario = :usuario");
        $stmt->bindParam(':usuario',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
        $stmt -> close(); 
    }

    public static function mostrarDataNomina($idNomina,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$idNomina,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
        $stmt -> close(); 
    }

    public static function obtenerPorcentaje($cliente,$tabla,$manual=false){
        $stmt = Conexion::conectar()->prepare("SELECT porcentaje FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$cliente,PDO::PARAM_INT);
        if($manual){
            $stmt -> execute();
            return $stmt->fetch()[0];
        }
        else
            return array('error'=>!$stmt -> execute(),'comision'=>$stmt->fetch()[0]);
        $stmt -> close(); 
    }

    public static function verificarPermisoModificacion($nomina,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT id_nominista FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$nomina,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()[0];
        $stmt->close(); 
    }

    public static function verificarJefe($usuario,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT id_jefe FROM $tabla WHERE id_empleado = :id");
        $stmt->bindParam(':id',$usuario,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()[0];
        $stmt->close(); 
    }


    public static function actualizarFinanzas($data,$tabla){
        $conexion = Conexion::conectar();
		$stmt = $conexion->prepare("UPDATE $tabla SET 
        
        financiada=:financiada,
        fecha_envio=:fecha_envio,
        hora_envio=:hora_envio,
        observaciones=:observaciones,
        fecha_liberacion=:fecha_liberacion,
        fondeo_imss=:fondeo_imss,
        fondeo_asimilados=:fondeo_asimilados,
        comentarios_finanzas=:comentarios_finanzas,
        id_finanzas=:id_finanzas,
        captura_finanzas=NOW()
        WHERE id = :id");
        
        //$stmt->bindParam(':numero_factura',$data['numero_factura'],PDO::PARAM_STR);
        $stmt->bindParam(':financiada',$data['financiada'],PDO::PARAM_INT);
        $stmt->bindParam(':fecha_envio',$data['fecha_envio'],PDO::PARAM_STR);
        $stmt->bindParam(':hora_envio',$data['hora_envio'],PDO::PARAM_STR);
        $stmt->bindParam(':observaciones',$data['observaciones'],PDO::PARAM_INT);
        $stmt->bindParam(':fecha_liberacion',$data['fecha_liberacion'],PDO::PARAM_STR);
        $stmt->bindParam(':fondeo_imss',$data['fondeo_imss'],PDO::PARAM_INT);
        $stmt->bindParam(':fondeo_asimilados',$data['fondeo_asimilados'],PDO::PARAM_INT);
        $stmt->bindParam(':comentarios_finanzas',$data['comentarios_finanzas'],PDO::PARAM_STR);

        $stmt->bindParam(':id_finanzas',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt->bindParam(':id',$data['id_nomina'],PDO::PARAM_INT);

        if($stmt->execute())
            return json_encode(array('error'=>false,'titulo'=>'Proceso correcto','subtitulo'=>'La información se guardo correctamente'));
        else
            return json_encode(array('error'=>true,'titulo'=>'Ocurrio un error','subtitulo'=>'Intente guardar de nuevo'));
        $stmt->close();
    }

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

    public static function traducirLista($valor,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT id FROM $tabla WHERE nombre = :nombre");
        $stmt->bindParam(':nombre',$valor,PDO::PARAM_STR);
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close(); 
    }

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

    public static function insersionManualTesoreria($data,$tabla){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $data AND (id_tesoreria = :id OR id_tesoreria IS NULL) ");
        $stmt->bindParam(':id',$_SESSION['identificador'],PDO::PARAM_INT);
        return array('respuesta'=>$stmt->execute(),'total'=>$stmt->rowCount());
        $conexion -> close();	
    }

    public static function insersionManualFacturacion($data,$tabla){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $data AND (id_facturacion = :id OR id_facturacion IS NULL) ");
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

    public static function actualizarNominasLiberacion($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT id_nominista,captura_nominista FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
        $stmt -> close(); 
    }

    public static function actualizarTesoreriaLiberacion($id,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT id_tesoreria,captura_tesoreria FROM $tabla WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
        $stmt -> close(); 
    }

    public static function actualizarTesoreria($data,$tabla){
        $anterior = 0;
        $conexion = Conexion::conectar();

        if($data['estatus'] == 4){
            $stmt = Conexion::conectar()->prepare("SELECT tesoreria_estatus FROM $tabla WHERE id = :id");
            $stmt->bindParam(':id',$data['id_nomina'],PDO::PARAM_INT);
            $stmt -> execute();
            $anterior = $stmt -> fetch()[0];
        }


		$stmt = $conexion->prepare("UPDATE $tabla SET 
        tesoreria_estatus=:tesoreria_estatus,
        comentarios_tesoreria=:comentarios_tesoreria,
        id_tesoreria=:id_tesoreria,
        captura_tesoreria=NOW()
		WHERE id = :id");

        $stmt->bindParam(':tesoreria_estatus',$data['estatus'],PDO::PARAM_INT);
        $stmt->bindParam(':comentarios_tesoreria',$data['comentarios_tesoreria'],PDO::PARAM_STR);
        $stmt->bindParam(':id_tesoreria',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt->bindParam(':id',$data['id_nomina'],PDO::PARAM_INT);

        if($stmt->execute())
            return array('error'=>false,'titulo'=>'Proceso correcto','subtitulo'=>'La información se guardo correctamente','anterior'=> $anterior);
        else
            return array('error'=>true,'titulo'=>'Ocurrio un error','subtitulo'=>'Intente guardar de nuevo','anterior'=> $anterior);
        $stmt->close();
    }

    public static function dataEliminar($id,$tabla){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla set status_nominas = 0 WHERE id IN ($id) AND id_finanzas IS NULL");
        return $stmt -> execute();
        $stmt -> close(); 
    }

    public static function dataLiberar($id,$tabla){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET liberacion_nominas = 1, fecha_liberacion_nominas = NOW() WHERE id IN ($id)");
        return $stmt -> execute();
        $stmt -> close(); 
    }

    public static function verificarJefatura($tabla){
		$stmt = Conexion::conectar()->prepare("SELECT tipo_acceso FROM $tabla WHERE id_usuario = :id");
		$stmt->bindParam(':id',$_SESSION['identificador'],PDO::PARAM_INT);
		$stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close();	
    }

    public static function validarRegistro($id,$tabla,$departamento ='nominas'){
        if($departamento === 'nominas')
            $capturista = 'id_nominista';
        else if($departamento === 'finanzas')
            $capturista = 'id_finanzas';
        else
            $capturista = 'id_tesoreria';

		$stmt = Conexion::conectar()->prepare("SELECT COUNT(id) FROM $tabla WHERE $capturista = :capturista AND id =:nomina");
		$stmt->bindParam(':capturista',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt->bindParam(':nomina',$id,PDO::PARAM_INT);       
        $stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close();	
    }

    public static function obtenerEsquema($id,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT esquema FROM $tabla WHERE id = :id");
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close();	
    }

    
    public static function getDatosNominista($id,$tabla,$tabla2,$tabla3){
        $stmt = Conexion::conectar()->prepare("SELECT CONCAT(t.nombre,' ',paterno,' ',materno) AS tesorero, t2.nombre AS sucursal,t3.comentarios_tesoreria AS comentarios
                                                FROM $tabla AS t 
                                                INNER JOIN $tabla2 AS t2 ON t.id_sucursal = t2.id_sucursal 
                                                INNER JOIN $tabla3 AS t3 ON t.id_usuario = t3.id_tesoreria
                                                WHERE id = :id");
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt -> execute();
        return $stmt->fetch();
        $stmt -> close();	
    }

    public static function getCliente($id,$tabla){
		$stmt = Conexion::conectar()->prepare("SELECT nombre FROM $tabla WHERE id = :id");
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt -> execute();
        return $stmt -> fetch()[0];
        $stmt -> close();	
    }

    public static function actualizarFacturacion($data,$tabla){
        $conexion = Conexion::conectar();
		$stmt = $conexion->prepare("UPDATE $tabla SET 
        estatus_factura=:estatus,
        numero_factura=:factura,
        numero_nota_credito=:nota,
        fecha_pago_factura=:fecha,
        comentarios_facturacion=:comentarios,
        fecha_factura=:fecha_factura,
        id_facturacion=:id_facturacion,
        retencion_isn = :retencion_isn,
        total = :total, 
        captura_facturacion=NOW()
        WHERE id =:id");

        $stmt->bindParam(':estatus',$data['estatus'],PDO::PARAM_INT);
        $stmt->bindParam(':factura',$data['numeroFactura'],PDO::PARAM_STR);
        $stmt->bindParam(':nota',$data['numeroNota'],PDO::PARAM_STR);
        $stmt->bindParam(':fecha',$data['fechaPago'],PDO::PARAM_STR);
        $stmt->bindParam(':comentarios',$data['comentarios'],PDO::PARAM_STR);
        $stmt->bindParam(':fecha_factura',$data['fechaFactura'],PDO::PARAM_STR);
        $stmt->bindParam(':id_facturacion',$_SESSION['identificador'],PDO::PARAM_INT);
        $stmt->bindParam(':id',$data['id'],PDO::PARAM_INT);
        $stmt->bindParam(':retencion_isn',$data['retencion_isn'],PDO::PARAM_STR);
        $stmt->bindParam(':total',$data['total'],PDO::PARAM_STR);

        if($stmt->execute()){
            $usuario = self::datos2($_SESSION['identificador'],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
            $html='<div class="row">
                        <div class="col-md-6">
                            <span><b>Nombre:</b> '.$usuario['nombre'].'</span>
                        </div>
                        <div class="col-md-6">
                            <span><b>Sucursal:</b> '.$usuario['sucursal'].'</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <span><b>Puesto:</b>  '.$usuario['puesto'].' </span>
                        </div>
                        <div class="col-md-6">
                            <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha(date('Y-m-d'),true).' - '.date('H:i:s').' </span></span>
                        </div>
                    </div>';
            return array('error'=>false,'titulo'=>'Proceso correcto','subtitulo'=>'La información se guardo correctamente','html'=>$html);
        }
        else
            return json_encode(array('error'=>true,'titulo'=>'Ocurrio un error','subtitulo'=>'Intente guardar de nuevo'));
        $stmt->close();
    }
    
    

}
