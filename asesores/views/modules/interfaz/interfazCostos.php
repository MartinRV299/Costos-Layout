<?php
$privilegios = GrupoCostos::privilegios($_SESSION['identificador']);
?>
 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="layoutCostos">
    <!-- Main content -->
    <section class="content-conciliacion">
    <!-- Default box -->
        <div class="box">
            <div class="box-header with-border ">
                <h3 class="box-title text"><i class="fa fa-file-excel-o icono-encabezado-conciliacion" ></i > Layout </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"title="Collapse">
                    <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div role="tabpanel"> 
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="active">
                            <a href="#captura" aria-controls="captura" role="tab" data-toggle="tab">Carga & Descarga</a>
                        </li>
                        <!--<li role="presentation">
                            <button  href="#reportes" aria-controls="reportes" role="tab" data-toggle="tab" class="btn btn-inf"> Reporte </button>
                        </li> -->                       
                    </ul>
                    <div class="tab-content" style="margin-top: 2%;">
                        <div role="tabpanel" class="tab-pane administrar-nominas active" id="captura"><!-- INICIO 1 PESTAÑA --> 
                              <!-- ***********************COLLAPSED DESCARGA REPORTES********************************** --> 
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
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <label for="" >Descarga reporte de costos registrados y las de tu personal a cargo:</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
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
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 estilos-centrar">
                                                    <button type="submit" name="reporteCostos"  class="btn btn-success  btn-lg"><i class="fa fa-download"></i> Descargar</button> 
                                                </div>
                                            </div>
                                        </div>    
                                    </form> 
                                </div>
                            </div><!-- ******************************* FIN COLLAPSED REPORTES*******************************************  style="text-align: center;"   -->
                        
                            <div class="box box-info collapsed-box" ><!-- ******************************* INICIO LAYOUT COSTOS******************************************* -->  
                                <div class="box-header with-border">
                                    <h3 class="box-title"> <i class="fa fa-upload fa-3x" style="color:#00C0EF"></i> Layout</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                                            <i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="max1000">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12" style="text-align: center;">
                                                     <label for="" >Carga Layout Costos (Solo archivos xlsx)  '</label><i style="color:green;" class="fa fa-file-excel-o fa-lg"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <form method="get" enctype="multipart/form-data" id="formlayout"> 
                                                <div class="col-md-12 estilos-centrar">
                                                    <span class="btn btn-info btn-lg btn-file" style="width:139px;"><i class="fa fa-upload"></i> Cargar<input type="file" class="form-control-file"  name="cargarRegistros" id="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"></span>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="row">
                                            <form method="post"  >  
                                                <div class="col-md-12 estilos-centrar">
                                                <p><hr>
                                                    <label for="" >Descarga del formato Layout para Llenado de Datos:</label>
                                                    <button type="submit"   class="btn btn-success btn-md" id="descarga" name="FormatoLayoutCostos" value="" > <i class="fa fa-download fa-lg"></i> Descargar</button>
                                                </p>
                                                </div>
                                            </form> 
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>  <!-- FIN 1 PESTAÑA --> 
                        <div role="tabpanel" class="tab-pane seccionPermisosx" id="reportes"> <!-- INICIO 2 COLUMNA -->
                        <!--**************************************************************************** -->
                        <!--<div class="box box-success collapsed-box">
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
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <label for="" >Descarga reporte de costos registrados y las de tu personal a cargo:</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
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
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 estilos-centrar">
                                                    <button type="submit" name="reporteCostos"  class="btn btn-success  btn-lg"><i class="fa fa-download"></i> Descargar</button> 
                                                </div>
                                            </div>
                                    </div>    
                                </form> 
                    
                            </div>
                        </div>-->
                        <!--**************************************************************************** -->    
                    </div>
                    
                </div>
            </div>
        </div>
    </section> 
</div>
