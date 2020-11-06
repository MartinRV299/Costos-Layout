<?php
session_start();
if(!$_SESSION["validar"]){
  header("location:ingreso");
  exit();
}
require_once "../../models/EventosModel.php";
require_once "../../models/config.php";
require_once "../../controllers/Eventos.php";
require_once "../../controllers/MetodosDiversos.php";

$alimentacion = EventosModel::datos($_SESSION['identificador'],Tablas::alimentacion());
$respuesta = EventosModel::datosUsuario($_SESSION['identificador'],Tablas::usuarios(),Tablas::sucursales());

require dirname(__FILE__).'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

$thml2pdf=new Html2Pdf();
$thml2pdf->writeHTML(
    '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        
        <style>
            * {
                margin: 0;
                padding: 0;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .contenedor{
                width: 98%;
                margin-top: 10px;
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
    <div class="contenedor">
            
        
        
        <table>
            <tr>
                <td style="width:360px; text-align: left; vertical-align: middle;"><img src="imagenes/asesores.jpg" alt=""></td>
                <td style="width:360px; text-align: right; vertical-align: middle;"><img src="imagenes/nutri-mini2.png" alt=""></td>
            </tr>  
        </table>

        <br>
        <div><b>Nombre:</b> '.$respuesta['nombre'].' '.$respuesta['paterno'].' '.$respuesta['materno'].'</div>
        <hr>
        <br>
        <table>
            <tr>
                <td height="40" style="width:240px; text-align: center; vertical-align: middle;border:2px; background:#A9A9A9;">DESAYUNO</td>
                <td style="width:240px; text-align: center; vertical-align: middle;border:2px; background:#A9A9A9;">COMIDA</td>
                <td style="width:240px; text-align: center; vertical-align: middle;border:2px; background:#A9A9A9;">CENA</td>
            </tr>  
            <tr>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Leche: </b>'.$alimentacion['leche1'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Leche: </b>'.$alimentacion['leche2'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Leche: </b>'.$alimentacion['leche3'].'</td>
            </tr>  
            <tr>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Cereales: </b>'.$alimentacion['cereales1'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Cereales: </b>'.$alimentacion['cereales2'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Cereales: </b>'.$alimentacion['cereales3'].'</td>
            </tr>  
            <tr>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Leguminosa: </b>'.$alimentacion['leguminosas1'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Leguminosa: </b>'.$alimentacion['leguminosas2'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Leguminosa: </b>'.$alimentacion['leguminosas3'].'</td>
            </tr>  
            <tr>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Carne: </b>'.$alimentacion['carnes1'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Carne: </b>'.$alimentacion['carnes2'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Carne: </b>'.$alimentacion['carnes3'].'</td>
            </tr>  
            <tr>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Fruta: </b>'.$alimentacion['frutas1'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Fruta: </b>'.$alimentacion['frutas2'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Fruta: </b>'.$alimentacion['frutas3'].'</td>
            </tr>  
            <tr>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Verdura: </b>'.$alimentacion['verduras1'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Verdura: </b>'.$alimentacion['verduras2'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Verdura: </b>'.$alimentacion['verduras3'].'</td>
            </tr>  
            <tr>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Grasa: </b>'.$alimentacion['grasas1'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Grasa: </b>'.$alimentacion['grasas2'].'</td>
                <td style="width:240px; text-align: left; vertical-align: middle;"><b>Grasa: </b>'.$alimentacion['grasas3'].'</td>
            </tr>  
        </table>
        
        <br>
        <br>
        <br>
        <span><b>Colasion:</b></span>
        <br>
        <div style="border:2px solid #000; padding: 10px 0 10px 10px">
            '.$alimentacion['colasiones'].'
        </div>

        
    </div>
  
    </body>
    </html>'
);

$nombreDelDocumento = "Plan-alimenticio.pdf";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');
$thml2pdf->output('Plan-alimenticio.pdf');
