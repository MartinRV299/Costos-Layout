<?php
include_once "configuracionesMenu.php";
?>
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- search form -->
     <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Buscar...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li style="margin-top:3px;"></li>
       <!--  <li><a href="<?php echo Ruta::ruta_server();?>facturacion"><i class="fa fa-dot-circle-o" style="color:#811363 "></i> <span>Facturación</span></a></li>-->
        <li class="<?php echo $inicio;?>"><a href="<?php echo Ruta::ruta_server();?>inicio"><i class="fa fa-home"></i> <span>Inicio</span></a></li>
        <li><a href="<?php echo Ruta::ruta_server();?>facturacion"><i class="fa fa-dot-circle-o" style="color:#811363 "></i> <span>Facturación</span></a></li>
        <li><a href="<?php echo Ruta::ruta_server();?>nominas"><i class="fa fa-usd" style="color:green "></i> <span>Gastos</span></a></li>
        <li><a href="<?php echo Ruta::ruta_server();?>costos"><i class="fa fa-file-excel-o" style="color:green "></i> <span>Layout</span></a></li>
        <li><a href="<?php echo Ruta::ruta_server();?>gastos"><i class="fa fa-diamond" style="color:black "></i> <span>Pruebas_Test</span></a></li>
        <!-- <li><a href="<?php echo Ruta::ruta_server();?>gastos"><i class="fa fa-calculator"></i> <span>Control de costos</span></a></li> -->
      </ul>
      <!--<ul class="sidebar-menu">
      <li class="<?php echo $costos;?>"><a href="<?php echo Ruta::ruta_server();?>gastos"><i class="fa fa-calculator"></i> <span>Control de costos</span></a></li>
      </ul>-->
    </section>
    <!-- /.sidebar -->
  </aside>