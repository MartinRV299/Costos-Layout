<?PHP

    class persona{

        public function guardar($nombre,$deportamento,$fecha,$producto,$cantidad,$precio,$descripcion){
            $conex=MetodoMartin::Conexion();
            echo"entro personas";
            $stm = $conex -> prepare(" INSERT INTO `compras_ae`
                                                    (`nombre`,
                                                    `departamento`,
                                                    `fecha`,
                                                    `producto`,
                                                    `cantidad`,
                                                    `precio`,
                                                    `descripcion`) 
                                            VALUES (:nombre,
                                                    :departamento,
                                                    :fecha,
                                                    :producto,
                                                    :cantidad,
                                                    :precio,
                                                    :descripcion);");

            $stmt -> bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt -> bindValue(":departamento", $nombre, PDO::PARAM_STR);
            $stmt -> bindValue(":fecha", $nombre, PDO::PARAM_STR);
            $stmt -> bindValue(":producto", $nombre, PDO::PARAM_STR);
            $stmt -> bindValue(":cantidad", $nombre, PDO::PARAM_STR);
            $stmt -> bindValue(":precio", $nombre, PDO::PARAM_STR);
            $stmt -> bindValue(":descripcion", $nombre, PDO::PARAM_STR);
            
            if($stmt -> execute()){
                return "OK";
            } else {
                return "Error: se ha generado un error";
            }
    }


    

}
?>