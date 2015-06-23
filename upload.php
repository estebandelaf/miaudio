<?php

	require_once("./inc/web1.inc.php");
	
	if(!$login) {
		echo $tab4,'<h2>Error: ¿estás registrado?</h2>',$eol;
		echo $tab4,'<div class="box">',$eol;
		echo $tab5,'<p>Para poder subir un archivo de audio al sitio debes ser un usuario registrado. Por favor <a href="./signup.php">regístrate aquí</a> para usar los servicios de la web. Si ya eres un usuario registrado puedes autentificarte ahora:</p>',$eol;
		echo $tab5,'<form action="./login.php" method="post" onsubmit="return validarLogin(this);">',$eol;
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
	} else {
	
		set_time_limit($miaudio['timeLimit']); // modifica valor de max_execution_time
	
		if (isset($_POST['subir'])) {
			if (!empty($_FILES['archivo']['name'])) {
				// verificar el formato del archivo
				if (validarMimeType($_FILES['archivo']['type'])) {
					if($_FILES['archivo']['size']<=($miaudio['maxFileSize']*1024*1024)) {
						// leer del archvio temporal los datos de este
						$binario_nombre_temporal = $_FILES['archivo']['tmp_name'] ; 
						$binario_contenido = addslashes(fread(fopen($binario_nombre_temporal, "rb"), filesize($binario_nombre_temporal))); 
						$binario_tipo = $_FILES['archivo']['type'];
						$binario_peso = $_FILES['archivo']['size'];
						// definir el nombre del archivo
						if(isset($_POST['fileName']) && $_POST['fileName']!="") {
							$extension = end(explode(".", $_FILES['archivo']['name']));
							$binario_nombre = $_POST['fileName'].".".$extension;
						} 
						else $binario_nombre = $_FILES['archivo']['name'];
						// obtener tag y actualizar tabla de tags
						$tags = explode(" ",$consultasSql->proteger($_POST['tags']));
						for($i=0;$i<count($tags);$i++) {
							if($consultasSql->contar("tags","tag",$tags[$i])) {
								$sql = $consultasSql->consulta("SELECT files FROM ".$miaudio['mysql']['prefix']."tags WHERE tag = '".$tags[$i]."'");
								$row = mysql_fetch_array($sql);
								$tagFiles = $row['files'] + 1;
								$consultasSql->consulta("UPDATE ".$miaudio['mysql']['prefix']."tags SET files = '".$tagFiles."' WHERE tag = '".$tags[$i]."'");
							} else {
								$consultasSql->consulta("INSERT INTO ".$miaudio['mysql']['prefix']."tags (tag,files) VALUES ('".$tags[$i]."','1')");
							}
						}
						//insertamos los datos en la base de datos
						$discId = isset($_POST['discId']) ? $consultasSql->proteger($_POST['discId']) : 'NULL';
						$groupId = isset($_POST['groupId']) ? $consultasSql->proteger($_POST['groupId']) : 'NULL';
						$consultasSql->consulta("INSERT INTO ".$miaudio['mysql']['prefix']."files (object,fileName,size,format,date,userId,tags,about,licenseName,licenseUrl,discId,groupId) VALUES ('$binario_contenido','".$consultasSql->proteger($binario_nombre)."','$binario_peso','$binario_tipo','".$miaudio['realTime']."','".$_SESSION['userId']."','".$consultasSql->proteger($_POST['tags'])."','".$consultasSql->proteger($_POST['about'])."','".$consultasSql->proteger($_POST['licenseName'])."','".$consultasSql->proteger($_POST['licenseUrl'])."',$discId,$groupId)");
						echo $tab4,'<h2>Archivo subido correctamente</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab5,'<p>Se ha agregado el archivo a la base de datos.</p>',$eol;
					} else {
						echo $tab4,'<h2>Error: tamaño máximo excedido, archivo tipo mime ',$_FILES['archivo']['type'],'</h2>',$eol;
						echo $tab4,'<div class="box">',$eol;
						echo $tab5,'<p>El archivo subido tiene un tamaño de ',round((($_FILES['archivo']['size']/1024)/1024),2),' MB siendo mayor a ',$miaudio['maxFileSize'],' MB que es el límite.</p>',$eol;
					}	
				} else {
					echo $tab4,'<h2>Error: tipo mime ',$_FILES['archivo']['type'],' inválido</h2>',$eol;
					echo $tab4,'<div class="box">',$eol;
					echo $tab5,'<p>Formato de archivo inválido, solo se permiten las siguientes extensiones: ',showArray($miaudio['extOk']),'</p>',$eol;
					echo $tab5,'<p>Adicionalmente solo se permiten los siguientes tipos mime: ',showArray($miaudio['mimeType']),'</p>',$eol;
				}
			} else {
				echo $tab4,'<h2>Error: no hay archivo</h2>',$eol;
				echo $tab4,'<div class="box">',$eol;
				echo $tab5,'<p>No se ha enviado correctamente el archivo</p>',$eol;
			}
		} else {
			echo $tab4,'<h2>Subir archivo de audio</h2>',$eol;
			echo $tab4,'<div class="box">',$eol;
			echo $tab5,'<p>El archivo a subir no debe tener un tamaño mayor a ',$miaudio['maxFileSize'],' MB.</p>',$eol;
			echo $tab5,'<p>Solo el campo archivo es obligatorio, pero se recomienda completar el formulario para poder entregar información adecuada y facilitar la búsqueda.</p>',$eol;
			echo $tab5,'<form action="',$_SERVER['PHP_SELF'],'" method="post" enctype="multipart/form-data" onsubmit="return validarUpload(this,',$miaudio['maxFileSize'],');" name="uploadForm">',$eol;
			echo $tab6,'<table>',$eol;
			echo $tab7,'<tr><td>Nombre ',helpBox("name","Nombre del archivo de audio, si no se indica se usará el nombre del archivo que se suba."),'</td><td><input type="text" name="fileName" size="56" maxlength="50" /></td></tr>',$eol;
			echo $tab7,'<tr><td>Tags ',helpBox("tags","Una tag es una o varias palabras que dan información rápida para clasificar los archivos de audio. Separarlos mediante un espacio, por ejemplo: rock 80s chile"),'</div></td><td><input type="text" name="tags" size="56" maxlength="250" /></td></tr>',$eol;
			echo $tab7,'<tr><td>Descripción ',helpBox("about","Una pequeña descripción del archivo de sonido."),'</td><td><textarea name="about" rows="5" cols="42"></textarea></td></tr>',$eol;
			echo $tab7,'<tr><td>Disco ',helpBox("disco","Los discos son subcategorías de los grupos, tal como si de discos musicales de bandas se tratase. Si seleccionas un disco no es necesario indicar el grupo. Más info en el link grupos del menú superior."),'</td><td>',$eol;
			echo $tab8,'<select tabindex="1" name="discId" style="width: 363px;" onchange="disableForm(\'uploadForm\',this.value,\'groupId\');">',$eol;
			echo $tab9,'<option value="0">Seleccionar un disco o subcategoría</option>',$eol;
			$sql = $consultasSql->consulta("SELECT id,shortName FROM ".$miaudio['mysql']['prefix']."groups WHERE userId = '".$_SESSION['userId']."' ORDER BY name ASC");
			while($row = mysql_fetch_array($sql)) {
				$sql2 = $consultasSql->consulta("SELECT id,name FROM ".$miaudio['mysql']['prefix']."discs WHERE groupId = '".$row['id']."' ORDER BY name ASC");
				while($row2 = mysql_fetch_array($sql2)) {
					echo $tab9,'<option value="',$row2['id'],'">',$row['shortName'],' - ',$row2['name'],'</option>',$eol;
				}
			}
			echo $tab8,'</select>',$eol;
			echo $tab7,'</td></tr>',$eol;
			echo $tab7,'<tr><td>Grupo ',helpBox("grupo","Los grupos son una forma de clasificar los archivos de audio que tu subas, puedes encontrar más información y como crearlos en el link grupos del menú superior."),'</td><td>',$eol;
			echo $tab8,'<select tabindex="1" name="groupId" style="width: 363px;" onchange="disableForm(\'uploadForm\',this.value,\'discId\');">',$eol;
			echo $tab9,'<option value="0">Seleccionar un grupo de audio o banda musical</option>',$eol;
			$sql = $consultasSql->consulta("SELECT id,name FROM ".$miaudio['mysql']['prefix']."groups WHERE userId = '".$_SESSION['userId']."' ORDER BY name ASC");
			while($row = mysql_fetch_array($sql)) {
				echo $tab9,'<option value="',$row['id'],'">',$row['name'],'</option>',$eol;
			}
			echo $tab8,'</select>',$eol;
			echo $tab7,'</td></tr>',$eol;
			echo $tab7,'<tr><td>Licencia ',helpBox("license","La licencia indicará a los usuarios que pueden hacer con el archivo de audio que subas, si no indicas una licencia quedará por defecto con una Licencia Creative Commons Atribución-No Comercial-Sin Obras Derivadas 2.0 Chile, ver términos y condiciones de uso para más información. Indica aquí el nombre de la licencia y una página web donde obtener información."),'</td><td><input type="text" name="licenseName" size="24" maxlength="250"/> url: <input type="text" name="licenseUrl" size="24" maxlength="250"/></td></tr>',$eol;
			echo $tab7,'<tr><td>Archivo ',helpBox("file","Extensiones permitidas: ".showArray($miaudio['extOk'])."<br />Tipos mime válidos: ".showArray($miaudio['mimeType'])."<br />Tamaño máximo del archivo a subir: ".$miaudio['maxFileSize']." MB<br />No hay límite en la duración."),'</td><td><input type="file" name="archivo" size="41" /></td></tr>',$eol;
			echo $tab7,'<tr><td></td><td><input type="hidden" name="MAX_FILE_SIZE" value="',floor($miaudio['maxFileSize']*1024*1024),'" /> <!-- valor en bytes --></td></tr>',$eol;
			echo $tab7,'<tr><td></td><td><input type="submit" name="subir" value="Subir" /></td></tr>',$eol;
			echo $tab6,'</table>',$eol;
			echo $tab5,'</form>',$eol;
			echo $tab5,'<p>El tiempo de subida del archivo demorará según el tamaño del mismo y la velocidad a Internet que dispongas.</p>',$eol;
		}

		echo $tab4,'</div>',$eol;
	
	}

	require_once("./inc/web2.inc.php");

?>
