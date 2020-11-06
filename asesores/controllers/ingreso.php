<?php
class Ingreso{

	public $captcha = NULL;

	public function ingresoController(){

		if(isset($_POST["solicitarLinkAcceso"])){
			if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuarioIngreso"])){
					$respuesta = IngresoModels::existeUsuario($_POST["usuarioIngreso"], Tablas::usuarios());
					if( intval($respuesta) === 1){//si existe el usuario enviamos correo
						echo '<div class="alert alert-success">Se envío un correo electrónico a la cuenta que tienes registrada</div>';
						self::confirmarCorreo($_POST["usuarioIngreso"]);
					}	
					else
						echo '<div class="alert alert-warning">Verifique sus datos</div>';
			}
			else
				echo '<div class="alert alert-warning">Verifique sus datos</div>';
		}

		else if(isset($_POST["usuarioIngreso"])){

			if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuarioIngreso"]) && preg_match('/^[a-zA-Z0-9]+$/', $_POST["passwordIngreso"])){
					
					$encriptar = Llaves::password($_POST["passwordIngreso"]);	
					//$encriptar = $_POST["passwordIngreso"];	
					$datosController = $_POST["usuarioIngreso"];
	
					//$datosController = $_POST["usuarioIngreso"];
					$respuesta = IngresoModels::ingresoUsuarioModel($datosController, "usuarios_ae");
				
					$intentos = $respuesta["intentos"];
					$usuario = $_POST["usuarioIngreso"];
					$maximoIntentos = 3;
					if($intentos < $maximoIntentos){
						if($respuesta["usuario"] === $usuario && $respuesta["contrasena"] === $encriptar){
							session_start();
							$respuesta = IngresoModels::ingreso2UsuarioModel($usuario, "usuarios_ae");
							$_SESSION["validar"] = true;
							$_SESSION["identificador2"] = $respuesta["tipo_acceso"];
							$_SESSION["identificador"] = $respuesta["id_usuario"];
							$_SESSION["usuario"] = $respuesta["nombre"]." ".$respuesta["paterno"];
							$_SESSION["imagen"] = $respuesta["imagen"];

							$respuesta = ConfiguracionesController::obtenerConfiguracionController($respuesta["id_usuario"]);
							$_SESSION["notificaciones"] = $respuesta['avisos'];
							$configColorScreen = array('skin-blue','skin-black','skin-red','skin-yellow','skin-purple','skin-green','skin-blue-light','skin-black-light','skin-red-light', 'skin-yellow-light','skin-purple-light','skin-green-light');
							$configSideLeft =array('','sidebar-collapse');
							$configScreenSize = array('','layout-boxed');

							//$configColorScreen=$configColorScreen[$respuesta['color_pantalla']];
							$configColorScreen='skin-red';
							setcookie("configColorScreen" ,$configColorScreen,time()+3600*24);
							$configSideLeft= $configSideLeft[$respuesta['menu_izquierdo']];
							setcookie("configSideLeft" ,$configSideLeft,time()+3600*24);
							$configScreenSize= $configScreenSize[$respuesta['tamano_pantalla']];
							setcookie("configScreenSize" ,$configScreenSize,time()+3600*24);

							setcookie("hiSystem" ,true,time()+3600*24);
							
							
							if($intentos > 0){
								$intentos = 0;
								$datosController = array("usuarioActual"=>$usuario, "actualizarIntentos"=>$intentos);
								$respuestaActualizarIntentos = IngresoModels::intentosUsuarioModel($datosController, "usuarios_ae");
							}
							header("location:inicio");
						}
						else{
							$intentos++;
							$datosController = array("usuarioActual"=>$usuario, "actualizarIntentos"=>$intentos);
							$respuestaActualizarIntentos = IngresoModels::intentosUsuarioModel($datosController, "usuarios_ae");
							echo '<div class="alert alert-warning">Verifique sus datos</div>';
						}
					}
					else{
						session_destroy();
						if($this->captcha !== NULL && preg_match('/^[a-zA-Z0-9]+$/', $_POST["captcha_challenge"])){
							if($this->captcha === $_POST['captcha_challenge']){
								if($respuesta["usuario"] === $usuario && $respuesta["contrasena"] === $encriptar){
									session_start();
									$respuesta = IngresoModels::ingreso2UsuarioModel($usuario, "usuarios_ae");
									$_SESSION["validar"] = true;
									$_SESSION["identificador2"] = $respuesta["tipo_acceso"];
									$_SESSION["identificador"] = $respuesta["id_usuario"];
									$_SESSION["usuario"] = $respuesta["nombre"]." ".$respuesta["paterno"];
									$_SESSION["imagen"] = $respuesta["imagen"];
		
									$respuesta = ConfiguracionesController::obtenerConfiguracionController($respuesta["id_usuario"]);
									$configColorScreen = array('skin-blue','skin-black','skin-red','skin-yellow','skin-purple','skin-green','skin-blue-light','skin-black-light','skin-red-light', 'skin-yellow-light','skin-purple-light','skin-green-light');
									$configSideLeft =array('','sidebar-collapse');
									$configScreenSize = array('','layout-boxed');
		
									$configColorScreen=$configColorScreen[$respuesta['color_pantalla']];
									setcookie("configColorScreen" ,$configColorScreen,time()+3600*24);
									$configSideLeft= $configSideLeft[$respuesta['menu_izquierdo']];
									setcookie("configSideLeft" ,$configSideLeft,time()+3600*24);
									$configScreenSize= $configScreenSize[$respuesta['tamano_pantalla']];
									setcookie("configScreenSize" ,$configScreenSize,time()+3600*24);
		
									setcookie("hiSystem" ,true,time()+3600*24);
									
									$intentos = 0;
									$datosController = array("usuarioActual"=>$usuario, "actualizarIntentos"=>$intentos);
									$respuestaActualizarIntentos = IngresoModels::intentosUsuarioModel($datosController, "usuarios_ae");
									
									header("location:inicio");
								}
							}
						}

						echo'<div class="alert alert-danger">Haz intentado ingresar en varias ocasiones con datos incorrectos, debes llenar el captcha para acceder
								<br>
								<div class="text-left"><img src="'.Ruta::ruta_server().'views/img/captcha.php" alt="CAPTCHA" class="captcha-image"><i class="fa fa-refresh fa-3x refrescarCaptcha" style="margin-left:10px;color:#fff;cursor:pointer;"></i></div>
								<div class="text-left"><input type="text" id="captcha" name="captcha_challenge" pattern="[A-Za-z0-9]{6}" style="color:#000;width:200px;" required></div>
							</div>';
					}
			}
			else
				echo '<div class="alert alert-warning">Verifique sus datos</div>';
		}
	}

	public function confirmarCorreo($correo){
		$respuesta = IngresoModels::datosUsuario($correo, Tablas::usuarios());
		$generales = $respuesta['nombre'].' '.$respuesta['paterno'].' '.$respuesta['materno'];
		
		$token= date("dmYHis", time());
		$token=crypt(md5($token),'$2a$07$asxx54ahHIU78jlR87a5a4dDDGYUjk03dev$');
		$id_usuario = crypt(md5($respuesta["id_usuario"]),'$2a$07$asxI54ahHIU78jTT87a5a4duN040jk03dEX$');

		IngresoModels::crearNuevaPass($id_usuario,$token,$correo,Tablas::credenciales());

		if(!empty($generales)){
			$nombre = 'Sistema de Intranet Asesores Empresariales';
			$mensaje = 'Este correo se genera automaticamente';
			$para = $correo.'@asesoresempresariales.com.mx';

			$titulo = 'Sistema de Intranet Asesores Empresariales';
					$mensajeFinal ='<html>
							<head>
							<title>AE!</title>
							</head>

							<body>
							<h3>Sistema de Intranet Asesores Empresariales!</h3>
							<hr>
							<h3>Hola '.$generales.'</h3>
							<br>
							<p>Da click en el siguiente link para enviarte tu <b>nueva contraseña:</b> <br>
							<b><a href="http://192.168.0.10/recuperarCredenciales7MhsjZIB5655mwBNIRSfp7YKck5TRSfp7WBKmyjKmyjDWHV0Nq3qNIRSfp7YKck5HV0Nq3qvcV.php?id='.urlencode($id_usuario).'&token='.urlencode($token).'" target="blank">Generar nueva contraseña</a></b> 
							<br>
							<h2>En caso de que tú no hayas realizado esta petición haz caso omiso de este correo electrónico.</h2>
							
							<hr>
							<h3><a href="http://www.intranet.asesoresempresariales.com.mx" target="blank">Asesores Empresariales</a></h3>
							<br>
							<img src="http://www.intranet.asesoresempresariales.com.mx/images/asesores.jpg">
							</body>

						</html>';

			$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
			$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			$cabeceras .= 'From: <desarrollo@asesoresempresariales.com.mx>' . "\r\n";
			$cabeceras .= 'CC: <desarrollo@asesoresempresariales.com.mx>' . "\r\n"; 

			mail($para, $titulo, $mensajeFinal, $cabeceras);
		}
		
	}

	public function existenDatos($id,$token){
		$respuesta = IngresoModels::existenDatos($id,$token,Tablas::credenciales());
		return $respuesta;
	}

	public function generarNuevoPass($id,$token,$correo){
	
		$respuesta = IngresoModels::datosUsuario($correo, Tablas::usuarios());
		$generales = $respuesta['nombre'].' '.$respuesta['paterno'].' '.$respuesta['materno'];
		$pass=self::generarPass();
		$passEncriptada = Llaves::password(md5($pass));

		$actualizarPass = IngresoModels::actualizarPass($id,$token,$correo,$passEncriptada,Tablas::usuarios(),Tablas::credenciales());
		if(!empty($generales) AND $actualizarPass){
			$nombre = 'Sistema de Intranet Asesores Empresariales';
			$mensaje = 'Este correo se genera automaticamente';
			$para = $correo.'@asesoresempresariales.com.mx';
			$titulo = 'Sistema de Intranet Asesores Empresariales';

			$mensajeFinal ='<html>
					<head>
					<title>AE!</title>
					</head>
					<body>
					<h3>Sistema de Intranet Asesores Empresariales!</h3>
					<hr>
					<h2>'.$generales.'</h2>
					<br>
					<p>Tu nueva contraseña: <b>'.$pass.'</b></p>
					<br>
					<p>Recuerda que también puedes personalizar tu contraseña si así lo deseas desde el módulo <b>MI CUENTA</b> para que puedas recordarla con mayor facilidad.</p>
					<br>
					<br>
					<p>Cualquier duda quedamos a tus ordenes por este medio, que tengas un excelente día.</p>
					<hr>
					<h3><a href="http://www.intranet.asesoresempresariales.com.mx" target="blank">Asesores Empresariales</a></h3>
					<br>
					<img src="http://www.intranet.asesoresempresariales.com.mx/images/asesores.jpg">
					</body>

				</html>';
			$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
			$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			$cabeceras .= 'From: <desarrollo@asesoresempresariales.com.mx>' . "\r\n";
			$cabeceras .= 'CC: <desarrollo@asesoresempresariales.com.mx>' . "\r\n"; 
			$envio = mail($para, $titulo, $mensajeFinal, $cabeceras);
			return array('error'=>!$envio,"usuario"=>$generales);
		}
		else
			return array('error'=>true);
	}

	public function generarPass(){
		$longitudPass = 12;
		$numeros = array('1','2','3','4','5','6','7','8','9','0');
		$mayusculas = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$minusculas = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$valores = array_merge($numeros,array_merge($mayusculas,$minusculas));
	
		$i = 0;
		$elemento = "";
		$pass = array();

		$minimoNumero = intval( ((float)rand()/(float)getrandmax()) * $longitudPass);
		$minimoMayuscula = intval( ((float)rand()/(float)getrandmax()) * $longitudPass);
		$minimoMinuscula = intval( ((float)rand()/(float)getrandmax()) * $longitudPass);

		while($minimoNumero == $minimoMayuscula)
			$minimoMayuscula = intval(((float)rand()/(float)getrandmax()) * $longitudPass);

		while($minimoMayuscula == $minimoMinuscula || $minimoNumero == $minimoMinuscula )
			$minimoMinuscula = intval( ((float)rand()/(float)getrandmax()) * $longitudPass);

		while($i < $longitudPass){
			if($i == $minimoNumero){
				$elemento = intval( ((float)rand()/(float)getrandmax()) * sizeof($numeros));
				$pass[$i] = $numeros[$elemento];  
			}

			else if($i == $minimoMayuscula){
				$elemento = intval( ((float)rand()/(float)getrandmax()) * sizeof($mayusculas));
				$pass[$i] = $mayusculas[$elemento];  
			}

			else if($i == $minimoMinuscula){
				$elemento = intval( ((float)rand()/(float)getrandmax()) * sizeof($minusculas));
				$pass[$i] = $minusculas[$elemento];  
			}
			
			else{
				$elemento = intval( ((float)rand()/(float)getrandmax()) * sizeof($valores));
				$pass[$i] = $valores[$elemento];  
			}					
			$i++;
		} 
		return implode($pass);
	}

}