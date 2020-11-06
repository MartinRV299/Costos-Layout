<?php
require_once "models/enlaces.php";
require_once "models/Paqueteria.php";
require_once "models/ingreso.php";
require_once "models/permisos.php";
require_once "models/ConfiguracionesModel.php";
require_once "models/TicketsModel.php";
require_once "models/NominasModel.php";
require_once "models/CostosModel.php";
require_once "controllers/Reportes.php";
require_once "controllers/enlaces.php";
require_once "controllers/Paqueteria.php";
require_once "controllers/ingreso.php";
require_once "controllers/permisos.php";
require_once "controllers/ConfiguracionesController.php";
require_once "controllers/Tickets.php";
require_once "controllers/Nominas.php";
require_once "controllers/MetodoMartin.php";
require_once "controllers/ComprasController.php";
require_once "controllers/Costos.php";

require_once "controllers/MetodosDiversos.php";
require_once "models/config.php";
require_once "controllers/template.php";
require_once "controllers/ajaxPaginacion.php";
require_once "views/excel/vendor/autoload.php";



$template = new TemplateController();
$template -> template();
