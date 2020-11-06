<?php
session_start();
if(!$_SESSION["validar"]){
  header("location:ingreso");
  exit();
}

include_once '../../models/permisos.php';
include_once '../../controllers/permisos.php';
include_once '../../models/usuarios.php';
include_once '../../models/sucursales.php';
include_once '../../models/departamentosPuestos.php';
include_once '../../controllers/MetodosDiversos.php';
include_once '../../models/config.php';
include_once '../../models/ConsultasGiroModel.php';

if($_SESSION['identificador'] !=168 AND $_SESSION['identificador'] !=171 AND $_SESSION['identificador'] != 390 AND $_SESSION['identificador'] !=215){
    if(PermisosModels::verificarPermisoPertenezcaUsusario($_GET['idPermiso'],"permisos_ae"))
        exit();
}


$permiso = PermisosModels::permisoUnicoModels($_GET['idPermiso'],"permisos_ae","dependencias_jefe_ae");
$usuario = Datos::mostrarUsuarioUnicoModel2($permiso["id_usuario"],Tablas::usuarios());
$jefe = Datos::mostrarUsuarioUnicoModel2($permiso["id_jefe"],Tablas::usuarios());
$rh = PermisosModels::obtenerIdRecursosHumanos2('dependencias_rh_ae');
$rh = Datos::mostrarUsuarioUnicoModel2($rh,Tablas::usuarios());
$empresa = ConsultasGiroModel::obtenerRegistro($usuario['clave'],TablasGiro::patronales());

$solicitud1=$solicitud2=$solicitud3=$sueldoSi=$sueldoNo=$justificanteSi=$justificanteNo='<img src="imagenes/empty.png" alt="">';
for($i=1;$i<=12;$i++){
    $tipo_permiso[$i]='<img src="imagenes/empty.png" alt="">';
}
$motivo="";
$fecha_otorgarse='';
$horario='';

$porDisfrutar='';
$disfrutados='';
$disponibles='';
$reincorporacion='';
$vacacionesInicio='';
$vacacionesFin='';

$nombrePermuta='';
$puestoPermuta='';
$fechaIngresoPermuta='';
$diaGuardia='';
$cambiarAdiaGuardia='';
$firmaPermuta = '';

if($permiso['tipo_solicitud'] == 1){
    $solicitud1='<img src="imagenes/check.png" alt="">';
    $tipo_permiso[$permiso['tipo_permiso']]='<img src="imagenes/check.png" alt="">';
    $motivo = $permiso['motivo'];
    $fecha_otorgarse=substr($permiso['fecha_inicio'],8,2).'-'.substr($permiso['fecha_inicio'],5,2).'-'.substr($permiso['fecha_inicio'],0,4);
    $horario = 'De las '.PermisosControllers::formatoHora($permiso['horario_inicio']).' a las '.PermisosControllers::formatoHora($permiso['horario_fin']);
}
else if($permiso['tipo_solicitud'] == 2){
    $solicitud2='<img src="imagenes/check.png" alt="">';
    //$vacacionesDisponibles =;
    //$vacacionesSolicitadas = ;
    /*parche semana santa 2019*///
    /*$sabadoSanto=0;
    if( $permiso['fecha_fin'] == '2019-04-20')
        $sabadoSanto=1;*/
    /*************/
    $contarSabados = $permiso['cuenta_sabado'] === NULL ? 0 : $permiso['cuenta_sabado'];

    $porDisfrutar= (MetodosDiversos::calcularDiasHabiles($permiso['fecha_inicio'],$permiso['fecha_fin'])) + $contarSabados;

    if(  date($permiso['fecha_inicio']) <= date('2019-12-25')  &&  date($permiso['fecha_fin']) >= date('2019-12-25') )
        $porDisfrutar -= 1;
    if(  date($permiso['fecha_inicio']) <= date('2020-01-01')  &&  date($permiso['fecha_fin']) >= date('2020-01-01') )
        $porDisfrutar -= 1;

    if(  date($permiso['fecha_inicio']) <= date('2020-02-03')  &&  date($permiso['fecha_fin']) >= date('2020-02-03') )
        $porDisfrutar -= 1;

    if(  date($permiso['fecha_inicio']) <= date('2020-03-16')  &&  date($permiso['fecha_fin']) >= date('2020-03-16') )
        $porDisfrutar -= 1;

    $disfrutados=PermisosControllers::vacacionesDisfrutadas($permiso["id_usuario"]);
    $disponibles= PermisosModels::vacacionesDisponibles($permiso["id_usuario"],Tablas::usuarios());
    $reincorporacion= !empty($permiso['fecha_incorporacion']) ? substr($permiso['fecha_incorporacion'],8,2).'-'.substr($permiso['fecha_incorporacion'],5,2).'-'.substr($permiso['fecha_incorporacion'],0,4) : '';
    $vacacionesInicio=substr($permiso['fecha_inicio'],8,2).'-'.substr($permiso['fecha_inicio'],5,2).'-'.substr($permiso['fecha_inicio'],0,4);
    $vacacionesFin=substr($permiso['fecha_fin'],8,2).'-'.substr($permiso['fecha_fin'],5,2).'-'.substr($permiso['fecha_fin'],0,4);
}
else if($permiso['tipo_solicitud'] == 3){
    $solicitud3='<img src="imagenes/check.png" alt="">';
    $permuta = Datos::mostrarUsuarioUnicoModel2($permiso["id_usuario_cambio"],"usuarios_ae");
    $nombrePermuta=$permuta['nombre'].' '.$permuta['paterno'].' '.$permuta['materno'];
    $puestoPermuta=Departamentos::vistaPuestos2Model($permuta['id_puesto'],"puestos_ae");
    $fechaIngresoPermuta=substr($permuta['fecha_ingreso'],8,2).'-'.substr($permuta['fecha_ingreso'],5,2).'-'.substr($permuta['fecha_ingreso'],0,4);
    $diaGuardia=substr($permiso['fecha_inicio'],8,2).'-'.substr($permiso['fecha_inicio'],5,2).'-'.substr($permiso['fecha_inicio'],0,4);
    $cambiarAdiaGuardia=substr($permiso['fecha_fin'],8,2).'-'.substr($permiso['fecha_fin'],5,2).'-'.substr($permiso['fecha_fin'],0,4);
    $firmaPermuta = Llaves::firma($permuta['id_usuario']);
}


if($permiso['goce_sueldo'] == 1){
    $sueldoSi='<img src="imagenes/check.png" alt="">';
}
else{
    $sueldoNo='<img src="imagenes/check.png" alt="">';
}
if($permiso['justificante'] == 1){
    $justificanteSi='<img src="imagenes/check.png" alt="">';
}
else{
    $justificanteNo='<img src="imagenes/check.png" alt="">';
}

require dirname(__FILE__).'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
//ob_start();
//require_once 'solicitud.php';
//$html = ob_get_clean();
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
                height: 98.5%;
                margin-top: 10px;
                margin-left: 10px;
            }
                    .primeraSeccion{
                        height: 12%;
                        border: 2px solid;
                    }
                    .border{
                            border-collapse: collapse;
                        }
                    .border td, .border th {
                            border: 1px solid black;
                    }
                    .segundaSeccion{
                        margin-top: -2px;
                        height: 5%;
                        border: 2px solid;
                    }
                    .terceraSeccion{
                        margin-top: -2px;
                        height: 25%;
                        border: 2px solid;
                    }
                    .cuartaSeccion{
                        margin-top: -2px;
                        height: 11%;
                        border: 2px solid;
                    }
                    .quintaSeccion{
                        margin-top: -2px;
                        height: 17%;
                        border: 2px solid;
                    }
                    .sextaSeccion{
                        margin-top: -2px;
                        height: 12%;
                        border: 2px solid;
                    }
                    .septimaSeccion{
                        margin-top: -2px;
                        height: 16%;
                        border: 2px solid;
                    }
                    .tituloSeccion{
                        margin-top: 10px;
                        text-align: center;
                        width: 740px;
                        font-weight: bold;
                    }
                    table{
                        margin-left: 3px;
                    }
                    .celda{
                        border-bottom: 1px solid #000;
                    }
                    .textoValidacion{
                        font-size: 8px;
                    }
                    .textoCampos{
                        font-size: 12px;
                    }
           
        </style>
    </head>
    <body>
        <div class="contenedor">
            <div class="primeraSeccion">
                <table class="border">
                    <tr>
                        <!--<td style="width:182px; height:38px; text-align: center; background: url(imagenes/logo.png);"></td>-->
                        <td style="width:182px; height:38px; text-align: center; vertical-align: middle"><b>'.$empresa.'</b></td>
                        <td style="width:324px; text-align: center; vertical-align: middle;"><h3>MULTIFORMATO RH</h3></td>
                        <td style="width:152px; text-align: center; vertical-align: middle;">CÓDIGO</td>
                        <td style="width:65px; text-align: center; vertical-align: middle;">MRH01</td>
                    </tr>  
                </table>
                <br>
                <table>
                    <tr>
                        <td style="width:52px;"></td>
                         <td style="width:400px;"></td>
                        <td style="width:75px; text-align: right;">Fecha:</td>
                        <td style="width:200px;" class="celda">'.substr($permiso['fecha_solicitud'],8,2).'-'.substr($permiso['fecha_solicitud'],5,2).'-'.substr($permiso['fecha_solicitud'],0,4) .'</td>
                    </tr>
                    <!--<tr>
                        <td style="width:52px;"></td>
                        <td style="width:400px;"></td>
                        <td style="width:75px;"></td>
                        <td style="width:200px;">&nbsp;</td>
                    </tr>-->
                    <tr>
                        <td style="width:52px;">Nombre:</td>
                        <td style="width:400px;" class="celda textoCampos">'.$usuario['nombre'].' '.$usuario['paterno'].' '.$usuario['materno'].'</td>
                        <td style="width:75px; text-align: right;">Sucursal:</td>
                        <td style="width:200px;" class="celda textoCampos">'.Sucursales::traducirSucursalesModel($usuario["id_sucursal"],"sucursales_ae").'</td>
                    </tr>
                </table>
               
                <table class="border2">
                    <tr>
                        <td style="width:52px;">Puesto:</td>
                        <td style="width:350px;" class="celda textoCampos">'.Departamentos::vistaPuestos2Model($usuario['id_puesto'],"puestos_ae") .'</td>
                        <td style="width:125px; text-align: right;">Fecha de ingreso:</td>
                        <td style="width:200px;" class="celda">'.substr($usuario['fecha_ingreso'],8,2).'-'.substr($usuario['fecha_ingreso'],5,2).'-'.substr($usuario['fecha_ingreso'],0,4) .'</td>
                    </tr>
                </table>
            </div>
            <div class="segundaSeccion">
                <br>
                <table>
                    <tr>
                        <td style="width:142px;">Solicitud de:</td>
                        <td style="width:95px;">A) Permiso</td>
                        <td style="width:80px;">'.$solicitud1.'</td>
                        <td style="width:100px;">B) Vacaciones</td>
                        <td style="width:80px;">'.$solicitud2.'</td>
                        <td style="width:160px;">C) Cambio de guardia</td>
                        <td style="width:60px;">'.$solicitud3.'</td>
                    </tr>
                </table>
            </div>
            <div class="terceraSeccion">
                <div class="tituloSeccion">A) PERMISO</div>
                <br>
                <table>
                    <tr>
                        <td style="width:285px;"><b>EC - Enfermedad comprobada</b></td>
                        <td style="width:50px;"></td>
                        <td style="width:80px;"></td>
                        <td style="width:255px;"><b>LU - Luto</b></td>
                        <td style="width:50px;">'.$tipo_permiso[8].'</td>
                    </tr>
    
                     <tr>
                        <td>01 - Justificante de IMSS</td>
                        <td>'.$tipo_permiso[1].'</td>
                        <td></td>
                        <td><b>FI - Falta injustificada</b></td>
                        <td>'.$tipo_permiso[9].'</td>
                    </tr>
    
                     <tr>
                        <td>02 - Justificante de médico particular</td>
                        <td>'.$tipo_permiso[2].'</td>
                        <td></td>
                        <td><b>SU - Suspensión</b></td>
                        <td>'.$tipo_permiso[10].'</td>
                    </tr>
    
                    <tr>
                        <td><b>PE - Permiso</b></td>
                        <td>'.$tipo_permiso[11].'</td>
                        <td></td>
                        <td><b>MA - Maternidad</b></td>
                        <td>'.$tipo_permiso[12].'</td>
                    </tr>
    
                     <tr>
                        <td>01 - Día completo</td>
                        <td>'.$tipo_permiso[3].'</td>
                        <td></td>
                        <td><b>PA - Paternidad</b></td>
                        <td><img src="imagenes/empty.png" alt=""></td>
                    </tr>
    
                     <tr>
                        <td>02 - Medio día</td>
                        <td>'.$tipo_permiso[4].'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>03 - Periodo de ausencia por horas</td>
                        <td>'.$tipo_permiso[5].'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>04 - Salida temprano</td>
                        <td>'.$tipo_permiso[6].'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>05 - Bono bimestral</td>
                        <td>'.$tipo_permiso[7].'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:46px;">Motivo:</td>
                        <td style="width:684px;" class="celda textoCampos">'.$motivo.'</td>
                    </tr>
                </table>
            

                <table>
                    <tr>
                        <td style="width:114px;">Fecha a otorgarse:</td>
                        <td style="width:200px;" class="celda">'.$fecha_otorgarse.'</td>
                        <td style="width:180px; text-align: right;">Horario de ausencia:</td>
                        <td style="width:230px;" class="celda">'.$horario.' </td>
                    </tr>
                </table>
             </div>
             <div class="cuartaSeccion">
                <div class="tituloSeccion">B) VACACIONES</div>
                <br>
                <table>
                    <tr>
                        <td style="width:70px;">A disfrutar:</td>
                        <td style="width:180px; text-align: center;" class="celda">'.$porDisfrutar.'</td>
                        <td style="width:200px; text-align: right;">A partir del día</td>
                        <td style="width:122px; text-align: center;" class="celda">'.$vacacionesInicio.'</td>
                        <td style="width:21px; text-align: center;">al</td>
                        <td style="width:122px; text-align: center;" class="celda">'.$vacacionesFin.'</td>
                    </tr>
                    <tr>
                        <td style="width:70px;">Disfrutados:</td>
                        <td style="width:180px; text-align: center;" class="celda">'.$disfrutados.'</td>
                        <td style="width:200px; text-align: right;"></td>
                        <td style="width:122px;"></td>
                        <td style="width:21px; text-align: center;"></td>
                        <td style="width:122px;"></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:70px;">Disponibles:</td>
                        <td style="width:180px; text-align: center;" class="celda">'.$disponibles.'</td>
                        <td style="width:200px; text-align: right;">Reincorporación:</td>
                        <td style="width:269px; text-align: center;"class="celda">'.$reincorporacion.'</td>
                    </tr>
                </table>
             </div>
             <div class="quintaSeccion">
                <div class="tituloSeccion">C) CAMBIO DE GUARDIA</div>
                <br>
                <table>
                    <tr>
                        <td style="width:100px;">Día de guardia:</td>
                        <td style="width:148px;" class="celda">'.$diaGuardia.'</td>
                        <td style="width:124px;"></td>
                        <td colspan="2" style="width:348px; text-align:center;"></td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td style="width:728px; text-align: center;"><b>CAMBIA CON:</b></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:50px;">Nombre:</td>
                        <td style="width:298px;" class="celda textoCampos">'.$nombrePermuta.'</td>
                        <td style="width:24px;"></td>
                        <td style="width:50px;"></td>
                        <td style="width:298px;"></td>
                    </tr>
                    <tr>
                        <td style="width:50px;">Puesto:</td>
                        <td style="width:298px;" class="celda textoCampos">'.$puestoPermuta.'</td>
                        <td style="width:24px;"></td>
                        <td colspan="2" style="width:348px; text-align:center;" class="textoCampos">'.$nombrePermuta.'</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:110px;">Fecha de ingreso:</td>
                        <td style="width:238px;" class="celda">'.$fechaIngresoPermuta.'</td>
                        <td style="width:24px;"></td>
                        <td colspan="2" style="width:348px; text-align:center;" class="celda textoValidacion">'.$firmaPermuta.'</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:95px;">Cambio al día:</td>
                        <td style="width:253px;" class="celda">'.$cambiarAdiaGuardia.'</td>
                        <td style="width:24px;"></td>
                        <td colspan="2" style="width:348px; text-align: center;">Empleado</td>
                    </tr>
                </table>



                <!--<table>
                    <tr>
                        <td style="width:364px; text-align: center;"><b>GUARDIA DE:</b></td>
                        <td style="width:364px; text-align: center;"><b>CAMBIA CON:</b></td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td style="width:50px;">Nombre:</td>
                        <td style="width:298px;" class="celda"></td>
                        <td style="width:24px;"></td>
                        <td style="width:50px;">Nombre:</td>
                        <td style="width:298px;" class="celda"></td>
                    </tr>
                    <tr>
                        <td style="width:50px;">Puesto:</td>
                        <td style="width:298px;" class="celda"></td>
                        <td style="width:24px;"></td>
                        <td style="width:50px;">Puesto:</td>
                        <td style="width:298px;" class="celda"></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:110px;">Fecha de ingreso:</td>
                        <td style="width:238px;" class="celda"></td>
                        <td style="width:24px;"></td>
                        <td style="width:110px;">Fecha de ingreso:</td>
                        <td style="width:238px;" class="celda"></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:95px;">Día de guardia:</td>
                        <td style="width:253px;" class="celda"></td>
                        <td style="width:24px;"></td>
                        <td style="width:95px;">Cambio al día:</td>
                        <td style="width:253px;" class="celda"></td>
                    </tr>
                </table>-->
             </div>
             <div class="sextaSeccion">
                <div class="tituloSeccion">EXCLUSIVO PARA AUTORIZACIÓN</div>
                <br>
                <br>
                <table>
                    <tr>
                        <td style="width:300px;">Con goce de sueldo</td>
                        <td style="width:100px; text-align: center;">Si</td>
                        <td style="width:70px; text-align: left;">'.$sueldoSi.'</td>
                        <td style="width:100px; text-align: center;">No</td>
                        <td style="width:70px; text-align: left;">'.$sueldoNo.'</td>
                    </tr>
                    <tr>
                        <td style="width:300px;">&nbsp;</td>
                        <td style="width:100px; text-align: center;"></td>
                        <td style="width:70px; text-align: left;"></td>
                        <td style="width:100px; text-align: center;"></td>
                        <td style="width:70px; text-align: left;"></td>
                    </tr>
                    <tr>
                        <td style="width:300px;">Presenta justificante</td>
                        <td style="width:100px; text-align: center;">Si</td>
                        <td style="width:70px; text-align: left;">'.$justificanteSi.'</td>
                        <td style="width:100px; text-align: center;">No</td>
                        <td style="width:70px; text-align: left;">'.$justificanteNo.'</td>
                    </tr>
                </table>
    
             </div>
             <div class="septimaSeccion">
                <div class="tituloSeccion">AUTORIZACIÓN</div>
                <br>
                <table>
                    <tr>
                        <td style="width:6px;"></td>
                        <td style="width:352px; text-align: center;" class="textoCampos">'.$usuario['nombre'].' '.$usuario['paterno'].' '.$usuario['materno'].'</td>
                        <td style="width:10px;"></td>
                        <td style="width:352px; text-align: center;" class="textoCampos">'.$jefe['nombre'].' '.$jefe['paterno'].' '.$jefe['materno'].'</td>
                        <td style="width:6px;"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center;" class="celda textoValidacion">'.Llaves::firma($permiso['id_usuario']).'</td>
                        <td></td>
                        <td style="text-align: center;" class="celda textoValidacion">'.Llaves::firma($jefe['id_usuario']).'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center;">Empleado</td>
                        <td></td>
                        <td style="text-align: center;">Jefe Inmediato</td> 
                        <td><br><br></td>
                    </tr>
                    <tr>
                        <td style="width:6px;"></td>
                        <td style="width:352px; text-align: center;" class="textoCampos">NOMBRE GERENTE</td>
                        <td style="width:10px;"></td>
                        <td style="width:352px; text-align: center;" class="textoCampos">'.$rh['nombre'].' '.$rh['paterno'].' '.$rh['materno'].'</td>
                        <td style="width:6px;"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center;" class="celda textoValidacion"></td>
                        <td></td>
                        <td style="text-align: center;" class="celda textoValidacion">'.Llaves::firma($rh['id_usuario']).'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center;">Gerente Administrativo</td>
                        <td></td>
                        <td style="text-align: center;">Recursos Humanos</td> 
                        <td><br><br></td>
                    </tr>
                    
                </table>
             </div>
        </div>
       
    </body>
    </html>'
);
$thml2pdf->output('solicitud.pdf');