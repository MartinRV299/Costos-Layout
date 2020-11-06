<?php
//if($_POST['accion'] == "GUARDAR"){echo"si entro";}
//$continente=$_POST['accion'];
//echo $continente
//if(isset($_POST["accion"])){echo "HOLAAAAAAA";}
//if(isset($_POST["ACCION"]))
//$a=$_POST["nombre"];
//echo $a;

require_once ("models/ComprasModels.php");

if(isset($_POST["accion"])){echo "HOLAAAAAssqAA";}
 $persona = new persona();
 if(isset($_POST["accion"])){echo "HOLAAAAAAA";}
 if(isset($_POST["accion"])){
        echo"holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        switch ($_POST["accion"]) {
            case 'GUARDAR':
                $nombre = $_POST["nombre"];
                $departamento = $_POST["departamento"];
                $fecha = $_POST["fecha"];
                $producto = $_POST["producto"];
                $cantidad = $_POST["cantidad"];
                $precio = $_POST["precio"];
                $respuesta = $persona->guardar();
                echo json_encode($respuesta);
            
          
        }

    }

?>