<?php

class MetodoMartin{



    public static function Conexion(){
       $conex= mysqli_connect("localhost","root","","asesores_empresariales");
            /* if (!$conex) {
                return $conex=false;
            }else {
                return $conex=true;
            }*/
        return $conex;
        /* try{
			$link = new PDO('mysql:host=192.168.0.10;dbname=asesores_empresariales', 'rootadmin' , '4dm1nQuimerA',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
			return $link;
		}
		catch(PDOException $e){
			return false;
		}*/
        
    }

    public static function SelectDinamic(){
            echo "si entro metodos";
            //echo $v1;
           // return $v1;
            

    }

    public static function ImpresionTabla($id,$nombre,$tabla){
        //$conex= mysqli_connect("localhost","root","","asesores_empresariales");
        $conex= MetodoMartin::Conexion();
        $query = $conex -> query ("SELECT $id , $nombre FROM $tabla");
        while ($valores = mysqli_fetch_array($query)) 
        {
        echo '<option value="'.$valores[id].'">'.$valores[nombre].'</option>';
        }  
    }
    
    
    public static function clientesLayout($tabla){
        $conex= mysqli_connect("localhost","root","","asesores_empresariales");
        
        //$stmt = $conex->prepare("SELECT nombre FROM $tabla WHERE activo = 1 ORDER BY nombre");
       // $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();


    }
    
    

}



?>