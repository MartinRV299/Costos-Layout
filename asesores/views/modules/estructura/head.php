<?php 
  class MiPerfil{
      public $fechaSolicitada=''; 
      public $fechaFinalizacion=''; 
      public $usuarioSecundario='';
      public $imagen = '';
      public $totalNotificaciones;
      public $notificaciones = 0;
      public $cambioGuardia;
      public $data=array();
      public $solicitudes='';
      public $resultado;
      public $cambioGuardiaBandera = 0;
      public $respuesta;
      public $textoNotificaciones='No hay notificaciones nuevas';
      public $textoSolicitudes='No hay solicitudes nuevas';
      public $claseNotificaciones='';
      public $claseSolicitudes='';
      public $paquetes = 0;
      public $paquetesExternos = 0;
      public $tickets = 0;

      public function solicitudesYnotificaciones(){ //indica que existe una nueva solicitud o una repuesta a la misma
          if($_SESSION["identificador2"] >= Configuraciones::jefatura() || AccesoRHespecial::pertenece($_SESSION['identificador']) ){// si no tiene personal a su cargo, entonces no consulto las solicitudes
              $this->solicitudes = PermisosControllers::mostrarSolicitudesPersonalAcargo($_SESSION["identificador"]);
          }  

          $this->notificaciones = PermisosControllers::mostrarRespuestaSolicitud($_SESSION["identificador"]);// cuando existe una respuesta a una solicitud (autorizada o cancelada)
          
          $this->cambioGuardia = PermisosControllers::mostrarCambiosDeGuardia($_SESSION["identificador"]);// cuando existe una solicitud de cambio de guardia
          if($this->cambioGuardia){
              $this->resultado = Datos::mostrarUsuarioUnicoModel3($this->cambioGuardia['id_usuario'],Tablas::usuarios());
              $this->usuarioSecundario = $this->resultado['nombre'].' '.$this->resultado['paterno'].' '.$this->resultado['materno'];
              $this->imagen = $this->resultado['imagen'];
              $this->fechaFinalizacion =substr($this->cambioGuardia['fecha_fin'],8,2).'-'.substr($this->cambioGuardia['fecha_fin'],5,2).'-'.substr($this->cambioGuardia['fecha_fin'],0,4);
              $this->fechaSolicitada=substr($this->cambioGuardia['fecha_inicio'],8,2).'-'.substr($this->cambioGuardia['fecha_inicio'],5,2).'-'.substr($this->cambioGuardia['fecha_inicio'],0,4);
              $this->cambioGuardiaBandera = 1;
          }

          $this->paquetes = Paqueteria::comprobarExistenciaPaquetes();//comprobar si hay paquetes
          $this->paquetesExternos = Paqueteria::comprobarExistenciaPaquetesExternos();//comprobar si hay paquetes
          $this->tickets = Tickets::comprobarRespuestaTickets(); // comprobar el cambio de estado de tickets


          $this->totalNotificaciones = $this->notificaciones + $this->cambioGuardiaBandera +  $this->paquetes +  $this->paquetesExternos + $this->tickets; //sumar todos los tipos de notificaciones
          $this->data = array(
                      'solicitudes' => $this->solicitudes,
                      'notificacionesSolicitudes' => $this->notificaciones,
                      'cambioGuardia' =>  $this->cambioGuardiaBandera,
                      'permuta' => $this->usuarioSecundario,
                      'imagen' => $this->imagen,
                      'inicio'=> $this->fechaSolicitada,
                      'fin'=> $this->fechaFinalizacion,
                      'totalNotificaciones' =>$this->totalNotificaciones,
                      'idPermiso'=> $this->cambioGuardia['id_permiso'],
                      'paquetes'=>$this->paquetes,
                      'paquetesExternos'=>$this->paquetesExternos,
                      'tickets'=>$this->tickets
                    );
          return $this->data;
      }     
  }

  $miPerfil = new MiPerfil();
  $miPerfil->respuesta = $miPerfil->solicitudesYnotificaciones();
  

  if($_SESSION["identificador2"] >= Configuraciones::jefatura() || AccesoRHespecial::pertenece($_SESSION['identificador'])){// si no tiene personal a su cargo, entonces no consulto las solicitudes
      if($miPerfil->solicitudes > 0 ){
          $miPerfil->claseSolicitudes = 'label label-success';
          $miPerfil->textoSolicitudes = $miPerfil->solicitudes > 1 ? 'Tienes '.$miPerfil->solicitudes.' nuevas solicitudes sin ver' : 'Tienes '.$miPerfil->solicitudes.' nueva solicitud sin ver' ;
      } 
      else
        $miPerfil->solicitudes='';

  }


  if($miPerfil->respuesta['totalNotificaciones'] > 0){
      $miPerfil->claseNotificaciones = 'label label-warning';
      $miPerfil->textoNotificaciones = $miPerfil->respuesta['totalNotificaciones']  > 1 ? 'Tienes '.$miPerfil->respuesta['totalNotificaciones'] .' notificaciones sin ver' : 'Tienes '.$miPerfil->respuesta['totalNotificaciones'].' notificación sin ver' ;
  }
  else
    $miPerfil->respuesta['totalNotificaciones'] = '';

?>
<header class="main-header" id="credencialesUsuario" value="<?php echo $_SESSION['identificador']; ?>" notify="<?php echo $_SESSION['notificaciones']; ?>">

<audio src="<?php echo Ruta::ruta_server();?>views/sonidos/lambada.mp3" preload="auto" id="customTicketUriel"></audio>
<!--<audio src="<?php echo Ruta::ruta_server();?>views/sonidos/YMCA-554202.mp3" preload="auto" id="customTicketMiguel"></audio>-->
<audio src="<?php echo Ruta::ruta_server();?>views/sonidos/cartel.mp3" preload="auto" id="customTicketUlises"></audio>

    <a href="#" class="logo">
      <span class="logo-mini"><img src="<?php echo Ruta::ruta_server();?>views/img/asesores2.png" alt=""> </span>
      <span class="logo-lg"> <img src="<?php echo Ruta::ruta_server();?>views/img/asesores.png" alt=""> </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Tocar navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav" >

        <?php if($_SESSION['identificador'] == 168 || $_SESSION['identificador'] == 223): ?>
          <li class="dropdown messages-menu">
            <!--<a href="#" id="activarChat" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-commenting-o"></i></a>-->

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-commenting-o"></i>
            </a>

            <ul class="dropdown-menu">
              <li class="header" style="background:#222d32;color:#fff;padding: 5px;">Abrir chat</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li id="activarChat"><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <i class="fa fa-window-restore fa-lg"></i>
                      </div>
                      <h4>
                        Ventana flotante
                      </h4>
                    </a>
                  </li>
                  <!-- end message -->
                  <li id="activarChatFull">
                    <a href="#">
                      <div class="pull-left">
                        <i class="fa fa-window-maximize fa-lg"></i>
                      </div>
                      <h4>
                        Pestaña
                      </h4>
                    </a>
                  </li>
                  
                </ul>
              </li>
            </ul>
       
          </li>
        <?php endif; ?>
       
        <?php if( ($_SESSION["identificador2"] >= Configuraciones::jefatura() AND $_SESSION["identificador2"] != Configuraciones::especial()) || AccesoRHespecial::pertenece($_SESSION['identificador']) ): ?>
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-file-text-o"></i>
                <span class="<?php echo $miPerfil->claseSolicitudes;?>" id="cargarTotalSolicitudes"> 
                    <?php echo $miPerfil->solicitudes;?>
                </span>
            </a>
            <ul class="dropdown-menu" style="border:1px solid #000;">
              <li class="header5" id="cargarTotalSolicitudes2" style="background:#222d32;color:#fff;padding: 5px;">
                <?php echo $miPerfil->textoSolicitudes?> 
              </li>
              <li>
                <ul class="menu">
                </ul>
              </li>
              <li class="footer" id="linkPermisos">
                <?php echo $miPerfil->solicitudes > 0 ? '<a href="solicitudes">Mostrar</a>' : '' ;?> 
              </li>
            </ul>
          </li>
        <?php endif ?>
        
          <!-- Notifications: style can be found in dropdown.less-->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="<?php echo $miPerfil->claseNotificaciones;?>" id="cargarNotificacionesTotal">
                  <?php echo $miPerfil->respuesta['totalNotificaciones']; ?>
              </span>
            </a>
            <ul class="dropdown-menu" style="border:1px solid #000;">
              <li class="header5" id="cargarNotificacionesTotal2" style="background:#222d32;color:#fff;padding: 5px;">
                <?php echo $miPerfil->textoNotificaciones;?> 
              </li>
              <li>
                <ul class="menuNotificaciones">

                  <li id="notificacionTickets">
                    <?php if( $miPerfil->tickets > 0): ?>
                      <form id="expandirPestanaTicketFormulario" action="ticketNuevo" method="post">
                        <input type="hidden" name="expandirPestanaHistorial" value="true"/>
                        <a href="#" id="expandirPestanaTicketHistorial"><i class="fa fa-ticket text-black"></i><?php echo $miPerfil->tickets; echo $miPerfil->tickets > 1 ? ' Respuestas a tus tickets' : ' Respuesta a tu ticket';?></a>
                      </form>
                    <?php endif ?>
                  </li>

                  <li id="cargarnotificacionesSolicitudes">
                      <?php if($miPerfil->notificaciones > 0): ?>
                        <form id="expandirformularioSolicitudes" action="usuariosPass" method="post">
                          <input type="hidden" name="expandirSolicitudes" value="true" />
                          <a href="#" id="expandirVentanaSolicitudes"><i class="fa fa-file-o text-black"></i><?php echo $miPerfil->notificaciones; echo $miPerfil->notificaciones > 1 ? ' Respuestas a tus solicitudes' : ' Respuesta a tu solicitud';?></a>
                        </form>
                      <?php  endif ?>
                  </li>
                    
                  <li id="cargarCambioGuardia">
                      <?php if( $miPerfil->cambioGuardiaBandera  > 0): ?>
                        <a href="usuariosPass" class="cambio-guardia"><i class="fa fa-handshake-o text-black"></i><?php echo $miPerfil->cambioGuardiaBandera; echo $miPerfil->cambioGuardiaBandera > 1 ? ' Cambios de guardia' : ' Cambio de guardia';?></a>
                      <?php endif ?>
                  </li>
                  
                  <li id="notificacionPaquete">
                    <?php if( $miPerfil->paquetes > 0): ?>
                        <a href="paqueteriaRevision"><i class="fa fa-truck text-black"></i><?php echo $miPerfil->paquetes; echo $miPerfil->paquetes > 1 ? ' Notificaciones paquetería interna' : ' Notificación paquetería interna';?></a>
                    <?php endif ?>
                  </li>

                  <li id="notificacionPaqueteExterno">
                    <?php if( $miPerfil->paquetesExternos > 0): ?>
                        <form id="expandirPestanaPaqueteExternoFormulario" action="paqueteriaRevision" method="post">
                          <input type="hidden" name="expandirPestanaExterna" value="true" />
                          <a href="#" id="expandirPestanaPaqueteExterno"><i class="fa fa-truck text-black"></i><?php echo $miPerfil->paquetesExternos; echo $miPerfil->paquetesExternos > 1 ? ' Notificaciones paquetería externa' : ' Notificación paquetería externa';?></a>
                        </form>
                    <?php endif ?>
                  </li>

                </ul>
              </li>
            </ul>
          </li>

        
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php if($_SESSION["imagen"] != NULL): ?>
              <img src="<?php echo Ruta::ruta_server();?>views/imagenes-usuarios/mini/martin.jpg" class="user-image" alt="User Image">
            <?php else: ?>
              <img src="<?php echo Ruta::ruta_server();?>views/img/user.png" class="user-image" alt="User Image">
            <?php endif ?>
              <span class="hidden-xs" id="codigoUnitario" name="<?php echo $_SESSION["identificador"];?>">Martin Rubio Vazquez</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image user-header-->
              <li class="alineacionCentralFlexBox user-header">
              <?php if($_SESSION["imagen"] != NULL): ?>
                <img src="<?php echo Ruta::ruta_server();?>views/imagenes-usuarios/mini/<?php echo $_SESSION["imagen"]; ?>" class="imagenSesion visor-crow-imagen-mini" alt="<?php echo Ruta::ruta_server();?>views/imagenes-usuarios/<?php echo $_SESSION["imagen"]; ?>" style="cursor: pointer;">
                <?php else: ?>
              <img src="<?php echo Ruta::ruta_server();?>views/img/user.png" class="imagenSesion visor-crow-imagen-mini" alt="User Image" style="cursor: pointer;">
              <?php endif ?>
                <p class="textoSesion">
                <?php echo $_SESSION["usuario"];?> 
                </p>
              </li>
            
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo Ruta::ruta_server();?>usuariosPass" class="btn btn-default btn-flat">Mi cuenta</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo Ruta::ruta_server();?>salir" class="btn btn-default btn-flat" id="cerrarChat">Cerra sesión</a>
                </div>
              </li>
            </ul>
          </li>


          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

     <!--Ventana modal 2-->
    <div class="modal fade bd-example-modal-lg fade" id="ventanaPreguntaCambioGuardia" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle2">CAMBIO DE GUARDIA <i class="fa fa-handshake-o fa-2x text-aqua"></i></h5>
                    <button type="button" class="close" id="limpiarPass" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="contenedorUsuario">
                          <div class="first-div-mini"> 
                                 <div id="nombreUsuarioCambioGuardia">
                                    <br>
                                    <span>Tienes una solicitud para cambiar guardia con:</span> 
                                    <br> 
                                    <b><?php echo $miPerfil->respuesta['permuta'];?></b> 
                                    <hr>
                                    <span>El día: <b><?php echo $miPerfil->respuesta['inicio'];?></b> (en caso de aceptar tendrías que venir en esta fecha).</span>
                                    <br>
                                    <br>
                                    <span>Por el día: <?php echo '<b>'.$miPerfil->respuesta['fin'].'</b> (y '.$miPerfil->respuesta['permuta'].' vendría en esta fecha).';  ?></span> 
                                    <br>
                                    <br>
                                    <p class="callout callout-info">¿Deseas aceptarla? <i class="fa fa-question-circle-o fa-2x"></i></p>
                                 </div>
                          </div>
                          <div class="second-div-mini estilos-centrar">
                                <img class="sangriaPermisos" id="fotografiaUsuarioPermuta" src="<?php echo $miPerfil->respuesta['imagen'] != null  ? "views/imagenes-usuarios/".$miPerfil->respuesta['imagen'] : "views/img/user.png" ?>" alt="imagen-usuario" height="140" width="110">
                          </div>
                          <div class="limpiardiv"></div>
                          <hr>
                          <div class="estilos-centrar" id="botonesFormularioCambioGuardia">
                              <button type="button" class="btn btn-success aceptarCambiodeGuardia" name="aceptar" value="<?php  echo  $miPerfil->respuesta['idPermiso']?>">Sí, aceptar</button>
                              <button type="button" class="btn btn-danger aceptarCambiodeGuardia" name="cancelar" value="<?php  echo  $miPerfil->respuesta['idPermiso']?>">No, rechazar</button>  
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
          <!--Ventana modal 2-->


        
  <!-- here Ventana modal
  <div class="modal fade bd-example-modal-lg fade" id="ventanaNotificaciones" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background:#28a745;color:#fff;">
                        <div class="modal-title col-md-8">
                          <h3><b>Notificaciones del sistema</b></h3>
                        </div>
                        <div class="modal-title col-md-4">
                           <img src="<?php echo Ruta::ruta_server();?>views/img/asesores.png" alt="">
                        </div>
                        
                       
                    </div>

                    <div class="modal-body">
                        <div id="contenidoNotificaciones">
                        <h3 class="estilos-centrar"> ¡Hola <?php echo $_SESSION["usuario"];?> !</h3>
                        <hr>        here  -->
                        <!--<p>Te invitamos a que pongas una foto de perfil, es muy fácil y permitiras que todos los compañeros de <b>Asesores Empresariales!</b> te conozcan.</p> -->
                        <!--<p>Te invitamos a que conozcas la <b>nueva fecha de tu cita</b> con la Nutrióloga.</p>-->
                       <!-- <p class="estilos-centrar">Te invitamos a que contestes la encusta <b>ECO</b> (Encuesta de Clima Organizacional), lo que nos ayudará a medir el clima organizacional de Asesores Empresariales!.</p>
                        <br>
                        <p class="estilos-centrar"> copia el siguiente link y pegalo en tu navegador: <b>http://www.humansolutions.com.mx/Secciones-20_eco.html</b></p>-->
                        <!--<form action="usuariosPass" method="post" class="estilos-centrar">
                          <input type="hidden" name="expandirMisDatos" value="true" />
                          <button type="submit" class="btn btn-info btn-lg botonEnterdoNotificaciones">ir ahora mismo <i class="fa fa-smile-o fa-lg" aria-hidden="true"></i></button>
                        </form>-->

                       <!--<form action="nutrifitness" method="post" class="estilos-centrar">
                          <button type="submit" class="btn btn-info btn-lg botonEnterdoNotificaciones">ir ahora mismo <i class="fa fa-smile-o fa-lg" aria-hidden="true"></i></button>
                        </form>-->
                      <!-- here2  <p>Te informamos que ya puedes descargar tus comprobantes de nómina desde el módulo <b>MI CUENTA</b>.</p>
                                          
                        </div>
                    </div>
                     
                   <div class="modal-footer">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-secondary botonEnterdoNotificaciones">Enterado.</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>     here2  -->
          <!--Ventana modal-->
