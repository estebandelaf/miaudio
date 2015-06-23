<?php

	require("./inc/web1.inc.php");

	echo $tab4,'<h2>Registro de nuevos usuarios</h2>',$eol;
	echo $tab4,'<div class="box">',$eol;
	echo $tab5,'<p><strong>Registro solo para usuarios que deseen probar el sistema, todavía no esta listo para un uso masivo ni alojar audio en forma definitiva.</strong></p>',$eol;

	if(isset($_POST['registrar'])) {
		if($miaudio['numUsers']['actual']==$miaudio['numUsers']['max'] && $miaudio['numUsers']['max']) {
			echo $tab5,'<p>Lo sentimos pero ya se ha alcanzado el máximo de usuarios permitido, o sea ',$miaudio['numUsers']['max'],' usuarios registrados.</p>',$eol;
			echo $tab5,'<p>Tienes dos opciones: esperar a que algún usuario borre su cuenta o esperar a que se aumenten los cupos.</p>',$eol;
		} else {
			if($_COOKIE['miaudio_user_reg_ver']==md5($_POST['reg_ver'])) {
				if(isset($_POST['acepto']) && $_POST['acepto']=="on") {
					if($_POST['email1']==$_POST['email2'] && $_POST['email1']!="" && validarEmail($_POST['email1'])) {
						if($_POST['clave1']==$_POST['clave2'] && $_POST['clave1']!="" && $_POST['clave2']!="" && strlen($_POST['clave1'])>=6) {
							if($_POST['usuario']!="" && validarUsuario($_POST['usuario']) && strlen($_POST['usuario'])>=4 && strlen($_POST['usuario'])<=30) {
								// verificar que el el usuario no exista ya en la base de datos
								$usuario = $consultasSql->proteger($_POST['usuario']);
								$usuario_correcto = $consultasSql->contar($miaudio['mysql']['prefix']."users", "username", $usuario);
								if($usuario_correcto==0) {
									// registrar el usuario
									$consultasSql->consulta("INSERT INTO ".$miaudio['mysql']['prefix']."users (username,password,email,signup) VALUES ('$usuario','".md5($_POST['clave1'])."','".$consultasSql->proteger($_POST['email1'])."','".$miaudio['realTime']."')");
									if(!$miaudio['numUsers']['actual'])
										$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."users SET mode = '1' WHERE username = '$usuario'");
									echo $tab5,'<p>Se ha registrado su usuario <em>',$usuario,'</em> satisfactoriamente, puedes acceder a tu cuenta mediante la dirección web <a href="./login.php">siguiente</a>.</p>',$eol;
									echo $tab5,'<p>Debe acceder al menos antes de una semana desde la fecha de registro a su cuenta, sino esta será eliminada.</p>',$eol;
								} else echo $tab5,'<p>El usuario <em>',$usuario,'</em> ya se encuentra registrado, por favor vuelve atrás e inténtalo denuevo.</p>',$eol;
							} else echo $tab5,'<p>El usuario ingresado es incorrecto o bien no se ha ingresado. Solo utilizar letras (a-z, sin ñ ni acentos), números (0-9), guiones (-) y guiones bajos (_), cualquier otro caracter no está permitido. Además debe tener entre 4 y 30 caracteres. Por favor vuelve atrás e inténtalo denuevo, si el problema persiste intenta con otro nombre de usuario.</p>',$eol;
						} else echo $tab5,'<p>Las contraseñas no han sido ingresadas correctamente, por favor vuelve atrás e inténtalo denuevo. </p>',$eol;
					} else echo $tab5,'<p>Los email no han sido ingresados correctamente, por favor vuelve atrás e inténtalo denuevo. </p>',$eol;
				} else echo $tab5,'<p>Se deben aceptar los <a href="./terms.php">términos y condiciones de uso</a> para poder realizar el registro, por favor vuelve atrás e inténtalo denuevo.</p>',$eol;
			} else echo $tab5,'<p>El código de verificación ingresado es incorrecto, por favor vuelve atrás e inténtalo denuevo.</p>',$eol;
		}
	} else {
		if($miaudio['numUsers']['actual']<$miaudio['numUsers']['max'] || !$miaudio['numUsers']['max']) {
			echo $tab5,'<p>Bienveni@, gracias por registrarse. Si tienes alguna duda revisa los signos de pregunta de cada item.</p>',$eol;
			echo $tab5,'<form action="',$_SERVER['PHP_SELF'],'" method="post" onsubmit="return validarRegistro(this);">',$eol;
			echo $tab6,'<table>',$eol;
			echo $tab7,'<tr><td>Usuario ',helpBox("usuario","Este será el nombre con el que accederás al sistema, además permitirá compartir tus archivos de audio de una forma fácil ingresando a la url ".$miaudio['url']."/usuario Solo utilizar letras (a-z, sin ñ ni acentos), números (0-9), guiones (-) y guiones bajos (_), cualquier otro caracter no está permitido. Además debe tener entre 4 y 30 caracteres."),'</td><td><input type="text" name="usuario" maxlength="30" /></td></tr>',$eol;
			echo $tab7,'<tr><td>Email ',helpBox("email","Será utilizado en caso de requerir restaurar la contraseña. Por esto debe ser un email válido"),'</td><td><input type="text" name="email1" maxlength="250" /></td></tr>',$eol;
			echo $tab7,'<tr><td>Repetir email</td><td><input type="text" name="email2" maxlength="250" /></td></tr>',$eol;
			echo $tab7,'<tr><td>Contraseña ',helpBox("pass","Utilizada para acceder a la administración de su tienda, esta debe ser de al menos 6 caracteres."),'</td><td><input type="password" name="clave1" /></td></tr>',$eol;
			echo $tab7,'<tr><td>Repetir contraseña</td><td><input type="password" name="clave2" /></td></tr>',$eol;
			echo $tab7,'<tr><td>Verificador <img src="./checkcode.php" alt="Código de verificación" /></td><td><input type="text" name="reg_ver" maxlength=\"4\" /></td></tr>',$eol;
			echo $tab7,'<tr><td></td><td><input type="checkbox" name="acepto" /> Acepto los <a href="./terms.php">términos y condiciones de uso</a> de ',$miaudio['site']['name'],'.</td></tr>',$eol;
			echo $tab7,'<tr><td></td><td><input type="submit" name="registrar" value="Registrar" /></td></tr>',$eol;
			echo $tab6,'</table>',$eol;
			echo $tab5,'</form>',$eol;
		} else {
			echo $tab5,'<p>Lo sentimos pero ya se ha alcanzado el máximo de usuarios permitido, o sea ',$miaudio['numUsers']['max'],' usuarios registrados.</p>',$eol;
			echo $tab5,'<p>Tienes dos opciones: esperar a que algún usuario borre su cuenta o esperar a que se aumenten los cupos.</p>',$eol;
		}
	}

	echo $tab4,'</div>',$eol;

	require("./inc/web2.inc.php");

?>