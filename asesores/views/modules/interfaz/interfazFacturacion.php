<?php 
    $data = array(  'cliente'=>'',
    'facturado'=>'',
    'liberado'=>'',
    'pago'=>'',
    'nomina'=>'',
    'nominista'=>'',
    'autorizacion'=>'1'
); 

    $paginacion = new Paginacion(30);
    $paginacion->target('facturacion');
    $paginacion->parametroCliente('');
    $paginacion->parametroFacturado('');
    $paginacion->parametroLiberado('');
    $paginacion->parametroPago('');
    $paginacion->parametroNomina('');
    $paginacion->parametroNominista('');
    $paginacion->parametrosAutorizacion($data['autorizacion']);
    $totalRegistros=Nominas::contarRegistros($data,$_SERVER['REQUEST_URI']);
    $paginacion->totalPaginas($totalRegistros);
       

?>
  <!-- =============================================== -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="controlFacturacion">
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-calculator icono-encabezado"></i> MÓDULO DE FACTURACIÓN</h3>
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
                        
                            <li role="presentation" class="active">
                                <a href="#administrar" aria-controls="encuesta" role="tab" data-toggle="tab">Nuevo registro</a>
                            </li>

                      
                            <li role="presentation">
                                <a href="#archivos" aria-controls="archivos" role="tab" data-toggle="tab">Cargar - Descargar archivos</a>
                            </li>
                       
                        
                        <?php if($_SESSION['identificador2'] == 6 || $_SESSION['identificador'] == 201 ): ?>
                            <li role="presentation">
                                <a href="#autorizados" aria-controls="examen" role="tab" data-toggle="tab">Personal con autorización</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content" style="margin-top: 2%;">

                        <div role="tabpanel" class="tab-pane administrar-nominas active" id="administrar"> 
                                                    
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label for="">Número de nómina:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-hashtag"></i>
                                            </div>
                                            <input class="form-control iluminarIconoInput" type="text" id="filtroFolio">
                                        </div>   
                                    </div>

                                    <div class="col-md-8">
                                        <label for="">Nombre del cliente:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput" id="filtroCliente" >
                                                <option></option>
                                                <?php echo Nominas::mostrarSelect($datos["id_cliente"],Tablas::clientes()); ?>
                                            </select>
                                        </div>   
                                    </div>  
                                </div>
        
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label for="">Monto (iva,subtotal,total):</label>                               
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                            </div>
                                            <input class="form-control monetario iluminarIconoInput" type="text" id="filtroMonto">
                                        </div>
                                    </div> 
                                </div>
   
                    
                                <div class="row" style="margin-top: 2%;">
                                    <div class="col-md-8"><b>Total de registros que coinciden: </b>  <span id="totalRegistrosFacturacion" style="font-size:20px;"><?php echo $totalRegistros;?> </span></div>
                                    <div class="col-md-4" style="text-align:right;margin-top:15px;"><button class="btn btn-lg btn-info" id="actualizarFacturacion"><i class="fa fa-refresh"></i> Actualizar</button></div>   
                                </div>

                            
                                <span class="paginadorFacturacion"><?php echo $paginacion->mostrar();?></span> 

                                     
                                    <div class="renglonEncabezado" style="margin-top: 25px;">
                                        <div class="campoFolioEncabezado">Folio</div>
                                        <div class="campoTesoreriaEncabezado">Facturación</div>
                                        <div class="campoNominasEmpresa">Cliente</div>
                                        <div class="campoTipoEncabezado">Estatus factura</div>
                                        <div class="campoFechaEncabezado">Fecha captura</div>
                                        <div class="campoArchivos">Archivos</div>
                                        <div class="campoOpcionesEncabezado">Opciones</div>
                                    </div>

                                    <div id="dataFacturacion">  
                                        <?php echo Nominas::mostrarNominas($paginacion->limitRegistros(),$data,$_SERVER['REQUEST_URI']); ?>        
                                    </div>

                                <span class="paginadorFacturacion"><?php echo $paginacion->mostrar();?></span>

                        </div>

                   
                        <div role="tabpanel" class="tab-pane seccionPermisosx" id="archivos"> 
                            <!-- ********************************************************* -->
                            
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
                                                        <label for="">Descarga la relación de tus facturas registradas y las de tu personal a cargo:</label>
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
                                                        <label for="">4.-Estatus de la factura:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-list-ol"></i>
                                                            </div>
                                                            <select class="form-control textoMay iluminarIconoInput" name="statusNominas" >
                                                                <option value="1">PENDIENTE</option>
                                                                <option value="2">PAGADA</option>
                                                                <option value="3">NOTA DE CRÉDITO</option>
                                                                <option value="4">CANCELADA</option>
                                                                <option value="5">TODAS</option>
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
                                                    <button type="submit" name="reporteFacturacionSucursal" value="" class="btn btn-success btn-lg"><i class="fa fa-download"></i> Descargar</button> 
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
                                                    <form method="post" enctype="multipart/form-data" id="formularioCargarLayout"> 
                                                        <div class="col-md-12 estilos-centrar">
                                                            <span class="btn btn-info btn-lg btn-file" style="width:139px;"><i class="fa fa-upload"></i> Cargar<input type="file" name="cargarRegistrosNominas" id="cargarRegistrosFacturacion" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"></span>
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
                                                                <button type="submit" name="formatoLlenadoFactura001" value="" class="btn btn-success"><i class="fa fa-download"></i> Facturación</button>
                                                            </p>
                                                        </div>
                                                    </form> 
                                                </div>
                                        </div>    
                                </div>
                            </div>
                    

                            
                           
                                <div class="box box-warning collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#f39c12"></i> Cargar comprobantes bancarios del cliente</h3>
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
                                                            <p>Si ya tienes nóminas capturadas y quieres cargar archivos adjuntos (<b>COMPROBANTES</b> en formato pdf y xml) puedes hacerlo desde aquí, <b>no importa si la nómina ya tiene archivos adjuntos previamente o no</b>.</p>
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
                                                        <span class="btn btn-warning btn-lg btn-file"><i class="fa fa-upload"></i> Cargar<input type="file" id="adjuntarArchivosMasivosFacturacion" class="btn btn-warning btn-lg" webkitdirectory multiple></span> 
                                                    </div>
                                                </div>
                                            </div>    
                                        </form> 
                            
                                    </div>
                                </div>
                          



                        
                            <!-- ********************************************************* -->
                        </div>
                   

                    <?php if($_SESSION['identificador2'] == Configuraciones::administrador() || $_SESSION['identificador'] == 201 ): ?>
                        <div role="tabpanel" class="tab-pane seccionPermisosx" id="autorizados"> 
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
                </div>
                              
                        
        </div>

       <!--Ventana modal-->
       <div class="modal fullscreen-modal fade bd-example-modal-lg fade" id="modalFacturacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" style="overflow-y:auto;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">DATOS DE LA NÓMINA No. <span id="consecutivoNominaLabel" style="font-size:25px;background:#00a65a;padding:10px;color:#fff;border-radius:5px;"></span></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity:1;">
                                <i class="fa fa-window-close fa-lg text-red" aria-hidden="true"></i>
                          </button>
                      </div>

                      <div class="modal-body">
                            <div id="dataModal">
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





               