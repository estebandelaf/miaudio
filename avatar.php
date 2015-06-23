<?php 

	if((!isset($_POST['subir']) && !isset($_GET['uid'])))
		header("location: ./error.php?error=4&file=".$_SERVER['PHP_SELF']);
	
	require_once("./inc/config.inc.php");
	
	set_time_limit($miaudio['timeLimit']); // modifica valor de max_execution_time
	
	if ($login && isset($_POST['subir'])) {
		require_once("./inc/web1.inc.php");
		if (!empty($_FILES['archivo']['name'])) {
			// verificar el formato del archivo
			if (validarAvatarMimeType($_FILES['archivo']['type'])) {
				$tam = getimagesize($_FILES['archivo']['tmp_name']);
				if($tam[0]<=100 && $tam[1]<=100) {
					if($_FILES['archivo']['size']<=($miaudio['avatar']['maxFileSize']*1024*1024)) {
						// leer del archvio temporal los datos de este
						$binario_nombre_temporal = $_FILES['archivo']['tmp_name']; 
						$binario_contenido = addslashes(fread(fopen($binario_nombre_temporal, "rb"), filesize($binario_nombre_temporal))); 
						$binario_nombre = $_FILES['archivo']['name'];
						$binario_tipo = $_FILES['archivo']['type'];
						$binario_peso = $_FILES['archivo']['size'];
						//insertamos los datos en la base de datos
						$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."users SET avatar = '$binario_contenido', avatarFileName = '$binario_nombre', avatarFormat = '$binario_tipo', avatarSize = '$binario_peso' WHERE id = '".$_SESSION['userId']."'");
						echo $tab4,'<h2>Archivo subido correctamente</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab5,'<p>Se ha modificado el avatar del usuario ',$_SESSION['username'],'.</p>',$eol;
					} else {
						echo $tab4,'<h2>Error: tamaño máximo excedido, archivo tipo mime ',$_FILES['archivo']['type'],'</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab5,'<p>El archivo subido tiene un tamaño de ',round((($_FILES['archivo']['size']/1024)/1024),2),' MB siendo mayor a ',$miaudio['avatar']['maxFileSize'],' MB que es el límite.</p>',$eol;
					}
				} else {
					echo $tab4,'<h2>Error: alto y ancho máximo excedido</h2>',$eol;
					echo $tab4,'<div class="box">',$eol;
					echo $tab5,'<p>El archivo subido tiene un alto y/o ancho que ha superado el máximo definido a 100x100px.</p>',$eol;
				}	
			} else {
				echo $tab4,'<h2>Error: tipo mime ',$_FILES['archivo']['type'],' inválido</h2>',$eol;
				echo $tab4,'<div class="box">',$eol;
				echo $tab5,'<p>Formato de archivo inválido, solo se permiten las siguientes extensiones: ',showArray($miaudio['avatar']['extOk']),'</p>',$eol;
				echo $tab5,'<p>Adicionalmente solo se permiten los siguientes tipos mime: ',showArray($miaudio['avatar']['mimeType']),'</p>',$eol;
			}
		} else {
			echo $tab4,'<h2>Error: no hay archivo</h2>',$eol;
			echo $tab4,'<div class="box">',$eol;
			echo $tab5,'<p>No se ha enviado correctamente el archivo</p>',$eol;
		}
		echo $tab4,'</div>',$eol;
		require_once("./inc/web2.inc.php");
	} else if(isset($_GET['uid'])) {
		require_once("./inc/config.inc.php");
		$sql = $consultasSql->consulta("SELECT avatar,avatarFileName,avatarFormat,avatarSize FROM ".$miaudio['mysql']['prefix']."users WHERE id='".$consultasSql->proteger($_GET['uid'])."'") or $consultasSql->error();
		$row = mysql_fetch_array($sql);
		$consultasSql->cerrar();
		header("Content-type: ".$row['avatarFormat']);
		header("Content-length: ".$row['avatarSize']);
		header("Content-Disposition: attachment; filename=\"".$row['avatarFileName']."\"");
		echo $row['avatar'];
	}

?>