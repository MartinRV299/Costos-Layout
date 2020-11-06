<?php
session_start();
if(!$_SESSION["validar"]){
  header("location:ingreso");
  exit();
}

include_once 'permisos.php';
include_once '../models/permisos.php';
include_once 'usuarios.php';
include_once '../models/usuarios.php';
include_once '../models/sucursales.php';
include_once '../models/departamentosPuestos.php';
require_once "ajaxPaginacion.php";
require_once "../models/config.php";
require_once "MetodosDiversos.php";
include_once 'Paqueteria.php';
include_once '../models/Paqueteria.php';
include_once 'Tickets.php';
include_once '../models/TicketsModel.php';

class PermisosAjax{
    public $fecha; 
    public $fecha2; 
    public $autorizacion;
    public $sueldo;
    public $justificante;
    public $fechaReincorporacion; 
    public $fechaSolicitada=''; 
    public $fechaFinalizacion=''; 
    public $tipoPermiso;
    public $motivo;
    public $usuarioPrincipal;
    public $usuarioSecundario='';
    public $tipoSolicitud; //total, vistas,canceladas,autorizadas
    public $nombreUsuarioPrincipal;
    public $sucursal;
    public $horarioInicio;
    public $horarioFin;
    public $paginaActual;
    public $situacion;
    public $registrosPorPagina;
    public $tipoUsuario;
    public $target;
    public $imagen = '';
    public $totalNotificaciones;
    public $notificaciones;
    public $cambioGuardia;
    public $data=array();
    public $solicitudes='';
    public $resultado;
    public $cambioGuardiaBandera = 0;
    public $paquetes = 0;
    public $paquetesExternos = 0;
    public $comentarioJefe='';
    public $contarSabado=0;
    public $extemporaneo=false;
    public $imagenNombre = NULL;
    public $imagenTipo = NULL;
    public $imagenTemporal =NULL;
    public $imagenTamano = NULL;

    public function permisos(){
      $datos = array( 
                      "usuario"=>$this->usuarioPrincipal,
                      "solicitud"=>1, 
                      "fecha"=>$this->fecha, 
                      "horarioInicio"=>$this->horarioInicio,
                      "horarioFin"=>$this->horarioFin,
                      "permiso"=>$this->tipoPermiso,
                      "extemporaneo"=>$this->extemporaneo,
                      "motivo"=>mb_strtoupper(trim($this->motivo),'utf-8'),
                      'imagenNombre'=>$this->imagenNombre,
                      'imagenTipo'=>$this->imagenTipo,
                      'imagenTemporal'=>$this->imagenTemporal,
                      'imagenTamano'=>$this->imagenTamano
                    );
     $respuesta = PermisosControllers::permisosNuevoControllers($datos);
      echo $respuesta;
    } 

    public function vacaciones(){
      $datos = array( 
                      "usuario"=>$this->usuarioPrincipal,
                      "solicitud"=>2, 
                      "fecha"=>$this->fecha,
                      "extemporaneo"=>$this->extemporaneo
                    );
      $respuesta = PermisosControllers::vacacionesNuevoControllers($datos);
      echo $respuesta;
    } 

    public function guardias(){
      $datos = array(   
                      "usuario"=>$this->usuarioPrincipal,
                      "solicitud"=>3, 
                      "fecha"=>$this->fecha,
                      "fecha2"=>$this->fecha2, 
                      "usuarioSecundario"=>$this->usuarioSecundario,
                      "extemporaneo"=>$this->extemporaneo
                    );
      $respuesta = PermisosControllers::guardiasNuevoControllers($datos);
      echo $respuesta;
    }

    public function actualizarCalendario(){
      PermisosControllers::actualizarCalendarioControllers($this->usuarioPrincipal);
    }

    public function mostrarSolicitudesAjax(){//mostrar los datos de una solicitud en particular
      $respuesta = PermisosControllers::mostrarSolicitudesControllers($this->usuarioPrincipal,$_SESSION["identificador"]);//id de permiso no de usuario
      echo $respuesta;
    }

    public function mostrarSolicitudesPersonalAcargo(){ //indica que existe una nueva solicitud o una repuesta a la misma

        if($_SESSION["identificador2"] >= Configuraciones::jefatura()  || AccesoRHespecial::pertenece($_SESSION['identificador']) ){// si no tiene personal a su cargo, entonces no consulto las solicitudes
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

        $this->paquetes = Paqueteria::comprobarExistenciaPaquetes();//comprobar si hay paquetes internos
        $this->paquetesExternos = Paqueteria::comprobarExistenciaPaquetesExternos();//comprobar si hay paquetes externos
        
        $this->tickets = Tickets::comprobarRespuestaTickets(); // comprobar el cambio de estado de tickets
        $this->ticketsMensajes = Tickets::comprobarRespuestaTicketsMensajes(); // mensajes windows

        $this->totalNotificaciones = $this->notificaciones + $this->cambioGuardiaBandera  +  $this->paquetes +  $this->paquetesExternos + $this->tickets; //sumar todos los tipos de notificaciones
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
                    'tickets'=>$this->tickets,
                    'mensajesTickets'=>$this->ticketsMensajes

                  );
      echo json_encode($this->data);
    }

    public function totalSolicitudes(){
      $respuesta = PermisosControllers::totalSolicitudesControllers($_SESSION["identificador"],$this->tipoSolicitud);
      echo $respuesta;
    }

    public function buscarUsuarioPermisos(){

      $datos = array('nombreBuscar'=>$this->nombreUsuarioPrincipal,
                    'situacion'=>$this->situacion,
                    'sucursal'=>$this->sucursal,
                    'fecha'=>$this->fecha,
                    'tipoUsuario'=>$this->tipoUsuario,
                    'usuarioPrincipal'=>$this->usuarioPrincipal
      ); 

      $paginacion = new Paginacion($this->registrosPorPagina);
      $paginacion->target($this->target);
      $totalRegistros = PermisosControllers::totalSolicitudes2Controllers($datos);
      
      $paginacion->totalPaginas($totalRegistros);
      $paginacion->paginaActual($this->paginaActual);

      $paginacion->parametrosPaginadorSolicitudes($this->tipoUsuario,$this->usuarioPrincipal);
      
      $mostrar =  $paginacion->mostrar();
      $data = PermisosControllers::buscarUsuariosPermisosController($datos,$paginacion->limitRegistros());

      $respuesta = array("mostrar"=>$mostrar,"data"=>$data);
      echo json_encode($respuesta);
    }
 
    public function actualizarFormularioSolicitud(){//autorizar o cancelar solicitud RH
      if($this->autorizacion){
        $datos = array('idSolicitud'=>$this->usuarioPrincipal,
                       'autorizar'=>$this->autorizacion,
                       'sueldo'=>$this->sueldo,
                       'justificante'=>$this->justificante,
                       'fechaReincorporacion'=>$this->fechaReincorporacion,
                       'fechaSolicitada'=>$this->fechaSolicitada,
                       'fechaFinalizacion'=>$this->fechaFinalizacion,
                       'tipoDePermiso'=>$this->tipoPermiso,
                       'idUsuario'=>$this->usuarioSecundario,
                       'contarSabado'=>$this->contarSabado
        ); 
      }
      else{
        $datos = array('idSolicitud'=>$this->usuarioPrincipal,
                       'autorizar'=>$this->autorizacion,
                       'negacion'=>mb_strtoupper(trim($this->motivo),'utf-8'),
                       'tipoDePermiso'=>$this->tipoPermiso
                  ); 
      }
       
      $respuesta = PermisosControllers::actualizarFormularioSolicitudControllers($datos);
      echo $respuesta;
    }

    public function actualizarFormularioSolicitud2(){//autorizar o cancelar solicitud JEFE
      if($this->autorizacion){
        $datos = array('idSolicitud'=>$this->usuarioPrincipal,
                       'autorizar'=>1,
                       'comentarioJefe'=>$this->comentarioJefe
        ); 
      }
      else{
        $datos = array('idSolicitud'=>$this->usuarioPrincipal,
                       'autorizar'=>0,
                       'negacion'=>$this->motivo
                  ); 
      }
       
      $respuesta = PermisosControllers::actualizarFormularioSolicitudControllers2($datos);
      echo $respuesta;
    }

    public function detallesSolicitudUsuario(){
        $respuesta = PermisosControllers::detallesSolicitudUsuarioControllers($this->usuarioPrincipal);//id de permiso no de usuario
    }

    public function borrarPermisoUsuario(){
      $respuesta = PermisosControllers::borrarPermisoUsuario($this->usuarioPrincipal);
      echo json_encode($respuesta);
  }
    

    #paginador para que cada usuario vea sus solicitudes
    public function paginadorUsuarioSolicitante(){
        
      $paginacion = new Paginacion($this->registrosPorPagina);
      $paginacion->target($this->target);
      $totalRegistros = PermisosControllers::marcadoresPermisosUsuario($_SESSION["identificador"],-1);
      
     $paginacion->totalPaginas($totalRegistros);
     $paginacion->paginaActual($this->paginaActual);

     $mostrar =  $paginacion->mostrar();
     $permisos = new PermisosControllers();
     $data = $permisos->mostrarPermisosController($_SESSION["identificador"],$paginacion->limitRegistros());

     $respuesta = array("mostrar"=>$mostrar,"data"=>$data);
     echo json_encode($respuesta);
    }

    public function reponderCambioGuardia(){
      $respuesta = PermisosControllers::reponderCambioGuardia($this->tipoPermiso,$this->situacion);//id permiso, respuesta
      echo json_encode($respuesta);
    }

    public function cargarPermisos(){
      $respuesta = PermisosControllers::cargarPermisos($this->fecha,$this->usuarioPrincipal);
      echo json_encode(array('error'=>false,'disfrutadas'=>$respuesta['disfrutadas'],'bonos'=>$respuesta['bonos'],'permisos'=>$respuesta['permisos'],'faltas'=>$respuesta['faltas'],'porAutorizar'=>$respuesta['porAutorizar'],'disponibles'=>$respuesta['disponibles'],'usuario'=>$this->usuarioPrincipal));
    }

}

/*MOSTRAR EL TIPO DE PERMISO*/
/************************************/
if(isset($_POST["fechaPermiso"])){
    if(isset($_POST["tipoPermiso"])){
        $a = new PermisosAjax();
        if(isset($_POST["usuarioTicketCreado"]) && !empty($_POST["usuarioTicketCreado"]) )
            $a->usuarioPrincipal = $_POST["usuarioTicketCreado"];
        else
            $a->usuarioPrincipal = $_POST["usuario"];
        $a->fecha = $_POST["fechaPermiso"];
        $a->horarioInicio = $_POST["horarioInicio"];
        $a->horarioFin = $_POST["horarioFin"];
        $a->tipoPermiso = $_POST["tipoPermiso"];
        $a->motivo = $_POST["comentarios"];
        if(isset($_POST["extemporaneo"]))
          $a->extemporaneo = $_POST["extemporaneo"];
        if( isset($_FILES["cargarImagenPermiso"]["name"])){
          $a->imagenNombre = $_FILES["cargarImagenPermiso"]["name"];
          $a->imagenTipo = $_FILES["cargarImagenPermiso"]["type"];
          $a->imagenTemporal = $_FILES["cargarImagenPermiso"]["tmp_name"];
          $a->imagenTamano = $_FILES["cargarImagenPermiso"]["size"];
        }
        $a->permisos();
    }
    else 
      echo 0;
}

else if(isset($_POST["periodoVacacional"])){
  $b = new PermisosAjax();
  if(isset($_POST["usuarioTicketCreado"]) && !empty($_POST["usuarioTicketCreado"]) )
    $b->usuarioPrincipal = $_POST["usuarioTicketCreado"];
  else
    $b->usuarioPrincipal = $_POST["usuario"];
  //$b->usuarioPrincipal=$_POST["usuario"];
  $b->fecha = $_POST["periodoVacacional"];
  if(isset($_POST["extemporaneo"]))
      $b->extemporaneo = $_POST["extemporaneo"];
  $b->vacaciones();
}

else if(isset($_POST["cambioGuardia"])){
  $c = new PermisosAjax();
  if(isset($_POST["usuarioTicketCreado"]) && !empty($_POST["usuarioTicketCreado"]) )
      $c->usuarioPrincipal = $_POST["usuarioTicketCreado"];
  else
      $c->usuarioPrincipal = $_POST["usuario"];
  //$c->usuarioPrincipal = $_POST["usuario"];
  $c->fecha = $_POST["cambioGuardia"];
  $c->fecha2 = $_POST["cambioGuardia2"];
  $c->usuarioSecundario = $_POST["usuarioCambio"];
  if(isset($_POST["extemporaneo"]))
    $c->extemporaneo = $_POST["extemporaneo"];
  $c->guardias();
}

else if(isset($_POST["tipoSolicitud"])){
  $html='';
  if($_POST["tipoSolicitud"] == 1){
       $html.='
       <!-- primera fila -->
       <div class="form-group max800">
         <div class="row">
         <!-- primera columna -->
           <div class="col-xs-12">
             <label>2.-Tipo de permiso:</label> <i class="fa fa-check-circle text-green"></i>
           </div>
         </div>
       </div>


       <div class="max800 b">                      
           <!-- primera fila -->
             <div class="form-group">
               <div class="row">
               <!-- primera columna -->
                   <div class="col-xs-12">
                     <label class="sangriaPermisos">EC - Enfermedad comprobada:</label>
                   </div>
               </div>
               <div class="row">
               <!-- primera columna -->
                   <div class="col-md-6 col-xs-12">
                     <input type="radio" name="tipoPermiso" id="EC1" class="marcaRegistrada with-font" value="1"><label class="hola" for="EC1">01 - Justificante de IMSS</label>
                   </div>
                   <div class="col-md-6 col-xs-12">
                     <input type="radio" name="tipoPermiso" id="EC2" class="marcaRegistrada with-font" value="2"><label class="hola" for="EC2">02 - Justificante del médico particular</label>
                   </div>
               </div>
             </div>

               
                 <!-- primera fila -->
             <div class="form-group max800">
                 <div class="row">
                     <div class="col-xs-12">
                       <label class="sangriaPermisos">PE - Permiso:</label>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-6 col-xs-12">
                       <input type="radio" name="tipoPermiso" id="PE1" class="marcaRegistrada with-font" value="3"><label class="hola" for="PE1">01 - Día completo</label>
                     </div>

                     <div class="col-md-6 col-xs-12">
                       <input type="radio" name="tipoPermiso" id="PE2" class="marcaRegistrada with-font" value="4"><label class="hola" for="PE2">02 - Medío día</label>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-6 col-xs-12">
                       <input type="radio" name="tipoPermiso" id="PE3" class="marcaRegistrada with-font" value="5"><label class="hola" for="PE3">03 - Periodo de ausencia por horas</label>
                     </div>
                     <div class="col-md-6 col-xs-12">
                       <input type="radio" name="tipoPermiso" id="PE4" class="marcaRegistrada with-font" value="6"><label class="hola" for="PE4">04 - Salida temprano</label>
                     </div>
                 </div>
                 <div class="row">
                 <div class="col-md-6 col-xs-12">
                   <input type="radio" name="tipoPermiso" id="PE5" class="marcaRegistrada with-font" value="7"><label class="hola" for="PE5">05 - Bono bimestral</label>
                 </div>
             </div>
             </div>

                 <!-- primera fila -->
             <div class="form-group max800">
               <div class="row">
                 <div class="col-md-6 col-xs-12">
                   <input type="radio" name="tipoPermiso" id="LU" class="marcaRegistrada with-font" value="8"><label for="LU">LU - Luto</label>
                 </div>
                 <div class="col-md-6 col-xs-12">
                 <input type="radio" name="tipoPermiso" id="FI" class="marcaRegistrada with-font" value="9"><label for="FI">FI - Falta injustificada</label>
                 </div>
               </div>
               <div class="row">
                 <div class="col-md-6 col-xs-12">
                   <input type="radio" name="tipoPermiso" id="SU" class="marcaRegistrada with-font" value="10"><label for="SU">SU - Suspensión</label>
                 </div>
                 <div class="col-md-6 col-xs-12">
                  <input type="radio" name="tipoPermiso" id="PA" class="marcaRegistrada with-font" value="11"><label for="PA">PA - Paternidad</label>
                 </div>
               </div>
               <div class="row">
                 <div class="col-md-6 col-xs-12">
                   <input type="radio" name="tipoPermiso" id="MA" class="marcaRegistrada with-font" value="12"><label for="MA">MA - Maternidad</label>
                 </div>
                 <div class="col-md-6 col-xs-12">
                 </div>
               </div>
             </div>
       </div>


       
       <!-- Date and time range -->
       <div class="form-group max800">
         <label>3.-Fecha:</label> <i class="fa fa-check-circle text-green"></i>
         <div class="input-group">
           <div class="input-group-addon">
             <i class="fa fa-calendar"></i>
           </div>
           <input type="text" class="form-control pull-right" name="fechaPermiso" id="reservationtime" required>
         </div>
         <!-- /.input group -->
       </div>
       <!-- /.form group -->

       <!-- Date and time range -->
       <div class="form-group max800">
          <div class="row">
              <div class="col-md-6">
                  <label>4.-Horario inicio:</label> <i class="fa fa-check-circle text-green"></i>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="horarioInicio" id="timepicker" value="09:00 AM">
                  </div>
                  <!-- /.input group -->
              </div> 

              <div class="col-md-6">
                  <label>Horario fin:</label> <i class="fa fa-check-circle text-green"></i>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="horarioFin" id="timepicker2" value="07:00 PM">
                  </div>
                  <!-- /.input group -->
              </div> 
          </div>
       </div>
       <!-- /.form group -->


       <!-- primera fila -->
       <div class="form-group max800">
         <div class="row">
         <!-- primera columna -->
           <div class="col-xs-12">
             <label for="">5.-Motivo:</label> <i class="fa fa-check-circle text-green"></i>
             <textarea name="comentarios" class="form-control bloquear-textarea textoMay" rows="4" required></textarea>
           </div>
         </div>
       </div>

       <div class="form-group max800">
         <p><label class="container">Permiso extemporáneo <input type="checkbox" id="condicionesYterminos"> <span class="checkmark"></span> </label></p>
       </div>

       
      <div class="form-group max800">
         <div class="row">
           <div class="col-xs-12">
           <p class="callout callout-success" id="lienzoArchivosPermisos">Si lo deseas puedes adjuntar algún archivo en formato: .jpg, .jpeg, .png o .pdf</p>
            <span class="btn btn-default btn-lg btn-file"><i class="fa fa-file-o"></i> Adjuntar <input type="file" name="cargarImagenPermiso" id="cargarImagenPermiso"></span>   
           </div>
         </div>
       </div>

       <hr>
       <!-- primera fila -->
       <div class="form-group max800">
         <div class="row">
         <!-- primera columna -->
           <div class="col-xs-12">
              <p> <i class="fa fa-check-circle text-green"></i> Campos obligatorios.</p>
           </div>
         </div>
       </div>';
  }
  else if($_POST["tipoSolicitud"] == 2){
    $html.= '<div class="form-group max800">
         <label>2.-Periodo:</label> <i class="fa fa-check-circle text-green"></i>
         <div class="input-group">
           <div class="input-group-addon">
             <i class="fa fa-calendar"></i>
           </div>
           <input type="text" class="form-control pull-right" name="periodoVacacional" id="periodoVacacional" autocomplete="off">
         </div>
         <!-- /.input group -->
       </div>
       <div class="form-group max800">
         <p><label class="container">Permiso extemporáneo <input type="checkbox" id="condicionesYterminos"> <span class="checkmark"></span> </label></p>
       </div>';
  }
  else if ($_POST["tipoSolicitud"] == 3){
    $html.= 
    '<!--<div class="form-group max800">
      <label>2.-Día de guardia / cambio al día:</label> <i class="fa fa-check-circle text-green"></i>
      <div class="input-group">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        <input type="text" class="form-control pull-right" name="cambioGuardia" id="cambioGuardia">
      </div>
    </div>-->

   <div class="max800">
      <div class="row">
        <div class="col-md-6">
          <label>2.-Día que corresponde mi guardia:</label> <i class="fa fa-check-circle text-green"></i>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="date" class="form-control pull-right" name="cambioGuardia">
          </div>
        </div>
        <div class="col-md-6">
            <label>Día al que quiero cambiar mi guardia:</label> <i class="fa fa-check-circle text-green"></i>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="date" class="form-control pull-right" name="cambioGuardia2">
            </div>
        </div>
      </div>
    </div>
    
    <div class="form-group max800">
      <label>3.-Cambio de guardia con:</label> <i class="fa fa-check-circle text-green"></i>
      <div class="input-group">
        <div class="input-group-addon">
          <i class="fa fa-user"></i>
        </div>
        <select class="form-control textoMay" name="usuarioCambio" id="usuarioCambio" required>
          <option value=""></option>'.gestionUsuarios::usuariosPermutaControllers($_POST["idPrincipalPermuta"]).'
        </select>
      </div>
      <!-- /.input group -->
    </div>

    <div class="form-group max800">
        <p><label class="container">Permiso extemporáneo <input type="checkbox" id="condicionesYterminos"> <span class="checkmark"></span> </label></p>
    </div>
    
    <!--<div class="form-group max800">
    <div class="row">
        <div class="col-xs-12">
          <label>4.-Motivo:</label> <i class="fa fa-check-circle text-green"></i>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-12">
          <input type="radio" name="razonCambio" id="cambio1" class="marcaRegistrada with-font" value="0"><label class="hola" for="cambio1">Por convenir la operación</label>
        </div>

        <div class="col-md-6 col-xs-12">
          <input type="radio" name="razonCambio" id="cambio2" class="marcaRegistrada with-font" value="1"><label class="hola" for="cambio2">Fuerza mayor</label>
        </div>
    </div>-->
</div>
    
    ';
  }
  else{
    $html .= '';
  }
  
  echo $html;
}

else if(isset($_POST["cargarCalendario"])){
  $d = new PermisosAjax();
  $d->usuarioPrincipal=$_POST["cargarDatosId"];
  $d->actualizarCalendario();
}

else if(isset($_POST["actualizarSolicitudes"])){
  $e = new PermisosAjax();
  $e->usuarioPrincipal = $_POST["actualizarSolicitudes"];
  $e->mostrarSolicitudesPersonalAcargo(); //tambien extraigo aparte de las solicitudes, las notificaciones.
}

else if(isset($_POST["idRegistroPermiso"])){
  $f = new PermisosAjax();
  $f->usuarioPrincipal = $_POST["idRegistroPermiso"];
  $f->mostrarSolicitudesAjax();
}

else if(isset($_POST["tipoSolicitudMostrar"])){
  $g = new PermisosAjax();
  $g->tipoSolicitud = $_POST["tipoSolicitudMostrar"];
  $g->totalSolicitudes();
}

/*Filtros en solicitudes*/
/************************************/
else if(isset($_POST["buscadorPermisos"])){
	$h = new PermisosAjax();
	$h->nombreUsuarioPrincipal = $_POST["buscadorPermisos"];
	$h->situacion = $_POST["cargarSituacionSolicitudes"];
  $h->sucursal = $_POST["cargarSucursalSolicitudes"];
  $h->fecha = $_POST["cargarFechaSolicitudes"];
  $h->paginaActual = $_POST["paginaActual"];
  $h->registrosPorPagina = $_POST["registrosPorPagina"];
  $h->tipoUsuario = $_POST["tipoUsuario"];
  $h->usuarioPrincipal = $_POST["idUsuario"];
  $h->target = $_POST["target"];
  $h->buscarUsuarioPermisos();
}

/*AUtorizar o cancelar formulario RECURSOS HUMANOS*/
/**************************************/
else if(isset($_POST["idPermisoAutorizar"])){
	$i = new PermisosAjax();
  $i->usuarioPrincipal = $_POST["idPermisoAutorizar"];//id de permiso
  $i->autorizacion = $_POST["autorizarSolicitud"];
  if($i->autorizacion){
    $i->sueldo = $_POST["autorizarGoceSueldo"];
    $i->justificante = $_POST["presentarJustificante"];
    $i->fechaReincorporacion = $_POST["fechaReincorporacion"];
    $i->fechaSolicitada = $_POST["actualizarFechaSolicitada"];
    $i->fechaFinalizacion = $_POST["actualizarFechaFin"];
    $i->tipoPermiso = $_POST["tipo_permiso"];
    $i->usuarioSecundario = $_POST["idSolicitante"]; //id usuario
    if(isset($_POST["contarDiaSabado"]))
      $i->contarSabado = $_POST["contarDiaSabado"];
  }
  else{
     $i->motivo = $_POST["negacionPermiso"];
     $i->tipoPermiso = $_POST["tipo_permiso"];

  }
  $i->actualizarFormularioSolicitud();
}

/*AUtorizar o cancelar formulario JEFE*/
/**************************************/
else if(isset($_POST["idPermisoAutorizar2"])){
	$k = new PermisosAjax();
  $k->usuarioPrincipal = $_POST["idPermisoAutorizar2"];
  $k->autorizacion = $_POST["autorizarSolicitud2"];
  if(!$k->autorizacion){
     $k->motivo = $_POST["negacionPermiso2"];
  }
  if (isset($_POST["observacionesAutorizarPermiso"]))
    $k->comentarioJefe = $_POST["observacionesAutorizarPermiso"];
  $k->actualizarFormularioSolicitud2();
}

/*Mostrar detalles solicitud (cada usuario verifica los detalles de sus propias solicitudes)*/
/************************************/
else if(isset($_POST["detallesSolicitudUsuario"])){
  $j = new PermisosAjax();
  $j->usuarioPrincipal = $_POST["detallesSolicitudUsuario"];
  $j->detallesSolicitudUsuario();
}

/*Pagunador para que cada usuario vea sus solicitudes*/
/************************************/
else if(isset($_POST["banderaPaginadorUsuarioStandar"])){
	$l = new PermisosAjax();
  $l->paginaActual = $_POST["paginaActual"];
  $l->registrosPorPagina = $_POST["registrosPorPagina"];
  $l->target = $_POST["target"];
  $l->paginadorUsuarioSolicitante();
}

else if(isset($_POST['respuestaFormularioCambioGuardia'])){
  $m = new PermisosAjax();
  $m->situacion = $_POST['respuestaFormularioCambioGuardia'];
  $m->tipoPermiso = $_POST['idSolicitudCambioGuardia'];
  $m->reponderCambioGuardia();
}

else if(isset($_POST['borrarPermisoUsuario'])){
  $m = new PermisosAjax();
  $m->usuarioPrincipal = $_POST['borrarPermisoUsuario'];
  $m->borrarPermisoUsuario();
}


else if(isset($_POST['cargarPermisos'])){
  $m = new PermisosAjax();
  $m->fecha = $_POST['anio'];
  $m->usuarioPrincipal = $_POST['usuario'];
  $m->cargarPermisos();
}






