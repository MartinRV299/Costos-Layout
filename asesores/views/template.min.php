<?php
session_start();
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared;


class Cabeceras{

  public function formatoLlenadoCostos(){
     // $privilegios = GrupoCostos::modulo($_SESSION['identificador']);
      $documento = Reportes::formatoCostosLayout();
      /*if($privilegios=='gm'){
          $documento = Reportes::formatoCostosLayout();
          $hoja = $documento->getActiveSheet();
          $documento->getActiveSheet()->getProtection()->setSheet(true);
          $documento->getDefaultStyle()->getProtection()->setLocked(false);
          $hoja->getStyle('A4:T100')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
          $hoja->getStyle('Z4:AA100')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
          $hoja->getStyle('U4:X4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
          //
        }*/

        header('Content-Disposition: attachment;filename="Laout-Costos.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento,'Xlsx');
        $writer->save('php://output');
        exit;
		
  }
  public function reportesCostos(){
      //echo 'incio: '.$this->fechaInicio;
      //echo 'incio: '.$this->fechaFinal;

     $documento = Reportes::ResporteCostosFin($this->fechaInicio,$this->fechaFinal);
     header('Content-Disposition: attachment;filename="Reporte-Costos.xlsx"');
     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     header('Cache-Control: max-age=0');

     $writer = IOFactory::createWriter($documento,'Xlsx');
     $writer->save('php://output');
     exit;   

    
    
 }


}


if(isset($_POST['FormatoLayoutCostos'])){
	$a = new Cabeceras();
	$a->formatoLlenadoCostos();
}else if(isset($_POST['reporteCostos']) && $_SESSION["validar"]){
	$a = new Cabeceras();
	$a->fechaInicio = $_POST['fechaInicio'];
	$a->fechaFinal = $_POST['fechaFinal'];
	$a->reportesCostos();
}


?>



<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    
    <title>Asesores Empresariales</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="Shortcut Icon" href="<?php echo Ruta::ruta_server(); ?>views/img/asesores.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/bootstrap.min.css?23">
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/ionicons.min.css">
    <link rel='stylesheet' href='<?php echo Ruta::ruta_server(); ?>views/css/timepicki.css'/>
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/AdminLTE.min.css"><!-- Theme style -->
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/skins/_all-skins.min.css"><!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/sweetalert2.min.css">
    <!-- estilos personalizados -->
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/Conciliacion.css">
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/estilos.css?2">
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/css/paginacion.min.css">
    <link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/js/visor-crow/visor.min.css">
	<link rel="stylesheet" href="<?php echo Ruta::ruta_server(); ?>views/js/visor-pdf-crow/visor-pdf.css">


  </head>
  <body class="hold-transition sidebar-mini <?php  echo ' '.$respuesta = isset($_COOKIE['configColorScreen']) ? $_COOKIE['configColorScreen'] : ''; echo ' '.$respuesta = isset($_COOKIE['configSideLeft']) ? $_COOKIE['configSideLeft'] : ''; echo ' '.$respuesta = isset($_COOKIE['configScreenSize']) ? $_COOKIE['configScreenSize'] : '';?>">
    
    <div class="wrapper">
      <?php
        $modulos=new Enlaces();
        $modulos->enlacesController();
      ?>
    </div>

    <script src="<?php echo Ruta::ruta_server(); ?>views/js/jquery.min.js"></script><!-- jQuery 3 -->
    <script src='<?php echo Ruta::ruta_server(); ?>views/js/timepicki.js'></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/jquery.mask.min.js"></script><!-- jQuery 3 -->
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/bootstrap.min.js"></script><!-- Bootstrap 3.3.7 -->
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/bootstrap-select.js"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/jquery.slimscroll.min.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/fastclick.js"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/moment/min/moment.min.js"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/metodosDiversos.min.js?30"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/bootstrap-year-calendar.js?v2"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/adminlte.min.js"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/demo.js"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/sweetalert2.min.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/push.min.js"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/preferencias.min.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/md5.min.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/configuraciones.min.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/funcionalidades.min.js" async="async"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/validarIngreso.min.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/visor-crow/visor.min.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/visor-pdf-crow/pdfobject.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/visor-pdf-crow/visor-pdf.js"></script>
    <script src="<?php echo Ruta::ruta_server(); ?>views/js/jquery-ui.min.js"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/jquery.knob.js" async="async"></script>
	<script src="<?php echo Ruta::ruta_server(); ?>views/js/nominas3-1.min.js" async="async"></script>
  <script src="<?php echo Ruta::ruta_server(); ?>views/js/facturacion-1.js?7" async="async"></script>
  <script src="<?php echo Ruta::ruta_server(); ?>views/js/Costos.js?7" async="async"></script>
<script src="<?php echo Ruta::ruta_server(); ?>views/js/gastos.js?7" async="async"></script>
  
    <script>
      $(document).ready(function () {
        $('.sidebar-menu').tree()
      });
    </script>
  </body>
</html>
