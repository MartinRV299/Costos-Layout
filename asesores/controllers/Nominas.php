<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class Nominas{

    private static $fileType = array('pdf','PDF','Pdf','xml','XML','Xml');
    private static $fileType2 = array('pdf','PDF','Pdf');

    private static $validacion = array( '1'=>array(true,false,false,true,true, true,true, true, true,false,false,false,false,true ,true ,true, true ,true),
                                        '2'=>array(true,false,false,true,true, true,true, true, true,false,false,false,false,false,false ,true, true ,true),
                                        '3'=>array(true,true, false,true,true, true,true, true, true,false,false,false,false,false,false ,true, true ,true),
                                        '4'=>array(true,false,false,true,true, true,true, true, true,false,false,true, true, false,false,true, true ,true),
                                        '5'=>array(true,false,false,true,true, true,true, true, true,false,false,false,false,false,false,false,false,true),
                                        '6'=>array(true,false,false,true,false,true,false,true, true,false,false,false,false,false,false,false,false,false));

    
    private static $validacion2 = array('1'=>array(true,false,false,true,true, true,true, true, true,false,false,true ,true ,true, true ,true),
                                        '2'=>array(true,false,false,true,true, true,true, true, true,false,false,false,false ,true, true ,true),
                                        '3'=>array(true,true, false,true,true, true,true, true, true,false,false,false,false ,true, true ,true),
                                        '4'=>array(true,false,false,true,true, true,true, true, true,true, true, false,false,true, true ,true),
                                        '5'=>array(true,false,false,true,true, true,true, true, true,false,false,false,false,false,false,true),
                                        '6'=>array(true,false,false,true,false,true,false,true, true,false,false,false,false,false,false,false));


    public static function mostrarSelect($id,$tabla){
       $select='';
        $respuesta = NominasModel::mostrarSelect($tabla);
        foreach($respuesta as $row => $item){
            if($id == $item["id"])
                $select.='<option value="'.$item["id"].'" selected="selected">'.$item["nombre"].'</option>';
            else
                $select.='<option value="'.$item["id"].'">'.$item["nombre"].'</option>';
        }
        return $select;
    }

    public static function mostrarNoministas(){
        $select='';
         $respuesta = NominasModel::mostrarNoministas(Tablas::usuarios());
         foreach($respuesta as $row => $item){
            if($_SESSION['identificador'] == $item["id_usuario"])
                $select.='<option value="'.$item["id_usuario"].'" selected="selected">'.$item["nombre"].'</option>';
            else
                $select.='<option value="'.$item["id_usuario"].'">'.$item["nombre"].'</option>';
         }
                
         return $select;
    }

    public static function Imprimir(){
      //  $cadena ="";
       echo "hola si imprime la funcion";
        //return $cadena;
        
     
        }

    public static function mostrarNoministas2($ruta){
        $cadena='';
        $index=1;
         $respuesta = NominasModel::mostrarNoministas2(Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos(),$ruta);
         foreach($respuesta as $row => $item){

            $cadena .='<div class="renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'">
                        <div class="campoId">'.$index++.'</div>
                        <div class="campoNombre" style="justify-content: flex-start;">'.$item["nombre"].'</div>
                        <div class="campoSucursal" style="justify-content: flex-start;">'.$item["sucursal"].'</div>
                        <div class="campoPuesto" style="justify-content: flex-start;">'.$item["puesto"].'</div>
                    </div>';
         }
                
         return $cadena;
    }

    public static function verificarJefatura(){
        $respuesta = NominasModel::verificarJefatura(Tablas::usuarios());
        $sql = '';
		if($respuesta > 2){
            $respuesta = NominasModel::mostrarNoministasLista(Tablas::usuarios(),Tablas::jefe());
            foreach($respuesta as $row => $item)
                $sql.='<option value="'.$item["id_usuario"].'">'.$item["nombre"].'</option>';
            $sql.='<option value="100">TODOS</option>';
        }  
        return $sql;
    }

    public static function registrarNomina($data){

        if($data['nomina_origen'] !== NULL){
            if(!preg_match('/^[0-9]{1,}$/',$data['nomina_origen']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El número de origen de la nómina no es correcto'));
        }

        if($data['id_nomina'] !== NULL){
            if(!preg_match('/^[0-9]{1,}$/',$data['id_nomina']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El id de la nómina no es correcto'));
        }

        if($data['id_nomina'] !== NULL){
            if( NominasModel::verificarStatusFinanzas($data['id_nomina'],Tablas::nominas_liberacion()) > 1)
                 return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ya no puedes actualizar la nómina debido a que ya fue liberada o cancelada por finanzas, tendrás que ponerte en contacto con dicho departamento para que la ponga en situación de pendiente para que puedas actualizar, en caso de que únicamente quieras cargar archivos tendrás que hacerlo desde el módulo: Cargar comprobantes bancarios del cliente(se encuentra en la pestaña: Cargar-Descargar archivos).'));
        }
        
        if($data['tipo_sindicato'] !== NULL || $data['tipo_esquema'] == "3"){
            if(!preg_match('/^[1-3]{1}$/',$data['tipo_sindicato']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El campo pagadora sindicato no es correcto'));
        }

        if(!preg_match('/^[0-9]{1,3}$/',$data['id_cliente']))
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar el nombre de cliente'));
        
        if(!preg_match('/^[0-1]{1}$/',$data['devengada']))
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar si la nómina es devengada o no'));
      
        if(  intval($data['tipo_esquema']) < 6  ) {
            if(!preg_match('/^[0-9]{1,2}$/',$data['tipo_pago']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar el tipo de pago'));
        }

        if(  intval($data['tipo_esquema']) != 7  ) {
            if(!preg_match('/^[0-9]{1}$/',$data['regimen']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar el regimen'));
        }

        if(!empty($data['comision'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['comision']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar la comision'));
            $data['comision']= str_replace(',','',$data['comision']);
        }
        else
            $data['comision']=NULL;

        if(  intval($data['tipo_esquema']) != 7  ) {
            if(!preg_match('/^[0-9]{1,3}$/',$data['empresa_facturadora']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la empresa que factura'));
        }

        if(!empty($data['subtotal'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['subtotal']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el subtotal'));
            $data['subtotal']= str_replace(',','',$data['subtotal']);
        }
        else
            $data['subtotal']=NULL;

        if(!empty($data['iva'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['iva']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el iva'));
            $data['iva']= str_replace(',','',$data['iva']);
        }
        else
            $data['iva']=NULL;

        if(!empty($data['total'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['total']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el total'));
            $data['total']= str_replace(',','',$data['total']);
        }
        else
            $data['total']=NULL;

        /********************************************************************************************************************************************/
        if(!empty($data['empresa_imss']) || $data['tipo_esquema'] == "4"){
            if(!preg_match('/^[0-9]{1,3}$/',$data['empresa_imss']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la empresa pagadora IMSS'));
        }
        else
            $data['empresa_imss'] = NULL;

        if(!empty($data['total_imss']) || $data['tipo_esquema'] == "4"){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['total_imss']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el total a depositarle IMSS'));
            $data['total_imss']= str_replace(',','',$data['total_imss']);
        }
        else
            $data['total_imss']=NULL;

        if(!empty($data['empresa_asimilados']) || $data['tipo_esquema'] == "1"){        
            if(!preg_match('/^[0-9]{1,3}$/',$data['empresa_asimilados']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la empresa pagadora asimilados'));
        }
        else
            $data['empresa_asimilados'] = NULL;

        if(!empty($data['total_asimilados']) || $data['tipo_esquema'] == "1"){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['total_asimilados']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el total a depositarle por asimilados:'));
            $data['total_asimilados']= str_replace(',','',$data['total_asimilados']);
        }
        else
            $data['total_asimilados'] = NULL;
            
        if(!empty($data['tipo_periodo']) || intval($data['tipo_esquema']) < 5){
            if(!preg_match('/^[0-9]{1}$/',$data['tipo_periodo']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar el tipo de periodo'));   
        }
        else
            $data['tipo_periodo'] = NULL;

        if(!empty($data['numero_periodo']) || intval($data['tipo_esquema']) < 5){
            if(!preg_match('/^[0-9]{1,}$/',$data['numero_periodo']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar el número de periodo')); 
        }
        else
            $data['numero_periodo'] = NULL;

        if( intval($data['tipo_esquema']) < 6){
            if(!preg_match('/^[0-9]{1,}$/',$data['socios']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar el campo socios')); 
        }
        else
            $data['socios'] = NULL;
        /******************************No obligatorios  *****************************************************/
        
        if(!empty($data['ingreso'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['ingreso']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el ingreso'));
            $data['ingreso']= str_replace(',','',$data['ingreso']);
        }
        else
            $data['ingreso'] = NULL;

        if(!empty($data['infonavit'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['infonavit']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el INFONAVIT'));
            $data['infonavit']= str_replace(',','',$data['infonavit']);
        }
        else
            $data['infonavit'] = NULL;

        if(!empty($data['fonacot'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['fonacot']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el FONACOT'));
            $data['fonacot']= str_replace(',','',$data['fonacot']);
        }
        else
            $data['fonacot'] = NULL;

        if(!empty($data['donativo'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['donativo']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el donativo'));
            $data['donativo']= str_replace(',','',$data['donativo']);
        }
        else
            $data['donativo'] = NULL;

        if(!empty($data['pension'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['pension']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar la pension'));
            $data['pension']= str_replace(',','',$data['pension']);
        }
        else
            $data['pension'] = NULL;

        if(!empty($data['excedente_cargas'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_cargas']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar excedente de cargas'));
            $data['excedente_cargas']= str_replace(',','',$data['excedente_cargas']);
        }
        else
            $data['excedente_cargas'] = NULL;

        if(!empty($data['cargas_patronal'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['cargas_patronal']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar la carga patronal'));
            $data['cargas_patronal']= str_replace(',','',$data['cargas_patronal']);
        }
        else
            $data['cargas_patronal'] = NULL;

        if(!empty($data['isn'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['isn']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el ISN'));
            $data['isn']= str_replace(',','',$data['isn']);
        }
        else
            $data['isn'] = NULL;

        if(!empty($data['comision_monto'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['comision_monto']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'No se cálculo la comison(monto)'));
            if($data['comision_monto'] === '0.00')
                $data['comision_monto'] = NULL;
            $data['comision_monto']= str_replace(',','',$data['comision_monto']);
        }
        else
            $data['comision_monto'] = NULL;

        if(!empty($data['imss_obrera'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['imss_obrera']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo IMSS obrera'));
            $data['imss_obrera']= str_replace(',','',$data['imss_obrera']);
        }
        else
            $data['imss_obrera'] = NULL;

        if(!empty($data['carga_social_imss'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['carga_social_imss']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar la carga social IMSS'));
            $data['carga_social_imss']= str_replace(',','',$data['carga_social_imss']);
        }
        else
            $data['carga_social_imss'] = NULL;

         if(!empty($data['prenomina_imss'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['prenomina_imss']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar la prenómina IMSS'));
            $data['prenomina_imss']= str_replace(',','',$data['prenomina_imss']);
        }
        else
            $data['prenomina_imss'] = NULL;

        if(!empty($data['isr_isp'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['isr_isp']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el ISR/ISP'));
            $data['isr_isp']= str_replace(',','',$data['isr_isp']);
        }
        else
            $data['isr_isp'] = NULL;

        if(!empty($data['isr_142'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['isr_142']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el ISR art. 142'));
            $data['isr_142']= str_replace(',','',$data['isr_142']);
        }
        else
            $data['isr_142'] = NULL;
       
        if(!empty($data['cuota_sindical'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['cuota_sindical']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar la cuota sindical'));
            $data['cuota_sindical']= str_replace(',','',$data['cuota_sindical']);
        }
        else
            $data['cuota_sindical'] = NULL;

        /***************************** */
        if(!empty($data['despensa'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['despensa']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo despensa'));
            $data['despensa']= str_replace(',','',$data['despensa']);
        }
        else
            $data['despensa'] = NULL;

        if(!empty($data['caja_ahorro'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['caja_ahorro']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar la caja de ahorro'));
            $data['caja_ahorro']= str_replace(',','',$data['caja_ahorro']);
        }
        else
            $data['caja_ahorro'] = NULL;

        if(!empty($data['descuento_imss'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['descuento_imss']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo descuento IMSS'));
            $data['descuento_imss']= str_replace(',','',$data['descuento_imss']);
        }
        else
            $data['descuento_imss'] = NULL;

        if(!empty($data['apoyo_sindical'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['apoyo_sindical']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo apoyo sindical'));
            $data['apoyo_sindical']= str_replace(',','',$data['apoyo_sindical']);
        }
        else
            $data['apoyo_sindical'] = NULL;

        if(!empty($data['descuento_comedor'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['descuento_comedor']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo descuento comedor'));
            $data['descuento_comedor']= str_replace(',','',$data['descuento_comedor']);
        }
        else
            $data['descuento_comedor'] = NULL;
        /********************************** */
        if(!empty($data['haberes'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['haberes']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo haberes'));
            $data['haberes']= str_replace(',','',$data['haberes']);
        }
        else
            $data['haberes'] = NULL;


        if(!empty($data['otros'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['otros']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo otros'));
            $data['otros']= str_replace(',','',$data['otros']);
        }
        else
            $data['otros'] = NULL;

        if(!empty($data['excedente_ingreso'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_ingreso']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el ingreso excedente'));
            $data['excedente_ingreso']= str_replace(',','',$data['excedente_ingreso']);
        }
        else
            $data['excedente_ingreso'] = NULL;


        if(!empty($data['excedente_isr'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_isr']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el ISR excedente'));
            $data['excedente_isr']= str_replace(',','',$data['excedente_isr']);
        }
        else
            $data['excedente_isr'] = NULL;

        if(!empty($data['excedente_imss'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_imss']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el IMSS excedente'));
            $data['excedente_imss']= str_replace(',','',$data['excedente_imss']);
        }
        else
            $data['excedente_imss'] = NULL;

        if(!empty($data['excedente_gmm'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_gmm']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el GMM excedente'));
            $data['excedente_gmm']= str_replace(',','',$data['excedente_gmm']);
        }
        else
            $data['excedente_gmm'] = NULL;

        if(!empty($data['excedente_infonavit'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_infonavit']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el INFONAVIT excedente'));
            $data['excedente_infonavit']= str_replace(',','',$data['excedente_infonavit']);
        }
        else
            $data['excedente_infonavit'] = NULL;

        if(!empty($data['excedente_fonacot'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_fonacot']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el FONACOT excedente'));
            $data['excedente_fonacot']= str_replace(',','',$data['excedente_fonacot']);
        }
        else
            $data['excedente_fonacot'] = NULL;

        if(!empty($data['excedente_prestamos'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_prestamos']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo prestamos excedente'));
            $data['excedente_prestamos']= str_replace(',','',$data['excedente_prestamos']);
        }
        else
            $data['excedente_prestamos'] = NULL;

        if(!empty($data['excedente_pension'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_pension']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo pensión excedente'));
            $data['excedente_pension']= str_replace(',','',$data['excedente_pension']);
        }
        else
            $data['excedente_pension'] = NULL;

        if(!empty($data['excedente_terceros'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_terceros']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo terceros excedente'));
            $data['excedente_terceros']= str_replace(',','',$data['excedente_terceros']);
        }
        else
            $data['excedente_terceros'] = NULL;

        if(!empty($data['excedente_clientes'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_clientes']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo cliente excedente'));
            $data['excedente_clientes']= str_replace(',','',$data['excedente_clientes']);
        }
        else
            $data['excedente_clientes'] = NULL;

        if(!empty($data['excedente_subsidio'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_subsidio']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo subsidio excedente'));
            $data['excedente_subsidio']= str_replace(',','',$data['excedente_subsidio']);
        }
        else
            $data['excedente_subsidio'] = NULL;

        if(!empty($data['excedente_recuperacion'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_recuperacion']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo recuperacion excedente'));
            $data['excedente_recuperacion']= str_replace(',','',$data['excedente_recuperacion']);
        }
        else
            $data['excedente_recuperacion'] = NULL;

        if(!empty($data['excedente_comision'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_comision']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo comision excedente'));
            $data['excedente_comision']= str_replace(',','',$data['excedente_comision']);
        }
        else
            $data['excedente_comision'] = NULL;

         if(!empty($data['excedente_prenomina'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_prenomina']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo prenómina excedente'));
            $data['excedente_prenomina']= str_replace(',','',$data['excedente_prenomina']);
        }
        else
            $data['excedente_prenomina'] = NULL;

        if(!empty($data['excedente_prenomina_gmm'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_prenomina_gmm']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo prenómina GMM excedente'));
            $data['excedente_prenomina_gmm']= str_replace(',','',$data['excedente_prenomina_gmm']);
        }
        else
            $data['excedente_prenomina_gmm'] = NULL;


        if(!empty($data['excedente_otros'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_otros']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el campo otros excedente'));
            $data['excedente_otros']= str_replace(',','',$data['excedente_otros']);
        }
        else
            $data['excedente_otros'] = NULL;
        
        if(!empty($data['comentarios_nominas'])){
            if(preg_match('/["\']{1,}/',$data['comentarios_nominas']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El campo comentarios no puede contener comillas simples ni dobles'));
        }
        else
            $data['comentarios_nominas'] = NULL;

        if(!empty($data['descuentos_sys'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['descuentos_sys']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error de calculo en el campo descuentos sueldos y salarios'));
            $data['descuentos_sys']= str_replace(',','',$data['descuentos_sys']);
        }
        else
            $data['descuentos_sys']=NULL;

        if(!empty($data['descuentos_asesores'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['descuentos_asesores']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error de calculo en el campo descuentos asesores'));
            $data['descuentos_asesores']= str_replace(',','',$data['descuentos_asesores']);
        }
        else
            $data['descuentos_asesores']=NULL;

        if(!empty($data['descuentos_terceros'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['descuentos_terceros']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error de calculo en el campo descuentos a terceros'));
            $data['descuentos_terceros']= str_replace(',','',$data['descuentos_terceros']);
        }
        else
            $data['descuentos_terceros']=NULL;

        if(!empty($data['prestamos_empleados'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['prestamos_empleados']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error de calculo en el campo prestamos empleados'));
            $data['prestamos_empleados']= str_replace(',','',$data['prestamos_empleados']);
        }
        else
            $data['prestamos_empleados']=NULL;

        if(!empty($data['prestamos_ayudate'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['prestamos_ayudate']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error de calculo en el campo prestamos ayudate'));
            $data['prestamos_ayudate']= str_replace(',','',$data['prestamos_ayudate']);
        }
        else
            $data['prestamos_ayudate']=NULL;

         if(!empty($data['excedente_caja_ahorro'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['excedente_caja_ahorro']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error de calculo en el campo caja de ahorro'));
            $data['excedente_caja_ahorro']= str_replace(',','',$data['excedente_caja_ahorro']);
        }
        else
            $data['excedente_caja_ahorro']=NULL;

        if(!empty($data['retencion_iva'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['retencion_iva']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error en el campo retencion de iva'));
            $data['retencion_iva']= str_replace(',','',$data['retencion_iva']);
        }
        else
            $data['retencion_iva']=NULL;

        if($data['devengadaFactura'] !== NULL){
            if(!preg_match('/^[0-9a-zA-Z-_\s]{1,}$/',$data['devengadaFactura']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error en el No. Factura'));
        }

        if(!empty($data['ajuste_subsidio_empleo'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['ajuste_subsidio_empleo']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error en el campo ajuste de subsidio para el empleo'));
            $data['ajuste_subsidio_empleo']= str_replace(',','',$data['ajuste_subsidio_empleo']);
        }
        else
            $data['ajuste_subsidio_empleo']=NULL;

        if(!empty($data['descuento_ayudate'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['descuento_ayudate']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error en el campo descuento ayudate'));
            $data['descuento_ayudate']= str_replace(',','',$data['descuento_ayudate']);
        }
        else
            $data['descuento_ayudate']=NULL;

        if(!empty($data['retencion_isn'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['retencion_isn']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error en el campo retención ISN'));
            $data['retencion_isn']= str_replace(',','',$data['retencion_isn']);
        }
        else
            $data['retencion_isn']=NULL;

        if($data['id_nomina'] !== NULL)
            $respuesta = NominasModel::actualizarNomina2($data,Tablas::nominas_liberacion());
        else
            $respuesta = NominasModel::registrarNomina2($data,Tablas::nominas_liberacion());
            
        if($respuesta){
            if($data['id_nomina'] !== NULL ){
                self::cargarDocumentos($data['id_nomina'],$data['url'],$data['documentsName'],$data['documentsTemp'],$data['documentsSize']);
                return json_encode(array('error'=>false,'titulo'=>'Proceso correcto','subtitulo'=>'La información se guardo correctamente'));
            }   
            else{
                self::cargarDocumentos($respuesta,$data['url'],$data['documentsName'],$data['documentsTemp'],$data['documentsSize']);
                return json_encode(array('error'=>false,'titulo'=>'Número de nómina: '.$respuesta,'subtitulo'=>'La información se guardo correctamente'));
            }  
        }
        else
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Intente guardar de nuevo'));
    }

    private static function cargarDocumentos($nomina,$destino,$archivos,$temporales,$tamanos){
        if(!empty($archivos)){
            
            if(!file_exists("../intranet/documentos-nominas/comprobantes-nominas/".$nomina))
                mkdir("../intranet/documentos-nominas/comprobantes-nominas/".$nomina, 0777, true);

            if($destino === "/asesores/nominas"){
                $departamento = 'nominas';
                $tipoArchivo = self::$fileType2;
            }
                
            else if($destino === "/asesores/finanzas"){
                $departamento = 'finanzas';
                $tipoArchivo = self::$fileType2;
            }
                
            else if($destino === "/asesores/tesoreria"){
                $departamento = 'tesoreria';
                $tipoArchivo = self::$fileType2;
            }
                
            else{
                $departamento = 'facturacion';
                $tipoArchivo = self::$fileType;
            }
               

            $sizeMax = 25;
            $total = count($archivos);
            $extension = array();

            for($i=0;$i<$total;$i++){
                $info = new SplFileInfo($archivos[$i]);
                $extension[$i] = $info->getExtension();
                if(in_array($extension[$i] ,$tipoArchivo) AND $tamanos[$i] <= $sizeMax * 1024 * 1024){
                    $hoy = date("Y-m-d-His"); 
                    $name = $nomina.'-'.$departamento.'-'.$hoy.'-'.$i.'.'.$extension[$i];
                    $src = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$name;       
                    move_uploaded_file($temporales[$i], $src);
                    $archivos[$i] = $name;   
                }
            }
        }
        return true;
    }

    public static function archivosMasivos($nomina,$destino,$archivos,$temporales,$tamanos){

            $inicio = date("d-m-Y H:i:s");
            $start = microtime(true);
            
            $sizeMax = 25;
            $total = count($archivos);
            $extension = array();
            $nameReal = array();
            $pdf= 0;
            $xml = 0;
            $pdf2= 0;
            $xml2 = 0;

            if($destino === "/asesores/nominas"){
                 $departamento = 'nominas';
                 $almacenamiento = "../intranet/documentos-nominas/comprobantes-nominas/";
                 $tipoArchivo =  self::$fileType2;
            }
            else if($destino === "/asesores/nominas2"){
                 $departamento = 'nominas';
                 $almacenamiento = "../intranet/documentos-nominas/recibos-nominas/";
                 $tipoArchivo = self::$fileType;
            }
            else if($destino === "/asesores/finanzas"){
                $departamento = 'finanzas';
                $almacenamiento = "../intranet/documentos-nominas/comprobantes-nominas/";
                $tipoArchivo = self::$fileType2;
            }
            else if($destino === "/asesores/tesoreria"){
                $departamento = 'tesoreria';
                $almacenamiento = "../intranet/documentos-nominas/comprobantes-nominas/";
                $tipoArchivo = self::$fileType2;
            }
            else{
                $departamento = 'facturacion';
                $almacenamiento = "../intranet/documentos-nominas/comprobantes-nominas/";
                $tipoArchivo = self::$fileType;
            }

            $nombreAsignado = array();
            $status = array();
            $extensionGeneral = array();
                
            for($i=0;$i<$total;$i++){
                if(Configuraciones::administrador() != $_SESSION['identificador2'] AND $_SESSION['identificador'] != 191 AND $_SESSION['identificador'] != 365 AND $_SESSION['identificador'] != 375 AND $_SESSION['identificador'] != 348){
                    if( NominasModel::validarRegistro($nomina[$i],Tablas::nominas_liberacion(),$departamento) != 1){
                        $nombreAsignado[$i] = 'No se le asigno nombre';
                        $status[$i] = 'Sin privilegios para esta nómina';
                        $info = new SplFileInfo($archivos[$i]);
                        $extensionGeneral[$i] = $info->getExtension();
                        if($extensionGeneral[$i] === "pdf" || $extensionGeneral[$i] === "Pdf" ||$extensionGeneral[$i] === "PDF")
                            $pdf2++;
                        else
                            $xml2++; 
                        continue;
                    }
                }
                   
                $info = new SplFileInfo($archivos[$i]);
                $extension[$i] = $info->getExtension();
                $nameReal[$i] = basename($archivos[$i],'.'.$extension[$i]);
                $extensionGeneral[$i] = $extension[$i];
                if(in_array($extension[$i] , $tipoArchivo) AND $tamanos[$i] <= $sizeMax * 1024 * 1024){

                    if(!file_exists($almacenamiento.$nomina[$i]))
                        mkdir($almacenamiento.$nomina[$i], 0777, true);
    
                    $hoy = date("Y-m-d-His"); 
                    if($destino === "/asesores/nominas2")
                        $name = $nomina[$i].'-'.$departamento.'-'.$nameReal[$i].'.'.$extension[$i];
                    else
                        $name = $nomina[$i].'-'.$departamento.'-'.$hoy.'-'.$i.'.'.$extension[$i];
                    $src = $almacenamiento.$nomina[$i]."/".$name; 
                    $nombreAsignado[$i] = $name;  
                    if( move_uploaded_file($temporales[$i], $src) ){
                        $status[$i] = 'OK';
                        if($extension[$i] === "pdf" || $extension[$i] === "Pdf" || $extension[$i] === "PDF")
                            $pdf++;
                        else
                            $xml++;
                    }
                    else{
                        $nombreAsignado[$i] = 'No se le asigno nombre';
                        $status[$i] = 'El archivo no se pudo cargar';
                        if($extension[$i] === "pdf" || $extension[$i] === "Pdf" || $extension[$i] === "PDF")
                            $pdf2++;
                        else
                            $xml2++;
                    }
                }
                else{
                    $nombreAsignado[$i] = 'No se le asigno nombre';
                    $status[$i] = 'El peso supera el permitido o el tipo de archivo no es el correcto';
                    if($extension[$i] === "pdf" || $extension[$i] === "Pdf" || $extension[$i] === "PDF")
                            $pdf2++;
                        else
                            $xml2++;
                }
                    
            }

            $archivo = fopen("../intranet/logs/".$_SESSION['identificador'].".txt","a"); 
            if( $archivo !== true ) 
            {
                fwrite($archivo, "\n\rIntranet Asesores Empresariales!");
                fwrite($archivo, "\rMódulo: Nóminas");
                fwrite($archivo, "\rProceso: Cargar masiva de comprobantes bancarios");
                fwrite($archivo, "\rFecha y hora inicial: ".$inicio);
                fwrite($archivo, "\r------------------------------------------------------------------------------------------------------------------------------\n");
                fwrite($archivo, "\rARCHIVOS CARGADOS CORRECTAMENTE");
                fwrite($archivo, "\rTotal PDF: ".$pdf);
                fwrite($archivo, "\rTotal XML: ".$xml."\n");
                fwrite($archivo, "\rARCHIVOS QUE NO SE CARGARON");
                fwrite($archivo, "\rTotal PDF: ".$pdf2);
                fwrite($archivo, "\rTotal XML: ".$xml2."\n");
                fwrite($archivo, "\n\r------------------------------------------------------------------------------------------------------------------------------");
                fwrite($archivo, "\rResumen: ");
                fwrite($archivo, "\n\r No.\tNOMBRE ORIGINAL\t\t\t\tNOMBRE ASIGNADO\t\t\t\t\t\tSTATUS\t\t\t\t\t");
                fwrite($archivo, "\n\r------------------------------------------------------------------------------------------------------------------------------");
                for($i=0;$i<$total;$i++){
                    if($i<9)
                        $consecutivo = '00'.($i+1);
                    else if($i < 99 )
                        $consecutivo = '0'.($i+1);
                    else
                        $consecutivo = $i+1;

                    $nom = $archivos[$i];
                    if(strlen($nom) > 39)
                        $nom = substr($nom, 0, 32).'....'.$extensionGeneral[$i];
                    else 
                        $nom = str_pad($nom, 39); 
                    
                    $nom2 = str_pad($nombreAsignado[$i],55);
                  

                    fwrite($archivo, "\r $consecutivo   $nom $nom2 $status[$i]");
                }
                fwrite($archivo, "\n\r------------------------------------------------------------------------------------------------------------------------------");
                fwrite($archivo, "\rEl proceso tardo: ".round((microtime(true) - $start),2)." segundos.\n\n\n\n\n");

                fflush($archivo); // Fuerza a que se escriban los datos pendientes en el buffer:
            }

            fclose($archivo);
            
            
            if ($pdf > 0 AND $xml > 0)
                $respuesta = "PDF cargados: $pdf y XML cargados: $xml";
            else if($pdf > 0 AND $xml ===0)
                $respuesta = "PDF cargados: $pdf";
            else if($pdf === 0 AND $xml > 0)
                $respuesta = "XML cargados: $xml";
            else 
                $respuesta = '0 archivos cargados';

            return $respuesta;
    }

    public static function mostrarListas($tabla){
        return NominasModel::mostrarSelect($tabla);
    }

    public static function idNominaCargar($id){
        return NominasModel::idNominaCargar($id,Tablas::nominas_liberacion());
    }

    public static function contarRegistros($data='',$ubicacion = "/asesores/nominas"){
        return NominasModel::contarRegistros(Tablas::nominas_liberacion(),$data,$ubicacion);
    }

    public static function mostrarNominas($limite="",$data="",$liberacion = "/asesores/nominas"){

        $html=''; 
        $colorFila= true;

        if($liberacion === '/asesores/liberacion'){

            $respuesta = NominasModel::mostrarNominas2(Tablas::nominas_liberacion(),Tablas::clientes(),Tablas::usuarios(),Tablas::sucursales(),$limite,$data,$liberacion);
        
            foreach($respuesta as $row => $item){

               
                /*if(Configuraciones::administrador() != $_SESSION['identificador2'] AND $_SESSION['identificador'] != 201){
                    if( $item['esquema'] == 7 AND (!GrupoCapturaConfidenciales::pertenece($_SESSION['identificador']) || $item['id_nominista'] != $_SESSION['identificador'])  ) // NÓMINAS CONFIDENCIALES
                        continue;
                }*/
               

                if($item["tesoreria_estatus"] == 1)
                    $tesoreria = '<i class="fa fa-clock-o text-yellow" aria-hidden="true" style="font-size:40px;position:relative;"><span style="position:absolute;font-size:13px;top:0;right:-25px;background:rgba(34, 33, 33, 0.85);color:#fff;min-width:25px;text-align:center;border-radius:3px;">'.self::archivosTesoreriaMin($item["id"]).'</span></i>';
                else 
                    $tesoreria = '<i class="fa fa-check-square-o text-green" aria-hidden="true" style="font-size:40px;position:relative;"><span style="position:absolute;font-size:13px;top:0;right:-25px;background:rgba(34, 33, 33, 0.85);color:#fff;min-width:25px;text-align:center;border-radius:3px;">'.self::archivosTesoreriaMin($item["id"]).'</span></i>';
               
                if($item["observaciones"] == 1)
                    $finanzas = '<i class="fa fa-clock-o fa-2x text-yellow" aria-hidden="true" style="font-size:40px;position:relative;"><span style="position:absolute;font-size:13px;top:0;right:-25px;background:rgba(34, 33, 33, 0.85);color:#fff;min-width:25px;text-align:center;border-radius:3px;">'.self::archivosFinanzasMin($item["id"]).'</span></i>';
                else if($item["observaciones"] == 2)
                    $finanzas = '<i class="fa fa-check-square-o fa-2x text-green" aria-hidden="true" style="font-size:40px;position:relative;"><span style="position:absolute;font-size:13px;top:0;right:-25px;background:rgba(34, 33, 33, 0.85);color:#fff;min-width:25px;text-align:center;border-radius:3px;">'.self::archivosFinanzasMin($item["id"]).'</span></i>';
                else
                    $finanzas = '<i class="fa fa-ban fa-2x text-red" aria-hidden="true" style="font-size:40px;position:relative;"></i>';

                if($item["liberacion_nominas"] == 0)
                    $nominas ='<i class="fa fa-clock-o fa-2x text-yellow" aria-hidden="true" style="font-size:40px;position:relative;"><span style="position:absolute;font-size:13px;top:0;right:-25px;background:rgba(34, 33, 33, 0.85);color:#fff;min-width:25px;text-align:center;border-radius:3px;">'.self::archivosNominasMin($item["id"]).'</span><span style="position:absolute;font-size:13px;top:0;left:-25px;background:#3489df;color:#fff;min-width:25px;text-align:center;border-radius:3px;">'.self::archivosNominasRecibosMin($item["id"]).'</span></i>';
                else
                    $nominas='<i class="fa fa-check-square-o fa-2x text-green" aria-hidden="true" style="font-size:40px;position:relative;"><span style="position:absolute;font-size:13px;top:0;right:-25px;background:rgba(34, 33, 33, 0.85);color:#fff;min-width:25px;text-align:center;border-radius:3px;">'.self::archivosNominasMin($item["id"]).'</span><span style="position:absolute;font-size:13px;top:0;left:-25px;background:#3489df;color:#fff;min-width:25px;text-align:center;border-radius:3px;">'.self::archivosNominasRecibosMin($item["id"]).'</span></i>';
                   
                $boton='<div class="btn-group">
                            <a class="btn btn-success nominasMostrarData" href="#" data-toggle="modal" data-target="#modalMostrarNominas" value="'.$item["id"].'">Mostrar</a>
                        </div>';

                $html.='<div class="divContenedorPadre renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'">
                            <div class="campoFolioEncabezado2"><b>'.$item["id"].'</b></div>
                            <div class="campoClienteEncabezado" style="justify-content: flex-start;">'.$item["cliente"].'</div>      
                            <div class="campoSucursalEncabezado" style="justify-content: flex-start;">'.$item["sucursal"].'</div>
                            <div class="campoNominasEncabezado2">'.$nominas.'</div>      
                            <div class="campoFinanzasEncabezado2">'.$finanzas.'</div>
                            <div class="campoTesoreriaEncabezado2">'.$tesoreria.'</div>
                            <div class="campoOpcionesEncabezado2">'.$boton.'</div>
                        </div>';
            }
        }

        else{

            $respuesta = NominasModel::mostrarNominas(Tablas::nominas_liberacion(),$limite,$data,$liberacion);
        
            foreach($respuesta as $row => $item){

                /*if(Configuraciones::administrador() != $_SESSION['identificador2'] AND $_SESSION['identificador'] != 201){
                    if( $item['esquema'] == 7 AND (!GrupoCapturaConfidenciales::pertenece($_SESSION['identificador']) || $item['id_nominista'] != $_SESSION['identificador'])  ) // NÓMINAS CONFIDENCIALES
                        continue;
                }*/

                if($liberacion !== '/asesores/facturacion'){
                    if($item["esquema"] == 1)
                        $esquema="ASIMILADOS /";
                    else if($item["esquema"] == 2)
                        $esquema="MIXTO /";
                    else if($item["esquema"] == 3)
                        $esquema="SINDICATO /";
                    else if($item["esquema"] == 4)
                        $esquema="SYS /";
                    else if($item["esquema"] == 5)
                        $esquema="TARJETA E. /";
                    else if($item["esquema"] == 6)   
                        $esquema="PRESTAMO /";
                    else if($item["esquema"] == 7) 
                        $esquema="G. MÉDICOS /";
                        //$esquema="CONFIDENCIAL /";
                    else
                        $esquema="PAGADA CON OBSERVACIÓN /";
                    
                    $columna = $esquema.' '.self::traducirTipoPago($item["tipo_pago"]);
                }
                else{
                    if($item["estatus_factura"] == 1){
                        $columna = 'Pendiente'; 
                        $iconoFactura = '<i class="fa fa-clock-o fa-2x text-yellow aria-hidden="true" style="margin-right:8px;"></i>';
                    }  
                    else if($item["estatus_factura"] == 2){
                         $columna = 'Pagada'; 
                         $iconoFactura = '<i class="fa fa-check-square-o fa-2x text-green" aria-hidden="true" style="margin-right:8px;"></i>';
                    }
                    else if($item["estatus_factura"] == 3){
                        $columna = 'Nota credito';
                        $iconoFactura = '<i class="fa fa-check-square-o fa-2x text-green" aria-hidden="true" style="margin-right:8px;"></i>';
                    }
                    else{
                        $columna = 'Cancelada'; 
                        $iconoFactura = '<i class="fa fa-ban fa-2x text-red" aria-hidden="true" style="margin-right:8px;"></i> ';
                    }
                        
                }

                $fecha = explode ( " ", $item['captura_nominista']);

                $boton='<div class="btn-group">
                            <a class="btn btn-success nominasMostrarData" href="#" data-toggle="modal" data-target="#modalMostrarNominas" value="'.$item["id"].'">Mostrar</a>
                        </div>';

                $validarJefe = NominasModel::validacionJefe($item["id_nominista"],Tablas::jefe());
                if( ($item["id_nominista"] == $_SESSION['identificador'] || $validarJefe ) AND $item["id_finanzas"] == NULL AND $liberacion === '/asesores/nominas')  
                    $check = '<div style="width:25px;"><label class="container"><input type="checkbox" class="grupoCheckedNominas" style="cursor:pointer;margin-right:3px;"><span class="checkmark"></span></label></div>';
                else
                    $check = '';

                if( ($item["id_nominista"] == $_SESSION['identificador'] || $validarJefe ) AND $item["liberacion_nominas"] == 0 AND $liberacion === '/asesores/nominas')
                    $check2 = '<div style="width:20px;"><label class="container container2"><input type="checkbox" class="grupoCheckedNominas2" style="cursor:pointer;margin-right:3px;"><span class="checkmark checkmark2"></span></label></div>';
                else
                    $check2 = '';

                if($liberacion === '/asesores/nominas'){
                    $nominista = NominasModel::datos($item["id_nominista"],Tablas::usuarios());
                    if($item["liberacion_nominas"] == 0)
                        $iconoNominas = '<i class="fa fa-clock-o fa-2x text-yellow aria-hidden="true" style="margin-right:8px;"></i>';
                    else
                        $iconoNominas = '<i class="fa fa-check-square-o fa-2x text-green" aria-hidden="true" style="margin-right:8px;"></i>';
                     $modulo = ' <div class="campoNominasEncabezado" style="justify-content: flex-start;">'.$iconoNominas.$nominista.' </div>';
                     $comprobantes = self::archivosNominasMin($item["id"]);
                     $recibos = self::archivosNominasRecibosMin($item["id"]);
                     $columnaRecibos ='<div class="campoArchivos" style="font-size:13px;justify-content: flex-start;">Comp.: '.$comprobantes.'<br> Recib.: '.$recibos.'</div>';
                }
                else if($liberacion === '/asesores/finanzas'){
                    if($item["id_finanzas"] !== NULL)
                        $finanzas = NominasModel::datos($item["id_finanzas"],Tablas::usuarios());
                    else
                        $finanzas = '<b>PENDIENTE</b>';
                    if($item["observaciones"] == 1)
                        $iconoFinanzas = '<i class="fa fa-clock-o fa-2x text-yellow" aria-hidden="true" style="margin-right:8px;"></i> ';
                    else if($item["observaciones"] == 2)
                        $iconoFinanzas = '<i class="fa fa-check-square-o fa-2x text-green" aria-hidden="true" style="margin-right:8px;"></i> ';
                    else
                        $iconoFinanzas = '<i class="fa fa-ban fa-2x text-red" aria-hidden="true" style="margin-right:8px;"></i> ';
                    $modulo = ' <div class="campoFinanzasEncabezado" style="justify-content: flex-start;">'.$iconoFinanzas.$finanzas.' </div>';
                    $columnaRecibos ='<div class="campoArchivos">'.self::archivosFinanzasMin($item["id"]).'</div>';
                }  
                else if($liberacion === '/asesores/tesoreria'){
                    if($item["id_tesoreria"] !== NULL)
                        $tesoreria = NominasModel::datos($item["id_tesoreria"],Tablas::usuarios());
                    else    
                        $tesoreria = '<b>PENDIENTE</b>';
                    if($item["tesoreria_estatus"] == 1)
                        $iconoTesoreria = '<i class="fa fa-clock-o fa-2x text-yellow" aria-hidden="true" style="margin-right:8px;"></i> ';
                    else 
                        $iconoTesoreria = '<i class="fa fa-check-square-o fa-2x text-green" aria-hidden="true" style="margin-right:8px;"></i> ';
                    $modulo = ' <div class="campoTesoreriaEncabezado" style="justify-content: flex-start;">'.$iconoTesoreria.$tesoreria.' </div>';
                    $columnaRecibos ='<div class="campoArchivos">'.self::archivosTesoreriaMin($item["id"]).'</div>';
                }
                else{
                    if($item["id_facturacion"] !== NULL)
                        $facturacion = NominasModel::datos($item["id_facturacion"],Tablas::usuarios());
                    else
                        $facturacion = '<b>PENDIENTE</b>';
                    
                     $modulo = ' <div class="campoNominasEncabezado" style="justify-content: flex-start;">'.$iconoFactura.$facturacion.' </div>';
                     $columnaRecibos ='<div class="campoArchivos">'.self::archivosFacturacionMin($item["id"]).'</div>';
                }
                    
                $html.= '<div class="divContenedorPadre renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'">
                            <div class="campoFolio">'.$check.$check2.'<span><b>'.$item["id"].'</b></span></div>
                            '.$modulo.'      
                            <div class="campoNominasEmpresa" style="justify-content: flex-start;">'.NominasModel::getCliente($item["id_cliente"],Tablas::clientes()).'</div>
                            <div class="campoTipoEncabezado" style="font-size:13px;text-align:center">'.$columna.'</div>
                            <div class="campoFechaEncabezado textoMay" style="font-size:13px">'.MetodosDiversos::formatearFecha($fecha[0],true).'</div>
                            '.$columnaRecibos.'
                            <div class="campoOpcionesEncabezado">'.$boton.'</div>
                        </div>';

                

                
                /*    $html.='<div class="divContenedorPadre renglon'.(boolval($colorFila=!$colorFila) ? 1 : 0).'">
                                <div class="campoFolio">'.$check.$check2.'<span><b>'.$item["id"].'</b></span></div>
                                <div class="campoNominasEncabezado" style="justify-content: flex-start;">'.$iconoNominas.$nominista.' </div>      
                                <div class="campoFinanzasEncabezado" style="justify-content: flex-start;">'.$iconoFinanzas.$finanzas.'</div>
                                <div class="campoTesoreriaEncabezado" style="justify-content: flex-start;">'.$iconoTesoreria.$tesoreria.'</div>      
                                <div class="campoTipoEncabezado" style="font-size:13px;text-align:center">'.$esquema.' '.self::traducirTipoPago($item["tipo_pago"]).'</div>
                                <div class="campoFechaEncabezado textoMay" style="font-size:13px">'.MetodosDiversos::formatearFecha($fecha[0],true).'</div>
                                <div class="campoOpcionesEncabezado">'.$boton.'</div>
                            </div>';
                */
            }
        }

        return $html;    

    }

    static public function verificarPermisoModificacion($nomina){
        if(intval($_SESSION['identificador2']) === 6)
            return true;
        $usuario =  NominasModel::verificarPermisoModificacion($nomina,Tablas::nominas_liberacion());
        if($usuario == $_SESSION['identificador'] ? true : false)
            return true;
        else{
            $jefe= NominasModel::verificarJefe($usuario,Tablas::jefe());
            return $jefe == $_SESSION['identificador'] ? true : false;
        }
    }

    static public function document($ruta,$id,$nominista,$indice = 1){
        $peso = self::filesize_formatted($ruta);
        $nombre = basename($ruta);
        //$nombre2 = substr($nombre, -23);
        $info = new SplFileInfo($nombre);
        $extension = $info->getExtension();
        $ext = self::documentExt($extension);
        $visualizar2 = $extension != 'xml' ? '<a class="btn btn-default visor-pdf-crow-nominas" href="#" alt="intranet/documentos-nominas/comprobantes-nominas/'.$id.'/'.$nombre.'"  title="Visualizar">'.$ext.' '.'<b style="font-size:12px;">'.$id.'-'.$indice.'.'.$extension.'</b><div style="margin-bottom:-8px;margin-top:-2px;font-size:11px;margin-left:20px;">'.$peso.'</div></a>' : '<a class="btn btn-default" href="#" alt="intranet/documentos-nominas/comprobantes-nominas/'.$id.'/'.$nombre.'">'.$ext.' '.'<b style="font-size:12px;">'.$id.'-'.$indice.'.'.$extension.'</b><div style="margin-bottom:-8px;margin-top:-2px;font-size:11px;margin-left:20px;">'.$peso.'</div></a>';
        $visualizar = $extension != 'xml' ? '<li><a href="#" class="visor-pdf-crow-nominas" alt="intranet/documentos-nominas/comprobantes-nominas/'.$id.'/'.$nombre.'"><i class="fa fa-eye"></i> Visualizar</a></li>' : '';
        $eliminar = $nominista == $_SESSION['identificador'] ? '<li><a href="#" class="eliminarAdjuntoNominas" name="'.$nombre.'"><i class="fa fa-trash-o fa-fw"></i> Eliminar</a></li>' : '';
        return  '<div class="btn-group adjuntosContador" style="margin-right:8px;margin-bottom:8px">
                        '.$visualizar2.'
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="fa fa-caret-down" title="Opciones" style="padding-bottom:17px;"></span>
                    </a>
                    <ul class="dropdown-menu">
                        '.$visualizar.'
                        <li><a href="intranet/documentos-nominas/comprobantes-nominas/'.$id.'/'.$nombre.'" download="'.$id.'-'.$indice.'.'.$extension.'"><i class="fa fa-download fa-fw"></i> Descargar</a></li>
                        '.$eliminar.'
                    </ul>
                </div>';
    }

    static public function filesize_formatted($path) { 
        $bytes = filesize($path);
        if ($bytes >= 1073741824) 
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        elseif ($bytes >= 1048576) 
            $bytes = number_format($bytes / 1048576, 2) . ' MB'; 
        elseif ($bytes >= 1024) 
            $bytes = number_format($bytes / 1024, 2) . ' KB';  
        elseif ($bytes >= 1) 
            $bytes = $bytes . ' bytes';  
        else  
            $bytes = '0 bytes'; 
        
        return $bytes; 

    }

    static public function eliminarArchivo($archivo,$nomina){
        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$archivo; 
        return unlink($directorio);
    }

    static public function documentExt($ext){
        if($ext === 'xml' || $ext === 'XML' || $ext === 'Xml')
            return '<i class="fa fa-file-code-o fa-2x text-orange"></i>';
        else if($ext === 'pdf' || $ext === 'Pdf' || $ext === 'PDF')
            return '<i class="fa fa-file-pdf-o fa-2x text-red"></i>';
    }

    static public function archivosAdjuntos($nomina){
        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-nominas"."*") as $nombre_fichero) {
            $files .= self::document($nombre_fichero,$nomina,$_SESSION['identificador'],++$indexFile);
        }

        $files.='<div class="row">
                    <form method="post">  
                        <div class="col-md-12 estilos-centrar">
                            <p>
                                <hr>
                                <input type="hidden" value="'.$nomina.'" name="idNomina">
                                <button type="submit" name="descargarComprobantesNominas" value="" class="btn btn-success"><i class="fa fa-download"></i> Descargar todos</button>
                            </p>
                        </div>
                    </form> 
                </div>';

        return array('html'=>$files,'total'=>$indexFile);
    }

    static public function archivosAdjuntosFinanzas($nomina){
        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-finanzas"."*") as $nombre_fichero) {
            $files .= self::document($nombre_fichero,$nomina,$_SESSION['identificador'],++$indexFile);
        }

        $files.='<div class="row">
                    <form method="post">  
                        <div class="col-md-12 estilos-centrar">
                            <p>
                                <hr>
                                <input type="hidden" value="'.$nomina.'" name="idNomina">
                                <button type="submit" name="descargarComprobantesFinanzas" value="" class="btn btn-success"><i class="fa fa-download"></i> Descargar todos</button>
                            </p>
                        </div>
                    </form> 
                </div>';

        return array('html'=>$files,'total'=>$indexFile);
    }

    static public function archivosAdjuntosTesoreria($nomina){
        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-tesoreria"."*") as $nombre_fichero) {
            $files .= self::document($nombre_fichero,$nomina,$_SESSION['identificador'],++$indexFile);
        }

        $files.='<div class="row">
                    <form method="post">  
                        <div class="col-md-12 estilos-centrar">
                            <p>
                                <hr>
                                <input type="hidden" value="'.$nomina.'" name="idNomina">
                                <button type="submit" name="descargarComprobantesTesoreria" value="" class="btn btn-success"><i class="fa fa-download"></i> Descargar todos</button>
                            </p>
                        </div>
                    </form> 
                </div>';

        return array('html'=>$files,'total'=>$indexFile);
    }

    static public function archivosAdjuntosFacturacion($nomina){
        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-facturacion"."*") as $nombre_fichero) {
            $files .= self::document($nombre_fichero,$nomina,$_SESSION['identificador'],++$indexFile);
        }

        $files.='<div class="row">
                    <form method="post">  
                        <div class="col-md-12 estilos-centrar">
                            <p>
                                <hr>
                                <input type="hidden" value="'.$nomina.'" name="idNomina">
                                <button type="submit" name="descargarComprobantesFacturacion" value="" class="btn btn-success"><i class="fa fa-download"></i> Descargar todos</button>
                            </p>
                        </div>
                    </form> 
                </div>';

        return array('html'=>$files,'total'=>$indexFile);
    }

    static public function archivosNominas($nomina,$nominista){
        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-nominas"."*") as $nombre_fichero) {
            if($indexFile++ < 4)
                $files .= self::document($nombre_fichero,$nomina,$nominista,$indexFile);
        }

        if($indexFile > 4)
            $files .= '<a class="btn btn-default botonArchivosAdjuntos" href="#" title="Visualizar todos los archivos adjuntos" location="1" nomina-data="'.$nomina.'"><b>Mostrar todos los archivos...</b></a>';

        return array('archivos'=>$files,'total'=>$indexFile);
    }

    static public function archivosNominasMin($nomina){
        $path = str_replace('\\', '/', dirname( __DIR__ )); 
        $directorio = $path."/intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;
        foreach (glob($directorio."-nominas"."*") as $nombre_fichero)
            $indexFile++;
        return $indexFile;
    }

    static public function archivosNominasRecibosMin($nomina){
        $path = str_replace('\\', '/', dirname( __DIR__ )); 
        $directorio = $path."/intranet/documentos-nominas/recibos-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = $indexFile2 = 0;

        foreach (glob($directorio."-nominas"."*".".pdf") as $nombre_fichero) {
            $indexFile++;
        }

        foreach (glob($directorio."-nominas"."*".".xml") as $nombre_fichero) {
            $indexFile2++;
        }


        return $indexFile + $indexFile2;
    }

    static public function archivosNominasRecibos($nomina){
        $directorio = "../intranet/documentos-nominas/recibos-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = $indexFile2 = 0;

        foreach (glob($directorio."-nominas"."*".".pdf") as $nombre_fichero) {
            $indexFile++;
        }

        foreach (glob($directorio."-nominas"."*".".xml") as $nombre_fichero) {
            $indexFile2++;
        }

        if($indexFile > 0 || $indexFile2 > 0){
            $files.='<div class="row">
                    <form method="post">  
                        <div class="col-md-12">
                            <p>
                                <input type="hidden" value="'.$nomina.'" name="idNomina">
                                <div style="min-height:40px;">Recibos de nómina PDF: <b><span style="font-size:20px;margin-right:10px;">'.$indexFile.'</span></b> XML: <b><span style="font-size:20px;margin-right:10px;">'.$indexFile2.'</span></b> <button type="submit" name="descargarRecibosNominas" class="btn btn-default"><i class="fa fa-download"></i> Descargar todos</button></div>
                            </p>
                        </div>
                    </form> 
                </div>';
        }
        else
            $files.='<div style="min-height:40px;">Recibos de nómina PDF: <b><span style="font-size:20px;margin-right:10px;">'.$indexFile.'</span></b> XML: <b><span style="font-size:20px;margin-right:10px;">'.$indexFile2.'</span></b></div>';
            

        return array('archivos'=>$files,'total'=>$indexFile+$indexFile2);
    }

    static public function archivosFacturacion($nomina,$nominista){

        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $file = '';
        $indexFile = $indexFile2 = 0;

        foreach (glob($directorio."-facturacion"."*") as $nombre_fichero) {
            if($indexFile++ < 4)
                $file .= self::document($nombre_fichero,$nomina,$nominista,$indexFile);
        }

        if($indexFile > 4)
            $file .= '<a class="btn btn-default botonArchivosAdjuntos" href="#" title="Visualizar todos los archivos adjuntos" location="4" nomina-data="'.$nomina.'"><b>Mostrar todos los archivos...</b></a>';

        $indexFile = 0;
        foreach (glob($directorio."-facturacion"."*".".pdf") as $nombre_fichero) {
            $indexFile++;
        }

        foreach (glob($directorio."-facturacion"."*".".xml") as $nombre_fichero) {
            $indexFile2++;
        }

        if($indexFile > 0 || $indexFile2 > 0){
            $files.='<div class="row">
                    <form method="post">  
                        <div class="col-md-12">
                            <p>
                                <input type="hidden" value="'.$nomina.'" name="idNomina">
                                <div style="min-height:40px;">Comprobantes de nómina PDF: <b><span style="font-size:20px;margin-right:10px;">'.$indexFile.'</span></b> XML: <b><span style="font-size:20px;margin-right:10px;">'.$indexFile2.'</span></b>'.$file.' </div>
                            </p>
                        </div>
                    </form> 
                </div>';
        }
        else
            $files.='<div style="min-height:40px;">Comprobantes de nómina PDF: <b><span style="font-size:20px;margin-right:10px;">'.$indexFile.'</span></b> XML: <b><span style="font-size:20px;margin-right:10px;">'.$indexFile2.'</span></b></div>';
        
        return array('archivos'=>$files,'total'=>$indexFile+$indexFile2);
    }

    static public function archivosFinanzas($nomina,$nominista){
        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-finanzas"."*") as $nombre_fichero) {
            if($indexFile++ < 4)
                $files .= self::document($nombre_fichero,$nomina,$nominista,$indexFile);
        }

        if($indexFile > 4)
            $files .= '<a class="btn btn-default botonArchivosAdjuntos" href="#" title="Visualizar todos los archivos adjuntos" location="2" nomina-data="'.$nomina.'"><b>Mostrar todos los archivos...</b></a>';

        return array('archivos'=>$files,'total'=>$indexFile);
    }

    static public function archivosFinanzasMin($nomina){
        $path = str_replace('\\', '/', dirname( __DIR__ )); 
        $directorio = $path."/intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-finanzas"."*") as $nombre_fichero)
            $indexFile++;
            
        return $indexFile;
    }

    static public function archivosTesoreria($nomina,$nominista){
        $directorio = "../intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-tesoreria"."*") as $nombre_fichero) {
            if($indexFile++ < 4)
            $files .= self::document($nombre_fichero,$nomina,$nominista,$indexFile);
        }

        if($indexFile > 4)
            $files .= '<a class="btn btn-default botonArchivosAdjuntos" href="#" title="Visualizar todos los archivos adjuntos" location="3"><b>Mostrar todos los archivos...</b></a>';

        return array('archivos'=>$files,'total'=>$indexFile);
    }

    static public function archivosTesoreriaMin($nomina){
        $path = str_replace('\\', '/', dirname( __DIR__ )); 
        $directorio = $path."/intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-tesoreria"."*") as $nombre_fichero)
            $indexFile++;
         
        return $indexFile;
    }

    static public function archivosFacturacionMin($nomina){
        $path = str_replace('\\', '/', dirname( __DIR__ )); 
        $directorio = $path."/intranet/documentos-nominas/comprobantes-nominas/".$nomina."/".$nomina; 
        $files = '';
        $indexFile = 0;

        foreach (glob($directorio."-facturacion"."*") as $nombre_fichero)
            $indexFile++;
         
        return $indexFile;
    }

    public static function mostrarDataNomina($idNomina,$tipo){

        $datos=NominasModel::mostrarDataNomina($idNomina,Tablas::nominas_liberacion());
        //$porcentaje = NominasModel::obtenerPorcentaje($datos['id_cliente'],Tablas::clientes());

        $valorDevengada = $datos['devengada'] == 1 ? 'checked': '' ;
        $nominasTab=$finanzasTab=$tesoreriaTab='';
       
        if($tipo === '/asesores/nominas'){
            $nominasTab='active';
        }
        else if($tipo === '/asesores/finanzas'){
            $finanzasTab='active';
        }
        else if($tipo === '/asesores/tesoreria'){
            $tesoreriaTab='active';
        }
        else if($tipo === '/asesores/liberacion'){
            $liberacionTab='active';
        }

        $asimiladosIcono='';
        $asimiladosValidacion='';
        $mixtoIcono='';
        $mixtoValidacion='';
        $sindicatoIcono='';
        $sindicatoValidacion='';
        $sysIcono='';
        $sysValidacion='';
        $tarjetaIcono='';
        $tarjetaValidacion='';
        $prestamoIcono='';
        $prestamoValidacion='';
        $tipo_esquema='';
        $tipoSindicato='';

        $especialRegimen = $especialFacturadora = '<i class="fa fa-check-circle text-green"></i>';
        $especialRegimen2= $especialFacturadora2 = 'required';

        $nominaVinculada ='';

        if($datos['esquema'] == 8){
            $esquema1 = NominasModel::obtenerEsquema($datos['nomina_origen'],Tablas::nominas_liberacion());
            $label = '';
            if($esquema1 == 1)
                $label='ASIMILADOS';
            else if($esquema1 == 2)
                $label='MIXTO';
            else if($esquema1 == 3)
                $label='SINDICATOS';
            else if($esquema1 == 4)
                $label='SUELDOS Y SALARIOS';
            else if($esquema1 == 5)
                $label='TARJETA EMPRESARIAL';
            else if($esquema1 == 6)
                $label='PRESTAMO';
            else if($esquema1 == 7)
                $label='CONFIDENCIAL';
            $nominaVinculada = '<div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-6">
                                        <span><b>Número de nómina origen:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.$datos['nomina_origen'].' </span></span></span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Tipo de esquema de nómina origen:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.$label.'</span></span></span>
                                    </div>
                                </div>';
        }
        else
            $esquema1 = $datos['esquema'];


        switch(intval($esquema1)){
            case 1://asimilados
                $asimiladosIcono='<i class="fa fa-check-circle text-green"></i>';
                $asimiladosValidacion='required';
                $tipo_esquema='ASIMILADOS';
            break;
            case 2://mixto
                $mixtoIcono='<i class="fa fa-check-circle text-green"></i>';
                $mixtoValidacion='required';
                $tipo_esquema='MIXTO';
            break;
            case 3://sindicato
                $sindicatoIcono='<i class="fa fa-check-circle text-green"></i>';
                $sindicatoValidacion='required';
                $tipo_esquema='SINDICATO';
                $tipoSindicato='<div class="row form-group rowColorGray">
                                    <div class="col-md-12">
                                        <label for="">A.-Pagadora sindicato:</label>
                                        <i class="fa fa-check-circle text-green"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                            <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput actualizar" name="tipoSindicato" required>
                                                <option value=""></option>
                                                '.self::tipoSindicato($datos["tipo_sindicato"]).'
                                            </select>
                                        </div>
                                     </div>
                                </div>';
            break;
            case 4://sys
                $sysIcono='<i class="fa fa-check-circle text-green"></i>';
                $sysValidacion='required';
                $tipo_esquema='SUELDOS Y SALARIOS';
            break;
            case 5://tarjeta empresarial
                $tarjetaIcono='<i class="fa fa-check-circle text-green"></i>';
                $tarjetaValidacion='required';
                $tipo_esquema='TARJETA EMPRESARIAL';
            break;
            case 6://PRESTAMO
                $prestamoIcono='<i class="fa fa-check-circle text-green"></i>';
                $prestamoValidacion='required';
                $tipo_esquema='PRESTAMO';
                $datos["comision_porcentaje"] = $datos["comision_porcentaje"] === NULL ? '0.00' : $datos["comision_porcentaje"];
            break;
            case 7://CONFIDENCIAL
                $tipo_esquema='CONFIDENCIAL';
                $especialRegimen = '';//$especialFacturadora = '';
                $especialRegimen2 = '';//$especialFacturadora2 ='';
                $datos["comision_porcentaje"] = $datos["comision_porcentaje"] === NULL ? '0.00' : $datos["comision_porcentaje"];
            break;
        }

        if($datos['esquema'] == 8)
            $tipo_esquema = 'PAGADA CON OBSERVACIÓN';
        
        if($datos["empresa_facturadora"] != 65 AND $tipo_esquema !=6 ){
            $sinFactura='style="display:;"';
            $requiredSinFactura = "required";
        }
        else{
            $sinFactura='style="display:none;"';
            $requiredSinFactura = "";
        }

         
        $nominista = NominasModel::datos2($datos["id_nominista"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaNominista = explode ( " ", $datos['captura_nominista']);

        $fecha_liberacion_nominas = explode ( " ", $datos['fecha_liberacion_nominas']);

        $tesoreria = NominasModel::datos2($datos["id_tesoreria"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaTesoreria = explode ( " ", $datos['captura_tesoreria']);

        $finanzas = NominasModel::datos2($datos["id_finanzas"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaFinanzas = explode ( " ", $datos['captura_finanzas']);
      
        $tesoreriaComentarios = $datos['comentarios_tesoreria'];
        $datos['comentarios_tesoreria'] = str_replace('<br />','',$datos['comentarios_tesoreria']);
        $finanzasComentarios = $datos['comentarios_finanzas'];
        $datos['comentarios_finanzas'] = str_replace('<br />','',$datos['comentarios_finanzas']);
        $nominasComentarios = $datos['comentarios_nominas'];
        $datos['comentarios_nominas'] = str_replace('<br />','',$datos['comentarios_nominas']);


        $files =  self::archivosNominas($idNomina,$datos["id_nominista"]);
        $filesx =self::archivosNominasRecibos($idNomina,$datos["id_nominista"]);
        $files2 = self::archivosFinanzas($idNomina,$datos["id_finanzas"]);
        $files3 = self::archivosTesoreria($idNomina,$datos["id_tesoreria"]);


        $html='';
        
        if($nominasTab === 'active'){
                $html.='<div style="margin-top: 2%;">
                           
                            <h3 style="text-align:left;"><u>CAPTURÓ</u></h3>
                            <div id="actualizarNominasComentarios">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                    </div>
                                </div>

                            </div>
                            <hr style="margin-left:-10px;">

                            <div style="min-height:40px;">Comprobantes bancarios: <b><span id="labelAdjuntos" style="font-size:20px;margin-right:10px;">'.$files['total'].'</span></b><span id="areaAdjuntosLoad">'.$files['archivos'].'</span></div>
                            <hr style="margin-top:-5px;">
                            '.$filesx['archivos'].'
                            <div class="callout callout-success" id="tipoEsquemaAjax" value="'.$tipo_esquema.'">Tipo de esquema: '.$tipo_esquema.'</div>

                            '.$nominaVinculada.'
                            <form method="POST" id="formularioNominasActualizar">

                                    <div style="border:2px dotted gray;">
                                    <h3 style="text-align:center;"><u>TABLA DE LIBERACIÓN</u></h3>

                                    '.$tipoSindicato.'

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-4">
                                            <label for="devengadaAjax" style="cursor:pointer;">La nómina es devengada:</label>
                                            <br>
                                            <label class="switch">
                                                <input type="checkbox" id="devengadaAjax" class="actualizar" value="1" '.$valorDevengada.' disabled>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                
                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-12">
                                            <label for="">1.-Nombre del cliente:</label>
                                            <i class="fa fa-check-circle text-green"></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasCliente" id="clienteActivoAjax" required>
                                                    <option></option>
                                                    '.Nominas::mostrarSelect($datos["id_cliente"],Tablas::clientes()).'
                                                </select>
                                            </div>   
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-4">
                                            <label for="">2.-Tipo de pago:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.''.$tarjetaIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasTipoPago" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.''.$tarjetaValidacion.'>
                                                    <option value=""></option>  
                                                    '.self::tipoPago($datos["tipo_pago"]).'
                                                </select>
                                            </div>                                 
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">3.-Régimen:</label>
                                            '.$especialRegimen.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasRegimen" '.$especialRegimen2.'>
                                                    <option></option>
                                                    '.self::regimen($datos["regimen"]).'
                                                </select>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">4.-Comisión:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.''.$tarjetaIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay actualizar iluminarIconoInput monetario sin-factura" type="text" name="nominasComision" id="nominasComisionAjax" value="'.$datos["comision_porcentaje"].'" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.''.$tarjetaValidacion.''.$requiredSinFactura.'>
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-12">
                                            <label for="">5.-Empresa que factura:</label>
                                            '.$especialFacturadora.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasEmpresaFactura" '.$especialFacturadora2.'>
                                                    <option></option>
                                                    '. Nominas::mostrarSelect($datos["empresa_facturadora"],Tablas::facturadoras()).'
                                                </select>
                                            </div>                                
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-4">
                                            <label for="">6.-Subtotal:</label>
                                            <i class="fa fa-check-circle text-green sin-factura-icono" '.$sinFactura.'></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar sin-factura" type="text" name="nominasSubtotal" value="'.$datos["subtotal"].'" id="nominasSubtotalAjax">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">7.-Iva:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" id="nominasIvaAjax" name="nominasIva" value="'.$datos["iva"].'">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">8.-Total:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" id="nominasTotalAjax" name="nominasTotal" value="'.$datos["total"].'">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-8">
                                            <label for="">9.-Empresa pagadora IMSS:</label>
                                            '.$sysIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasEmpresaImss" '.$sysValidacion.'>
                                                    <option></option>
                                                    '.Nominas::mostrarSelect($datos["empresa_imss"],Tablas::imss()).'
                                                </select>
                                            </div>                             
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">10.-Total a depositarle IMSS:</label>
                                            '.$sysIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["total_imss"].'" name="nominasTotalImss" '.$sysValidacion.'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-8">
                                            <label for="">11.-Empresa pagadora asimilados:</label>
                                            '.$asimiladosIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasEmpresaAsimilados" '.$asimiladosValidacion.'>
                                                    <option></option>
                                                    '.Nominas::mostrarSelect($datos["empresa_asimilados"],Tablas::asimilados()).'
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">12.-Total a depositarle por asimilados:</label>
                                            '.$asimiladosIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["total_asimilados"].'" name="nominasTotalAsimilados" '.$asimiladosValidacion.'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-4">
                                            <label for="">13.-Tipo de periodo:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasPeriodo" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.'>
                                                    <option></option>
                                                    '.self::tipoPeriodo($datos["tipo_periodo"]).'
                                                </select>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">14.-Número de periodo:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar" type="number" value="'.$datos["numero_periodo"].'" name="nominasNumeroPeriodo" min="1" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.'>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">15.-Socios:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.''.$tarjetaIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-hashtag"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar" type="number" value="'.$datos["socios"].'" name="nominasSocios" min="0" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.''.$tarjetaValidacion.'>
                                            </div>                              
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div style="border:2px dotted gray;">
                                    <h3 style="text-align:center;"><u>SUELDOS Y SALARIOS</u></h3>
                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">16.-Ingreso:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["ingreso"].'" name="nominasIngreso">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">17.-INFONAVIT:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["infonavit"].'" name="nominasInfonavit">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">18.-FONACOT:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["fonacot"].'" name="nominasFonacot">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">19.-Donativo:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["donativo"].'" name="nominasDonativo">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">20.-Pensión alimenticia:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["pension"].'" name="nominasPensionAlimenticia">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">21.-Excedente de cargas:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput monetario actualizar" type="input" value="'.$datos["excedente_cargas"].'" name="nominasExcedenteCargas">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">22.-Carga patronal:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["cargas_patronal"].'" name="nominasCargaPatronal">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">23.-ISN:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" value="'.$datos["isn"].'" type="input" name="nominasIsn">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">24.-Comisión(monto):</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay actualizar monetario" type="input" name="nominasComisionMonto" id="nominasComisionMontoAjax" value="'.$datos["comision_monto"].'">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">25.-IMSS obrera:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["imss_obrera"].'" name="nominasImssObrera">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">26.-Carga social IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["carga_social_imss"].'" name="nominasCargaSocialImss">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">27.-Prenómina IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["prenomina_imss"].'" name="nominasPrenominaImss">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">28.-ISR/ISP(SP):</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" value="'.$datos["isr_isp"].'" type="input" name="nominasIsrIsp">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">29.-ISR art. 142:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["isr_142"].'" name="nominasIsr142">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">30.-Cuota sindical:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["cuota_sindical"].'" name="nominasCuotaSindical">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">31.-Despensa:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["despensa"].'" name="nominasDespensa">
                                            </div>                              
                                        </div>
                                        
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">32.-Caja de ahorro:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["caja_ahorro"].'" name="nominasCajaAhorro">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">33.-Descuento IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["descuento_imss"].'" name="nominasDescuentoImss">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">34.-Apoyo sindical:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["apoyo_sindical"].'"name="nominasApoyoSindical">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">35.-Descuentos comedor:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["descuento_comedor"].'" name="nominasDescuentoComedor">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">36.-Haberes:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["haberes"].'" name="nominasHaberes">
                                            </div>                              
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">37.-Subsidio (SP):</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_subsidio"].'" name="nominasExcedenteSubsidio">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">38.-Otros:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["otros"].'" name="nominasOtros">
                                            </div>                              
                                        </div>
                                    </div>

                                </div>
                    
                                <br>
                                <div style="border:2px dotted gray;">
                                    <h3 style="text-align:center;"><u>DESCUENTOS AL TRABAJADOR (EXCEDENTE)</u></h3>
                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">39.-Ingreso:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_ingreso"].'" name="nominasExcedenteIngreso">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">40.-ISR:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_isr"].'" name="nominasExcedenteIsr">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">41.-IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_imss"].'" name="nominasExcedenteImss">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">42.-GMM:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_gmm"].'" name="nominasExcedenteGmm">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">43.-INFONAVIT:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_infonavit"].'" name="nominasExcedenteInfonavit">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">44.-FONACOT:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_fonacot"].'" name="nominasExcedenteFonacot">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">45.-Prestamos:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_prestamos"].'" name="nominasExcedentePrestamos">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">46.-Pensión alimenticia:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_pension"].'" name="nominasExcedentePensionAlimencia">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">47.-Pago a terceros:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_terceros"].'" name="nominasExcedenteTerceros">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">48.-Cliente:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_clientes"].'" name="nominasExcedenteClientes">
                                            </div>
                                        </div>
                                       

                                        <div class="col-md-3">
                                            <label for="">49.-Recuperación:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_recuperacion"].'" name="nominasExcedenteRecuperacion">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">50.-Comisión cobrada al socio:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_comision"].'" name="nominasExcedenteComisionSocio">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                       
                                        <div class="col-md-3">
                                            <label for="">51.-Prenómina IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_prenomina"].'" name="nominasExcedentePrenominaImss">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">52.-Prenómina GMM:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_prenomina_gmm"].'" name="nominasExcedentePrenominaGmm">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">53.-Otros:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_otros"].'" name="nominasExcedenteOtros">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" value="'.$datos["esquema"].'" name="tipoEsquema" hidden>
                                        <span><b>54.-Comentarios: </b></span>
                                        <textarea name="nominasComentarios" class="form-control textAreaImportante iluminarIconoInput actualizar" rows="8" style="resize:vertical;" placeholder="...">'.$datos["comentarios_nominas"].'</textarea>
                                    </div>
                                </div>';

         
                    $html.='     
                    <div class="row">
                        <div class="col-md-12" >
                            <h3>Comprobantes bancarios del cliente</h3>
                            <div class="alert alert-secondary" role="alert" style="margin-top:10px;">
                            <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Documentos validos: Pdf
                            <br>
                            <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Peso máximo por documento: 5 MB
                            </div>
                        </div>
                    </div>
                   
                    <p>Total de archivos adjuntos: <b><span id="totalAdjuntos2" style="font-size:20px;">0</span></b></p>
                    <div id="ocultarLienzoAdjuntos"><ol id="documentosNominas2" class="alert alert-info loadDocuments"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets2"><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>
                    <div id="ocultarLienzoAdjuntos2"><ol class="alert alert-default" style="background:#eee;cursor:not-allowed;"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default" disabled><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>';
            
                    

                    $html.=     '<div class="row text-center">
                                    <div class="col-md-12">
                                        <input type="file" id="archivosNominas2" multiple>
                                        <a id="botonFormularioActualizarNominas" class="btn btn-info"><i class="fa fa-refresh fa-lg"></i> Actualizar</a>
                                        <button type="submit" id="botonFormularioGuardarNominas" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>';

        }

        else if($finanzasTab === 'active'){
            $html.='<div> 
                        <div style="min-height:40px;">Comprobantes bancarios: <b><span style="font-size:20px;margin-right:10px;">'.$files['total'].'</span></b><span>'.$files['archivos'].'</span></div>
                        <div style="border:2px dotted gray;padding-left:10px;">
                            <h3 style="text-align:left;"><u>CAPTURÓ NÓMINAS</u></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                </div>
                            </div>

                    
                            <hr style="margin-left:-10px;">
                                <p><b>Tipo de esquema:</b>'.$tipo_esquema.'</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>1.-Cliente:</b>  '.NominasModel::obtenerDatoNominas($datos['id_cliente'],Tablas::clientes()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>2.-Tipo de pago: </b>'.self::traducirTipoPago($datos['tipo_pago']).' </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>3.-Régimen: </b>'.self::traducirTipoRegimen($datos['regimen']).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>4.-Comisión: </b> <i class="fa fa-dollar"></i> '.number_format($datos['comision_porcentaje'],2).'</span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>5.-Empresa que factura: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_facturadora'],Tablas::facturadoras()).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>6.-Subtotal: </b> <i class="fa fa-dollar"></i> '.number_format($datos['subtotal'],2).'</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>7.-Iva: </b> <i class="fa fa-dollar"></i> '.number_format($datos['iva'],2).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>8.-Total: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>9.-Empresa IMSS: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_imss'],Tablas::imss()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>10.-Total IMSS: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_imss'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>11.-Empresa asimilados: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_asimilados'],Tablas::asimilados()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>12.-Total asimilados: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_asimilados'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>13.-Tipo de periodo: </b>'.self::traducirTipoPeriodo($datos['tipo_periodo']).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>14.-Número de periodo: </b>'.$datos['numero_periodo'].' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>15.-Socios: </b>'.$datos['socios'].' </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Comentarios: </b>'.$nominasComentarios.' </span>
                                    </div>
                                </div>
                        </div>
                        <hr>
                        <br>
                    
                        
                        <div style="min-height:40px;">Comprobantes bancarios: <b><span id="labelAdjuntos" style="font-size:20px;margin-right:10px;">'.$files2['total'].'</span></b><span id="areaAdjuntosLoad">'.$files2['archivos'].'</span></div>
                        <h3 style="text-align:left;"><u>CAPTURÓ FINANZAS</u></h3>
                        <div id="actualizarFinanzasComentarios">
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$finanzas['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$finanzas['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$finanzas['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFinanzas[0],true).' - '.$fechaFinanzas[1].' </span></span>
                                </div>
                            </div>
                        </div>

                        <hr style="margin-left:-10px;">
              
                        <form method="POST" id="formularioFinanzasActualizar">
                        
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label for="financiada" style="cursor:pointer;">1.-Financiada:</label>
                                    <br>
                                    <label class="switch">
                                        <input type="checkbox" id="financiada" class="actualizar2" value="1" '.self::cheked($datos["financiada"]).' disabled>
                                        <span class="slider round"></span>
                                    </label>                         
                                </div>

                                <div class="col-md-4">
                                    <label for="">2.-Fecha y hora del deposito:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>  <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input class="form-controlCustome textoMay actualizar2 iluminarIconoInput" type="date" value="'.$datos['fecha_envio'].'" name="finanzasFechaEnvio">
                                            <input class="form-controlCustome textoMay actualizar2 iluminarIconoInput" type="time" value="'.$datos['hora_envio'].'" name="finanzasHoraEnvio">
                                        </div>                          
                                </div>
                                <div class="col-md-2">
                                    <label for="">3.-No. Factura:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                        <i class="fa fa-hashtag"></i>
                                        </div>
                                        <input class="form-control actualizar2 iluminarIconoInput" type="input" value="'.$datos['numero_factura'].'" name="finanzasNumeroFactura">
                                    </div> 
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="">4.-Estatus liberación:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-list-ol"></i>
                                        </div>
                                        <select class="form-control textoMay actualizar2 iluminarIconoInput" name="finanzasObservaciones" required>
                                            '.self::observaciones($datos["observaciones"]).'
                                        </select>
                                    </div> 
                                </div>
                               
                            </div>

                            <div class="row form-group">
                                
                                <div class="col-md-3">
                                    <label for="">5.-Fecha de liberación:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control textoMay actualizar2 iluminarIconoInput" type="date" value="'.$datos['fecha_liberacion'].'" name="finanzasFechaLiberaciones">
                                    </div>                              
                                </div>

                                <div class="col-md-2">
                                    <label for="fondeoImss" style="cursor:pointer;">6.-Fondeo IMSS:</label>
                                    <br>
                                    <label class="switch">
                                        <input type="checkbox" id="fondeoImss" class="actualizar2" value="1" '.self::cheked($datos["fondeo_imss"]).' disabled>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="fondeoAsimilados" style="cursor:pointer;">7.-Fondeo asimilados:</label>
                                    <br>
                                    <label class="switch">
                                        <input type="checkbox" id="fondeoAsimilados" class="actualizar2" value="1" '.self::cheked($datos["fondeo_asimilados"]).' disabled>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>8.-Comentarios: </b></span>
                                    <textarea name="finanzasComentarios" class="form-control textAreaImportante iluminarIconoInput actualizar2" rows="8" style="resize:vertical;" placeholder="...">'.$datos["comentarios_finanzas"].'</textarea>
                                </div>
                            </div>';  

             
                $html.=' 
                                        
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="alert alert-secondary" role="alert" style="margin-top:10px;">
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Documentos validos: Pdf
                                        <br>
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Peso máximo por documento: 5 MB
                                        </div>
                                    </div>
                                </div>
                               
                                <div id="ocultarLienzoAdjuntos"><ol id="documentosNominas2" class="alert alert-info loadDocuments"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets2"><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>
                                <div id="ocultarLienzoAdjuntos2"><ol class="alert alert-default" style="background:#eee;cursor:not-allowed;"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default" disabled><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>';
                        

                $html.=     '<div class="row text-center">
                                <div class="col-md-12">
                                    <input type="file" id="archivosNominas2" multiple>
                                    <a id="botonFormularioActualizarFinanzas" class="btn btn-info"><i class="fa fa-refresh fa-lg"></i> Actualizar</a>
                                    <button type="submit" id="botonFormularioGuardarFinanzas" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>';
        }


        else if($tesoreriaTab === 'active'){
            $html.='<div> 

                            <div style="min-height:40px;">Comprobantes bancarios: <b><span style="font-size:20px;margin-right:10px;">'.$files['total'].'</span></b><span>'.$files['archivos'].'</span></div>
                            '.$filesx['archivos'].'
                            <div style="border:2px dotted gray;padding-left:10px;">
                            <h3 style="text-align:left;"><u>CAPTURÓ NÓMINAS</u></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                </div>
                            </div>

                            <hr style="margin-left:-10px;">
                                <p><b>Tipo de esquema:</b>'.$tipo_esquema.'</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>1.-Cliente:</b>  '.NominasModel::obtenerDatoNominas($datos['id_cliente'],Tablas::clientes()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>2.-Tipo de pago: </b>'.self::traducirTipoPago($datos['tipo_pago']).' </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>3.-Régimen: </b>'.self::traducirTipoRegimen($datos['regimen']).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>4.-Comisión: </b> <i class="fa fa-dollar"></i> '.number_format($datos['comision_porcentaje'],2).'</span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>5.-Empresa que factura: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_facturadora'],Tablas::facturadoras()).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>6.-Subtotal: </b> <i class="fa fa-dollar"></i> '.number_format($datos['subtotal'],2).'</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>7.-Iva: </b> <i class="fa fa-dollar"></i> '.number_format($datos['iva'],2).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>8.-Total: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>9.-Empresa IMSS: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_imss'],Tablas::imss()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>10.-Total IMSS: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_imss'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>11.-Empresa asimilados: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_asimilados'],Tablas::asimilados()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>12.-Total asimilados: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_asimilados'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>13.-Tipo de periodo: </b>'.self::traducirTipoPeriodo($datos['tipo_periodo']).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>14.-Número de periodo: </b>'.$datos['numero_periodo'].' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>15.-Socios: </b>'.$datos['socios'].' </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Comentarios: </b>'.$nominasComentarios.' </span>
                                    </div>
                                </div>
                        </div>
                        <br>

                        <div style="min-height:40px;">Comprobantes bancarios: <b><span style="font-size:20px;margin-right:10px;">'.$files2['total'].'</span></b><span>'.$files2['archivos'].'</span></div>
                        <div style="border:2px dotted gray;padding-left:10px;">
                            <h3 style="text-align:left;"><u>CAPTURÓ FINANZAS</u></h3>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Nombre:</b> '.$finanzas['nombre'].'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Sucursal:</b> '.$finanzas['sucursal'].'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Puesto:</b>  '.$finanzas['puesto'].' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFinanzas[0],true).' - '.$fechaFinanzas[1].' </span></span>
                                    </div>
                                </div>
                            
                                <hr style="margin-left:-10px;">

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>1.-Financiada: </b> '.self::siOno2($datos["financiada"]).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>2.-Fecha y hora del depósito: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_envio'] ,true).'</span> - '.$datos['hora_envio'].'</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>3.-Número de factura: </b> '.$datos['numero_factura'].'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>4.-Estatus de liberación: </b> '.self::traducirObservaciones($datos["observaciones"]).'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>5.-Fecha de liberación: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_liberacion'] ,true).'</span></span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>6.-Fondeo IMSS: </b> '.self::siOno2($datos["fondeo_imss"]).'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>7.-Fondeo asimilados: </b> '.self::siOno2($datos["fondeo_asimilados"]).'</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Comentarios: </b> '.$finanzasComentarios.'</span>
                                    </div>
                                </div>
                        </div>
                        <hr>
                        <br>

                        <div style="min-height:40px;">Comprobantes bancarios: <b><span id="labelAdjuntos" style="font-size:20px;margin-right:10px;">'.$files3['total'].'</span></b><span id="areaAdjuntosLoad">'.$files3['archivos'].'</span></div>
                        <h3 style="text-align:left;"><u>CAPTURÓ TESORERIA</u></h3>
                        <div id="actualizarTesoreriaComentarios">
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$tesoreria['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$tesoreria['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$tesoreria['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaTesoreria[0],true).' - '.$fechaTesoreria[1].' </span></span>
                                </div>
                            </div>
                        </div>

                        <hr style="margin-left:-10px;">

                        <form method="POST" id="formularioTesoreriaActualizar">
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="">1.-Estatus de pago:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-list-ol"></i>
                                        </div>
                                        <select class="form-control textoMay actualizar3 iluminarIconoInput" name="tesoreriaEstatus" required>
                                            '.self::estatusPago($datos["tesoreria_estatus"]).'
                                        </select>
                                    </div> 
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>2.-Comentarios: </b></span>
                                    <textarea name="tesoreriaComentarios" class="form-control textAreaImportante iluminarIconoInput actualizar3" rows="8" style="resize:vertical;" placeholder="...">'.$datos["comentarios_tesoreria"].'</textarea>
                                </div>
                            </div>';  

       
                $html.='        
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="alert alert-secondary" role="alert" style="margin-top:10px;">
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Documentos validos: Pdf
                                        <br>
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Peso máximo por documento: 5 MB
                                        </div>
                                    </div>
                                </div>
                               
                                <div id="ocultarLienzoAdjuntos"><ol id="documentosNominas2" class="alert alert-info loadDocuments"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets2"><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>
                                <div id="ocultarLienzoAdjuntos2"><ol class="alert alert-default" style="background:#eee;cursor:not-allowed;"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default" disabled><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>';
                        

                $html.=     '<div class="row text-center">
                                <div class="col-md-12">
                                    <input type="file" id="archivosNominas2" multiple>
                                    <a id="botonFormularioActualizarTesoreria" class="btn btn-info"><i class="fa fa-refresh fa-lg"></i> Actualizar</a>
                                    <button type="submit" id="botonFormularioGuardarTesoreria" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>';
        }


        else if($liberacionTab === 'active'){

                $html.='<div>Tipo de esquema: <span style="font-size:20px;background:#00a65a;padding:10px;color:#fff;border-radius:5px;">'.$tipo_esquema.'</span></div>
                        <br>
                        <div> 
                                <div style="background:#3c8dbc;color:#fff;padding:5px;border-top-right-radius:20px;text-align:center;"><h4>NÓMINAS</h4></div>
                                <div style="border:2px dotted gray;padding-left:10px;margin-bottom:8px;">
                                    <h3 style="text-align:left;"><u>CAPTURÓ</u></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-bottom:8px;">
                                            <div class="col-md-12">
                                                <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                            </div>
                                        </div>

                                        <div class="panel box">
                                            <div class="box-header with-border">
                                                <h5 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseRecibosNominas">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Comprobantes bancarios: '.$files['total'].'
                                                </a>
                                                </h5>
                                            </div>
                                            <div id="collapseRecibosNominas" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    '.$files['archivos'].'
                                                </div>
                                            </div>
                                        </div>

                                        '.$nominaVinculada.'
                                    
                                        <div id="actualizarNominas_">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>1.-Cliente:</b>  '.NominasModel::obtenerDatoNominas($datos['id_cliente'],Tablas::clientes()).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>2.-Tipo de pago: </b>'.self::traducirTipoPago($datos['tipo_pago']).' </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>3.-Régimen: </b>'.self::traducirTipoRegimen($datos['regimen']).'</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>4.-Comisión: </b> <i class="fa fa-dollar"></i> '.number_format($datos['comision_porcentaje'],2).'</span>
                                                </div>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>5.-Empresa que factura: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_facturadora'],Tablas::facturadoras()).'</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>6.-Subtotal: </b> <i class="fa fa-dollar"></i> '.number_format($datos['subtotal'],2).'</span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>7.-Iva: </b> <i class="fa fa-dollar"></i> '.number_format($datos['iva'],2).'</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>8.-Total: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total'],2).' </span>
                                                </div>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>9.-Empresa IMSS: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_imss'],Tablas::imss()).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>10.-Total IMSS: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_imss'],2).' </span>
                                                </div>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>11.-Empresa asimilados: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_asimilados'],Tablas::asimilados()).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>12.-Total asimilados: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_asimilados'],2).' </span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>13.-Tipo de periodo: </b>'.self::traducirTipoPeriodo($datos['tipo_periodo']).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>14.-Número de periodo: </b>'.$datos['numero_periodo'].' </span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>15.-Socios: </b>'.$datos['socios'].' </span>
                                                </div>
                                            </div>

                                           <div class="panel box" style="margin-top:8px;">
                                                <div class="box-header with-border">
                                                    <h5 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseComentarisoNominas">
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Comentarios
                                                    </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseComentarisoNominas" class="panel-collapse collapse">
                                                    <div class="box-body">
                                                        '.$nominasComentarios.'
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                </div>
                                

                                <div style="background:#3c8dbc;color:#fff;padding:5px;border-top-right-radius:20px;text-align:center;"><h4>FINANZAS</h4></div>
                                <div style="border:2px dotted gray;padding-left:10px;margin-bottom:8px;">
                                    <h3 style="text-align:left;"><u>CAPTURÓ</u></h3>
                                   
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>Nombre:</b> '.$finanzas['nombre'].'</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Sucursal:</b> '.$finanzas['sucursal'].'</span>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom:8px;">
                                            <div class="col-md-6">
                                                <span><b>Puesto:</b>  '.$finanzas['puesto'].' </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFinanzas[0],true).' - '.$fechaFinanzas[1].' </span></span>
                                            </div>
                                        </div>

                                        <div class="panel box">
                                            <div class="box-header with-border">
                                                <h5 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseRecibosFinanzas">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Comprobantes bancarios: '.$files2['total'].'
                                                </a>
                                                </h5>
                                            </div>
                                            <div id="collapseRecibosFinanzas" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    '.$files2['archivos'].'
                                                </div>
                                            </div>
                                        </div>
                                    
                                      

                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>1.-Financiada: </b> '.self::siOno2($datos["financiada"]).' </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>2.-Fecha y hora del depósito: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_envio'] ,true).'</span> - '.$datos['hora_envio'].'</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>3.-Número de factura: </b> '.$datos['numero_factura'].'</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>4.-Estatus de liberación: </b> '.self::traducirObservaciones($datos["observaciones"]).'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>5.-Fecha de liberación: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_liberacion'] ,true).'</span></span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>6.-Fondeo IMSS: </b> '.self::siOno2($datos["fondeo_imss"]).'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>7.-Fondeo asimilados: </b> '.self::siOno2($datos["fondeo_asimilados"]).'</span>
                                            </div>
                                        </div>
                                        
                                        <div class="panel box" style="margin-top:8px;">
                                            <div class="box-header with-border">
                                                <h5 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseComentariosFinanzas">
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Comentarios
                                                </a>
                                                </h5>
                                            </div>
                                            <div id="collapseComentariosFinanzas" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    '.$finanzasComentarios.'
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                
                                
                                <div style="background:#3c8dbc;color:#fff;padding:5px;border-top-right-radius:20px;text-align:center;"><h4>TESORERIA</h4></div>
                                <div style="border:2px dotted gray;padding-left:10px;margin-bottom:8px;">
                                    <h3 style="text-align:left;"><u>CAPTURÓ</u></h3>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>Nombre:</b> '.$tesoreria['nombre'].'</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Sucursal:</b> '.$tesoreria['sucursal'].'</span>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom:8px;">
                                            <div class="col-md-6">
                                                <span><b>Puesto:</b>  '.$tesoreria['puesto'].' </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaTesoreria[0],true).' - '.$fechaTesoreria[1].' </span></span>
                                            </div>
                                        </div>
                                    

                                        <div class="panel box">
                                            <div class="box-header with-border">
                                                <h5 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseRecibosTesoreria">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Comprobantes bancarios: '.$files3['total'].'
                                                </a>
                                                </h5>
                                            </div>
                                            <div id="collapseRecibosTesoreria" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    '.$files3['archivos'].'
                                                </div>
                                            </div>
                                        </div>

                                 

                                    <div class="row">
                                            <div class="col-md-6">
                                                <span><b>1.-Estatus de pago: </b> '.self::traducirEstatusNominas($datos["tesoreria_estatus"]).'</span>
                                            </div>
                                    </div>

                                    <div class="panel box" style="margin-top:8px;">
                                        <div class="box-header with-border">
                                            <h5 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseComentariosTesoreria">
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Comentarios
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseComentariosTesoreria" class="panel-collapse collapse">
                                            <div class="box-body">
                                                '.$tesoreriaComentarios.'
                                            </div>
                                        </div>
                                    </div>

                                </div>
                        </div>';
        }
        
            return json_encode(array('error'=>false,"html"=>$html));
    }

    public static function mostrarDataNomina2($idNomina,$tipo){

        $datos=NominasModel::mostrarDataNomina($idNomina,Tablas::nominas_liberacion());
        //$porcentaje = NominasModel::obtenerPorcentaje($datos['id_cliente'],Tablas::clientes());

        if($datos['devengada'] == 1){
            $valorDevengada = 'checked';
            $claseActualizar='actualizar';
            $displayFactura='';
        }
        else{
            $valorDevengada = '';
            $claseActualizar='';
            $displayFactura='none';
        }
        //$valorDevengada = $datos['devengada'] == 1 ? 'checked': '' ;

        $nominasTab=$finanzasTab=$tesoreriaTab='';
       
        if($tipo === '/asesores/nominas')
            $nominasTab='active';
        else if($tipo === '/asesores/finanzas')
            $finanzasTab='active';
        else if($tipo === '/asesores/tesoreria')
            $tesoreriaTab='active';
        else if($tipo === '/asesores/liberacion')
            $liberacionTab='active';
        else if($tipo === '/asesores/facturacion')
            $facturacionTab='active';

        $asimiladosIcono='';
        $asimiladosValidacion='';
        $mixtoIcono='';
        $mixtoValidacion='';
        $sindicatoIcono='';
        $sindicatoValidacion='';
        $sysIcono='';
        $sysValidacion='';
        $tarjetaIcono='';
        $tarjetaValidacion='';
        $prestamoIcono='';
        $prestamoValidacion='';
        $tipo_esquema='';
        $tipoSindicato='';

        $especialRegimen = $especialFacturadora = '<i class="fa fa-check-circle text-green"></i>';
        $especialRegimen2= $especialFacturadora2 = 'required';

        $nominaVinculada ='';

        if($datos['esquema'] == 8){
            $esquema1 = NominasModel::obtenerEsquema($datos['nomina_origen'],Tablas::nominas_liberacion());
            $label = '';
            if($esquema1 == 1)
                $label='ASIMILADOS';
            else if($esquema1 == 2)
                $label='MIXTO';
            else if($esquema1 == 3)
                $label='SINDICATOS';
            else if($esquema1 == 4)
                $label='SUELDOS Y SALARIOS';
            else if($esquema1 == 5)
                $label='TARJETA EMPRESARIAL';
            else if($esquema1 == 6)
                $label='PRESTAMO';
            else if($esquema1 == 7)
                $label='GASTOS MÉDICOS';
                //$label='CONFIDENCIAL';
            $nominaVinculada = '<div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-6">
                                        <span><b>Número de nómina origen:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.$datos['nomina_origen'].' </span></span></span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Tipo de esquema de nómina origen:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.$label.'</span></span></span>
                                    </div>
                                </div>';
        }
        else
            $esquema1 = $datos['esquema'];


        switch(intval($esquema1)){
            case 1://asimilados
                $asimiladosIcono='<i class="fa fa-check-circle text-green"></i>';
                $asimiladosValidacion='required';
                $tipo_esquema='ASIMILADOS';
            break;
            case 2://mixto
                $mixtoIcono='<i class="fa fa-check-circle text-green"></i>';
                $mixtoValidacion='required';
                $tipo_esquema='MIXTO';
            break;
            case 3://sindicato
                $sindicatoIcono='<i class="fa fa-check-circle text-green"></i>';
                $sindicatoValidacion='required';
                $tipo_esquema='SINDICATO';
                $tipoSindicato='<div class="row form-group rowColorGray">
                                    <div class="col-md-12">
                                        <label for="">A.-Pagadora sindicato:</label>
                                        <i class="fa fa-check-circle text-green"></i>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                            <i class="fa fa-list-ol"></i>
                                            </div>
                                            <select class="form-control textoMay iluminarIconoInput actualizar" name="tipoSindicato" required>
                                                <option value=""></option>
                                                '.self::tipoSindicato($datos["tipo_sindicato"]).'
                                            </select>
                                        </div>
                                     </div>
                                </div>';
            break;
            case 4://sys
                $sysIcono='<i class="fa fa-check-circle text-green"></i>';
                $sysValidacion='required';
                $tipo_esquema='SUELDOS Y SALARIOS';
            break;
            case 5://tarjeta empresarial
                $tarjetaIcono='<i class="fa fa-check-circle text-green"></i>';
                $tarjetaValidacion='required';
                $tipo_esquema='TARJETA EMPRESARIAL';
            break;
            case 6://PRESTAMO
                $prestamoIcono='<i class="fa fa-check-circle text-green"></i>';
                $prestamoValidacion='required';
                $tipo_esquema='PRESTAMO';
                $datos["comision_porcentaje"] = $datos["comision_porcentaje"] === NULL ? '0.00' : $datos["comision_porcentaje"];
            break;
            case 7://CONFIDENCIAL
                //$tipo_esquema='CONFIDENCIAL';
                $tipo_esquema='GASTOS MÉDICOS';
                $especialRegimen = '';//$especialFacturadora = '';
                $especialRegimen2 = '';//$especialFacturadora2 ='';
                $datos["comision_porcentaje"] = $datos["comision_porcentaje"] === NULL ? '0.00' : $datos["comision_porcentaje"];
            break;
        }

        if($datos['esquema'] == 8)
            $tipo_esquema = 'PAGADA CON OBSERVACIÓN';
        
        if($datos["empresa_facturadora"] != 65 AND $tipo_esquema !=6 ){
            $sinFactura='style="display:;"';
            $requiredSinFactura = "required";
        }
        else{
            $sinFactura='style="display:none;"';
            $requiredSinFactura = "";
        }

         
        $nominista = NominasModel::datos2($datos["id_nominista"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaNominista = explode ( " ", $datos['captura_nominista']);

        $fecha_liberacion_nominas = explode ( " ", $datos['fecha_liberacion_nominas']);

        $tesoreria = NominasModel::datos2($datos["id_tesoreria"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaTesoreria = explode ( " ", $datos['captura_tesoreria']);

        $finanzas = NominasModel::datos2($datos["id_finanzas"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaFinanzas = explode ( " ", $datos['captura_finanzas']);

        $facturacion = NominasModel::datos2($datos["id_facturacion"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaFacturacion = explode ( " ", $datos['captura_facturacion']);
      
        $tesoreriaComentarios = $datos['comentarios_tesoreria'];
        $datos['comentarios_tesoreria'] = str_replace('<br />','',$datos['comentarios_tesoreria']);
        $finanzasComentarios = $datos['comentarios_finanzas'];
        $datos['comentarios_finanzas'] = str_replace('<br />','',$datos['comentarios_finanzas']);
        $nominasComentarios = $datos['comentarios_nominas'];
        $datos['comentarios_nominas'] = str_replace('<br />','',$datos['comentarios_nominas']);


        $files =  self::archivosNominas($idNomina,$datos["id_nominista"]);
        $filesx =self::archivosNominasRecibos($idNomina,$datos["id_nominista"]);
        $files2 = self::archivosFinanzas($idNomina,$datos["id_finanzas"]);
        $files3 = self::archivosTesoreria($idNomina,$datos["id_tesoreria"]);
        $filesFacturacion =self::archivosFacturacion($idNomina,$datos["id_facturacion"]);

        $checkRetencion = $datos["retencion_iva"] !== NULL ? 'checked' : '' ;

        if($datos["retencion_isn"] !== NULL){
            $displayRetencion = '';
            $checkedImpuestoEstatal = 'checked';
        }
        else{
            $displayRetencion = 'none';
            $checkedImpuestoEstatal = '';
        }
        

        $html='';
        
        if($nominasTab === 'active'){
                $html.='<div style="margin-top: 2%;">
                           
                            <h3 style="text-align:left;"><u>CAPTURÓ</u></h3>
                            <div id="actualizarNominasComentarios">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                    </div>
                                </div>

                            </div>
                            <hr style="margin-left:-10px;">

                            <div style="min-height:40px;">Comprobantes bancarios: <b><span id="labelAdjuntos" style="font-size:20px;margin-right:10px;">'.$files['total'].'</span></b><span id="areaAdjuntosLoad">'.$files['archivos'].'</span></div>
                            <hr style="margin-top:-5px;">
                            '.$filesx['archivos'].'
                            <div class="callout callout-success" id="tipoEsquemaAjax" value="'.$tipo_esquema.'">Tipo de esquema: '.$tipo_esquema.'</div>

                            '.$nominaVinculada.'
                            <form method="POST" id="formularioNominasActualizar">

                                    <div style="border:2px dotted gray;">
                                    <h3 style="text-align:center;"><u>TABLA DE LIBERACIÓN</u></h3>

                                    '.$tipoSindicato.'

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-4">
                                            <label for="devengadaAjax" style="cursor:pointer;">La nómina es devengada:</label>
                                            <br>
                                            <label class="switch">
                                                <input type="checkbox" id="devengadaAjax" class="actualizar" value="1" '.$valorDevengada.' disabled>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">No. Factura:</label>
                                            <i class="fa fa-check-circle text-green" id="styleFacturaDevengada2" style="display:'.$displayFactura.'"></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-hashtag"></i>
                                                </div>
                                                <input class="form-control '.$claseActualizar.'" type="text" name="devengadaFactura" value="'.$datos["devengada_factura"].'" id="facturaDevengada2" disabled>
                                            </div>     
                                        </div>
                                    </div>
                                
                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-12">
                                            <label for="">1.-Nombre del cliente:</label>
                                            <i class="fa fa-check-circle text-green"></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasCliente" id="clienteActivoAjax" required>
                                                    <option></option>
                                                    '.Nominas::mostrarSelect($datos["id_cliente"],Tablas::clientes()).'
                                                </select>
                                            </div>   
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-4">
                                            <label for="">2.-Tipo de pago:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.''.$tarjetaIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasTipoPago" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.''.$tarjetaValidacion.'>
                                                    <option value=""></option>  
                                                    '.self::tipoPago($datos["tipo_pago"]).'
                                                </select>
                                            </div>                                 
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">3.-Régimen:</label>
                                            '.$especialRegimen.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasRegimen" '.$especialRegimen2.'>
                                                    <option></option>
                                                    '.self::regimen($datos["regimen"]).'
                                                </select>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">4.-Comisión:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.''.$tarjetaIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay actualizar iluminarIconoInput monetario sin-factura" type="text" name="nominasComision" id="nominasComisionAjax" value="'.$datos["comision_porcentaje"].'" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.''.$tarjetaValidacion.''.$requiredSinFactura.'>
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-12">
                                            <label for="">5.-Empresa que factura:</label>
                                            '.$especialFacturadora.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasEmpresaFactura" '.$especialFacturadora2.'>
                                                    <option></option>
                                                    '. Nominas::mostrarSelect($datos["empresa_facturadora"],Tablas::facturadoras()).'
                                                </select>
                                            </div>                                
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-4">
                                            <label for="">6.-Subtotal:</label>
                                            <i class="fa fa-check-circle text-green sin-factura-icono" '.$sinFactura.'></i>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar sin-factura" type="text" name="nominasSubtotal" value="'.$datos["subtotal"].'" id="nominasSubtotalAjax">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label style="margin-bottom:-7px;" for="">7.-Retención IVA: <i class="fa fa-check-circle text-green campoRetencionObligatorio2" style="display:'.$displayRetencion.';"></i> <label class="container_">(Calcular) <input type="checkbox" id="calcularRetencionIva2" class="actualizar" '.$checkRetencion.'><span class="checkmark_"></span></label></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput actualizar" type="text" name="retencionIva" id="retencionIva2" value="'.$datos["retencion_iva"].'" readonly>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">7-A.-Retención ISN:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput sin-factura" type="text" name="retencionIsn" value="'.$datos["retencion_isn"].'" id="retencionIsn2" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-6">
                                            <label for="">8.-Iva:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput" type="text" id="nominasIvaAjax" name="nominasIva" value="'.$datos["iva"].'" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">9.-Total:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput" type="text" id="nominasTotalAjax" name="nominasTotal" value="'.$datos["total"].'" readonly>
                                            </div>
                                        </div>
                                        
                                    </div>


                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-8">
                                            <label for="">10.-Empresa pagadora IMSS:</label>
                                            '.$sysIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasEmpresaImss" '.$sysValidacion.'>
                                                    <option></option>
                                                    '.Nominas::mostrarSelect($datos["empresa_imss"],Tablas::imss()).'
                                                </select>
                                            </div>                             
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">11.-Total a depositarle IMSS:</label>
                                            '.$sysIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["total_imss"].'" name="nominasTotalImss" '.$sysValidacion.'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-8">
                                            <label for="">12.-Empresa pagadora asimilados:</label>
                                            '.$asimiladosIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasEmpresaAsimilados" '.$asimiladosValidacion.'>
                                                    <option></option>
                                                    '.Nominas::mostrarSelect($datos["empresa_asimilados"],Tablas::asimilados()).'
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">13.-Total a depositarle por asimilados:</label>
                                            '.$asimiladosIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["total_asimilados"].'" name="nominasTotalAsimilados" '.$asimiladosValidacion.'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-4">
                                            <label for="">14.-Tipo de periodo:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i>
                                                </div>
                                                <select class="form-control textoMay iluminarIconoInput actualizar" name="nominasPeriodo" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.'>
                                                    <option></option>
                                                    '.self::tipoPeriodo($datos["tipo_periodo"]).'
                                                </select>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">15.-Número de periodo:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list-ol"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar" type="number" value="'.$datos["numero_periodo"].'" name="nominasNumeroPeriodo" min="1" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.'>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">16.-Socios:</label>
                                            '.$asimiladosIcono.''.$mixtoIcono.''.$sindicatoIcono.''.$sysIcono.''.$tarjetaIcono.'
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-hashtag"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar" type="number" value="'.$datos["socios"].'" name="nominasSocios" min="0" '.$asimiladosValidacion.''.$mixtoValidacion.''.$sindicatoValidacion.''.$sysValidacion.''.$tarjetaValidacion.'>
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-4">
                                            <label for="">17.-Descuentos sueldos y salarios:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput" type="text" name="nominasDescuentosSys" value="'.$datos["descuentos_sys"].'" id="nominasDescuentosSys2" readonly>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">18.-Descuentos asesores:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control  monetario iluminarIconoInput" type="text" name="nominasDescuentosAsesores" value="'.$datos["descuentos_asesores"].'" id="nominasDescuentosAsesores2" readonly>
                                            </div>                                
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">19.-Descuentos terceros:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput" type="text" name="nominasDescuentosTerceros" value="'.$datos["descuentos_terceros"].'" id="nominasDescuentosTerceros2" readonly>
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
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["ingreso"].'" name="nominasIngreso">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">21.-INFONAVIT:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["infonavit"].'" name="nominasInfonavit">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">22.-FONACOT:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["fonacot"].'" name="nominasFonacot">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">23.-Donativo:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["donativo"].'" name="nominasDonativo">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">24.-Pensión alimenticia:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["pension"].'" name="nominasPensionAlimenticia" id="nominasPensionAlimenticia2">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">25.-Excedente de cargas:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput monetario actualizar" type="input" value="'.$datos["excedente_cargas"].'" name="nominasExcedenteCargas">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">26.-Carga patronal:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["cargas_patronal"].'" name="nominasCargaPatronal">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">27.-Impuesto estatal: <i class="fa fa-check-circle text-green campoRetencionObligatorio2" style="display:'.$displayRetencion.';"></i> <label class="container_"><input type="checkbox" id="checkCalcularIsn2" class="actualizar" '.$checkedImpuestoEstatal.'><span class="checkmark_"></span></label></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" value="'.$datos["isn"].'" type="input" name="nominasIsn" id="impuestoEstatal2">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">28.-Comisión(monto):</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay actualizar monetario" type="input" name="nominasComisionMonto" id="nominasComisionMontoAjax" value="'.$datos["comision_monto"].'">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">29.-IMSS obrera:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["imss_obrera"].'" name="nominasImssObrera">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">30.-Carga social IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["carga_social_imss"].'" name="nominasCargaSocialImss">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">31.-Prenómina IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["prenomina_imss"].'" name="nominasPrenominaImss">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">32.-ISR/ISP(SP):</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" value="'.$datos["isr_isp"].'" type="input" name="nominasIsrIsp">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">33.-ISR art. 142:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["isr_142"].'" name="nominasIsr142">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">34.-Cuota sindical:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["cuota_sindical"].'" name="nominasCuotaSindical">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">35.-Despensa:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["despensa"].'" name="nominasDespensa">
                                            </div>                              
                                        </div>
                                        
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">36.-Caja de ahorro:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["caja_ahorro"].'" name="nominasCajaAhorro">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">37.-Descuento generales:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["descuento_imss"].'" name="nominasDescuentoImss">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">38.-Apoyo sindical:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["apoyo_sindical"].'"name="nominasApoyoSindical">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">39.-Descuentos comedor:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["descuento_comedor"].'" name="nominasDescuentoComedor">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">40.-Haberes:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["haberes"].'" name="nominasHaberes">
                                            </div>                              
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">41.-Subsidio (SP):</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_subsidio"].'" name="nominasExcedenteSubsidio">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">42.-Prestamos empleado:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput actualizar" type="input" value="'.$datos["prestamos_empleados"].'" name="nominasPrestamosEmpleados" id="nominasPrestamosEmpleados2">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">43.-Prestamos ayudate:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput actualizar" type="input" value="'.$datos["prestamos_ayudate"].'" name="nominasPrestamosAyudate">
                                            </div>                              
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">44.-ajuste subsidio empleo:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["ajuste_subsidio_empleo"].'" name="ajusteSubsidioEmpleo">
                                            </div>                              
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">45.-Otros:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay iluminarIconoInput actualizar monetario" type="input" value="'.$datos["otros"].'" name="nominasOtros">
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
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_ingreso"].'" name="nominasExcedenteIngreso">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">47.-Ingresos sin timbrar:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput actualizar" type="text" name="nominasExcedenteTerceros" value="'.$datos["excedente_terceros"].'">
                                            </div>
                                        </div>
                                    </div>

                                    <h3 style="text-align:center;"><u>DESCUENTOS AL TRABAJADOR</u></h3>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">48.-ISR:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_isr"].'" name="nominasExcedenteIsr" id="nominasExcedenteIsr2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">49.-IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_imss"].'" name="nominasExcedenteImss" id="nominasExcedenteImss2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">50.-GMM:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_gmm"].'" name="nominasExcedenteGmm" id="nominasExcedenteGmm2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">51.-INFONAVIT:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_infonavit"].'" name="nominasExcedenteInfonavit" id="nominasExcedenteInfonavit2">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">52.-FONACOT:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_fonacot"].'" name="nominasExcedenteFonacot" id="nominasExcedenteFonacot2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">53.-Prestamos:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_prestamos"].'" name="nominasExcedentePrestamos" id="nominasExcedentePrestamos2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">54.-Pensión alimenticia:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_pension"].'" name="nominasExcedentePensionAlimencia" id="nominasExcedentePensionAlimencia2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">55.-Cliente:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_clientes"].'" name="nominasExcedenteClientes" id="nominasExcedenteClientes2">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorWhite">
                                        <div class="col-md-3">
                                            <label for="">56.-Recuperación:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_recuperacion"].'" name="nominasExcedenteRecuperacion" id="nominasExcedenteRecuperacion2">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">57.-Comisión cobrada al socio:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_comision"].'" name="nominasExcedenteComisionSocio" id="nominasExcedenteComisionSocio2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">58.-Prenómina IMSS:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_prenomina"].'" name="nominasExcedentePrenominaImss" id="nominasExcedentePrenominaImss2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">59.-Prenómina GMM:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_prenomina_gmm"].'" name="nominasExcedentePrenominaGmm" id="nominasExcedentePrenominaGmm2">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group rowColorGray">
                                        <div class="col-md-3">
                                            <label for="">60.-Caja de ahorro:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_caja_ahorro"].'" name="nominasExcedenteCajaAhorro" id="nominasExcedenteCajaAhorro2">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">61.-Descuento ayudate:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control monetario iluminarIconoInput actualizar" type="text" value="'.$datos["descuento_ayudate"].'" name="descuentoAyudate">
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-3">
                                            <label for="">62.-Otros:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                <i class="fa fa-dollar"></i>
                                                </div>
                                                <input class="form-control textoMay monetario iluminarIconoInput actualizar" type="text" value="'.$datos["excedente_otros"].'" name="nominasExcedenteOtros" id="nominasExcedenteOtros2">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" value="'.$datos["esquema"].'" name="tipoEsquema" hidden>
                                        <span><b>63.-Comentarios: </b></span>
                                        <textarea name="nominasComentarios" class="form-control textAreaImportante iluminarIconoInput actualizar" rows="8" style="resize:vertical;" placeholder="...">'.$datos["comentarios_nominas"].'</textarea>
                                    </div>
                                </div>';

         
                    $html.='     
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
                   
        
                    <p>Total de archivos adjuntos: <b><span id="totalAdjuntos2" style="font-size:20px;">0</span></b>, Tamaño total de los archivos adjuntos: <b><span id="totalAdjuntosPeso2" style="font-size:20px;">0 MB</span></b></p>
                    <div id="ocultarLienzoAdjuntos"><ol id="documentosNominas2" class="alert alert-info loadDocuments"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets2"><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>
                    <div id="ocultarLienzoAdjuntos2"><ol class="alert alert-default" style="background:#eee;cursor:not-allowed;"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default" disabled><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>';
            
                    

                    $html.=     '<div class="row text-center">
                                    <div class="col-md-12">
                                        <input type="file" id="archivosNominas2" multiple>
                                        <a id="botonFormularioActualizarNominas" class="btn btn-info"><i class="fa fa-refresh fa-lg"></i> Actualizar</a>
                                        <button type="submit" id="botonFormularioGuardarNominas" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>';

        }

        else if($finanzasTab === 'active'){
            $html.='<div> 
                        <div style="min-height:40px;">Comprobantes bancarios: <b><span style="font-size:20px;margin-right:10px;">'.$files['total'].'</span></b><span>'.$files['archivos'].'</span></div>
                        <div style="border:2px dotted gray;padding-left:10px;">
                            <h3 style="text-align:left;"><u>CAPTURÓ NÓMINAS</u></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                </div>
                            </div>

                    
                            <hr style="margin-left:-10px;">
                                <p><b>Tipo de esquema:</b>'.$tipo_esquema.'</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>1.-Cliente:</b>  '.NominasModel::obtenerDatoNominas($datos['id_cliente'],Tablas::clientes()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>2.-Tipo de pago: </b>'.self::traducirTipoPago($datos['tipo_pago']).' </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>3.-Régimen: </b>'.self::traducirTipoRegimen($datos['regimen']).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>4.-Comisión: </b> <i class="fa fa-dollar"></i> '.number_format($datos['comision_porcentaje'],2).'</span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>5.-Empresa que factura: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_facturadora'],Tablas::facturadoras()).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>6.-Subtotal: </b> <i class="fa fa-dollar"></i> '.number_format($datos['subtotal'],2).'</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>7.-Iva: </b> <i class="fa fa-dollar"></i> '.number_format($datos['iva'],2).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>8.-Total: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>9.-Empresa IMSS: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_imss'],Tablas::imss()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>10.-Total IMSS: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_imss'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>11.-Empresa asimilados: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_asimilados'],Tablas::asimilados()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>12.-Total asimilados: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_asimilados'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>13.-Tipo de periodo: </b>'.self::traducirTipoPeriodo($datos['tipo_periodo']).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>14.-Número de periodo: </b>'.$datos['numero_periodo'].' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>15.-Socios: </b>'.$datos['socios'].' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>16.-Descuentos sueldos y salarios: </b>'.number_format($datos['descuentos_sys'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>17.-Descuentos asesores: </b>'.number_format($datos['descuentos_asesores'],2).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>18.-Descuentos terceros: </b>'.number_format($datos['descuentos_terceros'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>19.-Retención del IVA al 6%: </b>'.number_format($datos['retencion_iva'],2).' </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Comentarios: </b>'.$nominasComentarios.' </span>
                                    </div>
                                </div>
                        </div>
                        <hr>
                        <br>
                    
                        
                        <div style="min-height:40px;">Comprobantes bancarios: <b><span id="labelAdjuntos" style="font-size:20px;margin-right:10px;">'.$files2['total'].'</span></b><span id="areaAdjuntosLoad">'.$files2['archivos'].'</span></div>
                        <h3 style="text-align:left;"><u>CAPTURÓ FINANZAS</u></h3>
                        <div id="actualizarFinanzasComentarios">
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$finanzas['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$finanzas['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$finanzas['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFinanzas[0],true).' - '.$fechaFinanzas[1].' </span></span>
                                </div>
                            </div>
                        </div>

                        <hr style="margin-left:-10px;">
              
                        <form method="POST" id="formularioFinanzasActualizar">
                        
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label for="financiada" style="cursor:pointer;">1.-Financiada:</label>
                                    <br>
                                    <label class="switch">
                                        <input type="checkbox" id="financiada" class="actualizar2" value="1" '.self::cheked($datos["financiada"]).' disabled>
                                        <span class="slider round"></span>
                                    </label>                         
                                </div>

                                <div class="col-md-5">
                                    <label for="">2.-Fecha y hora del deposito:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>  <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input class="form-controlCustome textoMay actualizar2 iluminarIconoInput" type="date" value="'.$datos['fecha_envio'].'" name="finanzasFechaEnvio">
                                            <input class="form-controlCustome textoMay actualizar2 iluminarIconoInput" type="time" value="'.$datos['hora_envio'].'" name="finanzasHoraEnvio">
                                        </div>                          
                                </div>
                               
                                <div class="col-md-5">
                                    <label for="">4.-Estatus liberación:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-list-ol"></i>
                                        </div>
                                        <select class="form-control textoMay actualizar2 iluminarIconoInput" name="finanzasObservaciones" required>
                                            '.self::observaciones($datos["observaciones"]).'
                                        </select>
                                    </div> 
                                </div>
                               
                            </div>

                            <div class="row form-group">
                                
                                <div class="col-md-3">
                                    <label for="">5.-Fecha de liberación:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control textoMay actualizar2 iluminarIconoInput" type="date" value="'.$datos['fecha_liberacion'].'" name="finanzasFechaLiberaciones">
                                    </div>                              
                                </div>

                                <div class="col-md-2">
                                    <label for="fondeoImss" style="cursor:pointer;">6.-Fondeo IMSS:</label>
                                    <br>
                                    <label class="switch">
                                        <input type="checkbox" id="fondeoImss" class="actualizar2" value="1" '.self::cheked($datos["fondeo_imss"]).' disabled>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="fondeoAsimilados" style="cursor:pointer;">7.-Fondeo asimilados:</label>
                                    <br>
                                    <label class="switch">
                                        <input type="checkbox" id="fondeoAsimilados" class="actualizar2" value="1" '.self::cheked($datos["fondeo_asimilados"]).' disabled>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>8.-Comentarios: </b></span>
                                    <textarea name="finanzasComentarios" class="form-control textAreaImportante iluminarIconoInput actualizar2" rows="8" style="resize:vertical;" placeholder="...">'.$datos["comentarios_finanzas"].'</textarea>
                                </div>
                            </div>';  

             
                $html.=' 
                                        
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="alert alert-secondary" role="alert" style="margin-top:10px;">
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Documentos validos: Pdf
                                        <br>
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Tamaño máximo por documento: <b>25 MB</b> y la suma de todos los archivos no puede superar los <b>50 MB</b>
                                        </div>
                                    </div>
                                </div>
                               
                                <p>Total de archivos adjuntos: <b><span id="totalAdjuntos2" style="font-size:20px;">0</span></b>, Tamaño total de los archivos adjuntos: <b><span id="totalAdjuntosPeso2" style="font-size:20px;">0 MB</span></b></p>
                                <div id="ocultarLienzoAdjuntos"><ol id="documentosNominas2" class="alert alert-info loadDocuments"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets2"><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>
                                <div id="ocultarLienzoAdjuntos2"><ol class="alert alert-default" style="background:#eee;cursor:not-allowed;"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default" disabled><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>';
                        

                $html.=     '<div class="row text-center">
                                <div class="col-md-12">
                                    <input type="file" id="archivosNominas2" multiple>
                                    <a id="botonFormularioActualizarFinanzas" class="btn btn-info"><i class="fa fa-refresh fa-lg"></i> Actualizar</a>
                                    <button type="submit" id="botonFormularioGuardarFinanzas" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>';
        }

        else if($tesoreriaTab === 'active'){
            $html.='<div> 

                            <div style="min-height:40px;">Comprobantes bancarios: <b><span style="font-size:20px;margin-right:10px;">'.$files['total'].'</span></b><span>'.$files['archivos'].'</span></div>
                            '.$filesx['archivos'].'
                            <div style="border:2px dotted gray;padding-left:10px;">
                            <h3 style="text-align:left;"><u>CAPTURÓ NÓMINAS</u></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                </div>
                            </div>
                        
                            <hr style="margin-left:-10px;">
                                <p><b>Tipo de esquema:</b>'.$tipo_esquema.'</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>1.-Cliente:</b>  '.NominasModel::obtenerDatoNominas($datos['id_cliente'],Tablas::clientes()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>2.-Tipo de pago: </b>'.self::traducirTipoPago($datos['tipo_pago']).' </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>3.-Régimen: </b>'.self::traducirTipoRegimen($datos['regimen']).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>4.-Comisión: </b> <i class="fa fa-dollar"></i> '.number_format($datos['comision_porcentaje'],2).'</span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>5.-Empresa que factura: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_facturadora'],Tablas::facturadoras()).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>6.-Subtotal: </b> <i class="fa fa-dollar"></i> '.number_format($datos['subtotal'],2).'</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>7.-Iva: </b> <i class="fa fa-dollar"></i> '.number_format($datos['iva'],2).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>8.-Total: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>9.-Empresa IMSS: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_imss'],Tablas::imss()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>10.-Total IMSS: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_imss'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>11.-Empresa asimilados: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_asimilados'],Tablas::asimilados()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>12.-Total asimilados: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_asimilados'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>13.-Tipo de periodo: </b>'.self::traducirTipoPeriodo($datos['tipo_periodo']).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>14.-Número de periodo: </b>'.$datos['numero_periodo'].' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>15.-Socios: </b>'.$datos['socios'].' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>16.-Descuentos sueldos y salarios: </b>'.number_format($datos['descuentos_sys'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>17.-Descuentos asesores: </b>'.number_format($datos['descuentos_asesores'],2).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>18.-Descuentos terceros: </b>'.number_format($datos['descuentos_terceros'],2).' </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>19.-Retención del IVA al 6%: </b>'.number_format($datos['retencion_iva'],2).' </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Comentarios: </b>'.$nominasComentarios.' </span>
                                    </div>
                                </div>
                        </div>
                        <br>

                        <div style="min-height:40px;">Comprobantes bancarios: <b><span style="font-size:20px;margin-right:10px;">'.$files2['total'].'</span></b><span>'.$files2['archivos'].'</span></div>
                        <div style="border:2px dotted gray;padding-left:10px;">
                            <h3 style="text-align:left;"><u>CAPTURÓ FINANZAS</u></h3>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Nombre:</b> '.$finanzas['nombre'].'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Sucursal:</b> '.$finanzas['sucursal'].'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Puesto:</b>  '.$finanzas['puesto'].' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFinanzas[0],true).' - '.$fechaFinanzas[1].' </span></span>
                                    </div>
                                </div>
                            
                                <hr style="margin-left:-10px;">

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>1.-Financiada: </b> '.self::siOno2($datos["financiada"]).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>2.-Fecha y hora del depósito: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_envio'] ,true).'</span> - '.$datos['hora_envio'].'</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>3.-Número de factura: </b> '.$datos['numero_factura'].'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>4.-Estatus de liberación: </b> '.self::traducirObservaciones($datos["observaciones"]).'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>5.-Fecha de liberación: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_liberacion'] ,true).'</span></span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>6.-Fondeo IMSS: </b> '.self::siOno2($datos["fondeo_imss"]).'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>7.-Fondeo asimilados: </b> '.self::siOno2($datos["fondeo_asimilados"]).'</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Comentarios: </b> '.$finanzasComentarios.'</span>
                                    </div>
                                </div>
                        </div>
                        <hr>
                        <br>

                        <div style="min-height:40px;">Comprobantes bancarios: <b><span id="labelAdjuntos" style="font-size:20px;margin-right:10px;">'.$files3['total'].'</span></b><span id="areaAdjuntosLoad">'.$files3['archivos'].'</span></div>
                        <h3 style="text-align:left;"><u>CAPTURÓ TESORERIA</u></h3>
                        <div id="actualizarTesoreriaComentarios">
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$tesoreria['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$tesoreria['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$tesoreria['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaTesoreria[0],true).' - '.$fechaTesoreria[1].' </span></span>
                                </div>
                            </div>
                        </div>

                        <hr style="margin-left:-10px;">

                        <form method="POST" id="formularioTesoreriaActualizar">
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="">1.-Estatus de pago:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-list-ol"></i>
                                        </div>
                                        <select class="form-control textoMay actualizar3 iluminarIconoInput" name="tesoreriaEstatus" required>
                                            '.self::estatusPago($datos["tesoreria_estatus"]).'
                                        </select>
                                    </div> 
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>2.-Comentarios: </b></span>
                                    <textarea name="tesoreriaComentarios" class="form-control textAreaImportante iluminarIconoInput actualizar3" rows="8" style="resize:vertical;" placeholder="...">'.$datos["comentarios_tesoreria"].'</textarea>
                                </div>
                            </div>';  

       
                $html.='        
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="alert alert-secondary" role="alert" style="margin-top:10px;">
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Documentos validos: Pdf
                                        <br>
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Tamaño máximo por documento: <b>25 MB</b> y la suma de todos los archivos no puede superar los <b>50 MB</b>
                                        </div>
                                    </div>
                                </div>
                               
                                
                                <div id="ocultarLienzoAdjuntos"><ol id="documentosNominas2" class="alert alert-info loadDocuments"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets2"><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>
                                <div id="ocultarLienzoAdjuntos2"><ol class="alert alert-default" style="background:#eee;cursor:not-allowed;"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default" disabled><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>';
                        

                $html.=     '<div class="row text-center">
                                <div class="col-md-12">
                                    <input type="file" id="archivosNominas2" multiple>
                                    <a id="botonFormularioActualizarTesoreria" class="btn btn-info"><i class="fa fa-refresh fa-lg"></i> Actualizar</a>
                                    <button type="submit" id="botonFormularioGuardarTesoreria" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>';
        }

        else if($facturacionTab === 'active'){
            
            if($datos["estatus_factura"] == 2){
                $styleFechaPagoFacturacion = 'style="display:;"';
                $requiredFechaPagoFacturacion = 'required';
            }
            else{
                $styleFechaPagoFacturacion = 'style="display:none;"';
                $requiredFechaPagoFacturacion = '';
            }
            if($datos["estatus_factura"] == 3){
                $styleNumeroNota = 'style="display:;"';
                $requiredNumeroNota = 'required';
                $styleNumeroFactura = 'style="display:;"';
                $requiredNumeroFactura = 'required';
            }
            else{
                $styleNumeroNota = 'style="display:none;"';
                $requiredNumeroNota = '';
                $styleNumeroFactura = 'style="display:none;"';
                $requiredNumeroFactura = '';
                
            }
            if($datos['numero_factura'] !== NULL){
                $styleNumeroFactura = 'style="display:;"';
                $requiredNumeroFactura = 'required';
            }
            else{
                $styleNumeroFactura = 'style="display:none;"';
                $requiredNumeroFactura = '';
            }

            
            $html.='<div> 

                            <div style="min-height:40px;">Comprobantes bancarios: <b><span style="font-size:20px;margin-right:10px;">'.$files['total'].'</span></b><span>'.$files['archivos'].'</span></div>
                            '.$filesx['archivos'].'
                            <div style="border:2px dotted gray;padding-left:10px;">
                            <h3 style="text-align:left;"><u>CAPTURÓ NÓMINAS</u></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                </div>
                            </div>
                        
                            <hr style="margin-left:-10px;">
                                <p><b>Tipo de esquema:</b>'.$tipo_esquema.'</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>1.-Cliente:</b>  '.NominasModel::obtenerDatoNominas($datos['id_cliente'],Tablas::clientes()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>2.-Tipo de pago: </b>'.self::traducirTipoPago($datos['tipo_pago']).' </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>3.-Régimen: </b>'.self::traducirTipoRegimen($datos['regimen']).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>4.-Comisión: </b> <i class="fa fa-dollar"></i> '.number_format($datos['comision_porcentaje'],2).'</span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>5.-Empresa que factura: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_facturadora'],Tablas::facturadoras()).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>6.-Subtotal: </b> <i class="fa fa-dollar"></i> '.number_format($datos['subtotal'],2).'</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>7.-Iva: </b> <i class="fa fa-dollar"></i> '.number_format($datos['iva'],2).'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>8.-Total: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>9.-Empresa IMSS: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_imss'],Tablas::imss()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>10.-Total IMSS: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_imss'],2).' </span>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>11.-Empresa asimilados: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_asimilados'],Tablas::asimilados()).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>12.-Total asimilados: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_asimilados'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>13.-Tipo de periodo: </b>'.self::traducirTipoPeriodo($datos['tipo_periodo']).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>14.-Número de periodo: </b>'.$datos['numero_periodo'].' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>15.-Socios: </b>'.$datos['socios'].' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>16.-Descuentos sueldos y salarios: </b>'.number_format($datos['descuentos_sys'],2).' </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>17.-Descuentos asesores: </b>'.number_format($datos['descuentos_asesores'],2).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>18.-Descuentos terceros: </b>'.number_format($datos['descuentos_terceros'],2).' </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>19.-Retención del IVA al 6%: </b>'.number_format($datos['retencion_iva'],2).' </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Comentarios: </b>'.$nominasComentarios.' </span>
                                    </div>
                                </div>
                        </div>
                        <br>

                        <div style="min-height:40px;">Comprobantes bancarios: <b><span style="font-size:20px;margin-right:10px;">'.$files2['total'].'</span></b><span>'.$files2['archivos'].'</span></div>
                        <div style="border:2px dotted gray;padding-left:10px;">
                            <h3 style="text-align:left;"><u>CAPTURÓ FINANZAS</u></h3>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Nombre:</b> '.$finanzas['nombre'].'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Sucursal:</b> '.$finanzas['sucursal'].'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>Puesto:</b>  '.$finanzas['puesto'].' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFinanzas[0],true).' - '.$fechaFinanzas[1].' </span></span>
                                    </div>
                                </div>
                            
                                <hr style="margin-left:-10px;">

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>1.-Financiada: </b> '.self::siOno2($datos["financiada"]).' </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>2.-Fecha y hora del depósito: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_envio'] ,true).'</span> - '.$datos['hora_envio'].'</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>3.-Número de factura: </b> '.$datos['numero_factura'].'</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>4.-Estatus de liberación: </b> '.self::traducirObservaciones($datos["observaciones"]).'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>5.-Fecha de liberación: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_liberacion'] ,true).'</span></span>
                                    </div>
                                    <div class="col-md-6">
                                        <span><b>6.-Fondeo IMSS: </b> '.self::siOno2($datos["fondeo_imss"]).'</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span><b>7.-Fondeo asimilados: </b> '.self::siOno2($datos["fondeo_asimilados"]).'</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><b>Comentarios: </b> '.$finanzasComentarios.'</span>
                                    </div>
                                </div>
                        </div>
                        <hr>
                        <br>

                        <div id="recargarArchivos">'.$filesFacturacion['archivos'].'</div>
                        <h3 style="text-align:left;"><u>CAPTURÓ FACTURA</u></h3>
                        <div id="actualizarFacturacionComentarios">
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Nombre:</b> '.$facturacion['nombre'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Sucursal:</b> '.$facturacion['sucursal'].'</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span><b>Puesto:</b> '.$facturacion['puesto'].'</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFacturacion[0],true).' - '.$fechaFacturacion[1].' </span></span>
                                </div>
                            </div>
                        </div>

                        <hr style="margin-left:-10px;">
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label for="">1.-Subtotal:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input class="form-control monetario" type="text" value="'.$datos["subtotal"].'" id="facturaSubtotal" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">2.-Retención IVA:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input class="form-control monetario" type="text" value="'.$datos["retencion_iva"].'" id="facturaRetencionIva" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">3.-IVA:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input class="form-control monetario" type="text" value="'.$datos["iva"].'" id="facturaIva" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">4.-Total:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input class="form-control monetario" type="text" value="'.$datos["total"].'" id="facturaTotal" name="nominasTotalCalculado" readonly>
                                </div>
                            </div>
                        </div>

                        <form method="POST" id="formularioFacturacion">
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label for="">5.-Retención ISN:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        <input class="form-control monetario actualizar" type="text" value="'.$datos['retencion_isn'].'" id="facturaRetencionIsn" name="retencionIsn">
                                    </div> 
                                </div>
                                <div class="col-md-3">
                                    <label for="">6.-Estatus de factura:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-list-ol"></i>
                                        </div>
                                        <select class="form-control textoMay actualizar iluminarIconoInput" name="estatusFactura" id="estatusFactura">
                                            '.self::estatusFactura($datos["estatus_factura"]).'
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-3">
                                    <label for="">7.-Número de factura:</label>
                                    <i class="fa fa-check-circle text-green numeroFacturaCss" '.$styleNumeroFactura.'></i>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                        <i class="fa fa-hashtag"></i>
                                        </div>
                                        <input class="form-control textoMay actualizar iluminarIconoInput" type="text" value="'.$datos['numero_factura'].'" name="numeroFactura" id="numeroFactura" '.$requiredNumeroFactura.'>
                                    </div>                              
                                </div>

                                <div class="col-md-3">
                                    <label for="">8.-Número de nota:</label>
                                    <i class="fa fa-check-circle text-green numeroNotaCss" '.$styleNumeroNota.'></i>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                        <i class="fa fa-hashtag"></i>
                                        </div>
                                        <input class="form-control textoMay actualizar iluminarIconoInput" type="text" value="'.$datos['numero_nota_credito'].'" name="numeroNota" id="numeroNota" '.$requiredNumeroNota.'>
                                    </div>                              
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label for="">9.-Fecha de factura:</label>
                                    <i class="fa fa-check-circle text-green fechaFacturacionCss" '.$styleNumeroFactura.'></i>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control textoMay actualizar iluminarIconoInput" type="date" value="'.$datos['fecha_factura'].'" name="fechaFacturacion" id="fechaFacturacion" '.$requiredNumeroFactura.'>
                                    </div>                              
                                </div>
                                <div class="col-md-3">
                                    <label for="">10.-Fecha de pago:</label>
                                    <i class="fa fa-check-circle text-green fechaPagoFacturacionCss" '.$styleFechaPagoFacturacion.'></i>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control textoMay actualizar iluminarIconoInput" type="date" value="'.$datos['fecha_pago_factura'].'" name="fechaPagoFacturacion" id="fechaPagoFacturacion" '.$requiredFechaPagoFacturacion.'>
                                    </div>                              
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span><b>11.-Comentarios: </b></span>
                                    <textarea name="comentariosFacturacion" class="form-control textAreaImportante iluminarIconoInput actualizar" rows="8" style="resize:vertical;" placeholder="...">'.$datos["comentarios_facturacion"].'</textarea>
                                </div>
                            </div>
                            <br>';  

                if($_SESSION['identificador']==168){
                    $html.='   <div class="row">
                                    <div class="col-md-12" >
                                        <div class="alert alert-secondary" role="alert" style="margin-top:10px;">
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Documentos validos: pdf Y xml
                                        <br>
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Tamaño máximo por archivo: <b>25 MB</b> y la suma de todos los archivos no puede superar los <b>50 MB</b>
                                        </div>
                                    </div>
                                </div>
                   
                                <p>Total de archivos adjuntos: <b><span id="totalAdjuntos" style="font-size:20px;">0</span></b>, Tamaño total de los archivos adjuntos: <b><span id="totalPeso" style="font-size:20px;">0 MB</span></b></p>
                                <div id="lienzoAdjuntos" style="display:none;"><ol id="documentos" class="alert alert-info loadDocuments"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets"><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>
                                <div id="lienzoAdjuntosDisabled"><ol class="alert alert-default" style="background:#eee;cursor:not-allowed;"><h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default" disabled><i class="fa fa-paperclip"></i> Presiona</button></h2></ol></div>';
            
                }

                $html.=     '<div class="row text-center">
                                <div class="col-md-12">
                                    <input type="file" id="adjuntarDocumentos" style="display:none;" multiple >
                                    <a id="botonActualizar" class="btn btn-info"><i class="fa fa-refresh fa-lg"></i> Actualizar</a>
                                    <button type="submit" id="botonGuardar" class="btn btn-success"><i class="fa fa-floppy-o fa-lg"></i> Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>';
        }

        else if($liberacionTab === 'active'){

                $html.='<div>Tipo de esquema: <span style="font-size:20px;background:#00a65a;padding:10px;color:#fff;border-radius:5px;">'.$tipo_esquema.'</span></div>
                        <br>
                        <div> 
                                <div style="background:#3c8dbc;color:#fff;padding:5px;border-top-right-radius:20px;text-align:center;"><h4>NÓMINAS</h4></div>
                                <div style="border:2px dotted gray;padding-left:10px;margin-bottom:8px;">
                                    <h3 style="text-align:left;"><u>CAPTURÓ</u></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Fecha y hora de captura:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-bottom:8px;">
                                            <div class="col-md-12">
                                                <span><b>Fecha y hora de liberación:</b> <span class="textoMay"><span style="font-size:15px;background:#00a65a;padding:5px;color:#fff;border-radius:5px;">'.MetodosDiversos::formatearFecha($fecha_liberacion_nominas[0],true).' - '.$fecha_liberacion_nominas[1].' </span></span></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="panel box">
                                                    <div class="box-header with-border">
                                                        <h5 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseRecibosNominas">
                                                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Comprobantes bancarios: '.$files['total'].'
                                                            </a>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseRecibosNominas" class="panel-collapse collapse">
                                                        <div class="box-body">
                                                            '.$files['archivos'].'
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="panel box">
                                                    <div class="box-header with-border">
                                                        <h5 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseRecibosNominas2">
                                                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Recibos de nómina: '.$filesx['total'].'
                                                            </a>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseRecibosNominas2" class="panel-collapse collapse">
                                                        <div class="box-body">
                                                            '.$filesx['archivos'].'
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                       
                                        '.$nominaVinculada.'
                                    
                                        <div id="actualizarNominas_">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>1.-Cliente:</b>  '.NominasModel::obtenerDatoNominas($datos['id_cliente'],Tablas::clientes()).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>2.-Tipo de pago: </b>'.self::traducirTipoPago($datos['tipo_pago']).' </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>3.-Régimen: </b>'.self::traducirTipoRegimen($datos['regimen']).'</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>4.-Comisión: </b> <i class="fa fa-dollar"></i> '.number_format($datos['comision_porcentaje'],2).'</span>
                                                </div>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span><b>5.-Empresa que factura: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_facturadora'],Tablas::facturadoras()).'</span>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>6.-Subtotal: </b> <i class="fa fa-dollar"></i> '.number_format($datos['subtotal'],2).'</span>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span><b>7.-Retención del IVA al 6%: </b> <i class="fa fa-dollar"></i> '.number_format($datos['retencion_iva'],2).' </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>8.-Iva: </b> <i class="fa fa-dollar"></i> '.number_format($datos['iva'],2).'</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>9.-Total: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total'],2).' </span>
                                                </div>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>10.-Empresa IMSS: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_imss'],Tablas::imss()).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>11.-Total IMSS: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_imss'],2).' </span>
                                                </div>
                                            </div>
                                        
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>12.-Empresa asimilados: </b>'.NominasModel::obtenerDatoNominas($datos['empresa_asimilados'],Tablas::asimilados()).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>13.-Total asimilados: </b> <i class="fa fa-dollar"></i> '.number_format($datos['total_asimilados'],2).' </span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>14.-Tipo de periodo: </b>'.self::traducirTipoPeriodo($datos['tipo_periodo']).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>15.-Número de periodo: </b>'.$datos['numero_periodo'].' </span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>16.-Socios: </b>'.$datos['socios'].' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>17.-Descuentos sueldos y salarios: </b> <i class="fa fa-dollar"></i> '.number_format($datos['descuentos_sys'],2).' </span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span><b>18.-Descuentos asesores: </b> <i class="fa fa-dollar"></i> '.number_format($datos['descuentos_asesores'],2).' </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span><b>19.-Descuentos terceros: </b> <i class="fa fa-dollar"></i> '.number_format($datos['descuentos_terceros'],2).' </span>
                                                </div>
                                            </div>
                                           
                                           <div class="panel box" style="margin-top:8px;">
                                                <div class="box-header with-border">
                                                    <h5 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseComentarisoNominas">
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Comentarios
                                                    </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseComentarisoNominas" class="panel-collapse collapse">
                                                    <div class="box-body">
                                                        '.$nominasComentarios.'
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                </div>
                                

                                <div style="background:#3c8dbc;color:#fff;padding:5px;border-top-right-radius:20px;text-align:center;"><h4>FINANZAS</h4></div>
                                <div style="border:2px dotted gray;padding-left:10px;margin-bottom:8px;">
                                    <h3 style="text-align:left;"><u>CAPTURÓ</u></h3>
                                   
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>Nombre:</b> '.$finanzas['nombre'].'</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Sucursal:</b> '.$finanzas['sucursal'].'</span>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom:8px;">
                                            <div class="col-md-6">
                                                <span><b>Puesto:</b>  '.$finanzas['puesto'].' </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFinanzas[0],true).' - '.$fechaFinanzas[1].' </span></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="panel box">
                                                    <div class="box-header with-border">
                                                        <h5 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseRecibosFinanzas">
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Comprobantes bancarios: '.$files2['total'].'
                                                        </a>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseRecibosFinanzas" class="panel-collapse collapse">
                                                        <div class="box-body">
                                                            '.$files2['archivos'].'
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                      

                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>1.-Financiada: </b> '.self::siOno2($datos["financiada"]).' </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>2.-Fecha y hora del depósito: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_envio'] ,true).'</span> - '.$datos['hora_envio'].'</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>3.-Número de factura: </b> '.$datos['numero_factura'].'</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>4.-Estatus de liberación: </b> '.self::traducirObservaciones($datos["observaciones"]).'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>5.-Fecha de liberación: </b> <span class="textoMay">'.MetodosDiversos::formatearFecha( $datos['fecha_liberacion'] ,true).'</span></span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>6.-Fondeo IMSS: </b> '.self::siOno2($datos["fondeo_imss"]).'</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>7.-Fondeo asimilados: </b> '.self::siOno2($datos["fondeo_asimilados"]).'</span>
                                            </div>
                                        </div>
                                        
                                        <div class="panel box" style="margin-top:8px;">
                                            <div class="box-header with-border">
                                                <h5 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseComentariosFinanzas">
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Comentarios
                                                </a>
                                                </h5>
                                            </div>
                                            <div id="collapseComentariosFinanzas" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    '.$finanzasComentarios.'
                                                </div>
                                            </div>
                                        </div>';

                                        '<div style="background:#f39c12;color:#fff;padding:5px;text-align:center;cursor:pointer;margin-left:-10px;" data-toggle="collapse" data-parent="#accordion2" href="#collapseFacturacion">
                                            <h4 class="box-title text-left">
                                                
                                                    <span style="color:#fff;">
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> FACTURACIÓN
                                                    </span>
                                            
                                            </h4>
                                        </div>         
                                        <div id="collapseFacturacion" class="panel-collapse collapse">
                                            <div class="box-body">
                                                holaaaaaaaaaaaaaaa
                                            </div>
                                        </div>';
                                               

                    $html.=     '</div>
                                
                                <div style="background:#3c8dbc;color:#fff;padding:5px;border-top-right-radius:20px;text-align:center;"><h4>TESORERIA</h4></div>
                                <div style="border:2px dotted gray;padding-left:10px;margin-bottom:8px;">
                                    <h3 style="text-align:left;"><u>CAPTURÓ</u></h3>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <span><b>Nombre:</b> '.$tesoreria['nombre'].'</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Sucursal:</b> '.$tesoreria['sucursal'].'</span>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom:8px;">
                                            <div class="col-md-6">
                                                <span><b>Puesto:</b>  '.$tesoreria['puesto'].' </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaTesoreria[0],true).' - '.$fechaTesoreria[1].' </span></span>
                                            </div>
                                        </div>
                                    
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="panel box">
                                                    <div class="box-header with-border">
                                                        <h5 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapseRecibosTesoreria">
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Comprobantes bancarios: '.$files3['total'].'
                                                        </a>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseRecibosTesoreria" class="panel-collapse collapse">
                                                        <div class="box-body">
                                                            '.$files3['archivos'].'
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                 

                                    <div class="row">
                                            <div class="col-md-6">
                                                <span><b>1.-Estatus de pago: </b> '.self::traducirEstatusNominas($datos["tesoreria_estatus"]).'</span>
                                            </div>
                                    </div>

                                    <div class="panel box" style="margin-top:8px;">
                                        <div class="box-header with-border">
                                            <h5 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapseComentariosTesoreria">
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Comentarios
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseComentariosTesoreria" class="panel-collapse collapse">
                                            <div class="box-body">
                                                '.$tesoreriaComentarios.'
                                            </div>
                                        </div>
                                    </div>

                                </div>

                        </div>';
        }
        
            return json_encode(array('error'=>false,"html"=>$html));
    }

    public static function actualizarFinanzas($data){
        
        if(!preg_match('/^[0-9]{1,}$/',$data['id_nomina']))
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El id de la nómina no es correcto'));

       /* if( NominasModel::verificarStatusTesoreria($data['id_nomina'],Tablas::nominas_liberacion()) > 1)
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ya no puedes actualizar la nómina debido a que ya fue pagada por tesorería, tendrás que ponerte en contacto con dicho departamento para que la ponga en situación de pendiente para que puedas actualizar, en caso de que únicamente quieras cargar archivos tendrás que hacerlo desde el módulo: Cargar comprobantes bancarios del cliente(se encuentra en la pestaña: Cargar-Descargar archivos).'));
        */
       /*if(!empty($data['numero_factura'])){
            if(!preg_match('/^[0-9a-zA-Z-_\s]{1,}$/',$data['numero_factura']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el número de factura'));
        }
        else
            $data['numero_factura'] = NULL;*/

        if($data['financiada'] != '1' && $data['financiada'] != '0')
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la opción financiada'));
       
        if(!empty($data['fecha_envio'])){
            if(!preg_match('/^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$/',$data['fecha_envio']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la fecha del depósito'));
        }
        else
            $data['fecha_envio'] = NULL;

        if(!empty($data['hora_envio'])){
            if(!preg_match('/^[0-9]{2}[:]{1}[0-9]{2}([:][0]{2})*$/',$data['hora_envio']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la hora del depósito'));
        }
        else
            $data['hora_envio'] = NULL;

    
        if(!preg_match('/^[1-3]{1}$/',$data['observaciones']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la opción observaciones'));

        if(!empty($data['fecha_liberacion'])){
            if(!preg_match('/^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$/',$data['fecha_liberacion']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la fecha de liberación'));
        }
        else
            $data['fecha_liberacion'] = NULL;

        if($data['fondeo_imss'] != '1' && $data['fondeo_imss'] != '0')
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la opción fondeo IMSS'));
      
        if($data['fondeo_asimilados'] != '1' && $data['fondeo_asimilados'] != '0')
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la opción fondeo asimilados'));
        
        if(!empty($data['comentarios_finanzas'])){
            if(preg_match('/["\']{1,}/',$data['comentarios_finanzas']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El campo comentarios no puede contener comillas simples ni dobles'));
        }
        else
            $data['comentarios_finanzas'] = NULL;

        //if(($_SESSION['identificador'] == 168 || $_SESSION['identificador'] == 187) )
            self::cargarDocumentos($data['id_nomina'],$data['url'],$data['documentsName'],$data['documentsTemp'],$data['documentsSize']);  

        return NominasModel::actualizarFinanzas($data,Tablas::nominas_liberacion());
    }

    public static function obtenerPorcentaje($cliente){
        return NominasModel::obtenerPorcentaje($cliente,Tablas::clientes());
    }

    public static function marcadores($estado){
        return NominasModel::marcadores($estado,Tablas::nominas_liberacion());
    }

    public static function actualizarTesoreria($data){
        
        if(!preg_match('/^[0-9]{1,}$/',$data['id_nomina']))
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El id de la nómina no es correcto'));
        
        if(!preg_match('/^[1-4]{1}$/',$data['estatus']))
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar el estatus de pago'));

        if(!empty($data['comentarios_tesoreria'])){
            if(preg_match('/["\']{1,}/',$data['comentarios_tesoreria']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El campo comentarios no puede contener comillas simples ni dobles'));
        }
        else
            $data['comentarios_tesoreria'] = NULL;

        self::cargarDocumentos($data['id_nomina'],$data['url'],$data['documentsName'],$data['documentsTemp'],$data['documentsSize']);   
        return NominasModel::actualizarTesoreria($data,Tablas::nominas_liberacion());
    }

    public static function actualizarFacturacion($data){
        
        if(!preg_match('/^[0-9]{1,}$/',$data['id']))
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El id de la nómina no es correcto'));
        
        if(!preg_match('/^[1-4]{1}$/',$data['estatus']))
            return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar el estatus de pago'));


        if(!empty($data['numeroFactura'])){
            if(!preg_match('/^[0-9a-zA-Z-_\s]{1,}$/',$data['numeroFactura']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el número de factura'));
        }
        else
            $data['numeroFactura'] = NULL;

        if(!empty($data['numeroNota'])){
            if(!preg_match('/^[0-9a-zA-Z-_\s]{1,}$/',$data['numeroNota']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes capturar el número de nota'));
        }
        else
            $data['numeroNota'] = NULL;

        if(!empty($data['fechaPago'])){
            if(!preg_match('/^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$/',$data['fechaPago']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la fecha de pago'));
        }
        else
            $data['fechaPago'] = NULL;

        if(!empty($data['fechaFactura'])){
            if(!preg_match('/^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$/',$data['fechaFactura']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Debes seleccionar la fecha de facturación'));
        }
        else
            $data['fechaFactura'] = NULL;

        if(!empty($data['comentarios'])){
            if(preg_match('/["\']{1,}/',$data['comentarios']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'El campo comentarios no puede contener comillas simples ni dobles'));
        }
        else
            $data['comentarios_tesoreria'] = NULL;

        if(!empty($data['retencion_isn'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['retencion_isn']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error en el campo retención ISN'));
            $data['retencion_isn']= str_replace(',','',$data['retencion_isn']);
        }
        else
            $data['retencion_isn']=NULL;
        
        if(!empty($data['total'])){
            if(!preg_match('/^[0-9,.]{4,}$/',$data['total']))
                return json_encode(array('error'=>true,'titulo'=>'Ocurrió un error','subtitulo'=>'Ocurrio un error en el campo total'));
            $data['total']= str_replace(',','',$data['total']);
        }
        else
            $data['total']=NULL;
  
        self::cargarDocumentos($data['id'],$data['url'],$data['documentsName'],$data['documentsTemp'],$data['documentsSize']);
        $respuesta = NominasModel::actualizarFacturacion($data,Tablas::nominas_liberacion());
        if(!empty($data['documentsName'])){
            $respuesta['archivos'] = self::archivosFacturacion($data['id'],$_SESSION['identificador']);
        }
        return json_encode($respuesta);
    }


    public static function comunicarNominista($nomina){

        $tesorero = NominasModel::getDatosNominista($nomina,Tablas::usuarios(),Tablas::sucursales(),Tablas::nominas_liberacion());

        $para = 'urielcrow@gmail.com';
		$titulo = 'Sistema de Intranet Asesores Empresariales - módulo nóminas';
		$mensajeFinal ='
					<html>
						<head>
							<title>Módulo de nóminas del sistema de Intranet de Asesores Empresariales!</title>
						</head>
						<body>
							<p style="font-size:18px;">Te informamos que el usuario <b>'.$tesorero["tesorero"].'</b> de la sucursal '.$tesorero["sucursal"].' del departamento de tesoreria le asigno a la nómina con folio: <b>'.$nomina.' </b> el estatus de pago con observación.</p> 
							<br>
                            <br>
                            <p>Comentarios:</p>
                            <p>'.$tesorero["comentarios"].'</p>
                            <p>Te sugerimos te pongas en contacto para que realices las operaciones convenientes.</p>
                            <hr>
							<h3><a href="http://www.intranet.asesoresempresariales.com.mx" target="blank">Asesores Empresariales</a></h3>
							<br>
							<img src="http://www.intranet.asesoresempresariales.com.mx/images/asesores.jpg">
						</body>
					</html>';
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$cabeceras .= 'From: <desarrollo@asesoresempresariales.com.mx>' . "\r\n";
		//$cabeceras .= 'CC: <arodriguez@asesoresempresariales.com.mx>' . "\r\n";  

		return mail($para, $titulo, $mensajeFinal, $cabeceras);
    }

    public static function actualizarFinanzasLiberacion($idNomina){
        
        $datos=NominasModel::actualizarFinanzasLiberacion($idNomina,Tablas::nominas_liberacion());
        $finanzas = NominasModel::datos2($datos["id_finanzas"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaFinanzas = explode ( " ", $datos['captura_finanzas']);

        $html='<div class="row">
                    <div class="col-md-6">
                        <span><b>Nombre:</b> '.$finanzas['nombre'].'</span>
                    </div>
                    <div class="col-md-6">
                        <span><b>Sucursal:</b> '.$finanzas['sucursal'].'</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span><b>Puesto:</b>  '.$finanzas['puesto'].' </span>
                    </div>
                    <div class="col-md-6">
                        <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaFinanzas[0],true).' - '.$fechaFinanzas[1].' </span></span>
                    </div>
                </div>';

                return json_encode(array('error'=>false,'html'=>$html));
    }

    public static function actualizarTesoreriaLiberacion($idNomina){
        
        $datos=NominasModel::actualizarTesoreriaLiberacion($idNomina,Tablas::nominas_liberacion());
        $tesoreria = NominasModel::datos2($datos["id_tesoreria"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaTesoreria = explode ( " ", $datos['captura_tesoreria']);

        $html='<div class="row">
                    <div class="col-md-6">
                        <span><b>Nombre:</b> '.$tesoreria['nombre'].'</span>
                    </div>
                    <div class="col-md-6">
                        <span><b>Sucursal:</b> '.$tesoreria['sucursal'].'</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span><b>Puesto:</b>  '.$tesoreria['puesto'].' </span>
                    </div>
                    <div class="col-md-6">
                        <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaTesoreria[0],true).' - '.$fechaTesoreria[1].' </span></span>
                    </div>
                </div>';

                return json_encode(array('error'=>false,'html'=>$html));
    }

    public static function actualizarNominasLiberacion($idNomina){
        
        $datos=NominasModel::actualizarNominasLiberacion($idNomina,Tablas::nominas_liberacion());
        $nominista = NominasModel::datos2($datos["id_nominista"],Tablas::usuarios(),Tablas::sucursales(),Tablas::puestos());
        $fechaNominista = explode ( " ", $datos['captura_nominista']);
        
        $html=' <div class="row">
                    <div class="col-md-6">
                        <span><b>Nombre:</b> '.$nominista['nombre'].'</span>
                    </div>
                    <div class="col-md-6">
                        <span><b>Sucursal:</b> '.$nominista['sucursal'].'</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span><b>Puesto:</b>  '.$nominista['puesto'].' </span>
                    </div>
                    <div class="col-md-6">
                        <span><b>Fecha y hora:</b> <span class="textoMay">'.MetodosDiversos::formatearFecha($fechaNominista[0],true).' - '.$fechaNominista[1].' </span></span>
                    </div>
                </div>';

        return json_encode(array('error'=>false,'html'=>$html));
    }

    public static function nominasManual($data,$target){

        if(($data["documentoNombre"]) != NULL){

            $info = new SplFileInfo($data["documentoNombre"]);
            $extensionImagen = $info->getExtension();

            if($extensionImagen == 'xlsx'){
                $aleatorio = mt_rand(100,99999999);
                $hoy = date("YmdHis"); 
                $nombreArchivo = $hoy.$aleatorio.'.xlsx';               
                $ruta = "../intranet/documentos-nominas/".$nombreArchivo;       
                if(!move_uploaded_file($data['documentoTemporal'], $ruta))
                    return array('error'=>'return',"titulo"=>'El archivo no pudo ser subido al servidor', "subtitulo"=>'error de carga');
                
                if($target === '/asesores/nominas')
                    return self::cargarRegistrosNominasNew($ruta);
                else if($target === '/asesores/finanzas')
                    return self::cargarRegistrosFinanzasNew($ruta);
                else if($target === '/asesores/tesoreria')
                    return self::cargarRegistrosTesoreriaNew($ruta);
                else if($target === '/asesores/facturacion'){
                     return self::cargarRegistrosFacturacionNew($ruta);
                }
                   
            }
            else
                return array('error'=>'return',"titulo"=>'Formato no válido', "subtitulo"=>'Formatos válidos: .xlsx');
        }
        else  
            return array('error'=>'return',"titulo"=>'No se ha cargado ningun archivo', "subtitulo"=>'Carga un archivo con formato: .xlsx');            
    }



    private function crearBitacoraLayout($modulo,$inicio,$start,$totalCorrectos,$totalErroneos,$dataCorrecta,$dataErroneos){
        $archivo = fopen("../intranet/logs/".$_SESSION['identificador'].".txt","a"); 
            if( $archivo !== true ) 
            {
                fwrite($archivo, "\n\rIntranet Asesores Empresariales!");
                fwrite($archivo, "\rMódulo: ".$modulo);
                fwrite($archivo, "\rProceso: Cargar masiva de comprobantes bancarios");
                fwrite($archivo, "\rFecha y hora inicial: ".$inicio);
                fwrite($archivo, "\r------------------------------------------------------------------------------------------------------------------------------\n");
                fwrite($archivo, "\rREGISTROS CARGADOS CORRECTAMENTE");
                fwrite($archivo, "\rTotal: ".$totalCorrectos);
                fwrite($archivo, "\rREGISTROS NO CARGADOS");
                fwrite($archivo, "\rTotal: ".$totalErroneos);
                fwrite($archivo, "\n\r------------------------------------------------------------------------------------------------------------------------------");
                fwrite($archivo, "\rResumen CORRECTOS: ");
                fwrite($archivo, "\n\r DATA CORRECTA\t\t\t\t");
                fwrite($archivo, "\n\r------------------------------------------------------------------------------------------------------------------------------");
                fwrite($archivo, "\n".$dataCorrecta);
                fwrite($archivo, "\n\r------------------------------------------------------------------------------------------------------------------------------");
                fwrite($archivo, "\rResumen NO CARGADOS: ");
                fwrite($archivo, "\n\r DATA INCORRECTA\t\t\t\t");
                fwrite($archivo, "\n\r------------------------------------------------------------------------------------------------------------------------------");
                fwrite($archivo,  "\n".$dataErroneos);
                fwrite($archivo, "\n\r------------------------------------------------------------------------------------------------------------------------------");
                fwrite($archivo, "\rEl proceso tardo: ".round((microtime(true) - $start),2)." segundos.\n\n\n\n\n");
                fflush($archivo); // Fuerza a que se escriban los datos pendientes en el buffer:
            }
            fclose($archivo);
            return;
    }

    public static function dataEliminar($id){
        return NominasModel::dataEliminar($id,Tablas::nominas_liberacion());
    }

    public static function dataLiberar($id){
        return NominasModel::dataLiberar($id,Tablas::nominas_liberacion());
    }
    
    /*public static function cargarRegistrosNominas2($ruta){
   
        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;

        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;

            if($flagOverFlow)
                break;

            $sqlCampos="(";
            $sqlValores="(";
            $flag=true;
            $flagObligatorio=false;
            $porcentaje=0;
            $subtotal=0;

            foreach ($fila->getCellIterator() as $celda) {
                
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila>=105){//sólo leemos 100 filas como máximo
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                    
                if($fila > 4){
                    
                    $respuesta = self::validar($columna,$fila,$valor,$nombresColumnas);
                    
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna == 'A'){
                             $finalizarLectura = true;
                             break;
                        } 
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true)//el campo esta vacio y no es obligatorio
                        true;

                    else{
                        $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                        $flag=false;
                        $sqlCampos.=$separador.self::convertirEncabezado($columna);
                        $sqlValores.=$separador.$respuesta['valor'];
                    }

                }

            }//fin de fila


            if( ($fila > 4 AND !$flagObligatorio) AND !$finalizarLectura ){
         
                $capturoCampo = ",id_nominista,captura_nominista";
                $capturoValor = ",".$_SESSION['identificador'].",NOW()";

                $data = $sqlCampos.$capturoCampo.") VALUES". $sqlValores.$capturoValor.")";
                $respuesta = NominasModel::insersionManual($data,Tablas::nominas_liberacion());
                if(!$respuesta){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    ++$totalRegistrosCorrectos;
            }   
            
        }

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="*******************************************************************************************\r\n";
        $etiquetas2.="*                                          ALERTAS                                        *\r\n";
        $etiquetas2.="*******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

        //$resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }*/

    /*public static function cargarRegistrosNominas2($ruta){
        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;

        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;

            if($flagOverFlow)
                break;

            $sqlCampos="(";
            $sqlValores="(";
            $flag=true;
            $flagObligatorio=false;
            $porcentaje=0;
            $subtotal=0;
            $tipoValidacion = '1'; //DE ACUALQUIER MANERA EL TIPO DE ESQUEMA ES OBLIGATORIO EN TODOS LOS CASOS
            $sinFactura = '';
            $comisionValor= '';

    
            foreach ($fila->getCellIterator() as $celda) {
                
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila>=105){//sólo leemos 100 filas como máximo
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                    
                if($fila > 4){
                    
                    if($columna != 'G')
                        $respuesta = self::validar($columna,$fila,$valor,$nombresColumnas,$tipoValidacion,$sinFactura);
                    else{
                        $comisionValor=$valor; //brincamos el campo comisión (columna G) para evaluarlo al final
                        continue;
                    }
                        
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna == 'A'){
                             $finalizarLectura = true;
                             break;
                        } 
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true)//el campo esta vacio y no es obligatorio
                        true;

                    else{  
                        if($columna == 'A')
                            $tipoValidacion = $respuesta['valor'];
                        else if($columna == 'H')
                            $sinFactura = $respuesta['valor'];
                        $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                        $flag=false;
                        $sqlCampos.=$separador.self::convertirEncabezado($columna);
                        $sqlValores.=$separador.$respuesta['valor'];
                    }

                }

            }//fin de fila


            if( ($fila > 4 AND !$flagObligatorio) AND !$finalizarLectura){
         
                $capturoCampo = ",id_nominista,captura_nominista";
                $capturoValor = ",".$_SESSION['identificador'].",NOW()";
                
                
                $respuesta = self::validar('G',$fila,$comisionValor,$nombresColumnas,$tipoValidacion,$sinFactura);
                if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                    $errores.= $respuesta['respuesta']."\r\n";
                    ++$totalRegistrosErrores;
                    continue;
                }
                else{//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                    if(($respuesta['alerta'])){
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    }
                    if($respuesta['valor'] != 1){
                        $sqlCampos.=','.self::convertirEncabezado('G');
                        $sqlValores.=','.$respuesta['valor'];
                    }
                        
                } 


                $data = $sqlCampos.$capturoCampo.") VALUES". $sqlValores.$capturoValor.")";
                $respuesta = NominasModel::insersionManual($data,Tablas::nominas_liberacion());
                if(!$respuesta){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    ++$totalRegistrosCorrectos;


            }   
            
        }

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="*******************************************************************************************\r\n";
        $etiquetas2.="*                                          ALERTAS                                        *\r\n";
        $etiquetas2.="*******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

        //$resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }*/

    public static function cargarRegistrosNominas($ruta){
        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;

        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;

            if($flagOverFlow)
                break;

            $sqlCampos="(";
            $sqlValores="(";
            $flag=true;
            $flagObligatorio=false;
            $porcentaje=0;
            $subtotal=0;
            $tipoValidacion = '1'; //DE ACUALQUIER MANERA EL TIPO DE ESQUEMA ES OBLIGATORIO EN TODOS LOS CASOS
            $sinFactura = '';
            $comisionValor= '';

    
            foreach ($fila->getCellIterator() as $celda) {
                
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila>=105){//sólo leemos 100 filas como máximo
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                    
                if($fila > 4){
                    
                    if($columna != 'G')
                        $respuesta = self::validar($columna,$fila,$valor,$nombresColumnas,$tipoValidacion,$sinFactura);
                    else{
                        $comisionValor=$valor; //brincamos el campo comisión (columna G) para evaluarlo al final
                        continue;
                    }
                        
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna == 'A'){
                             $finalizarLectura = true;
                             break;
                        } 
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true)//el campo esta vacio y no es obligatorio
                        true;

                    else{  
                        if($columna == 'A')
                            $tipoValidacion = $respuesta['valor'];
                        else if($columna == 'H')
                            $sinFactura = $respuesta['valor'];
                        $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                        $flag=false;
                        $sqlCampos.=$separador.self::convertirEncabezado($columna);
                        $sqlValores.=$separador.$respuesta['valor'];
                    }

                }

            }//fin de fila


            if( ($fila > 4 AND !$flagObligatorio) AND !$finalizarLectura){
         
                $capturoCampo = ",id_nominista,captura_nominista";
                $capturoValor = ",".$_SESSION['identificador'].",NOW()";
                
                
                $respuesta = self::validar('G',$fila,$comisionValor,$nombresColumnas,$tipoValidacion,$sinFactura);
                if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                    $errores.= $respuesta['respuesta']."\r\n";
                    ++$totalRegistrosErrores;
                    continue;
                }
                else{//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                    if(($respuesta['alerta'])){
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    }
                    if($respuesta['valor'] != 1){
                        $sqlCampos.=','.self::convertirEncabezado('G');
                        $sqlValores.=','.$respuesta['valor'];
                    }
                        
                } 


                $data = $sqlCampos.$capturoCampo.") VALUES". $sqlValores.$capturoValor.")";
                $respuesta = NominasModel::insersionManual($data,Tablas::nominas_liberacion());
                if(!$respuesta['respuesta']){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    $totalRegistrosCorrectos = $totalRegistrosCorrectos + intval($respuesta['total']);

            }   
            
        }

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="*******************************************************************************************\r\n";
        $etiquetas2.="*                                          ALERTAS                                        *\r\n";
        $etiquetas2.="*******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

        //$resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }

    public static function cargarRegistrosNominasNew($ruta){
        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(1);
        $celda = $hojaActual->getCell('MNN1000');
        $valorRaw = $celda->getValue();

        if($valorRaw!=="ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_NOMINAS"){
            unlink($ruta);
            return array("data"=>'Necesitas utilizar la última versión del layout de Nóminas (versión 28-02-2020), se anexo el cálculo automático de la retención del ISN',"totalCorrectos"=>0,"totalErrores"=>0,"totalAlertas"=>0);
        }
            
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;

        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;

            if($flagOverFlow)
                break;

            $sqlCampos="(";
            $sqlValores="(";
            $flag=true;
            $flagObligatorio=false;
            $descuentossy=0;
            $descuentosasesores=0;
            $descuentosterceros=0;
            $subtotal = 0;
            $retencion = false;
            $retencionIsn = false;
            $retencionMonto = 0;
            $impuestoEstatal = 0;
            $tipoValidacion = '1'; //DE ACUALQUIER MANERA EL TIPO DE ESQUEMA ES OBLIGATORIO EN TODOS LOS CASOS
            $sinFactura = '';
            $comisionValor= '';

    
            foreach ($fila->getCellIterator() as $celda) {
                
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila>=105){//sólo leemos 100 filas como máximo
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                    
                if($fila > 4){
                    
                    if($columna != 'G')
                        $respuesta = self::validarNew($columna,$fila,$valor,$nombresColumnas,$tipoValidacion,$sinFactura);
                    else{
                        $comisionValor=$valor; //brincamos el campo comisión (columna G) para evaluarlo al final
                        continue;
                    }

                    if($columna == 'Z' AND $retencionIsn){//En caso de que se seleccione la opción de calcular la retención del ISN pero no capturo o lo hizo con otro formato el IMPUESTO ESTATAL
                        if($respuesta['valor']===true || $respuesta['alerta']){
                            $errores.= 'La fila: '.$fila.' no pudo ser guardada porqué no se capturó el monto del IMPUESTO ESTATAL y se seleccionó la opción del calculo automático de la RETENCIÖN DEL ISN'."\r\n";
                            ++$totalRegistrosErrores;
                            $flagObligatorio = true;
                            continue;
                        }
                    }

                        
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna == 'A'){
                             $finalizarLectura = true;
                             break;
                        } 
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true)//el campo esta vacio y no es obligatorio
                        true;

                    else{  
                        if($columna == 'A')
                            $tipoValidacion = $respuesta['valor'];
                        else if($columna == 'H')
                            $sinFactura = $respuesta['valor'];
                        else if($columna == 'I' AND $respuesta['valor'] != "")
                            $subtotal = str_replace("\"", "", $respuesta['valor']);
                        else if($columna == 'J' AND $respuesta['valor'] == 1)
                            $retencion = true;
                        else if($columna == 'K' AND $respuesta['valor'] == 1){
                            $retencion = true;//Retención IVA
                            $retencionIsn = true;
                        }
                        else if( ( $columna == 'W' || $columna == 'AO')  AND $respuesta['valor'] != "" )
                            $descuentossy +=  str_replace("\"", "", $respuesta['valor']);
                        else if( ( $columna == 'AU' || $columna == 'AV' || $columna == 'AW' || $columna == 'AX' || $columna == 'AY' || $columna == 'AZ' || $columna == 'BC' || $columna == 'BD' || $columna == 'BE' || $columna == 'BF' || $columna == 'BG')  AND $respuesta['valor'] != "" )
                            $descuentosasesores +=  str_replace("\"", "", $respuesta['valor']);
                        else if( ( $columna == 'BA' || $columna == 'BB'  || $columna == 'BH')  AND $respuesta['valor'] != "" )
                            $descuentosterceros +=  str_replace("\"", "", $respuesta['valor']);

                        $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                        $flag=false;

                        if($columna == 'Z' AND $retencionIsn)
                            $impuestoEstatal =  str_replace("\"", "", $respuesta['valor']);

                        if($columna != 'J' AND $columna != 'K'){
                            $sqlCampos.=$separador.self::convertirEncabezadoNew($columna);
                            $sqlValores.=$separador.$respuesta['valor'];
                        }

                        
                    }
                }

            }//fin de fila


            if( ($fila > 4 AND !$flagObligatorio) AND !$finalizarLectura){
         
                $capturoCampo = ",id_nominista,captura_nominista";
                $capturoValor = ",".$_SESSION['identificador'].",NOW()";

                if($subtotal != 0){
                    if(!$retencion)
                        $total = number_format( ($subtotal * 1.16) ,2, '.', '');
                    else{  
                        $total = number_format( ($subtotal * 1.10) - $impuestoEstatal ,2, '.', '');
                        $retencionMonto = number_format( ($subtotal * 0.06) ,2, '.', '');
                    }

                    if($retencionIsn){
                        $capturoCampo .= ',retencion_isn';
                        $capturoValor .= ",\"".number_format( ($impuestoEstatal) ,2, '.', '')."\"";
                    }
                        
                    $iva = number_format( ($subtotal * 0.16) ,2, '.', ''); 
                    $capturoCampo .= ',iva,total';
                    $capturoValor .= ",\"".$iva."\"";
                    $capturoValor .= ",\"".$total."\"";

                    if($retencion){
                        $capturoCampo .= ',retencion_iva';
                        $capturoValor .= ",\"".$retencionMonto."\"";
                    }
                    
                }
                if($descuentossy > 0){
                    $capturoCampo .= ',descuentos_sys';
                    $capturoValor .= ",\"".number_format($descuentossy,2, '.', '')."\"";
                }
                if($descuentosasesores > 0){
                    $capturoCampo .= ',descuentos_asesores';
                    $capturoValor .= ",\"".number_format($descuentosasesores,2, '.', '')."\"";
                }
                if($descuentosterceros > 0){
                    $capturoCampo .= ',descuentos_terceros';
                    $capturoValor .= ",\"".number_format($descuentosterceros,2, '.', '')."\"";
                }
                
                $respuesta = self::validarNew('G',$fila,$comisionValor,$nombresColumnas,$tipoValidacion,$sinFactura);
                if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                    $errores.= $respuesta['respuesta']."\r\n";
                    ++$totalRegistrosErrores;
                    continue;
                }
                else{//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                    if(($respuesta['alerta'])){
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    }
                    if($respuesta['valor'] != 1){
                        $sqlCampos.=','.self::convertirEncabezadoNew('G');
                        $sqlValores.=','.$respuesta['valor'];
                    }  
                } 
                $data = $sqlCampos.$capturoCampo.") VALUES". $sqlValores.$capturoValor.")";
                $respuesta = NominasModel::insersionManual($data,Tablas::nominas_liberacion());
                if(!$respuesta['respuesta']){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    $totalRegistrosCorrectos = $totalRegistrosCorrectos + intval($respuesta['total']);
            }   
        }

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="*******************************************************************************************\r\n";
        $etiquetas2.="*                                          ALERTAS                                        *\r\n";
        $etiquetas2.="*******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

        //$resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }

    /*public static function cargarRegistrosNominasNew($ruta){
        $documento = IOFactory::load($ruta);

        $hojaActual = $documento->getSheet(1);
        $celda = $hojaActual->getCell('MNN1000');
        $valorRaw = $celda->getValue();

        if($valorRaw!=="ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU87")
            return array("data"=>'Necesitas utilizar la última versión del layout (versión 20012020)',"totalCorrectos"=>0,"totalErrores"=>0,"totalAlertas"=>0);
        
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;

        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;

            if($flagOverFlow)
                break;

            $sqlCampos="(";
            $sqlValores="(";
            $flag=true;
            $flagObligatorio=false;
            $descuentossy=0;
            $descuentosasesores=0;
            $descuentosterceros=0;
            $subtotal = 0;
            $retencion = false;
            $retencionMonto = 0;
            $tipoValidacion = '1'; //DE ACUALQUIER MANERA EL TIPO DE ESQUEMA ES OBLIGATORIO EN TODOS LOS CASOS
            $sinFactura = '';
            $comisionValor= '';

    
            foreach ($fila->getCellIterator() as $celda) {
                
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila>=105){//sólo leemos 100 filas como máximo
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                    
                if($fila > 4){
                    
                    if($columna != 'G')
                        $respuesta = self::validarNew($columna,$fila,$valor,$nombresColumnas,$tipoValidacion,$sinFactura);
                    else{
                        $comisionValor=$valor; //brincamos el campo comisión (columna G) para evaluarlo al final
                        continue;
                    }
                        
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna == 'A'){
                             $finalizarLectura = true;
                             break;
                        } 
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true)//el campo esta vacio y no es obligatorio
                        true;

                    else{  
                        if($columna == 'A')
                            $tipoValidacion = $respuesta['valor'];
                        else if($columna == 'H')
                            $sinFactura = $respuesta['valor'];
                        else if($columna == 'I' AND $respuesta['valor'] != "")
                            $subtotal = str_replace("\"", "", $respuesta['valor']);
                        else if($columna == 'J' AND $respuesta['valor'] == 1)
                            $retencion = true;
                        else if( ( $columna == 'V' || $columna == 'AN')  AND $respuesta['valor'] != "" )
                            $descuentossy +=  str_replace("\"", "", $respuesta['valor']);
                        else if( ( $columna == 'AS' || $columna == 'AT' || $columna == 'AU' || $columna == 'AV' || $columna == 'AW' || $columna == 'AX' || $columna == 'BA' || $columna == 'BB' || $columna == 'BC' || $columna == 'BD' || $columna == 'BE')  AND $respuesta['valor'] != "" )
                            $descuentosasesores +=  str_replace("\"", "", $respuesta['valor']);
                        else if( ( $columna == 'AY' || $columna == 'AZ'  || $columna == 'BF')  AND $respuesta['valor'] != "" )
                            $descuentosterceros +=  str_replace("\"", "", $respuesta['valor']);
                        $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                        $flag=false;

                        if($columna != 'J'){
                            $sqlCampos.=$separador.self::convertirEncabezadoNew($columna);
                            $sqlValores.=$separador.$respuesta['valor'];
                        }
                        
                    }

                }

            }//fin de fila


            if( ($fila > 4 AND !$flagObligatorio) AND !$finalizarLectura){
         
                $capturoCampo = ",id_nominista,captura_nominista";
                $capturoValor = ",".$_SESSION['identificador'].",NOW()";

                if($subtotal != 0){
                    if(!$retencion)
                        $total = number_format( ($subtotal * 1.16) ,2, '.', ''); 
                    else{
                        $total = number_format( ($subtotal * 1.10) ,2, '.', '');
                        $retencionMonto = number_format( ($subtotal * 0.06) ,2, '.', '');
                    }

                    $iva = number_format( ($subtotal * 0.16) ,2, '.', ''); 
                    $capturoCampo .= ',iva,total';
                    $capturoValor .= ",\"".$iva."\"";
                    $capturoValor .= ",\"".$total."\"";

                    if($retencion){
                        $capturoCampo .= ',retencion_iva';
                        $capturoValor .= ",\"".$retencionMonto."\"";
                    }
                }
                if($descuentossy > 0){
                    $capturoCampo .= ',descuentos_sys';
                    $capturoValor .= ",\"".number_format($descuentossy,2, '.', '')."\"";
                }
                if($descuentosasesores > 0){
                    $capturoCampo .= ',descuentos_asesores';
                    $capturoValor .= ",\"".number_format($descuentosasesores,2, '.', '')."\"";
                }
                if($descuentosterceros > 0){
                    $capturoCampo .= ',descuentos_terceros';
                    $capturoValor .= ",\"".number_format($descuentosterceros,2, '.', '')."\"";
                }
                
                $respuesta = self::validarNew('G',$fila,$comisionValor,$nombresColumnas,$tipoValidacion,$sinFactura);
                if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                    $errores.= $respuesta['respuesta']."\r\n";
                    ++$totalRegistrosErrores;
                    continue;
                }
                else{//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                    if(($respuesta['alerta'])){
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    }
                    if($respuesta['valor'] != 1){
                        $sqlCampos.=','.self::convertirEncabezadoNew('G');
                        $sqlValores.=','.$respuesta['valor'];
                    }  
                } 

                $data = $sqlCampos.$capturoCampo.") VALUES". $sqlValores.$capturoValor.")";
                $respuesta = NominasModel::insersionManual($data,Tablas::nominas_liberacion());
                if(!$respuesta['respuesta']){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    $totalRegistrosCorrectos = $totalRegistrosCorrectos + intval($respuesta['total']);

            }   
            
        }

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="*******************************************************************************************\r\n";
        $etiquetas2.="*                                          ALERTAS                                        *\r\n";
        $etiquetas2.="*******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

        //$resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }*/

    public static function cargarRegistrosFinanzas($ruta){
        
        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;
        $filasALeer = intval(Nominas::marcadores(1));

        
        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;
  
            if($flagOverFlow)
                break;

            $sqlCampos="";
            $sqlValores="";
            $sqlWhere="";
            $flag=true;
            $flagObligatorio=false;
            //$porcentaje=0;
            //$subtotal=0;
            $contarColumna = 0;

            $campoVacio = 0;

            foreach ($fila->getCellIterator() as $celda) {
                $contarColumna++;
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila > $filasALeer + 3){
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                
                if( ($fila > 3) AND ($contarColumna === 1 || $contarColumna >= 20) ){

                    $respuesta = self::validar2($columna,$fila,$valor,$nombresColumnas);
                    
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        /*if( $columna === 'A'){
                             $finalizarLectura = true;
                             break;
                        } */
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true){//el campo esta vacio y no es obligatorio{
                        $campoVacio++;
                         true;
                    }
                       
                        
                    else{
                        if($columna === "A")
                            $sqlWhere = ' WHERE id = '.intval($respuesta['valor']);
                        else{
                            $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                            $flag=false;
                            $sqlCampos.=$separador.self::convertirEncabezado2($columna) ."=".$respuesta['valor'];
                        }
                        
                    }

                }

            }//fin de fila


            if( ($fila > 3 AND !$flagObligatorio) AND !$finalizarLectura AND ($contarColumna === 1 || $contarColumna >= 20) AND $campoVacio!= 8){
         
                $capturoCampo = ",id_finanzas = ".$_SESSION['identificador'].",captura_finanzas = NOW()";
                
                $data = $sqlCampos.$capturoCampo.$sqlWhere;
                $respuesta = NominasModel::insersionManualFinanzas($data,Tablas::nominas_liberacion());
                if(!$respuesta['respuesta']){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    $totalRegistrosCorrectos  = $totalRegistrosCorrectos + intval($respuesta['total']);
            }   
            
        }
        

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="******************************************************************************************\r\n";
        $etiquetas2.="*                                         ALERTAS                                        *\r\n";
        $etiquetas2.="******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

        //$resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }

    public static function cargarRegistrosFinanzasNew($ruta){
        
        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(1);
        $celda = $hojaActual->getCell('MNN1000');
        $valorRaw = $celda->getValue();

        if($valorRaw!=="ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_FINANZAS"){
            unlink($ruta);
            return array("data"=>'Necesitas utilizar la última versión del layout de Finanzas (versión 28-02-2020), se anexo el cálculo automático de la retención del ISN',"totalCorrectos"=>0,"totalErrores"=>0,"totalAlertas"=>0);
        }
           
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;
        $filasALeer = intval(Nominas::marcadores(1));

        
        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;
  
            if($flagOverFlow)
                break;

            $sqlCampos="";
            $sqlValores="";
            $sqlWhere="";
            $flag=true;
            $flagObligatorio=false;
            $contarColumna = 0;

            $campoVacio = 0;

            foreach ($fila->getCellIterator() as $celda) {
                $contarColumna++;
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila > $filasALeer + 3){
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                
                if( ($fila > 3) AND ($contarColumna === 1 || $contarColumna >= 30) ){

                    $respuesta = self::validar2New($columna,$fila,$valor,$nombresColumnas);
                    
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna === 'A'){
                             $finalizarLectura = true;
                             break;
                        }
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true){//el campo esta vacio y no es obligatorio{
                        $campoVacio++;
                         true;
                    }
                       
                        
                    else{
                        if($columna === "A")
                            $sqlWhere = ' WHERE id = '.intval($respuesta['valor']);
                        else{
                            $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                            $flag=false;
                            $sqlCampos.=$separador.self::convertirEncabezado2New($columna) ."=".$respuesta['valor'];
                        } 
                    }
                }

            }//fin de fila


            if( ($fila > 3 AND $fila <= ($filasALeer + 3) AND !$flagObligatorio) AND !$finalizarLectura AND ($contarColumna === 1 || $contarColumna >= 30) AND $campoVacio != 7){
         
                $capturoCampo = ",id_finanzas = ".$_SESSION['identificador'].",captura_finanzas = NOW()";
                
                $data = $sqlCampos.$capturoCampo.$sqlWhere;
                $respuesta = NominasModel::insersionManualFinanzas($data,Tablas::nominas_liberacion());
                if(!$respuesta['respuesta']){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    $totalRegistrosCorrectos  = $totalRegistrosCorrectos + intval($respuesta['total']);
            }   
            
        }
        

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="******************************************************************************************\r\n";
        $etiquetas2.="*                                         ALERTAS                                        *\r\n";
        $etiquetas2.="******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

        //$resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }

    public static function cargarRegistrosTesoreria($ruta){
        
        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;
        $filasALeer = intval(Nominas::marcadores(4));

        
        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;
  
            if($flagOverFlow)
                break;

            $sqlCampos="";
            $sqlValores="";
            $sqlWhere="";
            $flag=true;
            $flagObligatorio=false;
            //$porcentaje=0;
            //$subtotal=0;
            $contarColumna = 0;

            $campoVacio = 0;

            foreach ($fila->getCellIterator() as $celda) {
                $contarColumna++;
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila > $filasALeer + 3){
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                

                if( ($fila > 3) AND ($contarColumna === 1 || $contarColumna >= 30) ){

                    
                    /*if($contarColumna == 30 AND $valor == ''){
                        continue;
                    }*/
                        

                    $respuesta = self::validar3($columna,$fila,$valor,$nombresColumnas);
                    
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        /*if( $columna === 'A'){
                             $finalizarLectura = true;
                             break;
                        } */
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true){//el campo esta vacio y no es obligatorio{
                        $campoVacio++;
                         true;
                    }
                       
                        
                    else{
                        if($columna === "A")
                            $sqlWhere = ' WHERE id = '.intval($respuesta['valor']);
                        else{
                            $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                            $flag=false;
                            $sqlCampos.=$separador.self::convertirEncabezado3($columna) ."=".$respuesta['valor'];
                        }
                        
                    }

                }

            }//fin de fila


            if( ($fila > 3 AND !$flagObligatorio) AND !$finalizarLectura AND ($contarColumna === 1 || $contarColumna >= 30) AND $campoVacio!= 2){
         
                $capturoCampo = ",id_tesoreria = ".$_SESSION['identificador'].",captura_tesoreria = NOW()";
                
                $data = $sqlCampos.$capturoCampo.$sqlWhere;
                $respuesta = NominasModel::insersionManualTesoreria($data,Tablas::nominas_liberacion());
                if(!$respuesta){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    $totalRegistrosCorrectos  = $totalRegistrosCorrectos + intval($respuesta['total']);
            }   
            
        }
        

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="******************************************************************************************\r\n";
        $etiquetas2.="*                                         ALERTAS                                        *\r\n";
        $etiquetas2.="******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

       // $resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }

    public static function cargarRegistrosTesoreriaNew($ruta){
        
        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(1);
        $celda = $hojaActual->getCell('MNN1000');
        $valorRaw = $celda->getValue();

        if($valorRaw!=="ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_TESORERIA"){
            unlink($ruta);
            return array("data"=>'Necesitas utilizar la última versión del layout de Tesoreria (versión 28-02-2020), se anexo el cálculo automático de la retención del ISN',"totalCorrectos"=>0,"totalErrores"=>0,"totalAlertas"=>0);
        }
            
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;
        $filasALeer = intval(Nominas::marcadores(4));

        
        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;
  
            if($flagOverFlow)
                break;

            $sqlCampos="";
            $sqlValores="";
            $sqlWhere="";
            $flag=true;
            $flagObligatorio=false;
            $contarColumna = 0;

            $campoVacio = 0;

            foreach ($fila->getCellIterator() as $celda) {
                $contarColumna++;
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila > $filasALeer + 3){
                    $flagOverFlow = true;
                    break;
                }

                if($fila === 3)//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                

                if( ($fila > 3) AND ($contarColumna === 1 || $contarColumna >= 42) ){

                    
                    /*if($contarColumna == 30 AND $valor == ''){
                        continue;
                    }*/
                        

                    $respuesta = self::validar3New($columna,$fila,$valor,$nombresColumnas);
                    
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna === 'A'){
                             $finalizarLectura = true;
                             break;
                        } 
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true){//el campo esta vacio y no es obligatorio{
                        $campoVacio++;
                         true;
                    }
                    else{
                        if($columna === "A")
                            $sqlWhere = ' WHERE id = '.intval($respuesta['valor']);
                        else{
                            $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                            $flag=false;
                            $sqlCampos.=$separador.self::convertirEncabezado3New($columna) ."=".$respuesta['valor'];
                        }
                        
                    }

                }

            }//fin de fila


            if( ($fila > 3 AND !$flagObligatorio) AND !$finalizarLectura AND ($contarColumna === 1 || $contarColumna >= 42) AND $campoVacio!= 2){
         
                $capturoCampo = ",id_tesoreria = ".$_SESSION['identificador'].",captura_tesoreria = NOW()";
                
                $data = $sqlCampos.$capturoCampo.$sqlWhere;
                $respuesta = NominasModel::insersionManualTesoreria($data,Tablas::nominas_liberacion());
                if(!$respuesta){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    $totalRegistrosCorrectos  = $totalRegistrosCorrectos + intval($respuesta['total']);
            }   
            
        }
        

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="******************************************************************************************\r\n";
        $etiquetas2.="*                                         ALERTAS                                        *\r\n";
        $etiquetas2.="******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

       // $resultado.=$data;
 
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }

    public static function cargarRegistrosFacturacionNew($ruta){
        $inicio = date("d-m-Y H:i:s");
        $start = microtime(true);

        $documento = IOFactory::load($ruta);
        $hojaActual = $documento->getSheet(1);
        $celda = $hojaActual->getCell('MNN1000');
        $valorRaw = $celda->getValue();

        if($valorRaw!=="ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_FACTURACION"){
            unlink($ruta);
            return array("data"=>'Necesitas utilizar la última versión del layout de Tesoreria (versión 28-02-2020), se anexo el cálculo automático de la retención del ISN',"totalCorrectos"=>0,"totalErrores"=>0,"totalAlertas"=>0);
        }
            
        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;
        $filasALeer = intval(Nominas::marcadores(10));
        $log;
        $logError;

        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;
  
            if($flagOverFlow)
                break;

            $sqlCampos="";
            $sqlValores="";
            $sqlWhere="";
            $flag=true;
            $flagObligatorio=false;
            $contarColumna = 0;
            //$campoVacio = 0;
            $campoValido = 0;

            $temp='';
            $subtotal = 0;
                //$iva=0;
                //$retencionIva = 0;
            $retencionIsn = "";
                //$total = 0;

            foreach ($fila->getCellIterator() as $celda) {
                $contarColumna++;
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                
                if($fila > $filasALeer + 3){
                    $flagOverFlow = true;
                    break;
                }

                if($fila < 3)//Filas 1 y 2 son ignoradas
                    continue;

                if($fila === 3){//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                    continue;
                }

                if( $contarColumna > 1 AND $contarColumna < 42 )//Leemos a partir de las columnas que seran modificables
                    continue;

                if($contarColumna === 42)
                    $subtotal = str_replace(',','',$valor);
                      
                else if($contarColumna === 43)
                    continue;
                    //$retencionIva =  str_replace(',','',$valor);
                        
                else if($contarColumna === 44)
                    continue;
                    //$iva = str_replace(',','',$valor);
                
                else if($contarColumna === 45)//ignoro el Total
                    continue;
                
                else {

                    $respuesta = self::validar4New($columna,$fila,$valor,$nombresColumnas);
                    
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna === 'A'){
                             $finalizarLectura = true;
                             break;
                        } 
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true;
                        continue;
                    }

                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    } 
                        
                    else if($respuesta['valor']===true)//el campo esta vacio y no es obligatorio{
                        continue;
                        //$campoVacio++;
                      
                    else{
                        if($columna === "A")
                            $sqlWhere = ' WHERE id = '.intval($respuesta['valor']);
                        else{
                            $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                            $flag=false;
                            if($columna === "AT")//verifico si existe retención del ISN
                                $retencionIsn = str_replace("\"", "", $respuesta['valor']); 
                            else
                                $campoValido++;//necesito que existan al menos 2 cambios en el registro para asignarlo, no se cuenta la retención ISN porque esa puede venir o no capturada desde otro módulo
                            $sqlCampos.=$separador.self::convertirEncabezado4New($columna) ."=".$respuesta['valor'];
                           
                        }
                        
                    }

                }

            }//fin de fila


            if( ($fila > 3 AND !$flagObligatorio) AND !$finalizarLectura AND ($contarColumna === 1 || $contarColumna >= 46) AND $campoValido >= 3){ 
                if($retencionIsn !== ""){//recalculo el total en caso de que exista el ISN
                    $total = number_format( ($subtotal * 1.10) ,2, '.', '') - $retencionIsn;
                    $temp = ',total = "'.$total.'"';
                }
                 
               
                $capturoCampo = ",id_facturacion = ".$_SESSION['identificador'].",captura_facturacion = NOW()";
                
                $data = $sqlCampos.$capturoCampo.$temp.$sqlWhere;

                $respuesta = NominasModel::insersionManualFacturacion($data,Tablas::nominas_liberacion());
                if(!$respuesta){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                    $logError .= $data."\r";
                }
                else{
                     $totalRegistrosCorrectos  = $totalRegistrosCorrectos + intval($respuesta['total']);
                     if(intval($respuesta['total']) === 1)
                        $log .= $data."\r";//Se manda a labitacora del usuario para referencia de los registro que realizó correctos
                }
                
            }   
        }
        

        $etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="******************************************************************************************\r\n";
        $etiquetas2.="*                                         ALERTAS                                        *\r\n";
        $etiquetas2.="******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";

        //$resultado.=$data;
 
        self::crearBitacoraLayout('FACTURACIÓN',$inicio,$start,$totalRegistrosCorrectos,$totalRegistrosErrores,$log,$logError);
        unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }

    public static function cargarRegistrosFacturacionNew___($ruta){
    
        $documento = IOFactory::load($ruta);
       
        $hojaActual = $documento->getSheet(1);
        $celda = $hojaActual->getCell('MNN1000');
        $valorRaw = $celda->getValue();
       
        if($valorRaw!=="ADAssADe4632233_poid4655RSESRShhgtopodi89987kdjhdhcccv_ttr#$5yuuihuhuioyuioHHAFhh6rhYUU875yuuihuhuioyuioHHAFhh6rhYUU87___7uKpoHu_FACTURACION"){
            unlink($ruta);
            return array("data"=>'Necesitas utilizar la última versión del layout de Facturación (versión 28-02-2020), se anexo el cálculo automático de la retención del ISN',"totalCorrectos"=>0,"totalErrores"=>0,"totalAlertas"=>0);
        }

        $hojaActual = $documento->getSheet(0);

        $errores='';
        $alertas='';
        $data='';
        $resultado='';
        $nombresColumnas=array();
        $flagOverFlow = false;
        $finalizarLectura = false;
        $totalRegistrosCorrectos=0;
        $totalRegistrosErrores=0;
        $totalRegistrosAlertas=0;
        $filasALeer = intval(self::marcadores(10));

        foreach ($hojaActual->getRowIterator() as $fila){
           
            if($finalizarLectura)//si antes del máximo de lecturas hay celdas en blanco, entonces salgo
                break;
  
            if($flagOverFlow)//solo leemos las filas que se obtuvieron de la consulta
                break;

            $sqlCampos="";
            $sqlValores="";
            $sqlWhere="";
            $flag=true;
            $flagObligatorio=false;
            $contarColumna = 0;
            $campoVacio = 0;
            $registrado = false;

           /* $subtotal = 0;
            $iva=0;
            $retencionIva = 0;
            $retencionIsn = "";
            $total = 0;*/

            foreach ($fila->getCellIterator() as $celda) {
                $contarColumna++;
                $fila = $celda->getRow();
                $columna = $celda->getColumn();
                $valor=$celda->getFormattedValue();

                if($fila > $filasALeer + 3){ // Cantidad de filas a leer
                    $flagOverFlow = true;//si pasamos el límite salimos
                    break;
                }

                if($fila === 3){//leemos el nombre de cada columna
                    $nombresColumnas["$columna"]="$valor";
                    //continue;
                }
                    /******A partir de la fila 4 */
                /*if($contarColumna > 1 && $contarColumna < 46)//Leemos a partir de las columnas que seran modificables
                    continue;

                if($contarColumna === 42)
                    $subtotal = str_replace(',','',$valor);
                      
                else if($contarColumna === 43)
                    $retencionIva =  str_replace(',','',$valor);
                        
                else if($contarColumna === 44)
                    $iva = str_replace(',','',$valor);
                     
                else if($contarColumna === 45)
                    $total = str_replace(',','',$valor);

                else{ */
                if($fila > 3 AND ($contarColumna === 1 || $contarColumna >= 46) ){
                    $respuesta = self::validar4New($columna,$fila,$valor,$nombresColumnas); //Validamos el tipo de dato que contendra cada campo
                    
                    if(!$respuesta['error']){//en caso de false, quiere decir que es necesario que el campo se capture y que ademas cumpla con el formato, por lo tanto no se realizará ese registro
                        if( $columna === 'A'){ //Indica que la fila no tiene datos
                                $finalizarLectura = true;
                                break;
                        } 
                        $errores.= $respuesta['respuesta']."\r\n";
                        ++$totalRegistrosErrores;
                        $flagObligatorio = true; //No registramos la fila en caso de que tuviera un campo oblogatorio y no fue capturado y/o no tien el formato incorrecto
                        continue;//continuamos con la fila siguiente
                    }
    
                    if($respuesta['alerta']){//Ese campo se guarda en alertas pero permite que los demas campos de su fila se guarden, se capturo con un formato incorrecto pero no es de caracter obligatorio
                        $alertas.=$respuesta['respuesta']."\r\n";
                        ++$totalRegistrosAlertas;
                    }   
                    else if($respuesta['valor']===true)//el campo esta vacio y no es obligatorio
                        $campoVacio++;
                       
                    else{
                        if($columna === "A")
                            $sqlWhere = ' WHERE id = '.intval($respuesta['valor']);
                        else {
                            $separador = $flag === true ? '':',';//al primer campo del registro no le pongo coma para crear el comando sql de inserción
                            $flag=false;
    
                            /*if($columna === "AT")//verifico si existe retención del ISN
                                $retencionIsn = str_replace("\"", "", $respuesta['valor']);*/
                            
                            $sqlCampos.=$separador.self::convertirEncabezado4New($columna) ."=".$respuesta['valor'];
                         
                        }
                    }
                }

            }//fin de fila


            if( ($fila > 3 AND !$flagObligatorio) AND !$finalizarLectura AND ($contarColumna === 1 || $contarColumna >= 46) AND $campoVacio !=2){
                /*if($retencionIsn !== "")//recalculo el total en caso de que se haya actualizado el ISN
                    $total  = $total;//($subtotal + $iva) - $retencionIva - $retencionIsn;

                $capturoCampo .= ",total = \"".$total."\"";*/
                $capturoCampo .= ",id_facturacion = ".$_SESSION['identificador'].",captura_facturacion = NOW()";
                
                $data .= $sqlCampos.$capturoCampo.$sqlWhere;
               /* $respuesta = NominasModel::insersionManualTesoreria($data,Tablas::nominas_liberacion());
                if(!$respuesta){
                    $errores.="La fila: ".$fila." no pudo ser guardada en la base de datos (error SQL).\r\n";
                    ++$totalRegistrosErrores;
                }
                else
                    $totalRegistrosCorrectos  = $totalRegistrosCorrectos + intval($respuesta['total']);*/ 
            }   
            
        }
        

       /*$etiquetas = $etiquetas2 = "";

        $etiquetas.="*******************************************************************************************\r\n";
        $etiquetas.="*                                          ERRORES                                        *\r\n";
        $etiquetas.="*******************************************************************************************\r\n";

        if(!empty($errores))
            $resultado.=$etiquetas.$errores."\r\n";

        $etiquetas2.="******************************************************************************************\r\n";
        $etiquetas2.="*                                         ALERTAS                                        *\r\n";
        $etiquetas2.="******************************************************************************************\r\n";

        if(!empty($alertas))
            $resultado.=$etiquetas2.$alertas."\r\n";*/

        $resultado.=$data;
 
        //unlink($ruta);
        return array("data"=>$resultado,"totalCorrectos"=>$totalRegistrosCorrectos,"totalErrores"=>$totalRegistrosErrores,"totalAlertas"=>$totalRegistrosAlertas);
    }

    public static function validar($columna,$fila,$valor,$nombresColumnas,$tipoValidacion,$sinFactura){
        
        $encabezado = $nombresColumnas["$columna"];
        
        switch($columna){
            case 'A':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado, self::$validacion["$tipoValidacion"][0]);
            break;
            case 'B':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][1]);
            break;
            case 'C':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][2],true);
            break;
            case 'D':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][3]);
            break;
            case 'E':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][4]);
            break;
            case 'F':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][5]);
            break;
            case 'G':
                if($sinFactura == 65)
                    $validacion = false;
                else
                    $validacion = self::$validacion["$tipoValidacion"][6];
                return self::numerico($valor,$fila,$encabezado,$validacion);
            break;
            case 'H':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][7]);
            break;
            case 'I':
                if($sinFactura == 65 AND $tipoValidacion != 6)
                    $validacion = false;
                else
                    $validacion = self::$validacion["$tipoValidacion"][8];
                return self::numerico($valor,$fila,$encabezado,$validacion);
            break;
            case 'J':
                return self::numerico($valor,$fila,$encabezado,self::$validacion["$tipoValidacion"][9]);
            break;
            case 'K':
                return self::numerico($valor,$fila,$encabezado,self::$validacion["$tipoValidacion"][10]);
            break;
            case 'L':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][11]);
            break;
            case 'M':
                return self::numerico($valor,$fila,$encabezado,self::$validacion["$tipoValidacion"][12]);
            break;
            case 'N':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][13]);
            break;
            case 'O':
                return self::numerico($valor,$fila,$encabezado,self::$validacion["$tipoValidacion"][14]);
            break;
            case 'P':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,self::$validacion["$tipoValidacion"][15]);
            break;
            case 'Q':
                return self::entero($valor,$fila,$encabezado,self::$validacion["$tipoValidacion"][16]);
            break;
            case 'R':
                return self::entero($valor,$fila,$encabezado,self::$validacion["$tipoValidacion"][17],true);
            break;
            case 'S':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'T':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'U':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'V':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'W':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'X':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'Y':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'Z':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AA':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AB':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AC':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AD':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AE':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AF':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AG':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AH':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AI':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AJ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AK':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AL':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AM':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AN':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AO':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AP':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AQ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AR':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AS':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AT':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AU':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AV':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AW':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AX':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AY':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AZ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BA':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BB':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BC':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BD':
                return self::texto($valor,$fila,$encabezado,false);
            break;

            default:  return  array('alerta'=>false,'error'=>true,'valor'=>true);
        }
    }

    public static function validarNew($columna,$fila,$valor,$nombresColumnas,$tipoValidacion,$sinFactura){
        
        $encabezado = $nombresColumnas["$columna"];
        
        switch($columna){
            case 'A':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado, self::$validacion2["$tipoValidacion"][0]);
            break;
            case 'B':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][1]);
            break;
            case 'C':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][2],true);
            break;
            case 'D':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][3]);
            break;
            case 'E':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][4]);
            break;
            case 'F':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][5]);
            break;
            case 'G':
                if($sinFactura == 65)
                    $validacion = false;
                else
                    $validacion = self::$validacion2["$tipoValidacion"][6];
                return self::numerico($valor,$fila,$encabezado,$validacion);
            break;
            case 'H':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][7]);
            break;
            case 'I':
                if($sinFactura == 65 AND $tipoValidacion != 6)
                    $validacion = false;
                else
                    $validacion = self::$validacion2["$tipoValidacion"][8];
                return self::numerico($valor,$fila,$encabezado,$validacion);
            break;
            case 'J':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,false);
            break;
            case 'K':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,false);
            break;
            case 'L':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][9]);
            break;
            case 'M':
                return self::numerico($valor,$fila,$encabezado,self::$validacion2["$tipoValidacion"][10]);
            break;
            case 'N':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][11]);
            break;
            case 'O':
                return self::numerico($valor,$fila,$encabezado,self::$validacion2["$tipoValidacion"][12]);
            break;
            case 'P':
                return self::lista(self::traducirValorNew($columna,$valor),$fila,$encabezado,self::$validacion2["$tipoValidacion"][13]);
            break;
            case 'Q':
                return self::entero($valor,$fila,$encabezado,self::$validacion2["$tipoValidacion"][14]);
            break;
            case 'R':
                return self::entero($valor,$fila,$encabezado,self::$validacion2["$tipoValidacion"][15],true);
            break;
            case 'S':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'T':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'U':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'V':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'W':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'X':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'Y':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'Z':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AA':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AB':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AC':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AD':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AE':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AF':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AG':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AH':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AI':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AJ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AK':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AL':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AM':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AN':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AO':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AP':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AQ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AR':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AS':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AT':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AU':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AV':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AW':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AX':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AY':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AZ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BA':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BB':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BC':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BD':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BE':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BF':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BG':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BH':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BI':
                return self::texto($valor,$fila,$encabezado,false);
            break;

            default:  return  array('alerta'=>false,'error'=>true,'valor'=>true);
        }
    }

    /*public static function validar($columna,$fila,$valor,$nombresColumnas){
        
        $encabezado = $nombresColumnas["$columna"];
       
        switch($columna){
            case 'A':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,true);
            break;
            case 'B':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,false);
            break;
            case 'C':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'D':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,true);
            break;
            case 'E':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,true);
            break;
            case 'F':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,true);
            break;
            case 'G':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'H':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,true);
            break;
            case 'I':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'J':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'K':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'L':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,false);
            break;
            case 'M':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'N':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,false);
            break;
            case 'O':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'P':
                return self::lista(self::traducirValor($columna,$valor),$fila,$encabezado,false);
            break;
            case 'Q':
                return self::entero($valor,$fila,$encabezado,false);
            break;
            case 'R':
                return self::entero($valor,$fila,$encabezado,true,true);
            break;
            case 'S':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'T':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'U':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'V':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'W':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'X':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'Y':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'Z':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AA':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AB':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AC':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AD':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AE':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AF':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AG':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AH':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AI':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AJ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AK':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AL':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AM':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AN':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AO':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AP':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AQ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AR':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AS':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AT':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AU':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AV':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AW':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AX':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AY':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AZ':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BA':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BB':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BC':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'BD':
                return self::texto($valor,$fila,$encabezado,false);
            break;

            default:  return  array('alerta'=>false,'error'=>true,'valor'=>true);
        }
    }*/

    public static function validar2($columna,$fila,$valor,$nombresColumnas){
        
        $encabezado = $nombresColumnas["$columna"];
       
        switch($columna){
            case 'A':
                return self::entero($valor,$fila,$encabezado,true,true);
            break;
            case 'T':
                return self::lista(self::traducirValor2($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'U':
                return self::fecha($valor,$fila,$encabezado,false);
            break;
            case 'V':
                return self::hora($valor,$fila,$encabezado,false);
            break;
            case 'W':
                return self::texto($valor,$fila,$encabezado,false);
            break;
            case 'X':
                return self::lista(self::traducirValor2($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'Y':
                return self::fecha($valor,$fila,$encabezado,false);
            break;
            case 'Z':
                return self::lista(self::traducirValor2($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'AA':
                return self::lista(self::traducirValor2($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'AB':
                return self::texto($valor,$fila,$encabezado,false);
            break;
            default:  
                return  array('alerta'=>false,'error'=>true,'valor'=>true);
        }
    }

    public static function validar2New($columna,$fila,$valor,$nombresColumnas){
        
        $encabezado = $nombresColumnas["$columna"];
       
        switch($columna){
            case 'A':
                return self::entero($valor,$fila,$encabezado,true,true);
            break;
            case 'AD':
                return self::lista(self::traducirValor2New($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'AE':
                return self::fecha($valor,$fila,$encabezado,false);
            break;
            case 'AF':
                return self::hora($valor,$fila,$encabezado,false);
            break;
            case 'AG':
                return self::lista(self::traducirValor2New($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'AH':
                return self::fecha($valor,$fila,$encabezado,false);
            break;
            case 'AI':
                return self::lista(self::traducirValor2New($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'AJ':
                return self::lista(self::traducirValor2New($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'AK':
                return self::texto($valor,$fila,$encabezado,false);
            break;
            default:  
                return  array('alerta'=>false,'error'=>true,'valor'=>true);
        }
    }

    public static function validar3($columna,$fila,$valor,$nombresColumnas){
        
        $encabezado = $nombresColumnas["$columna"];
       
        switch($columna){
            case 'A':
                return self::entero($valor,$fila,$encabezado,true,true);
            break;
            case 'AD':
                return self::lista(self::traducirValor3($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'AE':
                return self::texto($valor,$fila,$encabezado,false);
            break;
            default:  
                return  array('alerta'=>false,'error'=>true,'valor'=>true);
        }
    }

    public static function validar3New($columna,$fila,$valor,$nombresColumnas){
        
        $encabezado = $nombresColumnas["$columna"];
       
        switch($columna){
            case 'A':
                return self::entero($valor,$fila,$encabezado,true,true);
            break;
            case 'AP':
                return self::lista(self::traducirValor3($columna,$valor),$fila,$encabezado,false,true);
            break;
            case 'AQ':
                return self::texto($valor,$fila,$encabezado,false);
            break;
            default:  
                return  array('alerta'=>false,'error'=>true,'valor'=>true);
        }
    }

    public static function validar4New($columna,$fila,$valor,$nombresColumnas){
        
        $encabezado = $nombresColumnas["$columna"];
       
        switch($columna){
            case 'A':
                return self::entero($valor,$fila,$encabezado,true,true);
            break;
            case 'AT':
                return self::numerico($valor,$fila,$encabezado,false);
            break;
            case 'AU':
                return self::lista(self::traducirValor4($columna,$valor),$fila,$encabezado,false);
            break;
            case 'AV':
                return self::texto($valor,$fila,$encabezado,false);
            break;
            case 'AW':
                return self::texto($valor,$fila,$encabezado,false);
            break;
            case 'AX':
                return self::fecha($valor,$fila,$encabezado,false);
            break;
            case 'AY':
                return self::fecha($valor,$fila,$encabezado,false);
            break;
            case 'AZ':
                return self::texto($valor,$fila,$encabezado,false);
            break;
            default:  
                return  array('alerta'=>false,'error'=>true,'valor'=>true);
        }
    }

    public static function numerico($valor,$fila,$campo,$obligatorio){
        $formato = !preg_match('/^[0-9,.]{4,}$/',$valor);

        if( (empty($valor) AND $obligatorio) || ($formato AND $obligatorio))//El campo obligatorio no puede estar vacio y debe cumplir con el formato
            return array('error'=>false,'respuesta'=>'La fila: '.$fila.' no pudo ser guardada, el campo '. $campo .' es obligatorio y debe ser númerico en formato: 000,000,000.00 .');
        
        if($formato AND !empty($valor))//El campo que no es obligatorio y contien un formato que no es permitido sera almacenado en ALERTAS pero dejara que los demas campos de su registro se almacenen
            return array('alerta'=>true,'error'=>true,'respuesta'=>'Fila: '.$fila.', el formato del campo '. $campo .' debe ser númerico en formato: 000,000,000.00 (este campo no contendra valor), sin embargo se ingreso el registro de la fila.');
        
        if(empty($valor))
            return  array('alerta'=>false,'error'=>true,'valor'=>true);//El campo que no es obligatorio y ademas esta vacio sera ignorado y la base de datos le asignara un NULL

        $valor = str_replace(',','',$valor);
        return  array('alerta'=>false,'error'=>true,'valor'=>'"'.$valor.'"','campo'=>$campo);
    }

    public static function hora($valor,$fila,$campo,$obligatorio){
        $formato = !preg_match('/^[0-9]{2}:{1}[0-9]{2}([:][0]{2})*$/',$valor);

        if( (empty($valor) AND $obligatorio) || ($formato AND $obligatorio))//El campo obligatorio no puede estar vacio y debe cumplir con el formato
            return array('error'=>false,'respuesta'=>'La fila: '.$fila.' no pudo ser guardada, el campo '. $campo .' es obligatorio y debe ser tipo hora en formato: HH:MM .');
        
        if($formato AND !empty($valor))//El campo que no es obligatorio y contien un formato que no es permitido sera almacenado en ALERTAS pero dejara que los demas campos de su registro se almacenen
            return array('alerta'=>true,'error'=>true,'respuesta'=>'Fila: '.$fila.', el formato del campo '. $campo .' debe ser tipo hora en formato: HH:MM (este campo no contendra valor), sin embargo se ingreso el registro de la fila.');
        
        if(empty($valor))
            return  array('alerta'=>false,'error'=>true,'valor'=>true);//El campo que no es obligatorio y ademas esta vacio sera ignorado y la base de datos le asignara un NULL

        return  array('alerta'=>false,'error'=>true,'valor'=>'"'.$valor.'"','campo'=>$campo);
    }

    public static function fecha($valor,$fila,$campo,$obligatorio){
        $formato = !preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/',$valor);
       
        if( ($valor==="" AND $obligatorio) || ($formato AND $obligatorio))//El campo obligatorio no puede estar vacio y debe cumplir con el formato
            return array('error'=>false,'respuesta'=>'La fila: '.$fila.' no pudo ser guardada, el campo '. $campo .' es obligatorio y debe ser tipo fecha en formato: DD/MM/AAAA.');
        
        if($formato AND $valor!== "")//El campo que no es obligatorio y contien un formato que no es permitido sera almacenado en ALERTAS pero dejara que los demas campos de su registro se almacenen
            return array('alerta'=>true,'error'=>true,'respuesta'=>'Fila: '.$fila.', el formato del campo '. $campo .' debe ser tipo fecha en formato: DD/MM/AAAA (este campo no contendra valor), sin embargo se ingreso el registro de la fila: '.$valor);
        if($valor==="")
            return  array('alerta'=>false,'error'=>true,'valor'=>true);//El campo que no es obligatorio y ademas esta vacio sera ignorado y la base de datos le asignara un NULL

        $valor = substr($valor,6,4).'-'.substr($valor,3,2).'-'.substr($valor,0,2);
        
        return  array('alerta'=>false,'error'=>true,'valor'=>'"'.$valor.'"','campo'=>$campo);
    }

    public static function lista($valor,$fila,$campo,$obligatorio,$cero=false){

        if($valor =='0' AND $cero)
            return  array('alerta'=>false,'error'=>true,'valor'=>$valor,'campo'=>$campo);

        $formato = !preg_match('/^[0-9]{1,}$/',$valor);

        if( (empty($valor) AND $obligatorio) || ($formato AND $obligatorio))//El campo obligatorio no puede estar vacio y debe cumplir con el formato
            return array('error'=>false,'respuesta'=>'La fila: '.$fila.' no pudo ser guardada, el campo '. $campo .' es obligatorio y debe pertenecer a un elemento de la lista.');

        if($formato AND !empty($valor))//El campo que no es obligatorio y contien un formato que no es permitido sera almacenado en ALERTAS pero dejara que los demas campos de su registro se almacenen
            return array('alerta'=>true,'error'=>true,'respuesta'=>'Fila: '.$fila.', el valor del campo '. $campo .' no corresponde a los valores de la lista(este campo no contendra valor), sin embargo se ingreso el registro de la fila.');
        
        if(empty($valor))
            return  array('alerta'=>false,'error'=>true,'valor'=>true);//El campo que no es obligatorio y ademas esta vacio sera ignorado y la base de datos le asignara un NULL
     
        return array('alerta'=>false,'error'=>true,'valor'=>$valor,'campo'=>$campo);

    }

    public static function texto($valor,$fila,$campo,$obligatorio){
        $formato = preg_match('/["\']{1,}/',$valor);

        if( (empty($valor) AND $obligatorio) || ($formato AND $obligatorio))//El campo obligatorio no puede estar vacio y debe cumplir con el formato
            return array('error'=>false,'respuesta'=>'La fila: '.$fila.' no pudo ser guardada, el campo '. $campo .' es obligatorio y no debe contener ni comillas simples ni dobles .');

        if($formato AND !empty($valor))//El campo que no es obligatorio y contien un formato que no es permitido sera almacenado en ALERTAS pero dejara que los demas campos de su registro se almacenen
            return array('alerta'=>true,'error'=>true,'respuesta'=>'Fila: '.$fila.', el valor del campo '. $campo .' no debe contener ni comillas simples ni dobles(este campo no contendra valor), sin embargo se ingreso el registro de la fila.');
        
        if(empty($valor))
            return  array('alerta'=>false,'error'=>true,'valor'=>true);//El campo que no es obligatorio y ademas esta vacio sera ignorado y la base de datos le asignara un NULL
   
        $valor = trim($valor);
        return  array('alerta'=>false,'error'=>true,'valor'=>'"'.$valor.'"','campo'=>$campo);
    }

    public static function entero($valor,$fila,$campo,$obligatorio,$cero=false){

        if($valor==='0' AND $cero)
            return  array('alerta'=>false,'error'=>true,'valor'=>$valor,'campo'=>$campo);

        $formato = !preg_match('/^[0-9]{1,}$/',$valor);
        
        if( (empty($valor) AND $obligatorio) || ($formato AND $obligatorio))//El campo obligatorio no puede estar vacio y debe cumplir con el formato
            return array('error'=>false,'respuesta'=>'La fila: '.$fila.' no pudo ser guardada, el campo '. $campo .' es obligatorio y debe ser de tipo entero.');

        if($formato AND !empty($valor))//El campo que no es obligatorio y contien un formato que no es permitido sera almacenado en ALERTAS pero dejara que los demas campos de su registro se almacenen
            return array('alerta'=>true,'error'=>true,'respuesta'=>'Fila: '.$fila.', el valor del campo '. $campo .' debe ser de tipo entero(este campo no contendra valor), sin embargo se ingreso el registro de la fila.');
        
        if(empty($valor))
            return  array('alerta'=>false,'error'=>true,'valor'=>true);//El campo que no es obligatorio y ademas esta vacio sera ignorado y la base de datos le asignara un NULL
       
        return  array('alerta'=>false,'error'=>true,'valor'=>$valor,'campo'=>$campo);
    }

    public static function convertirEncabezado($indice){
        $arreglo = array(   'A'=>'esquema',
                            'B'=>'tipo_sindicato',
                            'C'=>'devengada',
                            'D'=>'id_cliente',
                            'E'=>'tipo_pago',
                            'F'=>'regimen',
                            'G'=>'comision_porcentaje',
                            'H'=>'empresa_facturadora',
                            'I'=>'subtotal',
                            'J'=>'iva',
                            'K'=>'total',
                            'L'=>'empresa_imss',
                            'M'=>'total_imss',
                            'N'=>'empresa_asimilados',
                            'O'=>'total_asimilados',
                            'P'=>'tipo_periodo' ,
                            'Q'=>'numero_periodo',
                            'R'=>'socios',
                            'S'=>'ingreso',
                            'T'=>'infonavit',
                            'U'=>'fonacot',
                            'V'=>'donativo',
                            'W'=>'pension',
                            'X'=>'excedente_cargas',
                            'Y'=>'cargas_patronal',
                            'Z'=>'isn',
                            'AA'=>'imss_obrera',
                            'AB'=>'carga_social_imss',
                            'AC'=>'prenomina_imss',
                            'AD'=>'isr_isp',
                            'AE'=>'isr_142',
                            'AF'=>'cuota_sindical',
                            'AG'=>'despensa',
                            'AH'=>'caja_ahorro',
                            'AI'=>'descuento_imss',
                            'AJ'=>'apoyo_sindical',
                            'AK'=>'descuento_comedor',
                            'AL'=>'haberes',
                            'AM'=>'excedente_subsidio',
                            'AN'=>'otros',
                            'AO'=>'excedente_ingreso',
                            'AP'=>'excedente_isr',
                            'AQ'=>'excedente_imss',
                            'AR'=>'excedente_gmm',
                            'AS'=>'excedente_infonavit',
                            'AT'=>'excedente_fonacot',
                            'AU'=>'excedente_prestamos',
                            'AV'=>'excedente_pension',
                            'AW'=>'excedente_terceros',
                            'AX'=>'excedente_clientes',
                            'AY'=>'excedente_recuperacion',
                            'AZ'=>'excedente_comision',
                            'BA'=>'excedente_prenomina',
                            'BB'=>'excedente_prenomina_gmm',
                            'BC'=>'excedente_otros',
                            'BD'=>'comentarios_nominas'      
                    );
            return $arreglo["$indice"];
    }

    public static function convertirEncabezadoNew($indice){
        $arreglo = array(   'A'=>'esquema',
                            'B'=>'tipo_sindicato',
                            'C'=>'devengada',
                            'D'=>'id_cliente',
                            'E'=>'tipo_pago',
                            'F'=>'regimen',
                            'G'=>'comision_porcentaje',
                            'H'=>'empresa_facturadora',
                            'I'=>'subtotal',
                            'J'=>'retencion_iva',
                            'K'=>'retencion_isn',
                            'L'=>'empresa_imss',
                            'M'=>'total_imss',
                            'N'=>'empresa_asimilados',
                            'O'=>'total_asimilados',
                            'P'=>'tipo_periodo' ,
                            'Q'=>'numero_periodo',
                            'R'=>'socios',
                            'S'=>'ingreso',
                            'T'=>'infonavit',
                            'U'=>'fonacot',
                            'V'=>'donativo',
                            'W'=>'pension',
                            'X'=>'excedente_cargas',
                            'Y'=>'cargas_patronal',
                            'Z'=>'isn',
                            'AA'=>'comision_monto',
                            'AB'=>'imss_obrera',
                            'AC'=>'carga_social_imss',
                            'AD'=>'prenomina_imss',
                            'AE'=>'isr_isp',
                            'AF'=>'isr_142',
                            'AG'=>'cuota_sindical',
                            'AH'=>'despensa',
                            'AI'=>'caja_ahorro',
                            'AJ'=>'descuento_imss',
                            'AK'=>'apoyo_sindical',
                            'AL'=>'descuento_comedor',
                            'AM'=>'haberes',
                            'AN'=>'excedente_subsidio',
                            'AO'=>'prestamos_empleados',
                            'AP'=>'prestamos_ayudate',
                            'AQ'=>'ajuste_subsidio_empleo',
                            'AR'=>'otros',
                            'AS'=>'excedente_ingreso',
                            'AT'=>'excedente_terceros',
                            'AU'=>'excedente_isr',
                            'AV'=>'excedente_imss',
                            'AW'=>'excedente_gmm',
                            'AX'=>'excedente_infonavit',
                            'AY'=>'excedente_fonacot',
                            'AZ'=>'excedente_prestamos',
                            'BA'=>'excedente_pension',
                            'BB'=>'excedente_clientes',
                            'BC'=>'excedente_recuperacion',
                            'BD'=>'excedente_comision',
                            'BE'=>'excedente_prenomina',
                            'BF'=>'excedente_prenomina_gmm',
                            'BG'=>'excedente_caja_ahorro',
                            'BH'=>'excedente_otros',
                            'BI'=>'comentarios_nominas'      
                    );
            return $arreglo["$indice"];
    }

    public static function convertirEncabezado2($indice){
        $arreglo =  array(  'A'=>'id',
                            'T'=>'financiada',
                            'U'=>'fecha_envio',
                            'V'=>'hora_envio',
                            'W'=>'numero_factura',
                            'X'=>'observaciones',
                            'Y'=>'fecha_liberacion',
                            'Z'=>'fondeo_imss',
                            'AA'=>'fondeo_asimilados',
                            'AB'=>'comentarios_finanzas'  
                        );
            return $arreglo["$indice"];
    }

    public static function convertirEncabezado2New($indice){
        $arreglo =  array(  'A'=>'id',
                            'AD'=>'financiada',
                            'AE'=>'fecha_envio',
                            'AF'=>'hora_envio',
                            'AG'=>'observaciones',
                            'AH'=>'fecha_liberacion',
                            'AI'=>'fondeo_imss',
                            'AJ'=>'fondeo_asimilados',
                            'AK'=>'comentarios_finanzas'  
                        );
            return $arreglo["$indice"];
    }

    public static function convertirEncabezado3($indice){
        $arreglo =  array(  'A'=>'id',
                            'AD'=>'tesoreria_estatus',
                            'AE'=>'comentarios_tesoreria'  
                        );
            return $arreglo["$indice"];
    }

    public static function convertirEncabezado3New($indice){
        $arreglo =  array(  'A'=>'id',
                            'AP'=>'tesoreria_estatus',
                            'AQ'=>'comentarios_tesoreria'  
                        );
            return $arreglo["$indice"];
    }

    public static function convertirEncabezado4New($indice){
        $arreglo =  array(  'A'=>'id',
                            'AT'=>'retencion_isn',
                            'AU'=>'estatus_factura',
                            'AV'=>'numero_factura', 
                            'AW'=>'numero_nota_credito',
                            'AX'=>'fecha_factura', 
                            'AY'=>'fecha_pago_factura', 
                            'AZ'=>'comentarios_facturacion' 
                        );
            return $arreglo["$indice"];
    }

    public static function traducirValor($columna,$valor){
        if($columna === "A")
            return self::traducirTipoEsquema2($valor);
        else if($columna === "B")
            return self::traducirTipoSindicato2($valor);
        else if($columna === "C")
            return self::traducirDevengada2($valor);
        else if($columna === "D")
            return NominasModel::traducirLista($valor,Tablas::clientes());
        else if($columna === "E")
            return self::traducirTipoPago2($valor);
        else if($columna === "F")
            return self::traducirTipoRegimen2($valor);
        else if($columna === "H")
            return NominasModel::traducirLista($valor,Tablas::facturadoras());
        else if($columna === "L")
            return NominasModel::traducirLista($valor,Tablas::imss());
        else if($columna === "N")
            return NominasModel::traducirLista($valor,Tablas::asimilados());
        else if($columna === "P")
            return self::traducirTipoPeriodo2($valor);
    }

    public static function traducirValorNew($columna,$valor){
        if($columna === "A")
            return self::traducirTipoEsquema2($valor);
        else if($columna === "B")
            return self::traducirTipoSindicato2($valor);
        else if($columna === "C")
            return self::traducirDevengada2($valor);
        else if($columna === "D")
            return NominasModel::traducirLista($valor,Tablas::clientes());
        else if($columna === "E")
            return self::traducirTipoPago2($valor);
        else if($columna === "F")
            return self::traducirTipoRegimen2($valor);
        else if($columna === "H")
            return NominasModel::traducirLista($valor,Tablas::facturadoras());
        else if($columna === "J")
            return self::traducirDevengada2($valor);
        else if($columna === "K")
            return self::traducirDevengada2($valor);
        else if($columna === "L")
            return NominasModel::traducirLista($valor,Tablas::imss());
        else if($columna === "N")
            return NominasModel::traducirLista($valor,Tablas::asimilados());
        else if($columna === "P")
            return self::traducirTipoPeriodo2($valor);
    }

    public static function traducirValor2($columna,$valor){
        if($columna === "T")
            return self::traducirDevengada2($valor);
        else if($columna === "X")
            return self::traducirEstatusLiberacion2($valor);
        else if($columna === "Z")
            return self::traducirDevengada2($valor);
        else if($columna === "AA")
            return self::traducirDevengada2($valor);
    }

    public static function traducirValor2New($columna,$valor){
        if($columna === "AD")
            return self::traducirDevengada2($valor);
        else if($columna === "AG")
            return self::traducirEstatusLiberacion2($valor);
        else if($columna === "AI")
            return self::traducirDevengada2($valor);
        else if($columna === "AJ")
            return self::traducirDevengada2($valor);
    }

    public static function traducirValor3($columna,$valor){
        return self::traducirEstatusPago2($valor);
    }

    public static function traducirValor4($columna,$valor){
        return self::traducirEstatusFactura2($valor);
    }

    public static function cheked($valor){
       return $cheked = $valor == 1 ? 'checked': '' ;
    }
    
    public static function traducirTipoPago($tipo){
        $tiposMovimiento = ['AGUINALDO',
                            'ASESORES',
                            'BONO',
                            'CARGA SOCIAL',
                            'COMPLEMENTO',
                            'ESPECIAL',
                            'EXCEDENTE IMSS',
                            'FINIQUITO',
                            'FONACOT',
                            'GASTOS MÉDICOS MAYORES',
                            'INFONAVIT',
                            'ISN',
                            'NÓMINA',
                            'PAGO PROVEEDOR',
                            'PRENÓMINA IMSS',
                            'PRIMA VACACIONAL',
                            'SEGURO DE VIDA',
                            'TARJETA EMPRESARIAL',
                            'OTROS'];
        if($tipo === true)
            return $tiposMovimiento;
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirTipoPago2($tipo){
        $tiposMovimiento = array('AGUINALDO'=>1,
                            'ASESORES'=>2,
                            'BONO'=>3,
                            'CARGA SOCIAL'=>4,
                            'COMPLEMENTO'=>5,
                            'ESPECIAL'=>6,
                            'EXCEDENTE IMSS'=>7,
                            'FINIQUITO'=>8,
                            'FONACOT'=>9,
                            'GASTOS MÉDICOS MAYORES'=>10,
                            'INFONAVIT'=>11,
                            'ISN'=>12,
                            'NÓMINA'=>13,
                            'PAGO PROVEEDOR'=>14,
                            'PRENÓMINA IMSS'=>15,
                            'PRIMA VACACIONAL'=>16,
                            'SEGURO DE VIDA'=>17,
                            'TARJETA EMPRESARIAL'=>18,
                            'OTROS'=>19);
        if($tipo > count($tiposMovimiento))
            return "";
        return $tiposMovimiento["$tipo"];
    }

    public static function traducirTipoRegimen($tipo){
       
        $tiposMovimiento = ['ASIMILADOS',
                            'ESPECIAL',
                            'MIXTO',
                            'SUELDOS Y SALARIOS'];
        if($tipo === true)
            return $tiposMovimiento;
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirTipoRegimen2($tipo){
        $tiposMovimiento = ['ASIMILADOS'=>1,
                            'ESPECIAL'=>2,
                            'MIXTO'=>3,
                            'SUELDOS Y SALARIOS'=>4];
       
        if($tipo > count($tiposMovimiento))
            return "";
        return $tiposMovimiento["$tipo"];
    }

    public static function traducirTipoPeriodo($tipo){
       
        $tiposMovimiento = ['CATORCENAL',
                            'DIARIO',
                            'MENSUAL',
                            'QUINCENAL',
                            'QUINCENAL COMBINADO',
                            'SEMANAL',
                            'SEMANAL COMBINADO',
                            'AGUINALDO'];
        if($tipo === true)
            return $tiposMovimiento;
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirTipoPeriodo2($tipo){
       
        $tiposMovimiento = ['CATORCENAL'=>1,
                            'DIARIO'=>2,
                            'MENSUAL'=>3,
                            'QUINCENAL'=>4,
                            'QUINCENAL COMBINADO'=>5,
                            'SEMANAL'=>6,
                            'SEMANAL COMBINADO'=>7,
                            'AGUINALDO'=>8];
        
        if($tipo > count($tiposMovimiento))
            return "";
        return $tiposMovimiento["$tipo"];
    }

    public static function traducirSiOno($tipo){
        $tiposMovimiento = ['SI',
                            'NO'];
        if($tipo === true)
            return $tiposMovimiento;
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirSiOnoInverso($tipo){
        $tiposMovimiento = ['NO',
                            'SI'];
        return $tiposMovimiento[$tipo];
    }

    public static function traducirObservaciones($tipo){
        $tiposMovimiento = ['PENDIENTE',
                            'LIBERADA',
                            'CANCELADA'];
        if($tipo === true)
            return $tiposMovimiento;
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirEstatusNominas($tipo){
        $tiposMovimiento = ['PENDIENTE',
                            'PAGADA',
                            'PAGADA CON DEVOLUCIÓN',
                            'PAGADA CON OBSERVACIÓN'];
        if($tipo === true)
            return $tiposMovimiento;
        return $tiposMovimiento[$tipo-1];
    }
 
    public static function traducirTipoEsquema($tipo){
       
        $tiposMovimiento = ['ASIMILADOS',
                            'MIXTO',
                            'SINDICATOS',
                            'SUELDOS Y SALARIOS',
                            'TARJETA EMPRESARIAL',
                            'PRESTAMO',
                            'CONFIDENCIAL',
                            'PAGADA CON OBSERVACIÓN',];
        if($tipo === true)
            return $tiposMovimiento;
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirTipoEsquema2($tipo){
        $tiposMovimiento = ['ASIMILADOS'=>1,
                            'MIXTO'=>2,
                            'SINDICATOS'=>3,
                            'SUELDOS Y SALARIOS'=>4,
                            'TARJETA EMPRESARIAL'=>5,
                            'PRESTAMO'=>6];
        if($tipo > count($tiposMovimiento))
            return "";
        return $tiposMovimiento["$tipo"];
    }

    public static function traducirEstatusLiberacion2($tipo){
        $tiposMovimiento = ['PENDIENTE'=>1,
                            'LIBERADA'=>2,
                            'CANCELADA'=>3];
        if($tipo > count($tiposMovimiento))
            return "";
        return $tiposMovimiento["$tipo"];
    }

    public static function traducirEstatusPago2($tipo){
        $tiposMovimiento = ['PENDIENTE'=>1,
                            'PAGADA'=>2,
                            'PAGADA CON DEVOLUCIÓN'=>3,
                            'PAGADA CON OBSERVACIÓN'=>4];
        if($tipo > count($tiposMovimiento))
            return "";
        return $tiposMovimiento["$tipo"];
    }

    public static function traducirTipoSindicato2($tipo){
        $tiposMovimiento = ['SINDICATO ASESORES / CROM'=>1,
                            'SINDICATO BUDAPEST'=>2];
        if($tipo > count($tiposMovimiento))
            return "";
        return $tiposMovimiento["$tipo"];
    }

    public static function traducirDevengada2($tipo){
        $tiposMovimiento = ['NO'=>0,
                            'SI'=>1];
        if($tipo > count($tiposMovimiento))
            return "";
        return $tiposMovimiento["$tipo"];
    }

    public static function completeSelect($valor,$total){
        $array;
        for($i=0;$i<$total;$i++){
            if( ($valor-1) === $i)
                $array[$i]="selected='selected'";
            else
                $array[$i]="";
        }   
        return $array;
    }

    public static function tipoPago($tipo){
        $movimiento = self::completeSelect($tipo,19);
        return $html=   '<option value="1" '.$movimiento[0].'>AGUINALDO</option>
                        <option value="2" '.$movimiento[1].'>ASESORES</option>
                        <option value="3" '.$movimiento[2].'>BONO</option>
                        <option value="4" '.$movimiento[3].'>CARGA SOCIAL</option>
                        <option value="5" '.$movimiento[4].'>COMPLEMENTO</option>
                        <option value="6" '.$movimiento[5].'>ESPECIAL</option>
                        <option value="7" '.$movimiento[6].'>EXCEDENTE IMSS</option>
                        <option value="8" '.$movimiento[7].'>FINIQUITO</option>
                        <option value="9" '.$movimiento[8].'>FONACOT</option>
                        <option value="10" '.$movimiento[9].'>GASTOS MÉDICOS MAYORES</option>
                        <option value="11" '.$movimiento[10].'>INFONAVIT</option>
                        <option value="12" '.$movimiento[11].'>ISN</option>
                        <option value="13" '.$movimiento[12].'>NÓMINA</option>
                        <option value="14" '.$movimiento[13].'>PAGO PROVEEDOR</option>
                        <option value="15" '.$movimiento[14].'>PRENÓMINA IMSS</option>
                        <option value="16" '.$movimiento[15].'>PRIMA VACACIONAL</option>
                        <option value="17" '.$movimiento[16].'>SEGURO DE VIDA</option>
                        <option value="18" '.$movimiento[17].'>TARJETA EMPRESARIAL</option>
                        <option value="19" '.$movimiento[18].'>OTROS</option>';
    }

    public static function tipoPeriodo($tipo){
        $array = self::completeSelect($tipo,8);
        return $html=  '<option value="1" '.$array[0].'>CATORCENAL</option>
                        <option value="2" '.$array[1].'>DIARIO</option>
                        <option value="3" '.$array[2].'>MENSUAL</option>
                        <option value="4" '.$array[3].'>QUINCENAL</option>
                        <option value="5" '.$array[4].'>QUINCENAL COMBINADO</option>
                        <option value="6" '.$array[5].'>SEMANAL</option>
                        <option value="7" '.$array[6].'>SEMANAL COMBINADO</option>
                        <option value="8" '.$array[7].'>AGUINALDO</option>';                
    }

    public static function tipoSindicato($tipo){
        $array = self::completeSelect($tipo,2);
        return $html=  '<option value="1" '.$array[0].'>SINDICATO ASESORES / CROM</option>
                        <option value="2" '.$array[1].'>SINDICATO BUDAPEST</option>';
    }

    public static function regimen($tipo){
        $array = self::completeSelect($tipo,4);
        return $html=  '<option value="1" '.$array[0].'>ASIMILADOS</option>
                        <option value="2" '.$array[1].'>ESPECIAL</option>
                        <option value="3" '.$array[2].'>MIXTO</option>
                        <option value="4" '.$array[3].'>SUELDOS Y SALARIOS</option>';
    }

    public static function observaciones($tipo){
        $array = self::completeSelect($tipo,3);
        return $html=  '<option value="1" '.$array[0].'>PENDIENTE</option>
                        <option value="2" '.$array[1].'>LIBERADA</option>
                        <option value="3" '.$array[2].'>CANCELADA</option>';
    }

    public static function estatusPago($tipo){
        $array = self::completeSelect($tipo,4);
        return $html=  '<option value="1" '.$array[0].'>PENDIENTE</option>
                        <option value="2" '.$array[1].'>PAGADA</option>
                        <option value="3" '.$array[2].'>PAGADA CON DEVOLUCIÓN</option>
                        <option value="4" '.$array[3].'>PAGADA CON OBSERVACIÓN</option>';
    }

    public static function estatusFactura($tipo){
        $array = self::completeSelect($tipo,4);
        return $html=  '<option value="1" '.$array[0].'>PENDIENTE</option>
                        <option value="2" '.$array[1].'>PAGADA</option>
                        <option value="3" '.$array[2].'>NOTA DE CRÉDITO</option>
                        <option value="4" '.$array[3].'>CANCELADA</option>';
    }

    public static function traducirEstatusFactura($tipo){
        $tiposMovimiento = ['PENDIENTE',
                            'PAGADA',
                            'NOTA DE CRÉDITO',
                            'CANCELADA'];
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirEstatusFactura2($tipo){
        $tiposMovimiento = ['PENDIENTE'=>1,
                            'PAGADA'=>2,
                            'NOTA DE CRÉDITO'=>3,
                            'CANCELADA'=>4];
        return $tiposMovimiento["$tipo"];
    }
 
    public static function siOno($tipo){
        $array = self::completeSelect($tipo,2);
        return $html=  '<option value="1" '.$array[0].'>SÍ</option>
                        <option value="2" '.$array[1].'>NO</option>';
    }

    public static function siOno2($tipo){
        $tiposMovimiento = ['SÍ',
                            'NO'];
        
        $tipo = $tipo == 0 ? 2 : $tipo;//corrección bug
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirTipoCfdi($tipo){
        $tiposMovimiento = ['COMPLEMENTO',
                            'FACTURA',
                            'NOTA DE CARGO',
                            'NOTA DE CRÉDITO'];
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirFinanciada($tipo){
        $tiposMovimiento = ['SÍ',
                            'NO'];
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirFondeo($tipo){
        $tiposMovimiento = ['SÍ',
                            'NO'];
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducireStatusCfdi($tipo){
        $tiposMovimiento = ['APLICADA',
                            'CANCELADA',
                            'PAGADA',
                            'PENDIENTE'];
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirObservacionesFinanzas($tipo){
        $tiposMovimiento = ['PENDIENTE',
                            'LIBERADA',
                            'CANCELADA'];
        return $tiposMovimiento[$tipo-1];
    }

    public static function traducirOservacionesTesoreria($tipo){
        $tiposMovimiento = ['CHEQUE',
                            'PAGADA',
                            'PENDIENTE'];
        return $tiposMovimiento[$tipo-1];
    }

    public static function getNominista(){ 
        return NominasModel::datos($_SESSION['identificador'],Tablas::usuarios());
    }
}

