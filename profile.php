<?php

	require_once("./inc/web1.inc.php");

	if(!$login)
		header("location: ./error.php?error=4&file=".$_SERVER['PHP_SELF']);
		
	if(isset($_POST['editProfile']) || isset($_POST['chPass']) || isset($_POST['del'])) {
		$sql = $consultasSql->consulta("SELECT password FROM ".$miaudio['mysql']['prefix']."users WHERE id = ".$_SESSION['userId']);
		$row = mysql_fetch_array($sql);
		// verificar clave de usuario ingresada
		if($row['password']==md5($_POST['pass'])) {
			if(isset($_POST['editProfile'])) {
				// editar perfil
				$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."users SET email = '".$consultasSql->proteger($_POST['email'])."', about = '".$consultasSql->proteger($_POST['about'])."' WHERE id = ".$_SESSION['userId']);
				echo $tab4,'<META HTTP-EQUIV="Refresh" CONTENT="0; URL=',$_SERVER['PHP_SELF'],'">',$eol;
			} else {
				if(isset($_POST['chPass'])) {
					// cambiar contraseña
					if($_POST['pass1']==$_POST['pass2'] && strlen($_POST['pass1'])>=6) {
						$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."users SET password = '".md5($_POST['pass1'])."' WHERE id = ".$_SESSION['userId']);
						$_SESSION['userId'] = false;
						$_SESSION['hash'] = false;
						$_SESSION['username'] = false;
						session_destroy();
						echo $tab4,'<h2>Contraseña cambiada</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab6,'<p>Debes entrar denuevo a tu cuenta, click <a href="./login.php">aquí</a>.</p>',$eol;
						echo $tab4,'</div>',$eol;
					} else {
						echo $tab4,'<h2>Error: contraseña nueva</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab6,'<p>Las contraseñas nuevas no son iguales o bien tienen menos de 6 caracteres.</p>',$eol;
						echo $tab4,'</div>',$eol;
					}
				} else {
					if(isset($_POST['del'])) {
						// eliminar cuenta de usuario
						$consultasSql->consulta("DELETE FROM ".$miaudio['mysql']['prefix']."users WHERE id = '".$_SESSION['userId']."'");
						$consultasSql->consulta("DELETE FROM ".$miaudio['mysql']['prefix']."files WHERE userId = '".$_SESSION['userId']."'");
						$consultasSql->consulta("DELETE FROM ".$miaudio['mysql']['prefix']."comments WHERE fileUserId = '".$_SESSION['userId']."'");
						$consultasSql->consulta("DELETE FROM ".$miaudio['mysql']['prefix']."playlist WHERE userId = '".$_SESSION['userId']."'");
						$_SESSION['userId'] = false;
						$_SESSION['hash'] = false;
						$_SESSION['username'] = false;
						session_destroy();
						$consultasSql->cerrar();
						echo $tab4,'<h2>Cuenta eliminada</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab6,'<p>Se ha eliminado al usuario, vuelve a la <a href="./">portada</a> para seguir navegando.</p>',$eol;
						echo $tab4,'</div>',$eol;
					}
				}
			}
		} else {
		echo $tab4,'<h2>Error: contraseña incorrecta</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab6,'<p>La contraseña actual indicada es incorrecta.</p>',$eol;
		echo $tab4,'</div>',$eol;
		}
	} else {
		$sql = $consultasSql->consulta("SELECT email,avatarFileName,about FROM ".$miaudio['mysql']['prefix']."users WHERE id = ".$_SESSION['userId']);
		$row = mysql_fetch_array($sql);
		
		if($row['avatarFileName']) $avatar = "./avatar.php?uid=".$_SESSION['userId'];
		else $avatar = "./images/default_avatar.gif";
		
		echo $tab4,'<h2>Modificar perfil de ',$_SESSION['username'],'</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<form action="',$_SERVER['PHP_SELF'],'" method="post" onsubmit="return validarProfile(this);">',$eol;
		echo $tab6,'<table>',$eol;
		echo $tab7,'<tr><td>Email</td><td><input type="text" name="email" value="',$row['email'],'" maxlength="250" /></td></tr>',$eol;
		echo $tab7,'<tr><td>Sobre mí</td><td><textarea name="about" rows="5" cols="42">',$row['about'],'</textarea></td></tr>',$eol;
		echo $tab7,'<tr><td>Contraseña actual</td><td><input type="password" name="pass" /></td></tr>',$eol;
		echo $tab7,'<tr><td></td><td><input type="submit" name="editProfile" value="Modificar perfil" /></td></tr>',$eol;
		echo $tab6,'</table>',$eol;
		echo $tab5,'</form>',$eol;
		echo $tab4,'</div>',$eol;
		
		echo $tab4,'<h3>Cambiar contraseña</h3>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<form action="',$_SERVER['PHP_SELF'],'" method="post" onsubmit="return validarChPass(this);">',$eol;
		echo $tab6,'<table>',$eol;
		echo $tab7,'<tr><td>Contraseña actual</td><td><input type="password" name="pass" /></td></tr>',$eol;
		echo $tab7,'<tr><td>Contraseña nueva</td><td><input type="password" name="pass1" /></td></tr>',$eol;
		echo $tab7,'<tr><td>Repetir contraseña</td><td><input type="password" name="pass2" /></td></tr>',$eol;
		echo $tab7,'<tr><td></td><td><input type="submit" name="chPass" value="Cambiar contraseña" /></td></tr>',$eol;
		echo $tab6,'</table>',$eol;
		echo $tab5,'</form>',$eol;
		echo $tab4,'</div>',$eol;
		
		echo $tab4,'<h3>Avatar</h3>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<img src="',$avatar,'" alt="Avatar de ',$_SESSION['username'],'" class="right" />',$eol;
		echo $tab5,'<ul>',$eol;
		echo $tab6,'<li>Extensiones permitidas: ',showArray($miaudio['avatar']['extOk']),'</li>',$eol;
		echo $tab6,'<li>Tipos mime válidos: ',showArray($miaudio['avatar']['mimeType']),'</li>',$eol;
		echo $tab6,'<li>Tamaño máximo del archivo a subir: ',floor($miaudio['avatar']['maxFileSize']*1024),' KB</li>',$eol;
		echo $tab6,'<li>Ancho y alto máximo; 100x100px</li>',$eol;
		echo $tab5,'</ul>',$eol;
		echo $tab5,'<form action="./avatar.php" method="post" enctype="multipart/form-data" onsubmit="return validarAvatar(this,',$miaudio['avatar']['maxFileSize'],');">',$eol;
		echo $tab6,'<p><input type="file" name="archivo" />',$eol;
		echo $tab6,'<input type="hidden" name="MAX_FILE_SIZE" value="',floor($miaudio['avatar']['maxFileSize']*1024*1024),'" /> <!-- valor en bytes -->',$eol;
		echo $tab6,'<input type="submit" name="subir" value="Subir/modificar" /></p>',$eol;
		echo $tab5,'</form>',$eol;
		echo $tab5,'<p>El tiempo de subida del archivo demorará según el tamaño del mismo y la velocidad a Internet que dispongas.</p>',$eol;
		echo $tab4,'</div>',$eol;
		
		echo $tab4,'<h3>Eliminar cuenta</h3>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab6,'<p>Al borrar el usuario automáticamente se borrará toda la información relacionada con él, como archivos de audio, listas de reproducción o grupos.</p>',$eol;
		echo $tab5,'<form action="',$_SERVER['PHP_SELF'],'" method="post" onsubmit="return validarDel(this);">',$eol;
		echo $tab6,'<p>',$eol;
		echo $tab7,'Contraseña actual <input type="password" name="pass" />',$eol;
		echo $tab7,'<input type="hidden" name="user" value="',$_SESSION['username'],'" />',$eol;
		echo $tab7,'<input type="submit" name="del" value="Eliminar" />',$eol;
		echo $tab6,'</p>',$eol;
		echo $tab5,'</form>',$eol;
		echo $tab4,'</div>',$eol;
		
	}
	
	require_once("./inc/web2.inc.php");
		
?>