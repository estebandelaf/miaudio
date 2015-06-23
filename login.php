<?php
	
	if(isset($_POST['login'])) {
		require_once("./inc/config.inc.php");
		if(empty($_POST['user']) || empty($_POST['pass'])) {
			$consultasSql->cerrar();
			header("Location: ./error.php?error=1") ; // error 1 = No se han rellenado todos los datos
		} else {
			$sql = $consultasSql->consulta("SELECT id,password,username FROM ".$miaudio['mysql']['prefix']."users WHERE username = '".$consultasSql->proteger($_POST['user'])."'") or $consultasSql->error();
			$userOk = mysql_num_rows($sql);
			if(!$userOk) {
				$consultasSql->cerrar();
				header("Location: ./error.php?error=2&u=".$_POST['user']) ; // error 2 = usuario incorrecto
			} else {
				$row = mysql_fetch_array($sql);
				if($row['password']!= md5($_POST['pass'])) {
					$consultasSql->cerrar();
					header("Location: ./error.php?error=3") ; // error 3 = clave incorrecta
				} else {
					// loguear usuario
					$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."users SET lastLogin = '".$miaudio['realTime']."' WHERE username = '".$consultasSql->proteger($_POST['user'])."'");
					$_SESSION['userId'] = $row['id'];
					$_SESSION['username'] = $row['username'];
					$_SESSION['hash'] = md5("miaudio_".$_POST['pass']."_".$ip."_".$host);
					$consultasSql->cerrar();
					header("location: ./".$row['username']); // ahora nos vamos a un archivo, pero ya con la sesion creada
				}
			}
		}
	} else {
		require("./inc/web1.inc.php");
		echo $tab4,'<h2>Entrar al sistema</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<p>Ingresa tu usuario y contraseña para acceder a la cuenta:</p>',$eol;
		echo $tab5,'<form action="',$_SERVER['PHP_SELF'],'" method="post" onsubmit="return validarLogin(this);">',$eol;
		echo $tab6,'<table>',$eol;
		echo $tab7,'<tr>',$eol;
		echo $tab8,'<td>Usuario</td>',$eol;
		echo $tab8,'<td><input type="text" name="user" maxlength="30" /></td>',$eol;
		echo $tab7,'</tr>',$eol;
		echo $tab7,'<tr>',$eol;
		echo $tab8,'<td>Contrase&ntilde;a</td>',$eol;
		echo $tab8,'<td><input type="password" name="pass" /></td>',$eol;
		echo $tab7,'</tr>',$eol;
		echo $tab7,'<tr>',$eol;
		echo $tab8,'<td></td>',$eol;
		echo $tab8,'<td><input type="submit" name="login" value="Entrar"></input></td>',$eol;
		echo $tab7,'</tr>',$eol;
		echo $tab6,'</table>',$eol;
		echo $tab5,'</form>',$eol;	
		echo $tab4,'</div>',$eol;
		require("./inc/web2.inc.php");
	}

?>