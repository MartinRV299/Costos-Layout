<?php 

    $tipo = $_SERVER['REQUEST_URI'];
    $nominas='';
    $finanzas='';
    $tesoreria = '';
    $liberacion='';
    $titulo='';

    $liberado = '';
    $pago = '';
    $autorizacion = '';

    if($tipo === '/asesores/nominas'){
        $nominas='active';
        $titulo = 'NÓMINAS';
        $autorizacion = 0;
    }
    else if($tipo === '/asesores/finanzas'){
        $finanzas='active';
        $titulo= 'FINANZAS';
        $liberado = 1;
    }
    else  if($tipo === '/asesores/tesoreria'){
        $finanzas='active';
        $tesoreria = 'active';
        $titulo='TESORERIA';
        $liberado = 2;
        $pago = 1;
    }
    else  if($tipo === '/asesores/liberacion'){
        $finanzas='active';
        $liberacion='active';
        $titulo='TABLA DE LIBERACIÓN';
    }

    $nominista = GrupoNominas::pertenece2($_SESSION['identificador']) ? $_SESSION['identificador'] : '';
       
    $data = array(  'cliente'=>'',
                    'facturado'=>'',
                    'liberado'=>$liberado,
                    'pago'=>$pago,
                    'nomina'=>'',
                    'nominista'=>$nominista,
                    'autorizacion'=>$autorizacion
    ); 

    $paginacion = new Paginacion(30);
    $paginacion->target('nominas');
    $paginacion->parametroCliente('');
    $paginacion->parametroFacturado('');
    $paginacion->parametroLiberado($data['liberado']);
    $paginacion->parametroPago($data['pago']);
    $paginacion->parametroNomina('');
    $paginacion->parametroNominista($data['nominista']);
    $paginacion->parametrosAutorizacion($data['autorizacion']);
    $totalRegistros=Nominas::contarRegistros($data,$tipo);
    $paginacion->totalPaginas($totalRegistros);
    //$paginacion->totalPaginas(Nominas::contarRegistros($totalRegistros));
?>
  <!-- =============================================== -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content" id="contenedorPrincipalNominas">  <div role="tabpanel" class="tab-pane" id="importar"> 
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-dollar icono-encabezado"></i> MÓDULO DE <?php echo $titulo; ?></h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
            <div role="tabpanel"> 
                <ul class="nav nav-tabs">
                    <?php if($nominas === 'active'):?>
                        <li role="presentation" class="<?php echo $nominas; ?>">
                            <a href="#agregar" aria-controls="agregar" role="tab" data-toggle="tab">Capturar</a>
                        </li>
                    <?php endif; ?>
                    
                    <li role="presentation" class="<?php echo $finanzas; ?>">
                        <a href="#consultar" aria-controls="consultar" role="tab" data-toggle="tab">Consultar</a>
                    </li>

                    <?php if($liberacion !== 'active'): ?>
                        <li role="presentation">
                            <a href="#importar" aria-controls="importar" role="tab" data-toggle="tab">Cargar-Descargar archivos</a>
                        </li>
                    <?php endif; ?>


                    <?php if($_SESSION['identificador2'] == 6 || $_SESSION['identificador'] == 201): ?>

                        <?php if($liberacion === 'active'): ?>
                        <li role="presentation">
                            <a href="#importar2" aria-controls="importar2" role="tab" data-toggle="tab">Cargar-Descargar archivos</a>
                        </li>
                        <?php endif; ?>

                        <li role="presentation">
                            <a href="#autorizacion" aria-controls="autorizacion" role="tab" data-toggle="tab">Personal con autorización</a>
                        </li>
                    <?php endif; ?>

                </ul>

                <div class="tab-content" style="margin-top: 2%;">

                    <?php if($nominas === 'active'):?>
                    <div role="tabpanel" class="tab-pane <?php echo $nominas; ?>" id="agregar"> 
                    
                        <div class="callout callout-gray">
                            <!--<p class="estilos-izquierda"> <i class="fa fa-certificate fa-2x" style="color:#DBA901;"></i> <b>Campo nuevo:</b> 61.-Descuento ayudate</p>
                            <p class="estilos-izquierda"> <i class="fa fa-certificate fa-2x" style="color:#DBA901;"></i> <b>Calcular retención IVA:</b> Da click en el recuadro naranja que se encuentra sobre el campo y se calculará automáticamente; en caso de que no se actualice ninguno de los campos calculados, borra el último número del subtotal y escribelo de nuevo para recalcular los campos. </p>-->
                            <p class="estilos-izquierda"> <i class="fa fa-check-circle fa-2x text-green"></i> Campos obligatorios.</p>
                            <p class="estilos-izquierda"> <i class="fa fa-square fa-2x" style="color:#0e7c9b;"></i> Da click sobre el recuadro azul para obtener información del campo.</p>
                            <p class="estilos-izquierda"> <i class="fa fa-exclamation-circle fa-2x" style="color:#FF4500;"></i> Te pedimos de la manera más atenta que <b><u>cualquier tipo de soporte</u></b> relacionado con este módulo lo solicites mediante el <b style="text-size:16px;">SISTEMA DE TICKETS</b>, esto con la finalidad de llevar un control y atenderte de una manera rápida.</p>
                           
                        </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="">Tipo de esquema: (esta opción indicará cuales campos son de captura obligatoria)</label>
                                        <i class="fa fa-check-circle text-green"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay textAreaImportante" id="tipoEsquema">
                                                <option value=""></option>
                                                <option value="1">ASIMILADOS</option>
                                                <option value="2">MIXTO</option>
                                                <option value="3">SINDICATOS</option>
                                                <option value="4">SUELDOS Y SALARIOS</option>
                                                <option value="5">TARJETA EMPRESARIAL</option>
                                                <option value="6">PRESTAMO</option>
                                                  <!--if(GrupoCapturaConfidenciales::pertenece($_SESSION['identificador']) || Configuraciones::administrador() == $_SESSION['identificador2'] ):-->
                                                    <!--<option value="7">CONFIDENCIAL</option>-->
                                                <option value="7">GASTOS MÉDICOS</option>
                                                <option value="8">PAGADA CON OBSERVACIÓN</option>
                                            </select>
                                        </div> 
                                </div>   
                            </div>
                        <div id="cargarNumeroNomina"></div>
                        <form method="POST" id="formularioNominas">
                            <div style="border:2px dotted gray;">
                                <h3 style="text-align:center;"><u>TABLA DE LIBERACIÓN</u></h3>

                                <div id="tipoSindicato"></div>
                               
                                <div class="row form-group rowColorGray">
                                    <div class="col-md-4">
                                        <label for="devengada" style="cursor:pointer;">La nómina es devengada:</label>
                                        <br>
                                        <label class="switch">
                                            <input type="checkbox" id="devengada" value="1">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">No. Factura:</label>
                                        <i class="fa fa-check-circle text-green" id="styleFacturaDevengada" style="display:none"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Número de factura">
                                            <i class="fa fa-hashtag"></i>
                                            </div>
                                            <input class="form-control" type="text" name="devengadaFactura" id="facturaDevengada" disabled>
                                        </div>     
                                    </div>
                                </div>
                    
                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-12">
                                        <label for="">1.-Nombre del cliente:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono mixto-icono sys-icono tarjeta-icono sindicato-icono prestamo-icono especial-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Nombre de la razon social a la cual se factura">
                                                <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput asimilados-validacion mixto-validacion sys-validacion tarjeta-validacion sindicato-validacion prestamo-validacion especial-validacion" name="nominasCliente" id="clienteActivo">
                                                <option></option>
                                                <?php echo Nominas::mostrarSelect($datos["id_cliente"],Tablas::clientes()); ?>
                                            </select>
                                        </div>   
                                    </div>
                                </div>

                                <div class="row form-group rowColorGray">
                                    <div class="col-md-4">
                                        <label for="">2.-Tipo de pago:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono mixto-icono sys-icono tarjeta-icono sindicato-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Se especifica si es un Mov. Especial, Finiquito, nomina, etc">
                                            <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput asimilados-validacion mixto-validacion sys-validacion tarjeta-validacion sindicato-validacion" name="nominasTipoPago" >
                                                <option value=""></option>
                                                <option value="1">AGUINALDO</option>
                                                <option value="2">ASESORES</option>
                                                <option value="3">BONO</option>
                                                <option value="4">CARGA SOCIAL</option>
                                                <option value="5">COMPLEMENTO</option>
                                                <option value="6">ESPECIAL</option>
                                                <option value="7">EXCEDENTE IMSS</option>
                                                <option value="8">FINIQUITO</option>
                                                <option value="9">FONACOT</option>
                                                <option value="10">GASTOS MÉDICOS MAYORES</option>
                                                <option value="11">INFONAVIT</option>
                                                <option value="12">ISN</option>
                                                <option value="13">NÓMINA</option>
                                                <option value="14">PAGO PROVEEDOR</option>
                                                <option value="15">PRENÓMINA IMSS</option>
                                                <option value="16">PRIMA VACACIONAL</option>
                                                <option value="17">SEGURO DE VIDA</option>
                                                <option value="18">TARJETA EMPRESARIAL</option>
                                                <option value="19">OTROS</option>
                                            </select>
                                        </div>                                 
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">3.-Régimen:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono mixto-icono sys-icono tarjeta-icono sindicato-icono prestamo-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Tipo de esquema para pago">
                                            <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInputo asimilados-validacion mixto-validacion sys-validacion tarjeta-validacion sindicato-validacion prestamo-validacion" name="nominasRegimen" >
                                                <option></option>
                                                <option value="1">ASIMILADOS</option>
                                                <option value="2">ESPECIAL</option>
                                                <option value="3">MIXTO</option>
                                                <option value="4">SUELDOS Y SALARIOS</option>
                                            </select>
                                        </div>                                
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">4.-Comisión:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono mixto-icono sys-icono tarjeta-icono sindicato-icono sin-factura-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Monto que representa la comisión cobrada">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario asimilados-validacion mixto-validacion sys-validacion tarjeta-validacion sindicato-validacion sin-factura" type="text" name="nominasComision" id="nominasComision" required>
                                        </div>                              
                                    </div>
                                </div>

                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-12">
                                        <label for="">5.-Empresa que factura:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono mixto-icono sys-icono tarjeta-icono sindicato-icono prestamo-icono especial-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Nombre de la  empresa que emite la factura">
                                            <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput asimilados-validacion mixto-validacion sys-validacion tarjeta-validacion sindicato-validacion prestamo-validacion especial-validacion" name="nominasEmpresaFactura" >
                                                <option></option>
                                                <?php echo Nominas::mostrarSelect($datos["id_empresa_factura"],Tablas::facturadoras()); ?>
                                            </select>
                                        </div>                                
                                    </div>
                                </div>

                                <div class="row form-group rowColorGray">
                                    <div class="col-md-4">
                                        <label for="">6.-Subtotal:</label>
                                        <i class="fa fa-check-circle text-green sin-factura-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe Total antes de iva">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput sin-factura" type="text" name="nominasSubtotal" id="nominasSubtotal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="margin-bottom:-7px;" for="">7.-Retención IVA: <i class="fa fa-check-circle text-green campoRetencionObligatorio" style="display:none;"></i> <label class="container_">(Calcular) <input type="checkbox" id="calcularRetencionIva"><span class="checkmark_"></span></label></label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                                <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="retencionIva" id="retencionIva" readonly>
                                        </div>                                
                                    </div>
                                   
                                    <div class="col-md-4">
                                        <label for="">7-A.-Retención ISN:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput sin-factura" type="text" name="retencionIsn" id="retencionIsn" readonly>
                                        </div>
                                    </div>
                                 
                                </div>

                            
                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-6">
                                        <label for="">8.-IVA:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Iva">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario" type="text" id="nominasIva" name="nominasIva" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">9.-Total:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe Total despues de iva">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario" type="text" id="nominasTotal" name="nominasTotal" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group rowColorGray">
                                    <div class="col-md-8">
                                        <label for="">10.-Empresa pagadora IMSS:</label>
                                        <i class="fa fa-check-circle text-green sys-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Empresa que pagara los  salarios y /o sueldos  (normalmente es donde estan vigente los trabajajores a los cuales se generaran los pagos)">
                                            <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput sys-validacion" name="nominasEmpresaImss" >
                                                <option></option>
                                                <?php echo Nominas::mostrarSelect($datos["id_empresa_pagadora_imss"],Tablas::imss()); ?>
                                            </select>
                                        </div>                             
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">11.-Total a depositarle IMSS:</label>
                                        <i class="fa fa-check-circle text-green sys-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe Neto a Pagar de Sueldos y Salarios">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput sys-validacion" type="text" name="nominasTotalImss" >
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-8">
                                        <label for="">12.-Empresa pagadora asimilados:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono"></i><div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Empresa que pagara el Asimilados y/o Anticipo a Rendimiento">
                                            <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput asimilados-validacion" name="nominasEmpresaAsimilados" >
                                                <option></option>
                                                <?php echo Nominas::mostrarSelect($datos["id_empresa_pagadora_asimilados"],Tablas::asimilados()); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">13.-Total a depositarle por asimilados:</label>
                                        <i class="fa fa-check-circle text-green iluminarIconoInput asimilados-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe Neto a Pagar de Asimilados">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario asimilados-validacion" type="text" name="nominasTotalAsimilados" >
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group rowColorGray">
                                    <div class="col-md-4">
                                        <label for="">14.-Tipo de periodo:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono mixto-icono sys-icono sindicato-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Se indica la perioricidad del pago (Quincenal, Semanal, Diario, etc)">
                                            <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput asimilados-validacion mixto-validacion sys-validacion sindicato-validacion" name="nominasPeriodo" >
                                                <option></option>
                                                <option value="1">CATORCENAL</option>
                                                <option value="2">DIARIO</option>
                                                <option value="3">MENSUAL</option>
                                                <option value="4">QUINCENAL</option>
                                                <option value="5">QUINCENAL COMBINADO</option>
                                                <option value="6">SEMANAL</option>
                                                <option value="7">SEMANAL COMBINADO</option>
                                                <option value="8">AGUINALDO</option> 
                                            </select>
                                        </div>                                
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">15.-Número de periodo:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono mixto-icono sys-icono sindicato-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="De acuerdo a la periodicidad se indica en número de acuerdo a la fecha de pago">
                                                <i class="fa fa-hashtag"></i>
                                            </div>
                                            <input class="form-control iluminarIconoInput asimilados-validacion mixto-validacion sys-validacion sindicato-validacion" type="number" name="nominasNumeroPeriodo" min="1">
                                        </div>                                
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">16.-Socios:</label>
                                        <i class="fa fa-check-circle text-green asimilados-icono mixto-icono sys-icono tarjeta-icono sindicato-icono"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Número de personas a las que se paga en dicha nomina">
                                            <i class="fa fa-hashtag"></i>
                                            </div>
                                            <input class="form-control iluminarIconoInput asimilados-validacion mixto-validacion sys-validacion tarjeta-validacion sindicato-validacion" type="number" name="nominasSocios" min="0" >
                                        </div>                              
                                    </div>
                                </div>

                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-4">
                                        <label for="">17.-Descuentos sueldos y salarios:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="se suman los campos: 24 + 42">
                                                <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control iluminarIconoInput" type="text" name="nominasDescuentosSys" readonly>
                                        </div>                                
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">18.-Descuentos asesores:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="se suman los campos: 48 + 49 + 50 + 51 + 52 + 53 + 56 + 57 +58 + 59 + 60">
                                                <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control iluminarIconoInput" type="text" name="nominasDescuentosAsesores" readonly>
                                        </div>                                
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">19.-Descuentos terceros:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="se suman los campos: 54 + 55 + 62">
                                                <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control iluminarIconoInput" type="text" name="nominasDescuentosTerceros" readonly>
                                        </div>                              
                                    </div>
                                </div>

                                
                            </div>
                            <br>
                            <div style="border:2px dotted gray;">
                                <h3 style="text-align:center;"><u>SUELDOS Y SALARIOS</u></h3>
                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-3">
                                        <label for="">20.-Ingreso:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe Neto a Pagar de Sueldos y Salarios">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasIngreso">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">21.-INFONAVIT:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de Amortización y Seguro de Daños de Vivienda del crédito">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasInfonavit">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">22.-FONACOT:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de Amortizacion de este credito">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasFonacot">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">23.-Donativo:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasDonativo">
                                        </div>                              
                                    </div>
                                </div>

                                <div class="row form-group rowColorGray">
                                    <div class="col-md-3">
                                        <label for="">24.-Pensión alimenticia:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Descuento por concepto de pension alimenticia">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasPensionAlimenticia">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">25.-Excedente de cargas:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Descuento por cargas obrero patronal a recuperar">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasExcedenteCargas">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">26.-Carga patronal:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de obligaciones de imss que se recupera con la comision cobrada al cliente">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasCargaPatronal">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">27.-Impuesto estatal: <i class="fa fa-check-circle text-green campoRetencionObligatorio" style="display:none;"></i> <label class="container_"><input type="checkbox" id="checkCalcularIsn"><span class="checkmark_"></span></label></label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Impuesto generado en Raya como impuesto estatal (generalmente es del 2%)">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasIsn" id="impuestoEstatal">
                                        </div>                              
                                    </div>
                                </div>

                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-3">
                                        <label for="">28.-Comisión(monto):</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de comision cobrada dentro de los ingresos de sueldos y salarios">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasComisionMonto" id="nominasComisionMonto">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">29.-IMSS obrera:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Cuota de imms que se genera en raya y que se retiene al trabajador">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasImssObrera">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">30.-Carga social IMSS:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de obligaciones de imss que se facturan directamente al cliente">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasCargaSocialImss">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">31.-Prenómina IMSS:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasPrenominaImss">
                                        </div>                              
                                    </div>
                                </div>

                                <div class="row form-group rowColorGray">
                                    <div class="col-md-3">
                                        <label for="">32.-ISR/ISP(SP):</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Impuesto de isr que se descuenta en raya al trabajador">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasIsrIsp">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">33.-ISR art. 142:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Impuesto isr del aguinaldo (se genera cuando el aguinaldo rebasa el tope de ley)">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasIsr142">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">34.-Cuota sindical:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="importe descontado a personal sindicalizado en nominas 100% Out.">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasCuotaSindical">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">35.-Despensa:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de pago de despensa ">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasDespensa">
                                        </div>                              
                                    </div>
                                    
                                </div>

                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-3">
                                        <label for="">36.-Caja de ahorro:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe que se descuenta a solicitud del cliente concepto de Caja y Prestamo caja de Ahorro">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasCajaAhorro">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">37.-Descuento generales:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasDescuentoImss">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">38.-Apoyo sindical:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe descontado a personal sindicalizado en nominas 100% Out.">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasApoyoSindical">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">39.-Descuentos comedor:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe descontado a personal  en nominas 100% Out por uso de comedor de empleados">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasDescuentoComedor">
                                        </div>                              
                                    </div>
                                </div>



                                <div class="row form-group rowColorGray">
                                    <div class="col-md-3">
                                        <label for="">40.-Haberes:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasHaberes">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">41.-Subsidio (SP):</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteSubsidio">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">42.-Prestamos empleado:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasPrestamosEmpleados">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">43.-Prestamos ayudate:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasPrestamosAyudate">
                                        </div>                              
                                    </div>
                                </div>

                                 <div class="row form-group rowColorWhite">
                                    <div class="col-md-3">
                                        <label for="">44.-ajuste subsidio empleo:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="ajusteSubsidioEmpleo">
                                        </div>                              
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">45.-Otros:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Descuentos generales por varios conceptos">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="input" name="nominasOtros">
                                        </div>                              
                                    </div>
                                </div>


                            </div>
                
                            <br>
                            <div style="border:2px dotted gray;">
                                <h3 style="text-align:center;"><u>EXCEDENTE</u></h3>
                                <div class="row form-group rowColorGray">
                                    <div class="col-md-3">
                                        <label for="">46.-Ingreso:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe Neto a Pagar de Asimilados">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteIngreso">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">47.-Ingresos sin timbrar:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe Neto a pagar cuando no correponda un asimilados (es decir cuando se genera un pago por empresas de asimilados pero se recuperan a traves de un representante legal ) y no se genera isr ni se timbra este importe">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteTerceros">
                                        </div>
                                    </div>
                                   

                                </div>

                                <h3 style="text-align:center;"><u>DESCUENTOS AL TRABAJADOR</u></h3>

                                <div class="row form-group rowColorWhite">
                                    <div class="col-md-3">
                                        <label for="">48.-ISR:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Impuesto de isr que se descuenta en raya al trabajador">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteIsr">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">49.-IMSS:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Descuento por concepto de imss">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteImss">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">50.-GMM:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe aplicado por descuentos por polizas de Gastos Medicos y/o Vida">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteGmm">
                                        </div>
                                    </div>
                                     <div class="col-md-3">
                                        <label for="">51.-INFONAVIT:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de Infonavit descontado al trabajador (porque no se cubrio en  imss)">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteInfonavit">
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group rowColorGray">
                                    <div class="col-md-3">
                                        <label for="">52.-FONACOT:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de Fonacot descontado al trabajador (porque no se cubrio en  imss)">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteFonacot">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">53.-Prestamos:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importes de descuento por concepto de prestamos personales">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedentePrestamos">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">54.-Pensión alimenticia:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de descuento en importe o porcentaje segun lo indique el oficio correspondiente">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedentePensionAlimencia">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">55.-Cliente:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteClientes">
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group rowColorWhite">
                                   
                                    <div class="col-md-3">
                                        <label for="">56.-Recuperación:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Descuentos por pagos de mas o erroneos">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteRecuperacion">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="">57.-Comisión cobrada al socio:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Importe de comision que se descuenta del pago del empleado">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteComisionSocio">
                                        </div>
                                    </div>

                                     <div class="col-md-3">
                                        <label for="">58.-Prenómina IMSS:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedentePrenominaImss">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">59.-Prenómina GMM:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedentePrenominaGmm">
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group rowColorGray">
                                    <div class="col-md-3">
                                        <label for="">60.-Caja de ahorro:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteCajaAhorro">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">61.-Descuento ayudate:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="descuentoAyudate">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">62.-Otros:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon info" title="Información del campo" info="Descuentos generales por varios conceptos">
                                            <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" name="nominasExcedenteOtros">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>63.-Comentarios: </b></span>
                                    <textarea name="nominasComentarios" class="form-control textAreaImportante iluminarIconoInput" rows="8" style="resize:vertical;" placeholder="..."></textarea>
                                </div>
                            </div>

     
                            
                                <div class="row">
                                    <div class="col-md-12" >
                                        <h3>Comprobantes bancarios del cliente</h3>
                                        <div class="alert alert-secondary" role="alert" style="margin-top:10px;">
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Documentos validos: Pdf
                                        <br>
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Tamaño máximo por documento: <b>25 MB</b> y la suma de todos los archivos no puede superar los <b>50 MB</b>
                                        </div>
                                    </div>
                                </div>
                               
                               <p>Total de archivos adjuntos: <b><span id="totalAdjuntos" style="font-size:20px;">0</span></b>, Tamaño total de los archivos adjuntos: <b><span id="totalAdjuntosPeso" style="font-size:20px;">0 MB</span></b></p>
                               
                                <div><ol id="documentosNominas" class="alert alert-info loadDocuments"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets"><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>

                           
                           
                            <hr>
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <input type="file" id="archivosNominas" multiple>
                                    <button type="submit" class="btn btn-success" id="botonGuardarNominaPrimaria"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                    <button type="button" id="formularioCancelarNominas" class="btn btn-danger"><i class="fa fa-ban fa-lg"></i> Cancelar</button> 
                                </div>
                            </div>
                        </form>      
                    </div>
                    <?php endif; ?>

                    <div role="tabpanel" class="tab-pane" id="importar"> 

                        <?php //if($liberacion === 'active'): ?>
                          <!--  <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> <i class="fa fa-download fa-3x" style="color:#00A65A"></i> </h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                        <i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <form method="post">  
                                        <div class="max1000">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="">Selecciona el rango de fechas (en que el registro fue guardado por el nóminista) para descargar el reporte total de las nóminas existentes en el sistema:</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                            
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="">1.-Fecha inicio:</label>
                                                                <i class="fa fa-check-circle text-green"></i>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                    <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaInicio" required>
                                                                </div>      
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="">2.-Fecha final:</label>
                                                                <i class="fa fa-check-circle text-green"></i>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                    <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaFinal" required>
                                                                </div>     
                                                            </div>
                                                            <div class="col-md-2">
                                                            
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 estilos-centrar">
                                                        <button type="submit" name="reporteNominasFinal" value="" class="btn btn-success btn-lg"><i class="fa fa-download"></i> Descargar</button> 
                                                    </div>
                                                </div>
                                        </div>    
                                    </form> 
                        
                                </div>
                            </div> -->
                        <?php if($tesoreria === 'active'): ?>

                        <div class="box box-success collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-download fa-3x" style="color:#00A65A"></i> Descargar reportes</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                    <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <form method="post">  
                                    <div class="max1000">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Descarga la relación de tus nóminas y las de tu personal a cargo:</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">1.-Fecha de inicio:</label>
                                                        <i class="fa fa-check-circle text-green"></i>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaInicio" required>
                                                        </div>      
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">2.-Fecha de fin:</label>
                                                        <i class="fa fa-check-circle text-green"></i>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaFinal" required>
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                </div>
                                            </div>

                                            
                                             <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">3.-Capturó:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                            </div>
                                                            <select class="form-control textoMay iluminarIconoInput" name="nominista" >
                                                                <option value="<?php echo $_SESSION['identificador'] ?>"><?php echo Nominas::getNominista(); ?></option>
                                                                <?php echo Nominas::verificarJefatura(); ?>
                                                            </select>
                                                        </div>      
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">4.-Estatus de la nómina:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-list-ol"></i>
                                                            </div>
                                                            <select class="form-control textoMay iluminarIconoInput" name="statusNominas" >
                                                                <option value="1">PAGADA</option>
                                                                <option value="3">PAGADA CON DEVOLUCIÓN</option>
                                                                <option value="4">PAGADA CON OBSERVACIÓN</option>
                                                                <option value="6">PENDIENTE</option>
                                                                <option value="5">TODOS</option>
                                                            </select>
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                          
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 estilos-centrar">
                                                    <button type="submit" name="reporteTesoreriaSucursal" value="" class="btn btn-success btn-lg"><i class="fa fa-download"></i> Descargar</button> 
                                                </div>
                                            </div>
                                    </div>    
                                </form> 
                    
                            </div>
                        </div>


                  
                        <div class="box box-info collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#00C0EF"></i> Cargar nóminas</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                    <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                               
                                    <div class="max1000">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Cargar registros:</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                               <form method="post" enctype="multipart/form-data" id="formularioCargarNominas"> 
                                                    <div class="col-md-12 estilos-centrar">
                                                        <span class="btn btn-info btn-lg btn-file" style="width:139px;"><i class="fa fa-upload"></i> Cargar<input type="file" name="cargarRegistrosNominas" id="cargarRegistrosNominas" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"></span>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="row">
                                                <form method="post">  
                                                    <div class="col-md-12 estilos-centrar">
                                                        <p>
                                                            <hr>
                                                            <i class="fa fa-exclamation-circle fa-2x text-yellow"></i>
                                                            <b>Descargar archivo para llenado de datos</b>
                                                            <button type="submit" name="formatoLlenadoTesoreria001" value="" class="btn btn-success"><i class="fa fa-download"></i> Tesoreria</button>
                                                        </p>
                                                    </div>
                                                </form> 
                                            </div>
                                    </div>    
                             
                            </div>

                        </div>
                 

                       
                                <div class="box box-danger collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#dd4b39"></i> Cargar comprobantes bancarios del cliente</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                            <i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <form method="post">  
                                            <div class="max1000">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p>Si ya tienes nóminas capturadas y quieres cargar archivos adjuntos (<b>COMPROBANTES BANCARIOS DEL CLIENTE</b> en formato pdf) puedes hacerlo desde aquí, <b>no importa si la nómina ya tiene archivos adjuntos previamente o no</b>.</p>
                                                                <p>Para que el proceso funcione correctamente deberas subir una única carpeta (no importa el nombre), dentro de ella deben venir tantas subcarpetas como nóminas a las que quieras adjuntar los archivos, estas <b>subcarpetas deben tener el número de nómina</b>, y cada una de ellas a su vez contendra los archivos que quieras cargar, no importa el nombre de los archivos,</b> pero sólo se consideraran los <b>archivos con extensión .pdf</b>.</p>
                                                                <p>Ejemplo: si quiero subir archivos adjuntos a las nóminas 3000,3001 y 3050 la estructura deberá quedar de la siguiente manera:</p> 

                                                                <p><b>Nota:</b> Máximo <b>500</b> archivos por cada carga.</p>
                                                                <img src="<?php echo Ruta::ruta_server();?>views/img/cargar-archivos2.jpg" class="img-responsive center-block" style="border-radius:10px;max-width:600px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12 estilos-centrar">
                                                            <span class="btn btn-danger btn-lg btn-file"><i class="fa fa-upload"></i> Cargar<input type="file" id="cargarArchivosAdjuntosMasivosTesoreria" class="btn btn-warning btn-lg" webkitdirectory multiple></span> 
                                                        </div>
                                                    </div>
                                            </div>    
                                        </form> 
                            
                                    </div>
                                </div>
                           

                       
                        <?php elseif($nominas === 'active'): ?>

                        <div class="box box-success collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-download fa-3x" style="color:#00A65A"></i> Descargar reportes</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                    <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <form method="post">  
                                    <div class="max1000">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Descarga la relación de tus nóminas registradas y las de tu personal a cargo:</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">1.-Fecha de inicio:</label>
                                                        <i class="fa fa-check-circle text-green"></i>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaInicio" required>
                                                        </div>      
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">2.-Fecha de fin:</label>
                                                        <i class="fa fa-check-circle text-green"></i>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaFinal" required>
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                </div>
                                            </div>

                                            
                                             <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">3.-Nóminista:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                            </div>
                                                            <select class="form-control textoMay iluminarIconoInput" name="nominista" >
                                                                <option value="<?php echo $_SESSION['identificador'] ?>"><?php echo Nominas::getNominista(); ?></option>
                                                                <?php echo Nominas::verificarJefatura(); ?>
                                                            </select>
                                                        </div>      
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">4.-Estatus de la nómina:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-list-ol"></i>
                                                            </div>
                                                            <select class="form-control textoMay iluminarIconoInput" name="statusNominas" >
                                                                <option value="1">AUTORIZADA</option>
                                                                <option value="2">NO AUTORIZADA</option>
                                                                <option value="3">AUTORIZADA / NO AUTORIZADA</option>
                                                            </select>
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                          
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 estilos-centrar">
                                                    <button type="submit" name="reporteNominasSucursal" value="" class="btn btn-success btn-lg"><i class="fa fa-download"></i> Descargar</button> 
                                                </div>
                                            </div>
                                    </div>    
                                </form> 
                    
                            </div>
                        </div>
        

                        <div class="box box-info collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#00C0EF"></i> Cargar nóminas</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                    <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                               
                                    <div class="max1000">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Cargar registros:</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <form method="post" enctype="multipart/form-data" id="formularioCargarNominas"> 
                                                    <div class="col-md-12 estilos-centrar">
                                                        <span class="btn btn-info btn-lg btn-file" style="width:139px;"><i class="fa fa-upload"></i> Cargar<input type="file" name="cargarRegistrosNominas" id="cargarRegistrosNominas" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"></span>
                                                    </div>
                                                </form> 
                                            </div>

                                            
                                            <div class="row">
                                                <form method="post">  
                                                    <div class="col-md-12 estilos-centrar">
                                                        <p>
                                                            <hr>
                                                            <i class="fa fa-exclamation-circle fa-2x"></i>
                                                            <b>Descargar archivo para llenado de datos (Máximo 100 registros por archivo)</b>
                                                            <button type="submit" name="formatoLlenadoNominas001" value="" class="btn btn-success"><i class="fa fa-download"></i> Nóminas</button>
                                                        </p>
                                                    </div>
                                                </form> 
                                            </div>
                                          
                                           
                                                <hr>
                                                <h4>Dependiendo del tipo de esquema los campos obligatorios (los cuales forzosamente deben de ser capturados) de la tabla de liberación pueden cambiar, verifica que el llenado de las columnas del <b>layout de excel</b> sean las correctas.</h3>
                                               
                                                <table class="table table-bordered" style="box-shadow: 0px 10px 15px -4px rgba(0,0,0,0.75);">
                                                    <tr style="background:rgb(0,166,90);color:#fff;box-shadow: inset 2px -18px 18px 5px rgba(0,0,0,0.32);">
                                                        <th class="text-center"></th>
                                                        <th class="text-center">ASIMILADOS</th>
                                                        <th class="text-center">MIXTO</th>  
                                                        <th class="text-center">SINDICATOS</th>
                                                        <th class="text-center">SUELDOS Y SALARIOS</th>
                                                        <th class="text-center">TARJETA E.</th>  
                                                        <th class="text-center">PRESTAMO</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>A) Esquema</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>B) Pagadora sindicato</th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>C) Devengada</th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>D) Nombre del cliente</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>E) Tipo de pago</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>F) Régimen</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>G) Comisión</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"><i class="fa fa-exclamation-circle fa-2x text-blue"></i></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>H) Empresa que factura</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>I) Subtotal</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-exclamation-circle fa-2x text-orange"></i></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>J) IVA</th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>K) Total</th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>L) Pagadora IMSS</th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>M) Total IMSS</th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>N) Pagadora asimilados</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>O) Total asimilados</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>P) Tipo periodo</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaPar">
                                                        <th>Q) No. periodo</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    <tr class="lineaImpar">
                                                        <th>R) Socios</th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>
                                                        <th class="text-center"><i class="fa fa-check-circle fa-2x text-green"></i></th>  
                                                        <th class="text-center"></th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                </table>

                                                <br>
                                                <p><i class="fa fa-exclamation-circle fa-2x text-blue"></i> Si se selecciona la opción <b>SIN FACTURA</b> (H.-Empresa que factura) no sera obligatoria la <b>comisión</b> para ningún tipo de esquema</p>
                                                <p><i class="fa fa-exclamation-circle fa-2x text-orange"></i> Si se selecciona la opción <b>SIN FACTURA</b> (H.-Empresa que factura) no sera obligatorio el <b>subtotal</b> para ningún tipo de esquema (excepto para PRESTAMO)</p>
    
                                    </div>    
                             
                            </div>
                        </div>

                        
                        <div class="box box-danger collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#dd4b39"></i> Cargar comprobantes bancarios del cliente</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                    <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <form method="post">  
                                    <div class="max1000">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p>Si ya tienes nóminas capturadas y quieres cargar archivos adjuntos (<b>COMPROBANTES BANCARIOS DEL CLIENTE</b> en formato pdf) puedes hacerlo desde aquí, <b>no importa si la nómina ya tiene archivos adjuntos previamente o no</b>.</p>
                                                        <p>Para que el proceso funcione correctamente deberas subir una única carpeta (no importa el nombre), dentro de ella deben venir tantas subcarpetas como nóminas a las que quieras adjuntar los archivos, estas <b>subcarpetas deben tener el número de nómina</b>, y cada una de ellas a su vez contendra los archivos que quieras cargar, no importa el nombre de los archivos,</b> pero sólo se consideraran los <b>archivos con extensión .pdf</b>.</p>
                                                        <p>Ejemplo: si quiero subir archivos adjuntos a las nóminas 3000,3001 y 3050 la estructura deberá quedar de la siguiente manera:</p> 

                                                        <p><b>Nota:</b> Máximo <b>500</b> archivos por cada carga.</p>
                                                        <img src="<?php echo Ruta::ruta_server();?>views/img/cargar-archivos2.jpg" class="img-responsive center-block" style="border-radius:10px;max-width:600px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 estilos-centrar">
                                                    <span class="btn btn-danger btn-lg btn-file"><i class="fa fa-upload"></i> Cargar<input type="file" id="cargarARchivosAdjuntosMasivos" class="btn btn-warning btn-lg" webkitdirectory multiple></span> 
                                                </div>
                                            </div>
                                    </div>    
                                </form> 
                    
                            </div>
                        </div>

                        <div class="box box-warning collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#f39c12"></i> Cargar comprobantes de recibos de nómina</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                    <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <form method="post">  
                                    <div class="max1000">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p>Si ya tienes nóminas capturadas y quieres cargar archivos adjuntos (<b>COMPROBANTES DE NÓMINA</b> en formato pdf y xml) puedes hacerlo desde aquí, <b>no importa si la nómina ya tiene archivos adjuntos previamente o no</b>.</p>
                                                        <p>Para que el proceso funcione correctamente deberas subir una única carpeta (no importa el nombre), dentro de ella deben venir tantas subcarpetas como nóminas a las que quieras adjuntar los archivos, estas <b>subcarpetas deben tener el número de nómina</b>, y cada una de ellas a su vez contendra los archivos que quieras cargar, no importa el nombre de los archivos,</b> pero sólo se consideraran los <b>archivos con extensión .pdf y .xml</b>.</p>
                                                        <p>Ejemplo: si quiero subir archivos adjuntos a las nóminas 3000,3001 y 3050 la estructura deberá quedar de la siguiente manera:</p> 

                                                        <p><b>Nota:</b> Máximo <b>500</b> archivos por cada carga.</p>
                                                        <img src="<?php echo Ruta::ruta_server();?>views/img/cargar-archivos.jpg" class="img-responsive center-block" style="border-radius:10px;max-width:600px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 estilos-centrar">
                                                    <span class="btn btn-warning btn-lg btn-file"><i class="fa fa-upload"></i> Cargar<input type="file" id="adjuntarArchivosMasivosRecibos" class="btn btn-warning btn-lg" webkitdirectory multiple></span> 
                                                </div>
                                            </div>
                                    </div>    
                                </form> 
                    
                            </div>
                        </div>
                    


                   
                        <?php elseif($finanzas === 'active'): ?>

                        
                        <div class="box box-success collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <i class="fa fa-download fa-3x" style="color:#00A65A"></i> Descargar reportes</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                    <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <form method="post">  
                                    <div class="max1000">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Descarga la relación de tus nóminas registradas y las de tu personal a cargo:</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">1.-Fecha de inicio:</label>
                                                        <i class="fa fa-check-circle text-green"></i>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaInicio" required>
                                                        </div>      
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">2.-Fecha de fin:</label>
                                                        <i class="fa fa-check-circle text-green"></i>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaFinal" required>
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                </div>
                                            </div>

                                            
                                             <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">3.-Capturó:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                            </div>
                                                            <select class="form-control textoMay iluminarIconoInput" name="nominista" >
                                                                <option value="<?php echo $_SESSION['identificador'] ?>"><?php echo Nominas::getNominista(); ?></option>
                                                                <?php echo Nominas::verificarJefatura(); ?>
                                                            </select>
                                                        </div>      
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">4.-Estatus de la nómina:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-list-ol"></i>
                                                            </div>
                                                            <select class="form-control textoMay iluminarIconoInput" name="statusNominas" >
                                                                <option value="1">LIBERADA</option>
                                                                <option value="2">CANCELADA</option>
                                                                <?php //if(GrupoCapturaConfidenciales::pertenece($_SESSION['identificador']) || Configuraciones::administrador() == $_SESSION['identificador2'] ): ?>
                                                                    <!--<option value="5">CONFIDENCIAL</option>-->
                                                                <?php //endif; ?>
                                                                <option value="4">PENDIENTES</option>
                                                                <option value="3">TODOS</option>
                                                            </select>
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                          
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 estilos-centrar">
                                                    <button type="submit" name="reporteFinanzasSucursal" value="" class="btn btn-success btn-lg"><i class="fa fa-download"></i> Descargar</button> 
                                                </div>
                                            </div>
                                    </div>    
                                </form> 
                    
                            </div>
                        </div>


                    
                            <div class="box box-info collapsed-box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#00C0EF"></i> Cargar nóminas</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                        <i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                
                                        <div class="max1000">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="">Cargar registros:</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <form method="post" enctype="multipart/form-data" id="formularioCargarNominas"> 
                                                        <div class="col-md-12 estilos-centrar">
                                                            <span class="btn btn-info btn-lg btn-file" style="width:139px;"><i class="fa fa-upload"></i> Cargar<input type="file" name="cargarRegistrosNominas" id="cargarRegistrosNominas" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"></span>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="row">
                                                    <form method="post">  
                                                        <div class="col-md-12 estilos-centrar">
                                                            <p>
                                                                <hr>
                                                                <i class="fa fa-exclamation-circle fa-2x text-yellow"></i>
                                                                <b>Descargar archivo para llenado de datos</b>
                                                                <button type="submit" name="formatoLlenadoFinanazas001" value="" class="btn btn-success"><i class="fa fa-download"></i> Finanzas</button>
                                                            </p>
                                                        </div>
                                                    </form> 
                                                </div>
                                        </div>    
                                </div>
                            </div>
                    

                            
                           
                                <div class="box box-danger collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#dd4b39"></i> Cargar comprobantes bancarios del cliente</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                            <i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <form method="post">  
                                            <div class="max1000">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p>Si ya tienes nóminas capturadas y quieres cargar archivos adjuntos (<b>COMPROBANTES BANCARIOS DEL CLIENTE</b> en formato pdf) puedes hacerlo desde aquí, <b>no importa si la nómina ya tiene archivos adjuntos previamente o no</b>.</p>
                                                                <p>Para que el proceso funcione correctamente deberas subir una única carpeta (no importa el nombre), dentro de ella deben venir tantas subcarpetas como nóminas a las que quieras adjuntar los archivos, estas <b>subcarpetas deben tener el número de nómina</b>, y cada una de ellas a su vez contendra los archivos que quieras cargar, no importa el nombre de los archivos,</b> pero sólo se consideraran los <b>archivos con extensión .pdf</b>.</p>
                                                                <p>Ejemplo: si quiero subir archivos adjuntos a las nóminas 3000,3001 y 3050 la estructura deberá quedar de la siguiente manera:</p> 

                                                                <p><b>Nota:</b> Máximo <b>500</b> archivos por cada carga.</p>
                                                                <img src="<?php echo Ruta::ruta_server();?>views/img/cargar-archivos2.jpg" class="img-responsive center-block" style="border-radius:10px;max-width:600px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12 estilos-centrar">
                                                            <span class="btn btn-danger btn-lg btn-file"><i class="fa fa-upload"></i> Cargar<input type="file" id="cargarArchivosAdjuntosMasivosFinanzas" class="btn btn-warning btn-lg" webkitdirectory multiple></span> 
                                                        </div>
                                                    </div>
                                            </div>    
                                        </form> 
                            
                                    </div>
                                </div>
                          



                        

                        <?php endif;?>
                      
                
                    </div>


<!-- -->
                    <?php if($liberacion === 'active'): ?>
                    <div role="tabpanel" class="tab-pane" id="importar2"> 

                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title"> <i class="fa fa-download fa-3x" style="color:#00A65A"></i> </h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                        <i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <form method="post">  
                                        <div class="max1000">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="">Selecciona el rango de fechas (en que el registro fue guardado por el nóminista) para descargar el reporte total de las nóminas existentes en el sistema:</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                            
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="">1.-Fecha inicio:</label>
                                                                <i class="fa fa-check-circle text-green"></i>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                    <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaInicio" required>
                                                                </div>      
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="">2.-Fecha final:</label>
                                                                <i class="fa fa-check-circle text-green"></i>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                    <input class="form-control iluminarIconoInput" type="date" value="<?php echo date("Y-m-d");?>" name="fechaFinal" required>
                                                                </div>     
                                                            </div>
                                                            <div class="col-md-2">
                                                            
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 estilos-centrar">
                                                        <button type="submit" name="reporteNominasFinal" value="" class="btn btn-success btn-lg"><i class="fa fa-download"></i> Descargar</button> 
                                                    </div>
                                                </div>
                                        </div>    
                                    </form> 
                        
                                </div>
                            </div>      
                    </div>
                    <?php endif; ?>

    <!-- -->
                              
                    <div role="tabpanel" class="tab-pane administrar-nominas <?php echo $finanzas; ?>" id="consultar"> 

                        <?php if($nominas === ""): ?>
                            <div class="row"  style="margin-top: 2%;">
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
                                        <div class="info-box-content">
                                        <span class="info-box-text"> <b>Pendientes</b></span>
                                            <span class="info-box-text">Finanzas: <b><span id="cargarMarcadoresPendientes" style="font-size:16px;"><?php echo Nominas::marcadores(1); ?></span></b> </span>
                                            <span class="info-box-text">Tesoreria: <b><span id="cargarMarcadoresPendientes2" style="font-size:16px;"><?php echo Nominas::marcadores(4); ?></span></b></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i class="fa fa-check-square-o"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"> <b>Liberadas / Pagadas</b></span>
                                            <span class="info-box-text">Finanzas: <b><span id="cargarMarcadoresLiberados" style="font-size:16px;"><?php echo Nominas::marcadores(2); ?></span></b> </span>
                                            <span class="info-box-text">Tesoreria: <b><span id="cargarMarcadoresPagadas" style="font-size:16px;"><?php echo Nominas::marcadores(5); ?></span></b></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"> <b>Canceladas</b> </span>
                                            <span class="info-box-text">Finanzas: <b><span id="cargarMarcadoresCancelados" style="font-size:16px;"><?php echo Nominas::marcadores(3); ?></span></b> </span>
                                            <!--<span class="info-box-text">Tesoreria: <b><span id="cargarMarcadoresCancelados2" style="font-size:16px;"><?php //echo Nominas::marcadores(6); ?></span></b></span>-->
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                            </div>
                        <?php endif;?>


                         <?php /*if($nominas === "active" || $_SESSION['identificador'] == 172 ||  $_SESSION['identificador'] == 168 ):*/if($nominas === "active"): ?>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="">Nombre del nominista:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select class="form-control textoMay iluminarIconoInput" id="filtroNombreNominista" >
                                            <option></option>
                                            <?php echo Nominas::mostrarNoministas(); ?>
                                        </select>
                                    </div>   
                                </div>  
                            </div>
                         <?php endif; ?>
                        
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label for="">Número de nómina:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-hashtag"></i>
                                    </div>
                                    <input class="form-control iluminarIconoInput" type="text" id="filtroNumeroNomina">
                                </div>   
                            </div>

                            <div class="col-md-8">
                                <label for="">Nombre del cliente:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select class="form-control textoMay iluminarIconoInput" id="filtroNombreCliente" >
                                        <option></option>
                                        <?php echo Nominas::mostrarSelect($datos["id_cliente"],Tablas::clientes()); ?>
                                    </select>
                                </div>   
                            </div>  
                        </div>
   
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label for="">Monto (iva,subtotal,total):</label>                               
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input class="form-control monetario iluminarIconoInput" type="text" id="filtroMontoFacturado">
                                </div>
                            </div> 

                            <?php if($tipo === '/asesores/nominas' || $tipo === '/asesores/liberacion'):?>
                                <div class="col-md-3">
                                    <label for="">Autorización <?php if($tipo === '/asesores/liberacion') echo '<b> (nóminas)</b>'; ?>:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput" id="filtroAutorizacion">
                                                <option value=""></option>
                                                <option value="1">AUTORIZADA</option>
                                                <option value="0">NO AUTORIZADA</option>
                                            </select>
                                    </div> 
                                </div>   
                            <?php endif;?>  

                            <?php if($tipo === '/asesores/finanzas' || $tipo === '/asesores/liberacion'):?>
                                <div class="col-md-3">
                                    <label for="">Estatus liberación <?php if($tipo === '/asesores/liberacion') echo '<b> (finanzas)</b>'; ?>:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput" id="filtroObservaciones">
                                                <option value="0"></option>
                                                <option value="1">PENDIENTE</option>
                                                <option value="2">LIBERADA</option>
                                                <option value="3">CANCELADA</option>
                                            </select>
                                    </div> 
                                </div>   
                            <?php endif;?>

                             <?php if($tipo === '/asesores/tesoreria' || $tipo === '/asesores/liberacion'):?>
                                <div class="col-md-3">
                                    <label for="">Estatus Pago <?php if($tipo === '/asesores/liberacion') echo '<b> (tesorería)</b>'; ?>:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput" id="filtroPago">
                                                <option value="0"></option>
                                                <option value="1">PENDIENTE</option>
                                                <option value="2">PAGADA</option>
                                                <option value="3">PAGADA CON DEVOLUCIÓN</option>
                                                <option value="4">PAGADA CON OBSERVACIÓN</option>
                                            </select>
                                    </div> 
                                </div>   
                            <?php endif;?>
                             
                        </div>

                        <div class="row form-group">
                            <div class="col-md-8">
                                <b>Total de registros que coinciden: </b>  <span id="totalRegistrosNominas" style="font-size:20px;"><?php echo $totalRegistros; ?> </span>
                            </div>   

                            <div class="col-md-4" style="text-align:right;margin-top:15px;">
                                    <button class="btn btn-lg btn-info" id="actualizarNominas"><i class="fa fa-refresh"></i> Actualizar</button>
                            </div>   
                        </div>

                            <?php if($liberacion !== "active"): ?>

                                <div class="row form-group" style="margin-bottom: -20px;">
                                    <div class="col-md-2">
                                        <?php if($_SERVER['REQUEST_URI'] === '/asesores/nominas'): ?>
                                            <i class="fa fa-trash fa-3x text-red" style="cursor:pointer;" id="eliminarNominas" title="Eliminar nóminas"></i>
                                            <i class="fa fa-check-square-o fa-3x text-green" style="cursor:pointer;" id="liberarNominas" title="Autorizar nóminas"></i>
                                            <div class="row form-group">
                                                <div class="col-md-12">

                                                        <label class="container container2" style="margin-left:50px;margin-top:10px;" title="Seleccionar todas las nóminas pendientes por autorizar de la página actual">
                                                            <input type="checkbox" id="masterAutorizar" style="cursor:pointer;"> 
                                                            <span class="checkmark checkmark2"></span>
                                                        </label>
                                                    
                                                        <label class="container container" style="margin-left:10px;margin-top:-12px;" title="Seleccionar todas las nóminas pendientes por autorizar de la página actual">
                                                            <input type="checkbox" id="masterEliminar" style="cursor:pointer;"> 
                                                            <span class="checkmark checkmark"></span>
                                                        </label>

                                                </div>  
                                            </div>
                                        <?php endif; ?>
                                    </div>  
                                    
                                    <div class="col-md-10">
                                        <span class="paginadorNominas"><?php echo $paginacion->mostrar();?></span> 
                                    </div>   
                                </div>

                                <div class="renglonEncabezado" style="margin-top: 25px;">
                                    <div class="campoFolioEncabezado">Folio</div>
                                    <?php if($_SERVER['REQUEST_URI'] === '/asesores/nominas'): ?>
                                        <div class="campoNominasEncabezado" style="justify-content: center;">Nominas</div>
                                    <?php elseif($_SERVER['REQUEST_URI'] === '/asesores/finanzas'): ?>
                                         <div class="campoFinanzasEncabezado" style="justify-content: center;">Finanzas</div>
                                    <?php else: ?>
                                        <div class="campoTesoreriaEncabezado">Tesorería</div>
                                    <?php endif; ?>
                                    <div class="campoNominasEmpresa">Cliente</div>
                                    <div class="campoTipoEncabezado">Esquema / Tipo</div>
                                    <div class="campoFechaEncabezado">Fecha captura</div>
                                    <div class="campoArchivos">Archivos</div>
                                    <div class="campoOpcionesEncabezado">Opciones</div>
                                </div>

                                <div id="recargarNominas">
                                    <?php echo Nominas::mostrarNominas($paginacion->limitRegistros(),$data,$_SERVER['REQUEST_URI']); ?>           
                                </div>

                                 <span class="paginadorNominas"><?php echo $paginacion->mostrar();?></span>

                            <?php else: ?>

                                <span class="paginadorNominas"><?php echo $paginacion->mostrar();?></span> 

                                <div class="row form-group" style="margin-bottom: -20px;">
                                    <div class="col-md-12">
                                        <p><span style="display:inline-block;font-size:15px;background:rgba(34, 33, 33, 0.85);color:#fff;width:50px;text-align:center;border-radius:3px;"> 0 </span> Total de <b>comprobantes</b> bancarios anexados a la nómina</p>
                                        <p><span style="display:inline-block;font-size:15px;background:#3489df;color:#fff;width:50px;text-align:center;border-radius:3px;"> 0 </span>  Total de <b>recibos</b> anexados a la nómina</p>
                                    </div>  
                                </div>
                            
                                <div class="renglonEncabezado" style="margin-top: 25px;">
                                    <div class="campoFolioEncabezado2">Folio</div>
                                    <div class="campoClienteEncabezado" style="justify-content: center;">Cliente</div>
                                    <div class="campoSucursalEncabezado" style="justify-content: center;">Sucursal</div>
                                    <div class="campoNominasEncabezado2">Nominas</div>
                                    <div class="campoFinanzasEncabezado2">Finanzas</div>
                                    <div class="campoTesoreriaEncabezado2">Tesorería</div>
                                    <div class="campoOpcionesEncabezado2">Opciones</div>
                                </div>

                                <div id="recargarNominas">
                                    <?php echo Nominas::mostrarNominas($paginacion->limitRegistros(),$data,$_SERVER['REQUEST_URI']); ?>           
                                </div>

                                <span class="paginadorNominas"><?php echo $paginacion->mostrar();?></span> 

                            <?php endif; ?>

                    </div>


                    <?php if($_SESSION['identificador2'] == 6 || $_SESSION['identificador'] == 201 ): ?>
                        <div role="tabpanel" class="tab-pane seccionPermisosx" id="autorizacion"> 
                            <h3 class="text-center">Personal con autorización para visualizar este módulo</h3>
                            <br>
                            <div class="renglonEncabezado">
                                <div class="campoIdEncabezado">No.</div>
                                <div class="campoNombreEncabezado">Nombre</div>
                                <div class="campoSucursalEncabezado">Sucursal</div>
                                <div class="campoPuestoEncabezado">Puesto</div>
                            </div>
                            <?php echo Nominas::mostrarNoministas2($_SERVER['REQUEST_URI']); ?>
                        </div>
                    <?php endif; ?>
                   

                </div> 
            </div>panel
        </div>

    
       

        <!--Ventana modal-->
        <div class="modal fullscreen-modal fade bd-example-modal-lg fade" id="modalMostrarNominas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" style="overflow-y:auto;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">DATOS DE LA NÓMINA No. <span id="consecutivoNomina" style="font-size:25px;background:#00a65a;padding:10px;color:#fff;border-radius:5px;"></span></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity:1;">
                                <i class="fa fa-window-close fa-lg text-red" aria-hidden="true"></i>
                          </button>
                      </div>

                      <div class="modal-body">
                            <div id="dataNominas">
                                <div style="text-align:center">
                                    <i class="fa fa-spinner fa-pulse fa-fw" style="font-size:110px;"></i>
                                </div>
                            </div>
                      </div>
                      <div class="modal-footer estilos-centrar limpiardiv">
                        
                      </div>


                </div>
            </div>
        </div>
          <!--Ventana modal-->

         <!--Ventana modal-->
         <div class="modal fade bd-example-modal-lg" id="modalArchivosAdjuntos" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">TOTAL DE ARCHIVOS ADJUNTOS: <b><span id="labelArchivosAdjuntos" style="font-size:21px;"></span></b></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity:1;">
                                <i class="fa fa-window-close fa-lg text-red" aria-hidden="true"></i>
                          </button>
                      </div>

                      <div class="modal-body">
                            <div id="dataArchivosAdjuntos" style="text-align:center">
                                <div>
                                    <i class="fa fa-spinner fa-pulse fa-fw" style="font-size:110px;"></i>
                                </div>
                            </div>
                      </div>
                      <div class="modal-footer estilos-centrar limpiardiv">
                        
                      </div>


                </div>
            </div>
        </div>
          <!--Ventana modal-->


        <!-- /.box-body -->
        <div class="box-footer">
          <!--Footer-->
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- =============================================== -->