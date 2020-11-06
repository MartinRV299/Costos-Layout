<?php
define('RUTA',"http://".$_SERVER["HTTP_HOST"]."/asesores/");
################################################Privilegios
define('ADMINISTRADOR',6);
define('CONTRALORIA',5);
define('GERENCIA',4);
define('JEFATURA',3);
define('RECEPCION',2);
define('ESTANDAR',1);
define('ESPECIAL',10);
###############################################Tablas
define('PERMISOS','permisos_ae');
define('JEFE','dependencias_jefe_ae');
define('RH','dependencias_rh_ae');
define('USUARIOS','usuarios_ae');
define('SUCURSALES','sucursales_ae');
define('BITACORA','bitacora_vacaciones_ae');
define('SEGMENTODESCARGA1','ruta_primer_segmento');
define('SEGMENTODESCARGA2','ruta_segundo_segmento');
define('INTERNOS','paquete_interno_ae');
define('EXTERNOS','paquete_externo_ae');
define('ESTADOS','estados_ae');
define('DEPARTAMENTOSINTRANET','departamentos_ae');
define('PUESTOS','puestos_ae');
define('PAQUETERIAS','paqueterias_ae');
define('DEPENDENCIASPAQUETERIA','dependencias_paqueteria_ae');
define('TICKETS','tickets_ae');
define('CONFIGURACIONES','usuarios_configuracion_ae');
define('TICKETSHISTORIAL','tickets_historial_apertura_ae');
define('TICKETSCATEGORIAS','tickets_categorias_ae');
define('TICKETSSUBCATEGORIAS','tickets_subcategorias_ae');
define('NUTRIFITNESS','nutrifitness');
define('ALIMENTACION','nutrifitness_alimentacion');
define('LABORATORIO','nutrifitness_laboratorio');
define('COMPOSICION','nutrifitness_composicion');
define('ESPECIALES','usuarios_especiales');
define('FISICA','nutrifitness_fisica_evaluacion');
define('FISICA2','nutrifitness_fisica_plan');
define('TALLERES','talleres_nutrifitness');
define('MENSAJEROS','mensajeros_internos_ae');
define('CREDENCIALES','credenciales_temporales_ae');
define('CURSOS','cursos_ae');
define('CURSANTES','cursos_usuarios_ae');
define('CLIENTES','nominas_clientes_ae');
define('EMPRESAS','nominas_empresas_asimilados_ae');
define('EMPRESAS2','nominas_empresas_imss_ae');
define('EMPRESAS3','nominas_empresas_facturadoras_ae');
define('NOMINAS','nominas_ae');
define('NOMINAS2','nominas_liberacion_ae');
define('PROMOTOR','nominas_promotor_ae');
define('SUBCOMISIONISTA','nominas_subcomisionista_ae');
define('CHAT','chat_ae');
define('CHATGRUPOS','chat_grupos_ae');
define('CHATINTEGRANTES','chat_grupos_integrantes_ae');
define('ENCUESTA','encuesta_giro_ae');
define('ENCUESTADOS','encuestados_giro_ae');
define('EMPRESASEXT','empresas_ae');
define('EMPRESASEXT2','empresas_sucursales_ae');
define('RESPONSABLES','empresas_responsables_ae');
define('VALORES','valores_ae');
define('SERVIDORES','credenciales_servidores_ae');
define('COSTOS','costos_ae');
define('COSTOS1','costos_clientes_ae');
define('COSTOS2','costos_nombre_comercial_ae');
define('COSTOS3','costos_promotor_ae');
define('COSTOS4','costos_subcomisionista_ae');
define('COSTOS5','costos_empresas_ae');
define('SOFTWARE','software_ae');
define('USUARIOSCLIENTES','relaciones_empleados_clientes');
define('INFORMATIVOCOSTOS','informativo_costos_ae');
define('CUENTAS','conciliacion_cuentas');
define('BANCOS','bancos_ae');
define('BENEFICIARIOS','conciliacion_beneficiarios');
define('CONCEPTOS','conciliacion_conceptos');
define('MOVIMIENTOS','conciliacion_clasificacion_movimientos');
define('CONCILIACION','conciliacion_ae');
define('CONCILIACIONEXTEMPORANEOS','conciliacion_extemporaneos_ae');
###############################################Tablas GIRO
define('EMPPRIN','Supervisor_giro.Empprin');
define('PATRONALES','Supervisor_giro.EMP_PATRONALES');
define('EMPDEP','Supervisor_giro.Empdep');
#############################################Llaves
define('FIRMA','$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
define('PASSWORD','$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

class Ruta{
    public static function ruta_server(){
        return RUTA;
    }
}

class Configuraciones{
    public static function administrador(){
        return ADMINISTRADOR; 
    }

    public static function contraloria(){
        return CONTRALORIA; 
    }

    public static function gerencia(){
        return GERENCIA; 
    }

    public static function jefatura(){
        return JEFATURA; 
    }

    public static function recepcion(){
        return RECEPCION; 
    }

    public static function estandar(){
        return ESTANDAR; 
    }

    public static function especial(){
        return ESPECIAL; 
    }
}

class Tablas{
    public static function permisos(){
        return PERMISOS;
    }

    public static function jefe(){
        return JEFE;
    }

    public static function rh(){
        return RH;
    }

    public static function usuarios(){
        return USUARIOS;
    }

    public static function sucursales(){
        return SUCURSALES;
    }

    public static function bitacora(){
        return BITACORA;
    }

    public static function segmentoDescarga1(){
        return SEGMENTODESCARGA1;
    }

    public static function segmentoDescarga2(){
        return SEGMENTODESCARGA2;
    }

    public static function paquetesInternos(){
        return INTERNOS;
    }

    public static function paquetesExternos(){
        return EXTERNOS;  
    }

    public static function estados(){
        return ESTADOS;  
    }

    public static function departamentosIntranet(){
        return DEPARTAMENTOSINTRANET;  
    }

    public static function puestos(){
        return PUESTOS;  
    }

    public static function paqueterias(){
        return PAQUETERIAS;  
    }

    public static function dependenciasPaqueteria(){
        return DEPENDENCIASPAQUETERIA;  
    }

    public static function tickets(){
        return TICKETS;  
    }

    public static function configuraciones(){
        return CONFIGURACIONES;  
    }
 
    public static function tickets_historial(){
        return TICKETSHISTORIAL;  
    }

    public static function tickets_categorias(){
        return TICKETSCATEGORIAS;  
    }

    public static function tickets_subcategorias(){
        return TICKETSSUBCATEGORIAS;  
    }

    public static function nutrifitness(){
        return NUTRIFITNESS;  
    }

    public static function alimentacion(){
        return ALIMENTACION;  
    }

    public static function laboratorio(){
        return LABORATORIO;  
    }

    public static function composicion(){
        return COMPOSICION;  
    }

    public static function especiales(){
        return ESPECIALES;  
    }

    public static function fisica_evaluacion(){
        return FISICA;  
    }

    public static function fisica_plan(){
        return FISICA2;  
    }

    public static function talleres(){
        return TALLERES;  
    }

    public static function mensajeros(){
        return MENSAJEROS;  
    }

    public static function credenciales(){
        return CREDENCIALES;  
    }

    public static function cursos(){
        return CURSOS;  
    }

    public static function cursantes(){
        return CURSANTES;  
    }

    public static function clientes(){
        return CLIENTES;  
    }

    public static function nominas(){
        return NOMINAS;  
    }

    public static function nominas_liberacion(){
        return NOMINAS2;  
    }
    
    public static function asimilados(){
        return EMPRESAS;  
    }

    public static function imss(){
        return EMPRESAS2;  
    }

    public static function facturadoras(){
        return EMPRESAS3;  
    }

    public static function promotor(){
        return PROMOTOR;  
    }

    public static function subcomisionista(){
        return SUBCOMISIONISTA;  
    }

    public static function chat(){
        return CHAT;  
    }

    public static function grupos(){
        return CHATGRUPOS;  
    }

    public static function integrantes(){
        return CHATINTEGRANTES;  
    }

    public static function encuesta_giro(){
        return ENCUESTA;  
    }

    public static function encuestados_giro(){
        return ENCUESTADOS;  
    }

    public static function empresas(){
        return EMPRESASEXT;  
    }

    public static function empresas_sucursales(){
        return EMPRESASEXT2;  
    }

    public static function responsables(){
        return RESPONSABLES;
    }

    public static function valores(){
        return VALORES;  
    }

    public static function servidores(){
        return SERVIDORES;  
    }

    public static function costos(){
        return COSTOS;  
    }

    public static function costos_clientes(){
        return COSTOS1;  
    }

    public static function costos_comercial(){
        return COSTOS2;  
    }

    public static function costos_promotor(){
        return COSTOS3;  
    }

    public static function costos_subcomisionista(){
        return COSTOS4;  
    }

    public static function costos_empresas(){
        return COSTOS5;  
    }

    public static function software(){
        return SOFTWARE;  
    }

    public static function usuario_cliente(){
        return USUARIOSCLIENTES;
    }

    public static function informativo_costos(){
        return INFORMATIVOCOSTOS;
    }

    public static function Ccuentas(){
        return CUENTAS;
    }

    public static function bancos(){
        return BANCOS;
    }

    public static function Cbeneficiarios(){
        return BENEFICIARIOS;
    }

    public static function Cconceptos(){
        return CONCEPTOS;
    }

    public static function Cmovimientos(){
        return MOVIMIENTOS;
    }

    public static function conciliacion(){
        return CONCILIACION;
    }

    public static function Cextemporaneos(){
        return CONCILIACIONEXTEMPORANEOS;
    }

}

class TablasGiro{

    public static function patronales(){
        return PATRONALES;
    }

    public static function empprin(){
        return EMPPRIN;
    }

    public static function empdep(){
        return EMPDEP;
    }
}

class Llaves{
    public static function firma($usuario){
        $respuesta = Datos::obtenerCurpModel($usuario,Tablas::usuarios());
        return crypt($respuesta,FIRMA);
    }

    public static function password($semilla){
        return crypt($semilla,PASSWORD);
    }
}

class AccesoSoporte{
    public static $acceso = array(
        "168"=>3, //uriel
        "441"=>3, //Martin
        "180"=>2, // salvador
        "223"=>1,//ulises
        "187"=>1, //rogelio
        "271"=>1//Juan
    ); 

    /*public static $soporte = array(
        "Uriel"=>168, 
        "Miguel"=>200, 
        "Salvador"=>180, 
        "Ulises"=>223, 
        "Rogelio"=>187,
        "Juan"=>271
    ); */
    

    public static function usuarios($elemento){//categoria que atiende 
        return self::$acceso[$elemento];
    }

    public static function idUsuarios($elemento){
        return self::$soporte[$elemento];
    }

    public static function perteneceAsoporte($elemento){
        if(array_key_exists($elemento,self::$acceso))
            return true;
        else
            return false;
    }
}

class AccesoReclutamiento{
    public static $acceso = array(
        "198"=>true, //Andrea hernandez
        "228"=>true, //Irma Jeanette
        "360"=>true //Kenia del Carmen
    ); 

    public static function perteneceReclutamiento($elemento){
        if(array_key_exists($elemento,self::$acceso))
            return true;
        else
            return false;
    }
}

class AccesoNoticias{
    public static $acceso = array(
        "387"=>true, //Isabel
        "357"=>true //Gaby-Nutriologa
    ); 

    public static function perteneceNoticias($elemento){
        if(array_key_exists($elemento,self::$acceso))
            return true;
        else
            return false;
    }
}

class AccesoEspecialPaqueteria{
    public static $acceso = array(
        "172"=>true,
        "168"=>true
    ); 

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$acceso))
            return true;
        else
            return false;
    }
}

class AccesoRHespecial{
    
    public static $acceso = array(
        "390"=>'10,21,25',
        "1680"=>'10,18,20,21'
    ); 

    public static function pertenece($elemento,$boleano = false){
        
        if($boleano){
            if(array_key_exists($elemento,self::$acceso))
                return true;
            else
                 return false;
        }

        if(array_key_exists($elemento,self::$acceso))
            return self::$acceso[$elemento];
        else
            return false;
    }
}


class AccesoGuadalajara{
    public static $grupoGuadalajara = array(
        '168'=>true,
        '171'=>true,
        '172'=>true,
        '175'=>true,
        '179'=>true,
        '180'=>true,
        '181'=>true,
        '182'=>true,
        '185'=>true,
        '187'=>true,
        '188'=>true,
        '190'=>true,
        '191'=>true,
        '193'=>true,
        '194'=>true,
        '195'=>true,
        '199'=>true,
        '201'=>true,
        '202'=>true,
        '204'=>true,
        '206'=>true,
        '207'=>true,
        '208'=>true,
        '215'=>true,
        '216'=>true,
        '217'=>true,
        '218'=>true,
        '219'=>true,
        '222'=>true,
        '223'=>true,
        '224'=>true,
        '225'=>true,
        '241'=>true,
        '243'=>true,
        '245'=>true,
        '248'=>true,
        '305'=>true,
        '307'=>true,
        '365'=>true,
        '368'=>true,
        '381'=>true,
        '387'=>true,
        '388'=>true,
        '394'=>true,
        '398'=>true,
        '400'=>true,
        '402'=>true,
        '403'=>true,
        '409'=>true,
        '232'=>true,
        '240'=>true,
        '252'=>true,
        '359'=>true,
        '393'=>true,
        '401'=>true,
        '174'=>true,
        '176'=>true,
        '177'=>true,
        '197'=>true,
        '354'=>true,
        '198'=>true,
        '228'=>true,
        '389'=>true,
        '235'=>true,
        '239'=>true,
        '212'=>true,
        '230'=>true,
        '233'=>true,
        '237'=>true,
        '242'=>true,
        '251'=>true,
        '324'=>true,
        '325'=>true,
        '346'=>true,
        '356'=>true,
        '374'=>true,
        '196'=>true,
        '238'=>true,
        '410'=>true,
        '210'=>true,
        '231'=>true,
        '203'=>true,
        '323'=>true        
    ); 

    public static function pertenecePrograma($elemento){
        if(array_key_exists($elemento,self::$grupoGuadalajara))
            return true;
        else
            return false;
    }
}



class AccesoNutrifitness{
    public static $grupoGuadalajara = array(
        '168'=>true,
        '171'=>true,
        '172'=>true,
        '175'=>true,
        '179'=>true,
        '180'=>true,
        '181'=>true,
        '182'=>true,
        '184'=>true,
        '185'=>true,
        '187'=>true,
        '188'=>true,
        '189'=>true,
        '190'=>true,
        '191'=>true,
        '193'=>true,
        '195'=>true,
        '196'=>true,
        '197'=>true,
        '198'=>true,
        '199'=>true,
        '200'=>true,
        '202'=>true,
        '204'=>true,
        '206'=>true,
        '207'=>true,
        '208'=>true,
        '210'=>true,
        '212'=>true,
        '215'=>true,
        '217'=>true,
        '218'=>true,
        '219'=>true,
        '223'=>true,
        '224'=>true,
        '225'=>true,
        '228'=>true,
        '231'=>true,
        '232'=>true,
        '233'=>true,
        '235'=>true,
        '236'=>true,
        '237'=>true,
        '239'=>true,
        '240'=>true,
        '241'=>true,
        '242'=>true,
        '243'=>true,
        '244'=>true,
        '245'=>true,
        '246'=>true,
        '248'=>true,
        '249'=>true,
        '251'=>true,
        '305'=>true,
        '307'=>true,
        '324'=>true, 
        '346'=>true,
        '353'=>true,
        '360'=>true,
        '362'=>true,  //borrar
        '368'=>true
    ); 


    public static $grupoMonterrey = array(
        //'269'=>true,
        '270'=>true,
        '271'=>true,
        '276'=>true,
        '277'=>true,
        '279'=>true,
        '281'=>true,
        '282'=>true,
        '283'=>true,
        '285'=>true,
        '287'=>true,
        '288'=>true,
        '291'=>true,
        '343'=>true,
        '351'=>true,
        '355'=>true,
        '366'=>true
    ); 

    public static $grupoMexico = array(
        '201'=>true,
        '250'=>true,
        '256'=>true,
        '257'=>true,
        '259'=>true,
        '261'=>true,
        '265'=>true,
        '336'=>true,
        '348'=>true
    ); 

    public static $grupoVallarta = array(
        '314'=>true,
        '345'=>true,
        '295'=>true,
        '315'=>true,
        '304'=>true,
        '312'=>true,
        '308'=>true,
        '309'=>true,
        '313'=>true
    );

    public static function pertenecePrograma($elemento){
        if(array_key_exists($elemento,self::$grupoGuadalajara))
            return true;
        if(array_key_exists($elemento,self::$grupoMonterrey))
            return true;
        if(array_key_exists($elemento,self::$grupoMexico))
            return true;
        if(array_key_exists($elemento,self::$grupoVallarta))
            return true;
        else
            return false;
    }
}

class GrupoNominas{

    public static $usuarios = array(
        '168'=>array(true)
    );

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            return false;
    }

    public static $usuarios2 = array(
        '295'=>array(true),//Dalia
        '195'=>array(true),//Keila
        '188'=>array(true),//cuahu
        '368'=>array(true),//Edgar Alejandro
        '201'=>array(true),//Leti Rios
        '253'=>array(true),//Gelmy
        '264'=>array(true),//Vanessa
        '254'=>array(true),//Fatima
        '391'=>array(true),//Maria Ontiveros
        '312'=>array(true),//Elena
        '313'=>array(true),//Sandra
        '343'=>array(true),//Daniela
        '283'=>array(true),//Ruth Guadalupe
        '277'=>array(true),//Irasema
        '330'=>array(true),//Arturo Alejandro
        '270'=>array(true),//Esmeralda
        '375'=>array(true),//Johana Elizabeth
        '394'=>array(true),//Josue Daniel
        '179'=>array(true),//Laura Bernal
        '225'=>array(true),//Bono
        '403'=>array(true),//Rosa
        '305'=>array(true),//Yolanda
        '241'=>array(true),//Brenda
        '372'=>array(true),//Alexis
        '265'=>array(true),//Omar
        '191'=>array(true),//Karen
        '365'=>array(true),//Briseida
        '288'=>array(true),//Xochitl
        '308'=>array(true),//Norma Leticia
        '185'=>array(true),//Flor
        '168'=>array(true),
        '187'=>array(true),//rogelio
        '416'=>array(true),//Carlos Valente
        '396'=>array(true),
        '348'=>array(true),
        '215'=>array(true)
        
    );

    public static function data(){
        return self::$usuarios2;
    }

    public static function pertenece2($elemento){
        if(array_key_exists($elemento,self::$usuarios2))
            return true;
        else 
            return false;
    }

}

class GrupoEmpresas{

    public static $usuarios = array(
        '201'=>array(false),
        '359'=>array(true),
        '185'=>array(false),
        '252'=>array(true),
        '212'=>array(false),
        '240'=>array(true),
        '232'=>array(true)
    );

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            return false;
    }

    public static function privilegios($elemento){
        return self::$usuarios[$elemento][0];
    }

}

class GrupoFinanzas{

    public static $usuarios = array(
        '201'=>array(true),//Leticia Magaña
        '308'=>array(true),//norma leticia
        '415'=>array(true),//Angelica
        '288'=>array(true),//Xochitl
        '287'=>array(true),//GRISEL ALMA 
        '269'=>array(true),//ANDRES
        '279'=>array(true),//ANA MARLEN
        '405'=>array(true),//SHERLYN GISELLE 
        '253'=>array(true),//Gelmy
        '307'=>array(true),
        '409'=>array(true),
        '368'=>array(true),
        '185'=>array(true),
        '188'=>array(true),
        '216'=>array(true),
        '199'=>array(true),
        '398'=>array(true),
        '250'=>array(true),
        '257'=>array(true),
        '262'=>array(true),
        '261'=>array(true),
        '406'=>array(true),
        '264'=>array(true),
        '407'=>array(true),
        '292'=>array(true),
        '172'=>array(true),
        '248'=>array(true)//Hugo Rabago 
    );

    public static function data(){
        return self::$usuarios;
    }

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            false;
    }
}

class GrupoTesoreria{

    public static $usuarios = array(
        '201'=>array(true),//Leticia Magaña
        '308'=>array(true),//norma leticia
        '415'=>array(true),//Angelica
        '288'=>array(true),//Xochitl
        '287'=>array(true),//GRISEL ALMA 
        '269'=>array(true),//ANDRES
        '279'=>array(true),//ANA MARLEN
        '405'=>array(true),//SHERLYN GISELLE 
        '181'=>array(true),//Beatriz 
        '243'=>array(true),//Monica
        '193'=>array(true),//Hugo frutasio
        '248'=>array(true),//Hugo Rabago 
        '400'=>array(true),//Jocelyn
        '253'=>array(true),//Gelmy
        '368'=>array(true),//Edgar Alejandro
        '250'=>array(true),//ANA MARLEN
        '457'=>array(true),//SHERLYN GISELLE 
        '262'=>array(true),//Beatriz 
        '261'=>array(true),//Monica
        '406'=>array(true),//Hugo frutasio
        '264'=>array(true),//Vanesa
        '407'=>array(true),//Jocelyn
        '292'=>array(true),//Gelmy
        '257'=>array(true),//Gelmy
        '405'=>array(true),//Gelmy
        '283'=>array(true)//Ruth Guadalupe
    );

    public static function data(){
        return self::$usuarios;
    }

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            false;
    }

}

class GrupoAtencionComercial{

    public static $usuarios = array(
        '175'=>array(true),
        '190'=>array(true),
        '202'=>array(true),
        '206'=>array(true),
        '217'=>array(true),
        '224'=>array(true),
        '245'=>array(true),
        '255'=>array(true),
        '256'=>array(true),
        '259'=>array(true),
        '276'=>array(true),
        '282'=>array(true),
        '290'=>array(true),
        '314'=>array(true),
        '315'=>array(true),
        '321'=>array(true),
        '328'=>array(true),
        '331'=>array(true),
        '334'=>array(true),
        '379'=>array(true),
        '388'=>array(true),
        '397'=>array(true),
        '182'=>array(true),
        '194'=>array(true),
        '204'=>array(true),
        '285'=>array(true),
        '381'=>array(true),
        '418'=>array(true)
    );

    public static function data(){
        return self::$usuarios;
    }

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            false;
    }

}

class GrupoFacturacion{

    public static $usuarios = array(
        '253'=>array(true),//Gelmy
        '201'=>array(true),//Leticia Magaña
        '257'=>array(true),//Gladys
        '307'=>array(true),//Liliana
        '199'=>array(true),//marcela
        '287'=>array(true),//grisel
        '269'=>array(true),//Gladys
        '308'=>array(true),//Liliana
        '304'=>array(true),//Angelica
        '288'=>array(true),//Xotchil
        '398'=>array(true),//Valeria
        '185'=>array(true),//Flor
        '415'=>array(true),//Karla
        '188'=>array(true)//Cuhautemoc
    );

    public static function data(){
        return self::$usuarios;
    }

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            false;
    }

}

class GrupoLiberacion{

    public static $usuarios = array(
        //'201'=>array(true),//Leticia Magaña
        //'308'=>array(true),//norma leticia
        //'288'=>array(true),//Xochitl
        //'287'=>array(true),//GRISEL ALMA 
        //'269'=>array(true),//ANDRES
        //'279'=>array(true),//ANA MARLEN
        //'405'=>array(true),//SHERLYN GISELLE */
        //'181'=>array(true),//Beatriz 
        //'243'=>array(true),//Monica
        //'193'=>array(true),//Hugo frutasio
        //'248'=>array(true),//Hugo Rabago 
        //'400'=>array(true),//Jocelyn
        //'368'=>array(true),//Edgar Alejandro
        //'253'=>array(true),//Gelmy
        //'264'=>array(true),//Vanesa
        //'307'=>array(true),
        //'409'=>array(true),
        //'185'=>array(true),
        //'188'=>array(true),
        //'216'=>array(true),
        //'199'=>array(true),
        //'398'=>array(true),
        //'250'=>array(true),
        //'257'=>array(true),
        //'262'=>array(true),
        //'261'=>array(true),
        //'406'=>array(true),
        //'407'=>array(true),
        //'292'=>array(true),
        //'304'=>array(true),
        //'172'=>array(true)
        //'283'=>array(true),//Ruth Guadalupe
    );

    public static function data(){
        return GrupoNominas::$usuarios2 + GrupoTesoreria::$usuarios + GrupoFinanzas::$usuarios + GrupoAtencionComercial::$usuarios;
    }

    public static function pertenece($elemento){
        if(array_key_exists($elemento, GrupoNominas::$usuarios2 +  GrupoTesoreria::$usuarios + GrupoFinanzas::$usuarios + GrupoAtencionComercial::$usuarios))
            return true;
        else 
            return false;
    }

}

class GrupoCapturaConfidenciales{

    public static $usuarios = array(
        '168'=>array(true)
    );

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            return false;
    }
}

class GrupoCostos{

    public static $usuarios = array(
        '201'=>array(false,NULL),//Leticia Magaña
        '195'=>array(false,'nominas'),
        '239'=>array(false,'gm'),
        '238'=>array(true,'imss'),//Sugey
        '168'=>array(true,'gm')//Sugey
    );

    public static function data(){
        return self::$usuarios;
    }

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            false;
    }

    public static function privilegios($elemento){
        return self::$usuarios[$elemento][0];
    }

    public static function modulo($elemento){
        return self::$usuarios[$elemento][1];
    }

}

class GrupoConciliacion{

    public static $usuarios = array(
        '201'=>array(false)//Leticia Magaña
    );

    public static function data(){
        return self::$usuarios;
    }

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            false;
    }

    public static function privilegios($elemento){
        return self::$usuarios[$elemento][0];
    }

}

/*class GrupoVisualizaConfidenciales{

    public static $usuarios = array(

    );

    public static function pertenece($elemento){
        if(array_key_exists($elemento,self::$usuarios))
            return true;
        else 
            return false;
    }
}*/


class NotificacionesConfig{

    public static function tipo($tipo){
        switch($tipo){
            case 1:
                return self::revisarSolictudes();
            break;
            case 2:
                return self::encuestaGiro();
            break;
        }
    }



    public static function revisarSolictudes(){
        return '<div class="modal bd-example-modal-lg fade" id="ventanaModalNotificaciones" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header" style="background:#28a745;color:#fff;">
                                <div class="modal-title col-md-8">
                                    <h3><b>Notificaciones del sistema</b></h3>
                                </div>
                                <div class="modal-title col-md-4">
                                    <img src="'.Ruta::ruta_server().'views/img/asesores.png" alt="">
                                </div>
                            </div>


                            <div class="modal-body estilos-centrar">
                            
                                    <p>Hola <span style="font-size:18px;">  '.$_SESSION['usuario'].' </span> <br><br> <b> No olvides evaluar las solicitudes que tienes pendientes del personal de tu departamento, sino te aparecen en el icono de notificaciones deberas buscarlas en situación de vistas en el módulo de solicitudes.</b></p>
                                            
                            
                            </div>

                            
                            <div class="modal-footer">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary botonEnteradoNotificaciones">Enterado.</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>';
    }


    public static function avisosGenerales(){
        return '<div class="modal bd-example-modal-lg fade" id="ventanaModalNotificaciones" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header" style="background:#28a745;color:#fff;">
                                <div class="modal-title col-md-8">
                                    <h3><b>Notificaciones del sistema</b></h3>
                                </div>
                                <div class="modal-title col-md-4">
                                    <img src="'.Ruta::ruta_server().'views/img/asesores.png" alt="">
                                </div>
                            </div>


                            <div class="modal-body estilos-centrar">
                            
                                    <p>Hola <span style="font-size:18px;">  '.$_SESSION['usuario'].' </span> <br> <b>te esperamos el día de la posada, ¡¡No Faltes!!</b></p>
                                    <hr>
                                    <img src="'.Ruta::ruta_server().'views/img/invitacionPosada272626762.gif" class="img-responsive center-block" alt="">          
                            
                            </div>

                            
                            <div class="modal-footer">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary botonEnteradoNotificaciones">Enterado.</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>';
       /* return '<div class="modal fade bd-example-modal-lg fade" id="ventanaModalNotificaciones" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header" style="background:#28a745;color:#fff;">
                                <div class="modal-title col-md-8">
                                    <h3><b>Notificaciones del sistema</b></h3>
                                </div>
                                <div class="modal-title col-md-4">
                                    <img src="'.Ruta::ruta_server().'views/img/asesores.png" alt="">
                                </div>
                            </div>


                            <div class="modal-body">
                               
                                    <h3 class="estilos-centrar"> ¡Hola '.$_SESSION['usuario'].'!</h3>
                                    <hr>
                                    <p>Te informamos que ya puedes descargar tus comprobantes de nómina desde el módulo <b>MI CUENTA</b>.</p>            
                               
                            </div>

                            
                            <div class="modal-footer">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary botonEnteradoNotificaciones">Enterado.</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>';*/
    }

    public static function encuestaGiro(){

        $iniciar = '<div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <p style="font-size:25px;text-align:center;">
                                Asesores Empresariales agradece su participación en la realización de la siguiente encuesta con el propósito de mejorar los procesos de trabajo aplicados a bien de la empresa y sus colaboradores.
                            </p>
                            <p style="text-align:center;">
                                <img src="'. Ruta::ruta_server().'views/img/logo-giro.png" alt="">
                            </p>
                        </div>
                    </div>';


        return '<div class="modal fullscreen-modal fade bd-example-modal-lg fade" id="ventanaModalNotificaciones" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        
                            <div class="modal-header" style="background:#28a745;color:#fff;">
                                
                                <div class="modal-title col-md-4">
                                    <h3><b>Encuesta GIRO</b></h3>
                                </div>

                                <div class="modal-title col-md-4" style="display:flex;align-items;justify-content: center;">
                                    <span id="temporizadorEncuesta" style="background:rgba(0,0,0,0.5);padding:15px;font-size:25px;border:2px solid #000;"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                                </div>

                                <div class="modal-title col-md-4 text-right">
                                    <img src="'. Ruta::ruta_server().'views/img/asesores.png" alt="">
                                </div>
                            </div>

                            <div class="modal-body" id="areaContenido">
                                    '.$iniciar.'
                            </div>

                            <div class="estilos-centrar" style="margin-bottom: 15px;">
                                <button class="btn btn-primary btn-lg" href="#" id="finalizarEncuesta">Finalizar encuesta</button>
                            </div>

                            <div class="modal-footer estilos-centrar limpiardiv">

                                    <div id="areaBotonEncuesta">
                                        <button class="btn btn-primary btn-lg" href="#" id="iniciarEncuesta">
                                            Iniciar encuesta
                                        </button>
                                    </div>

                                    <div id="areaBotonesNavegacion">
                                        <span id="preguntaActual" style="font-size:18px;"></span>
                                        <br>
                                        <button class="btn btn-success" href="#" id="anterior">
                                            <i class="fa fa-arrow-circle-o-left fa-2x"></i> Anterior
                                        </button>
                                        <button class="btn btn-success" href="#" id="siguiente">
                                            Siguiente <i class="fa fa-arrow-circle-o-right fa-2x"></i>
                                        </button>

                                    </div>

                            </div>
                        </div>
                    </div>
                </div>';
    }

}




