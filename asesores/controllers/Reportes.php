<?php
//require "views/excel/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;//clases de excel cargadas con el autoload de la libreria
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
//require_once 'MetodoMartin.php';
//require_once 'models/CostosModel.php';

class Reportes{

    public static function usuarios($sucursal){
				$respuesta=Datos::usuariosEquiposComputo($sucursal,Tablas::usuarios());
				$data = array();
				foreach($respuesta as $row => $item){
					array_push($data,array('id'=>$item["id_usuario"],'name'=>$item["nombre"].' '.$item["paterno"].' '.$item["materno"]));
				}
				return $data;
	}
	
	public static function usuarios2($sucursal){
		$respuesta=Datos::usuariosEquiposComputo($sucursal,Tablas::usuarios());
		$html= '<option value=""></option>';
        foreach( $respuesta as $row=>$item)
            $html.='<option value="'.$item['id_usuario'].'">'.$item["nombre"].' '.$item["paterno"].' '.$item["materno"].'</option>';
        return $html;
}

    public static function reporte_permisos($data){
				$respuesta = ReportesModel::reporte_permisos($data,Tablas::usuarios(),Tablas::permisos(),Tablas::sucursales(),Tablas::departamentosIntranet(),Tablas::puestos());
				return $respuesta;
    }
    
    public static function reporte_vacaciones($data){
				$respuesta = ReportesModel::reporte_vacaciones($data,Tablas::usuarios(),Tablas::sucursales());
				return $respuesta;
	}

	public static function vacacionesDisponibles($data,$anio){
		$respuesta = ReportesModel::vacacionesDisponibles($data,$anio,Tablas::bitacora());
		return $respuesta;
}

	public static function reportes_nutrifitness($data,$vuelta){
				$tabla="";
				if($data == 1)
					$tabla = Tablas::laboratorio();
				else if($data == 2)
					$tabla = Tablas::composicion();
				$respuesta = ReportesModel::reporte_nutrifitness(Tablas::usuarios(),$tabla,$vuelta,Tablas::sucursales());
				return $respuesta;
	}


	public static function nominas($data,$fechaInicial='',$fechaFinal='',$nominista='',$tipo=''){
		$respuesta = ReportesModel::nominas($data,$fechaInicial,$fechaFinal,$nominista,$tipo,Tablas::nominas_liberacion(),Tablas::clientes(),Tablas::facturadoras(),Tablas::usuarios(),Tablas::sucursales());
		return $respuesta;
	}

	/*********************BORRAR */
	public static function encabezadosNominasCompleto($hoja,$liberada=false){
		$columna=1;
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"No. NÓMINA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESQUEMA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PAGADORA SINDICATO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÓMINA DEVENGADA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PAGO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RÉGIMEN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA QUE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBTOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN ISR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE POR ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SOCIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS SUELDOS Y SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS ASESORES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS TERCEROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INGRESO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DONATIVO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EXCEDENTE DE CARGAS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA PATRONAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS OBRERA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA SOCIAL IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR/ISP(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR art. 142");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CUOTA SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESPENSA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO GENERALES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"APOYO SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS COMEDOR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HABERES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBSIDIO(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS EMPLEADO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS AYUDATE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE SUBSIDIO PARA EL EMPLEO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ASIMILADOS A SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ANTICIPO A RENDIMIENTOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RECUPERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN SOCIO O CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO AYUDATE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");
		if(!$liberada){
			for ($i = 'A'; $i !== 'BT'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		}
		else{
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"AUTORIZADA");
			for ($i = 'A'; $i !== 'BU'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		}
	}

	public static function encabezadosNominasCompletoNuevaVersion($hoja,$liberada=false){
		$columna=1;
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"No. NÓMINA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESQUEMA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PAGADORA SINDICATO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÓMINA DEVENGADA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PAGO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RÉGIMEN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA QUE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBTOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN ISR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE POR ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SOCIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS SUELDOS Y SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS ASESORES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS TERCEROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INGRESO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DONATIVO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EXCEDENTE DE CARGAS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA PATRONAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS OBRERA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA SOCIAL IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR/ISP(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR art. 142");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CUOTA SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESPENSA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO GENERALES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"APOYO SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS COMEDOR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HABERES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBSIDIO(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS EMPLEADO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS AYUDATE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE SUBSIDIO PARA EL EMPLEO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GASTOS MÉDICOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ASIMILADOS A SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ANTICIPO A RENDIMIENTOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SINDICATO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TARJETA EMPRESARIAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MODALIDAD 40");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RECUPERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN SOCIO O CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO AYUDATE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");
		if(!$liberada){
			for ($i = 'A'; $i !== 'BX'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		}
		else{
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"AUTORIZADA");
			for ($i = 'A'; $i !== 'BY'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		}
	}

	public static function encabezadosNominasCompletoNuevaVersion2($hoja,$liberada=false){
		$columna=1;
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"No. NÓMINA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESQUEMA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PAGADORA SINDICATO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÓMINA DEVENGADA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PAGO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RÉGIMEN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA QUE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBTOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN ISR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUELDOS Y SALARIOS 4%");////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITAR SIN TIMBRE");////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE POR ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ASIMILADOS 4%");/////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SOCIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS SUELDOS Y SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS ASESORES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS TERCEROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INGRESO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DONATIVO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EXCEDENTE DE CARGAS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA PATRONAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS OBRERA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA SOCIAL IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR/ISP(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR art. 142");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CUOTA SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESPENSA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO GENERALES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"APOYO SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS COMEDOR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HABERES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HABERES 5%");//////////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBSIDIO(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS EMPLEADO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS AYUDATE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE SUBSIDIO PARA EL EMPLEO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GASTOS MÉDICOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ASIMILADOS A SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ANTICIPO A RENDIMIENTOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SINDICATO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SINDICATO 2%");////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TARJETA EMPRESARIAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TARJETA EMPRESARIAL 2%");//////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MODALIDAD 40");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MODALIDAD 40 4%");//////////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RECUPERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN SOCIO O CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO AYUDATE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");
		if(!$liberada){
			for ($i = 'A'; $i !== 'BX'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		}
		else{
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"AUTORIZADA");
			for ($i = 'A'; $i !== 'CG'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		}
	}

	/********borrar */
	public static function encabezadosNominasCompletoLayout($hoja){
		$columna=1;
		$fila=3;
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"No. NÓMINA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESQUEMA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PAGADORA SINDICATO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÓMINA DEVENGADA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PAGO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RÉGIMEN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA QUE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBTOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN DEL IVA AL 6%");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN ISN");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"IVA");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE POR ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SOCIOS");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS SUELDOS Y SALARIOS");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS ASESORES");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS TERCEROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INGRESO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DONATIVO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EXCEDENTE DE CARGAS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA PATRONAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS OBRERA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA SOCIAL IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR/ISP(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR art. 142");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CUOTA SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESPENSA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO GENERALES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"APOYO SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS COMEDOR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HABERES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBSIDIO(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS EMPLEADO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS AYUDATE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE SUBSIDIO PARA EL EMPLEO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ASIMILADOS A SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ANTICIPO A RENDIMIENTOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RECUPERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN SOCIO O CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURAL");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");

		$letra='A';
    	$letras=array();
		for($i=0;$i<$columna;$i++) 
        	$letras[$i] = $letra++;    
	
		$columna=1;
		$fila=4;

		for($i=0;$i<6;$i++){
			$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		}

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');

		/*for($i=0;$i<3;$i++){
			$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
			$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');

			$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		}*/

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		for($i=0;$i<2;$i++){
			$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'NÚMERO ENTERO');
		}
			
		for($i=0;$i<42;$i++){
			$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
			$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		}

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'TEXTO');
	
		for ($i = 'A'; $i !== 'BI'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		$hoja->getColumnDimension('BI')->setWidth(120);
		
		return $letras;
	
	}

	public static function encabezadosNominasCompletoLayoutNuevaVersion($hoja){
		$columna=1;
		$fila=3;
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"No. NÓMINA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESQUEMA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PAGADORA SINDICATO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÓMINA DEVENGADA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PAGO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RÉGIMEN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA QUE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBTOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN DEL IVA AL 6%");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN ISN");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"IVA");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE POR ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SOCIOS");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS SUELDOS Y SALARIOS");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS ASESORES");
		//$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS TERCEROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INGRESO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DONATIVO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EXCEDENTE DE CARGAS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA PATRONAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS OBRERA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CARGA SOCIAL IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR/ISP(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR art. 142");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CUOTA SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESPENSA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO GENERALES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"APOYO SINDICAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS COMEDOR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HABERES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBSIDIO(SP)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS EMPLEADO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS AYUDATE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE SUBSIDIO PARA EL EMPLEO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GASTOS MÉDICOS");////////////////////////////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ASIMILADOS A SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ANTICIPO A RENDIMIENTOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SINDICATO");////////////////////////////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TARJETA EMPRESARIAL");////////////////////////////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MODALIDAD 40");////////////////////////////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ISR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONACOT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRESTAMOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PENSIÓN ALIMENTICIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RECUPERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN SOCIO O CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PRENÓMINA GMM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAJA DE AHORRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTO AYUDATE");////////////////////////////////////////////////
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		
		$letra='A';
    	$letras=array();
		for($i=0;$i<$columna;$i++) 
        	$letras[$i] = $letra++;    
	
		$columna=1;
		$fila=4;

		for($i=0;$i<6;$i++){
			$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		}

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		for($i=0;$i<2;$i++){
			$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'NÚMERO ENTERO');
		}
			
		for($i=0;$i<47;$i++){
			$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
			$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		}

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'TEXTO');
	
		for ($i = 'A'; $i !== 'BN'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		$hoja->getColumnDimension('BN')->setWidth(120);
		
		return $letras;
	
	}

	
	public function encabezadosNominasResumen($hoja){
		$columna=1;
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"No. NÓMINA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESQUEMA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PAGADORA SINDICATO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÓMINA DEVENGADA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PAGO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RÉGIMEN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMISIÓN(MONTO)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA QUE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBTOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN DEL IVA AL 6 %");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN ISN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA PAGADORA ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL A DEPOSITARLE POR ASIMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE PERIODO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SOCIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS SUELDOS Y SALARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS ASESORES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCUENTOS TERCEROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");
		for ($i = 'A'; $i !== 'AD'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
	}

	public function encabezadosFinanzasCompleto($hoja,$columna,$inicio,$fin){
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FINANCIADA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA Y HORA DEL DEPOSITO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESTATUS DE LIBERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE LIBERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONDEO IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONDEO ASMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAPTURÓ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");
		for ($i = $inicio; $i !== $fin; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
	}

	public function encabezadosFinanzasCompletoLayout($hoja,$columna,$inicio,$fin,$tesoreria=false){
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FINANCIADA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DEL DEPOSITO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA DEL DEPOSITO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESTATUS DE LIBERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE LIBERACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONDEO IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FONDEO ASMILADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		if($tesoreria){
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAPTURÓ");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");
		}
		
		for ($i = $inicio; $i !== $fin; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
	}

	public function encabezadosFacturacionCompleto($hoja,$columna,$inicio,$fin,$layout=true){
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBTOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IVA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RETENCIÓN ISN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESTATUS DE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE NOTA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE PAGO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		if($layout){
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAPTURÓ");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");
		}
		for ($i = $inicio; $i !== $fin; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
	}

	
	public function encabezadosTesoreriaCompleto($hoja,$columna,$inicio,$fin,$layout=true){
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESTATUS DE PAGO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		if($layout){
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAPTURÓ");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA");
			$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA");
		}
		for ($i = $inicio; $i !== $fin; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
	}

	/*********************BORRAR */
	public static function filasreporteSucursalNominas($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$capturaNominista = explode ( " ", $item['captura_nominista']);
			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' : Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['donativo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_cargas']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cargas_patronal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_monto']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss_obrera']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['carga_social_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prenomina_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_isp']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_142']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cuota_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['despensa']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['caja_ahorro']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['apoyo_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_comedor']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['haberes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_subsidio']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_empleado']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_ayudate']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_subsidio_empleo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_terceros']);//ingresos sin timbrar
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_isr']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prestamos']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_clientes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_recuperacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_comision']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_caja_ahorro']);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_ayudate']);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);
			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	public static function filasreporteSucursalNominasNuevaVersion($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$capturaNominista = explode ( " ", $item['captura_nominista']);
			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' : Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['donativo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_cargas']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cargas_patronal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_monto']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss_obrera']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['carga_social_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prenomina_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_isp']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_142']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cuota_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['despensa']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['caja_ahorro']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['apoyo_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_comedor']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['haberes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_subsidio']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_empleados']);////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_ayudate']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_subsidio_empleo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['gastos_medicos2']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_terceros']);//ingresos sin timbrar
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sindicato']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tarjeta_empresarial']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['modalidad_40']);/////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_isr']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prestamos']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_clientes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_recuperacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_comision']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_caja_ahorro']);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_ayudate']);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);
			$columna=1;
			$fila++;
		}  
		return $fila;
	}



	public static function filasreporteSucursalFinanzas($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$finanzas=$capturaFinanzas;

			$capturaNominista = explode ( " ", $item['captura_nominista']);

			if($item["id_finanzas"] !==NULL){
				$finanzas = NominasModel::datos3($item["id_finanzas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFinanzas = explode ( " ", $item['captura_finanzas']);
			}

			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' :Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );	
			$hoja->setCellValueByColumnAndRow($columna++,$fila, ($item['fecha_envio'] !== NULL ? substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4) : '').' - '.($item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : ''));					
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirObservaciones($item['observaciones']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fecha_liberacion'] !== NULL ? substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_finanzas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaFinanzas[0],8,2).'/'.substr($capturaFinanzas[0],5,2).'/'.substr($capturaFinanzas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[1]);

			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	public static function filasreporteSucursalFacturacion($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$finanzas=$capturaFinanzas=$facturacion=$capturaFacturacion='';

			$capturaNominista = explode ( " ", $item['captura_nominista']);

			if($item["id_finanzas"] !==NULL){
				$finanzas = NominasModel::datos3($item["id_finanzas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFinanzas = explode ( " ", $item['captura_finanzas']);
			}
			if($item["id_facturacion"] !==NULL){
				$facturacion = NominasModel::datos3($item["id_facturacion"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFacturacion = explode ( " ", $item['captura_facturacion']);
			}

			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' :Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );	
			$hoja->setCellValueByColumnAndRow($columna++,$fila,($item['fecha_envio'] !== NULL ? substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4) : '').' - '.($item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : ''));					
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['observaciones'] == NULL ? '' : Nominas::traducirObservaciones($item['observaciones']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_liberacion'] !== NULL ? substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_finanzas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaFinanzas[0],8,2).'/'.substr($capturaFinanzas[0],5,2).'/'.substr($capturaFinanzas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['estatus_factura'] == NULL ? '' : Nominas::traducirEstatusFactura($item['estatus_factura']) );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_nota_credito']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($item['fecha_factura'],8,2).'/'.substr($item['fecha_factura'],5,2).'/'.substr($item['fecha_factura'],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($item['fecha_pago_factura'],8,2).'/'.substr($item['fecha_pago_factura'],5,2).'/'.substr($item['fecha_pago_factura'],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_facturacion']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$facturacion['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$facturacion['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaFacturacion[0],8,2).'/'.substr($capturaFacturacion[0],5,2).'/'.substr($capturaFacturacion[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFacturacion[1]);

			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	public static function filasreporteSucursalTesoreria($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$finanzas=$capturaFinanzas=$tesoreria=$capturaTesoreria='';

			$capturaNominista = explode ( " ", $item['captura_nominista']);

			if($item["id_finanzas"] !==NULL){
				$finanzas = NominasModel::datos3($item["id_finanzas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFinanzas = explode ( " ", $item['captura_finanzas']);
			}
			if($item["id_tesoreria"] !==NULL){
				$tesoreria = NominasModel::datos3($item["id_tesoreria"],Tablas::usuarios(),Tablas::sucursales());
				$capturaTesoreria = explode ( " ", $item['captura_tesoreria']);
			}

			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' :Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );	
			$hoja->setCellValueByColumnAndRow($columna++,$fila,($item['fecha_envio'] !== NULL ? substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4) : '').' - '.($item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : ''));					
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['observaciones'] == NULL ? '' : Nominas::traducirObservaciones($item['observaciones']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_liberacion'] !== NULL ? substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_finanzas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaFinanzas[0],8,2).'/'.substr($capturaFinanzas[0],5,2).'/'.substr($capturaFinanzas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tesoreria_estatus'] == NULL ? '' : Nominas::traducirEstatusNominas($item['tesoreria_estatus']) );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_tesoreria']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$tesoreria['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$tesoreria['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaTesoreria[0],8,2).'/'.substr($capturaTesoreria[0],5,2).'/'.substr($capturaTesoreria[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaTesoreria[1]);

			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	/*******************Eliminación */
	public static function filasreporteTablaLiberacion($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$finanzas=$capturaFinanzas=$tesoreria=$capturaTesoreria='';

			$capturaNominista = explode ( " ", $item['captura_nominista']);

			if($item["id_finanzas"] !==NULL){
				$finanzas = NominasModel::datos3($item["id_finanzas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFinanzas = explode ( " ", $item['captura_finanzas']);
			}
			if($item["id_tesoreria"] !==NULL){
				$tesoreria = NominasModel::datos3($item["id_tesoreria"],Tablas::usuarios(),Tablas::sucursales());
				$capturaTesoreria = explode ( " ", $item['captura_tesoreria']);
			}

			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' : Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);


			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['donativo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_cargas']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cargas_patronal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_monto']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss_obrera']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['carga_social_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prenomina_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_isp']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_142']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cuota_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['despensa']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['caja_ahorro']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['apoyo_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_comedor']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['haberes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_subsidio']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_empleado']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_ayudate']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_subsidio_empleo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_terceros']);//ingresos sin timbrar
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_isr']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prestamos']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_clientes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_recuperacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_comision']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_caja_ahorro']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_ayudate']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['liberacion_nominas'] == 1 ? 'SÍ' : 'NO');

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );	
			$hoja->setCellValueByColumnAndRow($columna++,$fila,($item['fecha_envio'] !== NULL ? substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4) : '').' - '.($item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : ''));					
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['observaciones'] == NULL ? '' : Nominas::traducirObservaciones($item['observaciones']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_liberacion'] !== NULL ? substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_finanzas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[0] == NULL ? '' :  substr($capturaFinanzas[0],8,2).'/'.substr($capturaFinanzas[0],5,2).'/'.substr($capturaFinanzas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tesoreria_estatus'] == NULL ? '' : Nominas::traducirEstatusNominas($item['tesoreria_estatus']) );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_tesoreria']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$tesoreria['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$tesoreria['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaTesoreria[0] == NULL ? '' :   substr($capturaTesoreria[0],8,2).'/'.substr($capturaTesoreria[0],5,2).'/'.substr($capturaTesoreria[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaTesoreria[1]);

			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	public static function filasreporteTablaLiberacionNuevaVersion($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$finanzas=$capturaFinanzas=$tesoreria=$capturaTesoreria='';

			$capturaNominista = explode ( " ", $item['captura_nominista']);

			if($item["id_finanzas"] !==NULL){
				$finanzas = NominasModel::datos3($item["id_finanzas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFinanzas = explode ( " ", $item['captura_finanzas']);
			}
			if($item["id_tesoreria"] !==NULL){
				$tesoreria = NominasModel::datos3($item["id_tesoreria"],Tablas::usuarios(),Tablas::sucursales());
				$capturaTesoreria = explode ( " ", $item['captura_tesoreria']);
			}

			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' : Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);


			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['donativo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_cargas']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cargas_patronal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_monto']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss_obrera']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['carga_social_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prenomina_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_isp']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_142']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cuota_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['despensa']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['caja_ahorro']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['apoyo_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_comedor']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['haberes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_subsidio']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_empleado']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_ayudate']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_subsidio_empleo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['gastos_medicos2']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_terceros']);//ingresos sin timbrar
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sindicato']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tarjeta_empresarial']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['modalidad_40']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_isr']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prestamos']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_clientes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_recuperacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_comision']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_caja_ahorro']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_ayudate']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['liberacion_nominas'] == 1 ? 'SÍ' : 'NO');

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );	
			$hoja->setCellValueByColumnAndRow($columna++,$fila,($item['fecha_envio'] !== NULL ? substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4) : '').' - '.($item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : ''));					
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['observaciones'] == NULL ? '' : Nominas::traducirObservaciones($item['observaciones']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_liberacion'] !== NULL ? substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_finanzas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[0] == NULL ? '' :  substr($capturaFinanzas[0],8,2).'/'.substr($capturaFinanzas[0],5,2).'/'.substr($capturaFinanzas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tesoreria_estatus'] == NULL ? '' : Nominas::traducirEstatusNominas($item['tesoreria_estatus']) );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_tesoreria']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$tesoreria['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$tesoreria['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaTesoreria[0] == NULL ? '' :   substr($capturaTesoreria[0],8,2).'/'.substr($capturaTesoreria[0],5,2).'/'.substr($capturaTesoreria[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaTesoreria[1]);

			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	public static function filasreporteTablaLiberacionNuevaVersion2($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$finanzas=$capturaFinanzas=$tesoreria=$capturaTesoreria='';

			$capturaNominista = explode ( " ", $item['captura_nominista']);

			if($item["id_finanzas"] !==NULL){
				$finanzas = NominasModel::datos3($item["id_finanzas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFinanzas = explode ( " ", $item['captura_finanzas']);
			}
			if($item["id_tesoreria"] !==NULL){
				$tesoreria = NominasModel::datos3($item["id_tesoreria"],Tablas::usuarios(),Tablas::sucursales());
				$capturaTesoreria = explode ( " ", $item['captura_tesoreria']);
			}

			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss'] == NULL ? '' :$item['total_imss'] * .04);/////////////////////////////////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_terceros']);///////////////////////////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados'] == NULL ? '' : $item['total_asimilados']* .04);/////////////////////////////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' : Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);


			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['donativo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_cargas']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cargas_patronal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_monto']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss_obrera']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['carga_social_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prenomina_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_isp']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['isr_142']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cuota_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['despensa']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['caja_ahorro']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['apoyo_sindical']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_comedor']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['haberes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['haberes'] == NULL ? '' : $item['haberes'] * .05);///////////////////////////////////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_subsidio']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_empleado']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['prestamos_ayudate']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_subsidio_empleo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['gastos_medicos2']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_ingreso']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_terceros']);//ingresos sin timbrar
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sindicato']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sindicato']  == NULL ? '' : $item['sindicato']* .02);/////////////////////////////////////////////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tarjeta_empresarial']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tarjeta_empresarial']  == NULL ? '' : $item['tarjeta_empresarial'] * .02);///////////////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['modalidad_40']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['modalidad_40']  == NULL ? '' : $item['modalidad_40'] * .04);/////////////////////
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_isr']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_fonacot']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prestamos']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_pension']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_clientes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_recuperacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_comision']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_prenomina_gmm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_caja_ahorro']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuento_ayudate']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['excedente_otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['liberacion_nominas'] == 1 ? 'SÍ' : 'NO');

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );	
			$hoja->setCellValueByColumnAndRow($columna++,$fila,($item['fecha_envio'] !== NULL ? substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4) : '').' - '.($item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : ''));					
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['observaciones'] == NULL ? '' : Nominas::traducirObservaciones($item['observaciones']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_liberacion'] !== NULL ? substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_finanzas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[0] == NULL ? '' :  substr($capturaFinanzas[0],8,2).'/'.substr($capturaFinanzas[0],5,2).'/'.substr($capturaFinanzas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tesoreria_estatus'] == NULL ? '' : Nominas::traducirEstatusNominas($item['tesoreria_estatus']) );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_tesoreria']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$tesoreria['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$tesoreria['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaTesoreria[0] == NULL ? '' :   substr($capturaTesoreria[0],8,2).'/'.substr($capturaTesoreria[0],5,2).'/'.substr($capturaTesoreria[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaTesoreria[1]);

			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	/*************borrar*********************/
	public static function reporteSucursalNominas($fechaInicial,$fechaFinal,$nombre,$tipo){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales') // última vez modificado por
			->setTitle('Reporte nóminas')
			->setSubject('Reporte nóminas')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de Nóminas');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Nóminas");
	
		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:BT1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "SUELDOS Y SALARIOS");
		$hoja->mergeCells('Y2:AX2');
		$hoja->getStyle('Y2:AX2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');
		$hoja->getStyle('Y3:AX3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');

		$hoja->setCellValue("AY2", "DESCUENTOS AL TRABAJADOR (EXCEDENTE)");
		$hoja->mergeCells('AY2:BP2');
		$hoja->getStyle('AY2:BP2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		$hoja->getStyle('AY3:BP3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');

		$hoja->setCellValue("BQ2", "CAPTURÓ");
		$hoja->mergeCells('BQ2:BT2');
		$hoja->getStyle('BQ2:BT2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('BQ3:BT3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->getStyle('A1:BT3')->getFont()->setBold(true);
		$hoja->getStyle('A1:BT3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:BT3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:BT3');
		$documento->getActiveSheet()->freezePane('A4');
	
		self::encabezadosNominasCompleto($hoja);
		$fila = self::filasreporteSucursalNominas($hoja,self::nominas(4,$fechaInicial,$fechaFinal,$nombre,$tipo));

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BR4:BR'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BS4:BS'.$fila);
		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		for ($i = 'V'; $i !== 'BP'; $i++)
			$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), $i.'4:'.$i.$fila);
		return $documento;
	}

	public static function reporteSucursalNominasNuevaVersion($fechaInicial,$fechaFinal,$nombre,$tipo){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Reporte nóminas')
			->setSubject('Reporte nóminas')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de Nóminas');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Nóminas");
	
		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:BX1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "SUELDOS Y SALARIOS");
		$hoja->mergeCells('Y2:AY2');
		$hoja->getStyle('Y2:AY2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');
		$hoja->getStyle('Y3:AY3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');

		$hoja->setCellValue("AZ2", "DESCUENTOS AL TRABAJADOR (EXCEDENTE)");
		$hoja->mergeCells('AZ2:BT2');
		$hoja->getStyle('AZ2:BT2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		$hoja->getStyle('AZ3:BT3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		
		
		$hoja->setCellValue("BU2", "CAPTURÓ");
		$hoja->mergeCells('BU2:BX2');
		$hoja->getStyle('BU2:BX2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('BU3:BX3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->getStyle('A1:BX3')->getFont()->setBold(true);
		$hoja->getStyle('A1:BX3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:BX3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:BX3');
		$documento->getActiveSheet()->freezePane('A4');
	
		self::encabezadosNominasCompletoNuevaVersion($hoja);
		$fila = self::filasreporteSucursalNominasNuevaVersion($hoja,self::nominas(4,$fechaInicial,$fechaFinal,$nombre,$tipo));

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BR4:BR'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BS4:BS'.$fila);
		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		for ($i = 'V'; $i !== 'BT'; $i++)
			$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), $i.'4:'.$i.$fila);
		return $documento;
	}

	public static function reporteSucursalFinanzas($fechaInicial,$fechaFinal,$nombre,$tipo){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Reporte finanzas')
			->setSubject('Reporte finanzas')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de finanzas');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Reporte");

		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:AC1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('Y2:AC2');
		$hoja->getStyle('Y2:AC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('Y3:AC3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AD1", "FINANZAS");
		$hoja->mergeCells('AD1:AN1');
		$hoja->getStyle('AD1:AN1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD2:AN2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD3:AN3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->setCellValue("AJ2", "CAPTURÓ FINANZAS");
		$hoja->mergeCells('AJ2:AN2');
		$hoja->getStyle('AJ2:AN2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('AJ3:AN3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		
		$hoja->getStyle('A1:AN3')->getFont()->setBold(true);
		$hoja->getStyle('A1:AN3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:AN3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:AN3');
		$documento->getActiveSheet()->freezePane('A4');

		self::encabezadosNominasResumen($hoja);
		self::encabezadosFinanzasCompleto($hoja,30,'AD','AN');
		$fila = self::filasreporteSucursalFinanzas($hoja,self::nominas(6,$fechaInicial,$fechaFinal,$nombre,$tipo));

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AB4:AB'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AC4:AC'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AD4:AD'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AE4:AE'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AF4:AF'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AG4:AG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AH4:AH'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AI4:AI'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AM4:AM'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AN4:AN'.$fila);
		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'V4:V'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'W4:W'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'X4:X'.$fila);
		
		return $documento;
	}

	public static function reporteSucursalFacturacion($fechaInicial,$fechaFinal,$nombre,$tipo){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Reporte facturación')
			->setSubject('Reporte facturación')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de facturación');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Reporte");

		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:AC1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('Y2:AC2');
		$hoja->getStyle('Y2:AC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('Y3:AC3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AD1", "FINANZAS");
		$hoja->mergeCells('AD1:AN1');
		$hoja->getStyle('AD1:AN1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD2:AI2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD3:AI3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->setCellValue("AJ2", "CAPTURÓ FINANZAS");
		$hoja->mergeCells('AJ2:AN2');
		$hoja->getStyle('AJ1:AN2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('AJ3:AN3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AO1", "FACTURACIÓN");
		$hoja->mergeCells('AO1:BC1');
		$hoja->getStyle('AO1:BC1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('811363');
		$hoja->getStyle('AO2:AX2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('811363');
		$hoja->getStyle('AO3:AX3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('811363');

		$hoja->setCellValue("AY2", "CAPTURÓ FACTURA");
		$hoja->mergeCells('AY2:BC2');
		$hoja->getStyle('AY2:BC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('AY3:BC3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		
		$hoja->getStyle('A1:BC3')->getFont()->setBold(true);
		$hoja->getStyle('A1:BC3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:BC3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:BC3');
		$documento->getActiveSheet()->freezePane('A4');

		self::encabezadosNominasResumen($hoja);
		self::encabezadosFinanzasCompleto($hoja,30,'AD','AO');
		self::encabezadosFacturacionCompleto($hoja,41,'AO','BD');

		$fila = self::filasreporteSucursalFacturacion($hoja,self::nominas(7,$fechaInicial,$fechaFinal,$nombre,$tipo));

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AB4:AB'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AC4:AC'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AD4:AD'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AE4:AE'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AF4:AF'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AG4:AG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AH4:AH'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AI4:AI'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AM4:AM'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AN4:AN'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AO4:AO'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AT4:AT'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AU4:AU'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AV4:AV'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AW4:AW'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AX4:AX'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BB4:BB'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BC4:BC'.$fila);
		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'V4:V'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'W4:W'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'X4:X'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AO4:AO'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AP4:AP'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AQ4:AQ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AR4:AR'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AS4:AS'.$fila);
		return $documento;
	}

	public static function reporteSucursalTesoreria($fechaInicial,$fechaFinal,$nombre,$tipo){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Reporte tesorería')
			->setSubject('Reporte tesorería')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de tesorería');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Reporte");

		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:AC1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('Y2:AC2');
		$hoja->getStyle('Y2:AC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('Y3:AC3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AD1", "FINANZAS");
		$hoja->mergeCells('AD1:AN1');
		$hoja->getStyle('AD1:AN1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD2:AI2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD3:AI3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->setCellValue("AJ2", "CAPTURÓ FINANZAS");
		$hoja->mergeCells('AJ2:AN2');
		$hoja->getStyle('AJ1:AN2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('AJ3:AN3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AO1", "TESORERIA");
		$hoja->mergeCells('AO1:AT1');
		$hoja->getStyle('AO1:AT1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('AO2:AO2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('AO3:AO3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');

		$hoja->setCellValue("AP2", "CAPTURÓ TESORERIA");
		$hoja->mergeCells('AP2:AT2');
		$hoja->getStyle('AP2:AT2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('AP3:AT3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		
		$hoja->getStyle('A1:AT3')->getFont()->setBold(true);
		$hoja->getStyle('A1:AT3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:AT3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:AT3');
		$documento->getActiveSheet()->freezePane('A4');

		self::encabezadosNominasResumen($hoja);
		self::encabezadosFinanzasCompleto($hoja,30,'AD','AO');
		self::encabezadosTesoreriaCompleto($hoja,41,'AO','AU');

		$fila = self::filasreporteSucursalTesoreria($hoja,self::nominas(5,$fechaInicial,$fechaFinal,$nombre,$tipo));

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AB4:AB'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AC4:AC'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AD4:AD'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AE4:AE'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AF4:AF'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AG4:AG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AH4:AH'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AI4:IA'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AM4:AM'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AN4:AN'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AO4:AO'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AS4:AS'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AT4:AT'.$fila);
		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'V4:V'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'W4:W'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'X4:X'.$fila);
		return $documento;
	}

	/************Borrar */
	public static function reporteTablaLiberacion($fechaInicial,$fechaFinal){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales') // última vez modificado por
			->setTitle('Reporte tabla de liberación')
			->setSubject('Reporte tabla de liberación')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo tabla de liberación');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Nóminas");
	
		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:BU1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('BU1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('BU2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('BU3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "SUELDOS Y SALARIOS");
		$hoja->mergeCells('Y2:AX2');
		$hoja->getStyle('Y2:AX2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');
		$hoja->getStyle('Y3:AX3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');

		$hoja->setCellValue("AY2", "DESCUENTOS AL TRABAJADOR (EXCEDENTE)");
		$hoja->mergeCells('AY2:BO2');
		$hoja->getStyle('AY2:BO2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		$hoja->getStyle('AY3:BO3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');

		$hoja->setCellValue("BP2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('BP2:BT2');
		$hoja->getStyle('BP2:BT2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('BP3:BT3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("BV1", "FINANZAS");
		$hoja->mergeCells('BV1:CF1');
		$hoja->getStyle('BV1:CF1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('BV2:CA2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('BV3:CA3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->setCellValue("CB2", "CAPTURÓ FINANZAS");
		$hoja->mergeCells('CB2:CF2');
		$hoja->getStyle('CB2:CF2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('CB3:CF3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("CG1", "TESORERIA");
		$hoja->mergeCells('CG1:CL1');
		$hoja->getStyle('CG1:CG1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('CG2:CG2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('CG3:CG3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');

		$hoja->setCellValue("CH2", "CAPTURÓ TESORERIA");
		$hoja->mergeCells('CH2:CL2');
		$hoja->getStyle('CH2:CL2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('CH3:CL3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');


		$hoja->getStyle('A1:CL3')->getFont()->setBold(true);
		$hoja->getStyle('A1:CL3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:CL3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:CL3');
		$documento->getActiveSheet()->freezePane('A4');
	
		self::encabezadosNominasCompleto($hoja,true);
		self::encabezadosFinanzasCompleto($hoja,74,'BV','CG');
		self::encabezadosTesoreriaCompleto($hoja,85,'CG','CM');

		$fila = self::filasreporteTablaLiberacion($hoja,self::nominas(0,$fechaInicial,$fechaFinal));

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BS4:BS'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BT4:BT'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BU4:BU'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BV4:BV'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BW4:BW'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BX4:BX'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BY4:BY'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'BZ4:BZ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CA4:CA'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CE4:CE'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CF4:CF'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CG4:CG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CK4:CK'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CL4:CL'.$fila);
		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		for ($i = 'V'; $i !== 'BP'; $i++)
			$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), $i.'4:'.$i.$fila);
		return $documento;
	}

	public static function reporteTablaLiberacionNuevaVersion($fechaInicial,$fechaFinal){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales') // última vez modificado por
			->setTitle('Reporte tabla de liberación')
			->setSubject('Reporte tabla de liberación')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo tabla de liberación');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Nóminas");
	
		/*$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:BY1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('BY1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('BY2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('BY3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "SUELDOS Y SALARIOS");
		$hoja->mergeCells('Y2:AY2');
		$hoja->getStyle('Y2:AY2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');
		$hoja->getStyle('Y3:AY3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');

		$hoja->setCellValue("AZ2", "DESCUENTOS AL TRABAJADOR (EXCEDENTE)");
		$hoja->mergeCells('AZ2:BS2');
		$hoja->getStyle('AZ2:BS2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		$hoja->getStyle('AZ3:BS3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');

		$hoja->setCellValue("BT2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('BT2:BX2');
		$hoja->getStyle('BT2:BX2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('BT3:BX3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("BZ1", "FINANZAS");
		$hoja->mergeCells('BZ1:CJ1');
		$hoja->getStyle('BZ1:CJ1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('BZ2:CE2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('BZ3:CE3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->setCellValue("CF2", "CAPTURÓ FINANZAS");
		$hoja->mergeCells('CF2:CJ2');
		$hoja->getStyle('CF2:CJ2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('CF3:CJ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("CK1", "TESORERIA");
		$hoja->mergeCells('CK1:CP1');
		$hoja->getStyle('CK1:CK1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('CK2:CK2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('CK3:CK3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');

		$hoja->setCellValue("CL2", "CAPTURÓ TESORERIA");
		$hoja->mergeCells('CL2:CP2');
		$hoja->getStyle('CL2:CP2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('CL3:CP3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');


		$hoja->getStyle('A1:CP3')->getFont()->setBold(true);
		$hoja->getStyle('A1:CP3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:CP3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:CP3');
		$documento->getActiveSheet()->freezePane('A4');
	
		self::encabezadosNominasCompletoNuevaVersion($hoja,true);
		self::encabezadosFinanzasCompleto($hoja,78,'BZ','CK');
		self::encabezadosTesoreriaCompleto($hoja,89,'CK','CQ');*/

		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:CF1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('CF1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('CF2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('CF3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:AA2');
		$hoja->getStyle('B2:AA2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:AA3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("AB2", "SUELDOS Y SALARIOS");
		$hoja->mergeCells('AB2:BC2');
		$hoja->getStyle('AB2:BC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');
		$hoja->getStyle('AB3:BC3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');

		$hoja->setCellValue("BD2", "DESCUENTOS AL TRABAJADOR (EXCEDENTE)");
		$hoja->mergeCells('BD2:BZ2');
		$hoja->getStyle('BD2:BZ2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		$hoja->getStyle('BD3:BZ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');

		$hoja->setCellValue("CA2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('CA2:CE2');
		$hoja->getStyle('CA2:CE2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('CA3:CE3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("CG1", "FINANZAS");
		$hoja->mergeCells('CG1:CQ1');
		$hoja->getStyle('CG1:CQ1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('CG2:CL2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('CG3:CL3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->setCellValue("CM2", "CAPTURÓ FINANZAS");
		$hoja->mergeCells('CM2:CQ2');
		$hoja->getStyle('CM2:CQ2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('CM3:CQ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("CR1", "TESORERIA");
		$hoja->mergeCells('CR1:CW1');
		$hoja->getStyle('CR1:CR1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('CR2:CR2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('CR3:CR3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');

		$hoja->setCellValue("CS2", "CAPTURÓ TESORERIA");
		$hoja->mergeCells('CS2:CW2');
		$hoja->getStyle('CS2:CW2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('CS3:CW3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->getStyle('A1:CW3')->getFont()->setBold(true);
		$hoja->getStyle('A1:CW3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:CW3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:CW3');
		$documento->getActiveSheet()->freezePane('A4');


		self::encabezadosNominasCompletoNuevaVersion2($hoja,true);
		self::encabezadosFinanzasCompleto($hoja,85,'CG','CR');
		self::encabezadosTesoreriaCompleto($hoja,96,'CR','CX');

		$fila = self::filasreporteTablaLiberacionNuevaVersion2($hoja,self::nominas(0,$fechaInicial,$fechaFinal));


		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'V4:V'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'W4:W'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'X4:X'.$fila);

		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CD4:CD'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CE4:CE'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CF4:CF'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CG4:CG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CH4:CH'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CI4:CI'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CJ4:CJ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CK4:CK'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CL4:CL'.$fila);

		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CP4:CP'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CQ4:CQ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CR4:CR'.$fila);

		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CV4:CV'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'CW4:CW'.$fila);
		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'Q4:Q'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'U4:U'.$fila);
		for ($i = 'Y'; $i !== 'CA'; $i++)
			$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), $i.'4:'.$i.$fila);
		return $documento;
	}
	
	/*******eliminar */
	public static function formatoLlenadoNominas(){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales') // última vez modificado por
			->setTitle('Layout nóminas')
			->setSubject('Layout nóminas')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de Nóminas');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Nóminas");
	
		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:BI1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("A2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('A2:R2');
		$hoja->getStyle('A2:R2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('A3:R3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("S2", "SUELDOS Y SALARIOS");
		$hoja->mergeCells('S2:AR2');
		$hoja->getStyle('S2:AR2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');
		$hoja->getStyle('S3:AR3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');

		$hoja->setCellValue("AS2", "DESCUENTOS AL TRABAJADOR (EXCEDENTE)");
		$hoja->mergeCells('AS2:BI2');
		$hoja->getStyle('AS2:BI2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		$hoja->getStyle('AS3:BI3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');

		$hoja->getStyle('A4:BI4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('212F3D');

		$hoja->getStyle('A1:BI4')->getFont()->setBold(true);
		$hoja->getStyle('A1:BI4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:BI4')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->freezePane('A5');

		$letras = self::encabezadosNominasCompletoLayout($hoja);

	
		$documento->createSheet();//añadimos una nueva hoja
		$hoja2 = $documento->setActiveSheetIndex(1);
		$hoja2= $documento->getActiveSheet();
		$hoja2->setTitle("Options");
		$hoja2->setCellValue("MNN1000", "ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_NOMINAS");//anexamos la versión
	
		
		//Proteger segunda hoja 
		$hoja2->getProtection()->setPassword('3998202097258335');
		$hoja2->getProtection()->setSheet(true);
		$hoja2->getProtection()->setSort(true);
		$hoja2->getProtection()->setInsertRows(true);
		$hoja2->getProtection()->setInsertColumns(true);
		$hoja2->getProtection()->setFormatCells(true);

	
		$respuesta = Nominas::mostrarListas(Tablas::clientes());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("A".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'clientes', $documento->getSheetByName('Options'), 'A1:A'.$indice ) );


		$respuesta = Nominas::traducirTipoPago(true);
		$indice=1;
		$longitud = sizeof($respuesta);
		for($i=0;$i<$longitud;$i++){
			$documento->getSheetByName('Options')->SetCellValue("B".$indice,$respuesta[$i]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'movimientos', $documento->getSheetByName('Options'), 'B1:B'.$indice ) );


		$respuesta = Nominas::mostrarListas(Tablas::facturadoras());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("C".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'facturadoras', $documento->getSheetByName('Options'), 'C1:C'.$indice ) );



		$respuesta = Nominas::mostrarListas(Tablas::imss());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("D".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'imss', $documento->getSheetByName('Options'), 'D1:D'.$indice ) );



		$respuesta = Nominas::mostrarListas(Tablas::asimilados());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("E".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'asimilados', $documento->getSheetByName('Options'), 'E1:E'.$indice ) );


		$respuesta = Nominas::traducirTipoRegimen(true);
		$indice=1;
		$longitud = sizeof($respuesta);
		for($i=0;$i<$longitud;$i++){
			$documento->getSheetByName('Options')->SetCellValue("F".$indice,$respuesta[$i]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'regimen', $documento->getSheetByName('Options'), 'F1:F'.$indice ) );

		
		$respuesta = Nominas::traducirTipoPeriodo(true);
		$indice=1;
		$longitud = sizeof($respuesta);
		for($i=0;$i<$longitud;$i++){
			$documento->getSheetByName('Options')->SetCellValue("G".$indice,$respuesta[$i]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'periodo', $documento->getSheetByName('Options'), 'G1:G'.$indice ) );


		$respuesta = Nominas::traducirTipoEsquema(true);
		$indice=1;
		$longitud = sizeof($respuesta);
		for($i=0;$i<$longitud;$i++){
			$documento->getSheetByName('Options')->SetCellValue("H".$indice,$respuesta[$i]); 
			$indice++;
		}
		$indice = $indice - 3;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'esquema', $documento->getSheetByName('Options'), 'H1:H'.$indice ) );

		
		$documento->getSheetByName('Options')->SetCellValue("I1",'NO');
		$documento->getSheetByName('Options')->SetCellValue("I2",'SI');  
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'devengada', $documento->getSheetByName('Options'), 'I1:I2' ) );

		$documento->getSheetByName('Options')->SetCellValue("J1",'SINDICATO ASESORES / CROM');
		$documento->getSheetByName('Options')->SetCellValue("J2",'SINDICATO BUDAPEST');  
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'sindicatos', $documento->getSheetByName('Options'), 'J1:J2' ) );

		$documento->setActiveSheetIndex(0);//la hoja que aparecera activa
		$documento->getSheetByName('Options')->setSheetState(Worksheet::SHEETSTATE_HIDDEN); //oculto la hoja de opciones
	
		
		$documento->getActiveSheet()->getProtection()->setPassword('3998202097258335');
		$documento->getActiveSheet()->getProtection()->setSheet(true);
		$documento->getActiveSheet()->getProtection()->setSort(true);
		$documento->getActiveSheet()->getProtection()->setInsertRows(true);
		$documento->getActiveSheet()->getProtection()->setInsertColumns(true);
		$documento->getActiveSheet()->getProtection()->setFormatCells(true);
		

		$documento->getActiveSheet()->getStyle('A5:BI104')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
	
		
		$columna=1;
		$fila=5;
		for($i=0;$i<100;$i++){  

						for($j=0;$j<6;$j++){
							if($j===2)
								$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						}

						$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

						$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

						$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

						$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
							$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

						for($j=0;$j<3;$j++){
							$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						}

						for($j=0;$j<42;$j++){
							$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
							$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						}

						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');


					$objValidation = $documento->getActiveSheet()->getCell('A'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Error de captura'); 
					$objValidation->setError('El valor no existe.'); 
					//$objValidation->setPromptTitle('Selecciona un elemento de la lista'); 
					//$objValidation->setPrompt('Selecciona un elemento de la lista');
					$objValidation->setFormula1("=esquema"); 

					$objValidation = $documento->getActiveSheet()->getCell('B'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=sindicatos"); 

					$objValidation = $documento->getActiveSheet()->getCell('C'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=devengada"); 

					$objValidation = $documento->getActiveSheet()->getCell('D'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=clientes"); 

					$objValidation = $documento->getActiveSheet()->getCell('E'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=movimientos"); 

					$objValidation = $documento->getActiveSheet()->getCell('F'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=regimen"); 

					$objValidation = $documento->getActiveSheet()->getCell('H'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=facturadoras"); 

					$objValidation = $documento->getActiveSheet()->getCell('J'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=devengada"); 

					$objValidation = $documento->getActiveSheet()->getCell('K'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setPromptTitle('RETENCIÓN ISN'); 
					$objValidation->setPrompt('Si seleccionas sí deberas capturar obligatoriamente el IMPUSTO ESTATAL (columna Z) para realizar el cálculo, de igual manera se realizará automáticamente el cálculo de la RETENCIÓN DEL IVA (columna J)');
					$objValidation->setFormula1("=devengada"); 

					$objValidation = $documento->getActiveSheet()->getCell('L'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=imss"); 

					$objValidation = $documento->getActiveSheet()->getCell('N'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=asimilados"); 

					$objValidation = $documento->getActiveSheet()->getCell('P'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=periodo"); 
							
					$columna=1;
					$fila++;
		} 

		return $documento;
	}

	public static function formatoLlenadoNominasNuevaVersion(){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Layout nóminas')
			->setSubject('Layout nóminas')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de Nóminas');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Nóminas");
	
		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:BN1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("A2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('A2:R2');
		$hoja->getStyle('A2:R2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('A3:R3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("S2", "SUELDOS Y SALARIOS");
		$hoja->mergeCells('S2:AS2');
		$hoja->getStyle('S2:AS2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');
		$hoja->getStyle('S3:AS3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');

		$hoja->setCellValue("AT2", "DESCUENTOS AL TRABAJADOR (EXCEDENTE)");
		$hoja->mergeCells('AT2:BN2');
		$hoja->getStyle('AT2:BN2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		$hoja->getStyle('AT3:BN3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');

		$hoja->getStyle('A4:BN4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('212F3D');

		$hoja->getStyle('A1:BN4')->getFont()->setBold(true);
		$hoja->getStyle('A1:BN4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:BN4')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->freezePane('A5');

		$letras = self::encabezadosNominasCompletoLayoutNuevaVersion($hoja);

	
		$documento->createSheet();//añadimos una nueva hoja
		$hoja2 = $documento->setActiveSheetIndex(1);
		$hoja2= $documento->getActiveSheet();
		$hoja2->setTitle("Options");
		$hoja2->setCellValue("MNN1000", "poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___YETETEDEEDqfg45677%&&%%%RthnC50937DGD_7uKpoHu_NOMINAS");//anexamos la versión
	
		
		//Proteger segunda hoja 
		$hoja2->getProtection()->setPassword('3998202097258335');
		$hoja2->getProtection()->setSheet(true);
		$hoja2->getProtection()->setSort(true);
		$hoja2->getProtection()->setInsertRows(true);
		$hoja2->getProtection()->setInsertColumns(true);
		$hoja2->getProtection()->setFormatCells(true);


		///////////////

	
		$respuesta = Nominas::mostrarListas(Tablas::clientes());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("A".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'clientes', $documento->getSheetByName('Options'), 'A1:A'.$indice ) );


		$respuesta = Nominas::traducirTipoPago(true);
		$indice=1;
		$longitud = sizeof($respuesta);
		for($i=0;$i<$longitud;$i++){
			$documento->getSheetByName('Options')->SetCellValue("B".$indice,$respuesta[$i]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'movimientos', $documento->getSheetByName('Options'), 'B1:B'.$indice ) );


		$respuesta = Nominas::mostrarListas(Tablas::facturadoras());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("C".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'facturadoras', $documento->getSheetByName('Options'), 'C1:C'.$indice ) );



		$respuesta = Nominas::mostrarListas(Tablas::imss());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("D".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'imss', $documento->getSheetByName('Options'), 'D1:D'.$indice ) );



		$respuesta = Nominas::mostrarListas(Tablas::asimilados());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("E".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'asimilados', $documento->getSheetByName('Options'), 'E1:E'.$indice ) );


		$respuesta = Nominas::traducirTipoRegimen(true);
		$indice=1;
		$longitud = sizeof($respuesta);
		for($i=0;$i<$longitud;$i++){
			$documento->getSheetByName('Options')->SetCellValue("F".$indice,$respuesta[$i]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'regimen', $documento->getSheetByName('Options'), 'F1:F'.$indice ) );

		
		$respuesta = Nominas::traducirTipoPeriodo(true);
		$indice=1;
		$longitud = sizeof($respuesta);
		for($i=0;$i<$longitud;$i++){
			$documento->getSheetByName('Options')->SetCellValue("G".$indice,$respuesta[$i]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'periodo', $documento->getSheetByName('Options'), 'G1:G'.$indice ) );


		$respuesta = Nominas::traducirTipoEsquema(true);
		$indice=1;
		$longitud = sizeof($respuesta);
		for($i=0;$i<$longitud;$i++){
			$documento->getSheetByName('Options')->SetCellValue("H".$indice,$respuesta[$i]); 
			$indice++;
		}
		$indice = $indice - 3;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'esquema', $documento->getSheetByName('Options'), 'H1:H'.$indice ) );

		
		$documento->getSheetByName('Options')->SetCellValue("I1",'NO');
		$documento->getSheetByName('Options')->SetCellValue("I2",'SI');  
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'devengada', $documento->getSheetByName('Options'), 'I1:I2' ) );

		$documento->getSheetByName('Options')->SetCellValue("J1",'SINDICATO ASESORES / CROM');
		$documento->getSheetByName('Options')->SetCellValue("J2",'SINDICATO BUDAPEST');  
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'sindicatos', $documento->getSheetByName('Options'), 'J1:J2' ) );

		$documento->setActiveSheetIndex(0);//la hoja que aparecera activa
		$documento->getSheetByName('Options')->setSheetState(Worksheet::SHEETSTATE_HIDDEN); //oculto la hoja de opciones
	
		
		$documento->getActiveSheet()->getProtection()->setPassword('3998202097258335');
		$documento->getActiveSheet()->getProtection()->setSheet(true);
		$documento->getActiveSheet()->getProtection()->setSort(true);
		$documento->getActiveSheet()->getProtection()->setInsertRows(true);
		$documento->getActiveSheet()->getProtection()->setInsertColumns(true);
		$documento->getActiveSheet()->getProtection()->setFormatCells(true);
		

		$documento->getActiveSheet()->getStyle('A5:BN104')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
	

		$columna=1;
		$fila=5;
		for($i=0;$i<100;$i++){  

						for($j=0;$j<6;$j++){
							if($j===2)
								$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						}

						$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

						$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

						$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

						$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
							$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

						for($j=0;$j<3;$j++){
							$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						}

						for($j=0;$j<47;$j++){
							$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
							$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
						}

						$hoja->setCellValueByColumnAndRow($columna++,$fila,'');


					$objValidation = $documento->getActiveSheet()->getCell('A'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Error de captura'); 
					$objValidation->setError('El valor no existe.'); 
					//$objValidation->setPromptTitle('Selecciona un elemento de la lista'); 
					//$objValidation->setPrompt('Selecciona un elemento de la lista');
					$objValidation->setFormula1("=esquema"); 

					$objValidation = $documento->getActiveSheet()->getCell('B'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=sindicatos"); 

					$objValidation = $documento->getActiveSheet()->getCell('C'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=devengada"); 

					$objValidation = $documento->getActiveSheet()->getCell('D'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=clientes"); 

					$objValidation = $documento->getActiveSheet()->getCell('E'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=movimientos"); 

					$objValidation = $documento->getActiveSheet()->getCell('F'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=regimen"); 

					$objValidation = $documento->getActiveSheet()->getCell('H'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=facturadoras"); 

					$objValidation = $documento->getActiveSheet()->getCell('J'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=devengada"); 

					$objValidation = $documento->getActiveSheet()->getCell('K'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setPromptTitle('RETENCIÓN ISN'); 
					$objValidation->setPrompt('Si seleccionas sí deberas capturar obligatoriamente el IMPUSTO ESTATAL (columna Z) para realizar el cálculo, de igual manera se realizará automáticamente el cálculo de la RETENCIÓN DEL IVA (columna J)');
					$objValidation->setFormula1("=devengada"); 

					$objValidation = $documento->getActiveSheet()->getCell('L'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=imss"); 

					$objValidation = $documento->getActiveSheet()->getCell('N'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=asimilados"); 

					$objValidation = $documento->getActiveSheet()->getCell('P'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=periodo"); 
							
					$columna=1;
					$fila++;
		} 

		return $documento;
	}

	public static function formatoLlenadoFinanzas(){
		
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Layout finanzas')
			->setSubject('Layout finanzas')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de finanzas');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Finanzas");

		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:AC1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('Y2:AC2');
		$hoja->getStyle('Y2:AC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('Y3:AC3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AD1", "FINANZAS");
		$hoja->mergeCells('AD1:AK1');
		$hoja->getStyle('AD1:AK1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD2:AK2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD3:AK3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->getStyle('A1:AK3')->getFont()->setBold(true);
		$hoja->getStyle('A1:AK3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:AK3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		self::encabezadosNominasResumen($hoja);
		self::encabezadosFinanzasCompletoLayout($hoja,30,'AD','AL');

		$fila=2;
		$columna = 30;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OPCIÓN MULTIPLE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DD/MM/AAAA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HH:MM");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OPCIÓN MULTIPLE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DD/MM/AAAA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OPCIÓN MULTIPLE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OPCIÓN MULTIPLE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TEXTO");
		

		$documento->getActiveSheet()->setAutoFilter('A3:AK3');
		$documento->getActiveSheet()->freezePane('A4');

		$documento->createSheet();//añadimos una nueva hoja
		$hoja2 = $documento->setActiveSheetIndex(1);
		$hoja2 = $documento->getActiveSheet();
		$hoja2->setTitle("Options");
		$hoja2->setCellValue("MNN1000", "ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_FINANZAS");//anexamos la versión

		//Proteger segunda hoja 
		$hoja2->getProtection()->setPassword('3998202097258335');
		$hoja2->getProtection()->setSheet(true);
		$hoja2->getProtection()->setSort(true);
		$hoja2->getProtection()->setInsertRows(true);
		$hoja2->getProtection()->setDeleteRows(true);
		$hoja2->getProtection()->setInsertColumns(true);
		$hoja2->getProtection()->setFormatCells(true);

		$documento->getSheetByName('Options')->SetCellValue("A1",'NO');
		$documento->getSheetByName('Options')->SetCellValue("A2",'SI');  
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'simple', $documento->getSheetByName('Options'), 'A1:A2' ) );

		$documento->getSheetByName('Options')->SetCellValue("B1", "PENDIENTE"); 
		$documento->getSheetByName('Options')->SetCellValue("B2", "LIBERADA"); 
		$documento->getSheetByName('Options')->SetCellValue("B3", "CANCELADA"); 
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'estatus', $documento->getSheetByName('Options'), 'B1:B3' ) ); 
	
		$documento->setActiveSheetIndex(0);//la hoja que aparecera activa
		$documento->getSheetByName('Options')->setSheetState(Worksheet::SHEETSTATE_HIDDEN); //oculto la hoja de opciones

		$documento->getActiveSheet()->getProtection()->setPassword('3998202097258335');
		$documento->getActiveSheet()->getProtection()->setSheet(true);
		$documento->getActiveSheet()->getProtection()->setSort(true);
		$documento->getActiveSheet()->getProtection()->setInsertRows(true);
		$documento->getActiveSheet()->getProtection()->setDeleteRows(true);
		$documento->getActiveSheet()->getProtection()->setInsertColumns(true);
		$documento->getActiveSheet()->getProtection()->setFormatCells(true);

		$respuesta =Reportes::nominas(2);
		

		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$capturaNominista = explode ( " ", $item['captura_nominista']);
			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' : Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$date = PhpOffice\PhpSpreadsheet\Shared\Date::FormattedPHPToExcel(substr($capturaNominista[0],0,4), substr($capturaNominista[0],5,2), substr($capturaNominista[0],8,2));
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $date/*substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4)*/);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);

			$date = PhpOffice\PhpSpreadsheet\Shared\Date::FormattedPHPToExcel(substr($item['fecha_envio'],0,4), substr($item['fecha_envio'],5,2), substr($item['fecha_envio'],8,2));
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fecha_envio'] !== NULL ? $date /*substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4)*/ : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['observaciones'] !== NULL ? Nominas::traducirObservaciones($item['observaciones']) : '' );
			
			$date = PhpOffice\PhpSpreadsheet\Shared\Date::FormattedPHPToExcel(substr($item['fecha_liberacion'],0,4), substr($item['fecha_liberacion'],5,2), substr($item['fecha_liberacion'],8,2));
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fecha_liberacion'] !== NULL ? $date/*substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4)*/ : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['comentarios_finanzas'] !== NULL ? str_replace('<br />','; ',$item['comentarios_finanzas']) : '');
	
			$objValidation = $documento->getActiveSheet()->getCell('AD'.$fila)->getDataValidation(); 
			$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
			$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP); 
			$objValidation->setAllowBlank(false); 
			$objValidation->setShowInputMessage(true); 
			$objValidation->setShowErrorMessage(true); 
			$objValidation->setShowDropDown(true); 
			$objValidation->setErrorTitle('Input error'); 
			$objValidation->setError('El valor no existe.'); 
			$objValidation->setPromptTitle('Pick from list'); 
			$objValidation->setFormula1("=simple"); //note this! 

			$objValidation = $documento->getActiveSheet()->getCell('AG'.$fila)->getDataValidation(); 
			$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
			$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP); 
			$objValidation->setAllowBlank(false); 
			$objValidation->setShowInputMessage(true); 
			$objValidation->setShowErrorMessage(true); 
			$objValidation->setShowDropDown(true); 
			$objValidation->setErrorTitle('Input error'); 
			$objValidation->setError('El valor no existe.'); 
			$objValidation->setPromptTitle('Pick from list'); 
			$objValidation->setFormula1("=estatus"); //note this! 
	
			$objValidation = $documento->getActiveSheet()->getCell('AI'.$fila)->getDataValidation(); 
			$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
			$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP); 
			$objValidation->setAllowBlank(false); 
			$objValidation->setShowInputMessage(true); 
			$objValidation->setShowErrorMessage(true); 
			$objValidation->setShowDropDown(true); 
			$objValidation->setErrorTitle('Input error'); 
			$objValidation->setError('El valor no existe.'); 
			$objValidation->setPromptTitle('Pick from list'); 
			$objValidation->setFormula1("=simple"); //note this! 

			$objValidation = $documento->getActiveSheet()->getCell('AJ'.$fila)->getDataValidation(); 
			$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
			$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP); 
			$objValidation->setAllowBlank(false); 
			$objValidation->setShowInputMessage(true); 
			$objValidation->setShowErrorMessage(true); 
			$objValidation->setShowDropDown(true); 
			$objValidation->setErrorTitle('Input error'); 
			$objValidation->setError('El valor no existe.'); 
			$objValidation->setPromptTitle('Pick from list'); 
			$objValidation->setFormula1("=simple");			

			$columna=1;
			$fila++;
		}

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AC4:AC'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AD4:AD'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AF4:AF'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AG4:AG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AI4:AI'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AJ4:AJ'.$fila);

		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'V4:V'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'W4:W'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'X4:X'.$fila);

		$hoja->getStyle('AB4')->getNumberFormat()->setFormatCode('dd/mm/yyyy');
		$hoja->getStyle('AB4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AB4'), 'AB5:AB'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AB4'), 'AE4:AE'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AB4'), 'AH4:AH'.$fila);

		$documento->getActiveSheet()->getStyle('AD4:AK'.(count($respuesta) + 3 ) )->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
		return $documento;
	}

	public static function formatoLlenadoTesoreria(){
		
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Layout tesoreria')
			->setSubject('Layout tesoreria')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de tesoreria');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Tesoreria");

		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:AC1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('Y2:AC2');
		$hoja->getStyle('Y2:AC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('Y3:AC3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AD1", "FINANZAS");
		$hoja->mergeCells('AD1:AO1');
		$hoja->getStyle('AD1:AJ1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD2:AJ2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD3:AJ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->setCellValue("AK2", "CAPTURÓ FINANZAS");
		$hoja->mergeCells('AK2:AO2');
		$hoja->getStyle('AK2:AO2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('AK3:AO3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AP1", "TESORERIA");
		$hoja->mergeCells('AP1:AQ1');
		$hoja->getStyle('AP1:AQ1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('AP2:AQ2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');
		$hoja->getStyle('AP3:AQ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0C345F');


		$hoja->getStyle('A1:AQ3')->getFont()->setBold(true);
		$hoja->getStyle('A1:AQ3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:AQ3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		self::encabezadosNominasResumen($hoja);
		self::encabezadosFinanzasCompletoLayout($hoja,30,'AD','AP',true);
		self::encabezadosTesoreriaCompleto($hoja,42,'AP','AR',false);

		$documento->getActiveSheet()->setAutoFilter('A3:AQ3');
		$documento->getActiveSheet()->freezePane('A4');
	
		$documento->createSheet();//añadimos una nueva hoja
		$hoja2 = $documento->setActiveSheetIndex(1);
		$hoja2 = $documento->getActiveSheet();
		$hoja2->setTitle("Options");
		$hoja2->setCellValue("MNN1000", "ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_TESORERIA");//anexamos la versión

		//Proteger segunda hoja 
		$hoja2->getProtection()->setPassword('3998202097258335');
		$hoja2->getProtection()->setSheet(true);
		$hoja2->getProtection()->setSort(true);
		$hoja2->getProtection()->setInsertRows(true);
		$hoja2->getProtection()->setDeleteRows(true);
		$hoja2->getProtection()->setInsertColumns(true);
		$hoja2->getProtection()->setFormatCells(true);
		
		$documento->getSheetByName('Options')->SetCellValue("A1", "");
		$documento->getSheetByName('Options')->SetCellValue("A2", "PENDIENTE"); 
		$documento->getSheetByName('Options')->SetCellValue("A3", "PAGADA"); 
		$documento->getSheetByName('Options')->SetCellValue("A4", "PAGADA CON DEVOLUCIÓN"); 
		$documento->getSheetByName('Options')->SetCellValue("A5", "PAGADA CON OBSERVACIÓN"); 
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'estatus', $documento->getSheetByName('Options'), 'A1:A5' ) );
	
		
		$documento->setActiveSheetIndex(0);//la hoja que aparecera activa
		$documento->getSheetByName('Options')->setSheetState(Worksheet::SHEETSTATE_HIDDEN); //oculto la hoja de opciones
		$documento->getActiveSheet()->freezePane('A4');

		$documento->getActiveSheet()->getProtection()->setPassword('3998202097258335');
		$documento->getActiveSheet()->getProtection()->setSheet(true);
		$documento->getActiveSheet()->getProtection()->setSort(true);
		$documento->getActiveSheet()->getProtection()->setInsertRows(true);
		$documento->getActiveSheet()->getProtection()->setDeleteRows(true);
		$documento->getActiveSheet()->getProtection()->setInsertColumns(true);
		$documento->getActiveSheet()->getProtection()->setFormatCells(true);

		$respuesta =Reportes::nominas(3);
		
		$hoja->getStyle('AO2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(42,2,'OPCIÓN MULTIPLE');
		$hoja->getStyle('AP2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(43,2,'TEXTO');

		$columna=1;
		$fila=4;
		
		foreach ($respuesta as $row => $item){  
			$finanzas=$capturaFinanzas='';
			$capturaNominista = explode ( " ", $item['captura_nominista']);
			if($item["id_finanzas"] !==NULL){
				$finanzas = NominasModel::datos3($item["id_finanzas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFinanzas = explode ( " ", $item['captura_finanzas']);
			}
			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' : Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fecha_envio'] !== NULL ? substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['observaciones'] !== NULL ? Nominas::traducirObservaciones($item['observaciones']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fecha_liberacion'] !== NULL ? substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['comentarios_finanzas'] !== NULL ? str_replace('<br />','; ',$item['comentarios_finanzas']) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaFinanzas[0],8,2).'/'.substr($capturaFinanzas[0],5,2).'/'.substr($capturaFinanzas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['id_tesoreria'] == NULL ? '' : Nominas::traducirEstatusNominas($item['tesoreria_estatus']) );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_tesoreria']));

			$objValidation = $documento->getActiveSheet()->getCell('AP'.$fila)->getDataValidation(); 
			$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
			$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP); 
			$objValidation->setAllowBlank(false); 
			$objValidation->setShowInputMessage(true); 
			$objValidation->setShowErrorMessage(true); 
			$objValidation->setShowDropDown(true); 
			$objValidation->setErrorTitle('Input error'); 
			$objValidation->setError('El valor no existe.'); 
			$objValidation->setPromptTitle('Pick from list'); 
			$objValidation->setFormula1("=estatus"); //note this! 

			$columna=1;
			$fila++;
		} 

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AB4:AB'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AC4:AC'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AD4:AD'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AE4:AE'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AF4:AF'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AG4:AG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AI4:AI'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AJ4:AJ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AN4:AN'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AO4:AO'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AP4:AP'.$fila);

		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'V4:V'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'W4:W'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'X4:X'.$fila);


		$documento->getActiveSheet()->getStyle('AP4:AQ'.(count($respuesta) + 3 ) )->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

		return $documento;
	}

	public static function formatoLlenadoFactura(){
		
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Layout facturación')
			->setSubject('Layout facturación')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de facturación');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Facturación");

		$hoja->setCellValue("A1", "NÓMINAS");
		$hoja->mergeCells('A1:AC1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');

		$hoja->setCellValue("B2", "TABLA DE LIBERACIÓN");
		$hoja->mergeCells('B2:X2');
		$hoja->getStyle('B2:X2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('B3:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("Y2", "CAPTURÓ NÓMINAS");
		$hoja->mergeCells('Y2:AC2');
		$hoja->getStyle('Y2:AC2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('Y3:AC3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AD1", "FINANZAS");
		$hoja->mergeCells('AD1:AO1');
		$hoja->getStyle('AD1:AJ1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD2:AJ2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');
		$hoja->getStyle('AD3:AJ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');

		$hoja->setCellValue("AK2", "CAPTURÓ FINANZAS");
		$hoja->mergeCells('AK2:AO2');
		$hoja->getStyle('AK2:AO2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');
		$hoja->getStyle('AK3:AO3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CD6155');

		$hoja->setCellValue("AP1", "FACTURACIÓN");
		$hoja->mergeCells('AP1:AZ1');
		$hoja->getStyle('AP1:AZ1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('811363');
		$hoja->getStyle('AP2:AZ2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('811363');
		$hoja->getStyle('AT3:AZ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('811363');
		$hoja->getStyle('AP3:AS3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('63605F');


		$hoja->getStyle('A1:AZ3')->getFont()->setBold(true);
		$hoja->getStyle('A1:AZ3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:AZ3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		self::encabezadosNominasResumen($hoja);
		self::encabezadosFinanzasCompletoLayout($hoja,30,'AD','AP',true);
		self::encabezadosFacturacionCompleto($hoja,42,'AP','BA',false);

		$documento->getActiveSheet()->setAutoFilter('A3:AZ3');
		$documento->getActiveSheet()->freezePane('A4');
	
		$documento->createSheet();//añadimos una nueva hoja
		$hoja2 = $documento->setActiveSheetIndex(1);
		$hoja2 = $documento->getActiveSheet();
		$hoja2->setTitle("Options");
		$hoja2->setCellValue("MNN1000", "ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_FACTURACION");//anexamos la versión

		
		//Proteger segunda hoja 
		$hoja2->getProtection()->setPassword('3998202097258335');
		$hoja2->getProtection()->setSheet(true);
		$hoja2->getProtection()->setSort(true);
		$hoja2->getProtection()->setInsertRows(true);
		$hoja2->getProtection()->setDeleteRows(true);
		$hoja2->getProtection()->setInsertColumns(true);
		$hoja2->getProtection()->setFormatCells(true);
		
		$documento->getSheetByName('Options')->SetCellValue("A1", "");
		$documento->getSheetByName('Options')->SetCellValue("A2", "PENDIENTE"); 
		$documento->getSheetByName('Options')->SetCellValue("A3", "PAGADA"); 
		$documento->getSheetByName('Options')->SetCellValue("A4", "NOTA DE CRÉDITO"); 
		$documento->getSheetByName('Options')->SetCellValue("A5", "CANCELADA"); 
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'estatus', $documento->getSheetByName('Options'), 'A1:A5' ) );
	
		$documento->setActiveSheetIndex(0);//la hoja que aparecera activa
		$documento->getSheetByName('Options')->setSheetState(Worksheet::SHEETSTATE_HIDDEN); //oculto la hoja de opciones
		$documento->getActiveSheet()->freezePane('A4');

		$documento->getActiveSheet()->getProtection()->setPassword('3998202097258335');
		$documento->getActiveSheet()->getProtection()->setSheet(true);
		$documento->getActiveSheet()->getProtection()->setSort(true);
		$documento->getActiveSheet()->getProtection()->setInsertRows(true);
		$documento->getActiveSheet()->getProtection()->setDeleteRows(true);
		$documento->getActiveSheet()->getProtection()->setInsertColumns(true);
		$documento->getActiveSheet()->getProtection()->setFormatCells(true);

		$respuesta =Reportes::nominas(8);
		
		$hoja->getStyle('AT2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(46,2,'000,000,000.00');
		$hoja->getStyle('AU2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(47,2,'OPCIÓN MULTIPLE');
		$hoja->getStyle('AV2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(48,2,'ALFANÚMERICO');
		$hoja->getStyle('AW2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(49,2,'ALFANÚMERICO');
		$hoja->getStyle('AX2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(50,2,'DD/MM/AAAA');
		$hoja->getStyle('AY2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(51,2,'DD/MM/AAAA');
		$hoja->getStyle('AZ2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow(52,2,'TEXTO');


		
		$columna=1;
		$fila=4;
		
		foreach ($respuesta as $row => $item){  
			$finanzas=$capturaFinanzas='';
			$capturaNominista = explode ( " ", $item['captura_nominista']);
			if($item["id_finanzas"] !==NULL){
				$finanzas = NominasModel::datos3($item["id_finanzas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaFinanzas = explode ( " ", $item['captura_finanzas']);
			}
			if($item['empresa_asimilados'] !== NULL)
				$item['empresa_asimilados'] = NominasModel::obtenerDatoNominas($item['empresa_asimilados'],Tablas::asimilados());
			if($item['empresa_imss'] !== NULL)
				$item['empresa_imss'] = NominasModel::obtenerDatoNominas($item['empresa_imss'],Tablas::imss());
			if($item['tipo_sindicato']!= NULL)
				$sindicato = $item['tipo_sindicato'] == 0 ? 'ASESORES / CROM' : 'BUDAPEST';
			else
				$sindicato = "";
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoEsquema($item['esquema']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sindicato);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['devengada'] == 0 ? 'NO' : 'SÍ');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_pago'] == NULL ? '' : Nominas::traducirTipoPago($item['tipo_pago']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Nominas::traducirTipoRegimen($item['regimen']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comision_porcentaje']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total_asimilados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['tipo_periodo'] == NULL ? '' : Nominas::traducirTipoPeriodo($item['tipo_periodo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_periodo']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['socios']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_sys']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_asesores']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['descuentos_terceros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nominista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaNominista[0],8,2).'/'.substr($capturaNominista[0],5,2).'/'.substr($capturaNominista[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominista[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['financiada'] !== NULL ? Nominas::traducirSiOnoInverso($item['financiada']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fecha_envio'] !== NULL ? substr($item['fecha_envio'],8,2).'/'.substr($item['fecha_envio'],5,2).'/'.substr($item['fecha_envio'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['hora_envio'] !== NULL ? substr($item['hora_envio'],0,5) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['observaciones'] !== NULL ? Nominas::traducirObservaciones($item['observaciones']) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fecha_liberacion'] !== NULL ? substr($item['fecha_liberacion'],8,2).'/'.substr($item['fecha_liberacion'],5,2).'/'.substr($item['fecha_liberacion'],0,4) : '' );
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fondeo_imss'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_imss']) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['fondeo_asimilados'] !== NULL ? Nominas::traducirSiOnoInverso($item['fondeo_asimilados']) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila, $item['comentarios_finanzas'] !== NULL ? str_replace('<br />','; ',$item['comentarios_finanzas']) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$finanzas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaFinanzas[0],8,2).'/'.substr($capturaFinanzas[0],5,2).'/'.substr($capturaFinanzas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaFinanzas[1]);


			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['iva']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['retencion_isn']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,'PENDIENTE');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_nota_credito']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_factura'] !== NULL ? PhpOffice\PhpSpreadsheet\Shared\Date::FormattedPHPToExcel(substr($item['fecha_factura'],0,4), substr($item['fecha_factura'],5,2), substr($item['fecha_factura'],8,2)) /*substr($item['fecha_factura'],8,2).'/'.substr($item['fecha_factura'],5,2).'/'.substr($item['fecha_factura'],0,4)*/ : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_pago_factura'] !== NULL ? PhpOffice\PhpSpreadsheet\Shared\Date::FormattedPHPToExcel(substr($item['fecha_pago_factura'],0,4), substr($item['fecha_pago_factura'],5,2), substr($item['fecha_pago_factura'],8,2)) /*substr($item['fecha_pago_factura'],8,2).'/'.substr($item['fecha_pago_factura'],5,2).'/'.substr($item['fecha_pago_factura'],0,4)*/ : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_facturacion']));

			$objValidation = $documento->getActiveSheet()->getCell('AU'.$fila)->getDataValidation(); 
			$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
			$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP); 
			$objValidation->setAllowBlank(false); 
			$objValidation->setShowInputMessage(true); 
			$objValidation->setShowErrorMessage(true); 
			$objValidation->setShowDropDown(true); 
			$objValidation->setErrorTitle('Input error'); 
			$objValidation->setError('El valor no existe.'); 
			$objValidation->setPromptTitle('Pick from list'); 
			$objValidation->setFormula1("=estatus");

			$columna=1;
			$fila++;
		} 

		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'B4:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'C4:C'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'F4:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'S4:S'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'T4:T'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'U4:U'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AB4:AB'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AC4:AC'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AD4:AD'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AE4:AE'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AF4:AF'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AG4:AG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AI4:AI'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AJ4:AJ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AN4:AN'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AO4:AO'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AU4:AU'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AV4:AV'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AW4:AW'.$fila);
		

		$hoja->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'H5:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'K4:K'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'L4:L'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'M4:M'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'N4:N'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'P4:P'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'R4:R'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'V4:V'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'W4:W'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'X4:X'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AP4:AP'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AQ4:AQ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AR4:AR'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AS4:AS'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('H4'), 'AT4:AT'.$fila);

		$hoja->getStyle('AV4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
		$hoja->getStyle('AV4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		//$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AX4'), 'AX5:AX'.$fila);
		//$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AX4'), 'AY4:AY'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AV4'), 'AV5:AV'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AV4'), 'AW4:AW'.$fila);


		$hoja->getStyle('AX4')->getNumberFormat()->setFormatCode('dd/mm/yyyy');
		$hoja->getStyle('AX4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AX4'), 'AX5:AX'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('AX4'), 'AY4:AY'.$fila);
	
		$documento->getActiveSheet()->getStyle('AT4:AZ'.(count($respuesta) + 3 ) )->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

		return $documento;
	}

	public static function reporteTickets($fechaInico,$fechaFin,$area){
		
		$documento = new Spreadsheet();
		$documento
		->getProperties()
		->setCreator("Aquí va el creador, como cadena")
		->setLastModifiedBy('Parzibyte') // última vez modificado por
		->setTitle('Mi primer documento creado con PhpSpreadSheet')
		->setSubject('El asunto')
		->setDescription('Este documento fue generado para parzibyte.me')
		->setKeywords('etiquetas o palabras clave separadas por espacios')
		->setCategory('La categoría');

		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("El título de la hoja");

		$columna=1;
		$fila=1;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TICKET");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"USUARIO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AREA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CATEGORIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBCATEGORIA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ASUNTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"DESCRIPCION");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SITUACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ATENDIO TICKET");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"PROBLEMA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAUSA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SOLUCIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE REGISTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA ATENDIDO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE FINALIZACIÓN");

		for ($i = 'A'; $i !== 'Q'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);

		$hoja->getStyle('A1:P1')->getFont()->setBold(true);
		$hoja->getStyle('A1:P1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:P1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		$hoja->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$respuesta=ReportesModel::reporteTickets($fechaInico,$fechaFin,$area,Tablas::tickets(),Tablas::usuarios(),Tablas::sucursales());
		$columna=1;
		$fila=2;

		foreach ($respuesta as $row => $item){  

			$sinSaltos= str_replace('<br />',' . ',$item['descripcion']);
			
			if($item['situacion'] == 0)
				$item['situacion']='SIN ATENDER';
			else if($item['situacion'] == 1)
				$item['situacion']='ATENDIENDOSE';
			else
				$item['situacion']='FINALIZADO';

			if($item['area'] == 1)
				$item['area']='SISTEMAS';
			else if($item['area'] == 2)
				$item['area']='GIRO';
			else
				$item['area']='DESARROLLO';
			
			if($item['segmento'] != NULL)
				$item['segmento'] = TicketsModel::traducirReferencia($item['segmento'],Tablas::tickets_subcategorias());
			
			if($item['id_atiende_ticket'] != NULL)
				$item['id_atiende_ticket']= Datos::getNombre($item['id_atiende_ticket'],Tablas::usuarios());
				
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id_ticket']);
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['area']);
            $hoja->setCellValueByColumnAndRow($columna++,$fila,TicketsModel::traducirReferencia($item['subcategoria'],Tablas::tickets_categorias()));
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$item['segmento']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['asunto']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$sinSaltos);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['situacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id_atiende_ticket']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['problema']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['causa']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['solucion']);
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_registro']);
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_atendido']);
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_finalizado']);
            $columna=1;
            $fila++;
		}  

		return $documento;
	}

	public static function encabezadosCompletoCostos($hoja){
		$fila=3;
		$columna = 1;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"	No.	");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUBTOTAL PATRONAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TOTAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE COMERCIAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL PROMOTOR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL SUBCOMISIONISTA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CÓDIGO DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPLEADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RCV");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO RCV");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE RCV");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO IMSS OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE IMSS OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RCV OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO RCV OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE RCV OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AMORTIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO AMORTIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AJUSTE AMORTIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAPTURÓ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA ÚLTIMA ACTUALIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA ÚLTIMA ACTUALIZACIÓN");

		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMMA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"VIDA E INVALIDEZ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMME");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAPTURÓ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA ÚLTIMA ACTUALIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA ÚLTIMA ACTUALIZACIÓN");

		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMPUESTO ESTATAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAPTURÓ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA ÚLTIMA ACTUALIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA ÚLTIMA ACTUALIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FEHA DE REGISTRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA DE REGISTRO");
		
		for ($i = 'A'; $i !== 'AZ'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
	}

	public static function filasreporteCostos($hoja,$respuesta){
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  
			$gm=$capturaGm=$nominas=$capturaNominas=$promotor=$subcomisionista=$empresa='';

			$capturaImss = explode ( " ", $item['registro_imss']);
			$registro = explode ( " ", $item['registro']);

			if($item["id_gm"] !==NULL){
				$gm = NominasModel::datos3($item["id_gm"],Tablas::usuarios(),Tablas::sucursales());
				$capturaGm = explode ( " ", $item['registro_gm']);
			}
			if($item["id_nominas"] !==NULL){
				$nominas = NominasModel::datos3($item["id_nominas"],Tablas::usuarios(),Tablas::sucursales());
				$capturaNominas = explode ( " ", $item['registro_nominas']);
			}

			if($item["promotor"] !==NULL)
				$promotor = CostosModel::getNombrePromotor($item["promotor"],Tablas::costos_promotor());
			if($item["subcomisionista"] !==NULL)
				$subcomisionista = CostosModel::getNombrePromotor($item["subcomisionista"],Tablas::costos_subcomisionista());
			if($item["empresa"] !==NULL)
				$empresa = EmpresasModel::getNombreEmpresa($item["empresa"],Tablas::empresas());
			

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subtotal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['total']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,strtoupper(Costos::traducirMes($item['mes'])));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nombre_cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nombre_comercial']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$promotor);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$subcomisionista);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['codigo_cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empleados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$empresa);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['rcv']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_rcv']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_rcv']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_imss_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_imss_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['rcv_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_rcv_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_rcv_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['amortizacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_amortizacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['ajuste_amortizacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_imss']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['capturista_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($capturaImss[0],8,2).'/'.substr($capturaImss[0],5,2).'/'.substr($capturaImss[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaImss[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['gmma']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['vida_invalidez']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['gmme']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_gm']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$gm['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$gm['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaGm[0] == NULL ? '' :   substr($capturaGm[0],8,2).'/'.substr($capturaGm[0],5,2).'/'.substr($capturaGm[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaGm[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['impuesto_estatal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios_nominas']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$nominas['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$nominas['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominas[0] == NULL ? '' :   substr($capturaNominas[0],8,2).'/'.substr($capturaNominas[0],5,2).'/'.substr($capturaNominas[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$capturaNominas[1]);

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$registro[0] == NULL ? '' :   substr($registro[0],8,2).'/'.substr($registro[0],5,2).'/'.substr($registro[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$registro[1]);

			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	public static function reporteModuloCostos($fechaInicial,$fechaFinal){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Reporte módulo costos')
			->setSubject('Reporte módulo costos')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de Costos');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Costos");
	
		$hoja->setCellValue("A1", "MÓDULO COSTOS");
		$hoja->mergeCells('A1:AY1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2:C2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A3:C3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		
		$hoja->setCellValue("D2", "IMSS");
		$hoja->mergeCells('D2:AH2');
		$hoja->getStyle('D2:AH2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('D3:AH3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');

		$hoja->setCellValue("AI2", "GASTOS MÉDICOS");
		$hoja->mergeCells('AI2:AQ2');
		$hoja->getStyle('AI2:AQ2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');
		$hoja->getStyle('AI3:AQ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8570C');

		$hoja->setCellValue("AR2", "NÓMINAS");
		$hoja->mergeCells('AR2:AW2');
		$hoja->getStyle('AR2:AW2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		$hoja->getStyle('AR3:AW3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
		
		$hoja->getStyle('AX2:AY2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('AX3:AY3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		

		$hoja->getStyle('A1:AY3')->getFont()->setBold(true);
		$hoja->getStyle('A1:AY3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:AY3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A3:AY3');
		$documento->getActiveSheet()->freezePane('A4');
	
		self::encabezadosCompletoCostos($hoja);
		$fila = self::filasreporteCostos($hoja,ReportesModel::costos($fechaInicial,$fechaFinal,Tablas::costos(),Tablas::usuarios(),Tablas::sucursales(),Tablas::costos_clientes()));

		
		$hoja->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'A5:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'D4:D'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'I4:I'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'J4:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AG4:AG'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AH4:AH'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AP4:AP'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AQ4:AQ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AV4:AV'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AW4:AW'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AX4:AX'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A4'), 'AY4:AY'.$fila);
		$hoja->getStyle('B4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('B4'), 'B5:B'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('B4'), 'C4:C'.$fila);
		for ($i = 'L'; $i !== 'AD'; $i++)
			$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('B4'), $i.'4:'.$i.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('B4'), 'AI4:AI'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('B4'), 'AJ4:AJ'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('B4'), 'AK4:AK'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('B4'), 'AL4:AL'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('B4'), 'AR4:AR'.$fila);
		return $documento;
	}




	public static function reporteModuloConciliacion($fechaInicial,$fechaFinal,$tipo){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Reporte módulo conciliación')
			->setSubject('Reporte módulo conciliación')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de Conciliación');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Conciliación");
	
		$hoja->setCellValue("A1", "MÓDULO DE CONCILIACIÓN");
		$hoja->mergeCells('A1:Z1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A1:Z1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A1:Z2')->getFont()->setBold(true);
		$hoja->getStyle('A1:Z2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A2:Z2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('A1:Z2')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->setAutoFilter('A2:Z2');
		$documento->getActiveSheet()->freezePane('A3');
	
		self::encabezadosCompletoConciliacion($hoja);
		$fila = self::filasreporteConciliacion($hoja,ReportesModel::conciliacion($fechaInicial,$fechaFinal,$tipo,Tablas::conciliacion(),Tablas::usuarios(),Tablas::sucursales(),Tablas::Ccuentas(),Tablas::bancos(),Tablas::Cbeneficiarios(),Tablas::Cconceptos(),Tablas::Cmovimientos(),Tablas::empresas()));

		
		$hoja->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'A4:A'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'F3:F'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'G3:G'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'H3:H'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'J3:J'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'V3:V'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'W3:W'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'X3:X'.$fila);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('A3'), 'Y3:Y'.$fila);
		$hoja->getStyle('I3')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$documento->getActiveSheet()->duplicateStyle($documento->getActiveSheet()->getStyle('I3'), 'I4:I'.$fila);
		
		return $documento;
	}

	public static function encabezadosCompletoConciliacion($hoja){
		$fila=2;
		$columna = 1;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"	No.	 ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CUENTA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RESPONSABLE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"BANCO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE MOVIMIENTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE MOVIMIENTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"STATUS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MONTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE COBRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE POLIZA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CONCEPTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"BENEFICIARIO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLASIFICACIÓN DE MOVIMIENTO (NOMBRE)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLASIFICACIÓN DE MOVIMIENTO (DESCRIPCIÓN)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLASIFICACIÓN DE MOVIMIENTO (NOTA)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE NÓMINA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CAPTURÓ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"SUCURSAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE REGISTRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA DE REGISTRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA ÚLTIMA ACTUALIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"HORA ÚLTIMA ACTUALIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ESTATUS DE LÍNEA DE CAPTURA");

		for ($i = 'A'; $i !== 'AA'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
	}

	public static function filasreporteConciliacion($hoja,$respuesta){
		$columna=1;
		$fila=3;
		foreach ($respuesta as $row => $item){  
			$insercion = explode ( " ", $item['fecha_captura']);
			$actualizacion = explode ( " ", $item['fecha_ultima_actualizacion']);
			$clasificacion = $item['id_clasificacion_movimiento'] !== NULL ? ConciliacionModel::obtenerClasificacion($item['id_clasificacion_movimiento'],Tablas::Cmovimientos()) : '';
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cuenta']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,strtoupper($item['responsable']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['banco']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,strtoupper($item['empresa']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($item['fecha_movimiento'],8,2).'/'.substr($item['fecha_movimiento'],5,2).'/'.substr($item['fecha_movimiento'],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Conciliacion::tipo_movimiento($item['tipo_movimiento']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Conciliacion::tipo_status($item['tipo_movimiento'],$item['estatus']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['monto']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['fecha_cobro'] !== NULL ? substr($item['fecha_cobro'],8,2).'/'.substr($item['fecha_cobro'],5,2).'/'.substr($item['fecha_cobro'],0,4) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_poliza']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id_concepto'] !== NULL ? ConciliacionModel::obtenerNombre($item['id_concepto'],Tablas::Cconceptos()) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['id_beneficiario'] !== NULL ? strtoupper(ConciliacionModel::obtenerNombre($item['id_beneficiario'],Tablas::Cbeneficiarios())) : '');
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$clasificacion['nombre']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$clasificacion['descripcion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$clasificacion['nota']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_factura']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['numero_folio']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,str_replace('<br />','; ',$item['comentarios']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,strtoupper($item['capturo']));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($insercion[0],8,2).'/'.substr($insercion[0],5,2).'/'.substr($insercion[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$insercion[1]);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,substr($actualizacion[0],8,2).'/'.substr($actualizacion[0],5,2).'/'.substr($actualizacion[0],0,4));
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$actualizacion[1]);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,Conciliacion::tipo_registro($item['activa'],$item['autorizacion_extemporanea']));
			$columna=1;
			$fila++;
		}  
		return $fila;
	}

	public static function formatoLlenadoConciliacion(){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Layout conciliación')
			->setSubject('Layout conciliación')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de conciliación');
	
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("Conciliación");
	
		$hoja->setCellValue("A1", "CONCILIACIÓN");
		$hoja->mergeCells('A1:M1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3C8DBC');
		$hoja->getStyle('A2:M2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('A3:M3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('212F3D');
		$hoja->getStyle('A1:M3')->getFont()->setBold(true);
		$hoja->getStyle('A1:M3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:M3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

		$documento->getActiveSheet()->freezePane('A4');

		$letras = self::encabezadosConciliacionInsertar($hoja);

		$documento->createSheet();//añadimos una nueva hoja
		$hoja2 = $documento->setActiveSheetIndex(1);
		$hoja2= $documento->getActiveSheet();
		$hoja2->setTitle("Options");
		$hoja2->setCellValue("MNN1000", "ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_CONCILIACION");//anexamos la versión
	
		//Proteger segunda hoja 
		$hoja2->getProtection()->setPassword('3998202097258335');
		$hoja2->getProtection()->setSheet(true);
		$hoja2->getProtection()->setSort(true);
		$hoja2->getProtection()->setInsertRows(true);
		$hoja2->getProtection()->setInsertColumns(true);
		$hoja2->getProtection()->setFormatCells(true);
	
		$respuesta = ConciliacionModel::cargarCuentasLayout(Tablas::Ccuentas());
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("A".$indice,$item["cuenta"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'cuentas', $documento->getSheetByName('Options'), 'A1:A'.$indice ) );

		$documento->getSheetByName('Options')->SetCellValue("B1",'CHEQUE');
		$documento->getSheetByName('Options')->SetCellValue("B2",'INGRESO');  
		$documento->getSheetByName('Options')->SetCellValue("B3",'EGRESO'); 
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'tipo_movimiento', $documento->getSheetByName('Options'), 'B1:B3' ) );

		$documento->getSheetByName('Options')->SetCellValue("C1",'PRESTAMO');
		$documento->getSheetByName('Options')->SetCellValue("C2",'CANCELADO');  
		$documento->getSheetByName('Options')->SetCellValue("C3",'CIRCULACION');
		$documento->getSheetByName('Options')->SetCellValue("C4",'COBRADO'); 
		$documento->getSheetByName('Options')->SetCellValue("C5",'APLICADO');  
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'status', $documento->getSheetByName('Options'), 'C1:C5' ) );

		$respuesta = ConciliacionModel::cargarBeneficiarios(Tablas::Cconceptos(),true);
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("D".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'conceptos', $documento->getSheetByName('Options'), 'D1:D'.$indice ) );

		$respuesta = ConciliacionModel::cargarBeneficiarios(Tablas::Cbeneficiarios(),true);
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("E".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'beneficiarios', $documento->getSheetByName('Options'), 'E1:E'.$indice ) );

		$respuesta = ConciliacionModel::cargarMovimientos(Tablas::Cmovimientos(),true,0);
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("F".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'clasificacion', $documento->getSheetByName('Options'), 'F1:F'.$indice ) );


		$documento->setActiveSheetIndex(0);//la hoja que aparecera activa
		$documento->getSheetByName('Options')->setSheetState(Worksheet::SHEETSTATE_HIDDEN); //oculto la hoja de opciones
		
		$documento->getActiveSheet()->getProtection()->setPassword('3998202097258335');
		$documento->getActiveSheet()->getProtection()->setSheet(true);
		$documento->getActiveSheet()->getProtection()->setSort(true);
		$documento->getActiveSheet()->getProtection()->setInsertRows(true);
		$documento->getActiveSheet()->getProtection()->setInsertColumns(true);
		$documento->getActiveSheet()->getProtection()->setFormatCells(true);
		
		//$documento->getActiveSheet()->getStyle('A4:M104')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
	
		$columna=1;
		$fila=4;
		for($i=0;$i<100;$i++){  

					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					//$hoja->getStyle('AB4')->getNumberFormat()->setFormatCode('dd/mm/yyyy');
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');
					
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
					$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');

					$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$hoja->setCellValueByColumnAndRow($columna++,$fila,'');


					$objValidation = $documento->getActiveSheet()->getCell('A'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Error de captura'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setFormula1("=cuentas"); 

					$objValidation = $documento->getActiveSheet()->getCell('C'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=tipo_movimiento"); 

					$objValidation = $documento->getActiveSheet()->getCell('D'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=status"); 

					$objValidation = $documento->getActiveSheet()->getCell('H'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=conceptos"); 

					$objValidation = $documento->getActiveSheet()->getCell('I'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=beneficiarios"); 

					$objValidation = $documento->getActiveSheet()->getCell('J'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=clasificacion"); 

					$columna=1;
					$fila++;
		} 

		return $documento;
	}

	public static function encabezadosConciliacionInsertar($hoja){
		$columna=1;
		$fila=3;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CUENTA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE MOVIMIENTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"TIPO DE MOVIMIENTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"STATUS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MONTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"FECHA DE COBRO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE POLIZA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CONCEPTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"BENEFICIARIO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLASIFICACIÓN DE MOVIMIENTO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE FACTURA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NÚMERO DE NÓMINA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		
		$letra='A';
    	$letras=array();
		for($i=0;$i<$columna;$i++) 
        	$letras[$i] = $letra++;    
	
		$columna=1;
		$fila=2;

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'DD/MM/AAAA');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'DD/MM/AAAA');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'TEXTO');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'TEXTO');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'TEXTO');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'TEXTO');
		
		for ($i = 'A'; $i !== 'M'; $i++)
			$hoja->getColumnDimension($i)->setAutoSize(true);
		$hoja->getColumnDimension('M')->setWidth(120);
		
		return $letras;
	
	}

	public static function formatoCostosLayout(){
		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Layout Costos')
			->setSubject('Layout Costos')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de Costos');
		
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("COSTOS");
		$hoja->setCellValue("A1", "LAYOUT COSTOS");
		$hoja->mergeCells('A1:AA1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('696564');
		$hoja->getStyle('A2:T2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00A65A');
		$hoja->getStyle('U2:Y2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0e7c9b');
		$hoja->getStyle('Z2:AA2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f39c12');
		$hoja->getStyle('AB3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FE2E2E');
		$hoja->getStyle('A3:AA3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('212F3D');
		$hoja->getStyle('A1:AB3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:AB3')->getFont()->setBold(true);
		$hoja->getStyle('A2:AB3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		$hoja->getStyle('H4:S4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->getStyle('U4:X4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->getStyle('Z4:Z4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		//$hoja->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$fila=3;
		$columna = 1;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL PROMOTOR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL SUBCOMISIONISTA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CÓDIGO DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPLEADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RCV");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO RCV");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO IMSS OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RCV OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO RCV OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AMORTIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO AMORTIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS IMSS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMMA (GM)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"VIDA  INVALIDEZ (GM)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMME (GM)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS (GM) ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS GM (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMPUESTO ESTATAL (NOMINAS)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS NOMINAS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"ID DEL REGISTRO");
		$hoja->getColumnDimension('AB')->setWidth(20);
		
		$letra='A';
    	$letras=array();
		for($i=0;$i<$columna;$i++) 
        	$letras[$i] = $letra++;
		$columna=1;
		$fila=2;

		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'NUMERICO');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'NUMERICO');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'OPCIÓN MULTIPLE');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'TEXTO');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'COMENTARIOS GM');
		$hoja->getStyle($letras[$columna-1].$fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'000,000,000.00');
		$hoja->getStyle($letras[$columna-1].$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->setCellValueByColumnAndRow($columna++,$fila,'COMENTARIOS NOMINAS');

		
		
		$documento->createSheet();//añadimos una nueva hoja
		
		
		$hoja2 = $documento->setActiveSheetIndex(1);
		$hoja2 = $documento->getActiveSheet();
		$hoja2->setTitle("Options");
		$hoja2->setCellValue("MNN1000", "ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_MRV_COSTOS");
		

		$documento->getSheetByName('Options')->SetCellValue("I1",'ENERO');
		$documento->getSheetByName('Options')->SetCellValue("I2",'FEBRERO');  
		$documento->getSheetByName('Options')->SetCellValue("I3",'MARZO');
		$documento->getSheetByName('Options')->SetCellValue("I4",'ABRIL'); 
		$documento->getSheetByName('Options')->SetCellValue("I5",'MAYO'); 
		$documento->getSheetByName('Options')->SetCellValue("I6",'JUNIO');  
		$documento->getSheetByName('Options')->SetCellValue("I7",'JULIO'); 
		$documento->getSheetByName('Options')->SetCellValue("I8",'AGOSTO'); 
		$documento->getSheetByName('Options')->SetCellValue("I9",'SEPTIEMBRE'); 
		$documento->getSheetByName('Options')->SetCellValue("I10",'OCTUBRE'); 
		$documento->getSheetByName('Options')->SetCellValue("I11",'NOVIEMBRE'); 
		$documento->getSheetByName('Options')->SetCellValue("I12",'DICIEMBRE'); 
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'meses', $documento->getSheetByName('Options'), 'I1:I12' ) );


		$respuesta = CostosModel::cargarClientesLayout('costos_clientes_ae');
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("A".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'clientes', $documento->getSheetByName('Options'), 'A1:A'.$indice ) );



		$respuesta = CostosModel::cargarClientesLayout('costos_promotor_ae');
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("K".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'promotor', $documento->getSheetByName('Options'), 'K1:K'.$indice ) );


		$respuesta = CostosModel::cargarClientesLayout('costos_subcomisionista_ae');
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("P".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'subcomisionista', $documento->getSheetByName('Options'), 'P1:P'.$indice ) );


		$respuesta = CostosModel::cargarEmpresasLayout('nominas_empresas_facturadoras_ae');
		$indice=1;
		foreach($respuesta as $row => $item){
			$documento->getSheetByName('Options')->SetCellValue("T".$indice,$item["nombre"]); 
			$indice++;
		}
		$indice = $indice - 1;
		$documento->addNamedRange( new \PhpOffice\PhpSpreadsheet\NamedRange( 'empresas', $documento->getSheetByName('Options'), 'T1:T'.$indice ) );
		
		
		$documento->setActiveSheetIndex(0);
		$documento->getSheetByName('Options')->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

		$hoja2->getProtection()->setPassword('3998202097258335');
		$hoja2->getProtection()->setSheet(true);
		$hoja2->getProtection()->setSort(true);
		$hoja2->getProtection()->setInsertRows(true);
		$hoja2->getProtection()->setInsertColumns(true);
		$hoja2->getProtection()->setFormatCells(true);
		
		$columna=1;
		$fila=4;
		for($i=0;$i<100;$i++){  

					
					$objValidation = $documento->getActiveSheet()->getCell('A'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Input error'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setPromptTitle('Pick from list'); 
					$objValidation->setFormula1("=meses");

					$objValidation = $documento->getActiveSheet()->getCell('B'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Error de captura'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setFormula1("=clientes"); 

					$objValidation = $documento->getActiveSheet()->getCell('C'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Error de captura'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setFormula1("=promotor");

					$objValidation = $documento->getActiveSheet()->getCell('D'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Error de captura'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setFormula1("=subcomisionista");

					$objValidation = $documento->getActiveSheet()->getCell('G'.$fila)->getDataValidation(); 
					$objValidation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST ); 
					$objValidation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP ); 
					$objValidation->setAllowBlank(false); 
					$objValidation->setShowInputMessage(true); 
					$objValidation->setShowErrorMessage(true); 
					$objValidation->setShowDropDown(true); 
					$objValidation->setErrorTitle('Error de captura'); 
					$objValidation->setError('El valor no existe.'); 
					$objValidation->setFormula1("=empresas");

					$columna=1;
					$fila++;
		} 

		/*$hoja = $documento->getActiveSheet();
		$documento->getActiveSheet()->getProtection()->setSheet(true);
		$documento->getDefaultStyle()->getProtection()->setLocked(false);
		$hoja->getStyle('H4')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);*/

		for ($i = 'A'; $i !== 'AB'; $i++){$hoja->getColumnDimension($i)->setAutoSize(true);}
		return $documento;
		
	}
	public static function ResporteCostosFin($inicio,$fin){

		$documento = new Spreadsheet();
		$documento
			->getProperties()
			->setCreator("Intranet Asesores Empresariales")
			->setLastModifiedBy('Intranet Asesores Empresariales')
			->setTitle('Reporte Costos')
			->setSubject('Reporte Costos')
			->setDescription('Este documento fue generado por Intranet Asesores Empresariales')
			->setKeywords('')
			->setCategory('Modulo de Costos');
		
		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("REPORTE COSTOS");
		$hoja->setCellValue("A1", "REPORTE COSTOS");
		$hoja->getStyle('A2')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		$hoja->mergeCells('A1:AA1');
		$hoja->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('1C1C1C');
		$hoja->getStyle('A2:AA2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('585858');
		//$hoja->getActiveSheet()->mergeCells('A3:A2');
		$hoja->getStyle('A3:AA3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('585858');
		$hoja->getStyle('A1:AB3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$hoja->getStyle('A1:AB3')->getFont()->setBold(true);
		$hoja->getStyle('A2:AB3')->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
		$hoja->getStyle('H4:S4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->getStyle('U4:X4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$hoja->getStyle('Z4:Z4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		//$hoja->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$fila=3;
		$columna = 1;
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"MES");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL PROMOTOR");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"NOMBRE DEL SUBCOMISIONISTA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"CÓDIGO DEL CLIENTE");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPLEADOS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"EMPRESA");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO IMSS");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RCV");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO RCV");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO INFONAVIT");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMSS OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO IMSS OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"RCV OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO RCV OBRERO");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"AMORTIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"REAL PAGADO AMORTIZACIÓN");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS IMSS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMMA (GM)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"VIDA  INVALIDEZ (GM)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"GMME (GM)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"OTROS (GM) ");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS GM (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"IMPUESTO ESTATAL");
		$hoja->setCellValueByColumnAndRow($columna++,$fila,"COMENTARIOS NOMINAS (NO INCLUYAS COMILLAS SIMPLES NI DOBLES)");
		$hoja->getColumnDimension('AA')->setWidth(65);
		/*consulta para traer de una fecha a otra   DATE = YYYY/MM/DD
		SELECT * FROM costos_ae WHERE estatus='1' AND (DATE	(registro)) BETWEEN '2020/11/03' AND '2020/11/03'*/
		$respuesta=CostosModel::reporteCostos($inicio,$fin);
		$columna=1;
		$fila=4;
		foreach ($respuesta as $row => $item){  

			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['mes']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['promotor']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['subcomisionista']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['codigo_cliente']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empleados']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['empresa']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['rcv']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_rcv']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_infonavit']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['imss_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_imss_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['rcv_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_rcv_obrero']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['amortizacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['real_amortizacion']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comentarios_imss']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['gmma']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['vida_invalidez']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['gmme']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['otros']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comentarios_gm']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['impuesto_estatal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['comentarios_nominas']);
			
			$columna=1;
			$fila++;

		}

		for ($i = 'A'; $i !== 'AA'; $i++){$hoja->getColumnDimension($i)->setAutoSize(true);}
		return $documento;

	}

	
}
?>