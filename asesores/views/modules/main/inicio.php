<?php
      //session_start();
      if(!$_SESSION["validar"]){
        header("location:ingreso");
        exit();
      }
      
      include_once('views/modules/estructura/head.php');
      include_once('views/modules/estructura/asideSwitch.php');
      if(Configuraciones::especial() != $_SESSION['identificador2'])
        include_once('views/modules/interfaz/interfazInicio.php');
      else
        include_once('views/modules/interfaz/interfazUsuariosNutrifitness.php');
      include_once('views/modules/estructura/footer.php');
      include_once('views/modules/estructura/config.php');
