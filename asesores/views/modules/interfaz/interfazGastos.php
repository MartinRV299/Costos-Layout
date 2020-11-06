
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="controlCompras">
    <!-- Main content -->
    <section class="content-conciliacion">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border ">
             <h3 class="box-title text"><i class="fa fa-shopping-cart icono-encabezado-conciliacion" ></i > COMPRAS </h3>
          <div class="box-tools pull-right">
          
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div><!--<h2 class="box-title text"> C o n c i l i a c i ó n </h2>-->
        <div class="box-body">
                 <div role="tabpanel"> 
                    <ul class="nav nav-tabs">
                        
                            <li role="presentation" class="active">
                                <a href="#compra" aria-controls="compra" role="tab" data-toggle="tab">Nueva Compra</a>
                            </li>
                            <li role="presentation">
                              <!--  <a href="#catalogo" aria-controls="catalogo" role="tab" data-toggle="tab">Catalogos</a> -->
                                <button  href="#catalogo" aria-controls="catalogo" role="tab" data-toggle="tab" class="btn btn-inf"> Consultar </button>
                            </li>                       
                          <!--  <li role="presentation">
                                <a href="#autorizados" aria-controls="eautorizados" role="tab" data-toggle="tab">Configuracion de Personal</a>
                            </li>
                            <li role="presentation">
                                <a href="#Personalautorizados" aria-controls="Personalautorizados" role="tab" data-toggle="tab">Personal Autorizado</a>
                            </li>
                            <li role="presentation">
                                <a href="#descargarchivos" aria-controls="descargarchivos" role="tab" data-toggle="tab">Cargar - Descargar archivos</a>
                            </li>-->
                    </ul>
                    <div class="tab-content" style="margin-top: 2%;">
                        <div role="tabpanel" class="tab-pane administrar-nominas active" id="compra">  <!-- INICIO ROW 1 COLUMNA -->
                              <!-- ***********************FORMULARIO CONCILIACION********************************** --> 
                              <button type="button" class="btn btn-light" style=" background-color:#585858; color:#EFB810" id="llenado" name="llenado">Datos</button>
                              <form method="POST" id="formularioCompras" enctype="multipart/form-data">
                                <div style="background:#3c8dbc;color:#fff;padding:5px;border-top-right-radius:20px;border-top-left-radius:20px; text-align: center;" class="textoMay">Datos de Gasto</div>
                                <div style="border:1px solid #222d32;padding:5px;">
                                    <div class="row form-group">
                                            <div class="col-md-4">
                                                <label for="">1.-Nombre:</label>
                                                <i class="fa fa-check-circle text-green"></i>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-user-circle"></i>
                                                    </div>
                                                    <input class="form-control textoMay inputIconBg" type="text" name="Nombre" id="Nombre" required >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">2.-Departamento:</label>
                                                <i class="fa fa-check-circle text-green"></i>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-building"></i>
                                                    </div>
                                                    <input class="form-control textoMay inputIconBg" type="text" name="Departamento" id="Departamento"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">3.-Fecha:</label>
                                                <i class="fa fa-check-circle text-green"></i>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                    <i class="fa fa-calendar-plus-o"></i>
                                                    </div>
                                                    <input class="form-control textoMay" type="date" name="Fecha" id="Fecha" required>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">4.-RFC</label>
                                            <i class="fa fa-check-circule"></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                </div>
                                                <input type="text" class="form-control" id="Rfc" name="Rfc" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">5.-Proveedor</label>
                                            <i class=" "></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <select class="form-control" name="Proveedor" id="Proveedor" required>
                                                    <option value=""></option>
                                                    <option value="1">Papeleria</option>
                                                    <option value="2">Despensa</option>
                                                    <option value="3">Limpieza</option>
                                                    <option value="4">Sistemas</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">6.-Telefono</label>
                                            <i class="fa fa-check-circule"></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa  fa-phone"></i>
                                                </div>
                                                <input type="tel" class="form-control celular" name="Telefono" id="Telefono" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">7.- Email</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class=" fa fa-at"></i>
                                                </div>
                                                <input type="email" class="form-control" name="Email" id="Email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">8.-Direccion</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-address-card"></i>
                                                </div>
                                                <input type="text" class="form-control" name="Direccion" id="Direccion" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <br>
                                    <ol>
                                    <div class="row form-group">
                                        <div class="col-md-1 text-center">
                                            <i class="fa fa-plus-circle text-blue fa-3x agregarContacto" style="cursor:pointer;"></i>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="">9.-Producto: </label>
                                            <i class="fa fa-check-circle text-green"></i>
                                            <li>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                    <i class="fa fa-product-hunt"></i>
                                                    </div>
                                                    <input class="form-control textoMay" type="text" name="regimen" >
                                                </div>
                                            </li> 
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">10.-Cantidad:</label>
                                            <i class="fa fa-check-circle text-green"></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-plus"></i>
                                                </div>
                                                <input class="form-control textoMay" type="number" name="nombre" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">11.-Precio Total:</label>
                                            <i class="fa fa-check-circle text-green"></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-usd"></i>
                                                </div>
                                                <input class="form-control  textoMay" type="text" name="nombre" >
                                            </div>
                                        </div>     
                                    </div>
                                    <div id="areaContacto"></div>
                                    </ol>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span><b>7.-Motivo de compra:</b></span> <i class="fa fa-check-circle text-green"></i>
                                            <textarea name="nominasComentarios" class="form-control" rows="3" style="resize:vertical;"></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                <div class="row text-center">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success" id="enviargasto" name="enviargasto"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                        <button type="submit" id="cancelarClientes" class="btn btn-danger"><i class="fa fa-ban fa-lg"></i> Cancelar</button> 
                                    </div>
                                </div>
                            </form> 
                            <!-- ***********************FIN FORMULARIO CONCILIACION****************************** -->  
                        </div>              
                        <!-- ************************* INICIO DE SEGUNDA PESTAÑA CATALOGO*****************************-->
                        <div role="tabpanel" class="tab-pane seccionPermisosx" id="catalogo"> <!-- INICIO 2 COLUMNA -->

                        <div class="renglonEncabezado">
                                <div class="campoIdEncabezado">ID.</div>
                                <div class="campoNombreEncabezado">Nombre Socitud</div>
                                <div class="campoSucursalEncabezado">Sucursal</div>
                                <div class="campoPuestoEncabezado">Puesto</div>
                            </div>
                            
                         </div>
                                    <!-- ************************* FIN DE SEGUNDA PESTAÑA *****************************-->
                                    <!-- ***************************pestaña autorizadosA****************************** -->
                                    <div role="tabpanel" class="tab-pane seccionPermisosx" id="autorizados">  
                                        
                                        </div>
                                    <!-- ***************************FIN pestaña autorizadosA****************************** -->
                                    <!-- ***************************pestaña autorizadosA****************************** -->
                                    <div role="tabpanel" class="tab-pane seccionPermisosx" id="Personalautorizados">
                                    
                                    </div>
                                    <!-- ***************************pestaña autorizadosA****************************** -->
                                    <!--***********************INICIO DE CUARTA PESTAÑA*******************************-->
                                            <div role="tabpanel" class="tab-pane seccionPermisosx" id="descargarchivos"> 
                                    <!-- ************************archivos********************************* -->
                                        <div class="box-header with-border" style=" text-align: center;">
                                            <h3 class="box-title"> <i class="fa fa-download fa-3x" style="color:#00A65A" ></i> Descargar Archivo</h3>
                                        </div>
                                        <div class="box-body">
                                            
                                        </div>                    
                                        <!-- *************************FIN DEL CUARTA PESTAÑA******************************* -->
                                    </div>
                                    <!-------------- termina archivos---------------- -->
                                </div> <!-- ************************FIN TAB CONTEN FIN********************************* -->
                            </div> <!-- ************************FIN TAB PANEL********************************* -->               
                </div><!-- /.box -->
                </section>
                <!-- /.content -->
            </div>
            <!--##################### MODAL ACLARACION FECHAS ############################### -->
            