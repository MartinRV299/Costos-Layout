<!DOCTYPE html>
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
                .espacioHorizontal{
                    line-height: 20px;
                }

       
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="primeraSeccion">
            <table class="border">
                <tr>
                    <td style="width:182px; height:38px; text-align: center; background: url(imagenes/logo.png);"></td>
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
                    <td style="width:200px;" class="celda"></td>
                </tr>
                <!--<tr>
                    <td style="width:52px;"></td>
                    <td style="width:400px;"></td>
                    <td style="width:75px;"></td>
                    <td style="width:200px;">&nbsp;</td>
                </tr>-->
                <tr>
                    <td style="width:52px;">Nombre:</td>
                    <td style="width:400px;" class="celda"></td>
                    <td style="width:75px; text-align: right;">Sucursal:</td>
                    <td style="width:200px;" class="celda"></td>
                </tr>
            </table>
           
            <table class="border2">
                <tr>
                    <td style="width:52px;">Puesto:</td>
                    <td style="width:350px;" class="celda"></td>
                    <td style="width:125px; text-align: right;">Fecha de ingreso:</td>
                    <td style="width:200px;" class="celda"></td>
                </tr>
            </table>
        </div>
        <div class="segundaSeccion">
            <br>
            <table>
                <tr>
                    <td style="width:142px;">Solicitud de:</td>
                    <td style="width:95px;">A) Permiso</td>
                    <td style="width:80px;"><img src="imagenes/empty.png" alt=""></td>
                    <td style="width:100px;">B) Vacaciones</td>
                    <td style="width:80px;"><img src="imagenes/empty.png" alt=""></td>
                    <td style="width:160px;">C) Cambio de guardia</td>
                    <td style="width:60px;"><img src="imagenes/check.png" alt=""></td>
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
                    <td style="width:50px;"><img src="imagenes/empty.png" alt=""></td>
                </tr>

                 <tr>
                    <td>01 - Justificante de IMSS</td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                    <td></td>
                    <td><b>FI - Falta injustificada</b></td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                </tr>

                 <tr>
                    <td>02 - Justificante de médico particular</td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                    <td></td>
                    <td><b>SU - Suspensión</b></td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                </tr>

                <tr>
                    <td><b>PE - Permiso</b></td>
                    <td></td>
                    <td></td>
                    <td><b>MA - Maternidad</b></td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                </tr>

                 <tr>
                    <td>01 - Día completo</td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                    <td></td>
                    <td><b>PA - Paternidad</b></td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                </tr>

                 <tr>
                    <td>02 - Medio día</td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>03 - Periode de ausencia por horas</td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>04 - Salida temprano</td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>05 - Bono bimestral</td>
                    <td><img src="imagenes/empty.png" alt=""></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width:100px;">Motivo:</td>
                    <td style="width:630px;" class="celda"></td>
                </tr>
            </table>
        
            <table>
                <tr>
                    <td style="width:114px;">Fecha a otorgarse:</td>
                    <td style="width:200px;" class="celda"></td>
                    <td style="width:180px; text-align: right;">Horario de ausencia:</td>
                    <td style="width:230px;" class="celda"></td>
                </tr>
            </table>
         </div>
         <div class="cuartaSeccion">
            <div class="tituloSeccion">B) VACACIONES</div>
            <br>
            <table>
                <tr>
                    <td style="width:70px;">A disfrutar:</td>
                    <td style="width:180px;" class="celda"></td>
                    <td style="width:200px; text-align: right;">A partir del día</td>
                    <td style="width:122px;"class="celda"></td>
                    <td style="width:21px; text-align: center;">al</td>
                    <td style="width:122px;" class="celda"></td>
                </tr>
                <tr>
                    <td style="width:70px;">Disfrutados:</td>
                    <td style="width:180px;" class="celda"></td>
                    <td style="width:200px; text-align: right;"></td>
                    <td style="width:122px;"></td>
                    <td style="width:21px; text-align: center;"></td>
                    <td style="width:122px;"></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width:70px;">Disponibles:</td>
                    <td style="width:180px;" class="celda"></td>
                    <td style="width:200px; text-align: right;">Reincorporación:</td>
                    <td style="width:269px;"class="celda"></td>
                </tr>
            </table>
         </div>
         <div class="quintaSeccion">
            <div class="tituloSeccion">C) CAMBIO DE GUARDIA</div>
            <br>
            <table>
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
            </table>
         </div>
         <div class="sextaSeccion">
            <div class="tituloSeccion">EXCLUSIVO PARA AUTORIZACIÓN</div>
            <br>
            <br>
            <table>
                <tr>
                    <td style="width:300px;">Con goce de sueldo</td>
                    <td style="width:100px; text-align: center;">Si</td>
                    <td style="width:70px; text-align: left;"><img src="imagenes/empty.png" alt=""></td>
                    <td style="width:100px; text-align: center;">No</td>
                    <td style="width:70px; text-align: left;"><img src="imagenes/empty.png" alt=""></td>
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
                    <td style="width:70px; text-align: left;"><img src="imagenes/empty.png" alt=""></td>
                    <td style="width:100px; text-align: center;">No</td>
                    <td style="width:70px; text-align: left;"><img src="imagenes/empty.png" alt=""></td>
                </tr>
            </table>

         </div>
         <div class="septimaSeccion">
            <div class="tituloSeccion">AUTORIZACIÓN</div>
            <br>
            <table>
                <tr>
                    <td style="width:6px;"></td>
                    <td style="width:352px; text-align: center;">Uriel Alejandro Rosales González</td>
                    <td style="width:10px;"></td>
                    <td style="width:352px; text-align: center;">Uriel Alejandro Rosales González</td>
                    <td style="width:6px;"></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;" class="celda textoValidacion">U45454545455549459454545</td>
                    <td></td>
                    <td style="text-align: center;" class="celda textoValidacion">54545887475454jk54jk4hj54</td>
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
                    <td style="width:352px; text-align: center;">Uriel Alejandro Rosales González</td>
                    <td style="width:10px;"></td>
                    <td style="width:352px; text-align: center;">Uriel Alejandro Rosales González</td>
                    <td style="width:6px;"></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;" class="celda textoValidacion">U45454545455549459454545</td>
                    <td></td>
                    <td style="text-align: center;" class="celda textoValidacion">54545887475454jk54jk4hj54</td>
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
</html>