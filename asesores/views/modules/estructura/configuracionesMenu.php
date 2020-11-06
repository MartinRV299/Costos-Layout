<?php $activar = $_SERVER['REQUEST_URI'];
$inicio=$agenda=$paqueteria=$solicitudes=$rh=$sistemas=$giro=$tickets=$ticketsAdministrador=$eventos=$tutoriales=$empresas=$nominas=$finanzas=$tesoreria=$liberacion=$facturacion=$ayuda=$moduloTickets=$controlOperaciones=$proyectos='';
$paqueteNuevo=$paqueteAdministrar=$personal=$sucursales=$paginaInicio=$reportes=$nutrifitness=$inventario=$nutriRh=$cursos=$reconocimientos=$moduloNominas=$clientes=$costos=$gastos=$software=$conexiones=$conciliacion=$espera=$desarrollo=$prueba="";
$personalNuevo=$personalAdministrar=$sucursal=$departamento=$puesto=$noticiasEventos=$inventarioNuevo=$inventarioAdministrar=$participantesNutri=$reportesNutri='';
$sucursalNueva=$sucursalAdministrar=$departamentoNuevo=$departamentoVincular=$departamentoEliminar=$puestoNuevo=$puestoVincular=$puestoEliminar='';


switch($activar){
  case '/asesores/inicio':
    $inicio='active';
  break;
  case '/asesores/correos':
    $agenda='active';
  break;
  case '/asesores/paqueteriaCaptura':
    $paqueteria='active';
    $paqueteNuevo='active';
  break;
  case '/asesores/paqueteriaRevision':
    $paqueteria='active';
    $paqueteAdministrar='active';
  break;
  case '/asesores/solicitudes':
    $solicitudes='active';
  break;
  case '/asesores/usuarios':
    $rh='active';
    $personal='active';
    $personalNuevo='active';
  break;
  case '/asesores/usuariosAdministrar':
    $rh='active';
    $personal='active';
    $personalAdministrar='active';
  break;
  case '/asesores/sucursales':
    $rh='active';
    $sucursales='active';
    $sucursal='active';
    $sucursalNueva='active';
  break;
  case '/asesores/sucursalesAdministrar':
    $rh='active';
    $sucursales='active';
    $sucursal='active';
    $sucursalAdministrar='active';
  break;
  case '/asesores/departamento':
    $rh='active';
    $sucursales='active';
    $departamento='active';
    $departamentoNuevo='active';
  break;
  case '/asesores/vincularDepartamento':
    $rh='active';
    $sucursales='active';
    $departamento='active';
    $departamentoVincular='active';
  break;
  case '/asesores/eliminarDepartamento':
    $rh='active';
    $sucursales='active';
    $departamento='active';
    $departamentoEliminar='active';
  break;
  case '/asesores/puesto':
    $rh='active';
    $sucursales='active';
    $puesto='active';
    $puestoNuevo='active';
  break;
  case '/asesores/vincularPuesto':
    $rh='active';
    $sucursales='active';
    $puesto='active';
    $puestoVincular='active';
  break;
  case '/asesores/eliminarPuesto':
    $rh='active';
    $sucursales='active';
    $puesto='active';
    $puestoEliminar='active';
  break;
  case '/asesores/gestorNoticiasEventos':
    $rh='active';
    $paginaInicio='active';
    $noticiasEventos='active';
  break;
  case '/asesores/reportesRecursosHumanos':
    $rh='active';
    $reportes='active';
  break;
  case '/asesores/listaNutrifitness':
    $rh='active';
    $nutriRh='active';
    $participantesNutri='active';
  break;
  case '/asesores/resultadosNutrifitness':
    $rh='active';
    $nutriRh='active';
    $reportesNutri='active';
  break;
  case '/asesores/equipos':
    $sistemas='active';
    $inventario='active';
    $inventarioNuevo='active';
  break;
  case '/asesores/equiposAdministrar':
    $sistemas='active';
    $inventario='active';
    $inventarioAdministrar='active';
  break;
  case '/asesores/giro':
    $giro='active';
  break;
  case '/asesores/ticketNuevo':
    $tickets='active';
    $moduloTickets='active';
  break;
  case '/asesores/ticket':
    $ticketsAdministrador='active';
    $moduloTickets='active';
  break;
  case '/asesores/nutrifitness':
    $eventos='active';
    $nutrifitness='active';
  break;
  case '/asesores/cursos':
    $eventos='active';
    $cursos='active';
  break;
  case '/asesores/tutoriales':
    $tutoriales='active';
  break;
  case '/asesores/nominas':
    $nominas='active';
    $moduloNominas='active';
    $controlOperaciones='active';
  break;
  case '/asesores/finanzas':
    $finanzas='active';
    $moduloNominas='active';
    $controlOperaciones='active';
  break;
  case '/asesores/tesoreria':
    $tesoreria='active';
    $moduloNominas='active';
    $controlOperaciones='active';
  break;
  case '/asesores/liberacion':
    $liberacion='active';
    $controlOperaciones='active';
    $moduloNominas='active';
  break;
  case '/asesores/facturacion':
    $facturacion='active';
    $controlOperaciones='active';
    $moduloNominas='active';
  break;
  case '/asesores/empresas':
    $controlOperaciones='active';
    $empresas='active';
  break;
  case '/asesores/clientes':
    $controlOperaciones='active';
    $clientes='active';
  break;
  case '/asesores/reconocimientos':
    $eventos='active';
    $reconocimientos='active';
  break;
  case '/asesores/linea-ayuda':
    $ayuda='active';
  break;
  case '/asesores/gastos':
    $controlOperaciones='active';
    $proyectos='active';
    $prueba='active';
    $gastos='active';
  break;
  


}
