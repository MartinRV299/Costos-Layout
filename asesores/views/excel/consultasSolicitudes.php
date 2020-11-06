<?php

class ConexionGiro{
	public static function conectar(){
		try{
			$link = new PDO('mysql:host=127.0.0.1;dbname=asesores_empresariales', 'root' , '',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
			return $link;
		}
		catch(PDOException $e){
			return false;
		}
	}
}

class Consultas{

    public static function obtenerIdRecursosHumanos(){
		$stmt = ConexionGiro::conectar()->prepare("SELECT permisos_ae.id_usuario,permisos_ae.id_usuario_cambio, permisos_ae.tipo_solicitud,permisos_ae.tipo_permiso,permisos_ae.fecha_inicio,permisos_ae.fecha_fin,permisos_ae.horario_inicio,permisos_ae.horario_fin,permisos_ae.motivo,usuarios_ae.nombre AS nombre,paterno,materno,sucursales_ae.nombre AS sucursal, departamentos_ae.nombre AS departamento, puestos_ae.nombre AS puesto FROM permisos_ae INNER JOIN usuarios_ae ON permisos_ae.id_usuario = usuarios_ae.id_usuario INNER JOIN sucursales_ae ON usuarios_ae.id_sucursal = sucursales_ae.id_sucursal INNER JOIN departamentos_ae ON usuarios_ae.id_departamento = departamentos_ae.id_departamento INNER JOIN puestos_ae ON usuarios_ae.id_puesto = puestos_ae.id_puesto WHERE usuarios_ae.situacion = 1 AND (permisos_ae.enterado_cambio IS NULL OR permisos_ae.enterado_cambio > 0) ORDER BY permisos_ae.fecha_inicio");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt -> close();	
	} 
	
	public static function iniciar(){
        $respuesta = self::obtenerIdRecursosHumanos();
        return $respuesta;
    }
    
    public static function traducirPermisos($dato2){
		$permiso = array('JUSTIFICANTE DE IMSS','JUSTIFICANTE DEL MÉDICO PARTICULAR','DÍA COMPLETO','MEDIO DÍA','PERIODO DE AUSENCIA POR HORAS','SALIDA TEMPRANO','BONO BIMESTRAL','LUTO','FALTA INJUSTIFICADA','SUSPENSIÓN','PATERNIDAD','MATERNIDAD');
		return $permiso[$dato2-1];
	}
}

$respuesta = Consultas::iniciar();

require dirname(__FILE__).'/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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

$hoja->setCellValue("A1", "NOMBRE");
$hoja->getColumnDimension('A')->setAutoSize(true);
//$hoja->getRowDimension(1)->setRowHeight(120);
$hoja->setCellValue("B1", "SUCURSAL");
$hoja->getColumnDimension('B')->setAutoSize(true);
$hoja->setCellValue("C1", "DEPARTAMENTO");
$hoja->getColumnDimension('C')->setAutoSize(true);
$hoja->setCellValue("D1", "PUESTO");
$hoja->getColumnDimension('D')->setAutoSize(true);
$hoja->setCellValue("E1", "TIPO DE PERMISO");
$hoja->getColumnDimension('E')->setAutoSize(true);
$hoja->setCellValue("F1", "FECHA DE SOLICITUD");
$hoja->getColumnDimension('F')->setAutoSize(true);
$hoja->setCellValue("G1", "HORARIO DE SOLICITUD");
$hoja->getColumnDimension('G')->setAutoSize(true);
$hoja->setCellValue("H1", "MOTIVO");
$hoja->getColumnDimension('H')->setAutoSize(true);

$hoja->getStyle('A1:H1')->getFont()->setBold(true);

$columna=1;
$fila=2;

foreach ($respuesta as $row => $item){  
            
            $fechaInicio=$item['fecha_inicio'] != $item['fecha_fin'] ? substr($item['fecha_inicio'],8,2).'-'.substr($item['fecha_inicio'],5,2).'-'.substr($item['fecha_inicio'],0,4).' al '.substr($item['fecha_fin'],8,2).'-'.substr($item['fecha_fin'],5,2).'-'.substr($item['fecha_fin'],0,4) : substr($item['fecha_inicio'],8,2).'-'.substr($item['fecha_inicio'],5,2).'-'.substr($item['fecha_inicio'],0,4);
            
            if($item['tipo_solicitud'] != 1 ){
                if($item['tipo_solicitud'] == 2 ){
                    $permiso="VACACIONES";
                     $motivo = '';
                     $horario='';
                }
                else{
                     $permiso="CAMBIO DE GUARDIA";
                     $motivo = '';
                     $horario='';
                }     
            }
            else{
                $permiso = Consultas::traducirPermisos($item["tipo_permiso"]);
                $motivo=$item['motivo'];
                if($item["tipo_permiso"] == 3 || $item["tipo_permiso"] == 7)
                    $horario = '';
                else
                    $horario = 'de las '.$item['horario_inicio'].' a las '.$item['horario_fin'];
            }
            
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$item['nombre'].' '.$item['paterno'].' '.$item['materno']);
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$item['sucursal']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['departamento']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$item['puesto']);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$permiso);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$fechaInicio);
			$hoja->setCellValueByColumnAndRow($columna++,$fila,$horario);
            $hoja->setCellValueByColumnAndRow($columna++,$fila,$motivo);
            $columna=1;
            $fila++;
		}  
 
$nombreDelDocumento = "Reporte-permisos.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');
 
$writer = IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;
